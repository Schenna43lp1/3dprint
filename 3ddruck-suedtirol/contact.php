<?php
$page_title = 'Kontakt – 3D Druck Service Südtirol';
$page_desc  = 'Kontaktiere uns für deinen 3D-Druckauftrag in Südtirol. E-Mail, Telefon und Kontaktformular.';
require_once 'includes/header.php';

$sent  = $_GET['sent']  ?? '';
$error = $_GET['error'] ?? '';
$csrf  = generate_csrf_token();
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="/">Start</a></li>
                <li class="breadcrumb-item active">Kontakt</li>
            </ol>
        </nav>
        <span class="section-tag">Wir helfen dir</span>
        <h1 class="mt-2 mb-3">Kontakt</h1>
        <p>Fragen, Feedback oder ein spezielles Anliegen? Melde dich gerne – wir antworten in der Regel innerhalb von 24 Stunden.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-5">

            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="contact-info-card mb-4 fade-in-up">
                    <h5 class="mb-0" style="font-size:1.05rem">Kontaktinformationen</h5>
                    <hr class="divider my-3">
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <h6>E-Mail</h6>
                            <p><a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <h6>Erreichbarkeit</h6>
                            <p>Mo–Fr: 09:00 – 18:00 Uhr</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <h6>Standort</h6>
                            <p>Südtirol, Italien<br><span class="small text-muted">Genaue Adresse auf Anfrage</span></p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-chat-dots-fill"></i></div>
                        <div>
                            <h6>Sprachen</h6>
                            <p>Deutsch · Italiano</p>
                        </div>
                    </div>
                </div>

                <!-- Map Placeholder -->
                <div class="map-placeholder fade-in-up">
                    <i class="bi bi-map-fill"></i>
                    <span>Karte folgt</span>
                    <span class="small text-center px-3" style="font-size:0.75rem">Google Maps wird nach Datenschutzzustimmung eingebettet</span>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <?php if ($sent === '1'): ?>
                    <div class="alert-custom alert-success-custom mb-4">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>
                            <strong>Nachricht gesendet!</strong><br>
                            Wir melden uns so schnell wie möglich bei dir.
                        </div>
                    </div>
                <?php elseif ($error): ?>
                    <div class="alert-custom alert-error-custom mb-4">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>
                            <strong>Fehler.</strong> <?= h(urldecode($error)) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-card fade-in-up">
                    <h4 class="mb-4" style="font-size:1.1rem">
                        <i class="bi bi-envelope-fill text-accent me-2"></i>Schreib uns
                    </h4>
                    <form id="contactForm" action="/send_contact.php" method="POST" novalidate>
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= h($csrf) ?>">
                        <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="cName" class="form-label">Name <span class="text-accent">*</span></label>
                                <input type="text" id="cName" name="name" class="form-control"
                                    placeholder="Dein Name" required minlength="2" maxlength="100">
                                <div class="invalid-feedback">Bitte gib deinen Namen ein.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="cEmail" class="form-label">E-Mail <span class="text-accent">*</span></label>
                                <input type="email" id="cEmail" name="email" class="form-control"
                                    placeholder="deine@email.de" required maxlength="200">
                                <div class="invalid-feedback">Bitte gib eine gültige E-Mail ein.</div>
                            </div>
                            <div class="col-12">
                                <label for="cSubject" class="form-label">Betreff <span class="text-accent">*</span></label>
                                <input type="text" id="cSubject" name="subject" class="form-control"
                                    placeholder="Worum geht es?" required minlength="3" maxlength="200">
                                <div class="invalid-feedback">Bitte gib einen Betreff an.</div>
                            </div>
                            <div class="col-12">
                                <label for="cMessage" class="form-label">Nachricht <span class="text-accent">*</span></label>
                                <textarea id="cMessage" name="message" class="form-control" rows="6"
                                    placeholder="Deine Nachricht…" required minlength="10" maxlength="5000"></textarea>
                                <div class="invalid-feedback">Bitte schreibe eine Nachricht (mind. 10 Zeichen).</div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cPrivacy" name="privacy" required>
                                    <label class="form-check-label small" for="cPrivacy">
                                        Ich habe die <a href="/datenschutz.php" class="text-accent" target="_blank">Datenschutzerklärung</a> gelesen und bin einverstanden. <span class="text-accent">*</span>
                                    </label>
                                    <div class="invalid-feedback">Bitte akzeptiere die Datenschutzerklärung.</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-submit">
                                    <i class="bi bi-send-fill"></i> Nachricht senden
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Quick links -->
                <div class="row g-3 mt-2">
                    <div class="col-sm-6 fade-in-up">
                        <a href="/request.php" class="card-custom p-3 d-flex align-items-center gap-3 text-decoration-none">
                            <div class="card-icon mb-0 flex-shrink-0"><i class="bi bi-send-fill"></i></div>
                            <div>
                                <div class="fw-600" style="font-weight:600">Druckanfrage</div>
                                <div class="small text-muted">Datei hochladen & Angebot erhalten</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 fade-in-up">
                        <a href="/pricing.php" class="card-custom p-3 d-flex align-items-center gap-3 text-decoration-none">
                            <div class="card-icon mb-0 flex-shrink-0"><i class="bi bi-tag-fill"></i></div>
                            <div>
                                <div class="fw-600" style="font-weight:600">Preisinfo</div>
                                <div class="small text-muted">Orientierungspreise ansehen</div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<a href="#" id="backToTop" aria-label="Zurück nach oben"><i class="bi bi-arrow-up"></i></a>
<?php require_once 'includes/footer.php'; ?>
