<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

/* ── Only accept POST ── */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

/* ── CSRF ── */
$submitted_token = $_POST[CSRF_TOKEN_NAME] ?? '';
if (!verify_csrf_token($submitted_token)) {
    redirect_error('Ungültige Anfrage. Bitte Seite neu laden und erneut versuchen.');
}

/* ── Honeypot spam check ── */
if (!empty($_POST['website'])) {
    redirect_success(); // silently succeed to confuse bots
}

/* ── Rate limiting (simple session-based) ── */
$now = time();
if (isset($_SESSION['last_request']) && ($now - $_SESSION['last_request']) < 60) {
    redirect_error('Bitte warte mindestens 1 Minute zwischen Anfragen.');
}

/* ── Sanitize & validate fields ── */
$name        = sanitize($_POST['name']        ?? '');
$email       = sanitize($_POST['email']       ?? '');
$phone       = sanitize($_POST['phone']       ?? '');
$material    = sanitize($_POST['material']    ?? '');
$color       = sanitize($_POST['color']       ?? '');
$quantity    = (int) ($_POST['quantity']      ?? 1);
$description = sanitize($_POST['description'] ?? '');
$privacy     = !empty($_POST['privacy']);

$errors = [];

if (mb_strlen($name) < 2 || mb_strlen($name) > 100)            $errors[] = 'Name ungültig.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))                  $errors[] = 'E-Mail-Adresse ungültig.';
if (mb_strlen($email) > 200)                                     $errors[] = 'E-Mail-Adresse zu lang.';
if (!empty($phone) && !preg_match('/^[+\d\s\-().]{5,30}$/', $phone)) $errors[] = 'Telefonnummer ungültig.';
if (empty($material))                                            $errors[] = 'Material fehlt.';
if (empty($color))                                               $errors[] = 'Farbe fehlt.';
if ($quantity < 1 || $quantity > 9999)                           $errors[] = 'Ungültige Stückzahl.';
if (mb_strlen($description) < 10 || mb_strlen($description) > 5000) $errors[] = 'Beschreibung fehlt oder zu lang.';
if (!$privacy)                                                   $errors[] = 'Datenschutzerklärung nicht akzeptiert.';

if ($errors) {
    redirect_error(implode(' ', $errors));
}

/* ── File upload ── */
$upload_info = '';
$uploaded_path = null;

if (isset($_FILES['stl_file']) && $_FILES['stl_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file  = $_FILES['stl_file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $upload_err_msg = [
            UPLOAD_ERR_INI_SIZE   => 'Datei zu groß (PHP-Limit).',
            UPLOAD_ERR_FORM_SIZE  => 'Datei zu groß (Formular-Limit).',
            UPLOAD_ERR_PARTIAL    => 'Upload unvollständig.',
            UPLOAD_ERR_NO_TMP_DIR => 'Kein temporäres Verzeichnis.',
            UPLOAD_ERR_CANT_WRITE => 'Schreibfehler.',
            UPLOAD_ERR_EXTENSION  => 'Upload durch PHP-Erweiterung blockiert.',
        ];
        redirect_error($upload_err_msg[$file['error']] ?? 'Upload-Fehler.');
    }

    if ($file['size'] > UPLOAD_MAX_SIZE) {
        redirect_error('Datei zu groß. Maximum: 50 MB.');
    }

    $original_name = basename($file['name']);
    $extension     = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    if (!in_array($extension, ALLOWED_EXTENSIONS, true)) {
        redirect_error('Ungültiger Dateityp. Erlaubt: STL, 3MF, OBJ, ZIP.');
    }

    /* Double extension check (e.g. evil.php.stl) */
    $dangerous = ['php', 'php3', 'php4', 'php5', 'phtml', 'pl', 'py', 'sh', 'cgi', 'asp', 'aspx', 'js', 'exe'];
    $parts = explode('.', $original_name);
    foreach ($parts as $part) {
        if (in_array(strtolower($part), $dangerous, true) && strtolower($part) !== $extension) {
            redirect_error('Dateiname enthält unzulässige Erweiterung.');
        }
    }

    /* Validate MIME via finfo */
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $detected = $finfo->file($file['tmp_name']);

    /* STL/3MF/OBJ are often detected as octet-stream or text/plain — we trust the extension + size */
    $allowed_mime_prefix = ['application/', 'model/', 'text/plain', 'image/'];
    $mime_ok = false;
    foreach ($allowed_mime_prefix as $prefix) {
        if (str_starts_with($detected, $prefix)) { $mime_ok = true; break; }
    }
    if (!$mime_ok) {
        redirect_error('Ungültiger Dateityp (MIME-Prüfung).');
    }

    /* Save with random name */
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0750, true);
    }
    $safe_name     = bin2hex(random_bytes(16)) . '.' . $extension;
    $uploaded_path = UPLOAD_DIR . $safe_name;

    if (!move_uploaded_file($file['tmp_name'], $uploaded_path)) {
        redirect_error('Datei konnte nicht gespeichert werden. Bitte versuche es erneut.');
    }

    $upload_info = "Datei: {$original_name} (gespeichert als {$safe_name}, " . number_format($file['size'] / 1024, 1) . " KB)";
}

/* ── Build email ── */
$subject = '=?UTF-8?B?' . base64_encode('[3D Druck Südtirol] Neue Anfrage von ' . $name) . '?=';

$body = <<<TEXT
Neue Druckanfrage von der Website 3ddruck-suedtirol.it
=======================================================

KONTAKTDATEN
-----------
Name:     {$name}
E-Mail:   {$email}
Telefon:  {$phone}

DRUCKDETAILS
-----------
Material:    {$material}
Farbe:       {$color}
Stückzahl:   {$quantity}

BESCHREIBUNG
-----------
{$description}

UPLOAD
------
{$upload_info}

---
Gesendet am: {$now}
IP: [anonymisiert]
TEXT;

$headers  = "From: no-reply@3ddruck-suedtirol.it\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

$mail_sent = mail(SITE_EMAIL, $subject, $body, $headers);

/* ── Confirmation to customer ── */
$confirm_subject = '=?UTF-8?B?' . base64_encode('Deine Anfrage bei 3D Druck Südtirol') . '?=';
$confirm_body = <<<TEXT
Hallo {$name},

vielen Dank für deine Anfrage bei 3D Druck Südtirol!

Wir haben deine Anfrage erhalten und werden uns innerhalb von 24 Stunden bei dir melden.

DEINE ANGABEN
-------------
Material:  {$material}
Farbe:     {$color}
Stückzahl: {$quantity}

NACHRICHT
---------
{$description}

Bei Fragen erreichst du uns unter: info@3ddruck-suedtirol.it

Viele Grüße,
Dein 3D Druck Südtirol Team
--
3ddruck-suedtirol.it
TEXT;

$confirm_headers  = "From: info@3ddruck-suedtirol.it\r\n";
$confirm_headers .= "MIME-Version: 1.0\r\n";
$confirm_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
mail($email, $confirm_subject, $confirm_body, $confirm_headers);

/* ── Rate limit stamp ── */
$_SESSION['last_request'] = $now;

/* ── Invalidate CSRF token ── */
unset($_SESSION[CSRF_TOKEN_NAME]);

if ($mail_sent) {
    redirect_success();
} else {
    /* Upload saved but mail failed — still treat as partial success */
    redirect_success();
}

/* ── Helpers ── */
function redirect_success(): never {
    header('Location: /request.php?success=1');
    exit;
}

function redirect_error(string $msg): never {
    header('Location: /request.php?error=' . urlencode($msg));
    exit;
}
