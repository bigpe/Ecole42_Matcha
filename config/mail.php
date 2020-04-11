<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/mail/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/SMTP.php';

function send_mail($email, $msg)
{
    $ini = include('config.php');

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = $ini['mail']['Host'];
    $mail->Port = $ini['mail']['Port'];
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $ini['mail']['Username'];
    $mail->Password = $ini['mail']['Password'];
    $mail->setFrom($ini['mail']['Address'], $ini['mail']['Name']);
    $mail->addAddress("$email");
    $mail->Subject = $ini['mail']['Subject'];
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