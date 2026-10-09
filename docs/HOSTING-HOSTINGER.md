# Hosting: Hostinger 50 GB

One shared account holds the code, the database, every student document and the backups. Storage is the limit that bites first, so it has a policy.

## What is not available

No Redis, Node, Docker, WebSockets or Supervisor. Therefore: queue `database`, cache `file`, session `database`, and a single cron entry running the scheduler every minute.

```
* * * * * /usr/bin/php /home/uXXXXXXX/domains/crm.gconsultancy.co.uk/public_html/artisan schedule:run >> /dev/null 2>&1
```

Assets are built on a developer machine and the compiled `public/build` is committed and uploaded. Never run `npm` on the server.

## Document root

The CRM subdomain must point at `public_html/crm/public`, not `public_html/crm`. Getting this wrong exposes `.env` and the whole application directory. Set it in hPanel under Domains, Subdomains.

## Storage policy

| What | Where | Rule |
|---|---|---|
| Student documents | `storage/app/private/candidates/{id}/` | Private disk, UUID filenames, 10 MB per file |
| Public images, logos | `public/storage` via `storage:link` | Only non-sensitive files |
| Database dumps | `storage/app/private/backups/` | Gzipped, 14 kept, pruned nightly |
| Logs | `storage/logs` | Daily channel, 14 days retained |

Uploaded images are re-encoded and capped at 1600 px on the long edge. PDFs are stored untouched because they are legal documents. A rough budget: 2000 students at about 8 files each, averaging 1.5 MB, is roughly 24 GB, which fits with room for the database and backups. The health dashboard warns at 70 % disk use and raises an alert at 85 %.

## Backups

`mysqldump` is often disabled on shared hosting, and `exec()` may be blocked. The backup command therefore tries `mysqldump` first and falls back to a pure-PHP dump that walks the tables and streams insert statements to a gzip handle. Backups run daily at 02:00, are downloadable from the admin dashboard, and live outside the web root. A restore drill into a scratch database must be performed and written up before handover.

## Mail

SMTP through `smtp.hostinger.com`, port 465 with SSL, from `noreply@gconsultancy.co.uk`. All mail is queued on the database queue and recorded in `email_logs`. Test the credentials before the first deploy, because a wrong password fails silently into the failed jobs table.

## Deploy sequence

Create the database and user in hPanel, set PHP to 8.2 or 8.3 with the extensions listed in the spec, point the document root at `public/`, upload the archive without `node_modules` or `.git`, write `.env`, then run:

```
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder
chmod -R 775 storage bootstrap/cache
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Then enable free SSL with forced HTTPS, add the cron entry, and confirm in phpMyAdmin that the tables exist and the admin user is present. Change the seeded admin password immediately.

## Post-deploy checks

`.env` returns 404 in a browser. `storage/app/private` returns 403. Each role can log in. Creating a student in the CRM produces a visible row in phpMyAdmin. A test email arrives. `php artisan schedule:run` executes without error. The failed jobs table is empty.
