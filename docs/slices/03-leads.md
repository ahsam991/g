# Slice 03: Leads and enquiries

**Tables:** lead_sources, lead_campaigns, leads, lead_activities, website_enquiries

## Build
- Public enquiry endpoint (contact, counselling, callback, course, university types): validation, honeypot, throttle 5/min/IP, store IP.
- Duplicate detection by normalised email/phone; flag, do not block.
- Enquiry → lead, auto-assign by round robin within branch, in-app notify + queued confirmation email.
- Lead list with filters (status, source, consultant, date), activity log, convert to candidate (copies data, links lead_id).
- Sources seeded: Facebook, Google, Instagram, Referral, Website, Walk-in, YouTube, LinkedIn.

## Done when
Feature test: public POST creates lead + queued mail; a consultant converts a lead to a student; a consultant cannot see another consultant's lead.
