<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'adewale2024@gmail.com';
        $mail->Password = 'adewale.com';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('adewale2024@gmail.com', 'Smartteller Team');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
