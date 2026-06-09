/* =============================================
   3D Druck Südtirol — Main JS
   ============================================= */

(function () {
    'use strict';

    // ── Navbar scroll effect ──────────────────
    const nav = document.getElementById('mainNav');
    if (nav) {
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 20);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // ── Active nav link highlight ─────────────
    (function () {
        const path = window.location.pathname.replace(/\/$/, '') || '/index.php';
        document.querySelectorAll('#mainNav .nav-link').forEach(link => {
            const href = link.getAttribute('href')?.replace(/\/$/, '');
            if (!href) return;
            const match = href === '/' ? (path === '' || path === '/' || path === '/index.php') : path.startsWith(href);
            if (match) link.classList.add('active');
        });
    })();

    // ── Smooth back-to-top ────────────────────
    const btt = document.getElementById('backToTop');
    if (btt) {
        window.addEventListener('scroll', () => btt.classList.toggle('show', window.scrollY > 400), { passive: true });
        btt.addEventListener('click', (e) => { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });
    }

    // ── Theme toggle ──────────────────────────
    const html        = document.documentElement;
    const toggleBtn   = document.getElementById('themeToggle');
    const themeIcon   = document.getElementById('themeIcon');

    function applyTheme(theme) {
        html.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);
        if (themeIcon) {
            themeIcon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        }
    }

    const savedTheme = localStorage.getItem('theme') ||
        (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
    applyTheme(savedTheme);

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            applyTheme(html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
        });
    }

    // ── Scroll-reveal animations ──────────────
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));
    } else {
        document.querySelectorAll('.fade-in-up').forEach(el => el.classList.add('visible'));
    }

    // ── Gallery filter ────────────────────────
    const filterBtns  = document.querySelectorAll('.btn-filter');
    const galleryItems = document.querySelectorAll('.gallery-item');

    if (filterBtns.length && galleryItems.length) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const cat = btn.dataset.filter;
                galleryItems.forEach(item => {
                    const show = cat === 'all' || item.dataset.category === cat;
                    item.style.display = show ? '' : 'none';
                    if (show) {
                        item.style.animation = 'none';
                        item.offsetHeight; // reflow
                        item.style.animation = '';
                    }
                });
            });
        });
    }

    // ── File drop area ────────────────────────
    const dropArea = document.querySelector('.file-drop-area');
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.getElementById('fileNameDisplay');

    if (dropArea && fileInput) {
        ['dragenter', 'dragover'].forEach(evt => {
            dropArea.addEventListener(evt, (e) => {
                e.preventDefault();
                dropArea.classList.add('drag-over');
            });
        });
        ['dragleave', 'drop'].forEach(evt => {
            dropArea.addEventListener(evt, () => dropArea.classList.remove('drag-over'));
        });
        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            const files = e.dataTransfer?.files;
            if (files?.length) {
                fileInput.files = files;
                updateFileName(files[0]);
            }
        });
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) updateFileName(fileInput.files[0]);
        });
    }

    function updateFileName(file) {
        if (!fileNameDisplay) return;
        const mb = (file.size / 1024 / 1024).toFixed(2);
        fileNameDisplay.textContent = `${file.name} (${mb} MB)`;
        fileNameDisplay.style.display = 'block';
    }

    // ── Form validation & submit spinner ─────
    const requestForm = document.getElementById('requestForm');
    if (requestForm) {
        requestForm.addEventListener('submit', function (e) {
            if (!requestForm.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                requestForm.classList.add('was-validated');
                return;
            }
            requestForm.classList.add('was-validated');
            const btn = requestForm.querySelector('.btn-submit');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Wird gesendet...';
            }
        });
    }

    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            if (!contactForm.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            contactForm.classList.add('was-validated');
        });
    }

    // ── Stagger fade-in-up children ──────────
    document.querySelectorAll('[data-stagger]').forEach(container => {
        Array.from(container.children).forEach((child, i) => {
            child.classList.add('fade-in-up');
            child.style.transitionDelay = `${i * 80}ms`;
        });
    });

    // ── Cookie Consent Banner ─────────────────
    (function () {
        const banner = document.getElementById('cookieBanner');
        const btn    = document.getElementById('cookieAccept');
        if (!banner || !btn) return;

        if (!localStorage.getItem('cookie_consent')) {
            setTimeout(() => { banner.hidden = false; }, 600);
        }

        btn.addEventListener('click', function () {
            localStorage.setItem('cookie_consent', '1');
            banner.style.transition = 'opacity 0.3s, transform 0.3s';
            banner.style.opacity = '0';
            banner.style.transform = 'translateX(-50%) translateY(16px)';
            setTimeout(() => { banner.hidden = true; }, 300);
        });
    }());

})();
