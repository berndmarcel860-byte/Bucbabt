<?php
declare(strict_types=1);

require_once '../config.php';

// Auth guard
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$csrfToken = generate_csrf_token();

// ----------------------------------------------------------------
// Fetch dashboard statistics
// ----------------------------------------------------------------
try {
    $pdo = get_db_connection();

    // Total leads
    $totalStmt  = $pdo->query("SELECT COUNT(*) AS cnt FROM leads");
    $totalLeads = (int)($totalStmt->fetch()['cnt'] ?? 0);

    // New leads today
    $todayStmt  = $pdo->query("SELECT COUNT(*) AS cnt FROM leads WHERE DATE(created_at) = DATE(NOW())");
    $leadsToday = (int)($todayStmt->fetch()['cnt'] ?? 0);

    // Leads by status
    $statusStmt = $pdo->query("SELECT status, COUNT(*) AS cnt FROM leads GROUP BY status");
    $statusRows = $statusStmt->fetchAll();
    $byStatus   = ['neu' => 0, 'in_bearbeitung' => 0, 'abgeschlossen' => 0];
    foreach ($statusRows as $row) {
        $byStatus[$row['status']] = (int)$row['cnt'];
    }

    // Recent 10 leads
    $recentStmt = $pdo->query(
        "SELECT id, vorname, nachname, email, telefon, verlustbetrag, plattform, status, created_at
         FROM leads ORDER BY created_at DESC LIMIT 10"
    );
    $recentLeads = $recentStmt->fetchAll();

} catch (PDOException $e) {
    error_log('Dashboard stats error: ' . $e->getCode());
    $totalLeads  = $leadsToday = 0;
    $byStatus    = ['neu' => 0, 'in_bearbeitung' => 0, 'abgeschlossen' => 0];
    $recentLeads = [];
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
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – FinanzForensik Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        :root {
            --bg-deep:   #0a1628;
            --bg-card:   #0f1e35;
            --bg-sidebar:#0d1a2e;
            --gold:      #c9a84c;
            --gold-light:#e8c96a;
            --border:    rgba(201,168,76,0.2);
            --text:      #e0e0e0;
            --muted:     #7a9ab8;
            --sidebar-w: 260px;
        }
        body { background: var(--bg-deep); color: var(--text); font-family:'Segoe UI',Arial,sans-serif; min-height:100vh; margin:0; }

        /* ---- Sidebar ---- */
        .sidebar {
            position: fixed; top:0; left:0; bottom:0;
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-brand .icon {
            width:40px; height:40px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 10px;
            display:flex; align-items:center; justify-content:center;
        }
        .sidebar-brand .icon i { color:#0a1628; font-size:18px; }
        .sidebar-brand h2 { font-size:15px; font-weight:700; color:#fff; margin:0; }
        .sidebar-brand span { color:var(--gold); }
        .sidebar-brand p { font-size:11px; color:var(--muted); margin:2px 0 0; }

        .sidebar-nav { flex:1; padding:16px 0; overflow-y:auto; }
        .nav-section { padding:8px 20px 4px; font-size:10px; font-weight:600; text-transform:uppercase; color:var(--muted); letter-spacing:.8px; }
        .nav-link {
            display:flex; align-items:center; gap:10px;
            padding:10px 20px;
            color:var(--muted);
            text-decoration:none;
            font-size:14px;
            border-left:3px solid transparent;
            transition:all .15s;
        }
        .nav-link i { width:18px; text-align:center; font-size:15px; }
        .nav-link:hover { color:#fff; background:rgba(201,168,76,0.06); border-left-color:rgba(201,168,76,0.4); }
        .nav-link.active { color:var(--gold); background:rgba(201,168,76,0.1); border-left-color:var(--gold); font-weight:600; }
        .nav-link.logout { color:#ff6b7a; }
        .nav-link.logout:hover { background:rgba(220,53,69,0.08); border-left-color:rgba(220,53,69,0.5); }

        .sidebar-footer { padding:16px 20px; border-top:1px solid var(--border); font-size:12px; color:var(--muted); }
        .sidebar-footer strong { color:#fff; }

        /* ---- Main content ---- */
        .main { margin-left: var(--sidebar-w); padding:32px; min-height:100vh; }
        .page-header { margin-bottom:28px; }
        .page-header h1 { font-size:24px; font-weight:700; color:#fff; margin:0; }
        .page-header p { color:var(--muted); font-size:14px; margin:4px 0 0; }

        /* ---- Stat cards ---- */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(210px,1fr)); gap:20px; margin-bottom:32px; }
        .stat-card {
            background:var(--bg-card);
            border:1px solid var(--border);
            border-radius:14px;
            padding:22px 24px;
            position:relative;
            overflow:hidden;
        }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,var(--gold),var(--gold-light)); }
        .stat-card.blue::before  { background:linear-gradient(90deg,#3b82f6,#60a5fa); }
        .stat-card.green::before { background:linear-gradient(90deg,#22c55e,#4ade80); }
        .stat-card.purple::before{ background:linear-gradient(90deg,#a855f7,#c084fc); }
        .stat-label { font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px; }
        .stat-value { font-size:36px; font-weight:700; color:#fff; line-height:1; }
        .stat-icon { position:absolute; right:20px; top:50%; transform:translateY(-50%); font-size:36px; opacity:.12; color:#fff; }

        /* ---- Content card ---- */
        .content-card {
            background:var(--bg-card);
            border:1px solid var(--border);
            border-radius:14px;
            padding:0;
            overflow:hidden;
            margin-bottom:24px;
        }
        .content-card-header {
            padding:18px 24px;
            border-bottom:1px solid var(--border);
            display:flex; align-items:center; justify-content:space-between;
        }
        .content-card-header h3 { font-size:16px; font-weight:600; color:#fff; margin:0; }

        /* ---- Table ---- */
        .table { color:var(--text); margin:0; font-size:13px; }
        .table thead th { background:rgba(201,168,76,0.06); color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.5px; border-bottom:1px solid var(--border); padding:12px 16px; font-weight:500; }
        .table tbody tr { border-bottom:1px solid rgba(255,255,255,0.04); transition:background .1s; }
        .table tbody tr:hover { background:rgba(255,255,255,0.03); }
        .table tbody tr:last-child { border-bottom:none; }
        .table td { padding:12px 16px; vertical-align:middle; }

        /* ---- Badges ---- */
        .badge { font-size:11px; padding:4px 10px; border-radius:20px; font-weight:600; letter-spacing:.3px; }
        .badge-neu       { background:rgba(234,179,8,0.15);  color:#facc15; }
        .badge-in-bearb  { background:rgba(59,130,246,0.15); color:#60a5fa; }
        .badge-abge      { background:rgba(34,197,94,0.15);  color:#4ade80; }

        /* ---- Buttons ---- */
        .btn-gold { background:linear-gradient(135deg,var(--gold),var(--gold-light)); color:#0a1628; border:none; font-weight:600; font-size:13px; padding:7px 16px; border-radius:8px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:opacity .15s; }
        .btn-gold:hover { opacity:.88; color:#0a1628; }
        .btn-outline-muted { background:transparent; border:1px solid var(--border); color:var(--muted); font-size:12px; padding:5px 12px; border-radius:6px; text-decoration:none; transition:all .15s; }
        .btn-outline-muted:hover { border-color:var(--gold); color:var(--gold); }

        @media(max-width:768px){
            .sidebar { transform:translateX(-100%); }
            .main { margin-left:0; padding:16px; }
        }
    </style>
</head>
<body>

<!-- ========== Sidebar ========== -->
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
        <a href="index.php"   class="nav-link active"><i class="fas fa-gauge-high"></i> Dashboard</a>
        <a href="leads.php"   class="nav-link"><i class="fas fa-users"></i> Leads</a>
        <a href="#"           class="nav-link"><i class="fas fa-calendar-check"></i> Termine</a>
        <a href="#"           class="nav-link"><i class="fas fa-envelope"></i> Nachrichten</a>

        <div class="nav-section" style="margin-top:12px;">System</div>
        <a href="settings.php" class="nav-link"><i class="fas fa-gear"></i> Einstellungen</a>
        <a href="logout.php"  class="nav-link logout"><i class="fas fa-right-from-bracket"></i> Abmelden</a>
    </div>

    <div class="sidebar-footer">
        <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong><br>
        <?= htmlspecialchars($_SESSION['admin_rolle'] ?? '', ENT_QUOTES, 'UTF-8') ?>
    </div>
</nav>

<!-- ========== Main ========== -->
<main class="main">
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Willkommen zurück, <?= htmlspecialchars($_SESSION['admin_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>. Hier ist Ihre Übersicht.</p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Gesamt Leads</div>
            <div class="stat-value"><?= $totalLeads ?></div>
            <i class="fas fa-users stat-icon"></i>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Neue Heute</div>
            <div class="stat-value"><?= $leadsToday ?></div>
            <i class="fas fa-star stat-icon"></i>
        </div>
        <div class="stat-card">
            <div class="stat-label">Offen (Neu)</div>
            <div class="stat-value"><?= $byStatus['neu'] ?></div>
            <i class="fas fa-inbox stat-icon"></i>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">In Bearbeitung</div>
            <div class="stat-value"><?= $byStatus['in_bearbeitung'] ?></div>
            <i class="fas fa-hourglass-half stat-icon"></i>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Abgeschlossen</div>
            <div class="stat-value"><?= $byStatus['abgeschlossen'] ?></div>
            <i class="fas fa-circle-check stat-icon"></i>
        </div>
    </div>

    <!-- Recent leads table -->
    <div class="content-card">
        <div class="content-card-header">
            <h3><i class="fas fa-clock-rotate-left me-2" style="color:var(--gold);"></i>Letzte Anfragen</h3>
            <a href="leads.php" class="btn-outline-muted">Alle anzeigen <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <?php if (empty($recentLeads)): ?>
            <div style="padding:40px;text-align:center;color:var(--muted);">
                <i class="fas fa-inbox" style="font-size:36px;margin-bottom:12px;opacity:.4;"></i>
                <p>Noch keine Leads vorhanden.</p>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>E-Mail</th>
                        <th>Telefon</th>
                        <th>Verlustbetrag</th>
                        <th>Plattform</th>
                        <th>Status</th>
                        <th>Datum</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($recentLeads as $lead): ?>
                    <tr>
                        <td style="color:var(--muted);">#<?= (int)$lead['id'] ?></td>
                        <td><strong><?= htmlspecialchars($lead['vorname'] . ' ' . $lead['nachname'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                        <td style="color:var(--muted);"><?= htmlspecialchars($lead['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($lead['telefon'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="color:var(--gold);font-weight:600;"><?= htmlspecialchars($lead['verlustbetrag'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($lead['plattform'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= statusBadge($lead['status']) ?></td>
                        <td style="color:var(--muted);font-size:12px;"><?= htmlspecialchars(date('d.m.Y H:i', strtotime($lead['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="leads.php?view=<?= (int)$lead['id'] ?>" class="btn-outline-muted" style="font-size:11px;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Quick actions -->
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="leads.php" class="btn-gold"><i class="fas fa-users"></i> Lead-Verwaltung</a>
        <a href="logout.php" style="background:rgba(220,53,69,0.1);border:1px solid rgba(220,53,69,0.3);color:#ff6b7a;font-size:13px;padding:7px 16px;border-radius:8px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            <i class="fas fa-right-from-bracket"></i> Abmelden
        </a>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
