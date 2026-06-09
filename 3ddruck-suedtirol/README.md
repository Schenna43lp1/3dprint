# 3D Druck Südtirol — Website

Professional website for a local 3D printing service in South Tyrol, Italy.

## Tech Stack

- PHP 8+
- HTML5 / CSS3
- Bootstrap 5.3
- Bootstrap Icons
- Vanilla JavaScript
- Apache / `.htaccess`

## Project Structure

```
3ddruck-suedtirol/
├── index.php           Homepage
├── services.php        Services page
├── pricing.php         Pricing page
├── gallery.php         Gallery page
├── request.php         Request form (main conversion page)
├── contact.php         Contact page
├── send_request.php    Form backend — file upload + email + request log
├── send_contact.php    Contact form backend
├── login.php           Admin login
├── logout.php          Admin logout
├── dashboard.php       Admin dashboard — list/manage requests
├── download.php        Admin-only download of uploaded files
├── impressum.php       Legal notice (placeholder)
├── datenschutz.php     Privacy policy (placeholder)
├── .htaccess           Apache config (security, caching, headers)
├── includes/
│   ├── config.php      Constants, helpers, session start
│   ├── header.php      HTML head + navbar
│   └── footer.php      Footer + scripts
├── assets/
│   ├── css/style.css   All custom styles
│   ├── js/main.js      Navbar scroll, theme toggle, gallery filter, forms
│   └── img/
│       └── favicon.svg
└── uploads/
    └── .htaccess       Blocks direct access to uploaded files
```

## Admin-Bereich

- Login unter `/login.php` (auch als „Admin"-Link im Footer)
- Nach dem Login: Dashboard unter `/dashboard.php` mit allen eingegangenen Druckanfragen
- **Standard-Zugang:** Benutzer `admin`, Passwort `3ddruck-admin` — **unbedingt ändern!**
- Neuen Passwort-Hash erzeugen und in `includes/config.php` (`ADMIN_PASSWORD_HASH`) eintragen:
  ```bash
  php -r "echo password_hash('dein-neues-passwort', PASSWORD_DEFAULT);"
  ```
- Anfragen werden in `uploads/requests.json` protokolliert (per `.htaccess` vor direktem Web-Zugriff geschützt)
- Brute-Force-Schutz: nach 5 Fehlversuchen 5 Minuten gesperrt

## Setup

### Requirements

- Apache 2.4+ with `mod_rewrite`, `mod_headers`, `mod_expires`, `mod_deflate`
- PHP 8.0+
- `mail()` configured (Sendmail / SMTP relay)

### Steps

1. Upload the `3ddruck-suedtirol/` folder to your web root (e.g. `/var/www/html/`)
2. Ensure `uploads/` is writable by the web server:
   ```bash
   chmod 750 uploads/
   chown www-data:www-data uploads/
   ```
3. Edit `includes/config.php`:
   - Set `SITE_EMAIL` to your real email address
   - Set `SITE_URL` to your domain
4. Fill in `impressum.php` and `datenschutz.php` with real legal information
5. Enable HTTPS in `.htaccess` (uncomment the redirect block)
6. Test the upload form and verify emails are received

### PHP `php.ini` / `.htaccess` settings

The `.htaccess` already sets:
```
upload_max_filesize = 52M
post_max_size = 55M
max_execution_time = 60
```

Verify these are applied or set them in `php.ini` directly.

## Security

- CSRF token on all POST forms
- Honeypot anti-spam field
- Session-based rate limiting (1 request/minute)
- File upload: extension whitelist + MIME check + double-extension check
- Random upload filenames
- `uploads/.htaccess` blocks direct file access and PHP execution
- Security headers via `.htaccess` (X-Frame-Options, X-Content-Type-Options, etc.)
- Input sanitization on all fields

## Before Going Live

- [ ] Fill in Impressum with real data
- [ ] Write proper Datenschutzerklärung (consult a lawyer)
- [ ] Enable HTTPS redirect in `.htaccess`
- [ ] Configure a proper mail server / SMTP relay
- [ ] Replace gallery placeholders with real photos
- [ ] Set correct `SITE_EMAIL` in `config.php`
- [ ] Test all forms end-to-end
- [ ] Add Google Maps embed (after cookie consent integration)
- [ ] Consider adding a cookie consent banner for GDPR compliance

## License

Private / Commercial — All rights reserved.
