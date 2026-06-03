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
        <p>Zuletzt aktualisiert: <?= date('d.m.Y') ?></p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card fade-in-up">
                    <p class="text-muted small mb-4">
                        <strong>Hinweis:</strong> Diese Datenschutzerklärung ist ein Platzhalter und muss vor dem Livegang durch einen Rechtsanwalt oder Datenschutzbeauftragten vollständig ausgefüllt werden.
                    </p>

                    <h3 style="font-size:1.2rem">1. Verantwortlicher</h3>
                    <p>[Name und Adresse des Verantwortlichen, siehe Impressum]</p>

                    <h3 class="mt-4" style="font-size:1.2rem">2. Erhobene Daten</h3>
                    <p>Beim Besuch dieser Website und beim Ausfüllen von Formularen können folgende personenbezogene Daten erhoben werden:</p>
                    <ul class="text-muted">
                        <li>Name und E-Mail-Adresse (bei Kontakt- und Anfrage-Formularen)</li>
                        <li>Telefonnummer (optional)</li>
                        <li>Hochgeladene Dateien (STL, 3MF, OBJ, ZIP)</li>
                        <li>IP-Adresse (serverseitig im Zugriffslog)</li>
                    </ul>

                    <h3 class="mt-4" style="font-size:1.2rem">3. Zweck der Verarbeitung</h3>
                    <p>Die erhobenen Daten werden ausschließlich zur Bearbeitung deiner Anfrage und zur Kontaktaufnahme verwendet. Eine Weitergabe an Dritte erfolgt nicht.</p>

                    <h3 class="mt-4" style="font-size:1.2rem">4. Speicherdauer</h3>
                    <p>Daten werden nach Abschluss der Anfrage oder spätestens nach [X Monaten] gelöscht, sofern keine gesetzliche Aufbewahrungspflicht besteht.</p>

                    <h3 class="mt-4" style="font-size:1.2rem">5. Deine Rechte</h3>
                    <p>Du hast das Recht auf Auskunft, Berichtigung, Löschung und Einschränkung der Verarbeitung deiner Daten (Art. 15–18 DSGVO). Wende dich dafür per E-Mail an uns.</p>

                    <h3 class="mt-4" style="font-size:1.2rem">6. Cookies</h3>
                    <p>Diese Website verwendet ausschließlich technisch notwendige Session-Cookies für die Formularsicherheit (CSRF-Schutz). Es werden keine Tracking- oder Analyse-Cookies eingesetzt.</p>

                    <h3 class="mt-4" style="font-size:1.2rem">7. Externe Dienste</h3>
                    <p>
                        Diese Website lädt Schriften von Google Fonts und CSS/JS von CDN-Diensten. Dabei können technisch bedingt IP-Adressen übertragen werden. [Ggf. anpassen oder selbst hosten.]
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">8. Beschwerderecht</h3>
                    <p>
                        Du hast das Recht, dich beim Garante per la protezione dei dati personali (italienische Datenschutzbehörde) zu beschweren:<br>
                        <a href="https://www.garanteprivacy.it" target="_blank" rel="noopener" class="text-accent">www.garanteprivacy.it</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>
