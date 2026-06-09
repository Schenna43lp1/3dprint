<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

/* ── Auth ── */
$key = $_SERVER['HTTP_X_API_KEY'] ?? '';
if (empty($key) || !defined('ADMIN_API_KEY') || !hash_equals(ADMIN_API_KEY, $key)) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

/* ── Hilfsfunktionen ── */
function load_json(string $path): array {
    if (!is_file($path)) return [];
    $d = json_decode((string) file_get_contents($path), true);
    return is_array($d) ? $d : [];
}

function save_json(string $path, array $data): void {
    file_put_contents($path, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

function get_status(array $r): string {
    if (!empty($r['status'])) return $r['status'];
    return !empty($r['done']) ? 'erledigt' : 'offen';
}

/* ── GET /api.php?action=requests ── */
if ($method === 'GET' && $action === 'requests') {
    $rows = load_json(REQUEST_LOG);
    foreach ($rows as &$r) {
        $r['status'] = get_status($r);
    }
    usort($rows, fn($a, $b) => ($b['ts'] ?? 0) <=> ($a['ts'] ?? 0));
    echo json_encode($rows);
    exit;
}

/* ── GET /api.php?action=visitors ── */
if ($method === 'GET' && $action === 'visitors') {
    $visitors    = load_json(UPLOAD_DIR . 'visitors.json');
    $this_month  = date('Y-m');
    $last_month  = date('Y-m', strtotime('-1 month'));

    $result = ['this_month_views' => 0, 'this_month_unique' => 0,
               'last_month_unique' => 0, 'today_views' => 0, 'daily' => []];

    $unique_set  = [];
    $lunique_set = [];

    foreach ($visitors as $e) {
        if (($e['month'] ?? '') === $this_month) {
            $result['this_month_views'] += $e['views'] ?? 0;
            foreach ($e['ip_hashes'] ?? [] as $h) $unique_set[$h] = true;
            if (($e['date'] ?? '') === date('Y-m-d')) $result['today_views'] += $e['views'] ?? 0;
        }
        if (($e['month'] ?? '') === $last_month) {
            foreach ($e['ip_hashes'] ?? [] as $h) $lunique_set[$h] = true;
        }
    }
    $result['this_month_unique']  = count($unique_set);
    $result['last_month_unique']  = count($lunique_set);

    // letzte 14 Tage
    for ($i = 13; $i >= 0; $i--) {
        $d = date('Y-m-d', strtotime("-{$i} days"));
        $dv = 0; $du = [];
        foreach ($visitors as $e) {
            if (($e['date'] ?? '') === $d) {
                $dv += $e['views'] ?? 0;
                foreach ($e['ip_hashes'] ?? [] as $h) $du[$h] = true;
            }
        }
        $result['daily'][] = ['date' => $d, 'views' => $dv, 'unique' => count($du)];
    }

    echo json_encode($result);
    exit;
}

/* ── POST actions ── */
if ($method === 'POST') {
    $body   = json_decode((string) file_get_contents('php://input'), true) ?? [];
    $action = $body['action'] ?? $action;
    $id     = $body['id']     ?? '';
    $rows   = load_json(REQUEST_LOG);

    foreach ($rows as $i => $r) {
        if (($r['id'] ?? '') !== $id) continue;

        if ($action === 'set_status') {
            $new_status = $body['status'] ?? '';
            $allowed = ['offen','angebot_gesendet','bestaetigt','bezahlt',
                        'in_bearbeitung','druckfertig','abholbereit',
                        'versendet','erledigt','storniert'];
            if (!in_array($new_status, $allowed, true)) {
                http_response_code(400); echo json_encode(['error' => 'Invalid status']); exit;
            }
            $rows[$i]['status'] = $new_status;
            $rows[$i]['done']   = ($new_status === 'erledigt');
            if (isset($body['tracking'])) $rows[$i]['tracking'] = sanitize($body['tracking']);
            save_json(REQUEST_LOG, $rows);
            echo json_encode(['ok' => true]);
            exit;
        }

        if ($action === 'delete') {
            if (!empty($r['file_stored'])) {
                $f = UPLOAD_DIR . basename($r['file_stored']);
                if (is_file($f)) @unlink($f);
            }
            unset($rows[$i]);
            save_json(REQUEST_LOG, $rows);
            echo json_encode(['ok' => true]);
            exit;
        }

        if ($action === 'quote') {
            $rows[$i]['status']        = 'angebot_gesendet';
            $rows[$i]['done']          = false;
            $rows[$i]['quote_price']   = sanitize($body['price'] ?? '');
            $rows[$i]['quote_note']    = sanitize($body['note']  ?? '');
            $rows[$i]['quote_valid']   = sanitize($body['valid'] ?? '');
            $rows[$i]['quote_sent_at'] = time();
            save_json(REQUEST_LOG, $rows);
            echo json_encode(['ok' => true]);
            exit;
        }

        if ($action === 'invoice') {
            $rows[$i]['status']      = 'bestaetigt';
            $rows[$i]['done']        = false;
            $rows[$i]['inv_nr']      = sanitize($body['inv_nr']    ?? '');
            $rows[$i]['inv_price']   = sanitize($body['inv_price'] ?? '');
            $rows[$i]['inv_due']     = sanitize($body['inv_due']   ?? '');
            $rows[$i]['inv_note']    = sanitize($body['inv_note']  ?? '');
            $rows[$i]['inv_iban']    = sanitize($body['inv_iban']  ?? '');
            $rows[$i]['inv_sent_at'] = time();
            save_json(REQUEST_LOG, $rows);
            echo json_encode(['ok' => true]);
            exit;
        }

        break;
    }

    http_response_code(400);
    echo json_encode(['error' => 'Unknown action or ID not found']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
