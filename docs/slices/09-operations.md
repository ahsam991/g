# Slice 09: Operations

**Tables:** action_notes, appointments, staff_availabilities, communications, messages, notifications, notification_logs, sla_policies, sla_breaches, email_logs

## Build
- Appointments with calendar views (day/week/month/list), consultant availability, public slot booking from availability, meeting link field.
- Messages: threads student↔consultant, internal flag hidden from the student, unread counts.
- Communications log (phone/email/WhatsApp/meeting) with outcome and next action.
- Laravel database notifications + bell dropdown; all mail queued, logged in email_logs.
- Scheduler: tasks:remind 08:00, appointments:remind hourly (24h and 1h, uses reminder_sent_at), sla:check hourly, leads:archive-lost weekly.
- SLA defaults: new lead contact 2h, new application review 1 day, missing doc follow-up 2 days.

## Done when
Reminder commands dispatch queued mail (Mail::fake); booking cannot double-book a slot; candidate never sees internal messages.
