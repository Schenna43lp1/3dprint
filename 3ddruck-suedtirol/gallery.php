<?php
$page_title = 'Galerie – 3D Druck Beispiele Südtirol';
$page_desc  = 'Galerie von 3D-Drucken: Kabelmanagement, Homelab, Schreibtischzubehör, Funktionsteile und Custom-Prints aus Südtirol.';
require_once 'includes/header.php';

$items = [
    // [Label, Category, Icon, Description]
    ['Kabelkanal 40mm',       'cable',      'bi-usb-plug-fill',       'Modularer Kabelkanal für den Schreibtisch, PLA schwarz'],
    ['Kabelbinder-Station',   'cable',      'bi-grip-vertical',       'Halterung für Kabelbinder, direkt am Schreibtischbein'],
    ['Velcro-Clip Rund',      'cable',      'bi-circle',              'Kleiner Clip für Kabelorganisation, PETG weiß'],
    ['Rack-Blank Panel 1U',   'homelab',    'bi-server',              '19" Rack-Blindabdeckung 1U, PLA schwarz'],
    ['Pi Zero Gehäuse',       'homelab',    'bi-cpu-fill',            'Kompaktes Gehäuse für Raspberry Pi Zero 2W'],
    ['Patch-Panel-Halter',    'homelab',    'bi-ethernet',            'Halterung für Kabelbeschriftung am Patch-Panel'],
    ['SSD-Cage 2.5"',         'homelab',    'bi-hdd-fill',            'Halterung für 2.5" SSDs im Rack-Gehäuse'],
    ['Monitor-Ständer Fuß',   'desk',       'bi-display',             'Erhöhungsfuß für Bildschirm, PETG grau'],
    ['Klemm-Ablagefach',      'desk',       'bi-tray-fill',           'Seitliches Klemm-Regal am Schreibtisch'],
    ['Headset-Halter',        'desk',       'bi-headphones',          'Kopfhörerhalterung, Klemmmontage, PLA weiß'],
    ['Stifthalter',           'desk',       'bi-pencil-fill',         'Modularer Stifthalter, 3-teilig, PLA+ schwarz'],
    ['Türscharnier 40mm',     'functional', 'bi-door-open',           'Ersatzscharnier aus PETG, maßgenau gedruckt'],
    ['Lüftungskanal',         'functional', 'bi-wind',                'Umlenkkanal für 60mm Gehäuselüfter'],
    ['Gelenk-Klemme',         'functional', 'bi-wrench-adjustable',   'Einstellbare Klemme, TPU für spielfreien Sitz'],
    ['Wasserrinne Clip',      'functional', 'bi-droplet-fill',        'Regenrinnen-Befestigungsklammer, ASA UV-stabil'],
    ['LED-Strip Kanal',       'custom',     'bi-lightbulb-fill',      'Diffusorkanal für LED-Strips unter dem Schreibtisch'],
    ['Namensschild',          'custom',     'bi-person-badge-fill',   'Personalisiertes Namensschild, zweifarbig, PLA'],
    ['Logo-Plakette',         'custom',     'bi-award-fill',          'Firmenlogo als 3D-Plakette, Matt-PLA silber'],
];
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="/">Start</a></li>
                <li class="breadcrumb-item active">Galerie</li>
            </ol>
        </nav>
        <span class="section-tag">Beispieldrucke</span>
        <h1 class="mt-2 mb-3">Galerie</h1>
        <p>Eine Auswahl an Projekten, die wir bereits realisiert haben. Bilder folgen laufend – die Placeholder zeigen die Kategorien.</p>
    </div>
</section>

<!-- Gallery -->
<section class="section">
    <div class="container">

        <!-- Filter -->
        <div class="gallery-filter d-flex flex-wrap gap-2 justify-content-center mb-5 fade-in-up">
            <button class="btn-filter active" data-filter="all">Alle</button>
            <button class="btn-filter" data-filter="cable">Kabelmanagement</button>
            <button class="btn-filter" data-filter="homelab">Homelab</button>
            <button class="btn-filter" data-filter="desk">Schreibtisch</button>
            <button class="btn-filter" data-filter="functional">Funktionsteile</button>
            <button class="btn-filter" data-filter="custom">Custom</button>
        </div>

        <!-- Grid -->
        <div class="gallery-grid" id="galleryGrid">
            <?php foreach ($items as $item): ?>
                <div class="gallery-item fade-in-up" data-category="<?= $item[1] ?>">
                    <div class="gallery-placeholder">
                        <i class="bi <?= $item[2] ?>"></i>
                        <span><?= h($item[0]) ?></span>
                        <small class="text-center px-2" style="font-size:0.7rem;opacity:.6"><?= h($item[3]) ?></small>
                    </div>
                    <div class="gallery-overlay">
                        <span><?= h($item[0]) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="text-center text-muted small mt-5 fade-in-up">
            <i class="bi bi-images me-1"></i>
            Produktfotos werden laufend ergänzt. Hast du ein interessantes Projekt? <a href="/contact.php" class="text-accent">Schreib uns.</a>
        </p>
    </div>
</section>

<!-- CTA -->
<section class="section-sm">
    <div class="container">
        <div class="cta-banner fade-in-up">
            <h2 class="section-title mb-3">Dein Projekt könnte hier sein</h2>
            <p class="text-muted mb-4">Schicke uns deine STL-Datei und lass uns deine Idee Wirklichkeit werden.</p>
            <a href="/request.php" class="btn-hero-primary">
                <i class="bi bi-send-fill"></i> Jetzt Anfrage senden
            </a>
        </div>
    </div>
</section>

<a href="#" id="backToTop" aria-label="Zurück nach oben"><i class="bi bi-arrow-up"></i></a>
<?php require_once 'includes/footer.php'; ?>
