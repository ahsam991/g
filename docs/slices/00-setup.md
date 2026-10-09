# Slice 00: Project setup and toolchain

**Tables:** none

## Build
- Install toolchain if missing (check with `command -v`): PHP 8.3 + Composer via `/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.3)"`, Node LTS via the nodejs.org installer or `fnm`, MySQL 8 via DBngin (GUI) or Homebrew. Ask the user before any install that needs sudo.
- `composer create-project laravel/laravel:^11 .` into a temp dir, move into repo root without overwriting docs/, .claude/, CLAUDE.md.
- `composer config platform.php 8.2.0` so dependencies stay Hostinger-compatible.
- Breeze (Blade stack), Tailwind 3.4, Alpine 3, Inter + Instrument Serif fonts.
- tailwind.config.js tokens: navy #0B2C5C, orange #FF8C00, bg #F8FAFC, ink #0F172A, muted #64748B, border #E2E8F0, success #10B981, warning #F59E0B, danger #EF4444, radius 12px.
- .env.example: DB_CONNECTION=mysql, QUEUE_CONNECTION=database, SESSION_DRIVER=database, CACHE_STORE=file. Tests use a separate MySQL DB `gconsultancy_test` (phpunit.xml).
- `git init`, .gitignore (vendor, node_modules, .env, storage/app/private, storage/logs). Do NOT ignore public/build.
- GitHub Actions: MySQL 8 service, `composer install`, `php artisan test`, `vendor/bin/pint --test`, `npm ci && npm run build`.
- `composer.json` script `test` → `php artisan test --parallel` if paratest installed, else plain.

## Done when
`php artisan test` green on MySQL, `npm run build` works, home page renders with brand fonts and colours.
