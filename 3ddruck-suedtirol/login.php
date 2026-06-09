<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

// Bereits eingeloggt? → direkt zum Dashboard
if (is_admin()) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF
    if (!verify_csrf_token($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $error = 'Ungültige Anfrage. Bitte Seite neu laden.';
    } else {
        // Brute-Force-Schutz: max. 5 Versuche, dann 5 Minuten Sperre
        $attempts = $_SESSION['login_attempts'] ?? 0;
        $lockUntil = $_SESSION['login_lock_until'] ?? 0;

        if ($lockUntil > time()) {
            $wait = (int) ceil(($lockUntil - time()) / 60);
            $error = "Zu viele Versuche. Bitte in {$wait} Minute(n) erneut versuchen.";
        } else {
            $user = sanitize($_POST['username'] ?? '');
            $pass = (string) ($_POST['password'] ?? '');

            if (hash_equals(ADMIN_USER, $user) && password_verify($pass, ADMIN_PASSWORD_HASH)) {
                // Erfolg → Session-Fixation verhindern
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user'] = $user;
                unset($_SESSION['login_attempts'], $_SESSION['login_lock_until'], $_SESSION[CSRF_TOKEN_NAME]);
                header('Location: /dashboard.php');
                exit;
            }

            $attempts++;
            $_SESSION['login_attempts'] = $attempts;
            if ($attempts >= 5) {
                $_SESSION['login_lock_until'] = time() + 300; // 5 Min
            }
            $error = 'Benutzername oder Passwort falsch.';
        }
    }
}

$csrf = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="de" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login · <?= h(SITE_NAME) ?></title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
</head>
<body class="login-page">

<div class="login-wrap">
    <div class="login-card">
        <div class="login-brand">
            <span class="brand-icon"><i class="bi bi-printer-fill"></i></span>
            <span class="brand-text fs-5">3D Druck <span class="brand-accent">Südtirol</span></span>
        </div>
        <h1 class="login-title">Admin-Login</h1>
        <p class="login-sub">Melde dich an, um Anfragen zu verwalten.</p>

        <?php if ($error): ?>
            <div class="alert-custom alert-error-custom mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><?= h($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="post" autocomplete="off" novalidate>
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">

            <div class="mb-3">
                <label class="form-label" for="username">Benutzername</label>
                <div class="input-icon-group">
                    <i class="bi bi-person"></i>
                    <input type="text" class="form-control" id="username" name="username"
                           required autofocus value="<?= h($_POST['username'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label" for="password">Passwort</label>
                <div class="input-icon-group">
                    <i class="bi bi-lock"></i>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button type="button" class="pw-toggle" id="pwToggle" aria-label="Passwort anzeigen">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-box-arrow-in-right"></i> Anmelden
            </button>
        </form>

        <a href="/" class="login-back">
            <i class="bi bi-arrow-left"></i> Zurück zur Website
        </a>
    </div>
</div>

<script>
    document.getElementById('pwToggle')?.addEventListener('click', function () {
        const pw = document.getElementById('password');
        const icon = this.querySelector('i');
        const show = pw.type === 'password';
        pw.type = show ? 'text' : 'password';
        icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
</script>
</body>
</html>
