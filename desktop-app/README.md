# 3D Druck Südtirol – Desktop App

JavaFX desktop companion für das Admin-Dashboard.

## Voraussetzungen

- Java 17+
- Maven 3.8+

## Starten

```bash
cd desktop-app
mvn javafx:run
```

## Build (ausführbares JAR)

```bash
mvn package
java -jar target/druck3d-desktop-1.0.0.jar
```

## Einrichtung

1. App starten → Einstellungen öffnen (⚙-Button)
2. **Server-URL** eintragen: `http://DEINE-IP:8180` (Staging) oder `http://DEINE-DOMAIN` (Produktion)
3. **API-Key** eintragen (aus `includes/config.php` → `ADMIN_API_KEY`)

### API-Key generieren

```bash
php -r "echo bin2hex(random_bytes(32));"
```

Den generierten Key in `3ddruck-suedtirol/includes/config.php` bei `ADMIN_API_KEY` eintragen.

## Features

- 📋 **Anfragen-Dashboard** – alle Bestellungen in Echtzeit
- 🔄 **Status-Änderung** per Dropdown direkt in der Tabelle
- 💰 **Angebot senden** per Rechtsklick → Dialog
- 🧾 **Rechnung senden** per Rechtsklick → Dialog
- 🚚 **Versand markieren** mit Tracking-Nummer
- 📊 **Besucher-Statistiken** mit 14-Tage-Chart
- 🔔 **Desktop-Benachrichtigungen** bei neuer Anfrage (System Tray)
- ⚙ **Auto-Polling** alle 30 Sekunden (einstellbar)
