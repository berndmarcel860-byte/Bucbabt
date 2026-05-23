<?php
require_once 'config.php';

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
    <title>Johannes Kiehl | Persönlicher Berater für internationale Betrugsfälle</title>
    <meta name="description" content="Johannes Kiehl unterstützt Mandanten bei internationalen Betrugsfällen mit strukturierter Fallanalyse, Dokumentation und professioneller Begleitung.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background: #0b1420; color: #e8edf5; }
        .hero-intro { padding: 4.5rem 0 2.5rem; background: linear-gradient(160deg, #0b1420 0%, #12243c 65%, #1d3557 100%); }
        .hero-title { font-size: clamp(2rem, 3.8vw, 3.2rem); font-weight: 700; color: #fff; }
        .hero-sub { color: #cfd6e3; }
        .jk-card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.13); border-radius: 16px; }
        .section-title { color: #fff; font-weight: 700; margin-bottom: 1.25rem; }
        .service-card, .case-card { height: 100%; padding: 1.3rem; border-radius: 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); }
        .service-card i, .case-card i { color: #c9a84c; }
        .form-label, .form-check-label { color: #e8edf5; }
        .form-control, .form-select { background: #0f1f35; border: 1px solid #2b3d57; color: #fff; }
        .form-control::placeholder { color: #aeb7c6; }
        .form-control:focus, .form-select:focus { background: #13263f; border-color: #c9a84c; box-shadow: 0 0 0 .2rem rgba(201,168,76,.2); color: #fff; }
        .quick-badge { display: inline-block; background: rgba(201,168,76,.15); color: #f2d88f; border: 1px solid rgba(201,168,76,.45); border-radius: 999px; padding: .25rem .8rem; font-size: .85rem; }
        .info-strip { background: rgba(255,255,255,0.04); border-top: 1px solid rgba(255,255,255,.08); border-bottom: 1px solid rgba(255,255,255,.08); }
        .metric-item { padding: 1rem 0; }
        .metric-value { font-size: 1.5rem; font-weight: 700; color: #fff; }
        .metric-label { color: #b9c3d3; font-size: .92rem; }
        .process-card { padding: 1.2rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); height: 100%; }
        .jk-footer { padding: 2rem 0; border-top: 1px solid rgba(255,255,255,0.1); color: #b9c3d3; }
    </style>
</head>
<body>

<section class="hero-intro">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <span class="quick-badge"><i class="fas fa-user-shield me-2"></i>Persönliche Beratung</span>
                <h1 class="hero-title mt-3">Johannes Kiehl – Ihr persönlicher Berater für internationale Betrugsfälle</h1>
                <p class="hero-sub mt-3">
                    Als erfolgreicher Accounting-Berater für Betrugsplattformen unterstütze ich Sie bei der strukturierten
                    Aufarbeitung komplexer Fälle im In- und Ausland: von der Beweissicherung über die Finanzflussanalyse
                    bis zur professionellen Falldokumentation für Behörden, Anwälte und Compliance-Teams.
                </p>
                <div class="mt-4 d-flex flex-wrap gap-3">
                    <div><i class="fas fa-globe-europe me-2 text-warning"></i>Internationale Fallbegleitung</div>
                    <div><i class="fas fa-file-shield me-2 text-warning"></i>Diskrete Dokumentation</div>
                    <div><i class="fas fa-clock me-2 text-warning"></i>Rückmeldung innerhalb von 24h</div>
                </div>
                <p class="small mt-4 text-light-emphasis">
                    Hinweis: Eine Rückerstattung kann nicht garantiert werden. Ziel ist eine fundierte Analyse und bestmögliche Vorbereitung Ihres Falls.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="jk-card p-4 h-100">
                    <h2 class="h4 mb-3 text-white">Erstkontakt direkt starten</h2>
                    <p class="text-light-emphasis mb-3">Beschreiben Sie kurz Ihren Fall. Ich melde mich zeitnah persönlich bei Ihnen.</p>

                    <form id="personalContactForm" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="contact">
                        <div style="position:absolute;left:-9999px;opacity:0;pointer-events:none;">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vorname *</label>
                                <input class="form-control" type="text" name="vorname" required minlength="2" placeholder="Vorname">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nachname *</label>
                                <input class="form-control" type="text" name="nachname" required minlength="2" placeholder="Nachname">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefon *</label>
                                <input class="form-control" type="tel" name="telefon" required placeholder="+49 ...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-Mail *</label>
                                <input class="form-control" type="email" name="email" required placeholder="name@beispiel.de">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Verlustbetrag *</label>
                                <select class="form-select" name="verlustbetrag" required>
                                    <option value="">Bitte auswählen</option>
                                    <option value="unter_1000">Unter 1.000 €</option>
                                    <option value="1000_5000">1.000 € – 5.000 €</option>
                                    <option value="5000_15000">5.000 € – 15.000 €</option>
                                    <option value="15000_50000">15.000 € – 50.000 €</option>
                                    <option value="ueber_50000">Über 50.000 €</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Zahlungsmethode *</label>
                                <select class="form-select" name="zahlungsmethode" required>
                                    <option value="">Bitte auswählen</option>
                                    <option value="bankueberweisung">Banküberweisung</option>
                                    <option value="krypto">Kryptowährung</option>
                                    <option value="kreditkarte">Kreditkarte</option>
                                    <option value="wallet">Wallet Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Plattform / Gegenpartei *</label>
                                <input class="form-control" type="text" name="plattform" required minlength="3" placeholder="z. B. Brokername">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Land *</label>
                                <select class="form-select" name="land" required>
                                    <option value="">Bitte auswählen</option>
                                    <option value="DE">Deutschland</option>
                                    <option value="AT">Österreich</option>
                                    <option value="CH">Schweiz</option>
                                    <option value="EU">EU</option>
                                    <option value="INT">International</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Kurze Fallschilderung *</label>
                                <textarea class="form-control" name="nachricht" rows="4" required minlength="20" placeholder="Wann fand der Vorfall statt, wie wurde gezahlt, wie hoch ist der Schaden?"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Wunschtermin (optional)</label>
                                <input class="form-control" type="date" name="wunschtermin" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacyCheck" required>
                                    <label class="form-check-label" for="privacyCheck">
                                        Ich stimme der Verarbeitung meiner Angaben zur Kontaktaufnahme zu. *
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="formFeedback" class="mt-3 small"></div>

                        <button type="submit" class="btn btn-gold mt-3" id="sendBtn">
                            Anfrage senden
                            <span class="spinner-border spinner-border-sm ms-2 d-none" id="sendSpinner"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="info-strip">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 metric-item">
                <div class="metric-value">10+</div>
                <div class="metric-label">Jahre Finanz- & Accounting-Erfahrung</div>
            </div>
            <div class="col-md-3 metric-item">
                <div class="metric-value">250+</div>
                <div class="metric-label">Analysierte Betrugs- und Plattformfälle</div>
            </div>
            <div class="col-md-3 metric-item">
                <div class="metric-value">40+</div>
                <div class="metric-label">Länderbezug in internationalen Fällen</div>
            </div>
            <div class="col-md-3 metric-item">
                <div class="metric-value">24h</div>
                <div class="metric-label">Erstreaktion auf neue Anfragen</div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="section-title">Leistungen für internationale Privatfälle</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="service-card">
                    <i class="fas fa-magnifying-glass-chart fa-lg mb-3"></i>
                    <h3 class="h5 text-white">Transaktions- und Wallet-Analyse</h3>
                    <p class="mb-0">Accounting-orientierte Aufarbeitung von Zahlungsflüssen, Wallet-Bewegungen und Gegenparteien für eine belastbare Fallstruktur.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <i class="fas fa-folder-open fa-lg mb-3"></i>
                    <h3 class="h5 text-white">Strukturierte Falldokumentation</h3>
                    <p class="mb-0">Professionelle Zusammenstellung Ihrer Unterlagen inkl. Zahlungsbelege, Kommunikationshistorie und Zeitachsen.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <i class="fas fa-handshake-angle fa-lg mb-3"></i>
                    <h3 class="h5 text-white">Persönliche Begleitung</h3>
                    <p class="mb-0">Direkte Beratung durch Johannes Kiehl mit klaren nächsten Schritten und laufenden Status-Updates.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <h2 class="section-title">Mein professionelles Vorgehen als Accounting-Berater</h2>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="process-card">
                    <div class="text-warning fw-bold mb-2">01</div>
                    <h3 class="h6 text-white">Erstprüfung & Risiko-Scoring</h3>
                    <p class="mb-0">Schnelle Bewertung Ihrer Unterlagen, Identifikation zentraler Risiken und Prioritäten.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="process-card">
                    <div class="text-warning fw-bold mb-2">02</div>
                    <h3 class="h6 text-white">Forensische Finanzanalyse</h3>
                    <p class="mb-0">Analyse von Kontobewegungen, Zahlungsrouten und Plattformmustern mit klarer Nachvollziehbarkeit.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="process-card">
                    <div class="text-warning fw-bold mb-2">03</div>
                    <h3 class="h6 text-white">Dokumentenpaket</h3>
                    <p class="mb-0">Erstellung eines professionellen Fallpakets für weitere rechtliche oder behördliche Schritte.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="process-card">
                    <div class="text-warning fw-bold mb-2">04</div>
                    <h3 class="h6 text-white">Begleitete Umsetzung</h3>
                    <p class="mb-0">Kontinuierliche Betreuung mit klaren Status-Updates und transparenten Handlungsempfehlungen.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <h2 class="section-title">Professionelle private Fallbeispiele</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="case-card">
                    <i class="fas fa-user-secret fa-lg mb-3"></i>
                    <h3 class="h5 text-white">Privatfall A (anonymisiert)</h3>
                    <p class="mb-0">Internationale Krypto-Überweisungen über mehrere Wallets. Ergebnis: belastbare Chronologie und vollständige Dokumentation des Zahlungswegs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="case-card">
                    <i class="fas fa-building-columns fa-lg mb-3"></i>
                    <h3 class="h5 text-white">Privatfall B (anonymisiert)</h3>
                    <p class="mb-0">Fake-Investmentplattform mit Auslandsbezug. Ergebnis: strukturierte Aufbereitung von Login-Protokollen, Überweisungsbelegen und Kommunikation.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="case-card">
                    <i class="fas fa-earth-americas fa-lg mb-3"></i>
                    <h3 class="h5 text-white">Privatfall C (anonymisiert)</h3>
                    <p class="mb-0">Mehrsprachige Betrugskommunikation über Messenger und E-Mail. Ergebnis: klarer Incident-Report mit priorisierten Handlungsschritten.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="jk-footer">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
            <strong>Johannes Kiehl</strong><br>
            Persönlicher Berater für internationale Betrugsfälle
        </div>
        <div class="text-md-end">
            <a class="text-decoration-none text-warning" href="mailto:<?php echo htmlspecialchars(SITE_EMAIL, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>"><?php echo htmlspecialchars(SITE_EMAIL, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></a><br>
            <a class="text-decoration-none text-warning" href="tel:<?php echo htmlspecialchars(SITE_PHONE, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>"><?php echo htmlspecialchars(SITE_PHONE, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('personalContactForm');
    if (!form) return;

    var feedback = document.getElementById('formFeedback');
    var sendBtn = document.getElementById('sendBtn');
    var spinner = document.getElementById('sendSpinner');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        feedback.className = 'mt-3 small';
        feedback.textContent = '';

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            feedback.classList.add('text-danger');
            feedback.textContent = 'Bitte prüfen Sie Ihre Eingaben.';
            return;
        }

        sendBtn.disabled = true;
        spinner.classList.remove('d-none');

        var formData = new FormData(form);
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta && !formData.get('csrf_token')) {
            formData.append('csrf_token', csrfMeta.getAttribute('content'));
        }

        try {
            var response = await fetch('submit.php', {
                method: 'POST',
                body: formData
            });
            var result = await response.json();

            if (result && result.success) {
                feedback.classList.add('text-success');
                feedback.textContent = result.message || 'Vielen Dank! Ihre Anfrage wurde erfolgreich übermittelt.';
                form.reset();
                form.classList.remove('was-validated');
            } else {
                feedback.classList.add('text-danger');
                feedback.textContent = (result && result.message) ? result.message : 'Fehler beim Senden. Bitte versuchen Sie es erneut.';
            }
        } catch (err) {
            feedback.classList.add('text-danger');
            feedback.textContent = 'Verbindungsfehler. Bitte versuchen Sie es erneut.';
        } finally {
            sendBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    });
});
</script>
</body>
</html>
