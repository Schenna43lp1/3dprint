<?php
$page_title = 'Datenschutzerklärung – 3D Druck Südtirol';
$page_desc  = 'Datenschutzerklärung gemäß DSGVO / GDPR für 3D Druck Südtirol.';
require_once 'includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="/">Start</a></li>
                <li class="breadcrumb-item active">Datenschutz</li>
            </ol>
        </nav>
        <h1 class="mt-2 mb-3">Datenschutzerklärung</h1>
        <p class="text-muted small">Zuletzt aktualisiert: <?= date('d.m.Y') ?></p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card fade-in-up">

                    <h3 style="font-size:1.2rem">1. Verantwortlicher</h3>
                    <p>
                        Markus Stufer<br>
                        Schennastraße 81<br>
                        39017 Schenna (Scena), Südtirol, Italien<br>
                        E-Mail: <a href="mailto:info@3ddruck-suedtirol.it" class="text-accent">info@3ddruck-suedtirol.it</a><br>
                        Telefon: <a href="tel:+393245943473" class="text-accent">+39 324 594 3473</a>
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">2. Erhobene Daten</h3>
                    <p>Beim Besuch dieser Website und beim Ausfüllen von Formularen können folgende personenbezogene Daten erhoben werden:</p>
                    <ul>
                        <li>Name und E-Mail-Adresse (bei Kontakt- und Anfrage-Formularen)</li>
                        <li>Telefonnummer (optional)</li>
                        <li>Hochgeladene Dateien (STL, 3MF, OBJ, ZIP) für den Druckauftrag</li>
                        <li>IP-Adresse (serverseitig im Zugriffslog des Webservers)</li>
                    </ul>

                    <h3 class="mt-4" style="font-size:1.2rem">3. Zweck der Verarbeitung</h3>
                    <p>
                        Die erhobenen Daten werden ausschließlich zur Bearbeitung deiner Anfrage und zur Kontaktaufnahme bezüglich des 3D-Druckauftrags verwendet.
                        Eine Weitergabe an Dritte erfolgt nicht.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">4. Rechtsgrundlage</h3>
                    <p>
                        Die Verarbeitung erfolgt auf Grundlage von Art. 6 Abs. 1 lit. b DSGVO (Vertragsanbahnung / Vertragserfüllung)
                        sowie Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an der Beantwortung von Anfragen).
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">5. Speicherdauer</h3>
                    <p>
                        Personenbezogene Daten und hochgeladene Dateien werden nach Abschluss der Anfrage oder spätestens nach
                        <strong>6 Monaten</strong> gelöscht, sofern keine gesetzliche Aufbewahrungspflicht besteht.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">6. Deine Rechte</h3>
                    <p>Du hast gemäß DSGVO folgende Rechte gegenüber uns:</p>
                    <ul>
                        <li><strong>Auskunft</strong> (Art. 15 DSGVO) – welche Daten wir über dich gespeichert haben</li>
                        <li><strong>Berichtigung</strong> (Art. 16 DSGVO) – Korrektur unrichtiger Daten</li>
                        <li><strong>Löschung</strong> (Art. 17 DSGVO) – Löschung deiner Daten</li>
                        <li><strong>Einschränkung</strong> (Art. 18 DSGVO) – Einschränkung der Verarbeitung</li>
                        <li><strong>Widerspruch</strong> (Art. 21 DSGVO) – Widerspruch gegen die Verarbeitung</li>
                    </ul>
                    <p>Zur Ausübung dieser Rechte wende dich per E-Mail an <a href="mailto:info@3ddruck-suedtirol.it" class="text-accent">info@3ddruck-suedtirol.it</a>.</p>

                    <h3 class="mt-4" style="font-size:1.2rem">7. Cookies</h3>
                    <p>
                        Diese Website verwendet ausschließlich technisch notwendige Session-Cookies.
                        Diese Cookies dienen dem CSRF-Schutz der Formulare und werden nach dem Schließen des Browsers gelöscht.
                        Es werden keine Tracking-, Analyse- oder Werbe-Cookies eingesetzt.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">8. Externe Ressourcen</h3>
                    <p>
                        Diese Website lädt CSS- und JavaScript-Bibliotheken von externen CDN-Diensten
                        (Bootstrap via jsDelivr). Dabei kann technisch bedingt deine IP-Adresse an diese Dienste übertragen werden.
                        Eine Nutzung dieser Daten zu Tracking-Zwecken durch uns findet nicht statt.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">9. Beschwerderecht</h3>
                    <p>
                        Du hast das Recht, dich beim <strong>Garante per la protezione dei dati personali</strong>
                        (italienische Datenschutzbehörde) zu beschweren:<br>
                        <a href="https://www.garanteprivacy.it" target="_blank" rel="noopener" class="text-accent">www.garanteprivacy.it</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>
