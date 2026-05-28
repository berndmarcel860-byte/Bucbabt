<?php
// config.php handles session_start() with secure cookie params
require_once 'config.php';

$csrf_token = generate_csrf_token();
$site_name = SITE_NAME;
$site_email = SITE_EMAIL;

try {
    $pdo = get_db_connection();
    $customization = get_site_customization($pdo);
} catch (PDOException $e) {
    error_log('index2 customization read error: ' . $e->getCode());
    $customization = [
        'logo_url' => '',
        'phone' => SITE_PHONE,
        'accountant_name' => 'Johannes Kiehl',
        'whatsapp_number' => WHATSAPP_NUMBER,
        'navbar_background_color' => '#0a1628',
        'hero_title' => "Johannes Kiehl –\nIhr persönlicher Experte für internationale Betrugsfälle",
        'hero_subtitle' => 'Als erfolgreicher Accounting-Berater für Betrugsplattformen begleite ich Sie bei der vollständigen Aufarbeitung Ihres Falls: präzise Finanzflussanalyse, professionelle Dokumentation und persönliche Betreuung bis zur Einreichung bei Behörden und Anwälten.',
        'footer_tagline' => 'Persönlicher Accounting-Berater für internationale Betrugsfälle – professionell, diskret und vertrauenswürdig.',
    ];
}

$site_phone = (string)$customization['phone'];
$whatsapp_number = (string)$customization['whatsapp_number'];
$accountant_name = (string)$customization['accountant_name'];
$logo_url = trim((string)$customization['logo_url']);
$hero_title = (string)$customization['hero_title'];
$hero_subtitle = (string)$customization['hero_subtitle'];
$footer_tagline = (string)$customization['footer_tagline'];
$navbar_bg_color = preg_match('/^#[a-fA-F0-9]{6}$/', (string)$customization['navbar_background_color']) ? strtolower((string)$customization['navbar_background_color']) : '#0a1628';
?>
<!DOCTYPE html>
<html lang="de" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Johannes Kiehl | Accounting-Berater für internationale Betrugsfälle</title>
    <meta name="description" content="Johannes Kiehl – erfolgreicher Accounting-Berater für Betrugsplattformen. Professionelle Fallanalyse, Finanzdokumentation und persönliche Begleitung bei internationalem Investmentbetrug.">
    <meta name="author" content="<?php echo htmlspecialchars($accountant_name, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
    <meta name="robots" content="index, follow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Shared CSS (identical to index.php) -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ============================================================
     NAVIGATION
============================================================ -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav" style="--navbar-custom-bg: <?php echo htmlspecialchars($navbar_bg_color, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>;">
    <div class="container">
        <a class="navbar-brand" href="#">
            <?php if ($logo_url !== ''): ?>
                <img src="<?php echo htmlspecialchars($logo_url, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>" alt="Logo" style="height:34px;width:auto;max-width:140px;object-fit:contain;" class="me-2">
            <?php else: ?>
                <i class="fas fa-user-tie me-2 text-gold"></i>
            <?php endif; ?>
            <span class="brand-name"><?php echo htmlspecialchars($accountant_name, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></span>
            <span class="brand-sub">Berater</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#leistungen">Leistungen</a></li>
                <li class="nav-item"><a class="nav-link" href="#ueber-mich">Über mich</a></li>
                <li class="nav-item"><a class="nav-link" href="#prozess">Vorgehen</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonials">Referenzen</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontakt">Kontakt</a></li>
            </ul>
            <div class="navbar-nav d-flex align-items-center">
                <a class="nav-phone me-3" href="tel:<?php echo htmlspecialchars($site_phone, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                    <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($site_phone, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
                </a>
                <a class="btn btn-gold" href="#kontakt">Kostenlose Prüfung</a>
            </div>
        </div>
    </div>
</nav>

<!-- ============================================================
     HERO SECTION
============================================================ -->
<section class="hero-section" id="hero">
    <div id="particles-js"></div>
    <div class="hero-overlay"></div>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-9 mx-auto text-center hero-content">
                <div class="hero-badge animate-fadeInDown">
                    <i class="fas fa-briefcase me-2"></i>
                    Zertifizierter Accounting-Berater &amp; Betrugsanalyst
                </div>
                <h1 class="hero-title animate-fadeInUp">
                    <?php echo nl2br(htmlspecialchars($hero_title, ENT_QUOTES | ENT_HTML5, 'UTF-8')); ?>
                </h1>
                <p class="hero-subtitle animate-fadeInUp delay-1">
                    <?php echo nl2br(htmlspecialchars($hero_subtitle, ENT_QUOTES | ENT_HTML5, 'UTF-8')); ?>
                </p>
                <div class="hero-buttons animate-fadeInUp delay-2">
                    <a href="#kontakt" class="btn btn-gold btn-lg me-3">
                        <i class="fas fa-magnifying-glass me-2"></i>
                        Kostenlose Erstprüfung
                    </a>
                    <a href="#prozess" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-calendar-check me-2"></i>
                        Termin vereinbaren
                    </a>
                </div>
                <div class="trust-badges animate-fadeInUp delay-3">
                    <span class="trust-badge"><i class="fas fa-lock me-1"></i>SSL Secure</span>
                    <span class="trust-badge"><i class="fas fa-shield-check me-1"></i>DSGVO-konform</span>
                    <span class="trust-badge"><i class="fas fa-globe me-1"></i>International</span>
                    <span class="trust-badge"><i class="fas fa-user-tie me-1"></i>Persönliche Beratung</span>
                    <span class="trust-badge"><i class="fas fa-clock me-1"></i>Antwort in 24h</span>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- ============================================================
     LIVE ACTIVITY WIDGET
============================================================ -->
<div class="activity-widget" id="activityWidget">
    <div class="activity-header">
        <span class="activity-dot" id="activityDot"></span>
        <span class="activity-title">Live-Aktivität</span>
    </div>
    <div class="activity-message" id="activityMessage">
        <i class="fas fa-circle-check me-2 text-success"></i>
        <span id="activityText">Neue Analyse gestartet – gerade eben</span>
    </div>
</div>

<!-- ============================================================
     STATISTICS
============================================================ -->
<section class="stats-section section-padding" id="statistiken">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Meine Expertise</span>
            <h2 class="section-title">Vertrauen durch nachweisbare Ergebnisse</h2>
            <p class="section-subtitle">Über 10 Jahre Erfahrung als Accounting-Berater für internationale Betrugsplattformen</p>
        </div>
        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-folder-open"></i></div>
                    <div class="stat-number"><span class="counter" data-target="250">0</span>+</div>
                    <div class="stat-label">Analysierte Betrugsfälle</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-globe"></i></div>
                    <div class="stat-number"><span class="counter" data-target="40">0</span>+</div>
                    <div class="stat-label">Länder – internationale Fälle</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-euro-sign"></i></div>
                    <div class="stat-number"><span class="counter" data-target="8">0</span>+ Mio. €</div>
                    <div class="stat-label">Dokumentierter Schadensumfang</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <div class="stat-number"><span class="counter" data-target="97">0</span>%</div>
                    <div class="stat-label">Mandantenzufriedenheit</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-number">24h</div>
                    <div class="stat-label">Erstreaktion garantiert</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     SERVICES
============================================================ -->
<section class="services-section section-padding" id="leistungen">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Leistungen</span>
            <h2 class="section-title">Professionelle Beratung bei Betrugsfällen</h2>
            <p class="section-subtitle">Umfassendes Leistungsspektrum für Privatpersonen bei nationalem und internationalem Investmentbetrug</p>
        </div>
        <div class="row g-4">
            <?php
            $services2 = [
                ['icon' => 'fa-magnifying-glass-chart', 'title' => 'Transaktions- & Finanzflussanalyse', 'desc' => 'Accounting-orientierte Aufarbeitung aller Zahlungsströme, Wallet-Bewegungen und Gegenparteien.'],
                ['icon' => 'fa-folder-open',             'title' => 'Strukturierte Falldokumentation',  'desc' => 'Professionelle Zusammenstellung aller Belege, Kommunikationsverläufe und Zeitachsen.'],
                ['icon' => 'fa-link',                    'title' => 'Blockchain-Spurenanalyse',          'desc' => 'Nachverfolgung von Krypto-Transaktionen über verschiedene Netzwerke und Börsen.'],
                ['icon' => 'fa-shield-halved',           'title' => 'Plattform-Risikobewertung',         'desc' => 'Technische Überprüfung verdächtiger Broker, Handelsplattformen und Fake-Investoren.'],
                ['icon' => 'fa-file-contract',           'title' => 'Behördenfähige Berichte',           'desc' => 'Erstellung professioneller Analyseberichte für BaFin, Staatsanwaltschaft und Anwälte.'],
                ['icon' => 'fa-handshake-angle',         'title' => 'Persönliche Begleitung',            'desc' => 'Direkte Beratung mit klaren nächsten Schritten und kontinuierlichen Status-Updates.'],
                ['icon' => 'fa-scale-balanced',          'title' => 'Unterstützung bei Rückforderungen', 'desc' => 'Technisch-dokumentarische Vorbereitung von Rückforderungsverfahren.'],
                ['icon' => 'fa-earth-americas',          'title' => 'Internationale Fallbegleitung',     'desc' => 'Erfahrung mit Betrugsfällen aus über 40 Ländern und mehrsprachiger Kommunikation.'],
                ['icon' => 'fa-user-secret',             'title' => 'Diskrete Verarbeitung',             'desc' => 'Absolute Vertraulichkeit und DSGVO-konforme Datenverarbeitung für jeden Mandanten.'],
            ];
            foreach ($services2 as $svc): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="service-card glass-card animate-on-scroll">
                    <div class="service-icon">
                        <i class="fas <?php echo $svc['icon']; ?>"></i>
                    </div>
                    <h5 class="service-title"><?php echo htmlspecialchars($svc['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></h5>
                    <p class="service-desc"><?php echo htmlspecialchars($svc['desc'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></p>
                    <a href="#kontakt" class="service-link">
                        Jetzt anfragen <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     ÜBER MICH
============================================================ -->
<section class="about-section section-padding" id="ueber-mich">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 animate-on-scroll">
                <span class="section-badge">Über mich</span>
                <h2 class="section-title mt-2">Johannes Kiehl – Ihr vertrauenswürdiger Berater</h2>
                <p class="about-text">
                    Als zertifizierter Accounting-Berater mit über <strong>10 Jahren Erfahrung</strong> in der Analyse
                    von Betrugsplattformen und internationalen Investmentfällen biete ich meinen Mandanten eine
                    professionelle, strukturierte und absolut diskrete Unterstützung.
                </p>
                <p class="about-text">
                    Mein Fokus liegt auf der präzisen Dokumentation von Finanzflüssen, der Identifikation
                    verdächtiger Transaktionsmuster und der Erstellung behördenfähiger Berichte –
                    als verlässliche Grundlage für rechtliche und behördliche Schritte.
                </p>
                <p class="about-text">
                    Ich arbeite eng mit einem Netzwerk aus internationalen Forensik-Experten, Compliance-Beratern
                    und juristischen Partnern zusammen. Jeder Fall erhält meine persönliche Aufmerksamkeit –
                    ohne Versprechen, die ich nicht halten kann, aber mit maximaler Sorgfalt.
                </p>
                <div class="about-certifications mt-4">
                    <span class="cert-badge"><i class="fas fa-certificate me-1"></i>Zertifizierter Accounting-Berater</span>
                    <span class="cert-badge"><i class="fas fa-award me-1"></i>Fraud &amp; Compliance Spezialist</span>
                    <span class="cert-badge"><i class="fas fa-shield-check me-1"></i>DSGVO-zertifiziert</span>
                    <span class="cert-badge"><i class="fas fa-globe me-1"></i>International akkreditiert</span>
                </div>
            </div>
            <div class="col-lg-6 animate-on-scroll delay-1">
                <div class="about-image-wrapper">
                    <div class="about-image-placeholder">
                        <i class="fas fa-user-tie"></i>
                        <div class="about-image-badge">
                            <i class="fas fa-check-circle me-1"></i>
                            Zertifizierter Berater
                        </div>
                    </div>
                    <div class="about-float-card top-right glass-card">
                        <i class="fas fa-briefcase text-gold me-2"></i>
                        <div>
                            <div class="float-card-title">10+ Jahre Erfahrung</div>
                            <div class="float-card-sub">Accounting &amp; Betrugsanalyse</div>
                        </div>
                    </div>
                    <div class="about-float-card bottom-left glass-card">
                        <i class="fas fa-star text-gold me-2"></i>
                        <div>
                            <div class="float-card-title">4.9/5 Bewertung</div>
                            <div class="float-card-sub">Mandantenzufriedenheit</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     TRUST SECTION
============================================================ -->
<section class="trust-section section-padding" id="vertrauen">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Vertrauen</span>
            <h2 class="section-title">Warum Mandanten mir vertrauen</h2>
            <p class="section-subtitle">Meine Arbeit basiert auf Transparenz, Professionalität und nachweisbarer Expertise</p>
        </div>
        <div class="row g-4">
            <?php
            $trust2 = [
                ['icon' => 'fa-comments',        'title' => 'Transparente Kommunikation',   'desc' => 'Klare und verständliche Kommunikation – ich erkläre jeden Schritt in Ihrem Fall.'],
                ['icon' => 'fa-sitemap',          'title' => 'Strukturierte Prozesse',        'desc' => 'Bewährte Analyseprozesse für zuverlässige, nachvollziehbare Ergebnisse.'],
                ['icon' => 'fa-globe',            'title' => 'Internationale Vernetzung',     'desc' => 'Erfahrenes Netzwerk mit Forensik- und Compliance-Experten in über 40 Ländern.'],
                ['icon' => 'fa-user-secret',      'title' => 'Absolute Vertraulichkeit',      'desc' => 'Jeder Fall wird mit höchster Diskretion behandelt – Ihr Vertrauen ist meine Verpflichtung.'],
                ['icon' => 'fa-database',         'title' => 'Sichere Datenverarbeitung',     'desc' => 'Alle Daten werden nach DSGVO und höchsten Sicherheitsstandards verarbeitet.'],
                ['icon' => 'fa-shield-check',     'title' => 'DSGVO-konform',                 'desc' => 'Vollständige Compliance mit der europäischen Datenschutzgrundverordnung.'],
                ['icon' => 'fa-graduation-cap',   'title' => 'Nachgewiesene Expertise',       'desc' => 'Zertifizierungen und jahrelange praktische Erfahrung in der Betrugserkennung.'],
                ['icon' => 'fa-handshake',        'title' => 'Persönlicher Einsatz',          'desc' => 'Kein Call-Center – Sie erreichen mich direkt und erhalten persönliche Antworten.'],
            ];
            foreach ($trust2 as $item): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="trust-card glass-card animate-on-scroll">
                    <div class="trust-icon"><i class="fas <?php echo $item['icon']; ?>"></i></div>
                    <h6 class="trust-title"><?php echo htmlspecialchars($item['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></h6>
                    <p class="trust-desc"><?php echo htmlspecialchars($item['desc'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     PROZESS / TIMELINE
============================================================ -->
<section class="process-section section-padding" id="prozess">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Mein Vorgehen</span>
            <h2 class="section-title">Strukturierter Beratungsprozess</h2>
            <p class="section-subtitle">Von der ersten Kontaktaufnahme bis zur vollständigen Falldokumentation</p>
        </div>
        <div class="process-timeline">
            <?php
            $steps2 = [
                ['num' => '01', 'icon' => 'fa-magnifying-glass', 'title' => 'Kostenlose Erstprüfung & Risiko-Scoring', 'desc' => 'Unverbindliche Bewertung Ihres Falls. Ich analysiere Ihre Grundinformationen und gebe eine erste Einschätzung der Situation und Handlungsmöglichkeiten.'],
                ['num' => '02', 'icon' => 'fa-chart-line',        'title' => 'Forensische Finanzflussanalyse',         'desc' => 'Detaillierte Analyse aller relevanten Zahlungsströme, Wallets und Kontobewegungen. Identifikation verdächtiger Transaktionsmuster mit klarer Dokumentation.'],
                ['num' => '03', 'icon' => 'fa-file-shield',       'title' => 'Professionelles Dokumentenpaket',       'desc' => 'Erstellung eines vollständigen, behördenfähigen Fallpakets mit Zeitachse, Transaktionsübersicht und strukturiertem Analysebericht.'],
                ['num' => '04', 'icon' => 'fa-handshake',         'title' => 'Begleitete Umsetzung & Follow-up',      'desc' => 'Kontinuierliche Betreuung mit klaren Status-Updates. Unterstützung bei der Einreichung und Koordination mit Behörden, Anwälten und Partnernetzwerken.'],
            ];
            foreach ($steps2 as $i => $step): ?>
            <div class="process-step animate-on-scroll" style="--delay: <?php echo $i * 0.2; ?>s">
                <div class="process-number"><?php echo $step['num']; ?></div>
                <div class="process-icon"><i class="fas <?php echo $step['icon']; ?>"></i></div>
                <h5 class="process-title"><?php echo htmlspecialchars($step['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></h5>
                <p class="process-desc"><?php echo htmlspecialchars($step['desc'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     URGENCY CTA
============================================================ -->
<section class="urgency-section">
    <div class="container">
        <div class="urgency-inner animate-on-scroll">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="urgency-icon"><i class="fas fa-triangle-exclamation"></i></div>
                    <h3 class="urgency-title">Handeln Sie jetzt – digitale Spuren verblassen</h3>
                    <p class="urgency-text">
                        Je früher ein Betrugsfall analysiert wird, desto lückenloser lassen sich Zahlungswege
                        rekonstruieren. Warten Sie nicht – kontaktieren Sie mich noch heute für eine kostenlose
                        Ersteinschätzung Ihres Falls.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="#kontakt" class="btn btn-gold btn-lg">
                        <i class="fas fa-magnifying-glass me-2"></i>
                        Jetzt Fall prüfen lassen
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     TESTIMONIALS
============================================================ -->
<section class="testimonials-section section-padding" id="testimonials">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Referenzen</span>
            <h2 class="section-title">Was meine Mandanten sagen</h2>
            <p class="section-subtitle">Vertrauen basiert auf Erfahrungen – echte Rückmeldungen aus der Praxis</p>
        </div>
        <div class="row g-4">
            <?php
            $testimonials2 = [
                ['initial' => 'C.H.', 'city' => 'Hamburg',   'stars' => 5, 'text' => 'Johannes Kiehl hat meinen Fall mit außerordentlicher Sorgfalt analysiert. Jede Transaktion wurde lückenlos dokumentiert, die Kommunikation war stets klar und transparent. In einer sehr belastenden Situation hat er mir echte Orientierung gegeben.'],
                ['initial' => 'M.B.', 'city' => 'München',   'stars' => 5, 'text' => 'Nach einem erheblichen Verlust durch eine Fake-Investmentplattform wandte ich mich an Herrn Kiehl. Die Analyse war ausgesprochen professionell – ich erhielt ein vollständiges Dokumentenpaket, das meinem Anwalt weitergeholfen hat.'],
                ['initial' => 'S.R.', 'city' => 'Berlin',    'stars' => 5, 'text' => 'Ich schätze besonders die persönliche und direkte Erreichbarkeit. Herr Kiehl hat mich durch den gesamten Prozess begleitet und alle Fragen geduldig und kompetent beantwortet. Eine sehr professionelle und diskrete Zusammenarbeit.'],
                ['initial' => 'T.K.', 'city' => 'Frankfurt', 'stars' => 5, 'text' => 'Hervorragende fachliche Kompetenz gepaart mit menschlichem Einfühlungsvermögen. Der Analysebericht war präzise und für Behörden eingereicht worden. Keine übertriebenen Versprechen – dafür umso mehr echte, professionelle Unterstützung.'],
                ['initial' => 'A.W.', 'city' => 'Köln',      'stars' => 5, 'text' => 'Mein Fall hatte internationalen Bezug und Johannes Kiehl konnte auf ein breites Netzwerk zurückgreifen. Die Bearbeitung war schnell, strukturiert und vollständig transparent. Ich kann ihn absolut weiterempfehlen.'],
                ['initial' => 'P.L.', 'city' => 'Stuttgart', 'stars' => 5, 'text' => 'Ich war skeptisch – aber die Professionalität und das Fachwissen von Herrn Kiehl haben mich restlos überzeugt. Er lieferte realistische Einschätzungen ohne falsche Hoffnungen zu wecken. Genau das hatte ich gebraucht.'],
            ];
            foreach ($testimonials2 as $t): ?>
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card glass-card animate-on-scroll">
                    <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
                    <div class="testimonial-stars">
                        <?php for ($s = 0; $s < $t['stars']; $s++): ?><i class="fas fa-star text-gold"></i><?php endfor; ?>
                    </div>
                    <p class="testimonial-text"><?php echo htmlspecialchars($t['text'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar"><?php echo htmlspecialchars($t['initial'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></div>
                        <div>
                            <div class="testimonial-name"><?php echo htmlspecialchars($t['initial'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></div>
                            <div class="testimonial-city"><i class="fas fa-location-dot me-1"></i><?php echo htmlspecialchars($t['city'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></div>
                        </div>
                        <div class="ms-auto">
                            <span class="testimonial-verified"><i class="fas fa-circle-check me-1"></i>Verifiziert</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     FAQ
============================================================ -->
<section class="faq-section section-padding" id="faq">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">FAQ</span>
            <h2 class="section-title">Häufig gestellte Fragen</h2>
            <p class="section-subtitle">Antworten auf die wichtigsten Fragen zu meiner Beratung</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <?php
                    $faqs2 = [
                        ['q' => 'Ist die Erstprüfung wirklich kostenlos?',
                         'a' => 'Ja, die erste Einschätzung Ihres Falls ist vollständig kostenlos und unverbindlich. Ich bewerte Ihre Situation und gebe Ihnen eine ehrliche Einschätzung der Möglichkeiten.'],
                        ['q' => 'Können Sie mir garantieren, dass ich mein Geld zurückbekomme?',
                         'a' => 'Eine Rückerstattungsgarantie kann niemand seriös geben. Mein Ziel ist die professionelle Analyse und Dokumentation Ihres Falls, um die bestmögliche Basis für weitere rechtliche Schritte zu schaffen.'],
                        ['q' => 'Wie läuft der Prozess ab?',
                         'a' => 'Nach Ihrer Anfrage melde ich mich innerhalb von 24 Stunden. Wir besprechen Ihren Fall, ich erstelle eine Erstbewertung und lege dann gemeinsam mit Ihnen die nächsten Schritte fest.'],
                        ['q' => 'Welche Unterlagen benötige ich?',
                         'a' => 'Für eine gründliche Analyse benötige ich: Transaktionsbelege, Kontoauszüge, E-Mail-Korrespondenz, Screenshots von Plattformen, Wallet-Adressen sowie alle Vertragsunterlagen.'],
                        ['q' => 'Wie wird meine Vertraulichkeit gewährleistet?',
                         'a' => 'Alle Informationen werden streng vertraulich und vollständig DSGVO-konform behandelt. Ihre Daten werden ausschließlich für die Fallbearbeitung genutzt.'],
                    ];
                    foreach ($faqs2 as $i => $faq): ?>
                    <div class="accordion-item glass-card mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $i > 0 ? 'collapsed' : ''; ?>"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq<?php echo $i; ?>">
                                <?php echo htmlspecialchars($faq['q'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
                            </button>
                        </h2>
                        <div id="faq<?php echo $i; ?>" class="accordion-collapse collapse <?php echo $i === 0 ? 'show' : ''; ?>"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo htmlspecialchars($faq['a'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CONTACT FORM
============================================================ -->
<section class="contact-section section-padding" id="kontakt">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4 animate-on-scroll">
                <div class="contact-info">
                    <span class="section-badge">Kontakt</span>
                    <h2 class="section-title mt-2">Nehmen Sie Kontakt auf</h2>
                    <p class="contact-intro">Schildern Sie mir Ihren Fall. Die erste Prüfung ist kostenlos und unverbindlich.</p>
                    <div class="contact-items mt-4">
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <div class="contact-item-label">Telefon</div>
                                <a href="tel:<?php echo htmlspecialchars($site_phone, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>" class="contact-item-value"><?php echo htmlspecialchars($site_phone, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div class="contact-item-label">E-Mail</div>
                                <a href="mailto:<?php echo htmlspecialchars($site_email, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>" class="contact-item-value"><?php echo htmlspecialchars($site_email, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fab fa-whatsapp"></i></div>
                            <div>
                                <div class="contact-item-label">WhatsApp</div>
                                <a href="https://wa.me/<?php echo htmlspecialchars($whatsapp_number, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>" class="contact-item-value">Jetzt schreiben</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fas fa-clock"></i></div>
                            <div>
                                <div class="contact-item-label">Erreichbarkeit</div>
                                <div class="contact-item-value">Mo–Fr: 9:00–18:00 Uhr</div>
                            </div>
                        </div>
                    </div>
                    <div class="contact-security mt-4">
                        <i class="fas fa-lock me-2 text-gold"></i>
                        <small>Verschlüsselte &amp; DSGVO-konforme Übertragung</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 animate-on-scroll delay-1">
                <div class="contact-form-wrapper glass-card">
                    <!-- Progress Bar -->
                    <div class="form-progress-bar">
                        <div class="progress-step active" data-step="1">
                            <div class="progress-step-num">1</div>
                            <div class="progress-step-label">Persönliche Daten</div>
                        </div>
                        <div class="progress-connector"></div>
                        <div class="progress-step" data-step="2">
                            <div class="progress-step-num">2</div>
                            <div class="progress-step-label">Fall-Details</div>
                        </div>
                        <div class="progress-connector"></div>
                        <div class="progress-step" data-step="3">
                            <div class="progress-step-num">3</div>
                            <div class="progress-step-label">Nachricht</div>
                        </div>
                    </div>

                    <form id="contactForm" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                        <!-- Honeypot -->
                        <div style="position:absolute;left:-9999px;opacity:0;pointer-events:none;">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <!-- Step 1 -->
                        <div class="form-step active" id="step1">
                            <h5 class="form-step-title"><i class="fas fa-user me-2 text-gold"></i>Ihre Kontaktdaten</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Vorname *</label>
                                    <input type="text" class="form-control" name="vorname" placeholder="Max" required>
                                    <div class="invalid-feedback">Bitte geben Sie Ihren Vornamen ein.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nachname *</label>
                                    <input type="text" class="form-control" name="nachname" placeholder="Mustermann" required>
                                    <div class="invalid-feedback">Bitte geben Sie Ihren Nachnamen ein.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Telefonnummer *</label>
                                    <input type="tel" class="form-control" name="telefon" placeholder="+49 123 456789" required>
                                    <div class="invalid-feedback">Bitte geben Sie Ihre Telefonnummer ein.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">E-Mail-Adresse *</label>
                                    <input type="email" class="form-control" name="email" placeholder="max@beispiel.de" required>
                                    <div class="invalid-feedback">Bitte geben Sie eine gültige E-Mail-Adresse ein.</div>
                                </div>
                            </div>
                            <div class="form-nav mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-gold next-step">
                                    Weiter <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="form-step" id="step2">
                            <h5 class="form-step-title"><i class="fas fa-file-alt me-2 text-gold"></i>Details zu Ihrem Fall</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Ungefährer Verlustbetrag *</label>
                                    <select class="form-select" name="verlustbetrag" required>
                                        <option value="">Bitte auswählen</option>
                                        <option value="unter_1000">Unter 1.000 €</option>
                                        <option value="1000_5000">1.000 € – 5.000 €</option>
                                        <option value="5000_15000">5.000 € – 15.000 €</option>
                                        <option value="15000_50000">15.000 € – 50.000 €</option>
                                        <option value="50000_100000">50.000 € – 100.000 €</option>
                                        <option value="ueber_100000">Über 100.000 €</option>
                                    </select>
                                    <div class="invalid-feedback">Bitte wählen Sie einen Verlustbetrag.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Plattform / Broker *</label>
                                    <input type="text" class="form-control" name="plattform" placeholder="Name der Plattform" required>
                                    <div class="invalid-feedback">Bitte geben Sie den Namen der Plattform an.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Zahlungsmethode *</label>
                                    <select class="form-select" name="zahlungsmethode" required>
                                        <option value="">Bitte auswählen</option>
                                        <option value="krypto">Kryptowährung</option>
                                        <option value="bankueberweisung">Banküberweisung</option>
                                        <option value="kreditkarte">Kreditkarte</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="skrill">Skrill / Neteller</option>
                                        <option value="sonstige">Sonstige</option>
                                    </select>
                                    <div class="invalid-feedback">Bitte wählen Sie eine Zahlungsmethode.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Land *</label>
                                    <select class="form-select" name="land" required>
                                        <option value="">Bitte auswählen</option>
                                        <option value="DE">Deutschland</option>
                                        <option value="AT">Österreich</option>
                                        <option value="CH">Schweiz</option>
                                        <option value="LU">Luxemburg</option>
                                        <option value="sonstiges">Sonstiges</option>
                                    </select>
                                    <div class="invalid-feedback">Bitte wählen Sie Ihr Land.</div>
                                </div>
                            </div>
                            <div class="form-nav mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Zurück
                                </button>
                                <button type="button" class="btn btn-gold next-step">
                                    Weiter <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="form-step" id="step3">
                            <h5 class="form-step-title"><i class="fas fa-message me-2 text-gold"></i>Ihre Nachricht</h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Beschreiben Sie Ihren Fall *</label>
                                    <textarea class="form-control" name="nachricht" rows="5" placeholder="Schildern Sie kurz, was passiert ist, wann Sie zahlen mussten und welche Erfahrungen Sie mit der Plattform gemacht haben..." required minlength="20"></textarea>
                                    <div class="invalid-feedback">Bitte beschreiben Sie Ihren Fall (mindestens 20 Zeichen).</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Wunschtermin für Erstgespräch</label>
                                    <input type="date" class="form-control" name="wunschtermin" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="datenschutz" id="datenschutzCheck" required>
                                        <label class="form-check-label" for="datenschutzCheck">
                                            Ich habe die <a href="#datenschutz" class="text-gold">Datenschutzerklärung</a> gelesen und stimme der Verarbeitung meiner Daten zu. *
                                        </label>
                                        <div class="invalid-feedback">Bitte stimmen Sie der Datenschutzerklärung zu.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-nav mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Zurück
                                </button>
                                <button type="submit" class="btn btn-gold" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Anfrage senden
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" id="submitSpinner"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Success / Error -->
                        <div id="formSuccess" class="form-success d-none">
                            <div class="success-icon"><i class="fas fa-circle-check"></i></div>
                            <h5>Anfrage erfolgreich gesendet!</h5>
                            <p>Vielen Dank für Ihre Nachricht. Ich melde mich innerhalb von 24 Stunden persönlich bei Ihnen. Bitte überprüfen Sie auch Ihren Spam-Ordner.</p>
                        </div>
                        <div id="formError" class="form-error d-none">
                            <i class="fas fa-circle-exclamation me-2"></i>
                            <span id="formErrorText">Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CALLBACK STICKY BAR
============================================================ -->
<div class="callback-bar" id="callbackBar">
    <div class="container">
        <div class="callback-inner">
            <div class="callback-text">
                <i class="fas fa-phone-volume me-2 text-gold"></i>
                <strong>Rückruf anfordern</strong>
                <span class="d-none d-md-inline ms-2">– Ich rufe Sie kostenlos zurück</span>
            </div>
            <form class="callback-form" id="callbackForm">
                <input type="text" class="form-control" name="cb_name" placeholder="Ihr Name" required>
                <input type="tel" class="form-control" name="cb_telefon" placeholder="+49 ..." required>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                <input type="hidden" name="type" value="callback">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-phone me-1"></i> Rückruf
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="main-footer" id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <?php if ($logo_url !== ''): ?>
                            <img src="<?php echo htmlspecialchars($logo_url, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>" alt="Logo" style="height:34px;width:auto;max-width:140px;object-fit:contain;" class="me-2">
                        <?php else: ?>
                            <i class="fas fa-user-tie me-2 text-gold"></i>
                        <?php endif; ?>
                        <span class="brand-name"><?php echo htmlspecialchars($accountant_name, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></span>
                        <span class="brand-sub">Berater</span>
                    </div>
                    <p class="footer-tagline"><?php echo htmlspecialchars($footer_tagline, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></p>
                    <div class="footer-badges mt-3">
                        <span class="footer-badge"><i class="fas fa-lock me-1"></i>SSL Secured</span>
                        <span class="footer-badge"><i class="fas fa-shield-check me-1"></i>DSGVO</span>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <h6 class="footer-heading">Leistungen</h6>
                    <ul class="footer-links">
                        <li><a href="#leistungen">Finanzflussanalyse</a></li>
                        <li><a href="#leistungen">Falldokumentation</a></li>
                        <li><a href="#leistungen">Blockchain-Analyse</a></li>
                        <li><a href="#leistungen">Behördliche Berichte</a></li>
                        <li><a href="#leistungen">Rückforderungsunterstützung</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <h6 class="footer-heading">Navigation</h6>
                    <ul class="footer-links">
                        <li><a href="#ueber-mich">Über mich</a></li>
                        <li><a href="#prozess">Mein Vorgehen</a></li>
                        <li><a href="#testimonials">Referenzen</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="#kontakt">Kontakt</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <h6 class="footer-heading">Rechtliches</h6>
                    <ul class="footer-links">
                        <li><a href="#impressum" id="impressum">Impressum</a></li>
                        <li><a href="#datenschutz" id="datenschutz">Datenschutz</a></li>
                        <li><a href="#agb">AGB</a></li>
                        <li><a href="#haftungsausschluss">Haftungsausschluss</a></li>
                        <li><a href="#cookie">Cookie-Richtlinie</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <h6 class="footer-heading">Kontakt</h6>
                    <address class="footer-address">
                        <div><i class="fas fa-location-dot me-2 text-gold"></i>Maximilianstraße 35<br>80539 München</div>
                        <div class="mt-2"><i class="fas fa-phone me-2 text-gold"></i><a href="tel:<?php echo htmlspecialchars($site_phone, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>"><?php echo htmlspecialchars($site_phone, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></a></div>
                        <div class="mt-2"><i class="fas fa-envelope me-2 text-gold"></i><a href="mailto:<?php echo htmlspecialchars($site_email, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>"><?php echo htmlspecialchars($site_email, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></a></div>
                        <div class="mt-2"><i class="fas fa-clock me-2 text-gold"></i>Mo–Fr: 9:00–18:00</div>
                    </address>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($accountant_name, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>. Alle Rechte vorbehalten.</small>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <small class="footer-disclaimer">
                        <i class="fas fa-info-circle me-1"></i>
                        Rechtlicher Hinweis: Keine Rechtsberatung. Leistungen umfassen Finanzanalyse, Dokumentation und Informationsaufbereitung.
                    </small>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- ============================================================
     FLOATING BUTTONS
============================================================ -->
<a href="https://wa.me/<?php echo htmlspecialchars($whatsapp_number, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>" class="floating-btn whatsapp-btn" target="_blank" rel="noopener" title="WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<button class="floating-btn scroll-top-btn" id="scrollTopBtn" title="Nach oben">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<!-- Site configuration for JS -->
<script>
window.siteConfig = {
    whatsappNumber: '<?php echo htmlspecialchars($whatsapp_number, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>'
};
</script>
<!-- Shared JS (same as index.php) -->
<script src="js/ajax.js"></script>
</body>
</html>
