# HUMELIX SYSTEMS Laravel Platform

Production-ready Laravel 12 website and admin platform for HUMELIX SYSTEMS, a global engineering services company covering HVAC, solar, electrical, maintenance, equipment/vendor support and home appliance installation.

## Included modules

- Public website: Home, About, Services, Industries, Projects, Safety, Team, Branches, Resources, Careers, Equipment, Videos, Contact.
- Legal pages: Privacy Policy, Terms, Cookie Policy and Accessibility Statement.
- SEO: admin-managed metadata, Open Graph/Twitter tags, robots directives, JSON-LD support, `/sitemap.xml` and `/robots.txt`.
- Lead capture: contact form and floating chat enquiry assistant.
- Admin platform: dashboard, enquiries, projects, services foundation, branches, jobs, equipment, videos, safety foundation, articles/resources, media, reviews, users/roles, site settings and SEO settings.
- Role foundation: Super Admin, Content Editor, Service Manager, Country Admin, Support Agent and Safety Officer.

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

Development seed admin:

```txt
Email: admin@humelix.com
Password: password123
```

Change this password immediately before deployment. Treat it as development-only.

## Verification

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

## Production deployment summary

- Set `APP_ENV=production`.
- Set `APP_DEBUG=false`.
- Set `APP_URL=https://humelix.com` or the final production domain.
- Configure real MySQL and SMTP credentials in `.env`; never commit secrets.
- Run `composer install --no-dev --optimize-autoloader`.
- Run `php artisan migrate --force`.
- Run `php artisan storage:link`.
- Run `npm.cmd run build` locally or on the server build environment.
- Run Laravel cache commands after `.env` is final.
- Confirm `/sitemap.xml`, `/robots.txt`, `/privacy-policy`, `/terms`, `/cookie-policy`, `/accessibility`, `/admin/login` and the contact form work.

Full deployment notes are in [docs/HUMELIX_DEPLOYMENT_GUIDE.md](docs/HUMELIX_DEPLOYMENT_GUIDE.md).

## Security notes

- Admin routes are protected by authentication, admin role checks and module permissions.
- Public contact and chat endpoints are rate limited.
- Uploads reject SVG, PHP, JS, HTML, EXE and script files.
- Images are limited to JPG/JPEG/PNG/WebP.
- Videos are limited to MP4/WebM/MOV where video upload is supported.
- Equipment/vendor remains request/quote based only; no cart, checkout, payment or e-commerce workflow exists.
