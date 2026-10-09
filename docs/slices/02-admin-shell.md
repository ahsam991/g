# Slice 02: Admin shell, settings, layouts

**Tables:** teams, branches, settings, email_templates

## Build
- CRUD: users, roles (3-column permission grid), branches. Admin only.
- `teams` and `branches` tables are created now but teams CRUD is deferred to Phase 2; branches power the London / Khulna offices.
- Settings key/value with groups (company, crm, mail, finance, gdpr, sla), cached via `Settings::get()`, cache busted on save.
- UID service: `Uid::next('candidate')` → GC-2026-0001; APP-, INV- prefixes. Uses a locked counter row, safe under concurrency.
- Layouts: `layouts/crm` (navy sidebar 280px, sticky blurred topbar), `layouts/portal` (mobile bottom nav), `layouts/public`.
- Blade components: stat-card, data-table (search, sort, pagination 20), status-badge, modal, empty-state, toast, timeline.
- Email templates with `{{candidate_name}}`-style variables and preview.
- CRM layout sends `X-Robots-Tag: noindex, nofollow`.

## Done when
Admin creates a consultant who logs in and sees the CRM shell only; settings change is reflected without deploy.
