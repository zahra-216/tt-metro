# TT Metro Campus website

Laravel 13 + Tailwind CSS 4 + Alpine.js public site, with a Filament admin panel at `/admin`.

## Run locally (Laragon / Windows)

```bash
composer install
npm install
cp .env.example .env          # set DB_DATABASE=ttmetro, DB_USERNAME, DB_PASSWORD, FILESYSTEM_DISK=public
php artisan key:generate
php artisan migrate --seed    # creates tables, default settings, stats, board and the admin user
php artisan storage:link
npm run build                 # or `npm run dev` while developing
php artisan serve --port=8123
```

Admin login: `ADMIN_EMAIL` / `ADMIN_PASSWORD` from `.env` (used by the seeder). Change the password after first login.

## Import content from WordPress

```bash
php artisan wp:import path/to/export.xml            # programmes, events, news, gallery + downloads media from the live site
php artisan wp:import path/to/export.xml --no-media # data only
```

Safe to re-run: records are matched on their WordPress ID, and files already downloaded are skipped.

## What staff can edit in `/admin`

Faculties, programmes, events, news, gallery albums (photos, mp4 and YouTube links), team members (board, council, senate, staff),
testimonials, partners, stats, student-life items, plus **Site settings** (hero text, contact details, Google Map, portal links,
social links, the email that receives form submissions). Application and contact form submissions are stored under **Inbox**.

## Email

Set the `MAIL_*` values in `.env` (Hostinger SMTP) so the forms email `info.ttmetro@gmail.com`. With `MAIL_MAILER=log` messages are only written to `storage/logs`.
