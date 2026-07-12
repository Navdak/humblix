# UCH SYSTEMS Project Audit

## Blueprint Coverage

This Laravel project implements the core UCH SYSTEMS Master Blueprint v3.0 as a production-starting codebase.

### Public Website
- Home page with hero, CTAs, trust strip, services, sector finder, featured projects, why-choose cards, reviews, founder snapshot and contact band.
- Services index and service detail pages.
- Sector index and sector detail pages.
- Projects index and project detail pages.
- About page and founder profile page.
- Team/delegates public page.
- Reviews public page.
- Resources/blog index and article detail pages.
- Contact/quote page with file upload.
- Floating chat assistant on every public page.

### Admin Platform
- Private admin login.
- Dashboard stats and recent activity.
- Site Settings for homepage/contact/footer content.
- Enquiries lead management, status updates, internal notes and CSV export.
- Projects CRUD with featured project support.
- Team Members CRUD with region, role, certifications and visibility.
- Articles CRUD with TinyMCE WYSIWYG and related links repeater.
- Review moderation with approval and admin response.
- Media Library file uploads.
- Users & Roles management.

### Database
- Users and sessions.
- Site settings.
- Projects.
- Team members.
- Reviews.
- Enquiries.
- Articles.
- Related links.
- Media assets.

### Security / Deployment Basics
- Laravel CSRF protection.
- Auth middleware for admin routes.
- Admin-only middleware.
- File upload validation.
- Public storage disk support.
- Namecheap/cPanel-friendly MySQL configuration.

## Notes
- The service page templates are intentionally hardcoded because the blueprint says service text should be template-controlled by the developer.
- Client Portal, maintenance contract tracking, multilingual pages and CRM integration are treated as Phase 3/future expansion items.
- Vendor dependencies and node_modules are not included in the zip; run Composer and npm install locally.
