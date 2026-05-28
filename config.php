<?php
/**
 * Finanzforensik - Konfigurationsdatei
 * German Financial Expert Website - Production Configuration
 */

declare(strict_types=1);

// ---------------------------------------------------------------------------
// Error Handling
// ---------------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/logs/error.log');

// ---------------------------------------------------------------------------
// Site Settings
// ---------------------------------------------------------------------------
define('SITE_NAME',         'Finanzforensik');
define('SITE_EMAIL',        'admin@finanzforensik.de');
define('SITE_PHONE',        '+49 800 000 0000');
define('SITE_ADDRESS',      'Musterstraße 1, 10115 Berlin, Deutschland');
define('WHATSAPP_NUMBER',   getenv('WHATSAPP_NUMBER') !== false ? getenv('WHATSAPP_NUMBER') : '4989123456789');

// ---------------------------------------------------------------------------
// Security Constants
// ---------------------------------------------------------------------------
define('SESSION_LIFETIME',     1800);   // 30 minutes
define('MAX_LOGIN_ATTEMPTS',   5);
define('LOGIN_LOCKOUT_TIME',   900);    // 15 minutes in seconds
define('CSRF_TOKEN_LENGTH',    32);
define('PASSWORD_MIN_LENGTH',  8);
define('RATE_LIMIT_WINDOW',    3600);   // 1 hour in seconds
define('RATE_LIMIT_MAX',       10);

// ---------------------------------------------------------------------------
// Session Configuration (must be set before session_start)
// ---------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    $sessionCookieParams = [
        'lifetime' => SESSION_LIFETIME,
        'path'     => '/',
        'domain'   => '',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Strict',
    ];
    session_set_cookie_params($sessionCookieParams);
    ini_set('session.use_strict_mode',   '1');
    ini_set('session.use_only_cookies',  '1');
    ini_set('session.gc_maxlifetime',    (string) SESSION_LIFETIME);
    session_name('FINANZFORENSIK_SESS');
    session_start();
}

// ---------------------------------------------------------------------------
// Database Connection
// ---------------------------------------------------------------------------
function get_db_connection(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $host    = getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost';
    $dbname  = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'financial_expert';
    $user    = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
    // DB_PASS must be set via environment variable in production
    $pass    = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci",
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        http_response_code(503);
        die(json_encode(['error' => 'Service temporarily unavailable.']));
    }

    return $pdo;
}

// ---------------------------------------------------------------------------
// CSRF Token Helpers
// ---------------------------------------------------------------------------
function generate_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token']      = bin2hex(random_bytes(CSRF_TOKEN_LENGTH));
        $_SESSION['csrf_token_time'] = time();
    }

    return $_SESSION['csrf_token'];
}

function validate_csrf_token(string $token): bool
{
    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time'])) {
        return false;
    }

    // Token expires with the session lifetime
    if ((time() - $_SESSION['csrf_token_time']) > SESSION_LIFETIME) {
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
        return false;
    }

    $valid = hash_equals($_SESSION['csrf_token'], $token);

    if ($valid) {
        // Rotate token after successful validation
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
    }

    return $valid;
}

// ---------------------------------------------------------------------------
// Utility Helpers
// ---------------------------------------------------------------------------
function get_client_ip(): string
{
    $headers = [
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'REMOTE_ADDR',
    ];

    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ip = trim(explode(',', $_SERVER[$header])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function sanitize_input(string $value): string
{
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function ensure_site_customization_tables(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS site_settings (
            id TINYINT NOT NULL DEFAULT 1,
            logo_url VARCHAR(500) DEFAULT NULL,
            phone VARCHAR(50) NOT NULL,
            accountant_name VARCHAR(255) NOT NULL,
            whatsapp_number VARCHAR(30) NOT NULL,
            navbar_background_color VARCHAR(20) NOT NULL DEFAULT '#0a1628',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            CHECK (id = 1)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS site_content (
            id TINYINT NOT NULL DEFAULT 1,
            hero_title TEXT NOT NULL,
            hero_subtitle TEXT NOT NULL,
            footer_tagline TEXT NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            CHECK (id = 1)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $stmt = $pdo->prepare(
        "INSERT INTO site_settings (id, logo_url, phone, accountant_name, whatsapp_number, navbar_background_color)
         VALUES (1, NULL, :phone, :accountant_name, :whatsapp_number, :navbar_color)
         ON DUPLICATE KEY UPDATE id = id"
    );
    $stmt->execute([
        ':phone' => SITE_PHONE,
        ':accountant_name' => 'Johannes Kiehl',
        ':whatsapp_number' => WHATSAPP_NUMBER,
        ':navbar_color' => '#0a1628',
    ]);

    $stmt = $pdo->prepare(
        "INSERT INTO site_content (id, hero_title, hero_subtitle, footer_tagline)
         VALUES (1, :hero_title, :hero_subtitle, :footer_tagline)
         ON DUPLICATE KEY UPDATE id = id"
    );
    $stmt->execute([
        ':hero_title' => "Johannes Kiehl –\nIhr persönlicher Experte für internationale Betrugsfälle",
        ':hero_subtitle' => 'Als erfolgreicher Accounting-Berater für Betrugsplattformen begleite ich Sie bei der vollständigen Aufarbeitung Ihres Falls: präzise Finanzflussanalyse, professionelle Dokumentation und persönliche Betreuung bis zur Einreichung bei Behörden und Anwälten.',
        ':footer_tagline' => 'Persönlicher Accounting-Berater für internationale Betrugsfälle – professionell, diskret und vertrauenswürdig.',
    ]);
}

function get_site_customization(PDO $pdo): array
{
    $defaults = [
        'logo_url' => '',
        'phone' => SITE_PHONE,
        'accountant_name' => 'Johannes Kiehl',
        'whatsapp_number' => WHATSAPP_NUMBER,
        'navbar_background_color' => '#0a1628',
        'hero_title' => "Johannes Kiehl –\nIhr persönlicher Experte für internationale Betrugsfälle",
        'hero_subtitle' => 'Als erfolgreicher Accounting-Berater für Betrugsplattformen begleite ich Sie bei der vollständigen Aufarbeitung Ihres Falls: präzise Finanzflussanalyse, professionelle Dokumentation und persönliche Betreuung bis zur Einreichung bei Behörden und Anwälten.',
        'footer_tagline' => 'Persönlicher Accounting-Berater für internationale Betrugsfälle – professionell, diskret und vertrauenswürdig.',
    ];

    ensure_site_customization_tables($pdo);

    $settingsStmt = $pdo->query("SELECT logo_url, phone, accountant_name, whatsapp_number, navbar_background_color FROM site_settings WHERE id = 1 LIMIT 1");
    $settings = $settingsStmt->fetch() ?: [];

    $contentStmt = $pdo->query("SELECT hero_title, hero_subtitle, footer_tagline FROM site_content WHERE id = 1 LIMIT 1");
    $content = $contentStmt->fetch() ?: [];

    return array_merge($defaults, array_filter(array_merge($settings, $content), static fn ($value) => $value !== null));
}
