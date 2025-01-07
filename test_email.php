<?php
include 'send_email.php';

$destinatar = 'proiectphp11@gmail.com';
$subiect = 'Test Email Cinematograf';
$mesaj = '<h1>Bună ziua!</h1><p>Acesta este un email de test trimis din aplicația Cinematograf.</p>';

$result = send_email($destinatar, $subiect, $mesaj);

echo $result;
?>
