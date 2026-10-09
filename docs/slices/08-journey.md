# Slice 08: Student journey

**Tables:** offers, offer_conditions, deposits, cas_records, visa_cases, enrolments, student_support

## Build
- Offer conditions with status required/submitted/verified/completed; when all completed, offer becomes unconditional and application moves to UNCONDITIONAL_OFFER.
- Deposits (amount, currency, due, paid), CAS (number, requested/issued dates), visa cases (type, biometrics, interview, decision, country checklist JSON, UK first), enrolments.
- student_support holds accommodation, pre-departure checklist with progress %, arrival (BRP/eVisa), with a `type` column.
- Each record syncs application status through the state machine, never directly.

## Done when
Completing the last condition promotes offer and application (tested).
