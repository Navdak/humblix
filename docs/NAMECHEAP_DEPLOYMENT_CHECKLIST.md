# HUMELIX LIMITED Namecheap Deployment Checklist

This file is the deployment reminder for moving HUMELIX LIMITED from preview hosting to Namecheap.

## Deployment principle

GitHub should be the source of truth for the website code.

Use the admin dashboard for normal content updates such as articles, projects, equipment, safety topics, videos, reviews, team profiles, page heroes, engineers and enquiries.

Use GitHub for code/design/feature updates:

1. update locally;
2. test locally;
3. commit changes;
4. push to GitHub;
5. deploy/pull the latest code on Namecheap;
6. run Laravel deployment commands;
7. test the live site.

Avoid editing production code directly inside cPanel File Manager unless it is an emergency fix. Direct edits are easy to lose and hard to track.

## Important reminder

Do not commit local Laravel cache files from development. Files such as:

- `bootstrap/cache/packages.php`
- `bootstrap/cache/services.php`
- local `database/database.sqlite`

are environment/runtime files. On Namecheap, Laravel should regenerate the needed cache files after the server environment is configured.

## Recommended code deployment methods

### Option 1: SSH + Git pull - preferred if available

This is the cleanest workflow.

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Use this if Namecheap provides SSH/cPanel Terminal access.

### Option 2: cPanel Git Version Control

Use cPanel Git Version Control if SSH workflow is not smooth.

Typical flow:

1. Clone the GitHub repository in cPanel.
2. Use "Update from Remote" when new commits are pushed.
3. Use "Deploy HEAD Commit" where available.
4. Run Composer/Laravel commands from cPanel Terminal.

If needed, add a `.cpanel.yml` deployment file later after we confirm the exact folder structure on Namecheap.

### Option 3: Production zip upload - fallback

Use this only if Git deployment is not available.

1. Build/test locally.
2. Zip the production-ready project files.
3. Upload through cPanel File Manager.
4. Extract carefully.
5. Run Composer/Laravel commands if terminal is available.

This is okay for the first upload, but it is not ideal for ongoing updates.

### Option 4: SFTP/FTP upload - last resort

Use SFTP if possible, not plain FTP.

This can work for small changes, but it is easier to miss files or overwrite the wrong thing. It should not be the main workflow for this Laravel project.

## Preferred deployment flow

1. Confirm the Namecheap hosting plan supports the required PHP version for the current Laravel version.
2. Pull or deploy the latest GitHub code into the hosting account.
3. Point the web root to Laravel's `public` directory.
4. Create the production database in cPanel.
5. Import or migrate the database.
6. Configure the production `.env`.
7. Run Composer install.
8. Run Laravel production commands.
9. Confirm uploaded storage is linked and writable.
10. Test the public website, admin login, forms, uploads, email, and SEO routes.

## Production `.env` items to set

Set these values on Namecheap before caching config:

- `APP_NAME="HUMELIX LIMITED"`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-live-domain.com`
- `APP_KEY=base64:...`
- `DB_CONNECTION=mysql`
- `DB_HOST=...`
- `DB_PORT=3306`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`
- `MAIL_MAILER=smtp`
- `MAIL_HOST=...`
- `MAIL_PORT=...`
- `MAIL_USERNAME=...`
- `MAIL_PASSWORD=...`
- `MAIL_FROM_ADDRESS=...`
- `MAIL_FROM_NAME="HUMELIX LIMITED"`
- `QUEUE_CONNECTION=database` if the cPanel queue cron is configured
- `QUEUE_CONNECTION=sync` only as a temporary fallback if no queue worker/cron is running yet
- `SESSION_DOMAIN=.your-live-domain.com` if needed
- `SESSION_SECURE_COOKIE=true` after SSL is active
- `HUMELIX_SUPER_ADMIN_EMAIL=...`
- `HUMELIX_SUPER_ADMIN_PASSWORD=...`

## Commands to run on Namecheap

Run these from the Laravel project root using SSH or cPanel Terminal:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If frontend assets are not already committed or uploaded, build assets before deployment:

```bash
npm install
npm run build
```

On shared hosting, it is usually cleaner to build assets locally or during a controlled deployment, then upload or commit the built `public/build` files.

## Production database guidance

Use MySQL on Namecheap production, not SQLite.

Recommended cPanel steps:

1. Open cPanel -> `MySQL Databases`.
2. Create a database for the website.
3. Create a database user with a strong password.
4. Add the user to the database with the required privileges.
5. Put those values into `.env`:
   - `DB_CONNECTION=mysql`
   - `DB_HOST=localhost` unless Namecheap provides another host
   - `DB_PORT=3306`
   - `DB_DATABASE=...`
   - `DB_USERNAME=...`
   - `DB_PASSWORD=...`
6. Run `php artisan migrate --force`.
7. Import existing production data if we are moving from another database.

## Upload limits

The application validation currently allows:

- Article featured image: 4MB maximum.
- Article PDF attachment: 10MB maximum.

The hosting PHP settings must be equal to or higher than those limits. Recommended production PHP values:

- `upload_max_filesize=15M`
- `post_max_size=25M`
- `memory_limit=512M`
- `max_execution_time=120`
- `max_input_time=120`

`post_max_size` must be larger than one file because it covers the full request: image + PDF + article text + all other form fields.

On Render, these limits are configured in `docker/php-upload.ini`. On Namecheap, set the same values in cPanel/MultiPHP INI Editor before testing uploads.

## Image/media preparation before upload

The website does not auto-convert uploaded images because the client will prepare images manually.

Before uploading through admin:

- Hero/page banner images: about 1920x900 or 1920x1080, ideally under 500KB-900KB.
- Project/article/equipment card images: about 1200x750 or 1000x625, ideally under 300KB-600KB.
- Team/profile images: about 900x1125 or 800x1000, ideally under 250KB-500KB.
- Gallery images: about 1200px wide unless extra detail is required.
- Prefer JPG/WebP for photos.
- Use PNG mainly for transparent logos/graphics.
- Do not upload raw phone/camera images directly; resize and compress them first.
- Keep public videos on YouTube where possible. Use local video upload only for small, necessary files.

## Text encoding/content paste guidance

The website uses UTF-8. When admins paste content from Word, Google Docs, AI tools or websites, smart punctuation can sometimes arrive with bad encoding from the source. The article sanitizer now preserves UTF-8 and repairs common mojibake such as broken em dashes, curly apostrophes, copyright symbols and related pasted-text artifacts.

Before final publishing, preview long articles and correct any remaining odd copied characters manually.

## Production cache and hosting performance

After the final `.env` is correct and migrations are complete, run:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Namecheap/cPanel performance notes:

- Enable HTTPS before setting `SESSION_SECURE_COOKIE=true`.
- Use MySQL for production data.
- Keep uploaded images compressed before upload.
- Prefer YouTube embeds for videos.
- Keep public page navigation fast by using Laravel production caches: `config:cache`, `route:cache`, `view:cache`, and `event:cache` where supported.
- Browser caching and gzip compression rules for static assets are included in `public/.htaccess`; keep them when uploading to Namecheap.
- Keep hero images eager/high-priority, but lazy-load normal card, gallery, team, project, article and equipment images.
- Light public-page link prefetching is included in `public/js/uch.js` so major same-site routes feel faster after hover/touch. It intentionally skips admin, logout, files, phone, email and external links.
- Consider Cloudflare/CDN later for global static asset delivery and extra caching/security.
- If LiteSpeed Cache or cPanel-level caching is available on the selected Namecheap server, enable it carefully after launch testing.
- Do not cache admin pages, login pages, forms, newsletter submission, review submission, or any POST/action route.
- Re-test contact forms, admin login, newsletter, reviews, uploads and notifications after enabling any hosting-level cache.

## Queue setup for newsletter article emails

The website queues new-article newsletter emails so publishing an article does not have to send every subscriber email inside the browser request.

Recommended Namecheap shared-hosting setup:

1. Set `QUEUE_CONNECTION=database` in the production `.env`.
2. Run `php artisan migrate --force` so the `jobs` and `failed_jobs` tables exist.
3. Add a cPanel cron job that runs every 5 minutes and drains the queue safely.

Namecheap shared-hosting note:

- Namecheap shared servers allow cron jobs, but cron intervals below 5 minutes are not allowed on shared servers.
- Do not use `* * * * *` on Stellar Plus shared hosting.
- Use `*/5 * * * *` for the Laravel queue worker.

How to create the cron job in Namecheap cPanel:

1. Log in to Namecheap.
2. Open the hosting package.
3. Open cPanel.
4. Go to `Advanced` -> `Cron Jobs`.
5. Set the cron email field only if you want cron output emails during testing.
6. Under `Add New Cron Job`, choose `Once Per Five Minutes` or manually set:
   - Minute: `*/5`
   - Hour: `*`
   - Day: `*`
   - Month: `*`
   - Weekday: `*`
7. Paste the Laravel queue worker command in the `Command` field.
8. Click `Add New Cron Job`.
9. After testing, make sure the command output is redirected to a log file or `/dev/null` so cron emails do not fill the mailbox.

Example cron command, adjust the project path and PHP binary to match the cPanel account:

```bash
cd /home/CPANEL_USER/humelix && /usr/local/bin/php artisan queue:work --stop-when-empty --queue=default --tries=3 --max-time=240 >> storage/logs/queue.log 2>&1
```

Notes:

- If the PHP binary differs, use the cPanel Terminal `which php` result or the MultiPHP path provided by Namecheap.
- `--stop-when-empty` is shared-hosting friendly because the process exits after it clears current jobs.
- `--max-time=240` keeps the worker below the 5-minute cron interval so overlapping queue workers are less likely.
- If this cron is not configured yet, keep `QUEUE_CONNECTION=sync` temporarily so emails still send, but article publishing can be slower when subscriber volume grows.
- On a future VPS, replace the cron-drained queue with Supervisor or a proper long-running worker.

## Email image URLs

Email templates use absolute public image URLs for the HUMELIX logo and article images. This is required because email clients cannot reliably load relative paths such as `/images/...` or local URLs such as `127.0.0.1`.

Production requirements:

- Set `APP_URL` to the final HTTPS domain, for example `https://humelix.com`.
- In admin settings, keep the company website URL set to the same public domain.
- Keep email logo assets inside `public/images/brand/`.
- Ensure uploaded article images are publicly reachable through `public/storage/...`.
- If email images do not show during localhost or ngrok testing, re-test after the site is on the stable public domain.
- Some email apps still block images by default; the emails keep HUMELIX LIMITED text as a fallback.

## Client Job Portal on shared hosting

The first Client Job Portal / Job Conversations feature is built in a shared-hosting safe way while HUMELIX is on Namecheap Stellar Plus or Stellar Business.

Recommended hosting-safe rules:

- store job conversations in MySQL using normal database tables;
- use secure random portal tokens for client links, never predictable job IDs as the only access control;
- keep client portal pages lightweight;
- use normal form submissions and lightweight polling instead of WebSockets in the first version;
- polling uses the same conservative style as admin notifications:
  - about 15 seconds normally;
  - about 10 seconds only when an active conversation is open;
  - pause or slow polling when the browser tab is hidden;
- send optional client message emails through the configured mail queue where possible;
- keep WhatsApp, SMS, SSE, WebSockets, mobile app and full API-based real-time communication as future upgrades for VPS/cloud hosting.

The private agreed job amount feature is admin-only in the first version. It is stored as job/enquiry commercial documentation, not shown on public pages or the client portal until HUMELIX intentionally approves client-visible quotes/invoices.

Client Job attachment rules for shared hosting:

- attachments are allowed only inside private Client Job conversations;
- accepted types: JPG, JPEG, PNG, WebP, PDF, DOC and DOCX;
- max 3 files per message;
- max 10MB per file;
- do not allow local video uploads while on shared hosting;
- ask clients/admins to share video links instead of uploading video files;
- attachments are stored on Laravel's private local disk and served through authorized routes;
- ensure `storage/app/private` is writable on Namecheap;
- keep regular hosting backups enabled so private job attachments are included in site backups;
- future cleanup/media-management tooling can be added if attachment volume grows.

## If SSH/cPanel Terminal is unavailable

Use one of these alternatives:

1. Ask Namecheap support to enable SSH/cPanel Terminal.
2. Run Composer and build locally, then upload the generated vendor/build files carefully.
3. Use a temporary, protected deployment script only if absolutely necessary, then delete it immediately after deployment.

Do not leave any public deployment scripts online.

## Post-deployment tests

After deployment, check:

- `/`
- `/services`
- `/projects`
- `/resources`
- `/safety`
- `/reviews`
- `/contact`
- `/sitemap.xml`
- `/robots.txt`
- `/admin/login`
- `/admin`
- `/admin/enquiries`
- `/admin/engineers`
- `/admin/articles`
- `/admin/safety`
- `/admin/settings`

Also test:

- Contact form submission
- Newsletter subscription
- Admin login/logout
- Article creation
- Image upload
- Video link embedding
- Engineer assignment email
- Email delivery
- Admin notification polling
- Mobile layout

## Future hosting notes

- Start on Namecheap Stellar Plus if budget is the priority.
- Move to Stellar Business for stronger shared-hosting performance, AutoBackup, and better production safety.
- Move to VPS later if the site needs long-running workers, WebSockets, heavier video handling, or stronger server control.
- Move gradually toward a Laravel API architecture later if HUMELIX needs mobile apps, engineer apps, customer portals, external integrations or a separate frontend. The current Blade website should remain the stable base until those needs are real.
- Keep local video uploads limited; prefer YouTube links for video content.
- Add proper image optimization and thumbnails to protect shared-hosting storage and speed.
