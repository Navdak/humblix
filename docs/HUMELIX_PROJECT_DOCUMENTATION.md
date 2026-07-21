# HUMELIX LIMITED Project Documentation

Last updated: 2026-07-21

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
- Video management with local upload, standard YouTube links, `youtu.be` links and YouTube Shorts URL support.
- Resources/articles with TinyMCE editor.
- Media library.
- Review moderation.
- Safety Topics management.
- Team and careers management.
- Page hero editing.
- Site settings.
- SEO settings.
- Users and roles.

## Roles and permissions

The admin uses role-based permissions backed by database tables.

Primary roles:

- Technical Super Admin: full system access.
- Company Owner: broad business/content access, page hero editing and normal admin user management without developer-only recovery controls.
- Content Editor: resources, media, videos and reviews.
- Service Manager: services, enquiries, projects, equipment and videos.
- Country Admin: branches, enquiries, projects and team content.
- Support Agent: client enquiries and review support.
- Safety Officer: safety content and safety video oversight.

The Technical Super Admin can manage role permissions from the admin area. Company Owner can create, update, deactivate and delete normal admin users only. Company Owner cannot access the role-permission editor, cannot assign protected roles, and cannot view or modify Technical Super Admin, protected developer recovery or other Company Owner accounts.

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

## Safety Topics

Safety Framework content is admin-managed instead of being only static page copy.

The Safety module supports:

- add, edit and delete Safety Framework topics;
- title, slug, category, short description, summary checklist points and full read-more content;
- draft/published status and sort order;
- topic image upload and replacement;
- generated safety image fallback when no uploaded image exists;
- optional safety video URL using YouTube, YouTube Shorts, Vimeo, MP4 or WebM;
- video placement on the public safety read-more page: after intro, middle of content, or end of content;
- CTA label and CTA URL per topic.

The public `/safety` page lists published safety topics. The public `/safety/{slug}` page shows the topic image, full content, summary points, optional video, and CTA.

Delete behavior follows the production admin rule: Technical Super Admin and Company Owner can delete records; lower admins with Safety access can add/edit but cannot delete.

## Conversion and project case-study layer

Phase B focuses on turning interested visitors into clearer service enquiries without overbuilding a CTA management system.

Current behavior:

- broad pages such as Home, About, Team, Contact, Footer and the main navigation keep formal/general CTAs such as Request Service, Get a Quote, Contact HUMELIX and WhatsApp Us;
- specific service pages use targeted CTAs:
  - HVAC: Request HVAC Assessment;
  - Solar: Request Solar Site Assessment;
  - Electrical/Maintenance: Request Electrical Inspection or Book Maintenance Visit;
  - Vendor / Equipment: Request Equipment Quote;
  - Home Appliance: Request Home Appliance Installation;
- project detail pages use Request Similar Project CTAs that prefill the enquiry pathway where possible;
- article detail pages use Ask About This Resource for resource-related enquiries.

Project detail pages are structured as professional case studies using existing editable project fields:

- client challenge;
- solution delivered;
- result/outcome;
- equipment/materials used;
- safety approach/controls;
- duration, sector, client type, location and service division;
- related videos where linked from the Video Library.

Admin project create/edit forms explain these fields so admins can publish stronger case-study content without changing code.

## Future Maintenance / Aftercare page

Maintenance / Aftercare is intentionally documented as a future build, not part of the current Phase B implementation.

Planned future scope:

- public Maintenance / Aftercare page;
- editable Page Hero support;
- admin-editable maintenance/aftercare content;
- preventive maintenance plan sections;
- maintenance checklist/download support;
- maintenance-specific CTA flow such as Book Maintenance Visit, Request Aftercare Support and Emergency Support.

This should be built as a designed page with controlled editable fields, not a free-form page builder, so the design remains consistent.

## Admin profile photos

Admin users can have profile photos uploaded during account creation or edited later.

If no photo exists, the interface falls back to the user’s initial. Uploaded photos are displayed in the admin topbar, sidebar mini profile and user list.

## Video handling

The Video Library supports:

- standard YouTube watch URLs;
- short `youtu.be` URLs;
- YouTube Shorts URLs;
- Vimeo links;
- direct MP4/WebM links;
- local MP4/WebM/MOV uploads.

YouTube and YouTube Shorts links are recommended for shared hosting because YouTube handles playback, compression, streaming bandwidth and device quality. Shorts are displayed with a vertical-friendly public player layout.

## Resource/article handling

Resources support category-based organization for HVAC, solar, electrical, maintenance, vendor/equipment, safety, company news and general articles.

Article publishing includes:

- admin category selection during create/edit;
- public resource filtering by category using `/resources?category=...`;
- category badges on public article cards and detail pages;
- sanitized rich text article content;
- an 8,000-word maximum for web article content, with admin guidance to split longer guides manually or attach a PDF;
- optional PDF attachments up to 10MB for long guides, manuals, brochures, checklists and downloadable resources;
- optional article video embeds using YouTube, YouTube Shorts, Vimeo, MP4 or WebM URLs;
- article video placement controls for after-intro, middle-of-article or end-of-article display;
- optional article video title and caption fields for clearer public presentation;
- related resources below each article detail page, prioritizing the same category and falling back to latest resources when needed.

## Newsletter and article email updates

The website includes a newsletter foundation for sending new resource/article updates to visitors who subscribe.

Current behavior:

- public signup appears on the Resources listing and article detail pages;
- visitors provide an email address and consent before subscribing;
- subscriptions use single opt-in, so the visitor is subscribed immediately after submitting the form with consent;
- welcome emails are branded with the HUMELIX LIMITED logo, website link and professional styling;
- confirmed subscribers receive a branded email when a new article is published;
- each published article is marked after notification so subscribers are not emailed repeatedly for normal edits;
- article emails include the article title, category, excerpt, website link, resource CTA and unsubscribe link;
- admins with newsletter permission can view subscribers, pending confirmations and unsubscribed contacts;
- admins can mark a subscriber as unsubscribed, restore a subscriber, or delete a subscriber record.
- newsletter access is a role permission; company owner receives it by default, super admin always has access, and other roles can be granted access later from Role Permissions.
- newsletter emails use the editable `Company Website URL` setting when present and fall back to `APP_URL` when it is empty.

Current email provider plan:

- Gmail SMTP can be used during preview/testing with an app password stored only in `.env` or hosting environment variables;
- when the final Namecheap/domain email is ready, update the mail environment variables to use the Namecheap/cPanel mailbox SMTP details;
- never commit real SMTP passwords or app passwords to GitHub.

Future newsletter improvements:

- queue newsletter sends for very large subscriber lists;
- add optional campaign history and resend controls;
- add richer subscriber export/import if marketing workflows require it;
- integrate a dedicated email marketing provider if the audience grows beyond simple transactional updates.

## Admin delete policy

The admin platform follows a protected delete policy:

- Technical Super Admin / Developer can create, edit, upload, publish and delete records.
- Company Owner / CEO can create, edit, upload, publish and delete normal business records.
- Lower admin roles can create, edit, post, upload and publish only where their module permissions allow.
- Lower admin roles cannot delete website/admin records.
- Delete buttons are hidden from lower admin roles in admin list pages.
- Backend middleware blocks direct admin `DELETE` requests from lower admin roles with a 403 response.
- The protected developer recovery account remains undeletable, even by Company Owner.

This prevents accidental or unauthorized content loss while still allowing operational admins to do daily publishing work.

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
- Organization, LocalBusiness/ProfessionalService, WebSite and Breadcrumb structured data.
- Article structured data for public resource detail pages.
- VideoObject structured data when an article includes an optional embedded video.
- Review-page structured data for approved public reviews.

The current OG asset can be replaced when the client supplies a final approved social sharing image.

## Review flow

The public Reviews page supports two trust paths:

- an editable Google review CTA powered by the `Google Review URL` setting;
- a website review submission form.

Website-submitted reviews are not published immediately. They are stored as pending reviews and must be approved by permitted admins before appearing publicly. Review admins can approve/unapprove reviews and add a public admin response. Delete remains protected by the admin delete policy, so only Technical Super Admin / Developer and Company Owner / CEO can delete review records.

When a new website review is submitted, admins with review permission receive an admin notification.

## Public spam protection

Public forms currently use layered basic protection:

- Laravel rate limiting on contact, chat, newsletter and review submission routes;
- a hidden honeypot field on public forms;
- a minimum form-completion timing check to stop instant bot submissions.

This is intentionally lightweight for shared hosting. If spam increases later, add Cloudflare Turnstile or another CAPTCHA-style provider.

## Maintenance rule

After every meaningful product change, this document should be updated so future work does not depend only on memory or chat history.
