<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_admin();

/* ── Anfragen laden ── */
function load_requests(): array {
    if (!is_file(REQUEST_LOG)) return [];
    $data = json_decode((string) file_get_contents(REQUEST_LOG), true);
    return is_array($data) ? $data : [];
}

function save_requests(array $rows): void {
    file_put_contents(REQUEST_LOG, json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

/* ── Aktionen (löschen / Status ändern) ── */
$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $flash = 'Ungültige Anfrage.';
    } else {
        $id     = $_POST['id'] ?? '';
        $action = $_POST['action'] ?? '';
        $rows   = load_requests();

        foreach ($rows as $i => $r) {
            if (($r['id'] ?? '') !== $id) continue;

            if ($action === 'delete') {
                // zugehörige Upload-Datei mit entfernen
                if (!empty($r['file_stored'])) {
                    $f = UPLOAD_DIR . basename($r['file_stored']);
                    if (is_file($f)) @unlink($f);
                }
                unset($rows[$i]);
                $flash = 'Anfrage gelöscht.';
            } elseif ($action === 'toggle') {
                $rows[$i]['done'] = empty($r['done']);
                $flash = 'Status aktualisiert.';
            }
            break;
        }
        save_requests($rows);
    }
}

$requests = load_requests();
// neueste zuerst
usort($requests, fn($a, $b) => ($b['ts'] ?? 0) <=> ($a['ts'] ?? 0));

$total    = count($requests);
$today    = count(array_filter($requests, fn($r) => date('Y-m-d', $r['ts'] ?? 0) === date('Y-m-d')));
$open     = count(array_filter($requests, fn($r) => empty($r['done'])));
$withFile = count(array_filter($requests, fn($r) => !empty($r['file_stored'])));

$csrf = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="de" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · <?= h(SITE_NAME) ?></title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
</head>
<body class="dashboard-page">

<!-- Topbar -->
<nav class="dash-topbar">
    <div class="d-flex align-items-center gap-2">
        <span class="brand-icon"><i class="bi bi-printer-fill"></i></span>
        <span class="brand-text">3D Druck <span class="brand-accent">Südtirol</span></span>
        <span class="dash-badge">Admin</span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="/" class="btn-hero-secondary btn-sm-custom" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> Website
        </a>
        <a href="/logout.php" class="btn-hero-secondary btn-sm-custom">
            <i class="bi bi-box-arrow-right"></i> Abmelden
        </a>
    </div>
</nav>

<main class="dash-main container">
    <div class="mb-4">
        <h1 class="dash-title">Willkommen zurück 👋</h1>
        <p class="text-muted mb-0">Übersicht über alle eingegangenen Druckanfragen.</p>
    </div>

    <?php if ($flash): ?>
        <div class="alert-custom alert-success-custom mb-4">
            <i class="bi bi-check-circle-fill"></i><span><?= h($flash) ?></span>
        </div>
    <?php endif; ?>

    <!-- Stat-Karten -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-inbox-fill"></i></div>
                <div>
                    <div class="stat-value"><?= $total ?></div>
                    <div class="stat-label">Anfragen gesamt</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-calendar-day-fill"></i></div>
                <div>
                    <div class="stat-value"><?= $today ?></div>
                    <div class="stat-label">Heute</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="stat-value"><?= $open ?></div>
                    <div class="stat-label">Offen</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-paperclip"></i></div>
                <div>
                    <div class="stat-value"><?= $withFile ?></div>
                    <div class="stat-label">Mit Datei</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anfragen-Tabelle -->
    <div class="dash-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="dash-card-title mb-0"><i class="bi bi-list-ul me-2"></i>Druckanfragen</h2>
        </div>

        <?php if (!$requests): ?>
            <div class="dash-empty">
                <i class="bi bi-inbox"></i>
                <p>Noch keine Anfragen eingegangen.</p>
                <span class="text-muted small">Neue Anfragen über das Formular erscheinen hier automatisch.</span>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table dash-table align-middle">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Datum</th>
                            <th>Kunde</th>
                            <th>Material</th>
                            <th>Menge</th>
                            <th>Beschreibung</th>
                            <th>Datei</th>
                            <th class="text-end">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($requests as $r): ?>
                        <tr class="<?= !empty($r['done']) ? 'row-done' : '' ?>">
                            <td>
                                <?php if (!empty($r['done'])): ?>
                                    <span class="pill pill-done"><i class="bi bi-check-lg"></i> Erledigt</span>
                                <?php else: ?>
                                    <span class="pill pill-open">Offen</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-nowrap small">
                                <?= h(date('d.m.Y', $r['ts'] ?? 0)) ?><br>
                                <span class="text-muted"><?= h(date('H:i', $r['ts'] ?? 0)) ?> Uhr</span>
                            </td>
                            <td>
                                <strong><?= h($r['name'] ?? '') ?></strong><br>
                                <a href="mailto:<?= h($r['email'] ?? '') ?>" class="dash-link small"><?= h($r['email'] ?? '') ?></a>
                                <?php if (!empty($r['phone'])): ?>
                                    <br><span class="text-muted small"><i class="bi bi-telephone me-1"></i><?= h($r['phone']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="d-block"><?= h($r['material'] ?? '') ?></span>
                                <span class="text-muted small"><?= h($r['color'] ?? '') ?></span>
                            </td>
                            <td><?= (int) ($r['quantity'] ?? 1) ?>×</td>
                            <td class="dash-desc"><?= nl2br(h(mb_strimwidth($r['description'] ?? '', 0, 160, '…'))) ?></td>
                            <td>
                                <?php if (!empty($r['file_stored'])): ?>
                                    <a href="/download.php?id=<?= h(urlencode($r['id'])) ?>" class="dash-link small">
                                        <i class="bi bi-download me-1"></i><?= h($r['file_original'] ?? 'Datei') ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                                    <input type="hidden" name="id" value="<?= h($r['id']) ?>">
                                    <button name="action" value="toggle" class="icon-btn" title="Status wechseln">
                                        <i class="bi bi-check2-square"></i>
                                    </button>
                                </form>
                                <form method="post" class="d-inline" onsubmit="return confirm('Diese Anfrage wirklich löschen?');">
                                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                                    <input type="hidden" name="id" value="<?= h($r['id']) ?>">
                                    <button name="action" value="delete" class="icon-btn icon-btn-danger" title="Löschen">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
