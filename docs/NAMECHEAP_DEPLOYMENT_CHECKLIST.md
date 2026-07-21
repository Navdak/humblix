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

### Option 1: SSH + Git pull — preferred if available

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
2. Use “Update from Remote” when new commits are pushed.
3. Use “Deploy HEAD Commit” where available.
4. Run Composer/Laravel commands from cPanel Terminal.

If needed, add a `.cpanel.yml` deployment file later after we confirm the exact folder structure on Namecheap.

### Option 3: Production zip upload — fallback

Use this only if Git deployment is not available.

1. Build/test locally.
2. Zip the production-ready project files.
3. Upload through cPanel File Manager.
4. Extract carefully.
5. Run Composer/Laravel commands if terminal is available.

This is okay for the first upload, but it is not ideal for ongoing updates.

### Option 4: SFTP/FTP upload — last resort

Use SFTP if possible, not plain FTP.

This can work for small changes, but it is easier to miss files or overwrite the wrong thing. It should not be the main workflow for this Laravel project.

## Preferred deployment flow

1. Confirm the Namecheap hosting plan supports the required PHP version for the current Laravel version.
2. Pull or deploy the latest GitHub code into the hosting account.
3. Point the web root to Laravel’s `public` directory.
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
- Move to VPS later if the site needs queues, long-running workers, WebSockets, heavier video handling, or stronger server control.
- Keep local video uploads limited; prefer YouTube links for video content.
- Add proper image optimization and thumbnails to protect shared-hosting storage and speed.
