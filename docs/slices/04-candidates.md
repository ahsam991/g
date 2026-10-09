# Slice 04: Candidates

**Tables:** candidates, candidate_addresses, emergency_contacts, sponsors, candidate_assignments, counselling_sessions, gdpr_consents (+ qualifications, english_tests as child tables of candidates)

## Build
- CRUD with `visibleTo` scope and CandidatePolicy. Search on uid, name, email, phone, passport (indexed columns, no LIKE '%x%' on large text).
- 6-step wizard with draft save. Completion %: personal 20, contact 15, academic 20, english 10, documents 35.
- Fuzzy duplicate warning (same email, phone, or passport; similar name + DOB).
- Bulk assign / status / archive / restore. Soft deletes.
- Excel import tolerant of legacy headers (alias map), row-level error report; export current filter.
- Factory + seeder for 1000 candidates (local only).

## Done when
List of 1000 candidates loads < 3s with 20 per page and no N+1 (assert query count); Consultant A → Consultant B's student = 403.
