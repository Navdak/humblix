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

Use the seeded admin login for preview testing:

```text
Email: admin@humelix.com
Password: password123
```

Change these credentials before any real production launch.
