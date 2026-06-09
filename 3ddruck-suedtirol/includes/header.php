<?php
require_once __DIR__ . '/config.php';

/* ── Besucher-Tracking ── */
(function () {
    // Bots und CLI-Aufrufe ignorieren
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if (empty($ua) || preg_match('/bot|crawl|spider|slurp|curl|wget|python|go-http/i', $ua)) return;

    $log  = __DIR__ . '/../../uploads/visitors.json';
    $dir  = dirname($log);
    if (!is_dir($dir)) return;

    $today   = date('Y-m-d');
    $month   = date('Y-m');
    $ip_hash = hash('sha256', ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '') . date('Y-m-d'));
    $page    = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

    $data = [];
    if (is_file($log)) {
        $raw = json_decode((string) file_get_contents($log), true);
        if (is_array($raw)) $data = $raw;
    }

    // Heutiger Eintrag suchen oder anlegen
    $found = false;
    foreach ($data as &$entry) {
        if (($entry['date'] ?? '') === $today) {
            $entry['views']++;
            if (!in_array($ip_hash, $entry['ip_hashes'] ?? [], true)) {
                $entry['ip_hashes'][] = $ip_hash;
            }
            $found = true;
            break;
        }
    }
    unset($entry);

    if (!$found) {
        $data[] = ['date' => $today, 'month' => $month, 'views' => 1, 'ip_hashes' => [$ip_hash]];
    }

    // Einträge älter als 13 Monate löschen (Datensparsamkeit)
    $cutoff = date('Y-m', strtotime('-13 months'));
    $data   = array_values(array_filter($data, fn($e) => ($e['month'] ?? '') >= $cutoff));

    file_put_contents($log, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
})();

$page_title    = $page_title    ?? SITE_NAME;
$page_desc     = $page_desc     ?? 'Professioneller 3D-Druckservice in Südtirol. STL-Dateien drucken lassen – schnell, günstig und lokal.';
$page_keywords = $page_keywords ?? '3D Druck Südtirol, 3D Druckservice, STL drucken, FDM Druck, Prototypen, Ersatzteile';
$og_image      = $og_image      ?? SITE_URL . '/assets/img/og-image.jpg';
$canonical     = $canonical     ?? SITE_URL . strtok($_SERVER['REQUEST_URI'], '?');
?>
<!DOCTYPE html>
<html lang="de" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($page_title) ?></title>
    <meta name="description" content="<?= h($page_desc) ?>">
    <meta name="keywords" content="<?= h($page_keywords) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= h($canonical) ?>">

    <!-- OpenGraph -->
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="<?= h($canonical) ?>">
    <meta property="og:title"       content="<?= h($page_title) ?>">
    <meta property="og:description" content="<?= h($page_desc) ?>">
    <meta property="og:image"       content="<?= h($og_image) ?>">
    <meta property="og:locale"      content="de_IT">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= h($page_title) ?>">
    <meta name="twitter:description" content="<?= h($page_desc) ?>">
    <meta name="twitter:image"       content="<?= h($og_image) ?>">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <span class="brand-icon"><i class="bi bi-printer-fill"></i></span>
            <span class="brand-text">3D Druck <span class="brand-accent">Südtirol</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="nav-link" href="/">Start</a></li>
                <li class="nav-item"><a class="nav-link" href="/services.php">Leistungen</a></li>
                <li class="nav-item"><a class="nav-link" href="/pricing.php">Preise</a></li>
                <li class="nav-item"><a class="nav-link" href="/gallery.php">Galerie</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact.php">Kontakt</a></li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary-custom" href="/request.php">
                        <i class="bi bi-send-fill me-1"></i> Anfrage senden
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- /Navbar -->
