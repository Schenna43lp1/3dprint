<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_admin();

/* ── Hilfsfunktionen ── */
function load_requests(): array {
    if (!is_file(REQUEST_LOG)) return [];
    $data = json_decode((string) file_get_contents(REQUEST_LOG), true);
    return is_array($data) ? $data : [];
}

function save_requests(array $rows): void {
    file_put_contents(REQUEST_LOG, json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

// Rückwärtskompatibel: alte done-Boolean → neuer Status-String
function get_status(array $r): string {
    if (!empty($r['status'])) return $r['status'];
    return !empty($r['done']) ? 'erledigt' : 'offen';
}

/* ── Aktionen ── */
$flash      = '';
$flash_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $flash = 'Ungültige Anfrage.';
        $flash_type = 'error';
    } else {
        $id     = $_POST['id']     ?? '';
        $action = $_POST['action'] ?? '';
        $rows   = load_requests();

        foreach ($rows as $i => $r) {
            if (($r['id'] ?? '') !== $id) continue;

            if ($action === 'delete') {
                if (!empty($r['file_stored'])) {
                    $f = UPLOAD_DIR . basename($r['file_stored']);
                    if (is_file($f)) @unlink($f);
                }
                unset($rows[$i]);
                $flash = 'Anfrage gelöscht.';

            } elseif ($action === 'set_status') {
                $new_status = $_POST['status'] ?? '';
                $allowed    = ['offen', 'in_bearbeitung', 'erledigt'];
                if (!in_array($new_status, $allowed, true)) break;

                $old_status = get_status($r);
                $rows[$i]['status'] = $new_status;
                $rows[$i]['done']   = ($new_status === 'erledigt');
                $flash = 'Status aktualisiert.';

                if ($new_status === 'erledigt' && $old_status !== 'erledigt') {
                    notify_customer_done($rows[$i]);
                }

            } elseif ($action === 'ship') {
                $tracking = sanitize($_POST['tracking'] ?? '');
                $rows[$i]['status']   = 'versendet';
                $rows[$i]['done']     = false;
                $rows[$i]['tracking'] = $tracking;
                $flash = 'Als versendet markiert' . ($tracking ? ' – Tracking-Nr. gespeichert.' : '.');

                notify_customer_shipped($rows[$i]);
            }
            break;
        }
        save_requests($rows);
    }
}

$requests = load_requests();
usort($requests, fn($a, $b) => ($b['ts'] ?? 0) <=> ($a['ts'] ?? 0));

$total    = count($requests);
$today    = count(array_filter($requests, fn($r) => date('Y-m-d', $r['ts'] ?? 0) === date('Y-m-d')));
$open     = count(array_filter($requests, fn($r) => in_array(get_status($r), ['offen', 'in_bearbeitung'])));
$withFile = count(array_filter($requests, fn($r) => !empty($r['file_stored'])));

$csrf = generate_csrf_token();

/* ── E-Mail-Funktionen ── */
function notify_customer_shipped(array $r): void {
    $name     = $r['name']     ?? 'Kunde';
    $email    = $r['email']    ?? '';
    $tracking = $r['tracking'] ?? '';
    if (!$email) return;

    $subject = '=?UTF-8?B?' . base64_encode('Deine Bestellung wurde versendet – 3D Druck Südtirol') . '?=';

    $tracking_line = $tracking
        ? "Tracking-Nummer: {$tracking}\n"
        : "";

    $body = <<<TEXT
Hallo {$name},

deine Bestellung bei 3D Druck Südtirol ist auf dem Weg!

DEINE BESTELLUNG
----------------
Material:  {$r['material']}
Farbe:     {$r['color']}
Stückzahl: {$r['quantity']}

VERSANDINFO
-----------
{$tracking_line}
Bei Fragen zum Versand oder zur Lieferung melde dich jederzeit:
E-Mail:   info@3ddruck-suedtirol.it
Telefon:  +39 324 594 3473

Vielen Dank für dein Vertrauen!

Viele Grüße,
Markus Stufer
3D Druck Südtirol
--
3ddruck-suedtirol.it
TEXT;

    $headers  = "From: info@3ddruck-suedtirol.it\r\n";
    $headers .= "Reply-To: info@3ddruck-suedtirol.it\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";

    mail($email, $subject, $body, $headers);
}

function notify_customer_done(array $r): void {
    $name  = $r['name']  ?? 'Kunde';
    $email = $r['email'] ?? '';
    if (!$email) return;

    $subject = '=?UTF-8?B?' . base64_encode('Deine Druckanfrage wurde abgeschlossen – 3D Druck Südtirol') . '?=';
    $body = <<<TEXT
Hallo {$name},

deine Druckanfrage bei 3D Druck Südtirol wurde abgeschlossen und ist bereit!

DEINE BESTELLUNG
----------------
Material:  {$r['material']}
Farbe:     {$r['color']}
Stückzahl: {$r['quantity']}

Wir melden uns in Kürze, um die Übergabe / den Versand mit dir abzustimmen.

Bei Fragen erreichst du uns jederzeit:
E-Mail:   info@3ddruck-suedtirol.it
Telefon:  +39 324 594 3473

Vielen Dank für dein Vertrauen!

Viele Grüße,
Markus Stufer
3D Druck Südtirol
--
3ddruck-suedtirol.it
TEXT;

    $headers  = "From: info@3ddruck-suedtirol.it\r\n";
    $headers .= "Reply-To: info@3ddruck-suedtirol.it\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";

    mail($email, $subject, $body, $headers);
}
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
                    <div class="stat-label">Offen / In Arbeit</div>
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
                    <?php foreach ($requests as $r):
                        $status = get_status($r);
                    ?>
                        <tr class="<?= $status === 'erledigt' ? 'row-done' : '' ?>">
                            <td>
                                <?php if ($status === 'offen'): ?>
                                    <span class="pill pill-open">Offen</span>
                                <?php elseif ($status === 'in_bearbeitung'): ?>
                                    <span class="pill pill-progress"><i class="bi bi-printer me-1"></i>In Bearbeitung</span>
                                <?php elseif ($status === 'versendet'): ?>
                                    <span class="pill pill-shipped"><i class="bi bi-truck me-1"></i>Versendet</span>
                                    <?php if (!empty($r['tracking'])): ?>
                                        <br><span class="text-muted small mt-1 d-inline-block">
                                            <i class="bi bi-upc-scan me-1"></i><?= h($r['tracking']) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="pill pill-done"><i class="bi bi-check-lg me-1"></i>Erledigt</span>
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
                                <?php if ($status === 'offen'): ?>
                                    <!-- → In Bearbeitung -->
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                                        <input type="hidden" name="id"     value="<?= h($r['id']) ?>">
                                        <input type="hidden" name="action" value="set_status">
                                        <input type="hidden" name="status" value="in_bearbeitung">
                                        <button class="icon-btn icon-btn-progress" title="In Bearbeitung">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </form>

                                <?php elseif ($status === 'in_bearbeitung'): ?>
                                    <!-- → Versendet (Modal) -->
                                    <button class="icon-btn icon-btn-ship"
                                            title="Versendet"
                                            onclick="openShipModal('<?= h($r['id']) ?>', '<?= h(addslashes($r['name'] ?? '')) ?>')">
                                        <i class="bi bi-truck"></i>
                                    </button>
                                    <!-- → Erledigt -->
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                                        <input type="hidden" name="id"     value="<?= h($r['id']) ?>">
                                        <input type="hidden" name="action" value="set_status">
                                        <input type="hidden" name="status" value="erledigt">
                                        <button class="icon-btn" title="Als erledigt markieren">
                                            <i class="bi bi-check2-square"></i>
                                        </button>
                                    </form>

                                <?php elseif ($status === 'versendet'): ?>
                                    <!-- → Erledigt -->
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                                        <input type="hidden" name="id"     value="<?= h($r['id']) ?>">
                                        <input type="hidden" name="action" value="set_status">
                                        <input type="hidden" name="status" value="erledigt">
                                        <button class="icon-btn" title="Als erledigt markieren">
                                            <i class="bi bi-check2-square"></i>
                                        </button>
                                    </form>

                                <?php else: /* erledigt */ ?>
                                    <!-- → Wieder öffnen -->
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                                        <input type="hidden" name="id"     value="<?= h($r['id']) ?>">
                                        <input type="hidden" name="action" value="set_status">
                                        <input type="hidden" name="status" value="offen">
                                        <button class="icon-btn icon-btn-reopen" title="Wieder öffnen">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Löschen -->
                                <form method="post" class="d-inline" onsubmit="return confirm('Diese Anfrage wirklich löschen?');">
                                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                                    <input type="hidden" name="id"     value="<?= h($r['id']) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="icon-btn icon-btn-danger" title="Löschen">
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

<!-- Versand-Modal -->
<div class="modal fade" id="shipModal" tabindex="-1" aria-labelledby="shipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content dash-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="shipModalLabel">
                    <i class="bi bi-truck me-2 text-accent"></i>Paket versendet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" id="shipForm">
                <div class="modal-body">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                    <input type="hidden" name="id"     id="shipId">
                    <input type="hidden" name="action" value="ship">

                    <p class="text-muted mb-3">Anfrage von <strong id="shipName"></strong> als versendet markieren.</p>

                    <label for="trackingInput" class="form-label small">
                        Tracking-Nummer <span class="text-muted">(optional)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-secondary">
                            <i class="bi bi-upc-scan text-muted"></i>
                        </span>
                        <input type="text"
                               id="trackingInput"
                               name="tracking"
                               class="form-control bg-transparent border-secondary text-white"
                               placeholder="z.B. 1Z999AA10123456784"
                               maxlength="100">
                    </div>
                    <p class="text-muted small mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Der Kunde erhält automatisch eine E-Mail mit der Versandbestätigung.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn-hero-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-truck me-1"></i> Versendet &amp; E-Mail senden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openShipModal(id, name) {
    document.getElementById('shipId').value   = id;
    document.getElementById('shipName').textContent = name;
    document.getElementById('trackingInput').value  = '';
    new bootstrap.Modal(document.getElementById('shipModal')).show();
}
</script>
</body>
</html>
