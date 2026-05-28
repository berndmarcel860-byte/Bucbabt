<?php
declare(strict_types=1);

require_once '../config.php';

// If already logged in, redirect to dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$errorMessage   = '';
$lockoutMessage = '';
$csrfToken      = generate_csrf_token();

// Rate limiting via session (per IP / per browser session)
if (!isset($_SESSION['login_attempts']))   $_SESSION['login_attempts']    = 0;
if (!isset($_SESSION['login_locked_until'])) $_SESSION['login_locked_until'] = 0;

$isLocked = time() < (int)$_SESSION['login_locked_until'];

// ----------------------------------------------------------------
// Handle POST
// ----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isLocked) {

    $token    = $_POST['csrf_token'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password']     ?? '';

    if (!validate_csrf_token($token)) {
        $errorMessage = 'Ungültige Sitzung. Bitte laden Sie die Seite neu.';
    } elseif (empty($username) || empty($password)) {
        $errorMessage = 'Bitte füllen Sie alle Felder aus.';
    } else {
        try {
            $pdo  = get_db_connection();
            $stmt = $pdo->prepare("SELECT id, username, password_hash, name, rolle FROM admins WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Success – set session
                $_SESSION['admin_id']       = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name']     = $admin['name'];
                $_SESSION['admin_rolle']    = $admin['rolle'];
                $_SESSION['login_attempts'] = 0;

                // Update last_login
                $upd = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
                $upd->execute([$admin['id']]);

                session_regenerate_id(true);
                header('Location: index.php');
                exit;
            } else {
                $_SESSION['login_attempts']++;
                if ((int)$_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
                    $_SESSION['login_locked_until'] = time() + LOGIN_LOCKOUT_TIME;
                    $isLocked       = true;
                    $lockoutMessage = 'Zu viele fehlgeschlagene Anmeldeversuche. Bitte versuchen Sie es in 15 Minuten erneut.';
                } else {
                    $errorMessage = 'Ungültige Anmeldedaten.';
                }
            }
        } catch (PDOException $e) {
            error_log('Admin login DB error: ' . $e->getCode());
            $errorMessage = 'Ein Systemfehler ist aufgetreten. Bitte versuchen Sie es später erneut.';
        }
    }

    // Regenerate CSRF token after each attempt
    $csrfToken = generate_csrf_token();
}

if ($isLocked && empty($lockoutMessage)) {
    $remaining      = (int)$_SESSION['login_locked_until'] - time();
    $minutes        = (int)ceil($remaining / 60);
    $lockoutMessage = "Konto gesperrt. Bitte versuchen Sie es in {$minutes} Minute(n) erneut.";
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – FinanzForensik</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            background: #0a1628;
            color: #e0e0e0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .login-wrapper { width: 100%; max-width: 420px; padding: 16px; }
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo .shield-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #c9a84c, #e8c96a);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 4px 20px rgba(201,168,76,0.35);
        }
        .login-logo .shield-icon i { font-size: 28px; color: #0a1628; }
        .login-logo h1 { font-size: 22px; font-weight: 700; color: #fff; margin: 0; }
        .login-logo span { color: #c9a84c; }
        .login-card {
            background: #0f1e35;
            border: 1px solid rgba(201,168,76,0.25);
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.4);
        }
        .login-card h2 { font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 24px; text-align: center; }
        .form-label { color: #aabcd0; font-size: 13px; font-weight: 500; margin-bottom: 6px; }
        .form-control {
            background: #162236;
            border: 1px solid rgba(201,168,76,0.2);
            color: #e0e0e0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            background: #162236;
            border-color: #c9a84c;
            box-shadow: 0 0 0 3px rgba(201,168,76,0.15);
            color: #fff;
            outline: none;
        }
        .form-control::placeholder { color: #4a6080; }
        .input-group-text {
            background: #162236;
            border: 1px solid rgba(201,168,76,0.2);
            color: #c9a84c;
            border-radius: 8px 0 0 8px;
        }
        .input-group .form-control { border-radius: 0 8px 8px 0; }
        .btn-login {
            background: linear-gradient(135deg, #c9a84c, #e8c96a);
            border: none;
            color: #0a1628;
            font-weight: 700;
            font-size: 15px;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
            margin-top: 8px;
        }
        .btn-login:hover { opacity: .9; transform: translateY(-1px); }
        .btn-login:disabled { opacity: .5; cursor: not-allowed; }
        .alert-error {
            background: rgba(220,53,69,0.12);
            border: 1px solid rgba(220,53,69,0.4);
            color: #ff6b7a;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .alert-lockout {
            background: rgba(255,165,0,0.1);
            border: 1px solid rgba(255,165,0,0.4);
            color: #ffa500;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .form-check-label { color: #aabcd0; font-size: 13px; }
        .form-check-input:checked { background-color: #c9a84c; border-color: #c9a84c; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #c9a84c; font-size: 13px; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
        .attempts-info { color: #6a8099; font-size: 12px; text-align: center; margin-top: 12px; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-logo">
        <div class="shield-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        <h1>FinanzForensik <span>Admin</span></h1>
    </div>

    <div class="login-card">
        <h2><i class="fas fa-lock me-2" style="color:#c9a84c;font-size:16px;"></i>Anmeldung</h2>

        <?php if ($lockoutMessage): ?>
            <div class="alert-lockout">
                <i class="fas fa-ban me-2"></i><?= htmlspecialchars($lockoutMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php elseif ($errorMessage): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="login.php" autocomplete="off" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <div class="mb-3">
                <label for="username" class="form-label">Benutzername</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Benutzername"
                        autocomplete="username"
                        required
                        <?= $isLocked ? 'disabled' : '' ?>
                        value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Passwort</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Passwort"
                        autocomplete="current-password"
                        required
                        <?= $isLocked ? 'disabled' : '' ?>
                    >
                </div>
            </div>

            <div class="mb-4 d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" <?= $isLocked ? 'disabled' : '' ?>>
                    <label class="form-check-label" for="remember">Angemeldet bleiben</label>
                </div>
            </div>

            <button type="submit" class="btn-login" <?= $isLocked ? 'disabled' : '' ?>>
                <i class="fas fa-sign-in-alt me-2"></i>Anmelden
            </button>

            <?php if (!$isLocked && (int)$_SESSION['login_attempts'] > 0): ?>
                <p class="attempts-info">
                    <?= MAX_LOGIN_ATTEMPTS - (int)$_SESSION['login_attempts'] ?> Versuch(e) verbleibend
                </p>
            <?php endif; ?>
        </form>
    </div>

    <div class="back-link">
        <a href="../index.php"><i class="fas fa-arrow-left me-1"></i>Zurück zur Website</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
