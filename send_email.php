<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 

function send_email($to, $subject, $message_body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'liviu.andrei@cnimslatina.ro'; 
        $mail->Password = 'fhen gudv pjou uwds '; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('proiectphp11@gmail.com', 'Cinematograf');
        $mail->addAddress('proiectphp11@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message_body;

        $mail->send();
        return "Mesaj trimis cu succes către echipa,va multumim!";
    } catch (Exception $e) {
        return "Mesajul nu a fost trimis. Eroare: {$mail->ErrorInfo}";
    }
}
?>
