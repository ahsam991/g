# Slice 13: Dashboards and reports

**Tables:** none new (read models only)

## Build
- Role-aware dashboard: admin sees org-wide, consultant sees only their assigned students (spec §2 Dashboard). Each metric is a method on a `Metrics` service with a test.
- Reports: students, applications, universities, visas, enrolments, finance, consultant performance, deadlines, SLA, GDPR, funnel, marketing by source. Filters by date/consultant/country/university. Finance and GDPR reports are admin-only.
- Excel + PDF export.
- Global search across candidate uid/email/phone/passport, application id, invoice number using indexed columns.

## Done when
Each metric has a test with known fixtures; no hard-coded numbers in Blade.
