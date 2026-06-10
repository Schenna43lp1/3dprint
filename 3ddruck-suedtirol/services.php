<?php
$page_title = 'Leistungen – 3D Druck Service Südtirol';
$page_desc  = 'Unsere 3D-Druckleistungen: STL-Dateien drucken, Funktionsteile, Ersatzteile, Prototypen und Expressservice in Südtirol.';
require_once 'includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="/">Start</a></li>
                <li class="breadcrumb-item active">Leistungen</li>
            </ol>
        </nav>
        <span class="section-tag">Was wir tun</span>
        <h1 class="mt-2 mb-3">Unsere Leistungen</h1>
        <p>Vom einfachen Druck deiner STL-Datei bis hin zum technisch anspruchsvollen Funktionsteil – wir begleiten dein Projekt von der Datei bis zum fertigen Teil.</p>
    </div>
</section>

<!-- Main Services -->
<section class="section">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-6 fade-in-up">
                <div class="card-custom service-detail-card">
                    <div class="card-icon"><i class="bi bi-file-earmark-code-fill"></i></div>
                    <h3>STL- & 3MF-Dateien drucken</h3>
                    <p>
                        Du hast bereits eine fertige Druckdatei? Perfekt. Lade deine STL-, 3MF- oder OBJ-Datei über unser Formular hoch. Wir kümmern uns um das Slicing, die Druckparameter und die Qualitätskontrolle. Unterstützte Formate: STL, 3MF, OBJ, ZIP (für mehrere Dateien).
                    </p>
                    <div class="tags">
                        <span class="service-tag">STL</span>
                        <span class="service-tag">3MF</span>
                        <span class="service-tag">OBJ</span>
                        <span class="service-tag">ZIP</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 fade-in-up">
                <div class="card-custom service-detail-card">
                    <div class="card-icon"><i class="bi bi-gear-wide-connected"></i></div>
                    <h3>Funktionsteile & technische Drucke</h3>
                    <p>
                        Für Anwendungen, bei denen es auf Festigkeit, Maßhaltigkeit und Materialeigenschaften ankommt. Wir wählen gemeinsam das passende Filament (PLA, PETG) und die richtigen Druckparameter, um ein mechanisch belastbares Ergebnis zu erzielen.
                    </p>
                    <div class="tags">
                        <span class="service-tag">PETG</span>
                        <span class="service-tag">Robust</span>
                        <span class="service-tag">Maßhaltig</span>
                        <span class="service-tag">Technisch</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 fade-in-up">
                <div class="card-custom service-detail-card">
                    <div class="card-icon"><i class="bi bi-tools"></i></div>
                    <h3>Ersatzteile</h3>
                    <p>
                        Ein Kunststoffteil ist gebrochen und nicht mehr erhältlich? Kein Problem. Mit einer Skizze, einem Foto oder einer vorhandenen Datei können wir das Teil nachdrucken. Für viele Alltagsgeräte, Haushaltsgeräte, Fahrzeuge oder Maschinen bieten wir schnelle Lösungen.
                    </p>
                    <div class="tags">
                        <span class="service-tag">Reparatur</span>
                        <span class="service-tag">Reproduktion</span>
                        <span class="service-tag">PLA / PETG</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 fade-in-up">
                <div class="card-custom service-detail-card">
                    <div class="card-icon"><i class="bi bi-cpu-fill"></i></div>
                    <h3>Homelab & IT-Zubehör</h3>
                    <p>
                        Rack-Halterungen, Raspberry Pi-Gehäuse, Server-Abstandshalter, Kabelführungen – wir verstehen die Welt des Homelabs. Druck von fertigen Thingiverse-Modellen genauso wie von eigenen Designs für dein Setup.
                    </p>
                    <div class="tags">
                        <span class="service-tag">Raspberry Pi</span>
                        <span class="service-tag">Server Rack</span>
                        <span class="service-tag">Kabelmanagement</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 fade-in-up">
                <div class="card-custom service-detail-card">
                    <div class="card-icon"><i class="bi bi-lightbulb-fill"></i></div>
                    <h3>Prototypen & Produktentwicklung</h3>
                    <p>
                        Du hast eine Idee und möchtest sie schnell validieren? FDM-Druck ist ideal für die frühe Designphase: Schnelle Iterationen, vergleichsweise geringe Kosten und sofortiges haptisches Feedback. Wir unterstützen KMUs, Startups und Einzelpersonen.
                    </p>
                    <div class="tags">
                        <span class="service-tag">Rapid Prototyping</span>
                        <span class="service-tag">Iteration</span>
                        <span class="service-tag">KMU</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 fade-in-up">
                <div class="card-custom service-detail-card">
                    <div class="card-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                    <h3>Expressservice</h3>
                    <p>
                        Für dringende Anfragen bieten wir bevorzugte Bearbeitung an. Anfragen vor 10:00 Uhr können oft noch am gleichen oder nächsten Werktag fertiggestellt werden. Express-Aufschlag je nach Aufwand.
                    </p>
                    <div class="tags">
                        <span class="service-tag">24h</span>
                        <span class="service-tag">Bevorzugt</span>
                        <span class="service-tag">Kurzfristig</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Materials Section -->
<section class="section" id="materials" style="background:var(--bg-card2); border-top:1px solid var(--border); border-bottom:1px solid var(--border)">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <span class="section-tag">Materialien</span>
            <h2 class="section-title">Filamente & Eigenschaften</h2>
            <p class="section-subtitle">Die Wahl des richtigen Materials ist entscheidend für das Ergebnis. Wir beraten dich gerne.</p>
        </div>
        <div class="row g-4">
            <?php
            $materials = [
                ['PLA',      '#4ade80', 'Einsteiger & Allgemein',    'Biologisch abbaubar, gute Oberflächenqualität, einfach zu drucken. Ideal für Modelle, Gadgets und Innenanwendungen.', ['Leicht', 'Innen', 'Günstig']],
                ['PETG',     '#38bdf8', 'Technisch & Robust',        'Chemikalienbeständig, lebensmittelsicher (je nach Farbe), gute Zähigkeit. Für Funktionsteile, Flüssigkeitsbehälter, mechanische Teile.', ['Robust', 'Chemikalien', 'Innen+Außen']],
            ];
            foreach ($materials as $m): ?>
                <div class="col-sm-6 col-lg-4 fade-in-up">
                    <div class="card-custom p-3 h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="dot" style="width:14px;height:14px;border-radius:50%;background:<?= $m[1] ?>;flex-shrink:0"></span>
                            <span class="fw-700" style="font-family:var(--font-display);font-weight:700"><?= $m[0] ?></span>
                            <span class="ms-auto small text-muted"><?= $m[2] ?></span>
                        </div>
                        <p class="small text-muted mb-2"><?= $m[3] ?></p>
                        <div class="d-flex gap-1 flex-wrap">
                            <?php foreach ($m[4] as $tag): ?>
                                <span class="service-tag"><?= $tag ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Process -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <span class="section-tag">Ablauf</span>
            <h2 class="section-title">So funktioniert's</h2>
        </div>
        <div class="row g-3 fade-in-up">
            <div class="col-md-6 col-lg-3">
                <div class="process-step h-100">
                    <div class="step-number">1</div>
                    <div>
                        <h5>Anfrage senden</h5>
                        <p>Formular ausfüllen, Datei hochladen, Material und Farbe angeben. Fertig.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="process-step h-100">
                    <div class="step-number">2</div>
                    <div>
                        <h5>Prüfung & Angebot</h5>
                        <p>Wir prüfen die Datei auf Druckbarkeit und senden dir ein Angebot innerhalb von 24h.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="process-step h-100">
                    <div class="step-number">3</div>
                    <div>
                        <h5>Freigabe & Druck</h5>
                        <p>Nach deiner Bestätigung starten wir den Druck. Du wirst über den Fortschritt informiert.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="process-step h-100">
                    <div class="step-number">4</div>
                    <div>
                        <h5>Übergabe</h5>
                        <p>Abholung in Südtirol oder Versand per Post – nach deinem Wunsch.</p>
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
            <h2 class="section-title mb-3">Bereit für deinen Druck?</h2>
            <p class="text-muted mb-4">Keine Mindestbestellmenge. Kein Aufwand. Einfach Datei hochladen und Angebot abwarten.</p>
            <a href="/request.php" class="btn-hero-primary">
                <i class="bi bi-send-fill"></i> Jetzt Anfrage senden
            </a>
        </div>
    </div>
</section>

<a href="#" id="backToTop" aria-label="Zurück nach oben"><i class="bi bi-arrow-up"></i></a>
<?php require_once 'includes/footer.php'; ?>
