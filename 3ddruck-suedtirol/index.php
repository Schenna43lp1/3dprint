<?php
$page_title = '3D Druck Service Südtirol – STL Drucken lassen';
$page_desc  = 'Professioneller 3D-Druckservice in Südtirol. Du hast eine STL-Datei? Wir drucken sie für dich. Schnell, präzise und lokal.';
require_once 'includes/header.php';
?>

<!-- Hero -->
<section class="hero">
    <div class="hero-grid-bg"></div>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-geo-alt-fill"></i>
                    Lokaler Service · Südtirol, Italien
                </div>
                <h1 class="hero-title">
                    <span class="highlight">3D Druck</span><br>
                    Service Südtirol
                </h1>
                <p class="hero-subtitle">
                    Du hast eine STL-Datei? Wir drucken sie für dich.<br>
                    Präzise, schnell und direkt aus deiner Region.
                </p>
                <div class="hero-cta-group">
                    <a href="/request.php" class="btn-hero-primary">
                        <i class="bi bi-send-fill"></i> Jetzt Anfrage senden
                    </a>
                    <a href="/services.php" class="btn-hero-secondary">
                        <i class="bi bi-grid-3x3-gap"></i> Leistungen ansehen
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <div class="hero-stat-value">PLA</div>
                        <div class="hero-stat-label">PETG · ASA · TPU</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-value">50<span style="font-size:1.2rem">MB</span></div>
                        <div class="hero-stat-label">Upload-Limit</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-value">FDM</div>
                        <div class="hero-stat-label">Drucktechnologie</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="printer-cube">
                        <div class="icon-wrap"><i class="bi bi-printer-fill"></i></div>
                    </div>
                    <div class="floating-chip chip-1">
                        <span class="chip-dot"></span> STL · 3MF · OBJ
                    </div>
                    <div class="floating-chip chip-2">
                        <span class="chip-dot orange"></span> FDM Druck
                    </div>
                    <div class="floating-chip chip-3">
                        <span class="chip-dot blue"></span> Schichtdicke: 0.1mm
                    </div>
                    <div class="floating-chip chip-4">
                        <i class="bi bi-geo-alt-fill text-accent me-1"></i> Südtirol
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Overview -->
<section class="section" id="services">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <span class="section-tag">Was wir anbieten</span>
            <h2 class="section-title">Unsere Leistungen</h2>
            <p class="section-subtitle">Von einfachen Modellen bis hin zu funktionalen Bauteilen – wir drucken, was du brauchst.</p>
        </div>
        <div class="row g-4" data-stagger>
            <div class="col-sm-6 col-lg-4">
                <div class="card-custom service-card">
                    <div class="card-icon"><i class="bi bi-file-earmark-code-fill"></i></div>
                    <h4>STL / 3MF Dateien</h4>
                    <p>Lade deine fertige Druckdatei hoch. Wir übernehmen Slicing, Kalibrierung und Druck.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card-custom service-card">
                    <div class="card-icon"><i class="bi bi-gear-wide-connected"></i></div>
                    <h4>Funktionsteile</h4>
                    <p>Präzise Bauteile für Prototypen, Vorrichtungen und technische Anwendungen.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card-custom service-card">
                    <div class="card-icon"><i class="bi bi-tools"></i></div>
                    <h4>Ersatzteile</h4>
                    <p>Defekte oder nicht mehr verfügbare Teile? Wir drucken dein Ersatzteil nach.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card-custom service-card">
                    <div class="card-icon"><i class="bi bi-cpu-fill"></i></div>
                    <h4>Homelab & IT</h4>
                    <p>Halterungen, Gehäuse, Kabelmanagement – perfekt für Server, Raspberry Pi und mehr.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card-custom service-card">
                    <div class="card-icon"><i class="bi bi-lightbulb-fill"></i></div>
                    <h4>Prototypen</h4>
                    <p>Idee zu Modell: Schnelle Iterationen für Produktentwicklung und Machbarkeitsstudien.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card-custom service-card">
                    <div class="card-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                    <h4>Expressservice</h4>
                    <p>Dringend? Mit unserem Expressservice bekommst du dein Teil bevorzugt gedruckt.</p>
                </div>
            </div>
        </div>
        <div class="text-center mt-5 fade-in-up">
            <a href="/services.php" class="btn-hero-secondary">
                Alle Leistungen ansehen <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Why Us -->
<section class="why-us-bg section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 fade-in-up">
                <span class="section-tag">Warum wir?</span>
                <h2 class="section-title">Regional, zuverlässig<br>und qualitätsbewusst</h2>
                <p class="text-muted mt-3">
                    Als lokaler Anbieter in Südtirol stehen wir für kurze Kommunikationswege, persönlichen Service und faire Preise. Kein Versand in ferne Länder – dein Druck bleibt regional.
                </p>
                <a href="/request.php" class="btn-primary-custom mt-4">
                    <i class="bi bi-send-fill me-1"></i> Anfrage starten
                </a>
            </div>
            <div class="col-lg-7 fade-in-up">
                <div class="why-item">
                    <div class="why-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <h5>Lokal in Südtirol</h5>
                        <p>Kein Versand ins Ausland. Direkter Kontakt, kurze Wege, persönlicher Service auf Deutsch und Italienisch.</p>
                    </div>
                </div>
                <div class="why-item">
                    <div class="why-icon"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <h5>Qualitätskontrolle</h5>
                        <p>Jedes Teil wird vor der Auslieferung geprüft. Maßhaltigkeit und Druckqualität stehen an erster Stelle.</p>
                    </div>
                </div>
                <div class="why-item">
                    <div class="why-icon"><i class="bi bi-chat-dots-fill"></i></div>
                    <div>
                        <h5>Persönliche Beratung</h5>
                        <p>Unsicher welches Material oder welche Einstellungen? Wir helfen dir, die beste Lösung für dein Projekt zu finden.</p>
                    </div>
                </div>
                <div class="why-item">
                    <div class="why-icon"><i class="bi bi-currency-euro"></i></div>
                    <div>
                        <h5>Faire & transparente Preise</h5>
                        <p>Kein versteckter Aufpreis. Individuelle Kalkulation je nach Material, Zeit und Komplexität.</p>
                    </div>
                </div>
                <div class="why-item">
                    <div class="why-icon"><i class="bi bi-recycle"></i></div>
                    <div>
                        <h5>Nachhaltige Materialien</h5>
                        <p>Wir setzen auf biologisch abbaubare Materialien wie PLA und achten auf einen ressourcenschonenden Betrieb.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Materials -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <span class="section-tag">Materialien</span>
            <h2 class="section-title">Verfügbare Druckmaterialien</h2>
            <p class="section-subtitle">Wir arbeiten mit hochwertigen Filamenten für jeden Anwendungsfall.</p>
        </div>
        <div class="d-flex flex-wrap justify-content-center gap-3 fade-in-up">
            <span class="material-badge"><span class="dot" style="background:#4ade80"></span> PLA – Standard</span>
            <span class="material-badge"><span class="dot" style="background:#38bdf8"></span> PETG – Robust</span>
            <span class="material-badge"><span class="dot" style="background:#fb923c"></span> ASA – UV-beständig</span>
            <span class="material-badge"><span class="dot" style="background:#a78bfa"></span> TPU – Flexibel</span>
            <span class="material-badge"><span class="dot" style="background:#f9fafb"></span> PLA+ – Verstärkt</span>
            <span class="material-badge"><span class="dot" style="background:#fbbf24"></span> Holz-PLA – Optik</span>
            <span class="material-badge"><span class="dot" style="background:#94a3b8"></span> Seiden-PLA – Glanz</span>
        </div>
        <div class="text-center mt-4 fade-in-up">
            <a href="/services.php#materials" class="text-accent text-decoration-none small">
                Material-Übersicht ansehen <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Example Prints / Gallery Preview -->
<section class="section" style="background: var(--bg-card2); border-top:1px solid var(--border); border-bottom:1px solid var(--border)">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <span class="section-tag">Galerie</span>
            <h2 class="section-title">Beispieldrucke</h2>
            <p class="section-subtitle">Ein kleiner Einblick in Projekte, die wir bereits realisiert haben.</p>
        </div>
        <div class="gallery-grid">
            <?php
            $previews = [
                ['Kabelkanal',       'cable_mgmt',  'bi-usb-plug-fill'],
                ['Server-Halterung', 'homelab',     'bi-server'],
                ['Schreibtisch-Clip','desk',        'bi-paperclip'],
                ['Scharnier',        'functional',  'bi-tools'],
                ['Custom Deckel',    'custom',      'bi-box-fill'],
                ['Raspberry Pi Case','homelab',     'bi-cpu-fill'],
            ];
            foreach ($previews as $p): ?>
                <div class="gallery-item fade-in-up" data-category="<?= $p[1] ?>">
                    <div class="gallery-placeholder">
                        <i class="bi <?= $p[2] ?>"></i>
                        <span><?= $p[0] ?></span>
                    </div>
                    <div class="gallery-overlay"><span><?= $p[0] ?></span></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5 fade-in-up">
            <a href="/gallery.php" class="btn-hero-secondary">
                Zur vollständigen Galerie <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <span class="section-tag">So einfach geht's</span>
            <h2 class="section-title">In 4 Schritten zum Druck</h2>
        </div>
        <div class="row g-3 fade-in-up">
            <div class="col-md-6 col-lg-3">
                <div class="process-step h-100">
                    <div class="step-number">1</div>
                    <div>
                        <h5>Datei hochladen</h5>
                        <p>Lade deine STL-, 3MF- oder OBJ-Datei über das Anfrageformular hoch.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="process-step h-100">
                    <div class="step-number">2</div>
                    <div>
                        <h5>Angebot erhalten</h5>
                        <p>Wir prüfen deine Datei und senden dir ein individuelles Angebot per E-Mail.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="process-step h-100">
                    <div class="step-number">3</div>
                    <div>
                        <h5>Druck starten</h5>
                        <p>Nach deiner Bestätigung starten wir den Druck mit deinen gewählten Einstellungen.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="process-step h-100">
                    <div class="step-number">4</div>
                    <div>
                        <h5>Liefern oder Abholen</h5>
                        <p>Dein fertiges Teil wird geliefert oder steht zur Abholung in Südtirol bereit.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="section-sm">
    <div class="container">
        <div class="cta-banner fade-in-up">
            <span class="section-tag">Bereit anzufangen?</span>
            <h2 class="section-title mb-3">Starte deine Anfrage jetzt</h2>
            <p class="text-muted mb-4 mx-auto" style="max-width:500px">
                Lade deine STL-Datei hoch und erhalte innerhalb von 24 Stunden ein unverbindliches Angebot.
            </p>
            <div class="hero-cta-group justify-content-center">
                <a href="/request.php" class="btn-hero-primary">
                    <i class="bi bi-send-fill"></i> Jetzt Anfrage senden
                </a>
                <a href="/contact.php" class="btn-hero-secondary">
                    <i class="bi bi-envelope"></i> Kontakt aufnehmen
                </a>
            </div>
        </div>
    </div>
</section>

<a href="#" id="backToTop" aria-label="Zurück nach oben"><i class="bi bi-arrow-up"></i></a>

<?php require_once 'includes/footer.php'; ?>
