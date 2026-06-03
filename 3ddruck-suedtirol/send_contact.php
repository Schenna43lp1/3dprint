<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); exit('Method Not Allowed');
}

$submitted_token = $_POST[CSRF_TOKEN_NAME] ?? '';
if (!verify_csrf_token($submitted_token)) {
    redirect_err('Ungültige Anfrage.');
}

if (!empty($_POST['website'])) {
    redirect_ok();
}

$now     = time();
$name    = sanitize($_POST['name']    ?? '');
$email   = sanitize($_POST['email']   ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');
$privacy = !empty($_POST['privacy']);

if (mb_strlen($name) < 2)                       redirect_err('Name fehlt.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL))  redirect_err('E-Mail ungültig.');
if (mb_strlen($subject) < 3)                     redirect_err('Betreff fehlt.');
if (mb_strlen($message) < 10)                    redirect_err('Nachricht zu kurz.');
if (!$privacy)                                   redirect_err('Datenschutz nicht akzeptiert.');

$mail_subject = '=?UTF-8?B?' . base64_encode('[Kontakt] ' . $subject) . '?=';
$body = "Von: {$name} <{$email}>\n\n{$message}\n\n---\nGesendet: " . date('Y-m-d H:i');
$headers  = "From: no-reply@3ddruck-suedtirol.it\r\nReply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

mail(SITE_EMAIL, $mail_subject, $body, $headers);

unset($_SESSION[CSRF_TOKEN_NAME]);
redirect_ok();

function redirect_ok(): never {
    header('Location: /contact.php?sent=1'); exit;
}
function redirect_err(string $msg): never {
    header('Location: /contact.php?error=' . urlencode($msg)); exit;
}
