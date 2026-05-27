<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; font-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'; upgrade-insecure-requests");
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

$secureCookie = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secureCookie,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

function e(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function is_logged_in(): bool { return isset($_SESSION['user_id']); }
function is_admin(): bool { return !empty($_SESSION['is_admin']); }
function require_login(): void { if (!is_logged_in()) { header('Location: login.php'); exit; } }
function require_admin(): void { require_login(); if (!is_admin()) { http_response_code(403); exit('Kein Zugriff.'); } }
function current_user_name(): string { return $_SESSION['username'] ?? 'User'; }

function migrate_database(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (id SERIAL PRIMARY KEY, username VARCHAR(100) UNIQUE NOT NULL, password_hash TEXT NOT NULL, created_at TIMESTAMP DEFAULT NOW())');
    $pdo->exec('ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin BOOLEAN DEFAULT FALSE');
    $pdo->exec('ALTER TABLE users ADD COLUMN IF NOT EXISTS twofa_enabled BOOLEAN DEFAULT FALSE');
    $pdo->exec('ALTER TABLE users ADD COLUMN IF NOT EXISTS twofa_secret VARCHAR(64)');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">'; }
function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403); exit('Ungültiger CSRF Token.');
    }
}

function base32_encode_custom(string $data): string
{
    $alphabet='ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; $binary='';
    foreach(str_split($data) as $char) $binary.=str_pad(decbin(ord($char)),8,'0',STR_PAD_LEFT);
    $encoded=''; foreach(str_split($binary,5) as $chunk) $encoded.=$alphabet[bindec(str_pad($chunk,5,'0',STR_PAD_RIGHT))];
    return $encoded;
}
function base32_decode_custom(string $secret): string
{
    $alphabet='ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; $secret=strtoupper(preg_replace('/[^A-Z2-7]/','',$secret)); $binary='';
    foreach(str_split($secret) as $char){$pos=strpos($alphabet,$char); if($pos!==false)$binary.=str_pad(decbin($pos),5,'0',STR_PAD_LEFT);} $data='';
    foreach(str_split($binary,8) as $byte) if(strlen($byte)===8)$data.=chr(bindec($byte)); return $data;
}
function generate_totp_secret(): string { return base32_encode_custom(random_bytes(20)); }
function totp_code(string $secret, ?int $timeSlice=null): string
{
    $timeSlice=$timeSlice ?? floor(time()/30); $secretKey=base32_decode_custom($secret); $time=pack('N*',0).pack('N*',$timeSlice);
    $hash=hash_hmac('sha1',$time,$secretKey,true); $offset=ord(substr($hash,-1)) & 0x0F; $truncated=unpack('N',substr($hash,$offset,4))[1] & 0x7FFFFFFF;
    return str_pad((string)($truncated % 1000000),6,'0',STR_PAD_LEFT);
}
function verify_totp(string $secret, string $code): bool
{
    $code=preg_replace('/\D/','',$code); if(strlen($code)!==6)return false; $slice=floor(time()/30);
    for($i=-1;$i<=1;$i++) if(hash_equals(totp_code($secret,$slice+$i),$code)) return true; return false;
}

function ensure_default_admin(PDO $pdo): void
{
    migrate_database($pdo);
    $count=(int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if($count>0) return;
    $username=getenv('APP_ADMIN_USER') ?: 'admin'; $password=getenv('APP_ADMIN_PASSWORD') ?: 'admin123';
    $stmt=$pdo->prepare('INSERT INTO users (username,password_hash,is_admin) VALUES (?,?,true)');
    $stmt->execute([$username,password_hash($password,PASSWORD_DEFAULT)]);
}

function render_header(string $title): void
{
    $dark=($_SESSION['theme'] ?? 'light')==='dark'; $bodyClass=$dark?'bg-dark text-light':'bg-light';
    $cardCss=$dark?'<style>.card,.table{--bs-card-bg:#1f2937;--bs-table-bg:#1f2937;--bs-table-color:#f8f9fa;color:#f8f9fa}.form-control,.form-select,textarea{background:#111827;color:#f8f9fa;border-color:#374151}.form-control::placeholder{color:#9ca3af}</style>':'';
    echo '<!doctype html><html lang="de"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>'.e($title).'</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">'.$cardCss.'</head><body class="'.$bodyClass.'"><nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4"><div class="container"><a class="navbar-brand" href="index.php">3D Print Tracker</a>';
    if(is_logged_in()){ echo '<div class="navbar-nav"><a class="nav-link" href="filament_add.php">Filament</a><a class="nav-link" href="job_add.php">Druckjob</a><a class="nav-link" href="maintenance.php">Wartung</a><a class="nav-link" href="2fa_setup.php">2FA</a>'; if(is_admin()) echo '<a class="nav-link" href="admin_users.php">User</a>'; echo '</div><span class="navbar-text ms-auto me-3">'.e(current_user_name()).'</span><a class="btn btn-outline-warning btn-sm me-2" href="toggle_theme.php">Theme</a><a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>'; }
    echo '</div></nav><main class="container">';
}
function render_footer(): void { echo '</main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>'; }
?>