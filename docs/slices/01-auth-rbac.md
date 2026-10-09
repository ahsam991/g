# Slice 01: Auth and RBAC

**Tables:** users (add phone, role_id, team_id, branch_id, status, last_login_at, locked_until, failed_logins, two_factor_secret, two_factor_recovery_codes), roles, permissions, role_permissions, login_histories, sessions, audit_logs

## Build
- Roles: **admin, consultant, student** (3 roles). A `manager` tier is deliberately NOT built; permissions are table-driven so it can be added later without refactoring. Permissions seeded from the matrix in spec §6, collapsed to 3 columns (candidates.view, candidates.assign, applications.status.change, documents.verify, finance.*, settings.*, gdpr.*, backup.*, reports.view ...).
- `User::hasPermission()`, Gate::before for admin, `permission:` route middleware.
- Visibility scope trait `ScopedToUser` with `scopeVisibleTo(User $u)`: admin = all, consultant = assigned to them, student = own only. Every later model holding student data uses it.
- Login: 5 failures → lock 15 min, login_histories (ip, user agent, success). Admin can list and kill sessions.
- TOTP 2FA with pragmarx/google2fa + 8 recovery codes (hashed). Optional per user, required for admin in production.
- `Auditable` trait: logs create/update/delete with before/after JSON, ip, user agent; strips password, tokens, secrets.
- Consultants CAN verify documents and change application status. Admin-only: settings, users/roles, finance, GDPR tools, backups, reversing a document rejection.
- Seeder: roles, permissions, admin `admin@gconsultancy.co.uk` with random password printed once and `must_change_password=true`.

## Done when
Tests: lockout, 2FA, audit log hides password, consultant hitting an admin route = 403. IDOR test helper `assertForbiddenFor($user, $url)` exists for later slices.
