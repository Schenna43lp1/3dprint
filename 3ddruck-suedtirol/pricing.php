<?php
$page_title = 'Preise – 3D Druck Service Südtirol';
$page_desc  = 'Preisübersicht für 3D-Druckleistungen in Südtirol. Individuelle Kalkulation nach Material, Druckzeit und Komplexität.';
require_once 'includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="/">Start</a></li>
                <li class="breadcrumb-item active">Preise</li>
            </ol>
        </nav>
        <span class="section-tag">Transparent & Fair</span>
        <h1 class="mt-2 mb-3">Preisübersicht</h1>
        <p>Unsere Preise richten sich nach Material, Druckzeit und Komplexität. Die folgenden Angaben sind Richtwerte – jede Anfrage wird individuell kalkuliert.</p>
    </div>
</section>

<!-- Pricing Notice -->
<section class="section-sm">
    <div class="container">
        <div class="alert-custom alert-success-custom fade-in-up" style="justify-content:flex-start">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <span>Alle Preise sind unverbindliche Richtwerte. Das endgültige Angebot wird nach Prüfung deiner Datei individuell erstellt. Mindestbestellwert: <strong>5,00 €</strong>.</span>
        </div>
    </div>
</section>

<!-- Pricing Cards -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <span class="section-tag">Preiskategorien</span>
            <h2 class="section-title">Orientierungspreise</h2>
            <p class="section-subtitle">Als Grundlage dient die reine Druckzeit plus Materialkosten. Je nach Komplexität und Aufwand kann der Preis variieren.</p>
        </div>
        <div class="row g-4 justify-content-center">

            <!-- Small -->
            <div class="col-md-6 col-lg-3 fade-in-up">
                <div class="card-custom pricing-card">
                    <p class="price-label">Kleindruck</p>
                    <div class="d-flex align-items-baseline gap-1 mt-2 mb-1">
                        <span class="price-from">ab</span>
                        <span class="price-value">5</span>
                        <span class="price-unit">€</span>
                    </div>
                    <p class="small text-muted mb-0">Druckzeit &lt; 1 Std.</p>
                    <div class="price-divider"></div>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i><span>Kleine Teile &lt; 5 cm</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Standard PLA</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Geringes Gewicht</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Einfache Geometrie</span></li>
                        <li><i class="bi bi-dash text-muted"></i><span class="text-muted">Kein Support nötig</span></li>
                    </ul>
                    <a href="/request.php" class="btn-primary-custom mt-3 w-100 justify-content-center">Anfrage</a>
                </div>
            </div>

            <!-- Medium -->
            <div class="col-md-6 col-lg-3 fade-in-up">
                <div class="card-custom pricing-card featured">
                    <p class="price-label">Mitteldruck</p>
                    <div class="d-flex align-items-baseline gap-1 mt-2 mb-1">
                        <span class="price-from">ab</span>
                        <span class="price-value">15</span>
                        <span class="price-unit">€</span>
                    </div>
                    <p class="small text-muted mb-0">Druckzeit 1–4 Std.</p>
                    <div class="price-divider"></div>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i><span>Teile 5–15 cm</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>PLA, PETG</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Mittlere Komplexität</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Support möglich</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Mehrere Teile</span></li>
                    </ul>
                    <a href="/request.php" class="btn-primary-custom mt-3 w-100 justify-content-center">Anfrage</a>
                </div>
            </div>

            <!-- Technical -->
            <div class="col-md-6 col-lg-3 fade-in-up">
                <div class="card-custom pricing-card">
                    <p class="price-label">Technischer Druck</p>
                    <div class="d-flex align-items-baseline gap-1 mt-2 mb-1">
                        <span class="price-from">ab</span>
                        <span class="price-value">30</span>
                        <span class="price-unit">€</span>
                    </div>
                    <p class="small text-muted mb-0">Druckzeit 4–10 Std.</p>
                    <div class="price-divider"></div>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i><span>Große Teile &gt; 15 cm</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>PLA, PETG</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Hohe Präzision</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Komplexe Geometrie</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Nachbearbeitung</span></li>
                    </ul>
                    <a href="/request.php" class="btn-primary-custom mt-3 w-100 justify-content-center">Anfrage</a>
                </div>
            </div>

            <!-- Express -->
            <div class="col-md-6 col-lg-3 fade-in-up">
                <div class="card-custom pricing-card">
                    <p class="price-label">Express</p>
                    <div class="d-flex align-items-baseline gap-1 mt-2 mb-1">
                        <span class="price-from">+</span>
                        <span class="price-value">50</span>
                        <span class="price-unit">%</span>
                    </div>
                    <p class="small text-muted mb-0">Bevorzugte Bearbeitung</p>
                    <div class="price-divider"></div>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle-fill"></i><span>Vorrang vor anderen Aufträgen</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Bearbeitung ab 24h</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Alle Materialien</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Direkter Kontakt</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Statusupdates</span></li>
                    </ul>
                    <a href="/request.php" class="btn-primary-custom mt-3 w-100 justify-content-center">Anfrage</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Price Factors -->
<section class="section" style="background:var(--bg-card2);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <span class="section-tag">Preisfaktoren</span>
            <h2 class="section-title">Was beeinflusst den Preis?</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3 fade-in-up">
                <div class="card-custom p-3 text-center h-100">
                    <div class="card-icon mx-auto mb-3"><i class="bi bi-hourglass-split"></i></div>
                    <h5 class="fw-700">Druckzeit</h5>
                    <p class="small text-muted mb-0">Die reine Druckzeit ist der größte Kostenfaktor. Sie wird durch Schichtdicke, Füllgrad und Geometrie bestimmt.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-in-up">
                <div class="card-custom p-3 text-center h-100">
                    <div class="card-icon mx-auto mb-3"><i class="bi bi-layers-fill"></i></div>
                    <h5 class="fw-700">Material</h5>
                    <p class="small text-muted mb-0">PETG kostet etwas mehr als Standard-PLA. Auch Füllgrad und Materialverbrauch beeinflussen den Preis.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-in-up">
                <div class="card-custom p-3 text-center h-100">
                    <div class="card-icon mx-auto mb-3"><i class="bi bi-bezier2"></i></div>
                    <h5 class="fw-700">Komplexität</h5>
                    <p class="small text-muted mb-0">Teile mit Überhängen benötigen Support-Strukturen, was Zeit und Material erhöht. Filigranes Design erfordert spezielle Einstellungen.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-in-up">
                <div class="card-custom p-3 text-center h-100">
                    <div class="card-icon mx-auto mb-3"><i class="bi bi-wrench-adjustable"></i></div>
                    <h5 class="fw-700">Nachbearbeitung</h5>
                    <p class="small text-muted mb-0">Support-Entfernung, Schleifen, Grundieren oder spezielle Oberflächenbehandlung werden separat berechnet.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Pricing -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <span class="section-tag">FAQ</span>
            <h2 class="section-title">Häufige Fragen zu den Preisen</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in-up">
                <div class="accordion accordion-custom" id="pricingFaq">

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Gibt es einen Mindestbestellwert?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#pricingFaq">
                            <div class="accordion-body">
                                Ja, der Mindestbestellwert beträgt <strong>5,00 €</strong> pro Auftrag. Das deckt Rüstzeit, Materialverschnitt und Verwaltungsaufwand ab.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Wie bekomme ich ein konkretes Angebot?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                            <div class="accordion-body">
                                Lade deine Datei über das <a href="/request.php" class="text-accent">Anfrageformular</a> hoch. Wir prüfen die Datei und senden dir innerhalb von 24 Stunden ein konkretes, unverbindliches Angebot per E-Mail.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Was kostet der Expressservice genau?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                            <div class="accordion-body">
                                Der Expressaufschlag beträgt ca. 50% auf den regulären Preis. Bei sehr kleinen Aufträgen (unter 10 €) kann ein Mindestaufschlag von 5 € anfallen. Bitte beim Einreichen der Anfrage „Express" im Kommentarfeld vermerken.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Ist Lieferung im Preis inbegriffen?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                            <div class="accordion-body">
                                Nein. Abholung vor Ort ist kostenlos. Versand innerhalb Italiens kostet ca. 5–7 € je nach Paketgröße und wird separat ausgewiesen. Wir versenden ausschließlich innerhalb Italiens und der EU.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Welche Zahlungsmethoden werden akzeptiert?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                            <div class="accordion-body">
                                Zahlung auf Rechnung (Überweisung) oder Barzahlung bei Abholung. Weitere Details werden mit dem Angebot mitgeteilt.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section-sm">
    <div class="container">
        <div class="cta-banner fade-in-up">
            <h2 class="section-title mb-3">Kostenloses Angebot anfordern</h2>
            <p class="text-muted mb-4">Lade deine STL-Datei hoch und erhalte innerhalb von 24 Stunden ein individuelles Preisangebot.</p>
            <a href="/request.php" class="btn-hero-primary">
                <i class="bi bi-send-fill"></i> Jetzt Anfrage senden
            </a>
        </div>
    </div>
</section>

<a href="#" id="backToTop" aria-label="Zurück nach oben"><i class="bi bi-arrow-up"></i></a>
<?php require_once 'includes/footer.php'; ?>
