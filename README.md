# Global Consultancy Education

**[gconsultancy.co.uk](https://www.gconsultancy.co.uk)** — Study abroad consultancy CRM and public website built with Laravel 12.

## What is this?

A complete system for Global Consultancy Education, replacing WordPress + Excel with one Laravel application:

- **Public website** — Marketing pages with animated hero, journey rail, destination cards, service listings, contact form, and WhatsApp integration
- **CRM** — Admin and consultant dashboard for managing leads, students, documents, applications, and the full study-abroad pipeline
- **Student portal** — Self-service portal for students to track their application status

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+, Laravel 12, MySQL 8 |
| Frontend | Blade, Tailwind CSS 3.4, Alpine.js 3 |
| Build | Vite (assets committed for Hostinger) |
| Auth | Laravel Breeze, TOTP 2FA, RBAC with 38 permissions |
| Hosting | Hostinger shared (no Redis/Docker/Node on server) |

## Roles

- **Admin** — Full access to everything
- **Consultant** — Manages assigned leads and students only
- **Student** — Views own data through the portal

## Features Built

- [x] Authentication with lockout, 2FA, forced password change
- [x] Role-based access control (3 roles, 38 permissions)
- [x] Admin panel (users, roles, branches, settings, audit log)
- [x] Student (candidate) management with Excel import/export
- [x] Lead pipeline with auto-assignment and conversion to student
- [x] Duplicate detection (email, phone, passport)
- [x] Profile completeness scoring
- [x] Responsive public website with scroll animations
- [x] Contact form with honeypot and auto-lead creation
- [x] WhatsApp floating button

## Local Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

The seeder creates an admin account and prints the password once.

## Testing

```bash
php artisan test          # 152 tests, 415 assertions
./vendor/bin/pint         # Code formatting
```

## Deploy to Hostinger

See `docs/HOSTING-HOSTINGER.md` for the full deploy checklist. Key points:

- Document root must point at `public/`
- Run `composer install --no-dev --optimize-autoloader`
- Built assets (`public/build/`) are committed — no npm on server
- Queue driver: `database`, Cache: `file`, Session: `database`

## Project Docs

- `docs/PROGRESS.md` — Build progress tracker
- `docs/slices/*.md` — Spec for each build slice
- `docs/DESIGN-LANDING.md` — Landing page design brief
- `docs/HOSTING-HOSTINGER.md` — Hostinger deployment guide
