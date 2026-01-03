<?
// Страница авторизации
$error = '';

// Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

// Подключаюсь к базе
require_once ("config.php");

// Устанавливаю соединение
$link = mysqli_connect($servername, $username, $password, $database);

if(isset($_POST['submit'])) {
    // Вытаскиваем из БД запись, у которой логин равняется введенному
    $query = mysqli_query($link,"SELECT user_id, user_password FROM users WHERE user_login='".mysqli_real_escape_string($link,$_POST['login'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    // Сравниваем пароли
    if($data['user_password'] === md5(md5($_POST['password']))) {
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

        if(!empty($_POST['not_attach_ip']))
        {
            // Если пользователя выбрал привязку к IP
            // Переводим IP в строку
            $insip = ", user_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";
        }

        // Записываем в БД новый хеш авторизации и IP
        mysqli_query($link, "UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'");

        // Ставим куки
        setcookie("id", $data['user_id'], time()+60*60*24*30, "/");
        setcookie("hash", $hash, time()+60*60*24*30, "/", "", "", true); // httponly !!!

        // Переадресовываем браузер на страницу проверки нашего скрипта
        header("Location: check.php");
        exit();
    } else {
        // print "Вы ввели неправильный логин/пароль";
      $error = "Вы ввели неправильный логин/пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
  <link rel="stylesheet" href="/admin/css/admin.css">
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg" sizes="any">
  <meta name="robots" content="noindex, nofollow">
  <title>Login</title>
</head>
<body>
  <div class="login-page">
    <section class="vh-100 section">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-2-strong form-wrapper">
              <form action="login.php" class="form" method="POST">
                <div class="card-body p-5 text-center">

                  <h3 class="mb-5">Вход</h3>

                  <div class="form-outline mb-4">
                    <input type="text" name="login" id="typeEmailX-2" class="form-control form-control-lg" required>
                    <label class="form-label" for="typeEmailX-2">Логин</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" name="password" id="typePasswordX-2" class="form-control form-control-lg" minlength="8" maxlength="20" required>
                    <label class="form-label" for="typePasswordX-2">Пароль</label>
                  </div>

                  <input type="checkbox" name="not_attach_ip" checked style="display: none;">

                  <!-- Checkbox -->
                  <!-- <div class="form-check d-flex justify-content-start mb-4">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      value=""
                      id="form1Example3"
                    />
                    <label class="form-check-label" for="form1Example3"> Remember password </label>
                  </div> -->

                  <!-- <button class="btn btn-primary btn-lg btn-block" type="submit">Войти</button> -->
                  <input name="submit" type="submit" class="btn btn-primary btn-lg btn-block mb-4" value="Войти">

                  <div class="error mb-4">
                    <?php echo $error; ?>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

</body>
</html>