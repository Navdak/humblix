# HUMELIX LIMITED Laravel Platform

Production-ready Laravel 12 website and admin platform for **HUMELIX LIMITED**, a global engineering services company covering HVAC, solar, electrical, maintenance, equipment/vendor support and home appliance installation.

The platform is designed as both a polished public company website and a private operations dashboard for managing content, enquiries, engineers, assignments, client communication and early-stage job documentation.

## Included public website

- Public pages: Home, About, Services, Industries, Projects, Safety, Team, Branches, Resources, Careers, Equipment, Videos and Contact.
- Legal pages: Privacy Policy, Terms, Cookie Policy and Accessibility Statement.
- Sticky responsive navigation, mobile menu, floating chat CTA and back-to-top control.
- Editable page hero content/images through admin-managed page heroes.
- Generated/client-ready imagery with admin upload fallbacks for future real media.
- Public contact, newsletter and review forms submit without full page refresh when JavaScript is available, while retaining normal form-submit fallback.
- Service divisions aligned with the Humelix blueprint:
  - Humelix HVAC Installation
  - Humelix Solar Installation
  - Humelix Electrical & Maintenance
  - Humelix Vendor / Equipment
  - Home Appliance Installation
- Equipment/vendor remains request/quote based only; no cart, checkout, payment or e-commerce workflow exists.

## Admin platform modules

- Dashboard with stats, charts, alerts, quick actions and visitor tracking foundation.
- Enquiries/lead management with status, notes, assignment, site address fields and agreed job amount tracking.
- Engineers directory with specialty/field, contact details and assignment support.
- Client job portal with private token links, job updates, client/admin chat and protected media/document attachments.
- Projects/case studies.
- Services foundation.
- Branches/locations.
- Jobs/careers.
- Equipment catalogue.
- Videos, including YouTube/YouTube Shorts support and automatic thumbnail fallback.
- Safety framework CRUD with optional YouTube video support.
- Articles/resources with categories, filtering, related articles, PDF attachment support and optional article video.
- TinyMCE article editor support, including inline media guidance and media-library "copy URL" workflow.
- Newsletter subscription and subscriber management, with no-refresh public signup fallback.
- Reviews/testimonials.
- Media library.
- Page heroes.
- Site settings.
- SEO settings.
- Users, roles and permissions.

## Roles and permissions

The admin area uses a protected role model:

- **Technical Super Admin / Developer**
  - Full access.
  - Protected recovery account.
  - Cannot be deleted, deactivated, demoted or edited by another admin.
  - Can manage site settings, SEO, protected developer credit, roles, permissions and all operational modules.

- **Company Owner / CEO**
  - Second-highest access.
  - Can manage company content, operations, users and lower admin roles.
  - Cannot access or modify the protected developer account/role.

- **Lower admins**
  - Access depends on module permissions.
  - Can be allowed to create/update/upload/assign where needed.
  - Delete access is restricted by default to Developer and CEO.

Admin profiles support display names and profile photos. The dashboard greeting is time-aware and uses the admin's saved name when available.

## Enquiries, engineers and client job portal

Recent operations features include:

- Structured enquiry review and status updates.
- Engineer directory separate from public team members.
- Engineer assignment dropdown from the engineer list.
- Optional assignment email alert when assigning an engineer.
- Editable job allocation contact details for engineer emails.
- Site address fields for enquiry/job location documentation.
- Engineer assignment emails instruct engineers to contact Humelix before visiting the client/site.
- Admin-only agreed job amount/currency/notes for private job financial documentation.
- Private client job links generated from secure tokens.
- Client/admin chat attached to a specific job/enquiry.
- Chat sends messages without refreshing the whole page, while keeping normal form submission as fallback.
- Chat polling is designed for shared hosting rather than forced page refreshes.
- Client-facing admin replies display as **Humelix Project Team** while real admin names remain visible internally for audit/history.
- Private chat attachments saved to protected storage and served through authorized routes.

## Articles, resources and newsletters

Resource features include:

- Article categories such as HVAC, solar, electrical, safety, maintenance, vendor/equipment and company resources.
- Public resource filtering.
- Related articles on article detail pages.
- Optional PDF download per article.
- Optional article video from YouTube/YouTube Shorts.
- Inline article images through TinyMCE/media-library URLs.
- Admin guidance for recommended article length and image usage.
- Recommended maximum article length: **8,000 words**. Longer guides should be split manually into a series for better SEO, readability and performance.
- Newsletter subscription without requiring double confirmation.
- Newsletter welcome email and new-article email templates.
- Subscriber management in admin, controlled through role permissions.
- Newsletter links should point directly to the published article detail page.

## SEO and branding

SEO is built into the platform:

- Admin-managed meta title, description, canonical, robots and social metadata.
- Open Graph/Twitter card support.
- Default OG image support.
- Favicon/logo assets.
- `/sitemap.xml`
- `/robots.txt`
- Organization schema.
- Article schema.
- Breadcrumb-friendly structure.
- Public article HTML sanitization.

Final production SEO should be retested on the real domain after hosting.

## Media and upload policy

- Public images should use uploaded/admin images first, then generated fallback assets.
- Below-the-fold images are lazy-loaded where practical.
- Hero images remain eager/high priority.
- The client will manually compress/prepare final images before upload.
- YouTube is recommended for videos, especially on shared hosting.
- Local video uploads should stay limited to avoid shared-hosting storage/performance issues.
- Private client/job files are stored separately from public media.
- SVG, PHP, JS, HTML, EXE and script-like files are rejected.
- Images are limited to JPG/JPEG/PNG/WebP.
- Video upload support is limited to safe formats where enabled.

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm.cmd install
npm.cmd run build
php artisan serve
```

Admin URL:

```txt
/admin/login
```

Development seed admin fallback:

```txt
Email: admin@humelix.com
Password: password123
```

This fallback is development-only. In production, set `HUMELIX_SUPER_ADMIN_EMAIL` and `HUMELIX_SUPER_ADMIN_PASSWORD` before running seeders.

## Important environment variables

Core:

```env
APP_NAME="HUMELIX LIMITED"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
ASSET_URL=
```

Protected developer account:

```env
HUMELIX_SUPER_ADMIN_EMAIL=
HUMELIX_SUPER_ADMIN_PASSWORD=
```

Mail:

```env
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="HUMELIX LIMITED"
```

Queue:

```env
QUEUE_CONNECTION=database
```

TinyMCE:

```env
TINYMCE_API_KEY=
```

Never commit real `.env` secrets.

## Verification

Run checks proportional to the change:

```bash
php artisan optimize:clear
php artisan migrate
php artisan view:cache
php artisan route:list --except-vendor
php artisan test
composer audit
npm.cmd run build
npm audit --omit=dev
```

For production-readiness checks, also verify:

- `/`
- `/services`
- `/projects`
- `/resources`
- `/videos`
- `/contact`
- `/admin/login`
- `/admin`
- `/sitemap.xml`
- `/robots.txt`
- article detail pages
- service detail pages
- private client job links
- admin mobile views
- email templates
- upload/storage paths

## Production deployment summary

- Set `APP_ENV=production`.
- Set `APP_DEBUG=false`.
- Set `APP_URL=https://humelix.com` or the final production domain.
- Set `ASSET_URL` to the final domain when required for email/public asset links.
- Set `HUMELIX_SUPER_ADMIN_EMAIL` and a strong `HUMELIX_SUPER_ADMIN_PASSWORD`.
- Configure real MySQL/MariaDB credentials in `.env`.
- Configure real SMTP credentials in `.env`.
- Run `composer install --no-dev --optimize-autoloader`.
- Run `php artisan migrate --force`.
- Run `php artisan storage:link`.
- Run `npm.cmd run build` locally or on the server build environment.
- Run Laravel cache commands after `.env` is final.
- Confirm admin login, sessions, forms, uploads, emails, sitemap, robots, social previews and client job portal links.

Full deployment notes are in [docs/NAMECHEAP_DEPLOYMENT_CHECKLIST.md](docs/NAMECHEAP_DEPLOYMENT_CHECKLIST.md).

## Queue and cron notes

Email/newsletter sending should use the database queue where possible. On Namecheap/cPanel shared hosting, use Cron to run the Laravel queue worker periodically.

Typical cPanel cron pattern:

```bash
/usr/local/bin/php /home/USERNAME/path-to-project/artisan queue:work --stop-when-empty --tries=3 --timeout=60
```

Recommended interval: every 5 minutes for early production usage.

Render preview can use sync fallback or a separate worker depending on the deployment setup. Render SMTP may time out on Gmail/SMTP ports, so final email should be retested on Namecheap or with a transactional mail provider.

## Live update strategy

The admin currently uses a safe hybrid notification approach:

- AJAX polling checks every 15 seconds normally.
- It can speed up while the dashboard or notification dropdown is active.
- It slows/pauses when the browser tab is hidden.
- It avoids forced page refreshes so admins do not lose form work.

Future hosting upgrades can add Server-Sent Events, Laravel Reverb/WebSockets or push notifications while keeping polling as a fallback for shared hosting.

## Security notes

- Admin routes are protected by authentication, admin role checks and module permissions.
- Protected developer account rules prevent accidental lockout.
- Public article HTML is sanitized before rendering.
- Security headers are applied at application level.
- Docker/PHP deployment hardening disables PHP version exposure where configured.
- Public contact, chat and upload endpoints are rate limited where appropriate.
- Private client/job attachments should be served only through authorized routes.
- Do not expose secrets in git, screenshots or public documentation.

## Future roadmap

Documented future upgrades include:

- API-first backend evolution.
- React/Next.js public frontend when scale/UI needs justify it.
- True real-time chat/notifications with SSE or WebSockets on stronger hosting.
- WhatsApp Business API/SMS alerts.
- Invoice generation.
- Receipt tracking.
- Payment reminders.
- Revenue dashboard and job value reports.
- Excel/PDF exports.
- Media cleanup manager.
- Cloud/object storage for uploads.
- Transactional email provider.
- VPS/cloud migration when shared hosting limits are reached.

Full project notes and future plans are maintained in [docs/HUMELIX_PROJECT_DOCUMENTATION.md](docs/HUMELIX_PROJECT_DOCUMENTATION.md).
