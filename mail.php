<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/mail/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/SMTP.php';

function send_mail($email, $msg)
{
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = "smtp.yandex.ru";
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = 'bigpewm@yandex.ru';
    $mail->Password = 'ship0123';
    $mail->setFrom('bigpewm@yandex.ru', 'Camagru');
    $mail->addAddress("$email");
    $mail->Subject = 'Camagru System Message';
    $mail->msgHTML($msg);
    $mail->AltBody = 'HTML messaging not supported';
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    if ($mail->send())
        return(1);
    else
        return (0);
}