<?php

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST["name"]) &&
   isset($_POST["phone"]) && 
   isset($_POST["message"]) && 
   isset($_POST["checkbox-read"]) && 
   isset($_POST["checkbox-agree"])) {

    $name = htmlspecialchars($_POST["name"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $message = htmlspecialchars($_POST["message"]);
    $checkbox_read = $_POST["checkbox-read"];
    $checkbox_agree = $_POST["checkbox-agree"];

    require 'PHPMailer.php';
    require 'SMTP.php';
    require 'config.php';

    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';

    // Настройки SMTP
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0;

    $mail->Host = $Host;
    $mail->Username = $Username;
    $mail->Password = $Password;

    $mail->Port = 465;

    // От кого
    $mail->From = $Username;
    $mail->FromName = $Username;

    // Кому
    $mail->addAddress($To);

    if ($cc) {
      $mail->addCC($cc);
    }

    // Тема письма
    $mail->Subject = 'Сообщение с сайта mirext.ru';

    $mail->isHTML(true);

    if (strlen($name) >= 3 &&
      strlen($name) <= 50 &&
      strlen($phone) == 18 && 
      strlen($message) >= 3 &&
      strlen($message) <= 100 &&
      $checkbox_read == "on" && 
      $checkbox_agree == "on") {

        // Тело письма
        $mail->Body = "Имя: $name<br> Телефон: $phone<br> Сообщение: $message<br>";
        $mail->AltBody = "Имя: $name\r\n Телефон: $phone\r\n Сообщение: $message\r\n";

        $mail->send();
    }

    $mail->smtpClose();

} else {
    header("Location: /");
    exit;
}

?>