<?php
define('SITE_NAME', '3D Druck Südtirol');
define('SITE_URL', 'https://www.3ddruck-suedtirol.it');
define('SITE_EMAIL', 'info@3ddruck-suedtirol.it');
define('SITE_PHONE', '+39 XXX XXX XXXX');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_MAX_SIZE', 50 * 1024 * 1024); // 50MB
define('ALLOWED_EXTENSIONS', ['stl', '3mf', 'obj', 'zip']);
define('ALLOWED_MIME_TYPES', [
    'application/octet-stream',
    'application/zip',
    'application/x-zip-compressed',
    'model/stl',
    'model/obj',
    'application/sla',
    'text/plain',
]);

define('CSRF_TOKEN_NAME', '_csrf_token');

// Admin auth — change password hash via: php -r "echo password_hash('dein-passwort', PASSWORD_DEFAULT);"
define('ADMIN_PASSWORD_HASH', '$2y$12$placeholderHashChangeThis00000000000000000000000000000');
define('ADMIN_EMAIL', 'admin@3ddruck-suedtirol.it');

function require_admin(): void {
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: /login.php');
        exit;
    }
}

function generate_csrf_token(): string {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function verify_csrf_token(string $token): bool {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) return false;
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function sanitize(string $str): string {
    return trim(strip_tags($str));
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
