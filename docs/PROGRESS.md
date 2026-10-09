# Build Progress

Agents: read this file first. Work the first slice that is not `done`. Update its row and the log when you finish.

| Slice | File | Status | Note |
|---|---|---|---|
| 00 | docs/slices/00-setup.md | done | Laravel 12, not 11 — see log |
| 01 | docs/slices/01-auth-rbac.md | done | |
| 02 | docs/slices/02-admin-shell.md | done | |
| 03 | docs/slices/03-leads.md | todo | next |
| 04 | docs/slices/04-candidates.md | todo |
| 05 | docs/slices/05-documents.md | todo |
| 06 | docs/slices/06-catalogue.md | todo |
| 07 | docs/slices/07-applications.md | todo |
| 08 | docs/slices/08-journey.md | todo |
| 09 | docs/slices/09-operations.md | todo |
| 10 | docs/slices/10-finance.md | todo |
| 11 | docs/slices/11-portal.md | todo |
| 12 | docs/slices/12-public-site.md | todo |
| 13 | docs/slices/13-reports.md | todo |
| 14 | docs/slices/14-gdpr-ops.md | todo |
| 15 | docs/slices/15-launch.md | todo |

Roles are admin / consultant / student (3). Landing page brief: `docs/DESIGN-LANDING.md`. Hosting limits: `docs/HOSTING-HOSTINGER.md`.

Status values: todo, in-progress, blocked (reason in log), done.

## Log
<!-- One line per session: date, slice, what changed, anything the next session must know. Keep it short. -->
- 2026-10-09 slice 00 done. Toolchain installed to home dir (PHP 8.3.12, Composer, Node 22, MySQL 8.4 on port 3307) — see docs/DEV-ENVIRONMENT.md. **Laravel 12.69.3 instead of 11**: Laravel 11 has 4 unpatched advisories incl. one high (CRLF injection in the default email validation rule); 12 requires the same PHP 8.2, so Hostinger is unaffected and the spec's structure is unchanged. `composer audit` is clean. Breeze (Blade) + google2fa + excel + dompdf installed. Brand tokens in tailwind.config.js, fonts via <link> not CSS @import. 28 tests pass on MySQL, pint clean, npm run build works.
- 2026-10-09 slice 01 done. 3 roles + 38 permissions (App\Support\Permissions is the catalogue). Gate::before for admin, Gate::after maps every slug to a gate. `permission:` and `role:` middleware. ScopedToUser trait for record visibility (used by every later model). Lockout 5/15min, login_histories, audit_logs with secret redaction, TOTP 2FA + 8 single-use hashed recovery codes, forced password change. Model::preventLazyLoading on in dev. Test helper assertForbiddenFor() in tests/TestCase.php. 64 tests pass.
- 2026-10-09 slice 02 done. Settings service (cached, typed, flushed on write) + settings() helper; 30 settings seeded with real company data. Uid service with locked counter rows (GC-/APP-/INV-, restarts yearly). CRM layout at components/layouts/crm.blade.php (navy sidebar, blurred topbar, permission-aware nav). Components: stat-card, status-badge, data-table, empty-state, page-header, crm.nav-link. Admin CRUD for users, roles (permission grid), branches, settings, audit log. noindex middleware on every signed-in route. NOTE: pint rewrites FQCNs to imports, so string patches against bootstrap/app.php must assert before replacing. 90 tests pass.
