<?php
// config.php handles session_start() with secure cookie params
require_once 'config.php';

$csrf_token = generate_csrf_token();

$site_name  = SITE_NAME;
$site_phone = SITE_PHONE;
$site_email = SITE_EMAIL;
?>
<!DOCTYPE html>
<html lang="de" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Professionelle Finanzanalyse & Betrugsaufklärung | FinanzForensik Expert</title>
    <meta name="description" content="Lizenzierter Finanzexperte und Blockchain-Analyst bietet professionelle Unterstützung bei Anlagebetrug, Krypto-Scams und betrügerischen Investmentplattformen. Diskrete Analyse und Dokumentation.">
    <meta name="keywords" content="Anlagebetrug, Kryptobetrug, Blockchain Analyse, Finanzforensik, Scam Analyse, Fake Broker, Forex Betrug, Krypto Rückforderung, Finanzexperte Deutschland">
    <meta name="author" content="FinanzForensik Expert">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://www.finanzforensik-expert.de/">

    <!-- Open Graph -->
    <meta property="og:title" content="Professionelle Finanzanalyse & Betrugsaufklärung">
    <meta property="og:description" content="Lizenzierter Finanzexperte bietet professionelle Unterstützung bei Anlagebetrug, Krypto-Scams und Finanzforensik.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.finanzforensik-expert.de/">
    <meta property="og:image" content="https://www.finanzforensik-expert.de/assets/images/og-image.jpg">
    <meta property="og:locale" content="de_DE">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="FinanzForensik Expert – Professionelle Betrugsanalyse">
    <meta name="twitter:description" content="Lizenzierter Finanzexperte für Blockchain-Analyse und Betrugsaufklärung.">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrf_token); ?>">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Schema.org LocalBusiness -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "FinanzForensik Expert",
      "description": "Lizenzierter Finanzexperte und Blockchain-Analyst für Betrugsanalyse und Finanzforensik",
      "url": "https://www.finanzforensik-expert.de",
      "telephone": "<?php echo SITE_PHONE; ?>",
      "email": "<?php echo SITE_EMAIL; ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Maximilianstraße 35",
        "addressLocality": "München",
        "postalCode": "80539",
        "addressCountry": "DE"
      },
      "openingHours": "Mo-Fr 09:00-18:00",
      "priceRange": "$$",
      "image": "https://www.finanzforensik-expert.de/assets/images/og-image.jpg"
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Kann verlorenes Geld zurückgeholt werden?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Eine Rückforderung kann nicht garantiert werden. Unsere Aufgabe ist die professionelle Analyse, Dokumentation und Unterstützung bei der Nachverfolgung verdächtiger Transaktionen."
          }
        },
        {
          "@type": "Question",
          "name": "Ist die Erstprüfung kostenlos?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Ja, die erste Prüfung Ihres Falls ist vollständig kostenlos und unverbindlich."
          }
        }
      ]
    }
    </script>
</head>
<body>

<!-- ============================================================
     NAVIGATION
============================================================ -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-shield-halved me-2 text-gold"></i>
            <span class="brand-name">FinanzForensik</span>
            <span class="brand-sub">Expert</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#leistungen">Leistungen</a></li>
                <li class="nav-item"><a class="nav-link" href="#ueber-uns">Über uns</a></li>
                <li class="nav-item"><a class="nav-link" href="#prozess">Prozess</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonials">Referenzen</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontakt">Kontakt</a></li>
            </ul>
            <div class="navbar-nav d-flex align-items-center">
                <a class="nav-phone me-3" href="tel:<?php echo SITE_PHONE; ?>">
                    <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars(SITE_PHONE); ?>
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
                    <i class="fas fa-bolt me-2"></i>
                    Lizenzierter Finanzexperte & Blockchain-Analyst
                </div>
                <h1 class="hero-title animate-fadeInUp">
                    Professionelle Unterstützung bei<br>
                    <span class="text-gold">Anlagebetrug</span> und <span class="text-gold">Krypto-Scams</span>
                </h1>
                <p class="hero-subtitle animate-fadeInUp delay-1">
                    Wir unterstützen Mandanten bei der Analyse, Dokumentation und Nachverfolgung verdächtiger Finanztransaktionen und betrügerischer Investmentplattformen. Professionell, diskret und vertrauenswürdig.
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
                    <span class="trust-badge"><i class="fas fa-key me-1"></i>Verschlüsselt</span>
                    <span class="trust-badge"><i class="fas fa-globe me-1"></i>International</span>
                    <span class="trust-badge"><i class="fas fa-link me-1"></i>Blockchain-Analyse</span>
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
        <span class="activity-dot"></span>
        <span class="activity-title">Live-Aktivität</span>
    </div>
    <div class="activity-message" id="activityMessage">
        <i class="fas fa-circle-check me-2 text-success"></i>
        <span id="activityText">Neue Analyse gestartet – gerade eben</span>
    </div>
</div>

<!-- ============================================================
     TRUST STATISTICS
============================================================ -->
<section class="stats-section section-padding" id="statistiken">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Unsere Expertise</span>
            <h2 class="section-title">Vertrauen durch Zahlen</h2>
            <p class="section-subtitle">Messbare Ergebnisse und bewährte Expertise in der Finanzforensik</p>
        </div>
        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-euro-sign"></i></div>
                    <div class="stat-number"><span class="counter" data-target="12">0</span>+ Mio. €</div>
                    <div class="stat-label">Analysierte Transaktionen</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-folder-open"></i></div>
                    <div class="stat-number"><span class="counter" data-target="2500">0</span>+</div>
                    <div class="stat-label">Bearbeitete Fälle</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-globe"></i></div>
                    <div class="stat-number"><span class="counter" data-target="15">0</span>+</div>
                    <div class="stat-label">Internationale Kooperationen</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <div class="stat-number"><span class="counter" data-target="98">0</span>%</div>
                    <div class="stat-label">Kundenzufriedenheit</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="stat-card glass-card animate-on-scroll">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Erreichbarkeit</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     LEISTUNGEN / SERVICES
============================================================ -->
<section class="services-section section-padding" id="leistungen">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Unsere Leistungen</span>
            <h2 class="section-title">Professionelle Finanzforensik</h2>
            <p class="section-subtitle">Umfassende Expertise für Ihre Situation – von der Erstanalyse bis zur Dokumentation</p>
        </div>
        <div class="row g-4">
            <?php
            $services = [
                ['icon' => 'fa-link', 'title' => 'Blockchain-Analyse', 'desc' => 'Forensische Analyse von Blockchain-Transaktionen und dezentralen Netzwerken mit modernster Technologie.'],
                ['icon' => 'fa-bitcoin-sign', 'title' => 'Krypto-Transaktionsnachverfolgung', 'desc' => 'Nachverfolgung verdächtiger Kryptowährungstransaktionen über verschiedene Netzwerke und Börsen.'],
                ['icon' => 'fa-shield-halved', 'title' => 'Unterstützung bei Anlagebetrug', 'desc' => 'Professionelle Analyse und Dokumentation von Anlagebetrugsfällen für rechtliche Schritte.'],
                ['icon' => 'fa-globe', 'title' => 'Internationale Kooperationen', 'desc' => 'Zusammenarbeit mit internationalen Partnern, Experten und Behörden für eine umfassende Unterstützung.'],
                ['icon' => 'fa-file-contract', 'title' => 'Finanztechnische Dokumentation', 'desc' => 'Erstellung professioneller Analyseberichte und Dokumentationen für behördliche Einreichungen.'],
                ['icon' => 'fa-magnifying-glass-chart', 'title' => 'Analyse verdächtiger Plattformen', 'desc' => 'Technische Überprüfung und Bewertung verdächtiger Investmentplattformen und Broker.'],
                ['icon' => 'fa-wallet', 'title' => 'Wallet-Forensik', 'desc' => 'Detaillierte forensische Untersuchung von Kryptowährungs-Wallets und Transaktionsverläufen.'],
                ['icon' => 'fa-chart-line', 'title' => 'Technische Transaktionsanalyse', 'desc' => 'Detaillierte technische Auswertung von Finanztransaktionen und Zahlungsflüssen.'],
                ['icon' => 'fa-scale-balanced', 'title' => 'Unterstützung bei Rückforderungen', 'desc' => 'Technische und dokumentarische Unterstützung bei rechtlichen Rückforderungsverfahren.'],
                ['icon' => 'fa-microchip', 'title' => 'Digitale Finanzanalyse', 'desc' => 'Umfassende digitale Analyse von Finanzströmen und elektronischen Zahlungsspuren.'],
                ['icon' => 'fa-clipboard-list', 'title' => 'Betrugsdokumentation', 'desc' => 'Systematische Erfassung und Dokumentation aller relevanten Beweise und Transaktionsnachweise.'],
                ['icon' => 'fa-check-double', 'title' => 'Compliance-Beratung', 'desc' => 'Beratung zu regulatorischen Anforderungen und Compliance-Themen im Bereich digitaler Finanzen.'],
            ];
            foreach ($services as $service): ?>
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="service-card glass-card animate-on-scroll">
                    <div class="service-icon">
                        <i class="fas <?php echo $service['icon']; ?>"></i>
                    </div>
                    <h5 class="service-title"><?php echo htmlspecialchars($service['title']); ?></h5>
                    <p class="service-desc"><?php echo htmlspecialchars($service['desc']); ?></p>
                    <a href="#kontakt" class="service-link">
                        Mehr erfahren <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     DASHBOARD PREVIEW
============================================================ -->
<section class="dashboard-section section-padding" id="dashboard">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Technologie</span>
            <h2 class="section-title">Modernste Analyse-Infrastruktur</h2>
            <p class="section-subtitle">Professionelle Tools und Systeme für präzise forensische Analysen</p>
        </div>
        <div class="dashboard-mockup animate-on-scroll">
            <div class="dashboard-header">
                <div class="dashboard-logo">
                    <i class="fas fa-shield-halved me-2"></i>
                    FinanzForensik Analytics Dashboard
                </div>
                <div class="dashboard-controls">
                    <span class="dashboard-status active">System aktiv</span>
                    <i class="fas fa-user-circle ms-3"></i>
                </div>
            </div>
            <div class="dashboard-body">
                <div class="dashboard-sidebar">
                    <a href="#" class="sidebar-item active"><i class="fas fa-chart-pie me-2"></i>Übersicht</a>
                    <a href="#" class="sidebar-item"><i class="fas fa-wallet me-2"></i>Wallets</a>
                    <a href="#" class="sidebar-item"><i class="fas fa-exchange-alt me-2"></i>Transaktionen</a>
                    <a href="#" class="sidebar-item"><i class="fas fa-file-alt me-2"></i>Berichte</a>
                    <a href="#" class="sidebar-item"><i class="fas fa-cog me-2"></i>Einstellungen</a>
                </div>
                <div class="dashboard-main">
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-lg-3">
                            <div class="dash-stat-card">
                                <div class="dash-stat-number text-gold">47</div>
                                <div class="dash-stat-label">Aktive Fälle</div>
                                <div class="dash-stat-change up"><i class="fas fa-arrow-up"></i> +3 diese Woche</div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="dash-stat-card">
                                <div class="dash-stat-number text-gold">1.284</div>
                                <div class="dash-stat-label">Analysierte Wallets</div>
                                <div class="dash-stat-change up"><i class="fas fa-arrow-up"></i> +82 heute</div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="dash-stat-card">
                                <div class="dash-stat-number text-gold">89.432</div>
                                <div class="dash-stat-label">Transaktionen</div>
                                <div class="dash-stat-change up"><i class="fas fa-arrow-up"></i> +1.247 heute</div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="dash-stat-card">
                                <div class="dash-stat-number text-warning">7.2</div>
                                <div class="dash-stat-label">Risiko-Score (Ø)</div>
                                <div class="dash-stat-change down"><i class="fas fa-arrow-down"></i> -0.3 vs. letzte Woche</div>
                            </div>
                        </div>
                    </div>
                    <!-- Fake Chart -->
                    <div class="dash-chart-area mb-3">
                        <div class="dash-chart-title">Transaktionsvolumen – Letzte 30 Tage</div>
                        <svg viewBox="0 0 400 80" class="dash-chart-svg">
                            <polyline points="0,70 30,55 60,60 90,40 120,45 150,30 180,35 210,20 240,25 270,15 300,20 330,10 360,18 400,5" fill="none" stroke="#c9a84c" stroke-width="2"/>
                            <polyline points="0,70 30,55 60,60 90,40 120,45 150,30 180,35 210,20 240,25 270,15 300,20 330,10 360,18 400,5 400,80 0,80" fill="rgba(201,168,76,0.1)" stroke="none"/>
                        </svg>
                    </div>
                    <!-- Fake Transaction Table -->
                    <div class="dash-table-area">
                        <div class="dash-chart-title">Verdächtige Transaktionen</div>
                        <table class="dash-table">
                            <thead><tr><th>Wallet</th><th>Betrag</th><th>Netzwerk</th><th>Risiko</th></tr></thead>
                            <tbody>
                                <tr><td class="blurred-text">0x7f8a...3b2c</td><td class="blurred-text">€ 48.200</td><td>ETH</td><td><span class="risk-badge high">Hoch</span></td></tr>
                                <tr><td class="blurred-text">1A2b3C...9xYz</td><td class="blurred-text">€ 12.750</td><td>BTC</td><td><span class="risk-badge medium">Mittel</span></td></tr>
                                <tr><td class="blurred-text">TRX8n...4kQp</td><td class="blurred-text">€ 31.000</td><td>TRX</td><td><span class="risk-badge high">Hoch</span></td></tr>
                                <tr><td class="blurred-text">0x4e2d...8f1a</td><td class="blurred-text">€ 8.900</td><td>BSC</td><td><span class="risk-badge low">Niedrig</span></td></tr>
                                <tr><td class="blurred-text">bc1q8...m2nk</td><td class="blurred-text">€ 95.500</td><td>BTC</td><td><span class="risk-badge critical">Kritisch</span></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     DOCUMENT PREVIEW
============================================================ -->
<section class="documents-section section-padding" id="dokumente">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Dokumentation</span>
            <h2 class="section-title">Professionelle Analyseberichte</h2>
            <p class="section-subtitle">Strukturierte und rechtssichere Dokumentation für jeden Fall</p>
        </div>
        <div class="row g-4">
            <?php
            $docs = [
                ['icon' => 'fa-file-chart-column', 'title' => 'Analysebericht', 'type' => 'PDF Report', 'pages' => '24 Seiten', 'date' => '2024-03-15'],
                ['icon' => 'fa-link', 'title' => 'Blockchain-Auswertung', 'type' => 'Technischer Report', 'pages' => '38 Seiten', 'date' => '2024-03-18'],
                ['icon' => 'fa-wallet', 'title' => 'Wallet-Forensik-Report', 'type' => 'Forensik Report', 'pages' => '16 Seiten', 'date' => '2024-03-20'],
                ['icon' => 'fa-shield-halved', 'title' => 'Ermittlungsdokumentation', 'type' => 'Ermittlungsakte', 'pages' => '52 Seiten', 'date' => '2024-03-22'],
            ];
            foreach ($docs as $doc): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="doc-card glass-card animate-on-scroll">
                    <div class="doc-header">
                        <div class="doc-icon"><i class="fas <?php echo $doc['icon']; ?>"></i></div>
                        <div class="doc-meta">
                            <span class="doc-type"><?php echo $doc['type']; ?></span>
                            <span class="doc-pages"><?php echo $doc['pages']; ?></span>
                        </div>
                        <div class="doc-watermark">VERTRAULICH</div>
                    </div>
                    <div class="doc-title"><?php echo $doc['title']; ?></div>
                    <div class="doc-lines">
                        <div class="doc-line blur-content"></div>
                        <div class="doc-line blur-content short"></div>
                        <div class="doc-line blur-content"></div>
                        <div class="doc-line blur-content medium"></div>
                        <div class="doc-line blur-content"></div>
                        <div class="doc-line blur-content short"></div>
                        <div class="doc-chart blur-content"></div>
                        <div class="doc-line blur-content"></div>
                        <div class="doc-line blur-content medium"></div>
                    </div>
                    <div class="doc-footer">
                        <span><i class="fas fa-calendar me-1"></i><?php echo $doc['date']; ?></span>
                        <span><i class="fas fa-lock me-1"></i>Verschlüsselt</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     ÜBER UNS
============================================================ -->
<section class="about-section section-padding" id="ueber-uns">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 animate-on-scroll">
                <span class="section-badge">Über uns</span>
                <h2 class="section-title mt-2">Ihr vertrauenswürdiger Partner bei Finanzbetrug</h2>
                <p class="about-text">
                    Als lizenzierter Finanzexperte und zertifizierter Blockchain-Analyst verfüge ich über mehr als <strong>10 Jahre Erfahrung</strong> in der Finanzforensik und technischen Transaktionsanalyse. Meine Expertise umfasst die vollständige Bandbreite moderner digitaler Finanzkriminalität.
                </p>
                <p class="about-text">
                    Ich arbeite eng mit einem internationalen Netzwerk aus Forensik-Experten, Compliance-Beratern und juristischen Partnern zusammen, um Mandanten bestmöglich zu unterstützen. Jeder Fall wird mit höchster Sorgfalt, Diskretion und Professionalität behandelt.
                </p>
                <p class="about-text">
                    Mein Fokus liegt auf der <strong>präzisen Dokumentation</strong> und technischen Analyse verdächtiger Transaktionen – als Grundlage für weitere rechtliche oder behördliche Schritte. Ich gebe keine Erfolgsversprechen, aber ich garantiere professionelle Arbeit.
                </p>
                <div class="about-certifications mt-4">
                    <span class="cert-badge"><i class="fas fa-certificate me-1"></i>Zertifizierter Blockchain-Analyst</span>
                    <span class="cert-badge"><i class="fas fa-award me-1"></i>Lizenzierter Finanzexperte</span>
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
                            Lizenzierter Experte
                        </div>
                    </div>
                    <div class="about-float-card top-right glass-card">
                        <i class="fas fa-link text-gold me-2"></i>
                        <div>
                            <div class="float-card-title">10+ Jahre Erfahrung</div>
                            <div class="float-card-sub">Finanzforensik</div>
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
     WHY TRUST US
============================================================ -->
<section class="trust-section section-padding" id="vertrauen">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Vertrauen</span>
            <h2 class="section-title">Warum Mandanten uns vertrauen</h2>
            <p class="section-subtitle">Unsere Arbeit basiert auf Transparenz, Professionalität und nachweisbarer Expertise</p>
        </div>
        <div class="row g-4">
            <?php
            $trust = [
                ['icon' => 'fa-comments', 'title' => 'Transparente Kommunikation', 'desc' => 'Klare und verständliche Kommunikation in jedem Schritt des Prozesses.'],
                ['icon' => 'fa-sitemap', 'title' => 'Strukturierte Prozesse', 'desc' => 'Bewährte Analyseprozesse für zuverlässige und nachvollziehbare Ergebnisse.'],
                ['icon' => 'fa-globe', 'title' => 'Internationale Kooperationen', 'desc' => 'Netzwerk mit führenden internationalen Forensik- und Compliance-Experten.'],
                ['icon' => 'fa-microchip', 'title' => 'Modernste Technologie', 'desc' => 'Einsatz aktuellster Blockchain-Analyse-Tools und forensischer Software.'],
                ['icon' => 'fa-database', 'title' => 'Sichere Datenverarbeitung', 'desc' => 'Alle Daten werden nach höchsten Sicherheitsstandards verarbeitet und gespeichert.'],
                ['icon' => 'fa-user-secret', 'title' => 'Vertrauliche Fallbearbeitung', 'desc' => 'Absolute Diskretion und Vertraulichkeit bei jedem Mandat.'],
                ['icon' => 'fa-shield-check', 'title' => 'DSGVO-konform', 'desc' => 'Vollständige Compliance mit der europäischen Datenschutzgrundverordnung.'],
                ['icon' => 'fa-graduation-cap', 'title' => 'Nachgewiesene Expertise', 'desc' => 'Zertifizierungen und jahrelange praktische Erfahrung in der Finanzforensik.'],
            ];
            foreach ($trust as $item): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="trust-card glass-card animate-on-scroll">
                    <div class="trust-icon"><i class="fas <?php echo $item['icon']; ?>"></i></div>
                    <h6 class="trust-title"><?php echo $item['title']; ?></h6>
                    <p class="trust-desc"><?php echo $item['desc']; ?></p>
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
            <span class="section-badge">Unser Prozess</span>
            <h2 class="section-title">Strukturierter Analyseprozess</h2>
            <p class="section-subtitle">Von der ersten Kontaktaufnahme bis zur vollständigen Dokumentation</p>
        </div>
        <div class="process-timeline">
            <?php
            $steps = [
                ['num' => '01', 'icon' => 'fa-magnifying-glass', 'title' => 'Kostenlose Erstprüfung', 'desc' => 'Unverbindliche Bewertung Ihres Falls. Wir analysieren die Grundinformationen und geben eine erste Einschätzung der Situation.'],
                ['num' => '02', 'icon' => 'fa-chart-line', 'title' => 'Analyse der Zahlungen & Wallets', 'desc' => 'Detaillierte technische Analyse aller relevanten Transaktionen, Wallets und Zahlungsströme mit modernsten Forensik-Tools.'],
                ['num' => '03', 'icon' => 'fa-file-shield', 'title' => 'Dokumentation & Ermittlungsunterstützung', 'desc' => 'Erstellung professioneller Analyseberichte und Dokumentationen für behördliche Einreichungen und rechtliche Schritte.'],
                ['num' => '04', 'icon' => 'fa-handshake', 'title' => 'Unterstützung bei Rückforderungen', 'desc' => 'Technische und dokumentarische Unterstützung bei Rückforderungsverfahren in Zusammenarbeit mit internationalen Partnern.'],
            ];
            foreach ($steps as $i => $step): ?>
            <div class="process-step animate-on-scroll" style="--delay: <?php echo $i * 0.2; ?>s">
                <div class="process-number"><?php echo $step['num']; ?></div>
                <div class="process-icon"><i class="fas <?php echo $step['icon']; ?>"></i></div>
                <h5 class="process-title"><?php echo $step['title']; ?></h5>
                <p class="process-desc"><?php echo $step['desc']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     URGENCY SECTION
============================================================ -->
<section class="urgency-section">
    <div class="container">
        <div class="urgency-inner animate-on-scroll">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="urgency-icon"><i class="fas fa-triangle-exclamation"></i></div>
                    <h3 class="urgency-title">Handeln Sie jetzt – Zeit ist entscheidend</h3>
                    <p class="urgency-text">
                        Je schneller verdächtige Transaktionen analysiert werden, desto höher sind die Chancen auf eine erfolgreiche Nachverfolgung. Digitale Spuren verblassen mit der Zeit – warten Sie nicht, jede Stunde kann entscheidend sein.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="#kontakt" class="btn btn-gold btn-lg">
                        <i class="fas fa-magnifying-glass me-2"></i>
                        Jetzt unverbindlich prüfen lassen
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
            <h2 class="section-title">Was unsere Mandanten sagen</h2>
            <p class="section-subtitle">Vertrauen basiert auf Erfahrungen – lesen Sie, was andere sagen</p>
        </div>
        <div class="row g-4">
            <?php
            $testimonials = [
                ['initial' => 'M.K.', 'city' => 'Hamburg', 'stars' => 5, 'text' => 'Nach einem erheblichen Verlust bei einer unseriösen Kryptoplattform wandte ich mich an das Team. Die Analyse meines Falls war ausgesprochen professionell und strukturiert. Ich wurde stets informiert und transparent beraten. Ich bin sehr dankbar für die kompetente und diskrete Unterstützung.'],
                ['initial' => 'T.S.', 'city' => 'München', 'stars' => 5, 'text' => 'Ich war komplett verzweifelt nachdem ich durch einen Fake-Broker erhebliche Verluste erlitten hatte. Das Team hat meine Situation sehr ernst genommen, alle Transaktionen sorgfältig analysiert und professionell dokumentiert. Die Kommunikation war stets klar und verständlich. Sehr empfehlenswert!'],
                ['initial' => 'A.R.', 'city' => 'Berlin', 'stars' => 5, 'text' => 'Professionelle und absolut diskrete Bearbeitung meines Falls. Die technische Blockchain-Analyse war sehr detailliert und für mich verständlich aufbereitet. Die Kommunikation war stets transparent und auf Augenhöhe. Ich kann diesen Service jedem empfehlen, der sich in einer ähnlichen Situation befindet.'],
                ['initial' => 'K.H.', 'city' => 'Frankfurt', 'stars' => 5, 'text' => 'Ich war skeptisch, aber die Professionalität und das Fachwissen haben mich überzeugt. Die Dokumentation meines Falles war vollständig und präzise. Ein sehr kompetentes Team mit echter Expertise in der Finanzforensik. Die Unterstützung war in einer schwierigen Situation sehr wertvoll.'],
                ['initial' => 'S.B.', 'city' => 'Köln', 'stars' => 5, 'text' => 'Hervorragende fachliche Kompetenz und persönlicher Einsatz. Mein Fall wurde schnell aufgenommen und systematisch analysiert. Die Berichte waren umfassend und professionell. Besonders geschätzt habe ich die ehrliche und realistische Einschätzung der Situation ohne übertriebene Versprechen.'],
                ['initial' => 'R.M.', 'city' => 'Stuttgart', 'stars' => 5, 'text' => 'Die Zusammenarbeit war äußerst professionell. Alle meine Fragen wurden geduldig beantwortet, die Analyse war gründlich und die Dokumentation vollständig. Besonders die internationale Vernetzung hat mir in meiner Situation sehr geholfen. Ich kann diesen Service wärmstens empfehlen.'],
            ];
            foreach ($testimonials as $t): ?>
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card glass-card animate-on-scroll">
                    <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
                    <div class="testimonial-stars">
                        <?php for ($s = 0; $s < $t['stars']; $s++): ?>
                        <i class="fas fa-star text-gold"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="testimonial-text"><?php echo htmlspecialchars($t['text']); ?></p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar"><?php echo htmlspecialchars($t['initial']); ?></div>
                        <div>
                            <div class="testimonial-name"><?php echo htmlspecialchars($t['initial']); ?></div>
                            <div class="testimonial-city"><i class="fas fa-location-dot me-1"></i><?php echo htmlspecialchars($t['city']); ?></div>
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
     GOOGLE REVIEWS STYLE
============================================================ -->
<section class="reviews-section section-padding" id="bewertungen">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5 animate-on-scroll">
                <div class="review-summary glass-card">
                    <div class="review-logo"><i class="fab fa-google me-2"></i>Google Bewertungen</div>
                    <div class="review-score">4.9</div>
                    <div class="review-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-stroke"></i>
                    </div>
                    <div class="review-count">Basierend auf 127 Bewertungen</div>
                    <div class="review-badges">
                        <span class="review-badge-item"><i class="fas fa-check-circle me-1"></i>Von Mandanten empfohlen</span>
                        <span class="review-badge-item"><i class="fas fa-user-check me-1"></i>Verifizierte Mandanten</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 animate-on-scroll delay-1">
                <div class="review-bars glass-card">
                    <h5 class="mb-4">Bewertungsübersicht</h5>
                    <?php
                    $bars = [5 => 89, 4 => 28, 3 => 7, 2 => 2, 1 => 1];
                    foreach ($bars as $stars => $pct): ?>
                    <div class="review-bar-row">
                        <span class="review-bar-label"><?php echo $stars; ?> <i class="fas fa-star text-gold"></i></span>
                        <div class="review-bar-track">
                            <div class="review-bar-fill" style="width: <?php echo $pct; ?>%"></div>
                        </div>
                        <span class="review-bar-pct"><?php echo $pct; ?>%</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     PARTNERS
============================================================ -->
<section class="partners-section section-padding" id="netzwerk">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Netzwerk</span>
            <h2 class="section-title">Unser Partner-Netzwerk</h2>
            <p class="section-subtitle">Wir arbeiten mit internationalen Experten und Organisationen zusammen</p>
        </div>
        <div class="row g-4 justify-content-center">
            <?php
            $partners = [
                ['icon' => 'fa-building-columns', 'name' => 'Internationale Börsen'],
                ['icon' => 'fa-shield-halved', 'name' => 'Cybersecurity Partner'],
                ['icon' => 'fa-link', 'name' => 'Blockchain Analytics'],
                ['icon' => 'fa-gavel', 'name' => 'Compliance Experten'],
                ['icon' => 'fa-landmark', 'name' => 'Finanzaufsicht'],
                ['icon' => 'fa-microscope', 'name' => 'Forensik Netzwerk'],
            ];
            foreach ($partners as $p): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="partner-card animate-on-scroll">
                    <i class="fas <?php echo $p['icon']; ?>"></i>
                    <span><?php echo $p['name']; ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     EDUCATIONAL / SEO SECTION
============================================================ -->
<section class="education-section section-padding" id="wissen">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Wissen & Information</span>
            <h2 class="section-title">Informieren Sie sich</h2>
            <p class="section-subtitle">Fundiertes Wissen zum Schutz vor Finanzbetrug und zur Aufklärung Ihrer Situation</p>
        </div>
        <div class="row g-4">
            <?php
            $articles = [
                [
                    'icon' => 'fa-exclamation-triangle',
                    'title' => 'Häufige Arten von Anlagebetrug',
                    'text' => 'Anlagebetrug hat viele Gesichter. Zu den häufigsten Formen zählen Ponzi-Schemata, bei denen Renditen aus den Einlagen neuer Anleger bezahlt werden, Pump-and-Dump-Schemes bei Kryptowährungen sowie gefälschte Investmentplattformen, die professionell wirkende Webseiten und gefälschte Handelsergebnisse verwenden. Besonders verbreitet sind auch Fake-Broker, die regulierte Handelsumgebungen simulieren, sowie Romance-Scams, bei denen emotionale Beziehungen gezielt zur finanziellen Ausbeutung genutzt werden.',
                ],
                [
                    'icon' => 'fa-eye',
                    'title' => 'Warnzeichen unseriöser Plattformen',
                    'text' => 'Seriöse Investmentplattformen sind immer reguliert und transparent. Warnzeichen einer unseriösen Plattform sind: fehlende oder gefälschte Regulierungslizenzen, unrealistische Renditeversprechen, Druck zu schnellen Entscheidungen, erschwerter Kapitalabzug, anonyme Ansprechpartner sowie nicht verifizierbare Firmenadressen. Auch verdächtig: Plattformen, die ausschließlich in Kryptowährungen zahlen oder ihre Gebührenstruktur verschleiern.',
                ],
                [
                    'icon' => 'fa-link',
                    'title' => 'Wie Blockchain-Analyse funktioniert',
                    'text' => 'Blockchain-Transaktionen sind öffentlich und unveränderlich aufgezeichnet. Mittels spezialisierter Forensik-Software können Transaktionsketten zurückverfolgt und Wallet-Adressen mit Identitäten verknüpft werden. Durch Cluster-Analyse lassen sich mehrere Wallets einer Person zuordnen. Muster im Transaktionsverhalten, Zeitstempel und bekannte Exchange-Adressen liefern wertvolle Hinweise. Diese Informationen können als Grundlage für rechtliche Schritte oder Behördenanzeigen verwendet werden.',
                ],
                [
                    'icon' => 'fa-file-shield',
                    'title' => 'Sicherheitsmaßnahmen bei Investments',
                    'text' => 'Schützen Sie sich vor Anlagebetrug: Überprüfen Sie stets die Regulierung einer Plattform bei der zuständigen Aufsichtsbehörde (BaFin in Deutschland, FCA in UK, etc.). Investieren Sie nie mehr, als Sie sich leisten können zu verlieren. Misstrauen Sie garantierten Renditen – diese gibt es nicht. Dokumentieren Sie alle Zahlungen und Kommunikation sorgfältig. Bei Verdacht auf Betrug: handeln Sie sofort und sichern Sie alle Beweise.',
                ],
            ];
            foreach ($articles as $a): ?>
            <div class="col-md-6">
                <div class="edu-card glass-card animate-on-scroll">
                    <div class="edu-icon"><i class="fas <?php echo $a['icon']; ?>"></i></div>
                    <h5 class="edu-title"><?php echo htmlspecialchars($a['title']); ?></h5>
                    <p class="edu-text"><?php echo htmlspecialchars($a['text']); ?></p>
                    <a href="#kontakt" class="edu-link">Mehr erfahren <i class="fas fa-arrow-right ms-1"></i></a>
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
            <p class="section-subtitle">Antworten auf die wichtigsten Fragen unserer Mandanten</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion faq-accordion" id="faqAccordion">
                    <?php
                    $faqs = [
                        ['q' => 'Kann verlorenes Geld zurückgeholt werden?', 'a' => 'Eine direkte Rückforderung oder Rückzahlung können wir nicht garantieren – das wäre unseriös und entspräche nicht der Realität. Unsere Aufgabe ist die professionelle Analyse, lückenlose Dokumentation und technische Unterstützung bei der Nachverfolgung verdächtiger Transaktionen. Diese Unterlagen bilden die Grundlage für etwaige rechtliche Schritte oder Behördenanzeigen. In Zusammenarbeit mit unseren Partnern unterstützen wir Mandanten im gesamten Prozess.'],
                        ['q' => 'Wie läuft eine Analyse ab?', 'a' => 'Der Prozess beginnt mit einer kostenlosen und unverbindlichen Erstprüfung Ihres Falls. Danach erfolgt eine detaillierte Analyse aller relevanten Transaktionen, Wallet-Adressen und Zahlungsströme. Alle Erkenntnisse werden in einem professionellen Analysebericht dokumentiert. Auf Wunsch werden Sie durch den gesamten weiteren Prozess begleitet.'],
                        ['q' => 'Welche Unterlagen werden benötigt?', 'a' => 'Für eine umfassende Analyse benötigen wir: alle Transaktionsbelege und Kontoauszüge, E-Mail-Korrespondenz mit der Plattform oder dem Broker, Screenshots von Handelsplattformen und Wallet-Oberflächen, Wallet-Adressen und Transaktions-IDs, sowie alle Vertragsunterlagen und Nutzungsbedingungen. Je mehr Dokumentation vorliegt, desto gründlicher kann die Analyse sein.'],
                        ['q' => 'Arbeiten Sie mit Kryptobörsen zusammen?', 'a' => 'Wir arbeiten mit einem internationalen Netzwerk aus Blockchain-Forensik-Experten und Compliance-Spezialisten zusammen. Im Rahmen legaler Möglichkeiten kann dies auch die Zusammenarbeit mit Kryptobörsen bei der Aufklärung verdächtiger Transaktionen umfassen. Dies erfolgt stets im Rahmen der gesetzlichen Vorschriften und mit entsprechenden Nachweisen.'],
                        ['q' => 'Wie schnell erfolgt eine Rückmeldung?', 'a' => 'Wir sind bestrebt, innerhalb von 24 Stunden auf Ihre Anfrage zu antworten. Bei dringenden Fällen empfehlen wir eine direkte Kontaktaufnahme per Telefon oder WhatsApp für eine schnellere Reaktion. Die Erstprüfung kann in der Regel innerhalb von 48-72 Stunden durchgeführt werden.'],
                        ['q' => 'Ist die Erstprüfung wirklich kostenlos?', 'a' => 'Ja, die Erstprüfung Ihres Falls ist vollständig kostenlos und absolut unverbindlich. Dabei verschaffen wir uns einen ersten Überblick über Ihre Situation und geben eine ehrliche Einschätzung. Erst wenn Sie sich für eine umfassendere Analyse entscheiden, entstehen Kosten – die vorher transparent kommuniziert werden.'],
                        ['q' => 'Wie wird die Vertraulichkeit gewährleistet?', 'a' => 'Alle uns anvertrauten Informationen werden streng vertraulich behandelt. Wir arbeiten vollständig DSGVO-konform und verwenden verschlüsselte Kommunikationskanäle. Ihre persönlichen Daten werden ausschließlich für die Fallbearbeitung genutzt und nicht an Dritte weitergegeben – außer mit Ihrer ausdrücklichen Zustimmung für die Zusammenarbeit mit unseren Partnern.'],
                        ['q' => 'In welchen Ländern können Sie helfen?', 'a' => 'Wir unterstützen Mandanten aus dem gesamten deutschsprachigen Raum (Deutschland, Österreich, Schweiz) sowie international. Dank unseres internationalen Partnernetzwerks können wir auch grenzüberschreitende Fälle bearbeiten. Betrugsfälle kennen keine Grenzen – unsere Expertise auch nicht.'],
                    ];
                    foreach ($faqs as $idx => $faq): ?>
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $idx > 0 ? 'collapsed' : ''; ?>" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq<?php echo $idx; ?>">
                                <i class="fas fa-circle-question me-2 text-gold"></i>
                                <?php echo htmlspecialchars($faq['q']); ?>
                            </button>
                        </h2>
                        <div id="faq<?php echo $idx; ?>" class="accordion-collapse collapse <?php echo $idx === 0 ? 'show' : ''; ?>"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo htmlspecialchars($faq['a']); ?>
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
                    <p class="contact-intro">Schildern Sie uns Ihren Fall. Die erste Prüfung ist kostenlos und unverbindlich.</p>
                    <div class="contact-items mt-4">
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <div class="contact-item-label">Telefon</div>
                                <a href="tel:<?php echo SITE_PHONE; ?>" class="contact-item-value"><?php echo htmlspecialchars(SITE_PHONE); ?></a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div class="contact-item-label">E-Mail</div>
                                <a href="mailto:<?php echo SITE_EMAIL; ?>" class="contact-item-value"><?php echo htmlspecialchars(SITE_EMAIL); ?></a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-item-icon"><i class="fab fa-whatsapp"></i></div>
                            <div>
                                <div class="contact-item-label">WhatsApp</div>
                                <a href="https://wa.me/4989123456789" class="contact-item-value">Jetzt schreiben</a>
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
                        <small>Verschlüsselte & DSGVO-konforme Übertragung</small>
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
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                        <!-- Honeypot -->
                        <div style="position:absolute;left:-9999px;opacity:0;pointer-events:none;">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <!-- Step 1: Personal Data -->
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

                        <!-- Step 2: Case Details -->
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

                        <!-- Step 3: Message -->
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

                        <!-- Success Message -->
                        <div id="formSuccess" class="form-success d-none">
                            <div class="success-icon"><i class="fas fa-circle-check"></i></div>
                            <h5>Anfrage erfolgreich gesendet!</h5>
                            <p>Vielen Dank für Ihre Nachricht. Wir melden uns innerhalb von 24 Stunden bei Ihnen. Bitte überprüfen Sie auch Ihren Spam-Ordner.</p>
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
                <span class="d-none d-md-inline ms-2">– Wir rufen Sie kostenlos zurück</span>
            </div>
            <form class="callback-form" id="callbackForm">
                <input type="text" class="form-control" name="cb_name" placeholder="Ihr Name" required>
                <input type="tel" class="form-control" name="cb_telefon" placeholder="+49 ..." required>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
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
                        <i class="fas fa-shield-halved me-2 text-gold"></i>
                        <span class="brand-name">FinanzForensik</span>
                        <span class="brand-sub">Expert</span>
                    </div>
                    <p class="footer-tagline">Professionelle Finanzforensik und Blockchain-Analyse für Opfer von Anlagebetrug und Krypto-Scams.</p>
                    <div class="footer-social">
                        <a href="#" class="social-icon" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-icon" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
                        <a href="#" class="social-icon" title="Xing"><i class="fab fa-xing"></i></a>
                    </div>
                    <div class="footer-badges mt-3">
                        <span class="footer-badge"><i class="fas fa-lock me-1"></i>SSL Secured</span>
                        <span class="footer-badge"><i class="fas fa-shield-check me-1"></i>DSGVO</span>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <h6 class="footer-heading">Leistungen</h6>
                    <ul class="footer-links">
                        <li><a href="#leistungen">Blockchain-Analyse</a></li>
                        <li><a href="#leistungen">Wallet-Forensik</a></li>
                        <li><a href="#leistungen">Transaktionsanalyse</a></li>
                        <li><a href="#leistungen">Betrugsdokumentation</a></li>
                        <li><a href="#leistungen">Compliance-Beratung</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <h6 class="footer-heading">Unternehmen</h6>
                    <ul class="footer-links">
                        <li><a href="#ueber-uns">Über uns</a></li>
                        <li><a href="#prozess">Unser Prozess</a></li>
                        <li><a href="#netzwerk">Partner-Netzwerk</a></li>
                        <li><a href="#testimonials">Referenzen</a></li>
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
                        <div class="mt-2"><i class="fas fa-phone me-2 text-gold"></i><a href="tel:+498912345678"><?php echo htmlspecialchars(SITE_PHONE); ?></a></div>
                        <div class="mt-2"><i class="fas fa-envelope me-2 text-gold"></i><a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo htmlspecialchars(SITE_EMAIL); ?></a></div>
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
                    <small>&copy; <?php echo date('Y'); ?> FinanzForensik Expert. Alle Rechte vorbehalten.</small>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <small class="footer-disclaimer">
                        <i class="fas fa-info-circle me-1"></i>
                        Rechtlicher Hinweis: Wir bieten keine Rechtsberatung. Unsere Leistungen umfassen Finanzanalyse, technische Dokumentation und Informationsaufbereitung.
                    </small>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- ============================================================
     FLOATING BUTTONS
============================================================ -->
<a href="https://wa.me/4989123456789" class="floating-btn whatsapp-btn" target="_blank" rel="noopener" title="WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<button class="floating-btn scroll-top-btn" id="scrollTopBtn" title="Nach oben">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<!-- Custom JS -->
<script src="js/ajax.js"></script>
</body>
</html>
