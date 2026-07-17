# Render Preview Deployment

This setup is for a temporary client preview before the main production hosting is moved to Namecheap.

## Render service

- Service type: Web Service
- Runtime: Docker
- Repo: `https://github.com/Navdak/humblix`
- Branch: `main`
- Dockerfile path: `./Dockerfile`
- Instance type: Free

## Environment variables

Set these in Render before deploying:

```env
APP_NAME=HUMELIX LIMITED
APP_ENV=production
APP_KEY=base64:REPLACE_WITH_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://YOUR-RENDER-SERVICE.onrender.com
ASSET_URL=https://YOUR-RENDER-SERVICE.onrender.com
HUMELIX_SUPER_ADMIN_EMAIL=YOUR_ADMIN_EMAIL
HUMELIX_SUPER_ADMIN_PASSWORD=YOUR_STRONG_ADMIN_PASSWORD

LOG_CHANNEL=stderr
LOG_LEVEL=warning

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

CACHE_STORE=file
SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
MAIL_MAILER=log
RUN_SEEDERS=true
```

Generate `APP_KEY` locally with:

```powershell
C:\xampp\php\php.exe artisan key:generate --show
```

## Preview limitations

Render Free web services use an ephemeral filesystem. This means local SQLite data and uploaded files can be lost when the service restarts, spins down, or redeploys.

For this preview, the container creates `database/database.sqlite`, runs migrations, and seeds demo content on startup. This is suitable for showing the client the website and admin flow, not for final production data.

## After deploy

Use the protected seeded admin login you configured in Render:

```text
Email: value of HUMELIX_SUPER_ADMIN_EMAIL
Password: value of HUMELIX_SUPER_ADMIN_PASSWORD
```

Do not use the local development fallback credentials for a public preview or production launch.
