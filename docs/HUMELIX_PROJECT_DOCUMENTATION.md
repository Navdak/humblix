# HUMELIX LIMITED Project Documentation

Last updated: 2026-07-24

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
- Internal engineer directory and enquiry assignment.
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

Role permissions also include:

- Engineers: manage the internal engineer/personnel directory.
- Assign Engineers: assign an engineer to a lead enquiry.

These permissions can be granted to lower admin roles by the Technical Super Admin where operationally necessary. Delete behavior remains protected: only Technical Super Admin and Company Owner can delete records.

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

## Internal engineers and lead assignment

The Engineers admin module is an internal operations directory, separate from the public Team Members module.

Engineer records support:

- name;
- role/title;
- field of work;
- phone;
- WhatsApp;
- email;
- region/location;
- availability status;
- optional photo;
- optional linked admin user;
- internal notes;
- sort order.

Engineers do not appear on the public Team page unless a separate Team Member record is created and marked visible. This keeps the company free to store full field personnel records internally without exposing every engineer publicly.

Enquiry assignment behavior:

- enquiry detail pages use an engineer dropdown instead of a free-text assignment field;
- the enquiry list shows the assigned engineer where available;
- old text-only assignment data remains as legacy display fallback;
- public enquiries collect a general project area/city and can optionally include a project address, landmark or access note;
- admin enquiry details include a Confirmed Site Address field so admins can add/correct the address after contacting the client;
- engineer assignment emails use the Confirmed Site Address first, then fall back to the submitted site address/project location;
- admins with Assign Engineers permission can assign/reassign leads;
- admins without Assign Engineers permission can still view assignment details but cannot change them;
- when assigning, the admin can choose whether to email the engineer immediately;
- if email alert is unchecked, the company can contact the engineer manually by phone/WhatsApp.
- engineers are instructed not to visit the client site until HUMELIX Operations confirms schedule, exact location, client readiness and safety/material requirements.

Engineer assignment contact details:

- editable from the Engineers admin page by Technical Super Admin and Company Owner;
- stored separately from public website contact details;
- supports operations/team name, phone, WhatsApp, email and instruction note;
- shown in engineer assignment emails so field personnel know who to contact before any site visit.

Future engineer workflow improvements:

- WhatsApp assignment alerts;
- SMS assignment alerts;
- engineer workload dashboard;
- assignment history/activity log;
- technician portal or mobile view for “my assigned jobs”;
- push notifications when the hosting stack supports it safely.

## Client Job Portal and job conversation system

Status: first shared-hosting-safe version implemented.

Confirmed enquiries can now be turned into private client job portals. The goal is to let HUMELIX, the client and internal admins document job communication while work is ongoing, without building heavy real-time chat that may stress Namecheap shared hosting.

Recommended name in the UI:

- Client Job Portal;
- Job Conversations;
- Client Jobs.

Implemented first-version scope:

- admin activates a job portal from an enquiry;
- the system generates a long, random, secure client portal token;
- admin can copy, regenerate, enable or disable the client portal link;
- client opens `/client/jobs/{secure-token}`;
- client can view only that specific job, never the admin dashboard or other client jobs;
- admin can view all job conversations from a dedicated inbox-style page;
- each job conversation stores messages in the database with sender type, sender name, timestamp and read/unread state;
- admin and client can send messages without refreshing the whole page through AJAX/FormData, with normal form submission kept as a fallback;
- client-facing admin messages display as "Humelix Project Team" instead of exposing individual admin names;
- admin-side conversation history still keeps real sender names for accountability and audit/history;
- admin and client conversation threads can poll for new messages without refreshing the whole page;
- conversation history remains documented for audit/dispute/reference purposes;
- job status can be updated from admin, for example: Confirmed, Engineer Assigned, Site Visit Scheduled, In Progress, Awaiting Client, Completed, Closed;
- internal notes should remain separate from client-visible messages.

Implemented database structure:

- keep `enquiries` as the original lead/request record;
- `client_jobs` table linked to `enquiries.id`;
- `job_messages` table linked to the client job record;
- `job_message_attachments` table linked to the client job and exact message for photos/documents;
- later add `job_activity_logs` for status changes, portal-link regeneration and important admin actions.

Implemented job fields:

- enquiry ID;
- job reference;
- client name, email and phone copied from the enquiry;
- service/type of work;
- assigned engineer ID;
- status;
- secure portal token;
- portal enabled/disabled flag;
- last client message timestamp;
- last admin message timestamp;
- unread counts;
- created/updated timestamps.

Implemented message fields:

- job ID;
- sender type: client, admin, engineer or system;
- sender user ID where applicable;
- sender display name;
- message body;
- visibility: client-visible or internal-only;
- read/unread state;
- timestamps.

Implemented attachment behavior:

- client and admin can add optional files to a job conversation message;
- attachments are linked to both the `client_jobs` record and the exact `job_messages` record;
- first-version upload limit is 3 files per message;
- each file is limited to 10MB;
- allowed file types are JPG, JPEG, PNG, WebP, PDF, DOC and DOCX;
- local video uploads are intentionally not allowed on shared hosting;
- clients should paste video links in the message body if video evidence is needed;
- files are stored on Laravel's private local disk and served through authorized admin/client routes;
- admin access requires `client_jobs` permission;
- client access requires the private job portal token;
- client-visible emails mention that files were added and link back to the private portal instead of attaching heavy files directly to email.

Shared-hosting safe update behavior:

- do not use WebSockets in the first version;
- use normal database-backed messages;
- use normal page reload/manual refresh as a reliable fallback;
- use lightweight polling similar to the admin notification system;
- poll about every 15 seconds normally;
- poll about every 10 seconds when the active conversation is open;
- pause or slow polling when the browser tab is hidden;
- when polling finds new messages, append them to the conversation thread without refreshing the whole page.
- message sending also appends the saved message bubble immediately after a successful response, clears the form and keeps attachments supported.

Notification behavior:

- when the client sends a new message, permitted admins should see an admin notification/unread count;
- when admin sends a client-visible message, admin can optionally email the client with a link back to the private job portal;
- email should not expose sensitive internal notes;
- WhatsApp/SMS alerts should remain future builds unless a provider is added.

Permissions:

- Technical Super Admin / Developer has full access to all job portals, conversations, tokens and settings;
- Company Owner / CEO can manage job conversations, statuses, engineer assignment and client communication;
- lower admins can only access job conversations if their role permission allows it;
- delete/archive controls should remain restricted to Technical Super Admin and Company Owner;
- assigned engineers do not automatically need admin access in the first version;
- future engineer portal access can be added separately.

Security rules:

- portal tokens must be long, random and not guessable;
- never expose sequential job IDs as the only access control;
- allow admins to regenerate a token if a link is shared with the wrong person;
- allow admins to disable the portal when a job is closed;
- consider optional client email/phone verification later for higher sensitivity jobs.

## Private job financial documentation

Status: first admin-only version implemented.

After HUMELIX agrees a price with the client, permitted admins can save the agreed commercial details inside the Client Job admin screen. This does not appear on the public website, public enquiry form or first-version client portal.

Implemented first-version scope:

- add a private Commercial Agreement section to admin Client Job details;
- save agreed amount;
- save currency, defaulting to NGN unless changed;
- save payment status, for example Pending, Part Paid, Paid, Cancelled;
- save agreement note;
- save date agreed;
- keep this information admin-only in the first version.

Visibility rules:

- do not show agreed amount on the public enquiry form;
- do not show agreed amount on public service/resource pages;
- do not show agreed amount in the first client job portal version unless HUMELIX intentionally approves client-visible quotes later;
- restrict view/edit to Technical Super Admin, Company Owner and roles explicitly granted finance/commercial permission.

Future finance module path:

- revenue dashboard;
- job value reports;
- invoice generation;
- receipt tracking;
- payment reminders;
- export to Excel/PDF;
- client-visible quote/invoice portal, only when HUMELIX is ready for that workflow.

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
- inline article image uploads from the TinyMCE editor, stored under `storage/app/public/article-inline-images`;
- inline images render in the public read-more article exactly where the admin inserted them;
- inline article images are saved into the Media Library so Technical Super Admin / Company Owner can review and clean them up safely;
- Media Library shows whether an uploaded file is currently used inside article content and blocks deletion while it is still in use;
- Media Library provides a **Copy URL** helper for uploaded files. To reuse an inline article image, copy the public URL, open the article editor, click the TinyMCE image button, paste the URL into the image source field, then save the article;
- an 8,000-word maximum for web article content, with admin guidance to split longer guides manually or attach a PDF;
- optional PDF attachments up to 10MB for long guides, manuals, brochures, checklists and downloadable resources;
- optional article video embeds using YouTube, YouTube Shorts, Vimeo, MP4 or WebM URLs;
- article video placement controls for after-intro, middle-of-article or end-of-article display;
- optional article video title and caption fields for clearer public presentation;
- related resources below each article detail page, prioritizing the same category and falling back to latest resources when needed.

Manual article related-link entry is intentionally hidden from the admin form for now. The backend relationship is left intact so existing data is not broken, but the visible "Add Link" admin control was removed because automatic related resources already cover the current public need and the manual link UI should be rebuilt later as a clearer "Supporting Links / CTA Links" feature.

## Newsletter and article email updates

The website includes a newsletter foundation for sending new resource/article updates to visitors who subscribe.

Current behavior:

- public signup appears on the Resources listing and article detail pages;
- visitors provide an email address and consent before subscribing;
- subscriptions use single opt-in, so the visitor is subscribed immediately after submitting the form with consent;
- welcome emails are branded with the HUMELIX LIMITED logo, website link and professional styling;
- confirmed subscribers receive a branded email when a new article is published;
- new-article subscriber emails are dispatched after the response as queued jobs so article publishing is not held hostage by many SMTP sends;
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

Queue behavior:

- local/preview environments may keep `QUEUE_CONNECTION=sync` if no worker is running;
- production should use `QUEUE_CONNECTION=database` after the queue tables are migrated;
- on Namecheap shared hosting, process database queue jobs with a cPanel cron command that runs `php artisan queue:work --stop-when-empty`;
- if the queue worker/cron is not configured, queued newsletter jobs will sit in the `jobs` table until the worker runs.

Future newsletter improvements:

- add queue failure monitoring and retry controls if newsletter volume grows;
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
- use the database queue for bulk newsletter emails if a cPanel cron worker is configured;
- run storage link or equivalent public storage setup;
- confirm file uploads and public asset paths;
- configure final domain, SSL and production cache.

## Future API architecture plan

The current HUMELIX website is intentionally built as a Laravel Blade application because it is simple, SEO-friendly, easier to maintain, and better suited for Namecheap shared hosting at the current stage.

When HUMELIX grows larger, the project can move gradually toward an API-based architecture without rebuilding everything at once.

Possible future structure:

- public website: `https://humelix.com`;
- API backend: `https://api.humelix.com` or `https://humelix.com/api`;
- admin panel: current Laravel admin first, with a possible separate admin frontend later;
- future mobile/engineer/customer apps consuming the same API.

Example future environment variable:

```env
HUMELIX_API_URL=https://api.humelix.com
```

Future API use cases:

- mobile app for customers or engineers;
- engineer assignment/status updates;
- customer portal;
- external CRM integrations;
- WhatsApp/SMS automation;
- Google Reviews or analytics integrations;
- public article, project, service and equipment endpoints;
- separate frontend applications using the same Laravel backend.

Important note: moving to an API does not automatically make the website faster. It becomes faster and more scalable only when paired with good database design, caching, queues, CDN/static asset strategy, and stronger hosting such as VPS/cloud hosting. The recommended path is to keep the current Laravel website stable, then add API endpoints gradually when a real feature needs them.

## Image and media performance policy

Current safe performance polish:

- hero images are treated as first-screen assets and should stay eager/high priority;
- normal card, list, gallery, team, article, project, equipment and secondary images should lazy-load;
- image containers use stable aspect ratios where practical to reduce layout jumping while images load;
- generated public images are currently lightweight enough for preview use, with the largest generated-image audit under 1MB;
- automatic image conversion is intentionally not enabled because the client prefers to prepare image files manually before upload;
- videos should normally be hosted on YouTube and embedded by URL rather than uploaded as large local files.

## Page speed and navigation performance plan

The current Laravel Blade website can remain fast if the production environment is configured well. React/Next.js is documented as a future build option, but it is not required just to reduce normal page-to-page delay.

Current/future performance actions:

- use Laravel production caches after deployment: `config:cache`, `route:cache`, `view:cache`, and `event:cache` where supported;
- use MySQL in production instead of local SQLite;
- keep uploaded public images compressed before upload;
- keep hero images eager/high-priority because they are first-screen assets;
- lazy-load non-hero images such as cards, galleries, team images, article images, project images and equipment images;
- keep public pages free from unnecessary third-party scripts;
- avoid sending heavy emails synchronously inside browser requests;
- browser/server cache rules for static CSS, JS, font and image assets are included in `public/.htaccess`;
- light public navigation link prefetching is included in `public/js/uch.js` so common same-site routes such as Services, Projects, Resources and Contact feel faster after hover/touch;
- consider Cloudflare/CDN later for better global static asset delivery, caching and security.

Client-side image preparation rules:

- Hero/page banner images: prepare around 1920x900 or 1920x1080, ideally under 500KB-900KB.
- Project/article/equipment card images: prepare around 1200x750 or 1000x625, ideally under 300KB-600KB.
- Team/profile images: prepare around 900x1125 or 800x1000, ideally under 250KB-500KB.
- Gallery images: prepare around 1200px wide unless the image needs extra detail.
- Use JPG/WebP for photos and PNG only when transparency or sharp graphics are required.
- Avoid uploading raw phone/camera photos directly; resize/compress them first.
- Keep local video uploads limited and prefer YouTube links for public videos.

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
