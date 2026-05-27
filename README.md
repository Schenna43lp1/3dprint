# 3D Print Tracker

![Docker Build](https://github.com/Schenna43lp1/3dprint/actions/workflows/docker-image.yml/badge.svg)

Docker + PostgreSQL + PHP 3D Print Management MVP.

Features:
- Login System
- Dashboard
- Filament Inventory
- Druckjobs
- Automatischer Filamentverbrauch
- Maintenance Tracker
- Adminer
- Docker CI

Start:

```bash
docker compose up -d --build
```

Falls bereits Daten vorhanden sind:

```bash
docker compose down -v
docker compose up -d --build
```

Default Login:
- User: admin
- Passwort: admin123

Web:
- App: http://localhost:8080/login.php
- Adminer: http://localhost:8081
