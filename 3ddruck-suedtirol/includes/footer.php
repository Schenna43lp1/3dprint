
<!-- Footer -->
<footer class="footer mt-auto">
    <div class="footer-top py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="brand-icon"><i class="bi bi-printer-fill"></i></span>
                        <span class="brand-text fs-5">3D Druck <span class="brand-accent">Südtirol</span></span>
                    </div>
                    <p class="text-muted small">
                        Lokaler 3D-Druckservice in Südtirol. Wir drucken deine STL-Dateien präzise, schnell und zu fairen Preisen.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="social-link" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="mailto:info@3ddruck-suedtirol.it" class="social-link" aria-label="E-Mail"><i class="bi bi-envelope-fill"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="footer-heading">Navigation</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="/">Start</a></li>
                        <li><a href="/services.php">Leistungen</a></li>
                        <li><a href="/pricing.php">Preise</a></li>
                        <li><a href="/gallery.php">Galerie</a></li>
                        <li><a href="/request.php">Anfrage</a></li>
                        <li><a href="/contact.php">Kontakt</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="footer-heading">Leistungen</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="/services.php">STL-Dateien drucken</a></li>
                        <li><a href="/services.php">Funktionsteile</a></li>
                        <li><a href="/services.php">Ersatzteile</a></li>
                        <li><a href="/services.php">Prototypen</a></li>
                        <li><a href="/services.php">Expressservice</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="footer-heading">Kontakt</h6>
                    <ul class="list-unstyled footer-links">
                        <li>
                            <a href="mailto:info@3ddruck-suedtirol.it">
                                <i class="bi bi-envelope me-2"></i>info@3ddruck-suedtirol.it
                            </a>
                        </li>
                        <li class="mt-2 text-muted small">
                            <i class="bi bi-clock me-2"></i>Mo–Fr: 09:00 – 18:00 Uhr
                        </li>
                        <li class="mt-1 text-muted small">
                            <i class="bi bi-geo-alt me-2"></i>Südtirol, Italien
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom py-3">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="small text-muted mb-0">&copy; <?= date('Y') ?> 3D Druck Südtirol. Alle Rechte vorbehalten.</p>
            <ul class="list-inline mb-0 small">
                <li class="list-inline-item"><a href="/impressum.php" class="text-muted">Impressum</a></li>
                <li class="list-inline-item text-muted mx-1">·</li>
                <li class="list-inline-item"><a href="/datenschutz.php" class="text-muted">Datenschutz</a></li>
                <li class="list-inline-item text-muted mx-1">·</li>
                <li class="list-inline-item"><a href="/agb.php" class="text-muted">AGB</a></li>
                <li class="list-inline-item text-muted mx-1">·</li>
                <li class="list-inline-item"><a href="/login.php" class="text-muted">Admin</a></li>
            </ul>
        </div>
    </div>
</footer>
<!-- /Footer -->

<!-- Theme toggle button -->
<button class="theme-toggle" id="themeToggle" aria-label="Theme wechseln" title="Theme wechseln">
    <i class="bi bi-sun-fill" id="themeIcon"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="/assets/js/main.js"></script>

<!-- Cookie Consent Banner -->
<div id="cookieBanner" class="cookie-banner" role="dialog" aria-label="Cookie-Hinweis" aria-live="polite" hidden>
    <div class="cookie-banner-inner">
        <div class="cookie-text">
            <i class="bi bi-shield-check me-2 text-accent"></i>
            <span>
                Diese Website verwendet ausschließlich technisch notwendige Cookies für Formularsicherheit (CSRF-Schutz).
                Kein Tracking, keine Werbung.
                <a href="/datenschutz.php" class="cookie-link">Datenschutz</a>
            </span>
        </div>
        <div class="cookie-actions">
            <button id="cookieAccept" class="btn btn-primary-custom btn-sm">
                <i class="bi bi-check-lg me-1"></i>Verstanden
            </button>
        </div>
    </div>
</div>
</body>
</html>
