<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_admin();

$id = $_GET['id'] ?? '';

// Anfrage anhand der ID finden
$rows = is_file(REQUEST_LOG) ? json_decode((string) file_get_contents(REQUEST_LOG), true) : [];
$rows = is_array($rows) ? $rows : [];

$match = null;
foreach ($rows as $r) {
    if (($r['id'] ?? '') === $id) { $match = $r; break; }
}

if (!$match || empty($match['file_stored'])) {
    http_response_code(404);
    exit('Datei nicht gefunden.');
}

// Pfad absichern: nur Basename, keine Traversal
$stored = basename($match['file_stored']);
$path   = UPLOAD_DIR . $stored;

if (!is_file($path)) {
    http_response_code(404);
    exit('Datei nicht mehr vorhanden.');
}

$downloadName = $match['file_original'] ?? $stored;

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . preg_replace('/["\r\n]/', '', $downloadName) . '"');
header('Content-Length: ' . filesize($path));
header('X-Content-Type-Options: nosniff');
readfile($path);
exit;
