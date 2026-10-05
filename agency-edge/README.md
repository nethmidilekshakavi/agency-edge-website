# Agency Edge — website

**Marketing Meets Technology.** Laravel 12 (PHP backend) + Inertia + Vue 3 (frontend) + GSAP/Lenis (motion), with hand-written HTML and CSS.

All copy comes from the *Agency Edge Website Content and Customer Deck*, and every section can be edited in the admin panel.

---

## 1. Requirements

- PHP **8.2+** with these extensions: `pdo_sqlite` (or `pdo_mysql`), `mbstring`, `openssl`, `fileinfo`, `gd`
- Composer 2
- Node.js **20.19+** (Vite 8)

## 2. Setup (first time)

```bash
cd agency-edge
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
# SQLite (default): create the database file
php -r "touch('database/database.sqlite');"
# Set ADMIN_EMAIL / ADMIN_PASSWORD in .env first, then:
php artisan migrate --seed
php artisan storage:link
npm install
```

Or run everything in one go: `composer run setup`.

## 3. Run it locally

```bash
composer run dev      # Laravel server + queue + Vite hot reload
# or in two terminals:
php artisan serve
npm run dev
```

- Website: http://localhost:8000
- Admin: http://localhost:8000/admin (log in with ADMIN_EMAIL / ADMIN_PASSWORD from `.env`)

## 4. Admin panel

| Section | What it does |
|---|---|
| **Enquiries** | Contact-form leads: search, mark read/unread, delete, export CSV |
| **Website content** | Edit every block of copy (hero, services, founders, industries…). Upload founder photos here. "Reset" restores the deck copy. |
| **Insights (blog)** | Write articles in Markdown with a cover image, an SEO description and a scheduled publish date |
| **Subscribers** | Newsletter sign-ups, with CSV export |

To get an email for every new enquiry, set `AGENCY_LEADS_EMAIL` and real SMTP `MAIL_*` settings in `.env`. Every enquiry is saved in the admin panel either way.

## 5. Before going live: fill these in (Admin → Website content)

The deck says not to invent contact details, so these fields start empty. Each one shows up on the site as soon as you fill it in.

- **Contact**: email, phone, address, company LinkedIn, Ribelz website URL
- **Founders**: Indika Jayapala's LinkedIn URL, and portrait photos for both founders

## 6. Deploy

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

- Point the web server's document root at **`/public`**.
- In `.env` set `APP_ENV=production`, `APP_DEBUG=false` and `APP_URL=https://your-domain`.
- **Shared hosting (cPanel):** run `npm run build` on your computer and upload the `public/build` folder with the rest of the project.

## 7. Where things live

```
config/site.php                 ← default website copy (from the deck)
app/Support/SiteContent.php     ← merges defaults + admin edits (cached)
app/Http/Controllers/           ← public pages, contact, newsletter, insights, sitemap
app/Http/Controllers/Admin/     ← admin panel
resources/css/app.css           ← design system (black / white / electric yellow)
resources/js/lib/motion.js      ← GSAP + Lenis setup, page-reveal gate
resources/js/lib/directives.js  ← v-reveal, v-split, v-magnetic, v-tilt, v-parallax, v-scramble
resources/js/Components/site/   ← animated sections (hero canvas, equation, journey, chat demo…)
resources/js/Pages/             ← Home, WhatWeDo, Martech, Ai, About, Contact, Insights, Admin/*
```

## 8. Motion and accessibility notes

- With **`prefers-reduced-motion`** turned on, the preloader, smooth scroll, pinning and reveals are disabled and every piece of content stays visible.
- On touch devices the custom cursor, magnetic buttons and tilt are switched off and scrolling is native. The horizontal journey becomes a vertical timeline.
- The hero canvas pauses when it is off-screen or the tab is hidden.
- SEO: each page gets its own server-rendered title, description and Open Graph tags, plus Organization schema, `/sitemap.xml` and `/robots.txt`. The admin area is `noindex`.
- GSAP 3.13+ (including SplitText, ScrambleText, DrawSVG and MotionPath) is free for commercial use.
