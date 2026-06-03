<?php
$page_title = 'Druckanfrage senden – 3D Druck Südtirol';
$page_desc  = 'Druckanfrage für deinen 3D-Druck in Südtirol. STL, 3MF oder OBJ hochladen und ein unverbindliches Angebot erhalten.';
require_once 'includes/header.php';

$success = $_GET['success'] ?? '';
$error   = $_GET['error']   ?? '';
$csrf    = generate_csrf_token();

$materials = ['PLA (Standard)', 'PLA+ (Verstärkt)', 'PETG (Robust)', 'ASA (UV-beständig)', 'TPU (Flexibel)', 'Holz-PLA (Optik)', 'Seiden-PLA (Glanz)', 'Beratung gewünscht'];
$colors    = ['Schwarz', 'Weiß', 'Grau', 'Silber', 'Rot', 'Blau', 'Grün', 'Gelb', 'Orange', 'Transparent', 'Sonstiges / Egal'];
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="/">Start</a></li>
                <li class="breadcrumb-item active">Anfrage senden</li>
            </ol>
        </nav>
        <span class="section-tag">Druckanfrage</span>
        <h1 class="mt-2 mb-3">Anfrage senden</h1>
        <p>Fülle das Formular aus und lade deine Datei hoch. Wir melden uns innerhalb von 24 Stunden mit einem unverbindlichen Angebot.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <?php if ($success === '1'): ?>
                    <div class="alert-custom alert-success-custom mb-4">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>
                            <strong>Anfrage erfolgreich gesendet!</strong><br>
                            <span>Wir haben deine Anfrage erhalten und melden uns innerhalb von 24 Stunden per E-Mail.</span>
                        </div>
                    </div>
                <?php elseif ($error): ?>
                    <div class="alert-custom alert-error-custom mb-4">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>
                            <strong>Fehler beim Senden.</strong><br>
                            <span><?= h(urldecode($error)) ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-card fade-in-up">
                    <form id="requestForm" action="/send_request.php" method="POST" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                        <!-- Honeypot -->
                        <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

                        <h4 class="mb-4" style="font-size:1.1rem">
                            <i class="bi bi-person-fill text-accent me-2"></i>Deine Kontaktdaten
                        </h4>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label for="name" class="form-label">Name <span class="text-accent">*</span></label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Max Mustermann" required minlength="2" maxlength="100"
                                    value="<?= h($_GET['name'] ?? '') ?>">
                                <div class="invalid-feedback">Bitte gib deinen Namen ein.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="email" class="form-label">E-Mail-Adresse <span class="text-accent">*</span></label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="name@beispiel.de" required maxlength="200"
                                    value="<?= h($_GET['email'] ?? '') ?>">
                                <div class="invalid-feedback">Bitte gib eine gültige E-Mail-Adresse ein.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="phone" class="form-label">Telefon <span class="text-muted">(optional)</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                    placeholder="+39 XXX XXX XXXX" maxlength="30"
                                    value="<?= h($_GET['phone'] ?? '') ?>">
                            </div>
                        </div>

                        <hr class="divider my-4">

                        <h4 class="mb-4" style="font-size:1.1rem">
                            <i class="bi bi-printer-fill text-accent me-2"></i>Druckdetails
                        </h4>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label for="material" class="form-label">Material <span class="text-accent">*</span></label>
                                <select id="material" name="material" class="form-select" required>
                                    <option value="" disabled selected>Material wählen…</option>
                                    <?php foreach ($materials as $mat): ?>
                                        <option value="<?= h($mat) ?>"><?= h($mat) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Bitte wähle ein Material.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="color" class="form-label">Farbe <span class="text-accent">*</span></label>
                                <select id="color" name="color" class="form-select" required>
                                    <option value="" disabled selected>Farbe wählen…</option>
                                    <?php foreach ($colors as $col): ?>
                                        <option value="<?= h($col) ?>"><?= h($col) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Bitte wähle eine Farbe.</div>
                            </div>
                            <div class="col-12">
                                <label for="quantity" class="form-label">Stückzahl <span class="text-accent">*</span></label>
                                <input type="number" id="quantity" name="quantity" class="form-control"
                                    placeholder="1" min="1" max="9999" value="1" required style="max-width:160px">
                                <div class="invalid-feedback">Bitte gib eine gültige Stückzahl ein.</div>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Beschreibung / Anforderungen <span class="text-accent">*</span></label>
                                <textarea id="description" name="description" class="form-control" rows="5"
                                    placeholder="Beschreibe dein Projekt: Verwendungszweck, besondere Anforderungen, gewünschte Qualität, Toleranzen, ob Expressservice gewünscht wird, etc."
                                    required minlength="10" maxlength="5000"></textarea>
                                <div class="invalid-feedback">Bitte beschreibe deine Anforderungen (mind. 10 Zeichen).</div>
                                <div class="form-text text-muted small mt-1">Mind. 10 Zeichen. Je mehr Details, desto genauer das Angebot.</div>
                            </div>
                        </div>

                        <hr class="divider my-4">

                        <h4 class="mb-4" style="font-size:1.1rem">
                            <i class="bi bi-file-earmark-arrow-up-fill text-accent me-2"></i>Datei hochladen
                        </h4>

                        <div class="file-drop-area mb-2">
                            <input type="file" id="fileInput" name="stl_file"
                                accept=".stl,.3mf,.obj,.zip"
                                aria-label="3D-Datei hochladen">
                            <div class="file-drop-icon"><i class="bi bi-cloud-upload-fill"></i></div>
                            <div class="file-drop-text">Datei hier ablegen oder klicken zum Auswählen</div>
                            <div class="file-drop-formats">STL · 3MF · OBJ · ZIP — Max. 50 MB</div>
                        </div>
                        <div id="fileNameDisplay" class="small text-accent mb-3" style="display:none"></div>
                        <div class="form-text text-muted small mb-4">
                            <i class="bi bi-info-circle me-1"></i>
                            Du hast noch keine Datei? Beschreibe dein Projekt im Textfeld – wir helfen weiter.
                        </div>

                        <hr class="divider my-4">

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                            <label class="form-check-label small" for="privacy">
                                Ich habe die <a href="/datenschutz.php" class="text-accent" target="_blank">Datenschutzerklärung</a> gelesen und bin mit der Verarbeitung meiner Daten zur Bearbeitung dieser Anfrage einverstanden. <span class="text-accent">*</span>
                            </label>
                            <div class="invalid-feedback">Bitte akzeptiere die Datenschutzerklärung.</div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="bi bi-send-fill"></i> Anfrage senden
                        </button>
                    </form>
                </div>

            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="contact-info-card mb-4 fade-in-up">
                    <h5 class="mb-3" style="font-size:1rem">Was passiert nach der Anfrage?</h5>
                    <div class="contact-info-item">
                        <div class="contact-info-icon" style="background:rgba(34,197,94,0.1);color:#4ade80">
                            <i class="bi bi-1-circle-fill"></i>
                        </div>
                        <div>
                            <h6>Eingang bestätigt</h6>
                            <p>Du erhältst sofort eine Eingangsbestätigung per E-Mail.</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon" style="background:rgba(251,191,36,0.1);color:#fbbf24">
                            <i class="bi bi-2-circle-fill"></i>
                        </div>
                        <div>
                            <h6>Prüfung innerhalb 24h</h6>
                            <p>Wir prüfen deine Datei und senden ein individuelles Angebot.</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon" style="background:rgba(0,212,255,0.1);color:var(--accent)">
                            <i class="bi bi-3-circle-fill"></i>
                        </div>
                        <div>
                            <h6>Druck nach Freigabe</h6>
                            <p>Nach deiner Bestätigung wird dein Teil gedruckt.</p>
                        </div>
                    </div>
                </div>

                <div class="card-custom p-3 fade-in-up">
                    <h6 class="mb-3"><i class="bi bi-question-circle-fill text-accent me-2"></i>Fragen?</h6>
                    <p class="small text-muted mb-3">Kein Problem – schreib uns direkt per E-Mail oder nutze das Kontaktformular.</p>
                    <a href="mailto:<?= SITE_EMAIL ?>" class="btn-primary-custom w-100 justify-content-center">
                        <i class="bi bi-envelope-fill"></i> <?= SITE_EMAIL ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<a href="#" id="backToTop" aria-label="Zurück nach oben"><i class="bi bi-arrow-up"></i></a>
<?php require_once 'includes/footer.php'; ?>
