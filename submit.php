<?php
declare(strict_types=1);

require_once 'config.php';

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// ----------------------------------------------------------------
// Only POST allowed
// ----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Methode nicht erlaubt.']);
    exit;
}

// ----------------------------------------------------------------
// CSRF Validation
// ----------------------------------------------------------------
$submittedToken = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($submittedToken)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Ungültige Sitzung. Bitte laden Sie die Seite neu.']);
    exit;
}

// ----------------------------------------------------------------
// Honeypot check (bot trap)
// ----------------------------------------------------------------
if (!empty($_POST['website'])) {
    // Silent success – do not process
    echo json_encode(['success' => true, 'message' => 'Vielen Dank! Ihre Anfrage wurde erfolgreich übermittelt. Wir melden uns innerhalb von 24 Stunden bei Ihnen.']);
    exit;
}

// ----------------------------------------------------------------
// Rate limiting
// ----------------------------------------------------------------
$clientIp = get_client_ip();
$action   = sanitize_input($_POST['action'] ?? 'contact');

try {
    $pdo = get_db_connection();

    // Clean up expired windows first
    $cleanStmt = $pdo->prepare(
        "DELETE FROM rate_limits WHERE ip_address = ? AND action = ? AND window_start < DATE_SUB(NOW(), INTERVAL ? SECOND)"
    );
    $cleanStmt->execute([$clientIp, 'contact_form', RATE_LIMIT_WINDOW]);

    // Check current count
    $rateStmt = $pdo->prepare(
        "SELECT attempts FROM rate_limits WHERE ip_address = ? AND action = ? AND window_start >= DATE_SUB(NOW(), INTERVAL ? SECOND)"
    );
    $rateStmt->execute([$clientIp, 'contact_form', RATE_LIMIT_WINDOW]);
    $rateRow = $rateStmt->fetch();

    if ($rateRow && (int)$rateRow['attempts'] >= 3) {
        http_response_code(429);
        echo json_encode(['success' => false, 'message' => 'Zu viele Anfragen. Bitte versuchen Sie es später erneut.']);
        exit;
    }

} catch (PDOException $e) {
    error_log('Rate limit check failed: ' . $e->getCode());
    // Non-fatal – continue processing
}

// ----------------------------------------------------------------
// Callback action (minimal lead)
// ----------------------------------------------------------------
if ($action === 'callback') {
    // Accept both field naming conventions (name/phone and cb_name/cb_telefon)
    $name  = sanitize_input($_POST['cb_name']    ?? $_POST['name']  ?? '');
    $phone = sanitize_input($_POST['cb_telefon'] ?? $_POST['phone'] ?? '');

    if (empty($name) || empty($phone)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Name und Telefonnummer sind erforderlich.']);
        exit;
    }

    try {
        $nameParts = explode(' ', $name, 2);
        $vorname   = $nameParts[0];
        $nachname  = $nameParts[1] ?? '';

        $stmt = $pdo->prepare(
            "INSERT INTO leads (vorname, nachname, telefon, email, verlustbetrag, plattform, zahlungsmethode, land, nachricht, ip_address, user_agent, status)
             VALUES (?, ?, ?, '', 'Unbekannt', 'Rückruf', 'Unbekannt', 'Unbekannt', 'Rückrufanfrage', ?, ?, 'neu')"
        );
        $stmt->execute([
            $vorname,
            $nachname,
            $phone,
            $clientIp,
            substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        ]);

        updateRateLimit($pdo, $clientIp);

        echo json_encode(['success' => true, 'message' => 'Vielen Dank! Wir rufen Sie zurück.']);
    } catch (PDOException $e) {
        error_log('Callback insert failed: ' . $e->getCode());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.']);
    }
    exit;
}

// ----------------------------------------------------------------
// Main form submission
// ----------------------------------------------------------------
$vorname         = sanitize_input($_POST['vorname']         ?? '');
$nachname        = sanitize_input($_POST['nachname']        ?? '');
$telefon         = sanitize_input($_POST['telefon']         ?? '');
$email           = trim($_POST['email']                     ?? '');
$verlustbetrag   = sanitize_input($_POST['verlustbetrag']   ?? '');
$plattform       = sanitize_input($_POST['plattform']       ?? '');
$zahlungsmethode = sanitize_input($_POST['zahlungsmethode'] ?? '');
$land            = sanitize_input($_POST['land']            ?? '');
$nachricht       = sanitize_input($_POST['nachricht']       ?? '');
$wunschtermin    = sanitize_input($_POST['wunschtermin']    ?? '');

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.']);
    exit;
}
$email = htmlspecialchars(filter_var($email, FILTER_SANITIZE_EMAIL), ENT_QUOTES | ENT_HTML5, 'UTF-8');

// Required fields
$required = [
    'Vorname'         => $vorname,
    'Nachname'        => $nachname,
    'Telefon'         => $telefon,
    'E-Mail'          => $email,
    'Verlustbetrag'   => $verlustbetrag,
    'Plattform'       => $plattform,
    'Zahlungsmethode' => $zahlungsmethode,
    'Land'            => $land,
];

foreach ($required as $label => $value) {
    if (empty($value)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => "Bitte füllen Sie das Feld \"{$label}\" aus."]);
        exit;
    }
}

// Wunschtermin: validate date format if provided
$wunschterminDb = null;
if (!empty($wunschtermin)) {
    $d = \DateTime::createFromFormat('Y-m-d', $wunschtermin);
    if ($d && $d->format('Y-m-d') === $wunschtermin) {
        $wunschterminDb = $wunschtermin;
    }
}

try {
    // Insert lead
    $insertStmt = $pdo->prepare(
        "INSERT INTO leads
            (vorname, nachname, telefon, email, verlustbetrag, plattform, zahlungsmethode, land, nachricht, wunschtermin, ip_address, user_agent, status)
         VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'neu')"
    );
    $insertStmt->execute([
        $vorname,
        $nachname,
        $telefon,
        $email,
        $verlustbetrag,
        $plattform,
        $zahlungsmethode,
        $land,
        $nachricht,
        $wunschterminDb,
        $clientIp,
        substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);

    updateRateLimit($pdo, $clientIp);

    // Send email notification
    sendNotificationEmail($vorname, $nachname, $telefon, $email, $verlustbetrag, $plattform, $zahlungsmethode, $land, $nachricht, $wunschtermin);

    echo json_encode([
        'success' => true,
        'message' => 'Vielen Dank! Ihre Anfrage wurde erfolgreich übermittelt. Wir melden uns innerhalb von 24 Stunden bei Ihnen.',
    ]);

} catch (PDOException $e) {
    error_log('Lead insert failed: ' . $e->getCode());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut oder kontaktieren Sie uns telefonisch.']);
}
exit;

// ----------------------------------------------------------------
// Helper: update rate_limits table
// ----------------------------------------------------------------
function updateRateLimit(PDO $pdo, string $ip): void
{
    $existing = $pdo->prepare(
        "SELECT id, attempts FROM rate_limits WHERE ip_address = ? AND action = ? AND window_start >= DATE_SUB(NOW(), INTERVAL ? SECOND)"
    );
    $existing->execute([$ip, 'contact_form', RATE_LIMIT_WINDOW]);
    $row = $existing->fetch();

    if ($row) {
        $upd = $pdo->prepare("UPDATE rate_limits SET attempts = attempts + 1 WHERE id = ?");
        $upd->execute([$row['id']]);
    } else {
        $ins = $pdo->prepare("INSERT INTO rate_limits (ip_address, action, attempts) VALUES (?, 'contact_form', 1)");
        $ins->execute([$ip]);
    }
}

// ----------------------------------------------------------------
// Helper: send HTML email notification
// ----------------------------------------------------------------
function sendNotificationEmail(
    string $vorname,
    string $nachname,
    string $telefon,
    string $email,
    string $verlustbetrag,
    string $plattform,
    string $zahlungsmethode,
    string $land,
    string $nachricht,
    string $wunschtermin
): void {
    $to      = SITE_EMAIL;
    $subject = '=?UTF-8?B?' . base64_encode('Neue Anfrage: ' . $vorname . ' ' . $nachname) . '?=';
    $date    = date('d.m.Y H:i');

    $body = <<<HTML
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Neue Anfrage</title></head>
<body style="font-family:Arial,sans-serif;background:#f5f5f5;padding:20px;">
  <div style="max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="background:#0a1628;padding:24px 32px;">
      <h1 style="color:#c9a84c;margin:0;font-size:22px;">Neue Anfrage eingegangen</h1>
      <p style="color:#aaa;margin:4px 0 0;">{$date}</p>
    </div>
    <div style="padding:32px;">
      <table style="width:100%;border-collapse:collapse;">
        <tr><td style="padding:8px 0;color:#666;width:40%;border-bottom:1px solid #eee;"><strong>Name</strong></td>
            <td style="padding:8px 0;border-bottom:1px solid #eee;">{$vorname} {$nachname}</td></tr>
        <tr><td style="padding:8px 0;color:#666;border-bottom:1px solid #eee;"><strong>E-Mail</strong></td>
            <td style="padding:8px 0;border-bottom:1px solid #eee;"><a href="mailto:{$email}">{$email}</a></td></tr>
        <tr><td style="padding:8px 0;color:#666;border-bottom:1px solid #eee;"><strong>Telefon</strong></td>
            <td style="padding:8px 0;border-bottom:1px solid #eee;">{$telefon}</td></tr>
        <tr><td style="padding:8px 0;color:#666;border-bottom:1px solid #eee;"><strong>Verlustbetrag</strong></td>
            <td style="padding:8px 0;border-bottom:1px solid #eee;">{$verlustbetrag}</td></tr>
        <tr><td style="padding:8px 0;color:#666;border-bottom:1px solid #eee;"><strong>Plattform</strong></td>
            <td style="padding:8px 0;border-bottom:1px solid #eee;">{$plattform}</td></tr>
        <tr><td style="padding:8px 0;color:#666;border-bottom:1px solid #eee;"><strong>Zahlungsmethode</strong></td>
            <td style="padding:8px 0;border-bottom:1px solid #eee;">{$zahlungsmethode}</td></tr>
        <tr><td style="padding:8px 0;color:#666;border-bottom:1px solid #eee;"><strong>Land</strong></td>
            <td style="padding:8px 0;border-bottom:1px solid #eee;">{$land}</td></tr>
        <tr><td style="padding:8px 0;color:#666;border-bottom:1px solid #eee;"><strong>Wunschtermin</strong></td>
            <td style="padding:8px 0;border-bottom:1px solid #eee;">{$wunschtermin}</td></tr>
        <tr><td style="padding:8px 0;color:#666;vertical-align:top;"><strong>Nachricht</strong></td>
            <td style="padding:8px 0;">{$nachricht}</td></tr>
      </table>
    </div>
    <div style="background:#f9f9f9;padding:16px 32px;border-top:1px solid #eee;text-align:center;color:#999;font-size:12px;">
      Diese E-Mail wurde automatisch vom System generiert – bitte nicht direkt antworten.
    </div>
  </div>
</body>
</html>
HTML;

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . SITE_NAME . " <no-reply@finanzforensik.de>\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    @mail($to, $subject, $body, $headers);
}
