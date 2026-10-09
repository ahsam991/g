# Slice 11: Student portal

**Tables:** saved_courses, candidate_notifications

## Build
- UI copy says "student" everywhere. Table and model names stay `candidates` / `Candidate` so the spec, ERD and `GC-YYYY-0000` UID format remain valid.
- Register with GDPR consent (consent_given, ip, policy_version stored in gdpr_consents) + email verification.
- Dashboard: greeting, completion ring, progress tracker Profile → Application → Offer → Deposit → CAS → Visa → Enrolment from real data, missing docs, applications, appointments, tasks, latest message.
- Profile wizard (reuses slice 04 components), documents upload/status/requests, application timelines, offer accept/decline, booking, messages, notifications, GDPR export/deletion request.
- Mobile bottom nav at 375px.

## Done when
Student IDOR suite: a student cannot open another student's application, document or message, and cannot reach any CRM route.
