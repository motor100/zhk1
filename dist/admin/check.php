<?
// Скрипт проверки

// Подключаюсь к базе
require_once ("config.php");

// Устанавливаю соединение
$link = mysqli_connect($servername, $username, $password, $database);

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    $query = mysqli_query($link, "SELECT *,INET_NTOA(user_ip) AS user_ip FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
    $userdata = mysqli_fetch_assoc($query);

    if(($userdata['user_hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] !== $_COOKIE['id']) or (($userdata['user_ip'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['user_ip'] !== "0"))) {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
        print "Хм, что-то не получилось";
    } else { ?>
    

<!DOCTYPE html>
  <html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/admin.css">
    <link rel="icon" type="image/svg+xml" href="images/favicon.svg" sizes="any">
    <meta name="robots" content="noindex, nofollow">
    <title>Панель управления</title>
  </head>
  <body>
    <header class="header mt-1 mb-5">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <div class="title text-center">
              <h3>Панель управления</h3>
            </div>
          </div>
          <div class="col-md-2">
            <a href="/admin/logout.php">Выйти</a>
          </div>
        </div>
      </div>
    </header>
    <div class="content">
      <div class="container">
        <form action="admin-form-handler.php" class="form" method="post">
          <div class="materials mb-5">
            <h5 class="fw-bold">Материалы</h5>
            <?php

            // Подключаюсь к базе
            require_once ("config.php");

            // Устанавливаю соединение
            $conn = mysqli_connect($servername, $username, $password, $database);

            // Устанавливаю кодировку
            mysqli_query($conn,'SET names utf8;');

            // Проверяю соединение
            if (!$conn) {
              die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM prices WHERE id = 1";

            $result = mysqli_query($conn, $sql);

            $row = mysqli_fetch_array($result);

            mysqli_close($conn);

            $ss = $row["ss"]; // стоимость сырья
            $sp = $row["sp"]; // стоимость переработки
            $sd = $row["sd"]; // стоимость доставки
            $sk = $row["sk"]; // стоимость добавки концетратов

            ?>

            <table class="table mb-5">
              <tr>
                <td>стоимость сырья</td>
                <td class="text-end">
                  <input type="number" name="ss" class="material-input" min="1" max="10000" value="<?php echo $ss; ?>">
                </td>
              </tr>
              <tr>
                <td>стоимость переработки</td>
                <td class="text-end">
                  <input type="number" name="sp" class="material-input" min="1" max="10000" value="<?php echo $sp; ?>">
                </td>
              </tr>
              <tr>
                <td>стоимость доставки</td>
                <td class="text-end">
                  <input type="number" name="sd" class="material-input" min="1" max="10000" value="<?php echo $sd; ?>">
                </td>
              </tr>
              <tr>
                <td>стоимость добавки концетратов</td>
                <td class="text-end">
                  <input type="number" name="sk" class="material-input" min="1" max="10000" value="<?php echo $sk; ?>">
                </td>
              </tr>
            </table>

            <div class="btn-wrapper text-end">
              <input type="submit" class="btn btn-primary" value="Сохранить">
            </div>
            
          </div>
        </form>
      </div>
    </div>

  </body>
  </html>

    <?php
    mysqli_close($link);
    }
} else {
  header("Location: /");
  exit;
}
?>