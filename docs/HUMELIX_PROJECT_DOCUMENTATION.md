# HUMELIX LIMITED Project Documentation

Last updated: 2026-07-17

## Project overview

HUMELIX LIMITED is a Laravel 12 website and admin platform for a professional engineering company covering HVAC, solar, electrical, maintenance, vendor/equipment and home appliance installation services.

The application currently supports:

- a public marketing website;
- lead enquiry capture through contact and chat forms;
- project, team, branch, equipment, video, article and review management;
- SEO settings and page hero management;
- visitor analytics foundation;
- role-based admin access;
- protected developer super-admin recovery access;
- Render preview deployment and future Namecheap production hosting preparation.

## Public website

The public website includes:

- Homepage with hero, service divisions, project highlights, reviews, team growth and CTAs.
- Services landing page and service detail pages.
- Industries, projects, safety, team, branches, careers, equipment, resources/articles, videos and legal pages.
- Contact/enquiry form with project details, preferred contact method and upload support.
- Chat assistant enquiry flow.
- Sticky public navigation and back-to-top behavior.
- Generated image assets used across the site until final client photography is supplied.
- SEO metadata, sitemap and robots support.

## Admin platform

The admin platform includes:

- Dashboard KPIs and charts.
- Enquiry management.
- Project management.
- Branch management.
- Service foundation page.
- Equipment catalogue management.
- Video management with local upload and YouTube URL support.
- Resources/articles with TinyMCE editor.
- Media library.
- Review moderation.
- Safety content foundation.
- Team and careers management.
- Page hero editing.
- Site settings.
- SEO settings.
- Users and roles.

## Roles and permissions

The admin uses role-based permissions backed by database tables.

Primary roles:

- Technical Super Admin: full system access.
- Company Owner: broad business/content access without developer-only recovery controls.
- Content Editor: resources, media, videos and reviews.
- Service Manager: services, enquiries, projects, equipment and videos.
- Country Admin: branches, enquiries, projects and team content.
- Support Agent: client enquiries and review support.
- Safety Officer: safety content and safety video oversight.

The Technical Super Admin can manage role permissions from the admin area.

## Protected developer account

The developer account is protected for production safety:

- it remains a Super Admin;
- it cannot be deleted by other admins;
- it cannot be demoted or deactivated by other admins;
- it acts as a recovery account if the client/admin team locks themselves out.

The protected account credentials are configured through environment variables, not hardcoded in the repository.

## Page heroes

Page Heroes allow approved admins to edit hero content for static public pages.

Each page hero supports:

- eyebrow;
- title;
- subtitle;
- replacement image upload;
- fallback to generated image if no uploaded image exists.

Uploaded replacement images should replace the active hero image. Old uploaded files should be cleaned up during replacement to avoid storage bloat.

## Admin profile photos

Admin users can have profile photos uploaded during account creation or edited later.

If no photo exists, the interface falls back to the user’s initial. Uploaded photos are displayed in the admin topbar, sidebar mini profile and user list.

## Hybrid live admin notifications

The current live-update system uses safe AJAX polling rather than full page refreshes or always-open server streams.

Current behavior:

- admin notification bell checks for updates automatically;
- polling runs every 15 seconds normally;
- polling becomes 10 seconds when the dashboard is active or the notification dropdown is open;
- polling slows to 60 seconds when the browser tab is hidden;
- returning to the tab triggers an immediate check;
- new updates appear as professional toast cards;
- the notification dropdown shows the latest updates;
- notifications are permission-aware;
- read/unread status is tracked per admin user;
- enquiry list pages show an “Update list” prompt when new enquiry updates exist;
- the enquiry list is not refreshed automatically while an admin is working.

This gives the admin a near-live experience without interrupting form entry or relying on hosting features that may be unstable on shared hosting.

## Future live-update plan

When the site moves to hosting that supports stable streaming or workers, we can upgrade the notification layer to:

- Server-Sent Events (SSE);
- Laravel Reverb/WebSockets;
- queue-driven notifications;
- browser push notifications if needed.

The current polling system should remain as a fallback even after SSE/WebSockets are added.

## Hosting and database plan

Current preview hosting:

- Render web service for client preview.
- Environment variables stored in Render dashboard.
- Render preview database can be temporary depending on selected service/database setup.

Future Namecheap hosting:

- use Namecheap/cPanel MySQL database;
- import migrated production database;
- configure `.env` with Namecheap database credentials;
- run Laravel migrations;
- run storage link or equivalent public storage setup;
- confirm file uploads and public asset paths;
- configure final domain, SSL and production cache.

## SEO readiness

Current SEO foundation includes:

- route-level SEO metadata;
- editable SEO settings;
- sitemap generation;
- robots.txt;
- favicon and brand icon assets;
- Open Graph image support.

The current OG asset can be replaced when the client supplies a final approved social sharing image.

## Maintenance rule

After every meaningful product change, this document should be updated so future work does not depend only on memory or chat history.
