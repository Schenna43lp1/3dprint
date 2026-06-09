<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_admin();

/* ── Status-Konfiguration ── */
const STATUSES = [
    'offen'            => ['label' => 'Offen',             'icon' => 'bi-inbox',            'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.12)',  'border' => 'rgba(245,158,11,0.3)'],
    'angebot_gesendet' => ['label' => 'Angebot gesendet',  'icon' => 'bi-envelope-open',    'color' => '#fb923c', 'bg' => 'rgba(251,146,60,0.12)',  'border' => 'rgba(251,146,60,0.3)'],
    'bestaetigt'       => ['label' => 'Bestätigt',         'icon' => 'bi-hand-thumbs-up',   'color' => '#60a5fa', 'bg' => 'rgba(96,165,250,0.12)',  'border' => 'rgba(96,165,250,0.3)'],
    'in_bearbeitung'   => ['label' => 'In Bearbeitung',    'icon' => 'bi-printer',          'color' => '#a78bfa', 'bg' => 'rgba(167,139,250,0.12)', 'border' => 'rgba(167,139,250,0.3)'],
    'druckfertig'      => ['label' => 'Druckfertig',       'icon' => 'bi-check2-all',       'color' => '#34d399', 'bg' => 'rgba(52,211,153,0.12)',  'border' => 'rgba(52,211,153,0.3)'],
    'abholbereit'      => ['label' => 'Abholbereit',       'icon' => 'bi-bag-check',        'color' => '#4ade80', 'bg' => 'rgba(74,222,128,0.12)',  'border' => 'rgba(74,222,128,0.3)'],
    'versendet'        => ['label' => 'Versendet',         'icon' => 'bi-truck',            'color' => '#00d4ff', 'bg' => 'rgba(0,212,255,0.10)',   'border' => 'rgba(0,212,255,0.3)'],
    'erledigt'         => ['label' => 'Erledigt',          'icon' => 'bi-check-circle',     'color' => '#86efac', 'bg' => 'rgba(134,239,172,0.12)', 'border' => 'rgba(134,239,172,0.3)'],
    'storniert'        => ['label' => 'Storniert',         'icon' => 'bi-x-circle',         'color' => '#f87171', 'bg' => 'rgba(248,113,113,0.12)', 'border' => 'rgba(248,113,113,0.3)'],
];

/* ── Hilfsfunktionen ── */
function load_requests(): array {
    if (!is_file(REQUEST_LOG)) return [];
    $data = json_decode((string) file_get_contents(REQUEST_LOG), true);
    return is_array($data) ? $data : [];
}

function save_requests(array $rows): void {
    file_put_contents(REQUEST_LOG, json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

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
                if (!array_key_exists($new_status, STATUSES)) break;

                $old_status = get_status($r);
                $rows[$i]['status'] = $new_status;
                $rows[$i]['done']   = ($new_status === 'erledigt');
                $flash = 'Status: ' . STATUSES[$new_status]['label'];

                if ($new_status !== $old_status) {
                    if ($new_status === 'abholbereit')  notify_customer_pickup($rows[$i]);
                    if ($new_status === 'erledigt')     notify_customer_done($rows[$i]);
                    if ($new_status === 'storniert')    notify_customer_cancelled($rows[$i]);
                }

            } elseif ($action === 'ship') {
                $tracking = sanitize($_POST['tracking'] ?? '');
                $rows[$i]['status']   = 'versendet';
                $rows[$i]['done']     = false;
                $rows[$i]['tracking'] = $tracking;
                $flash = 'Versendet' . ($tracking ? ' – Tracking-Nr. gespeichert.' : '.');
                notify_customer_shipped($rows[$i]);

            } elseif ($action === 'quote') {
                $price   = sanitize($_POST['price']   ?? '');
                $note    = sanitize($_POST['note']    ?? '');
                $valid   = sanitize($_POST['valid']   ?? '');
                if (mb_strlen($price) < 1 || mb_strlen($price) > 100) {
                    $flash = 'Bitte einen Preis angeben.';
                    $flash_type = 'error';
                    break;
                }
                $rows[$i]['status']        = 'angebot_gesendet';
                $rows[$i]['done']          = false;
                $rows[$i]['quote_price']   = $price;
                $rows[$i]['quote_note']    = $note;
                $rows[$i]['quote_valid']   = $valid;
                $rows[$i]['quote_sent_at'] = time();
                $flash = 'Angebot gesendet an ' . ($r['email'] ?? '');
                notify_customer_quote($rows[$i]);
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
$active   = count(array_filter($requests, fn($r) => !in_array(get_status($r), ['erledigt', 'storniert'])));
$withFile = count(array_filter($requests, fn($r) => !empty($r['file_stored'])));

$csrf = generate_csrf_token();

/* ── E-Mail-Funktionen ── */
function send_mail_to_customer(array $r, string $subject_plain, string $body): void {
    $email = $r['email'] ?? '';
    if (!$email) return;
    $subject = '=?UTF-8?B?' . base64_encode($subject_plain) . '?=';
    $headers  = "From: info@3ddruck-suedtirol.it\r\nReply-To: info@3ddruck-suedtirol.it\r\n";
    $headers .= "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n";
    mail($email, $subject, $body, $headers);
}

function order_summary(array $r): string {
    return "Material:  {$r['material']}\nFarbe:     {$r['color']}\nStückzahl: {$r['quantity']}";
}

function mail_footer(): string {
    return "\nBei Fragen erreichst du uns jederzeit:\nE-Mail:   info@3ddruck-suedtirol.it\nTelefon:  +39 324 594 3473\n\nViele Grüße,\nMarkus Stufer\n3D Druck Südtirol\n--\n3ddruck-suedtirol.it";
}

function notify_customer_quote(array $r): void {
    $name  = $r['name']        ?? 'Kunde';
    $price = $r['quote_price'] ?? '';
    $note  = $r['quote_note']  ?? '';
    $valid = $r['quote_valid'] ?? '';

    $note_block  = $note  ? "\nHINWEIS\n-------\n{$note}\n" : '';
    $valid_block = $valid ? "\nAngebot gültig bis: {$valid}\n"      : '';

    send_mail_to_customer($r, 'Dein Angebot von 3D Druck Südtirol', <<<TEXT
Hallo {$name},

vielen Dank für deine Anfrage! Hier ist dein persönliches Angebot:

DEINE ANFRAGE
-------------
Material:  {$r['material']}
Farbe:     {$r['color']}
Stückzahl: {$r['quantity']}

ANGEBOT
-------
Preis: {$price}
{$valid_block}{$note_block}
Um das Angebot anzunehmen, antworte einfach auf diese E-Mail oder melde dich telefonisch.
TEXT . mail_footer());
}

function notify_customer_pickup(array $r): void {
    $name = $r['name'] ?? 'Kunde';
    send_mail_to_customer($r, 'Deine Bestellung ist abholbereit – 3D Druck Südtirol', <<<TEXT
Hallo {$name},

deine Bestellung ist fertig und kann jetzt abgeholt werden!

DEINE BESTELLUNG
----------------
{$r['material']}  ·  {$r['color']}  ·  {$r['quantity']}×

Abholadresse: Schennastraße 81, 39017 Schenna (Scena)
Öffnungszeiten: Mo–Fr 09:00–18:00 Uhr

Bitte melde dich kurz per E-Mail oder Telefon, um einen Abholtermin zu vereinbaren.
TEXT . mail_footer());
}

function notify_customer_shipped(array $r): void {
    $name     = $r['name']     ?? 'Kunde';
    $tracking = $r['tracking'] ?? '';
    $t_line   = $tracking ? "Tracking-Nummer: {$tracking}\n" : "";
    send_mail_to_customer($r, 'Deine Bestellung wurde versendet – 3D Druck Südtirol', <<<TEXT
Hallo {$name},

deine Bestellung ist unterwegs!

DEINE BESTELLUNG
----------------
{$r['material']}  ·  {$r['color']}  ·  {$r['quantity']}×

VERSANDINFO
-----------
{$t_line}
TEXT . mail_footer());
}

function notify_customer_done(array $r): void {
    $name = $r['name'] ?? 'Kunde';
    send_mail_to_customer($r, 'Deine Druckanfrage wurde abgeschlossen – 3D Druck Südtirol', <<<TEXT
Hallo {$name},

deine Anfrage bei 3D Druck Südtirol wurde erfolgreich abgeschlossen.
Vielen Dank für dein Vertrauen!

DEINE BESTELLUNG
----------------
{$r['material']}  ·  {$r['color']}  ·  {$r['quantity']}×
TEXT . mail_footer());
}

function notify_customer_cancelled(array $r): void {
    $name = $r['name'] ?? 'Kunde';
    send_mail_to_customer($r, 'Deine Anfrage bei 3D Druck Südtirol', <<<TEXT
Hallo {$name},

leider müssen wir deine Druckanfrage stornieren.
Bitte kontaktiere uns bei Fragen direkt per E-Mail oder Telefon.
TEXT . mail_footer());
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
    <style>
        .status-dropdown { min-width: 200px; }
        .status-dropdown .dropdown-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; }
        .status-dropdown .dropdown-item i { width: 16px; text-align: center; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
        .row-cancelled td { opacity: 0.45; }
        .row-done td { opacity: 0.7; }
    </style>
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
                    <div class="stat-value"><?= $active ?></div>
                    <div class="stat-label">Aktiv</div>
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
                            <th style="min-width:160px">Status</th>
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
                        $sc     = STATUSES[$status] ?? STATUSES['offen'];
                        $rowClass = match($status) {
                            'erledigt'  => 'row-done',
                            'storniert' => 'row-cancelled',
                            default     => '',
                        };
                    ?>
                        <tr class="<?= $rowClass ?>">
                            <td>
                                <span class="pill" style="
                                    background:<?= $sc['bg'] ?>;
                                    color:<?= $sc['color'] ?>;
                                    border:1px solid <?= $sc['border'] ?>;
                                    display:inline-flex;align-items:center;gap:.3rem;
                                    font-size:.72rem;font-weight:700;
                                    padding:.2rem .6rem;border-radius:50px;white-space:nowrap;">
                                    <i class="bi <?= $sc['icon'] ?>"></i><?= h($sc['label']) ?>
                                </span>
                                <?php if ($status === 'versendet' && !empty($r['tracking'])): ?>
                                    <br><span class="text-muted small mt-1 d-inline-block">
                                        <i class="bi bi-upc-scan me-1"></i><?= h($r['tracking']) ?>
                                    </span>
                                <?php elseif ($status === 'angebot_gesendet' && !empty($r['quote_price'])): ?>
                                    <br><span class="text-muted small mt-1 d-inline-block">
                                        <i class="bi bi-currency-euro me-1"></i><?= h($r['quote_price']) ?>
                                        <?php if (!empty($r['quote_valid'])): ?>
                                            · bis <?= h(date('d.m.Y', strtotime($r['quote_valid']))) ?>
                                        <?php endif; ?>
                                    </span>
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
                                <!-- Status-Dropdown -->
                                <div class="dropdown d-inline-block">
                                    <button class="icon-btn" title="Status ändern"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end status-dropdown">
                                        <?php foreach (STATUSES as $key => $cfg):
                                            if ($key === $status) continue; // aktuellen Status überspringen
                                        ?>
                                            <li>
                                                <?php if ($key === 'versendet'): ?>
                                                    <button type="button" class="dropdown-item"
                                                            onclick="openShipModal('<?= h($r['id']) ?>', '<?= h(addslashes($r['name'] ?? '')) ?>')">
                                                        <span class="status-dot" style="background:<?= $cfg['color'] ?>"></span>
                                                        <i class="bi <?= $cfg['icon'] ?>"></i>
                                                        <?= h($cfg['label']) ?>
                                                    </button>
                                                <?php elseif ($key === 'angebot_gesendet'): ?>
                                                    <button type="button" class="dropdown-item"
                                                            onclick="openQuoteModal('<?= h($r['id']) ?>', '<?= h(addslashes($r['name'] ?? '')) ?>')">
                                                        <span class="status-dot" style="background:<?= $cfg['color'] ?>"></span>
                                                        <i class="bi <?= $cfg['icon'] ?>"></i>
                                                        <?= h($cfg['label']) ?>
                                                    </button>
                                                <?php else: ?>
                                                    <form method="post" class="m-0">
                                                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                                                        <input type="hidden" name="id"     value="<?= h($r['id']) ?>">
                                                        <input type="hidden" name="action" value="set_status">
                                                        <input type="hidden" name="status" value="<?= h($key) ?>">
                                                        <button type="submit" class="dropdown-item">
                                                            <span class="status-dot" style="background:<?= $cfg['color'] ?>"></span>
                                                            <i class="bi <?= $cfg['icon'] ?>"></i>
                                                            <?= h($cfg['label']) ?>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

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

<!-- Angebot-Modal -->
<div class="modal fade" id="quoteModal" tabindex="-1" aria-labelledby="quoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content dash-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="quoteModalLabel">
                    <i class="bi bi-envelope-open me-2" style="color:#fb923c"></i>Angebot senden
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" id="quoteForm">
                <div class="modal-body">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                    <input type="hidden" name="id"     id="quoteId">
                    <input type="hidden" name="action" value="quote">

                    <p class="text-muted mb-3">Angebot an <strong id="quoteName"></strong> senden.</p>

                    <div class="mb-3">
                        <label for="quotePrice" class="form-label small">
                            Preis <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-secondary">
                                <i class="bi bi-currency-euro text-muted"></i>
                            </span>
                            <input type="text"
                                   id="quotePrice"
                                   name="price"
                                   class="form-control bg-transparent border-secondary text-white"
                                   placeholder="z.B. 24,50 €  oder  ab 20 €"
                                   maxlength="100"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="quoteValid" class="form-label small">
                            Gültig bis <span class="text-muted">(optional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-secondary">
                                <i class="bi bi-calendar3 text-muted"></i>
                            </span>
                            <input type="date"
                                   id="quoteValid"
                                   name="valid"
                                   class="form-control bg-transparent border-secondary text-white"
                                   style="color-scheme:dark">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="quoteNote" class="form-label small">
                            Nachricht / Hinweise <span class="text-muted">(optional)</span>
                        </label>
                        <textarea id="quoteNote"
                                  name="note"
                                  rows="3"
                                  class="form-control bg-transparent border-secondary text-white"
                                  placeholder="z.B. Preis gilt für PLA, Lieferzeit ca. 3–5 Werktage …"
                                  maxlength="1000"></textarea>
                    </div>

                    <p class="text-muted small mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Der Kunde erhält das Angebot per E-Mail und kann es per Antwort-Mail annehmen.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn-hero-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-send me-1"></i> Angebot senden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                        Der Kunde erhält automatisch eine Versandbestätigung per E-Mail.
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
function openQuoteModal(id, name) {
    document.getElementById('quoteId').value          = id;
    document.getElementById('quoteName').textContent  = name;
    document.getElementById('quotePrice').value       = '';
    document.getElementById('quoteNote').value        = '';
    document.getElementById('quoteValid').value       = '';
    new bootstrap.Modal(document.getElementById('quoteModal')).show();
}

function openShipModal(id, name) {
    document.getElementById('shipId').value          = id;
    document.getElementById('shipName').textContent  = name;
    document.getElementById('trackingInput').value   = '';
    new bootstrap.Modal(document.getElementById('shipModal')).show();
}
</script>
</body>
</html>
