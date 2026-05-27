<?php
session_start();

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function current_user_name(): string
{
    return $_SESSION['username'] ?? 'User';
}

function ensure_default_admin(PDO $pdo): void
{
    $count = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $username = getenv('APP_ADMIN_USER') ?: 'admin';
    $password = getenv('APP_ADMIN_PASSWORD') ?: 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
    $stmt->execute([$username, $hash]);
}
?>