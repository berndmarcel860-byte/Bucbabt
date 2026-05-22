<?php
declare(strict_types=1);

require_once '../config.php';

// Auth guard
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pdo       = get_db_connection();
$csrfToken = generate_csrf_token();

// ----------------------------------------------------------------
// AJAX: status update (POST action=update_status)
// ----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    if ($_POST['action'] === 'update_status') {
        $id     = (int)($_POST['lead_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $allowed = ['neu', 'in_bearbeitung', 'abgeschlossen'];

        if ($id < 1 || !in_array($status, $allowed, true)) {
            echo json_encode(['success' => false, 'message' => 'Ungültige Daten.']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            echo json_encode(['success' => true, 'message' => 'Status aktualisiert.']);
        } catch (PDOException $e) {
            error_log('Status update error: ' . $e->getCode());
            echo json_encode(['success' => false, 'message' => 'Datenbankfehler.']);
        }
        exit;
    }

    if ($_POST['action'] === 'delete') {
        $id    = (int)($_POST['lead_id'] ?? 0);
        $token = $_POST['csrf_token'] ?? '';
        if (!validate_csrf_token($token) || $id < 1) {
            header('Location: leads.php?error=csrf');
            exit;
        }
        try {
            $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Lead delete error: ' . $e->getCode());
        }
        header('Location: leads.php?deleted=1');
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Unbekannte Aktion.']);
    exit;
}

// ----------------------------------------------------------------
// CSV export (GET action=export)
// ----------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'export') {
    try {
        $stmt = $pdo->query(
            "SELECT id, vorname, nachname, email, telefon, verlustbetrag, plattform, zahlungsmethode, land, nachricht, wunschtermin, status, ip_address, created_at
             FROM leads ORDER BY created_at DESC"
        );
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('CSV export error: ' . $e->getCode());
        die('Export fehlgeschlagen.');
    }

    $filename = 'leads_export_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');

    $out = fopen('php://output', 'w');
    // UTF-8 BOM for Excel compatibility
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['ID','Vorname','Nachname','E-Mail','Telefon','Verlustbetrag','Plattform','Zahlungsmethode','Land','Nachricht','Wunschtermin','Status','IP-Adresse','Erstellt am'], ';');
    foreach ($rows as $row) {
        fputcsv($out, [
            $row['id'],
            $row['vorname'],
            $row['nachname'],
            $row['email'],
            $row['telefon'],
            $row['verlustbetrag'],
            $row['plattform'],
            $row['zahlungsmethode'],
            $row['land'],
            $row['nachricht'],
            $row['wunschtermin'] ?? '',
            $row['status'],
            $row['ip_address'],
            $row['created_at'],
        ], ';');
    }
    fclose($out);
    exit;
}

// ----------------------------------------------------------------
// Pagination & Filters
// ----------------------------------------------------------------
$perPage     = 20;
$currentPage = max(1, (int)($_GET['page']   ?? 1));
$filterStatus= $_GET['status'] ?? '';
$search      = trim($_GET['search'] ?? '');

$allowedStatuses = ['neu', 'in_bearbeitung', 'abgeschlossen'];
if (!in_array($filterStatus, $allowedStatuses, true)) {
    $filterStatus = '';
}

$where  = [];
$params = [];

if ($filterStatus !== '') {
    $where[]  = 'status = ?';
    $params[] = $filterStatus;
}
if ($search !== '') {
    $where[]  = '(vorname LIKE ? OR nachname LIKE ? OR email LIKE ? OR telefon LIKE ?)';
    $like     = '%' . $search . '%';
    $params   = array_merge($params, [$like, $like, $like, $like]);
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

try {
    // Count
    $countStmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM leads {$whereSQL}");
    $countStmt->execute($params);
    $totalRows  = (int)($countStmt->fetch()['cnt'] ?? 0);
    $totalPages = max(1, (int)ceil($totalRows / $perPage));
    $currentPage = min($currentPage, $totalPages);
    $offset     = ($currentPage - 1) * $perPage;

    // Leads
    $leadsStmt = $pdo->prepare(
        "SELECT id, vorname, nachname, email, telefon, verlustbetrag, plattform, zahlungsmethode, land, nachricht, wunschtermin, status, ip_address, created_at
         FROM leads {$whereSQL}
         ORDER BY created_at DESC
         LIMIT {$perPage} OFFSET {$offset}"
    );
    $leadsStmt->execute($params);
    $leads = $leadsStmt->fetchAll();

} catch (PDOException $e) {
    error_log('Leads list error: ' . $e->getCode());
    $leads = [];
    $totalRows = $totalPages = 0;
    $currentPage = 1;
}

// View single lead for modal
$viewLead = null;
if (isset($_GET['view']) && (int)$_GET['view'] > 0) {
    try {
        $vStmt = $pdo->prepare("SELECT * FROM leads WHERE id = ? LIMIT 1");
        $vStmt->execute([(int)$_GET['view']]);
        $viewLead = $vStmt->fetch() ?: null;
    } catch (PDOException $e) {
        $viewLead = null;
    }
}

// Status badge helper
function statusBadge(string $status): string
{
    return match($status) {
        'neu'            => '<span class="badge badge-neu">Neu</span>',
        'in_bearbeitung' => '<span class="badge badge-in-bearb">In Bearbeitung</span>',
        'abgeschlossen'  => '<span class="badge badge-abge">Abgeschlossen</span>',
        default          => '<span class="badge bg-secondary">' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</span>',
    };
}

function buildQuery(array $overrides = []): string
{
    $params = array_merge([
        'page'   => $_GET['page']   ?? 1,
        'status' => $_GET['status'] ?? '',
        'search' => $_GET['search'] ?? '',
    ], $overrides);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return http_build_query($params);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads – FinanzForensik Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing:border-box; }
        :root {
            --bg-deep:#0a1628; --bg-card:#0f1e35; --bg-sidebar:#0d1a2e;
            --gold:#c9a84c; --gold-light:#e8c96a; --border:rgba(201,168,76,0.2);
            --text:#e0e0e0; --muted:#7a9ab8; --sidebar-w:260px;
        }
        body { background:var(--bg-deep); color:var(--text); font-family:'Segoe UI',Arial,sans-serif; min-height:100vh; margin:0; }

        /* Sidebar */
        .sidebar { position:fixed; top:0; left:0; bottom:0; width:var(--sidebar-w); background:var(--bg-sidebar); border-right:1px solid var(--border); display:flex; flex-direction:column; z-index:100; }
        .sidebar-brand { padding:24px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:12px; }
        .sidebar-brand .icon { width:40px; height:40px; background:linear-gradient(135deg,var(--gold),var(--gold-light)); border-radius:10px; display:flex; align-items:center; justify-content:center; }
        .sidebar-brand .icon i { color:#0a1628; font-size:18px; }
        .sidebar-brand h2 { font-size:15px; font-weight:700; color:#fff; margin:0; }
        .sidebar-brand span { color:var(--gold); }
        .sidebar-brand p { font-size:11px; color:var(--muted); margin:2px 0 0; }
        .sidebar-nav { flex:1; padding:16px 0; overflow-y:auto; }
        .nav-section { padding:8px 20px 4px; font-size:10px; font-weight:600; text-transform:uppercase; color:var(--muted); letter-spacing:.8px; }
        .nav-link { display:flex; align-items:center; gap:10px; padding:10px 20px; color:var(--muted); text-decoration:none; font-size:14px; border-left:3px solid transparent; transition:all .15s; }
        .nav-link i { width:18px; text-align:center; font-size:15px; }
        .nav-link:hover { color:#fff; background:rgba(201,168,76,0.06); border-left-color:rgba(201,168,76,0.4); }
        .nav-link.active { color:var(--gold); background:rgba(201,168,76,0.1); border-left-color:var(--gold); font-weight:600; }
        .nav-link.logout { color:#ff6b7a; }
        .nav-link.logout:hover { background:rgba(220,53,69,0.08); border-left-color:rgba(220,53,69,0.5); }
        .sidebar-footer { padding:16px 20px; border-top:1px solid var(--border); font-size:12px; color:var(--muted); }
        .sidebar-footer strong { color:#fff; }

        /* Main */
        .main { margin-left:var(--sidebar-w); padding:32px; min-height:100vh; }
        .page-header { margin-bottom:24px; display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:12px; }
        .page-header h1 { font-size:24px; font-weight:700; color:#fff; margin:0; }
        .page-header p { color:var(--muted); font-size:14px; margin:4px 0 0; }

        /* Filter bar */
        .filter-bar { background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; }
        .filter-bar .form-control, .filter-bar .form-select { background:#162236; border:1px solid var(--border); color:var(--text); border-radius:8px; font-size:13px; padding:8px 12px; }
        .filter-bar .form-control:focus, .filter-bar .form-select:focus { border-color:var(--gold); box-shadow:0 0 0 3px rgba(201,168,76,0.12); background:#162236; color:#fff; }
        .filter-bar .form-select option { background:#162236; }

        /* Buttons */
        .btn-gold { background:linear-gradient(135deg,var(--gold),var(--gold-light)); color:#0a1628; border:none; font-weight:600; font-size:13px; padding:8px 16px; border-radius:8px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:opacity .15s; }
        .btn-gold:hover { opacity:.88; color:#0a1628; }
        .btn-outline-sm { background:transparent; border:1px solid var(--border); color:var(--muted); font-size:11px; padding:5px 10px; border-radius:6px; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:all .15s; cursor:pointer; }
        .btn-outline-sm:hover { border-color:var(--gold); color:var(--gold); }
        .btn-danger-sm { background:rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.3); color:#ff6b7a; font-size:11px; padding:5px 10px; border-radius:6px; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:all .15s; cursor:pointer; }
        .btn-danger-sm:hover { background:rgba(220,53,69,0.2); }
        .btn-export { background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.3); color:#4ade80; font-size:13px; padding:8px 16px; border-radius:8px; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:all .15s; }
        .btn-export:hover { background:rgba(34,197,94,0.2); color:#4ade80; }

        /* Table */
        .content-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-bottom:24px; }
        .content-card-header { padding:16px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
        .content-card-header h3 { font-size:16px; font-weight:600; color:#fff; margin:0; }
        .table { color:var(--text); margin:0; font-size:13px; }
        .table thead th { background:rgba(201,168,76,0.06); color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.5px; border-bottom:1px solid var(--border); padding:12px 16px; font-weight:500; white-space:nowrap; }
        .table tbody tr { border-bottom:1px solid rgba(255,255,255,0.04); transition:background .1s; }
        .table tbody tr:hover { background:rgba(255,255,255,0.03); }
        .table tbody tr:last-child { border-bottom:none; }
        .table td { padding:10px 16px; vertical-align:middle; }

        /* Badges */
        .badge { font-size:11px; padding:4px 10px; border-radius:20px; font-weight:600; }
        .badge-neu      { background:rgba(234,179,8,0.15); color:#facc15; }
        .badge-in-bearb { background:rgba(59,130,246,0.15); color:#60a5fa; }
        .badge-abge     { background:rgba(34,197,94,0.15);  color:#4ade80; }

        /* Status select inline */
        .status-select { background:#162236; border:1px solid var(--border); color:var(--text); border-radius:6px; font-size:11px; padding:3px 6px; cursor:pointer; }
        .status-select:focus { outline:none; border-color:var(--gold); }

        /* Pagination */
        .pagination-bar { display:flex; gap:6px; align-items:center; flex-wrap:wrap; padding:16px 20px; border-top:1px solid var(--border); }
        .page-btn { padding:5px 12px; border-radius:6px; font-size:13px; text-decoration:none; background:transparent; border:1px solid var(--border); color:var(--muted); transition:all .15s; }
        .page-btn:hover { border-color:var(--gold); color:var(--gold); }
        .page-btn.active { background:linear-gradient(135deg,var(--gold),var(--gold-light)); color:#0a1628; border-color:transparent; font-weight:700; }
        .page-btn.disabled { opacity:.3; pointer-events:none; }
        .page-info { color:var(--muted); font-size:12px; margin-left:auto; }

        /* Modals */
        .modal-content { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; color:var(--text); }
        .modal-header { border-bottom:1px solid var(--border); padding:20px 24px; }
        .modal-header .modal-title { color:#fff; font-weight:700; }
        .modal-header .btn-close { filter:invert(1) opacity(.5); }
        .modal-body { padding:24px; }
        .modal-footer { border-top:1px solid var(--border); padding:16px 24px; }
        .detail-row { display:flex; gap:16px; margin-bottom:14px; }
        .detail-label { min-width:140px; color:var(--muted); font-size:12px; font-weight:500; text-transform:uppercase; }
        .detail-value { color:#fff; font-size:14px; flex:1; word-break:break-word; }

        /* Toast notification */
        .toast-container { position:fixed; bottom:24px; right:24px; z-index:9999; }
        .toast-msg { background:rgba(34,197,94,0.9); color:#fff; padding:10px 18px; border-radius:8px; font-size:13px; display:flex; align-items:center; gap:8px; margin-top:8px; box-shadow:0 4px 20px rgba(0,0,0,0.3); animation:fadeIn .3s ease; }
        .toast-msg.error { background:rgba(220,53,69,0.9); }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

        /* Alert banners */
        .alert-success-bar { background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.3); color:#4ade80; border-radius:8px; padding:10px 16px; font-size:13px; margin-bottom:16px; display:flex; align-items:center; gap:8px; }

        @media(max-width:768px){ .sidebar{transform:translateX(-100%);} .main{margin-left:0;padding:16px;} }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-brand">
        <div class="icon"><i class="fas fa-shield-halved"></i></div>
        <div>
            <h2>Finanz<span>Forensik</span></h2>
            <p>Admin-Panel</p>
        </div>
    </div>
    <div class="sidebar-nav">
        <div class="nav-section">Hauptmenü</div>
        <a href="index.php"  class="nav-link"><i class="fas fa-gauge-high"></i> Dashboard</a>
        <a href="leads.php"  class="nav-link active"><i class="fas fa-users"></i> Leads</a>
        <a href="#"          class="nav-link"><i class="fas fa-calendar-check"></i> Termine</a>
        <a href="#"          class="nav-link"><i class="fas fa-envelope"></i> Nachrichten</a>
        <div class="nav-section" style="margin-top:12px;">System</div>
        <a href="#"          class="nav-link"><i class="fas fa-gear"></i> Einstellungen</a>
        <a href="logout.php" class="nav-link logout"><i class="fas fa-right-from-bracket"></i> Abmelden</a>
    </div>
    <div class="sidebar-footer">
        <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong><br>
        <?= htmlspecialchars($_SESSION['admin_rolle'] ?? '', ENT_QUOTES, 'UTF-8') ?>
    </div>
</nav>

<!-- Main -->
<main class="main">
    <div class="page-header">
        <div>
            <h1>Lead-Verwaltung</h1>
            <p><?= $totalRows ?> Einträge gefunden</p>
        </div>
        <a href="leads.php?action=export" class="btn-export">
            <i class="fas fa-file-csv"></i> CSV Exportieren
        </a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert-success-bar"><i class="fas fa-circle-check"></i> Lead erfolgreich gelöscht.</div>
    <?php endif; ?>

    <!-- Filter bar -->
    <form method="get" action="leads.php" class="filter-bar">
        <div>
            <label style="color:var(--muted);font-size:11px;display:block;margin-bottom:4px;">Status</label>
            <select name="status" class="form-select" style="width:160px;">
                <option value="">Alle Status</option>
                <option value="neu"            <?= $filterStatus === 'neu'            ? 'selected' : '' ?>>Neu</option>
                <option value="in_bearbeitung" <?= $filterStatus === 'in_bearbeitung' ? 'selected' : '' ?>>In Bearbeitung</option>
                <option value="abgeschlossen"  <?= $filterStatus === 'abgeschlossen'  ? 'selected' : '' ?>>Abgeschlossen</option>
            </select>
        </div>
        <div style="flex:1;min-width:200px;">
            <label style="color:var(--muted);font-size:11px;display:block;margin-bottom:4px;">Suche</label>
            <input type="text" name="search" class="form-control" placeholder="Name, E-Mail, Telefon…" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <button type="submit" class="btn-gold" style="align-self:flex-end;">
            <i class="fas fa-search"></i> Filtern
        </button>
        <?php if ($filterStatus || $search): ?>
            <a href="leads.php" class="btn-outline-sm" style="align-self:flex-end;">
                <i class="fas fa-times"></i> Zurücksetzen
            </a>
        <?php endif; ?>
    </form>

    <!-- Leads table -->
    <div class="content-card">
        <div class="content-card-header">
            <h3><i class="fas fa-users me-2" style="color:var(--gold);"></i>Leads</h3>
            <span style="color:var(--muted);font-size:12px;">Seite <?= $currentPage ?> von <?= $totalPages ?></span>
        </div>

        <?php if (empty($leads)): ?>
            <div style="padding:48px;text-align:center;color:var(--muted);">
                <i class="fas fa-inbox" style="font-size:40px;opacity:.4;margin-bottom:12px;display:block;"></i>
                Keine Leads gefunden.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table" id="leadsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>E-Mail</th>
                        <th>Telefon</th>
                        <th>Verlust</th>
                        <th>Plattform</th>
                        <th>Status</th>
                        <th>Erstellt</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($leads as $lead): ?>
                    <tr id="row-<?= (int)$lead['id'] ?>">
                        <td style="color:var(--muted);font-size:12px;">#<?= (int)$lead['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($lead['vorname'] . ' ' . $lead['nachname'], ENT_QUOTES, 'UTF-8') ?></strong>
                        </td>
                        <td style="color:var(--muted);font-size:12px;"><?= htmlspecialchars($lead['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($lead['telefon'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="color:var(--gold);font-weight:600;"><?= htmlspecialchars($lead['verlustbetrag'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="font-size:12px;"><?= htmlspecialchars($lead['plattform'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <select
                                class="status-select"
                                data-id="<?= (int)$lead['id'] ?>"
                                onchange="updateStatus(this)"
                            >
                                <option value="neu"            <?= $lead['status'] === 'neu'            ? 'selected' : '' ?>>Neu</option>
                                <option value="in_bearbeitung" <?= $lead['status'] === 'in_bearbeitung' ? 'selected' : '' ?>>In Bearbeitung</option>
                                <option value="abgeschlossen"  <?= $lead['status'] === 'abgeschlossen'  ? 'selected' : '' ?>>Abgeschlossen</option>
                            </select>
                        </td>
                        <td style="color:var(--muted);font-size:11px;white-space:nowrap;">
                            <?= htmlspecialchars(date('d.m.Y', strtotime($lead['created_at'])), ENT_QUOTES, 'UTF-8') ?><br>
                            <span style="opacity:.6;"><?= htmlspecialchars(date('H:i', strtotime($lead['created_at'])), ENT_QUOTES, 'UTF-8') ?></span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <button
                                    type="button"
                                    class="btn-outline-sm"
                                    onclick="viewLead(<?= (int)$lead['id'] ?>)"
                                    title="Details anzeigen"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn-danger-sm"
                                    onclick="confirmDelete(<?= (int)$lead['id'] ?>, '<?= htmlspecialchars(addslashes($lead['vorname'] . ' ' . $lead['nachname']), ENT_QUOTES, 'UTF-8') ?>')"
                                    title="Löschen"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-bar">
            <?php if ($currentPage > 1): ?>
                <a href="?<?= buildQuery(['page' => $currentPage - 1]) ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            <?php else: ?>
                <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
            <?php endif; ?>

            <?php
            $start = max(1, $currentPage - 2);
            $end   = min($totalPages, $currentPage + 2);
            if ($start > 1): ?><a href="?<?= buildQuery(['page' => 1]) ?>" class="page-btn">1</a><?php if ($start > 2): ?><span style="color:var(--muted);padding:0 4px;">…</span><?php endif; endif;
            for ($p = $start; $p <= $end; $p++): ?>
                <a href="?<?= buildQuery(['page' => $p]) ?>" class="page-btn <?= $p === $currentPage ? 'active' : '' ?>"><?= $p ?></a>
            <?php endfor;
            if ($end < $totalPages): ?><?php if ($end < $totalPages - 1): ?><span style="color:var(--muted);padding:0 4px;">…</span><?php endif; ?><a href="?<?= buildQuery(['page' => $totalPages]) ?>" class="page-btn"><?= $totalPages ?></a><?php endif; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?<?= buildQuery(['page' => $currentPage + 1]) ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            <?php else: ?>
                <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
            <?php endif; ?>

            <span class="page-info"><?= $totalRows ?> Einträge, <?= $perPage ?>/Seite</span>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- ============================================================ -->
<!-- Delete Confirmation Modal -->
<!-- ============================================================ -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-trash me-2" style="color:#ff6b7a;"></i>Lead löschen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Möchten Sie den Lead <strong id="deleteLeadName"></strong> wirklich unwiderruflich löschen?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-sm" data-bs-dismiss="modal">Abbrechen</button>
                <form method="post" action="leads.php" id="deleteForm" style="display:inline;">
                    <input type="hidden" name="action"    value="delete">
                    <input type="hidden" name="lead_id"   id="deleteLeadId">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" class="btn-danger-sm" style="padding:7px 14px;font-size:13px;">
                        <i class="fas fa-trash"></i> Löschen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- Lead Detail Modal -->
<!-- ============================================================ -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user me-2" style="color:var(--gold);"></i>Lead Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <div style="text-align:center;padding:32px;color:var(--muted);">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px;"></i>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-sm" data-bs-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Lead data for JS -->
<script>
const leadsData = <?= json_encode(array_column($leads, null, 'id'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ---- Status update via AJAX ----
function updateStatus(select) {
    const id     = select.dataset.id;
    const status = select.value;
    const fd     = new FormData();
    fd.append('action',   'update_status');
    fd.append('lead_id',  id);
    fd.append('status',   status);

    fetch('leads.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => showToast(data.success ? 'Status aktualisiert.' : data.message, data.success))
        .catch(() => showToast('Netzwerkfehler.', false));
}

// ---- Delete confirm ----
function confirmDelete(id, name) {
    document.getElementById('deleteLeadId').value = id;
    document.getElementById('deleteLeadName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// ---- View lead details ----
function viewLead(id) {
    const lead = leadsData[id];
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));

    if (!lead) {
        document.getElementById('detailModalBody').innerHTML = '<p style="color:#ff6b7a;">Lead nicht gefunden.</p>';
        modal.show();
        return;
    }

    const esc = str => String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');

    const statusMap = { 'neu':'badge-neu', 'in_bearbeitung':'badge-in-bearb', 'abgeschlossen':'badge-abge' };
    const statusLabel = { 'neu':'Neu', 'in_bearbeitung':'In Bearbeitung', 'abgeschlossen':'Abgeschlossen' };

    document.getElementById('detailModalBody').innerHTML = `
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <div class="detail-row"><span class="detail-label">Vorname</span><span class="detail-value">${esc(lead.vorname)}</span></div>
                <div class="detail-row"><span class="detail-label">Nachname</span><span class="detail-value">${esc(lead.nachname)}</span></div>
                <div class="detail-row"><span class="detail-label">E-Mail</span><span class="detail-value"><a href="mailto:${esc(lead.email)}" style="color:var(--gold);">${esc(lead.email)}</a></span></div>
                <div class="detail-row"><span class="detail-label">Telefon</span><span class="detail-value">${esc(lead.telefon)}</span></div>
                <div class="detail-row"><span class="detail-label">Land</span><span class="detail-value">${esc(lead.land)}</span></div>
                <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value"><span class="badge ${statusMap[lead.status] || 'bg-secondary'}">${statusLabel[lead.status] || esc(lead.status)}</span></span></div>
            </div>
            <div>
                <div class="detail-row"><span class="detail-label">Verlustbetrag</span><span class="detail-value" style="color:var(--gold);font-weight:700;">${esc(lead.verlustbetrag)}</span></div>
                <div class="detail-row"><span class="detail-label">Plattform</span><span class="detail-value">${esc(lead.plattform)}</span></div>
                <div class="detail-row"><span class="detail-label">Zahlungsmethode</span><span class="detail-value">${esc(lead.zahlungsmethode)}</span></div>
                <div class="detail-row"><span class="detail-label">Wunschtermin</span><span class="detail-value">${esc(lead.wunschtermin) || '–'}</span></div>
                <div class="detail-row"><span class="detail-label">IP-Adresse</span><span class="detail-value" style="color:var(--muted);font-size:12px;">${esc(lead.ip_address)}</span></div>
                <div class="detail-row"><span class="detail-label">Erstellt am</span><span class="detail-value" style="font-size:12px;">${esc(lead.created_at)}</span></div>
            </div>
        </div>
        ${lead.nachricht ? `<div style="margin-top:16px;padding:16px;background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:8px;"><div style="color:var(--muted);font-size:11px;text-transform:uppercase;margin-bottom:8px;">Nachricht</div><p style="margin:0;font-size:14px;line-height:1.6;">${esc(lead.nachricht)}</p></div>` : ''}
    `;
    modal.show();
}

// ---- Toast ----
function showToast(msg, success = true) {
    const container = document.getElementById('toastContainer');
    const el = document.createElement('div');
    el.className = 'toast-msg' + (success ? '' : ' error');
    el.innerHTML = `<i class="fas fa-${success ? 'circle-check' : 'circle-exclamation'}"></i> ${msg}`;
    container.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

// Auto-open detail modal if URL has ?view=id
<?php if ($viewLead): ?>
window.addEventListener('DOMContentLoaded', () => viewLead(<?= (int)$viewLead['id'] ?>));
<?php endif; ?>
</script>
</body>
</html>
