<?php
declare(strict_types=1);

require_once '../config.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$csrfToken = generate_csrf_token();
$errors = [];

try {
    $pdo = get_db_connection();
    $customization = get_site_customization($pdo);
} catch (PDOException $e) {
    error_log('Settings bootstrap error: ' . $e->getCode());
    $customization = [
        'logo_url' => '',
        'phone' => SITE_PHONE,
        'accountant_name' => 'Johannes Kiehl',
        'whatsapp_number' => WHATSAPP_NUMBER,
        'navbar_background_color' => '#0a1628',
        'hero_title' => '',
        'hero_subtitle' => '',
        'footer_tagline' => '',
    ];
    $errors[] = 'Datenbankverbindung fehlgeschlagen.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($token)) {
        $errors[] = 'Ungültige Sitzung. Bitte Seite neu laden.';
    }

    $logoUrl = trim((string)($_POST['logo_url'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $accountantName = trim((string)($_POST['accountant_name'] ?? ''));
    $whatsappNumber = preg_replace('/\D+/', '', (string)($_POST['whatsapp_number'] ?? ''));
    $navbarColor = trim((string)($_POST['navbar_background_color'] ?? '#0a1628'));
    $heroTitle = trim((string)($_POST['hero_title'] ?? ''));
    $heroSubtitle = trim((string)($_POST['hero_subtitle'] ?? ''));
    $footerTagline = trim((string)($_POST['footer_tagline'] ?? ''));

    if ($logoUrl !== '' && filter_var($logoUrl, FILTER_VALIDATE_URL) === false) {
        $errors[] = 'Logo-URL ist ungültig.';
    }
    if ($phone === '' || mb_strlen($phone) > 50) {
        $errors[] = 'Telefonnummer ist erforderlich (max. 50 Zeichen).';
    }
    if ($accountantName === '' || mb_strlen($accountantName) > 255) {
        $errors[] = 'Name ist erforderlich (max. 255 Zeichen).';
    }
    if ($whatsappNumber === '' || mb_strlen($whatsappNumber) < 8 || mb_strlen($whatsappNumber) > 20) {
        $errors[] = 'WhatsApp-Nummer muss 8-20 Ziffern enthalten.';
    }
    if (!preg_match('/^#[a-fA-F0-9]{6}$/', $navbarColor)) {
        $errors[] = 'Navbar-Farbe muss ein Hexwert sein (z. B. #0a1628).';
    }
    if ($heroTitle === '') {
        $errors[] = 'Hero-Titel darf nicht leer sein.';
    }
    if ($heroSubtitle === '') {
        $errors[] = 'Hero-Untertitel darf nicht leer sein.';
    }
    if ($footerTagline === '') {
        $errors[] = 'Footer-Text darf nicht leer sein.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare(
                "UPDATE site_settings
                 SET logo_url = :logo_url,
                     phone = :phone,
                     accountant_name = :accountant_name,
                     whatsapp_number = :whatsapp_number,
                     navbar_background_color = :navbar_color
                 WHERE id = 1"
            );
            $stmt->execute([
                ':logo_url' => $logoUrl !== '' ? $logoUrl : null,
                ':phone' => $phone,
                ':accountant_name' => $accountantName,
                ':whatsapp_number' => $whatsappNumber,
                ':navbar_color' => strtolower($navbarColor),
            ]);

            $stmt = $pdo->prepare(
                "UPDATE site_content
                 SET hero_title = :hero_title,
                     hero_subtitle = :hero_subtitle,
                     footer_tagline = :footer_tagline
                 WHERE id = 1"
            );
            $stmt->execute([
                ':hero_title' => $heroTitle,
                ':hero_subtitle' => $heroSubtitle,
                ':footer_tagline' => $footerTagline,
            ]);

            header('Location: settings.php?saved=1');
            exit;
        } catch (PDOException $e) {
            error_log('Settings update error: ' . $e->getCode());
            $errors[] = 'Speichern fehlgeschlagen.';
        }
    }

    $customization = [
        'logo_url' => $logoUrl,
        'phone' => $phone,
        'accountant_name' => $accountantName,
        'whatsapp_number' => $whatsappNumber,
        'navbar_background_color' => $navbarColor,
        'hero_title' => $heroTitle,
        'hero_subtitle' => $heroSubtitle,
        'footer_tagline' => $footerTagline,
    ];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einstellungen – FinanzForensik Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        :root { --bg-deep:#0a1628; --bg-card:#0f1e35; --bg-sidebar:#0d1a2e; --gold:#c9a84c; --gold-light:#e8c96a; --border:rgba(201,168,76,0.2); --text:#e0e0e0; --muted:#7a9ab8; --sidebar-w:260px; }
        body { background:var(--bg-deep); color:var(--text); font-family:'Segoe UI',Arial,sans-serif; min-height:100vh; margin:0; }
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
        .main { margin-left:var(--sidebar-w); padding:32px; min-height:100vh; }
        .page-header h1 { font-size:24px; font-weight:700; color:#fff; margin:0; }
        .page-header p { color:var(--muted); font-size:14px; margin:4px 0 0; }
        .content-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:24px; margin-top:20px; }
        .form-label { color:#aabcd0; font-size:13px; margin-bottom:6px; font-weight:500; }
        .form-control, .form-control:focus { background:#162236; border:1px solid var(--border); color:var(--text); }
        .form-control:focus { border-color:var(--gold); box-shadow:0 0 0 3px rgba(201,168,76,0.12); }
        .btn-gold { background:linear-gradient(135deg,var(--gold),var(--gold-light)); color:#0a1628; border:none; font-weight:600; font-size:13px; padding:9px 18px; border-radius:8px; }
        .btn-gold:hover { opacity:.9; color:#0a1628; }
        .form-text { color:var(--muted); }
    </style>
</head>
<body>
<nav class="sidebar">
    <div class="sidebar-brand">
        <div class="icon"><i class="fas fa-shield-halved"></i></div>
        <div><h2>Finanz<span>Forensik</span></h2><p>Admin-Panel</p></div>
    </div>
    <div class="sidebar-nav">
        <div class="nav-section">Hauptmenü</div>
        <a href="index.php" class="nav-link"><i class="fas fa-gauge-high"></i> Dashboard</a>
        <a href="leads.php" class="nav-link"><i class="fas fa-users"></i> Leads</a>
        <a href="#" class="nav-link"><i class="fas fa-calendar-check"></i> Termine</a>
        <a href="#" class="nav-link"><i class="fas fa-envelope"></i> Nachrichten</a>
        <div class="nav-section" style="margin-top:12px;">System</div>
        <a href="settings.php" class="nav-link active"><i class="fas fa-gear"></i> Einstellungen</a>
        <a href="logout.php" class="nav-link logout"><i class="fas fa-right-from-bracket"></i> Abmelden</a>
    </div>
    <div class="sidebar-footer">
        <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong><br>
        <?= htmlspecialchars($_SESSION['admin_rolle'] ?? '', ENT_QUOTES, 'UTF-8') ?>
    </div>
</nav>

<main class="main">
    <div class="page-header">
        <h1>Website-Einstellungen</h1>
        <p>Bearbeiten Sie Logo, Kontakt und Startseiten-Texte für index2.php.</p>
    </div>

    <?php if (isset($_GET['saved'])): ?>
        <div class="alert alert-success">Einstellungen wurden gespeichert.</div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="content-card">
        <form method="post" action="settings.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <h5 class="mb-3">Allgemeine Angaben</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Logo URL</label>
                    <input type="url" class="form-control" name="logo_url" value="<?= htmlspecialchars((string)$customization['logo_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://example.com/logo.png">
                    <small class="form-text">Optional. Bei leerem Feld wird das Icon angezeigt.</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Telefonnummer</label>
                    <input type="text" class="form-control" name="phone" maxlength="50" required value="<?= htmlspecialchars((string)$customization['phone'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">WhatsApp Nummer</label>
                    <input type="text" class="form-control" name="whatsapp_number" required value="<?= htmlspecialchars((string)$customization['whatsapp_number'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Name des Accountants</label>
                    <input type="text" class="form-control" name="accountant_name" maxlength="255" required value="<?= htmlspecialchars((string)$customization['accountant_name'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Navbar Hintergrundfarbe</label>
                    <input type="text" class="form-control" name="navbar_background_color" value="<?= htmlspecialchars((string)$customization['navbar_background_color'], ENT_QUOTES, 'UTF-8') ?>" placeholder="#0a1628" required>
                </div>
            </div>

            <h5 class="mb-3">Front-End Texte</h5>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Hero Titel</label>
                    <textarea class="form-control" name="hero_title" rows="3" required><?= htmlspecialchars((string)$customization['hero_title'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Hero Untertitel</label>
                    <textarea class="form-control" name="hero_subtitle" rows="4" required><?= htmlspecialchars((string)$customization['hero_subtitle'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Footer Text</label>
                    <textarea class="form-control" name="footer_tagline" rows="3" required><?= htmlspecialchars((string)$customization['footer_tagline'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-gold"><i class="fas fa-floppy-disk me-1"></i>Speichern</button>
        </form>
    </div>
</main>
</body>
</html>
