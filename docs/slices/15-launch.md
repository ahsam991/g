# Slice 15: Hardening and launch

**Tables:** none

## Build
- Security headers middleware, .env and storage return 404/403, rate limits on login and public forms.
- Full IDOR sweep, N+1 check (preventLazyLoading in non-prod), load test with 1000 candidates.
- Deploy per spec §10 to Hostinger: document root public_html/crm/public, cron schedule:run, SSL, SMTP test.
- Handover docs per spec §13: README, DEPLOYMENT, ARCHITECTURE, DATABASE_ERD (Mermaid), RBAC matrix, WORKFLOW, DESIGN_SYSTEM.

## Done when
Every box in spec §17 ticked; tag v1.0.
