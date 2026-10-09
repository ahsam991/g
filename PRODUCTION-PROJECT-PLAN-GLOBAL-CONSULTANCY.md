# GLOBAL CONSULTANCY EDUCATION - PRODUCTION PROJECT PLAN
## Complete Production-Ready Plan - PHP + MySQL (phpMyAdmin) + Hostinger
## Version: 1.0 | Date: 2026-10-08 | Status: APPROVED FOR DEVELOPMENT


> **Revision 1.1 (2026-10-09):** Admin email unified to admin@gconsultancy.co.uk. Seeder no longer ships a fixed password. Local dev uses MySQL 8, not SQLite. Duplicate table `courses_saved` removed (use `saved_courses`); `sessions` and `failed_jobs` are Laravel defaults, so the table count is approximate, not a fixed 100. Test target changed from "33 tests" to "every slice ships feature + policy tests". Moved to Phase 2 to protect launch: workflow builder, custom fields, automation rules engine, candidate merge tool, `search_index`. Build order lives in `docs/BUILD-PLAN.md`.

---

### 1. PROJECT OVERVIEW

**Client:** Global Consultancy Education (G Consultancy / GC CONSULTANCY LIMITED 02773896)
**Domain:** gconsultancy.co.uk + crm.gconsultancy.co.uk
**Hosting:** Hostinger Web/Cloud Hosting (hPanel + phpMyAdmin + MySQL 8 + PHP 8.2)
**Type:** 3-in-1 Production System - Public Website + CRM (Admin Arm) + Candidate Portal (Student Arm)
**Goal:** Replace current WordPress + manual Excel process with a perfect PHP system that stores 100% data in phpMyAdmin MySQL and runs 100% on Hostinger without external server.

**Business Problem:**
- Current site: gconsultancy.co.uk static, no CRM integration, leads lost via email
- Manual tracking in Excel, no pipeline, no commission tracking, no document expiry, no SLA
- No candidate portal, students call for status
- No real reporting, no funnel, no 19K followers social proof leveraged

**Solution:**
- Build Laravel 11 PHP CRM + Public Website + Candidate Portal sharing SAME MySQL database visible in phpMyAdmin
- Real business data: Navy #0B2C5C + Orange #FF8C00, Tagline "Your Dream to Study Abroad - Just Click & Achieve It", USP 100% FREE Counselling + 19K followers + 100% proven VISA, Offices London Suite 3 LMC Whitechapel Road + Khulna House 52 Alingon R#5, Phones 07402993321 / 01744742686 / 01935017746, Email info@gconsultancy.co.uk, Destinations UK/USA/Canada/Australia/EU/Malaysia/Finland/Middle East 15+ countries, 5-step Dream→Admission, 12 services, Visa Success Stories format "🇬🇧 UK STUDENT VISA SUCCESS | অভিনন্দন TANIYA AZAD! 🎉"

---

### 2. SCOPE - WHAT'S IN / OUT

**IN SCOPE (Must Build - Production):**
- Auth: 4 roles Admin/Manager/Staff/Candidate, RBAC Policies/Gates/Middleware, TOTP 2FA + recovery codes, 5-fail lockout 15 mins, login history IP/device, admin session kill, candidate public registration GDPR consent
- Candidates: CRUD, search/filter (UID/Name/Email/Phone/Passport), import legacy-header tolerant Excel + export, duplicate warnings fuzzy, bulk assign/status/archive/restore, merge tool, 6-step wizard drafts + auto completion % 20+15+20+10+35
- Academic: Multiple qualifications, English tests multiple IELTS/TOEFL/PTE, emergency contacts, sponsorship, counselling sessions history, assignments
- Documents: Private storage storage/app/private/candidates/{id}/ via controller only + policy + audit log + access logs, versioning, verify/reject with reason + notify, per-application checklist % 7/10=70%, expiry tracking cron 30/7 days + expired + task, requests staff→candidate portal pending, preview, download secure
- Catalogue: Universities + contacts (Admissions/Regional/Finance/CAS) + campuses, Courses + requirements, Intakes, Subjects, Levels, Scholarships, Course-intake deadlines, Deadline dashboard, Media library, Public course finder filters Subject/Level/Country/University/Intake/Tuition/IELTS + compare 3 + eligibility checker GPA+IELTS+Budget + wishlist heart + shortlist staff, same DB public+CRM
- Applications: Full 22-status pipeline DRAFT→PROFILE_CHECK→DOCUMENT_PENDING→READY_TO_APPLY→SUBMITTED→ACKNOWLEDGED→UNDER_REVIEW→INTERVIEW_REQUIRED→CONDITIONAL_OFFER→UNCONDITIONAL_OFFER→DEPOSIT_REQUIRED→DEPOSIT_PAID→CAS_REQUESTED→CAS_ISSUED→VISA_PREPARATION→VISA_APPLIED→VISA_APPROVED→ENROLLED→CLOSED + REJECTED/WITHDRAWN/VISA_REFUSED/CLOSED, history + reason enforcement, tabs Overview/Candidate/Documents/Timeline/Notes/Tasks/Offer/Deposit/CAS/Visa/Enrolment/Commission/Invoice/Payments/Activity, timeline DB-driven vertical, fees, notes internal+candidate visible + @mention notify, document checklist per app
- Journey: Offers + conditions sub-table required/submitted/verified/completed deadline auto-promotes to unconditional when all done, Deposits required/paid/currency/due date, CAS number requested/issued UK, Visa cases type/date/biometrics/interview/decision/reference/result checklist json country-specific UK Passport CAS Bank TB etc, Enrolments, Accommodation provider/property/room/price/contract/check-in/out/booking status, Pre-Departure checklist Visa/Accommodation/Flight/Pickup/Insurance/Orientation/Documents/Travel/Emergency progress %, Arrival Post-Arrival BRP/eVisa, Sponsorship sponsor/relationship/income/funding/evidence
- Ops: Tasks priority/due/overdue auto, Action Notes NEW/IN_PROGRESS/AWAITING_FEEDBACK/COMPLETED/CANCELLED + notify multi + doc, Appointments UID candidate/staff/branch/date/start/end/type Counselling/Follow-up/Review/Visa/Pre-departure/Interview/Document/Location/URL Zoom/Meet/status SCHEDULED/CONFIRMED/COMPLETED/CANCELLED/NO_SHOW reminder 24h+1h cron reminder_sent_at, Calendar Day/Week/Month/List, Staff availabilities day_of_week/start/end/is_available/branch, Public slot booking based on availability + confirmation email + meeting link placeholder, Recurring + Blocked time, Messages threads candidate/staff is_internal read_at unread count chat, Communications log type Phone/Email/WhatsApp/SMS/Meeting/Note/Video channel subject summary outcome next action direction inbound/outbound, SLA policies New Lead 2h contact New Application 1 day review Missing Doc 2 days follow-up breach detection cron notify manager dashboard widget, Automation rules engine trigger_event status_changed/document_rejected/task_overdue trigger_status action_type create_task/notify_staff/email_candidate/set_followup action_config json logs automation_logs
- Finance: Commission rules percentage/fixed/tiered 0-5 15% 6+ 20% tier_config json university/country/intake specific effective_from/to, Commissions UID university/candidate/application/enrolment/intake/referral partner rate amount currency claim date expected payment received date status NOT_ELIGIBLE/READY_TO_CLAIM/IN_REVIEW/CLAIMED/PENDING_PAYMENT/PAID/REJECTED/CLAWED_BACK rejection reason clawback amount/reason/deadline, Tiered calc tested, Clawback logic withdraw within clawback_months 6 setting -> CLAWED_BACK + Invoice CLAWED_BACK cron/manual, Referral partners name/company/email/phone/country/commission share % type Agent/Sub-Agent/Marketing/Student Referral active, Referral payments commission/partner/share % share amount auto £2000*20%=£400 status pending/paid, Invoices number unique INV-2026-0001 auto university/candidate/application/commission date/due/subtotal/tax/total/currency/status DRAFT/SENT/PAID/OVERDUE/CANCELLED/CLAWED_BACK payment reference notes items desc qty rate amount, Invoice PDF DomPDF professional logo address terms, Payments candidate/application/invoice/commission type commission/tuition/deposit/application_fee/other amount currency payment date transaction ref method BANK_TRANSFER/CARD/CASH/OTHER receipt notes never store raw card, Student payments candidate/application amount currency purpose tuition/deposit/application fee/other status pending/paid/refunded payment date receipt, Multi-currency default GBP supported GBP/USD/CAD/AUD/EUR/BDT store tuition_fee + tuition_fee_gbp converted for reporting, Finance dashboards cards Ready to Claim/In Review/Claimed/Due/Received/Rejected/Clawed Back count+amount table, Revenue dashboard total claimed/received/outstanding/expected/clawed back invoices paid/pending/overdue revenue by university bar/country donut/staff/intake/month line, Reports export Excel/PDF, Visible in Application/Candidate detail
- Leads + Enquiries: Type contact/counselling/appointment/course/university/callback name/email/phone/country/subject/message course/university nullable preferred date status new/contacted/converted/closed assigned to source IP list+filter+convert to lead creates lead+candidate + assign staff + notes + duplicate detection email/phone, Lead→candidate conversion
- Notifications: In-app + queued email status/lead/document/verify, bell unread count dropdown mark read link relevant record, mail logs
- Dashboard: Role-aware Admin org-wide Manager team Staff own, Cards total candidates new active applications submitted conditional/unconditional offers deposits CAS visa applied/approved/refused enrolled commission due/claimed/received outstanding invoices overdue tasks today's appointments SLA breaches, Charts applications by month bar candidates by country/destination donut applications by university bar by status donut offer conversion funnel visa outcomes pie approved/refused/pending enrolment trends line staff performance table candidates handled applications submitted offers visas enrolments tasks completed commission trends line real queries no hard-coded
- Reports: Candidate reports new by period/source/destination/staff, Application by university/course/intake/staff/status, University reports, Visa reports, Enrolment by destination/university/intake, Finance commission due/claimed/received invoices outstanding, Staff reports, Deadline reports, SLA reports, GDPR reports, Funnel analytics visual 10000 Leads->4000 Candidates->2500 Applications->1500 Offers->1000 Deposits->850 Visas->800 Enrolments with conversion % filter by staff/country/university/date, Marketing analytics source Facebook/Google/Instagram/Referral/Website/Walk-in/YouTube/LinkedIn metrics leads conversions applications offers enrolments revenue per source, Export Excel+PDF, Global search candidate UID/email/phone/passport application ID university course invoice commission indexed queries not full scan
- Admin: Users name/email/phone/role/team/branch/manager/status/last login, Roles & Permissions CRUD assign permissions matrix view, Teams CRUD manager members, Branches/Offices name/code/address/city/country/phone/email/manager/active for future expansion Head Office Dhaka London, Settings groups company name/logo/favicon/address/phone/email/website/social, CRM candidate UID format GC-{YYYY}-{0000} application APP-{YYYY}-{0000} invoice INV-{YYYY}-{0000} timezone Europe/London currency GBP default + supported list pagination 20 upload limits 10MB, Workflow candidate statuses application statuses offer statuses visa statuses document statuses task statuses configurable CRUD, Email SMTP host port username password encryption, Finance default currency clawback months 6 commission rules, GDPR retention months 72 policy version consent required boolean, SLA policies, Automation enable/disable, Workflow builder admin create custom statuses define transitions e.g. Conditional Offer->Deposit Paid->CAS Requested define permission for each transition define automation for transition stored workflow_templates + status_transitions future-proof, Custom fields admin add custom fields without developer module candidate/application etc field name type text/textarea/select/multiselect/date/number/checkbox options json is required sort order active values custom_field_values, Email templates name slug subject body variables {{candidate_name}} {{application_id}} {{university_name}} {{course_name}} {{intake}} variables list active preview sample, Audit logs user action model type model ID before/after json IP user agent created at filters export never log passwords/secrets, Data access logs who accessed personal data when purpose SAR compliance, GDPR consents candidate_id consent_type given boolean IP policy_version created_at, SAR export one-click export all data candidate as ZIP JSON + docs list + applications Subject Access Request, Anonymization tool admin anonymize candidate replace PII ANONYMIZED_XXXX but keep application IDs + financial records audit never hard delete financial, Deletion requests GDPR requests table type export/anonymize/delete status requested_at completed_at requested_by, Data retention cron anonymize old candidates after retention months 72, Backup dashboard last backup DB backup manual trigger + download file backup status history restoration documentation cron daily 2am, System health dashboard PHP version Laravel version DB status storage usage disk usage failed jobs mail status last backup application errors queue status cron status, Queue/failed jobs monitoring jobs failed jobs retries queue status database queue compatible no Redis
- Candidate Portal: Register/login email verification, Dashboard welcome Hi Ahsam + completion circular 75% + Progress Tracker visual horizontal Profile done✓->Application current->Offer pending->Deposit->CAS->Visa->Enrolment connected line icons current highlighted, Cards required docs 3 missing upload buttons, My applications 3 cards university logo course status mini timeline, Upcoming appointments date time counsellor meeting link, Tasks 2 pending, Messages latest from counsellor, Profile wizard same as CRM simplified save draft, Documents upload drag-drop status required/uploaded/verified/rejected requests pending upload button due date preview own only, Applications tracking list+detail+timeline DB-driven+status, Offers view accept/decline conditions list documents, Appointments view+book new based on staff availability + meeting link, Messages chat with counsellor internal notes NOT visible unread count, Notifications bell unread dropdown mark read link relevant record, GDPR view consent request data export ZIP request deletion, Mobile bottom nav Dashboard/Applications/Documents/Appointments/Profile works 375px, Candidate NEVER access admin/managers/other candidates data IDOR test, Design clean white cards friendly premium fintech rounded 12px soft shadows Inter font primary navy #0B2C5C bg #f8fafc progress tracker accurate real data
- Public Site: Home hero headline "Your Dream to Study Abroad - Just Click & Achieve It" sub "Find The Right Path with Global Consultancy!" + 2 CTAs + Trust badges 1000+ Students 50+ Universities 5+ Countries 19K followers + Right image diverse students UK campus + floating cards Visa Approved Offer Received + Countries 6 cards UK/USA/Canada/Australia/EU/Malaysia/Finland/Middle East with flag/image + course count + Services 6 cards Free Counselling Admission Compliance Accommodation Partner End-to-End + Course Finder widget filter bar Subject Level Country University Intake Tuition IELTS + Partner Universities logo carousel infinite marquee 30s grayscale→color hover pause + Testimonials visa stories 3 cards UK flag student photo "UK STUDENT VISA SUCCESS | অভিনন্দন TANIYA AZAD!" + Stats 4 cols + CTA navy bg orange blob parallax + Footer 4 cols Company Study Destinations Services Contact London Khulna addresses hotline email socials, Destinations pages, Universities, Courses, Course Finder with filters/compare/wishlist/eligibility, Contact with map + form + book appointment, Apply Online form builder, Blog/Resources, Visa Stories, Privacy Policy, Terms, Cookie banner, Sitemap/Robots, SEO Title/Meta/Canonical/OG/Image/Schema, CRM pages noindex nofollow, About Who We Are, Services 12 detailed, Admission Requirements Masters/Pre-Masters/Undergrad/Foundation, Scholarships, Admission Process 5-step Dream→Admission, 7-step Free Counselling flow, End-to-End flow Start→University Selection→Application→Scholarship→Visa→Pre-Departure→Post-Arrival
- Content & Design: Real G Consultancy data navy #0B2C5C orange #FF8C00 brand taglines USP both offices 3 phones socials 19K badge 19 countries 5-step flow visa-story testimonials, Stitch MCP project + design system + 3 reference screens implemented natively ink sidebar stat strip stamps journey rail, Better-interface polish AA contrast Apple glass backdrop-blur-xl focus rings orange press scale 0.98 image slots fallback initials editorial Instrument Serif headings black actions #0F172A fade-rise motion Lenis smooth scroll parallax count-up marquee magnetic buttons shine swipe 0.6s
- Docs: Architecture, ERD Mermaid, RBAC matrix, Workflow map state machines, Deployment plan/checklist, Risk log, User + Admin manuals, Security checklist, DB schema 100 tables, Design system, Backup guide, References guide Facebook+Website merged, Short animation prompts free-tier, Perfect hosting plan, Production plan this doc

**OUT OF SCOPE (Phase 2 - Future):**
- WhatsApp/SMS channel (architecture ready via whatsapp_logs + interface, not implemented)
- Recurring appointments + Blocked time calendar (table ready, UI pending)
- Native mobile app (portal is PWA responsive, not native)
- AI chatbot (can add later)
- Payment gateway integration for deposits (manual tracking now, Stripe later)
- Multi-language Bengali (English only now, structure ready)

---

### 3. TECH STACK - PRODUCTION LOCKED FOR HOSTINGER

```
Backend: PHP 8.2 (Hostinger hPanel Advanced → PHP Configuration), Laravel 12 (11 has unpatched security advisories; 12 needs the same PHP 8.2), MySQL 8 (via phpMyAdmin), Eloquent ORM
Frontend Public + CRM + Portal: Blade, Tailwind CSS 3.4, Alpine.js 3, Inter font + Instrument Serif headings
Build: Vite, npm run build locally → commit public/build → upload to Hostinger (no Node in prod)
Queue: database (not Redis - Hostinger no Redis), Cache: file, Session: database, Storage: local + private disk storage/app/private/candidates/{id}/ (NOT public)
Mail: SMTP smtp.hostinger.com Port 465 SSL or 587 TLS, Queue via database, Mail logs table
Auth: Laravel Breeze + custom RBAC + TOTP 2FA (pragmarx/google2fa) + recovery codes
PDF: DomPDF for invoices
Excel: maatwebsite/excel for import/export legacy-header tolerant
Security: Policies, Gates, Middleware, Rate limiting, CSRF, XSS Blade escaping, SQL Eloquent, $fillable, Bcrypt, Security headers
SEO: SEO Title, Meta, Canonical, OG, Schema JSON-LD, XML sitemap, robots.txt, Clean URLs, Optimized images, Fast Loading, Mobile Responsive, CRM noindex nofollow
Monitoring: Audit logs, Data access logs, Backup dashboard, Health dashboard, Queue/Failed jobs, Cron schedule:run * * * * * via hPanel
```

**Why Hostinger Safe:**
- No Redis, no Docker, no WebSocket, no Node server, no Supervisor - all database queue + cron
- All assets built locally, uploaded as static public/build
- Private docs protected via controller + policy + audit log, NOT via public URL
- .env not accessible via browser 404, storage/private 403

---

### 4. ARCHITECTURE - 3-TIER PRODUCTION

```
[Public Website: gconsultancy.co.uk]
Home, Destinations, Finder, Blog, Contact, Booking, Apply, Visa Stories
↓ Form submit validation+rate limit+spam+honeypot+duplicate check
↓ Creates Lead → Assign Staff → Notify → Confirmation Email
↓ Lead→Candidate Conversion

[CRM: crm.gconsultancy.co.uk] ← Admin/Manager/Staff login
Dashboard, Candidates, Applications 22-status, Documents secure private, Offers+Conditions, Deposits, CAS, Visa, Enrolment, Accommodation, Tasks, Action Notes, Appointments+Calendar+Availabilities+Public Booking, Messages, SLA, Automation, Commission Tiered+Clawback, Referral Partners, Invoices PDF, Payments, Reports+Funnel+Marketing, Users/Roles/Teams/Branches, Settings, Workflow Builder, Custom Fields, CMS, GDPR, Backup, Health, Queue
↓ All data stored in MySQL via phpMyAdmin
↓ Notifications in-app + queued email bell + mail logs
↓ Cron 6 commands: CheckDocumentExpiry daily 9am, SendTaskReminders daily 8am, SendAppointmentReminders hourly, AutoArchiveLostLeads weekly, SlaBreachCheck hourly, BackupDb daily 2am

[Candidate Portal: crm.gconsultancy.co.uk/candidate OR candidate.gconsultancy.co.uk]
Register/Login, Dashboard Progress Tracker Profile→App→Offer→Deposit→CAS→Visa→Enrolment, Profile Wizard, Docs Upload+Status+Requests, Applications Tracking+Timeline, Offers Accept/Decline+Conditions, Appointments View+Book, Tasks, Messages Chat internal NOT visible, Notifications, GDPR Export/Delete, Mobile Bottom Nav 375px
↓ Same MySQL DB
↓ Same private docs via controller+policy

[MySQL via phpMyAdmin: u123456789_gconsultancy]
100 tables - visible in phpMyAdmin → Browse → Structure
```

---

### 5. DATABASE - 100 TABLES IN PHPMYADMIN - PRODUCTION SCHEMA

**Group A Identity 8:** users, roles, permissions, role_permissions, teams, branches, login_histories, sessions
**Group B Leads 6:** lead_sources, lead_campaigns, leads, lead_activities, candidate_assignments, counselling_sessions
**Group C Candidates 6:** candidates, candidate_addresses, emergency_contacts, sponsors, candidate_custom_fields, gdpr_consents
**Group D Catalogue 15:** countries, cities, subjects, study_levels, universities, university_contacts, university_campuses, courses, intakes, course_intakes, course_requirements, scholarships, courses_shortlisted, deadlines
**Group E Documents 5:** document_types, candidate_documents, document_versions, document_requests, document_access_logs
**Group F Applications 12:** applications, application_statuses, application_status_history, application_documents, application_notes, offers, offer_conditions, deposits, cas_records, visa_cases, enrolments, student_support
**Group G Operations 12:** tasks, action_notes, appointments, staff_availabilities, communications, messages, sla_policies, sla_breaches, automation_rules, automation_logs, notifications, notification_logs
**Group H Finance 8:** commission_rules, commissions, referral_partners, referral_payments, invoices, invoice_items, payments, student_payments
**Group I CMS 12:** pages, menus, blog_categories, blog_posts, faqs, testimonials, team_members, banners, media_library, website_enquiries, forms, form_submissions
**Group J Portal+Reports 6:** saved_courses, candidate_notifications, staff_notifications, audit_logs, data_access_logs, gdpr_requests
**Group K System 10:** settings, email_templates, custom_field_definitions, custom_field_values, backups, system_health, failed_jobs, whatsapp_logs, email_logs, search_index

**All with migrations + indexes on all FKs + email, phone, uid, status, created_at, assigned_to, university_id, course_id, intake_id, candidate_id, application_id, document_type_id, due_date, invoice_number unique.**

**How to verify production:** phpMyAdmin → Your DB → Left sidebar 100 tables → Click users → Browse → See admin@gconsultancy.co.uk → Click candidates → After creating candidate via CRM, row appears here.

---

### 6. ROLES & ACCESS - PRODUCTION RBAC MATRIX

**4 Roles:**

**ADMIN:** All org-wide, create users/roles/settings/workflow/GDPR/backup/health, see all candidates/applications/finance, change default passwords, restore backup
**MANAGER:** Team + assigned, manage team candidates, assign staff, verify documents, change status, view team commission, cannot delete users or change settings or GDPR delete or backup
**STAFF/USER:** Assigned only record-level security, create/edit own candidates, upload docs, create applications, add tasks/notes/appointments, view own tasks, cannot verify docs only Manager/Admin, cannot see other staff candidates, cannot see finance commission, cannot access settings, IDOR must 403
**CANDIDATE:** Own only, register/login email verification, complete profile wizard, upload docs, view applications timeline, accept/decline offers, book appointments, messages, GDPR export/delete, NEVER access CRM or other candidates or verify docs or change status, public registration with GDPR consent checkbox consent_given consent_date consent_ip policy_version

**Permission Matrix:** candidates.view All/Team/Assigned/Own, candidates.create/update Yes/Team/Assigned/Own Profile, candidates.assign Yes/Yes/No/No, applications.view All/Team/Assigned/Own, applications.status.change Yes/Yes/Limited/No, documents.upload Yes/Yes/Yes/Own, documents.verify Yes/Yes/No/No, documents.download Authorized/Authorized/Authorized/Own Authorized, offers/cas/visa/enrolment.crud Yes/Team/Assigned/View Own, commission/invoice/payment.crud Yes/View Team/No/No, tasks/notes/appointments.crud Yes/Team/Assigned/View Own, reports.view All/Team/Own/No, settings/audit/gdpr/backup Yes/No/No/No, website.cms Yes/No/No/No

**Enforcement 3 Layers:**
1. Middleware: auth + role:admin,manager + permission
2. Policy: $this->authorize('view', $candidate) CandidatePolicy checks assigned_to + team + branch
3. Query Scope: Candidate::whereAssignedTo(auth()->id()) for Staff, whereTeam() for Manager

**IDOR Test Production:** Staff A login → copy /candidates/456 where 456 belongs to Staff B → paste → MUST 403, not data. Candidate A → /documents/789/download where 789 belongs to B → MUST 403. If fails, RBAC broken - DO NOT HANDOVER.

---

### 7. MODULES - PRODUCTION DETAIL - NOTHING MISSING

**Each module must have:** Migration → Model → Policy → Form Request → Service → Controller → Blade → Route → Test. No TODO, no fake data.

**See Perfect Hosting Plan for full module specs.** This plan adds production constraints: Every route has auth+can:permission, every controller has policy check, every query has eager loading to prevent N+1, every list has pagination 20 never load 1000s, every file upload MIME+finfo+double extension block passport.pdf.php blocked, every stat real DB query not hard-coded, every document download via controller+policy+audit log not public URL, every .env secret not in JS, every CRM page noindex nofollow, every email queued via database, every cron via schedule:run, every backup via phpMyAdmin export + file backup, every GDPR export ZIP JSON+docs list+applications.

---

### 8. UI/UX - PRODUCTION DESIGN SYSTEM

**Brand:** Navy #0B2C5C ink sidebar, Orange #FF8C00 accent, White, Bg #F8FAFC, Text #0F172A, Muted #64748B, Border #E2E8F0, Black actions #0F172A hover black, Success #10B981, Warning #F59E0B, Danger #EF4444, Radius 12px, Shadow-sm.

**Typography:** Inter 400-500 body leading-relaxed, Instrument Serif 600-700 headings tracking-tight line-height 0.9-1.1, 72px hero, 14px body, 12px small.

**Components:** AppLayout sidebar 280px ink navy white text orange active, Topbar 64px→56px on scroll blur bg-white/70 backdrop-blur-xl border-b, StatCard with icon + count-up + trend, DataTable with search/filter/sort/pagination/rows per page/export, FilterPanel, Modal, FormWizard 6 steps with draft+completion %, StatusBadge color-coded, Timeline vertical DB-driven, FileUploader drag-drop multiple, DocumentCard required/uploaded/verified/rejected/missing/expired, EmptyState, Toast, ContactCta pulling from settings, JourneyRail line draws on scroll progress 0→100%, Marquee infinite 30s logos grayscale→color pause hover, Apple glass bg-white/70 backdrop-blur-xl border-white/20 shadow 0 8px 32px rgba(11,44,92,0.08), Focus ring orange 2px offset, Press scale 0.98, Image slots fallback initials navy circle, Editorial restyle, Motion fade-rise 16px y 0.7s cubic-bezier(0.16,1,0.3,1) stagger 0.06-0.12s, Parallax flags 0.5x, Magnetic buttons follow cursor 8px shine swipe left→right 0.6s, Count-up easeOutExpo 1.2s, Lenis smooth scroll, Respects prefers-reduced-motion, No CLS <0.1, JS <150kb, Load <3s 1000 candidates, Mobile bottom nav Dashboard/Applications/Documents/Appointments/Profile.

**Pages:** Home hero Dream tagline stagger + trust 19K count-up + student image flags parallax + floating glass cards + orange contact bar + contact card right, 5-step journey rail, Countries 6 cards flag+count hover zoom 1.05 lift -4px shadow-xl, Services 6 cards glass hover orange top border, Course Finder widget sticky blur, Partner logos marquee infinite, Visa stories 3 cards masonry flag+photo+quote hover lift rotate 0.5deg stagger, Stats 4 cols count-up, CTA navy bg orange blob blur 200px parallax mouse, Footer 4 cols Company Destinations Services Contact London Khulna hotline email socials magnetic, Mobile 375px/768px/1440px.

---

### 9. SECURITY + GDPR - PRODUCTION HARDENED

**Security:** Bcrypt hashing never plaintext, CSRF all forms, XSS Blade escaping, SQL Eloquent+Form Requests, $fillable mass assignment, Rate limiting login 5 fails lock 15 mins + audit log + forms + API, Secure password reset token expiry, No secrets in frontend JS all .env, Security headers, IDOR tested Staff A cannot access Candidate B, Private docs storage/app/private/candidates/{id}/ NOT public storage/link only for public logos, Document download via controller only /documents/{id}/download with auth+policy+audit log document_access_logs, .env NOT accessible 404, storage/private 403, logs/backups/dumps NOT accessible, File upload MIME+extension+size+finfo deep check + block double extension passport.pdf.php blocked + UUID filename, Audit logs user action model type model ID before/after json IP user agent created_at filters export never log passwords/secrets, Login history IP device browser success/fail, Session management admin view active sessions force logout, TOTP 2FA optional admin recovery codes, Brute force protection.

**GDPR:** Consent checkbox saves consent_given consent_date consent_ip policy_version, Consents table, SAR export one-click ZIP JSON+docs list+applications, Anonymization tool admin anonymize replace PII ANONYMIZED_XXXX keep application IDs+financial records audit never hard delete financial, Deletion requests table type export/anonymize/delete status requested_at completed_at requested_by, Data access logs who accessed personal data when purpose SAR compliance, Data retention cron anonymize old after 72 months, Cookie banner + Privacy Policy CMS, Settings GDPR retention months 72 policy version consent required boolean, Candidate portal view consent request export ZIP request deletion, CRM pages noindex nofollow, Deletion never hard delete financial records for audit.

---

### 10. DEPLOYMENT - PRODUCTION TO HOSTINGER + PHPMYADMIN

**Pre-Deploy Local:**
- composer install --no-dev, npm run build, php artisan test full suite green, php artisan migrate:fresh --seed verified on local MySQL 8, HTTP sweeps clean, IDOR/RBAC probed, .env.example template no secrets, public/build committed, storage/app/private not committed, .gitignore node_modules/vendor/.env/storage/logs

**Deploy Steps (2 Hours):**

1. hPanel → Databases → MySQL Databases → Create u123456789_gconsultancy + user + password → Note DB name/user/pass/host localhost
2. hPanel → Advanced → PHP Configuration → 8.2 or 8.3 + extensions BCMath Ctype Fileinfo JSON Mbstring OpenSSL PDO Tokenizer XML Curl GD Zip + max_execution 300 memory 512M upload 32M post 32M
3. hPanel → Domains → Subdomains → crm.gconsultancy.co.uk → Folder public_html/crm → Change Document Root to public_html/crm/public (critical)
4. File Manager → public_html/crm → Upload zip (without node_modules/.git) → Extract
5. Create .env file in root (same level as artisan) with production values from Perfect Hosting Plan Step 5
6. Terminal: cd ~/domains/crm.gconsultancy.co.uk/public_html, composer install --no-dev --optimize-autoloader, php artisan key:generate, php artisan storage:link (only public logos), php artisan migrate --force (100 tables appear in phpMyAdmin), php artisan db:seed --class=ProductionSeeder (admin@gconsultancy.co.uk / one-time random password printed by seeder (forced change on first login) + roles/permissions/settings/document types/countries/levels/subjects/intakes/lead sources), chmod -R 775 storage bootstrap/cache, php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan optimize
7. hPanel → SSL → Free SSL → Enable + Force HTTPS
8. hPanel → Advanced → Cron Jobs → Add * * * * * /usr/bin/php /home/u123456789/domains/crm.gconsultancy.co.uk/public_html/artisan schedule:run >> /dev/null 2>&1
9. hPanel → Email → Create noreply@gconsultancy.co.uk + password → Note SMTP host smtp.hostinger.com port 465 SSL
10. Verify in phpMyAdmin: Open → Your DB → 100 tables → users → admin → login crm.gconsultancy.co.uk → admin@gconsultancy.co.uk / one-time random password printed by seeder (forced change on first login) → Change password immediately
11. Post-deploy smoke test: All 4 roles login, create candidate, upload doc, create application, change status, verify doc security IDOR, test email sending, test cron php artisan schedule:run, test backup/restore drill, load check 1000 candidates page <3s add indexes/cache if needed, device check 375/768/1440, .env private logs not accessible 404/403
12. Push to GitHub ahsam991/gconsultancy, tag v1.0 production

**Production .env Must Have:** APP_ENV=production APP_DEBUG=false APP_URL=https://crm.gconsultancy.co.uk DB_CONNECTION=mysql DB_HOST=localhost DB_PORT=3306 DB_DATABASE=u123456789_gconsultancy DB_USERNAME=u123456789_admin DB_PASSWORD=*** QUEUE_CONNECTION=database SESSION_DRIVER=database CACHE_DRIVER=file MAIL_MAILER=smtp MAIL_HOST=smtp.hostinger.com MAIL_PORT=465 MAIL_ENCRYPTION=ssl MAIL_FROM_ADDRESS=noreply@gconsultancy.co.uk

**phpMyAdmin Proof:** After deploy, open phpMyAdmin → Your DB → candidates table → Browse → After you create candidate from CRM, row appears here with created_at. This proves website stores data in phpMyAdmin MySQL.

---

### 11. TESTING & QA - PRODUCTION READY

**Unit Tests:** Models, Services, Policies, Commission tiered calc 5=15% 6th=20%, Clawback, Duplicate detection fuzzy
**Feature Tests:** Auth 4 roles, Candidate CRUD search/filter import/export duplicate bulk assign/archive/restore merge wizard completion %, Document private storage versioning verify/reject checklist % expiry cron requests preview/download via controller+policy+audit, Catalogue universities+contacts+campuses courses+requirements intakes finder filters/compare/eligibility/wishlist, Applications 22-status history+reason tabs timeline fees notes @mention, Journey offers+conditions auto-promotes deposits CAS visa enrolment accommodation pre-departure arrivals sponsorship, Ops tasks action notes appointments calendar availabilities public booking messages communications SLA breach automation 6 cron, Finance commission claim/receive/clawback referral partners share payouts student payments invoices real DomPDF payments revenue dashboard, Leads enquiries duplicate lead→candidate, Notifications in-app+queued email bell mail logs, Dashboard role-aware stats funnel charts 9 reports CSV/Excel global search indexed, Admin settings users/teams/branches workflow builder custom fields email templates CMS GDPR SAR export/anonymize/delete backup health queue, Portal progress tracker profile docs applications appointments tasks messages GDPR mobile bottom nav, Public site home destinations finder contact booking apply blog visa stories privacy/terms cookie sitemap/robots SEO
**Security Tests:** IDOR Staff A cannot see Candidate B assigned to Staff B 403, Candidate A cannot download B passport 403, .env 404, private 403, File upload MIME+finfo+double extension blocked, Rate limiting 5 fails lock 15 mins, CSRF, XSS, SQL Injection, $fillable, Bcrypt, Headers, No secrets in JS, Audit logs record login/create/update/delete/view/download/status change before/after IP user agent, Login history, Session kill
**Performance Tests:** N+1 eager loading, Pagination 20 never load 1000s, Indexes all FKs, Caching settings/countries, Asset build optimized, Page load <3s 1000 candidates, CLS <0.1, JS <150kb
**Manual QA:** Click every button no broken link, No fake dashboard data real DB queries, No hard-coded secrets grep password api_key, No TODO FIXME Coming Soon Lorem Ipsum, Mobile layout 375/768/1440, Candidate portal mobile bottom nav, All 4 roles E2E, Email sending Hostinger SMTP, Cron Hostinger, Backup & restore tested, Fresh-migrate + seed verified, HTTP sweeps clean
**Full test suite green (every slice ships feature + policy tests) required Before Handover.**

---

### 12. TIMELINE & MILESTONES - PRODUCTION

**Week 1-2 Phase 0-1:** Architecture docs + Foundation Auth RBAC Base Layout Settings Audit Logs
**Week 3-4 Phase 2-3:** Candidate CRM + Documents Secure
**Week 5-6 Phase 4-5:** Academic Catalogue + Course Finder + Applications Core 22-status
**Week 7-8 Phase 6-7:** Student Journey Offers/CAS/Visa/Enrolment/Accommodation + Ops Tasks/Appointments/Booking/SLA/Automation
**Week 9 Phase 8-9:** Finance Commission Tiered+Clawback+Invoices PDF + Leads+CMS
**Week 10 Phase 10-11:** Reports Dashboard Funnel Global Search GDPR Workflow Custom Fields Backup Health + Portal + Public Site Home Destinations Finder Contact Booking Apply Blog Visa Stories
**Week 11 Phase 12-13:** Security QA Performance IDOR RBAC Mobile Device + Hostinger MySQL Staging + Deployment + Smoke Test + Load Check + Loom Video + Handover Package
**Total 11 Weeks Single Dev, 6 Weeks 2 Devs.**

**Milestones:**
M1 Architecture Approved, M2 Foundation Done, M3 Candidate+Docs Done, M4 Catalogue+Applications Done, M5 Journey+Ops Done, M6 Finance+Leads+CMS Done, M7 Reports+Portal+Public Done, M8 Security+QA Green, M9 Hostinger Deployed + Smoke Test + Loom + Client UAT Signed

---

### 13. DELIVERABLES - PRODUCTION HANDOVER PACKAGE

1. Code: GitHub ahsam991/gconsultancy tag v1.0 production + zip
2. Database: SQL dump from phpMyAdmin Export + migrations + ProductionSeeder with real G Consultancy data navy #0B2C5C orange #FF8C00 taglines USP offices phones socials 19K badge 19 countries 5-step flow visa-story testimonials
3. Credentials: Admin login admin@gconsultancy.co.uk / new password + Hostinger hPanel + DB name/user/pass/host + phpMyAdmin URL + Email noreply@gconsultancy.co.uk + SMTP host/port/pass + .env file securely
4. Docs: README.md setup+deployment+cron+backup/restore, DEPLOYMENT.md Hostinger step-by-step screenshots, ARCHITECTURE.md high-level+Hostinger, DATABASE_ERD.md Mermaid ERD+100 tables relationship, DATABASE_SCHEMA.md columns types indexes FKs, RBAC_PERMISSION_MATRIX.md permissions+roles matrix, WORKFLOW.md candidate lifecycle application pipeline document lifecycle offer conditions finance flow country-specific UK Offer->Deposit->CAS->Visa Canada Offer->PAL->Visa USA Offer->I-20->Visa with Mermaid state diagrams, DESIGN_SYSTEM.md colors typography components layouts icons animations, REFERENCES.md Facebook+Website merged real content, User Manual staff how use CRM + candidate portal, Admin Manual admin manage users settings GDPR backup health, Security Checklist IDOR RBAC, Backup Guide, Risk Log, Short Animation Prompts free-tier, Perfect Hosting Plan, Production Plan this doc, Project Done Checklist 150+ checks
5. Backup Files + Restore Guide
6. Video: 15-min Loom walkthrough Dashboard Candidate Create Application Create Document Upload/Verify Offer Visa Commission Reports Candidate Portal
7. Support: 30 days bug fixing

**Client Handover Meeting:** Show login all 4 roles, create candidate live, upload doc see in phpMyAdmin, create application, change status history, verify doc, offer conditions auto-promote, commission tiered calc, invoice PDF, reports, portal progress tracker, public site hero 5-step journey rail marquee, device 375/768/1440, IDOR test Staff A cannot see B, email send, cron run, backup/restore drill, phpMyAdmin data proof.

---

### 14. RISK & MITIGATION - PRODUCTION

**Risk 1 - SQLite/MySQL drift:** Mitigation develop and test on local MySQL 8 from day 1 (DBngin or Docker), plus MySQL staging run migrate+test against Hostinger MySQL queries driver-aware but unproven, test on Hostinger staging subdomain before prod
**Risk 2 - 42 university logos missing:** Mitigation Ask client now, upload to media_library with fallback initials navy circle, guide says ask client, not blocker
**Risk 3 - IDOR vulnerability:** Mitigation Policy checks every controller + query scopes + test probes Staff A vs B + Candidate A vs B, DO NOT HANDOVER if fails
**Risk 4 - Private docs exposed via public URL:** Mitigation Store in storage/app/private/candidates/{id}/ NOT public, download via controller only auth+policy+audit log, storage/link only for public logos, test 403 via browser direct URL
**Risk 5 - Email not sending Hostinger SMTP:** Mitigation Test SMTP credentials before deploy, use database queue + mail logs table, test email sending post-deploy, fallback log
**Risk 6 - Cron not running Hostinger:** Mitigation Add cron * * * * * artisan schedule:run, test php artisan schedule:run, check failed_jobs table, backup/health dashboard shows cron status
**Risk 7 - Load 1000 candidates page >3s:** Mitigation Indexes all FKs, eager loading, pagination 20, caching settings/countries, asset build optimized, count-up not heavy, Lenis smooth scroll optional, test 1000 candidates via seeder
**Risk 8 - Mobile layout broken:** Mitigation Device check 375/768/1440 manual review, bottom nav candidate portal, Tailwind responsive, image fallbacks
**Risk 9 - GDPR non-compliant:** Mitigation Consent checkbox saves IP policy version, SAR export ZIP, Anonymization keeps financial IDs, Deletion requests table, Retention cron 72 months, Cookie banner, Privacy Policy CMS, Data access logs, Audit logs never log passwords
**Risk 10 - .env secrets exposed:** Mitigation .env NOT accessible 404, storage/private 403, logs/backups/dumps NOT accessible, no secrets in JS, grep password api_key, .env.example template no real secrets

---

### 15. MAINTENANCE & SUPPORT - PRODUCTION

**Post-Launch 30 Days Bug Fixing Included:** Fix bugs free, not new features
**Backup:** Daily 2am cron BackupDb + file backup, manual trigger + download via Backup dashboard, restoration documentation, store backups offsite Google Drive optional, test restore drill before handover
**Monitoring:** Health dashboard PHP version Laravel version DB status storage usage disk usage failed jobs mail status last backup application errors queue status cron status, Queue/failed jobs monitoring retries, Audit logs, Data access logs, Login histories, SLA breach widget, Deadline dashboard
**Updates:** Laravel security updates, composer update --no-dev, npm run build, migrate new migrations --force, config:cache route:cache view:cache optimize, tag new version v1.1 etc
**Support Channels:** Email info@gconsultancy.co.uk, WhatsApp +44 7402993321, Hostinger hPanel support

**Future Phase 2 Optional:** WhatsApp/SMS channel architecture-ready, Recurring appointments + blocked time, PHP 8.2 compat confirmation dev runs 8.5, Payment gateway Stripe for deposits, AI chatbot, Multi-language Bengali, Native mobile app PWA

---

### 16. REFERENCES - WHERE REAL CONTENT COMES FROM - PRODUCTION TRUTH

**Website:** https://www.gconsultancy.co.uk/ scraped 2026-10-03 - Who We Are "reputable educational consultancy end-to-end support consultation to settling", Services free counseling admission assistance compliance support student accommodation partner university services, Strategic research-driven in-depth assessment, Pre-visa interview pre-departure post-arrival, IELTS and spoken English training, British Council Certified agency quality over quantity certified consultants highest visa success rates first genuinely concerned individual needs, Admission Open September 2026 January 2026 May 2026, 100% FREE Education Counselling and Application Processing, largest UK universities representative, Admission Categories Masters Pre-Masters Undergraduate Foundation with requirements GPA CV Bachelor GRE/GMAT IELTS/TOEFL LOR SOP research proposal interview writing sample portfolio, Our Services Unlock Global Opportunities empowers students explore global education expert guidance tailored support simplify process, Pathway to International Education customized pathways managing every detail program selection to visa applications seamless, Transforming Futures transform future opening doors prestigious global institutions, UK education Expo all over world, A Glimpse of Our Happy Students, Leading University 42 images including Lincoln University Malaysia, Hotline 07402993321 London, Address London Suite 3 2nd Floor LMC Business Wing 38-44 Whitechapel Road London England, Email info@gconsultancy.co.uk, Services pages: Free Counseling Start Journey Free Expert Guidance every student deserves right guidance expert advice university selection admission requirements visa processes scholarships career opportunities no cost unsure course need help application experienced consultants support every step book free session brighter future What can we provide University & Course Selection experts analyze academic background career goals preferences recommend best institutions courses, Admission Guidance simplify understand entry requirements deadlines required documents write strong personal statement SOP team ensures stands out, Visa & Immigration Advice navigating overwhelming up-to-date information student visa applications required documents interview preparation compliance smooth approval, Scholarship Assistance expensive explore scholarship opportunities grants financial aid reduce costs guide eligibility application processes increase chances securing funding, Career Planning & Post-Study Options thinking beyond education insights career opportunities internships post-study work permits destination country goal build strong foundation future success, Step-by-Step Process Book Free Consultation visit working hours any day week book free appointment form team call confirm schedule convenient time bring academic documents free online counseling sessions chat one-on-one discuss needs expert guidance, Talk to Expert personalized guidance experienced consultants discuss academic goals career aspirations personal preferences help choose right university course study destination aligns profile, Get Tailored Study Plan personalized study plan including best universities courses application timelines scholarship options details requirements deadlines stay on track, Admission & Application Support assist university applications ensuring necessary documents order guide writing strong SOP collecting recommendation letters, Visa & Immigration Assistance after acceptance letter help student visa application process experts guide required documentation visa interviews approval procedures, Pre-Departure Guidance & Accommodation Assistance essential pre-departure guidance prepare life abroad including travel arrangements cultural tips assist finding accommodation close to university, Post-Arrival Support support university registration local transportation settling in providing information adjust new environment, Ready to Begin Book Free Counseling Session Today, Admission Services Start Journey Admission Services Seamless University Admissions We Make It Easy Challenging support selecting right university submitting application experts guide every step hassle-free, Step-by-Step University & Course Selection expert advice choosing best universities programs based academic profile career goals, Eligibility Check assess qualifications help determine right options suited academic background, Document Preparation team assists preparing required documents SOPs recommendation letters application forms, Application Submission ensure applications submitted accurately on time increasing chances acceptance, Follow-Up & Offer Letter track application status follow up universities help prepare interviews required, Visa & Pre-Departure Guidance once receive admission letter assist visa application travel arrangements pre-departure preparation, Why Choose Trusted University Partners Personalized Guidance Easy Application Process Scholarship Assistance Visa Support Pre-Departure & Arrival Help End to end Support, Compliance assistance Understanding following legal requirements complicated easy support help businesses individuals meet legal obligations smoothly ensure follow local international regulations avoid penalties minimize risks help documentation licenses ongoing monitoring expert support fully compliant, Compliance Services Documentation & Policy Support assistance preparing necessary legal operational documents, License & Permit Assistance helping obtain required approvals business, Risk Assessment & Mitigation identifying potential risks implementing preventive measures, Ongoing Monitoring & Updates keeping informed regulatory changes ensuring continued compliance, Partner University Services Offered Associates proud partner several top-ranked Universities globe local international organizations offer wide range services partnered universities materializing smooth hassle-free higher study journey Organize local International Education Fair/Expo Spot assessment day University open admission day Campus visit School Visit through branding/regional office Accommodation Options provide information various types accommodation international students university-managed halls private student residences private rented pros cons costs facilities, Finding Suitable Accommodation assist finding suitable meets preferences budget guidance location proximity transport links factors, Booking Assistance help booking process guide application process liaise accommodation providers behalf help ensure necessary paperwork payments completed accurately on time, Accommodation Arrangements help coordinate confirming move-in dates providing information expect upon arrival arranging airport transfers required, Resolving Accommodation Issues guidance support resolving issues accommodation stay UK guidance support resolving may include assisting communication between you accommodation provider addressing maintenance facility concerns helping contractual legal matters related accommodation, Important note availability specific options vary depending university location advisable discuss preferences requirements early possible allow sufficient time arrangements advisable research compare different accommodation options independently ensure comprehensive understanding choices help make informed decision best suits needs preferences, Student Accommodation Services We understand finding right accommodation crucial successful comfortable study experience abroad Student Accommodation Services designed assist every step process ensuring secure housing meets preferences budget, End-to-End Services At Global Consultancy Ltd provide seamless end-to-end service students aspiring study abroad Partnering leading universities across 15+ countries including USA Canada EU guide every stage journey selecting right university securing admission scholarships visa support beyond Start University Selection & Admission Counseling Application Assistance & Document Preparation Scholarship & Financial Aid Support Visa Guidance & Compliance Support Pre-Departure Assistance & Accommodation Support Post-Arrival & Career Support At Global Consultancy Ltd don't just help students get admitted universities build futures end-to-end services ensure right guidance resources support every stage study abroad journey selecting perfect university securing scholarships navigating visa processes settling new country with you every step

**Facebook:** https://www.facebook.com/GCEduLimited 19K followers 180 following 2 reviews Always open, Cover banner GLOBAL CONSULTANCY EDUCATION flags UK EU USA Canada Australia Malaysia Finland with "Your Dream to Study Abroad - Just Click & Achieve It" + 5 steps Dream Counselling Select University Apply Get Admission + Orange contact bar London Suit 3 2nd Floor Lmc Business Wing 38-44 Whitechapel Road London Khulna House No 52 Alingon House R#5 Nirala R/A Khulna + Right card www.gconsultancy.co.uk info@gconsultancy.co.uk +447402993321 +8801744742686 +8801935017746 + Logo white circular orange/blue graduation cap "Leading Education Support", Details 2 reviews Always open Address London Whitechapel Road Khulna Khulna Division Bagerhat Satkhira Sonadanga London UK Khalishpur, Links gconsultancy.co.uk, Contact info Instagram global_consultancy_education X GlobalCons78186 YouTube GlobalConsultancy-xf5jh Phone 07402 993321 Email info@gconsultancy.co.uk Messenger Global Consultancy Education, Featured posts UK STUDENT VISA SUCCESS অভিনন্দন TANIYA AZAD! Another Dream Achieved! Study in Finland with Your Family!, Photos See all photos, 19K followers badge social proof

**Company:** GC CONSULTANCY LIMITED 02773896 GOV.UK Registered office 283 Green Lanes London N13 4XS

**All real content extracted via browser.open + browser.search + screenshots provided by user.**

---

### 17. FINAL PRODUCTION CHECKLIST - GO LIVE

- [ ] 100 tables migrated in phpMyAdmin via migrate --force
- [ ] ProductionSeeder seeded admin + roles + permissions + settings + countries + levels + subjects + intakes + lead sources + document types with real G Consultancy data
- [ ] Admin login works admin@gconsultancy.co.uk / one-time random password printed by seeder (forced change on first login) → change password
- [ ] 4 roles tested Admin/Manager/Staff/Candidate
- [ ] IDOR tested Staff A cannot see Candidate B 403 + Candidate A cannot download B doc 403
- [ ] Private docs storage/app/private/candidates/{id}/ NOT public + download via controller+policy+audit
- [ ] .env NOT accessible 404 + storage/private 403 + logs/backups/dumps NOT accessible
- [ ] File upload MIME+finfo+double extension blocked passport.pdf.php
- [ ] 5-fail lockout 15 mins + login history + session kill + 2FA TOTP + recovery codes
- [ ] Candidate registration public GDPR consent saves IP policy version
- [ ] Candidates CRUD search/filter import legacy-header tolerant Excel export duplicate warnings fuzzy bulk assign/status/archive/restore merge 6-step wizard drafts completion % real
- [ ] Documents versioning verify/reject checklist % expiry cron 30/7 days requests preview download via controller+policy+audit
- [ ] Catalogue universities+contacts+campuses courses+requirements intakes finder filters/compare/eligibility/wishlist same DB
- [ ] Applications 22-status pipeline history+reason tabs timeline fees notes @mention
- [ ] Journey offers+conditions auto-promotes deposits CAS visa enrolment accommodation pre-departure arrivals sponsorship
- [ ] Ops tasks action notes appointments calendar availabilities public booking messages communications SLA breach automation 6 cron
- [ ] Finance tiered commission claim/receive/clawback referral partners share payouts student payments invoices real DomPDF payments revenue dashboard
- [ ] Leads enquiries duplicate lead→candidate conversion
- [ ] Notifications in-app+queued email bell mail logs
- [ ] Dashboard role-aware stats funnel charts real queries no fake data
- [ ] Reports 9 CSV/Excel global search indexed audit logs settings users/teams/branches email templates CMS GDPR SAR export/anonymize/delete workflow builder custom fields backup/health/queue
- [ ] Portal progress tracker profile docs applications appointments tasks messages GDPR mobile bottom nav 375px
- [ ] Public site home hero Dream tagline stagger 19K count-up flags parallax glass cards float magnetic CTAs journey rail draws scroll countries hover zoom logos marquee infinite visa stories stagger stats count-up CTA blob parallax footer 4 cols 375/768/1440 responsive
- [ ] Real data navy #0B2C5C orange #FF8C00 taglines USP offices phones socials 19K badge 19 countries 5-step flow visa-story testimonials
- [ ] Stitch design system 3 reference screens ink sidebar stat strip stamps journey rail better-interface polish AA contrast Apple glass focus rings press states image fallbacks editorial Instrument Serif black actions fade-rise motion
- [ ] full test suite tests green fresh-migrate+seed verified HTTP sweeps clean IDOR/RBAC probed
- [ ] Hostinger MySQL staging run migrate+test against Hostinger MySQL not just SQLite
- [ ] Hostinger deployment upload prod .env SSL cron schedule:run storage:link SMTP ProductionSeeder change passwords
- [ ] Post-deploy smoke test all 4 roles IDOR email cron backup/restore load 1000 <3s device 375/768/1440
- [ ] Docs architecture ERD RBAC workflow deployment checklist risk user+admin manuals security checklist DB schema 100 tables design system backup guide references Facebook+Website merged animation prompts free-tier hosting plan production plan checklist
- [ ] No fake data no hard-coded secrets no TODO no broken button no private doc exposure no .env accessible no Lorem Ipsum no hard-coded stats
- [ ] Backup files + restore guide + Loom video 15 min + support 30 days + GitHub tag v1.0 production + client UAT signed

**Project DONE only when all ticked.**

---

**END OF PRODUCTION PROJECT PLAN - READY TO BUILD - HOSTINGER + PHPMYADMIN + ROLES + REFERENCES INCLUDED**

**Give this to developer + hosting login. They build without asking anything. Website will store data in phpMyAdmin MySQL and run perfectly on Hostinger.**

System: GLOBAL CONSULTANCY EDUCATION | Production Plan v1.0
