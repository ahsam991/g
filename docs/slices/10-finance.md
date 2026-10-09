# Slice 10: Finance

**Tables:** commission_rules, commissions, referral_partners, referral_payments, invoices, invoice_items, payments, student_payments

## Build
- Commission rules: percentage, fixed, tiered (tier_config JSON, e.g. students 1-5 at 15%, 6+ at 20%), scoped by university/country/intake with effective dates.
- `CommissionCalculator` service, pure and unit tested.
- Commission statuses per spec; clawback when withdrawn within `finance.clawback_months` (default 6), also marks invoice CLAWED_BACK.
- Referral share auto-calculated (e.g. £2000 × 20% = £400).
- Invoices INV-YYYY-0000, items, DomPDF PDF with logo and both office addresses.
- Payments and student payments; never store card numbers.
- Multi-currency: GBP default, store original + GBP amount.
- Finance routes: admin full access. A consultant sees only their own commission lines (read-only, no invoices, no payments, no rules). Students get 403 everywhere.

## Done when
Unit tests for tiers and clawback; invoice PDF renders; a consultant sees only their own commission rows and gets 403 on invoices, payments and rules; a student gets 403 on all finance routes.
