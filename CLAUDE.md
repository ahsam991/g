# Global Consultancy — CLAUDE.md

Laravel 11 system for Global Consultancy Education: public site, CRM, and candidate portal sharing one MySQL database, hosted on Hostinger shared/cloud hosting.

- Spec: `PRODUCTION-PROJECT-PLAN-GLOBAL-CONSULTANCY.md`
- Start every build session with `docs/PROGRESS.md`, then the one file in `docs/slices/` for the current slice. Use the `build-next` skill.
- Never load the full spec. Grep it for a keyword if a detail is missing.

## Stack
PHP 8.2 target (8.3 local), Laravel 12, MySQL 8, Blade, Tailwind 3.4, Alpine 3, Vite, Breeze, DomPDF, maatwebsite/excel, pragmarx/google2fa.
Public-site glass effects: vendored liquid-glass-js in `resources/vendor/liquid-glass/`; usage rules in the `liquid-glass` skill (`.claude/skills/liquid-glass/SKILL.md`).

## Roles (3)
`admin` (everything), `consultant` (their assigned students only), `student` (own data only).
Permissions live in the `permissions` table, so a manager tier can be added later without code changes.
Admin-only: settings, users and roles, finance, GDPR tools, backups, reversing a document rejection.

## MVC structure
```
app/Models            Eloquent model per table + factory
app/Http/Controllers  Thin: authorize, validate, delegate, return a view
app/Http/Requests     All validation lives here
app/Policies          One per model; every controller action calls authorize()
app/Services          Business rules (CommissionCalculator, ApplicationStateMachine, Uid)
app/Console/Commands  The six scheduled commands
resources/views       layouts/{public,crm,portal}, components/, then one folder per module
routes/web.php        Three groups: public, crm (auth + permission), portal
database/migrations   One table per file, index every foreign key
tests/Feature         One file per module, plus the IDOR suite
```
Controllers contain no business logic and no raw queries. Anything used twice becomes a service.

## Reference docs
- Landing page design: `docs/DESIGN-LANDING.md` (read for slice 12)
- Hosting and storage limits: `docs/HOSTING-HOSTINGER.md` (read for slices 05, 14, 15)

## Hostinger constraints
- No Redis, Node, Docker, WebSockets or Supervisor in production.
- Queue `database`, cache `file`, session `database`.
- Scheduler via one cron: `schedule:run` every minute.
- Built assets (`public/build`) are committed.

## Rules for every change
- Every route behind `auth` plus a permission check. Every controller action calls a policy.
- Staff and managers see records through query scopes, never by unscoped `find()`.
- Lists paginate at 20 with eager loading. No N+1.
- Dashboard numbers are real queries. No hard-coded stats, no Lorem Ipsum, no TODO.
- Candidate files live on the private disk and are served only through a controller with policy and access log.
- Never log passwords or secrets. No secrets in JS.
- Each slice ships feature tests plus IDOR/policy tests.

## Commands
Local toolchain lives in the home directory; see `docs/DEV-ENVIRONMENT.md` for PATH and how to start MySQL on port 3307.

```
composer test        # php artisan test
./vendor/bin/pint    # format
npm run dev | build
php artisan migrate:fresh --seed
```

## graphify

This project has a knowledge graph at graphify-out/ with god nodes, community structure, and cross-file relationships.

Rules:
- For codebase questions, first run `graphify query "<question>"` when graphify-out/graph.json exists. Use `graphify path "<A>" "<B>"` for relationships and `graphify explain "<concept>"` for focused concepts. These return a scoped subgraph, usually much smaller than GRAPH_REPORT.md or raw grep output.
- If graphify-out/wiki/index.md exists, use it for broad navigation instead of raw source browsing.
- Read graphify-out/GRAPH_REPORT.md only for broad architecture review or when query/path/explain do not surface enough context.
- After modifying code, run `graphify update .` to keep the graph current (AST-only, no API cost).
