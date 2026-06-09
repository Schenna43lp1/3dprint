<?php
define('SITE_NAME', '3D Druck Südtirol');
define('SITE_URL', 'https://www.3ddruck-suedtirol.it');
define('SITE_EMAIL', 'info@3ddruck-suedtirol.it');
define('SITE_PHONE', '+39 XXX XXX XXXX');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_MAX_SIZE', 50 * 1024 * 1024); // 50MB
define('ALLOWED_EXTENSIONS', ['stl', '3mf', 'obj', 'zip']);
// Exact MIME whitelist — no broad prefixes
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

// API-Key für die Desktop-App — UNBEDINGT ändern!
// Neuen Key erzeugen: php -r "echo bin2hex(random_bytes(32));"
define('ADMIN_API_KEY', 'CHANGE_ME_generate_with_php_bin2hex_random_bytes_32');

// Admin login.
// Default password: "3ddruck-admin" — UNBEDINGT ändern!
// Neuen Hash erzeugen:  php -r "echo password_hash('dein-passwort', PASSWORD_DEFAULT);"
define('ADMIN_USER', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$12$25WnyyA4SoEGqRPooxHejO69i8SuOfAkGGj059w2imNS573GYahwy');
define('REQUEST_LOG', __DIR__ . '/../uploads/requests.json');

function is_admin(): bool {
    return !empty($_SESSION['admin_logged_in']);
}

function require_admin(): void {
    if (!is_admin()) {
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

// Verhindert E-Mail-Header-Injection (CRLF-Injection)
function sanitize_email_header(string $str): string {
    return trim(str_replace(["\r", "\n", "\x00"], '', $str));
}

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.gc_maxlifetime', '3600');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}
