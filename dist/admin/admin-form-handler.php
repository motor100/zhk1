<?php

$ss = $_POST['ss'];
$sp = $_POST['sp'];
$sd = $_POST['sd'];
$sk = $_POST['sk'];

if (isset($ss) && isset($sp) && isset($sd) && isset($sk)) {

  // Записываю в базу новые цены
  require_once ("config.php");

  $conn = mysqli_connect($servername, $username, $password, $database);

  mysqli_query($conn,'SET names utf8;');

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "UPDATE prices SET ss = '$ss', sp = '$sp', sd = '$sd', sk = '$sk' WHERE id = 1";

  if (!mysqli_query($conn, $sql)) {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  
} else {
  header("HTTP/1.1 500 Internal Server Error");
}

header("Location: check.php");
exit;

?>