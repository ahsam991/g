# Slice 14: GDPR, backup, health

**Tables:** data_access_logs, gdpr_requests, backups, system_health, whatsapp_logs (stub only)

## Build
- SAR export: ZIP with JSON of all candidate data + document list.
- Anonymize: replace PII with ANONYMIZED_XXXX, keep application ids and financial rows.
- Retention command monthly: anonymize candidates inactive > `gdpr.retention_months` (72).
- Backup command daily 02:00: mysqldump (or PHP fallback if exec disabled on Hostinger) to private disk, keep 14, dashboard with download.
- Health page: PHP/Laravel version, DB ok, disk, failed jobs, queue size, last cron run (heartbeat written by scheduler), last backup.

## Done when
Restore drill on staging documented in docs/BACKUP.md; anonymize test proves finance rows survive.
