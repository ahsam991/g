# Global Consultancy — Build Plan

Spec (reference only, do not load whole): `PRODUCTION-PROJECT-PLAN-GLOBAL-CONSULTANCY.md`.
Status: `docs/PROGRESS.md`. Each step: one file in `docs/slices/`.
Run a step with `/build-next` (or `/build-next 04`).
Roles: admin, consultant, student. Landing design: `docs/DESIGN-LANDING.md`. Hosting: `docs/HOSTING-HOSTINGER.md`.

| Milestone | Slices | Weeks |
|---|---|---|
| M1 Foundation | 00 setup, 01 auth + RBAC, 02 admin shell | 1–2 |
| M2 Core CRM | 03 leads, 04 candidates, 05 documents | 3–4 |
| M3 Pipeline | 06 catalogue, 07 applications, 08 journey | 5–7 |
| M4 Ops + money | 09 operations, 10 finance | 8–9 |
| M5 Front ends | 11 candidate portal, 12 public site | 9–10 |
| M6 Launch | 13 reports, 14 GDPR/backup/health, 15 hardening + deploy | 11–12 |

At the end of each milestone, deploy to the Hostinger staging subdomain and smoke test.

Phase 2 (after launch): workflow builder, custom fields, automation engine, candidate merge, search_index, WhatsApp/SMS, recurring appointments, Stripe, Bengali, chatbot.
