# Slice 07: Applications pipeline

**Tables:** applications, application_statuses, application_status_history, application_documents, application_notes, tasks

## Build
- Statuses (spec §2): DRAFT → PROFILE_CHECK → DOCUMENT_PENDING → READY_TO_APPLY → SUBMITTED → ACKNOWLEDGED → UNDER_REVIEW → INTERVIEW_REQUIRED → CONDITIONAL_OFFER → UNCONDITIONAL_OFFER → DEPOSIT_REQUIRED → DEPOSIT_PAID → CAS_REQUESTED → CAS_ISSUED → VISA_PREPARATION → VISA_APPLIED → VISA_APPROVED → ENROLLED → CLOSED, plus REJECTED / WITHDRAWN / VISA_REFUSED.
- One `ApplicationStateMachine` class holds allowed transitions; reason required for REJECTED, WITHDRAWN, VISA_REFUSED. Consultants get a limited transition set (no CLOSED, no reversals); students none.
- Every change writes application_status_history and fires `ApplicationStatusChanged` event (later used by notifications).
- Detail page tabs: Overview, Documents, Timeline, Notes, Tasks (later slices add Offer, Deposit, CAS, Visa, Enrolment, Finance tabs).
- Notes: internal or candidate-visible, @mention notifies user.
- Tasks: priority, due date, assignee, overdue flag.

## Done when
Invalid transition throws and is tested; history row per change; @mention creates notification.
