<?php


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   header('Content-Type: application/json');
   echo json_encode([
      "error" => "method not allowed"
   ], JSON_PRETTY_PRINT);
   exit;
}

if (isset($_POST["height"]) &&
   isset($_POST["lenght"]) &&
   isset($_POST["width"]) && 
   isset($_POST["thickness"])) {

   $L = $_POST["height"]; // высота изделия
   $H = $_POST["width"]; // ширина изделия
   $F = $_POST["lenght"]; // длина фальца
   $S = $_POST["thickness"]; // толщина изделия

   $P = 0.922; // плотность ПВД

   require 'config.php'; // подключение файла с конфигом

   // Устанавливаю соединение
   $conn = mysqli_connect($servername, $username, $password, $database);

   // Устанавливаю кодировку
   mysqli_query($conn,'SET names utf8;');

   // Проверяю соединение
   if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
   }

   // Формирую запрос
   $sql = "SELECT * FROM prices WHERE id = 1";

   $result = mysqli_query($conn, $sql);

   if ($result) {
      $row = mysqli_fetch_array($result);
   } else {
      die("Result failed");
   }

   mysqli_close($conn);

   $ss = $row["ss"]; // стоимость сырья
   $sp = $row["sp"]; // стоимость переработки
   $sd = $row["sd"]; // стоимость доставки
   $sk = $row["sk"]; // стоимость добавки концетратов

   $sm = ($ss + $sp + $sd + $sk) * $L * ($H + $F * 2) * 2 * $S * $P / 1000000000; // стоимость изделия
   $sm = round($sm, 1); // округление стоимости изделия

   $price_per_kilo = $ss + $sp + $sd + $sk; // цена за кг

   $product_weight = ($H + $F * 2) * 2 * $L * $S * $P / 1000000000; // вес изделия
   $product_weight = round($product_weight, 3); // округление вес изделия

   $product_area = ($H + $F * 2) * 2 * $L / 1000000; // площадь изделия
   $product_area = round($product_area, 3); // округление площади изделия

   $price_per_square_meter = $sm / $product_area; // цена за метр квадратный
   $price_per_square_meter = round($price_per_square_meter, 2); // округление цены за метр квадратный

   header('Content-Type: application/json');

   echo json_encode([
      // "sm" => $sm,
      "price_per_kilo" => $price_per_kilo,
      "product_weight" => $product_weight,
      "price_per_square_meter" => $price_per_square_meter,
      "product_area" => $product_area
   ], JSON_PRETTY_PRINT);

} else {
   header('Content-Type: application/json');
   echo json_encode([
      "error" => "something went wrong"
   ], JSON_PRETTY_PRINT);
}

?>