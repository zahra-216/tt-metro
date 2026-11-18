# TT Metro Campus - project notes for Claude

Written 1 Nov 2026 from a long working session. Read this first. Details that change often (deploy state, open items) are in sections 8 and 9.

## 1. The person and how to work with them

- Owner of the campus sites. Not a developer: wants **step-by-step, click-by-click instructions** ("tell how to do", not "do this"). Give **one step at a time** for anything on the server, and ask them to paste the output before the next step.
- Wants things **checked properly before they are called done**: open the page, test small phone widths (375 and 320 px), run the crawler and tests. They have found bugs by looking at screenshots, so look at the pages yourself.
- Style rules they set (apply to both sites, code and text):
  - Use the **short hyphen "-" everywhere**. Never the long dash.
  - **Solid colours only. No gradients** (LMS, whole system).
  - Paragraphs and long texts are **justified** (centred blocks, captions, tables and narrow cards stay left or centred).
  - Content should use the full width with only a **small side padding** (about 24 px). No big empty margins.
  - **One** Back button per inner page. No extra back arrows.
  - Tables: dark header with light text, spaced cells, rounded corners, same everywhere.
  - Lists of things to browse (faculties, courses, levels) are shown as **cards**, not long narrow bars.
  - Do not ask for passwords in chat. Never store passwords in files.
- Confirm before pushing or touching the live servers. Local commits on `main` are fine; the user runs `git push` and the server pulls.

## 2. What exists

Two separate Laravel projects, both on the same Hostinger hosting account.

| | Website | LMS |
|---|---|---|
| Local path | `C:\projects\ttmetro-campus` (this folder) | `C:\projects\lms` |
| GitHub | `zahra-216/tt-metro` | `zahra-216/lms` |
| Live URL | https://ttmetrocampus.com | https://lms.ttmetrocampus.com |
| Stack | Laravel 13, Filament 5 admin, Tailwind 4 (Vite), PHP 8.4 | Laravel 12, Bootstrap 5.3.2 (CDN), PHP 8.2+, MySQL |
| Local DB | `ttmetro` | `lms` (user's own data, never touch) and `lms_dev` (a copy used for all testing) |
| Live DB | `u422071295_ttmc` | `u422071295_lms` |
| Local run | `php artisan serve --port=8123` | `php artisan serve --port=8200` |

Local MySQL is Laragon's and does not start by itself: `C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe --defaults-file=my.ini --console` (run from that folder, in the background). Background servers die when a session ends, so restart both after a break.

## 3. Hostinger server facts

- SSH: `ssh -p 65002 u422071295@145.79.25.218` (the user has the password). Account home `~`.
- The default CLI PHP is 8.3. PHP 8.4 is at `/opt/alt/php84/usr/bin/php`; `~/.bashrc` and `~/.bash_profile` put it first on `PATH`. If `php -v` shows 8.3 in a new session, run `source ~/.bash_profile`.
- The **domain** is in one Hostinger account and the **hosting** in another. The domain already points at the hosting, so no DNS change is needed for updates.
- **Website**: code in `~/tt-metro` (a `git clone` of the website repo). `~/domains/ttmetrocampus.com/public_html` is the web root. It holds **symlinks** to every item in `~/tt-metro/public`, made by `~/golive.sh` (`~/rollback.sh` undoes it). If you add a new item to the website's `public/`, run `bash ~/golive.sh` again (it skips what already exists).
- Other apps live inside the same `public_html` and must never be touched: `Examination`, `Registration`, `StudentPortal`, `worksphere`, `lms.ttmetrocampus.com`, plus `.well-known`, `php.ini`, `.user.ini`, `.ftpquota`, `llms.txt`, `test.html`.
- The old WordPress files were moved (not deleted) to `~/wp-backup` (about 3 GB). **Delete it after about 22 Nov 2026** (`rm -rf ~/wp-backup`) once nobody wants to go back.
- **LMS**: code in `~/domains/ttmetrocampus.com/public_html/lms.ttmetrocampus.com` (a `git clone` of the LMS repo, `.env` has `APP_ENV=production`, `APP_DEBUG=false`).
- Backups taken before the LMS redesign: server `~/backups/lms-db-before-redesign.sql` and `lms-files-before-redesign.tar.gz`, and the same two files on the PC in `C:\projects\lms-backups`.
- The test domain `orchid-gazelle-273638.hostingersite.com` was created only for testing; its folder link was removed. Deleting it in hPanel was explained to the user, not confirmed.

## 4. Update routine (both sites)

On the PC: `git push origin main` (website: from `C:\projects\ttmetro-campus`, LMS: from `C:\projects\lms`). On the server, in that site's folder:

```bash
git pull
php artisan optimize:clear
```

Add `php artisan migrate --force` only when a migration was added, `composer install --no-dev --optimize-autoloader` only when `composer.lock` changed. Website extra: `php artisan config:cache && php artisan route:cache && php artisan view:cache`. Then Ctrl+F5. Rollback: `git checkout <old commit>` then `php artisan optimize:clear`.

Build note (website): `public/build` is committed (the server has no Node). Run `npm run build` before committing styling changes.

## 5. Website - decisions and what was built

- Migrated from WordPress. Content and images were imported from the WP export and a media zip. 14 partner logos, 4 team photos, 4 programme images and 8 event pictures were not recoverable (see `MISSING-IMAGES.md`); the user uploads them in the admin.
- Old WordPress addresses redirect to the new pages (`routes/web.php`): `/courses/{slug}` -> `/programmes/{slug}` (same slugs), `/event/{slug}` -> `/events/{slug}` (emoji removed), about/contact/faculty pages, lessons -> Academics, `/student-portal` -> the LMS link.
- Design tokens (`resources/css/app.css`): navy `#0a2a52`, brand `#0b4a8f`, top bar colour `#1b6fc4` (`--color-bar`, white text), fonts Inter + Plus Jakarta Sans. Navbar 5.25rem tall, logo and name at the far left, buttons at the far right, title 26 px (32 px on very wide screens) with the slogan "Empowering Minds, Shaping Futures." under it. Full-width bars with 16 to 24 px side padding.
- Timezone is `Asia/Colombo` (`APP_TIMEZONE`). Page titles and text use the short hyphen. Branch names with long dashes are normalised.
- Settings (map, emails, phones, top-bar text) live in the **database** and are edited in the admin "Site Settings". Code changes do not change them on the live site. Map URL: `https://www.google.com/maps?q=TT+Metro+Campus,+11+Galle+Rd,+Dehiwala-Mount+Lavinia+10370&output=embed`. Email order: `email_1 = co.techlink@gmail.com`, `email_2 = info.ttmetro@gmail.com`. Application and enquiry emails go to `apply_email` (`info.ttmetro@gmail.com`).
- Mail: Hostinger SMTP, sender `noreply@ttmetrocampus.com`, branded HTML layout (`resources/views/emails`). Live test email worked. `/dev/mail-preview/{type}` exists on local only.
- Admin (Filament at `/admin`): profile page enabled (`->profile()`), notifications auto-close after 3 seconds (`AppServiceProvider`), team-member photos are a real upload (`TeamMemberForm`), events have a "More photos and videos" repeater (`event_images` table), a **Branches** list (Ampara/Sammanthurai and Batticaloa, shown on the home and contact pages; table `branches`, edited under Site), the student count is the "Students" row in **Stats** (set to 5000+).
- Programme levels are in code, not the admin: `SiteController::LEVELS`, `ProgrammeForm`, `ProgrammesTable`. "Masters" was removed.
- Headings keep "TT Metro Campus" on one line (`keep_name()` helper). Contact icons are coloured (location red, phone green, email amber, hours violet).

## 6. LMS - decisions and what was built

Full review notes are in the LMS repo: `docs/AUDIT.md` (quiz explanation, fixes, recommendations, missing features). README there was rewritten.

**Roles and sign-in**
- Admin: guard `admin`, email + password, `/admin/login` (rate limited). Lecturer: guard `lecturer`, username + password, `/lecturer/login`. Student: session key `student_id`, registration number + password, `/login`, middleware `student.auth`.
- **Lecturers are not linked to subjects in the data model**, so every lecturer can open every subject. The user confirmed this is how the campus works; leave it.
- **Student access rule (enforced on the server)**: a student sees only subjects of their own course and level, from Semester 1 up to their current semester. The admin advances a student by changing their semester. Code: `App\Support\StudentSubjectAccess`, middleware `student.subject`, `Student\SubjectController::bySemester`. The old "enter subject code" unlock step was **removed** (the code was shown next to each subject, so it protected nothing).

**Interface system**
- Layouts in `resources/views/layouts`: `admin`, `lecturer`, `student` (sidebar, top bar, notifications, one Back button, messages), `guest` (sign-in), `public` (home, certificate check, grade scale). One stylesheet `public/css/lms.css`, one script `public/js/lms.js`. Pages only hold their own content (`@extends`, `@section('title')`, optional `@section('back', route(...))`, `@section('content')`). `@section('no_back','1')` hides the Back button (dashboards).
- Back button goes to the page the user came from; the parent page is only a fallback. After saving or deleting, `Controller::returnTo()` sends the user back to where they came from (filters and page kept), using `RememberPreviousPage` middleware.
- Public header has the same blue bar and navbar as the website, "Learning Portal" badge, email `info.ttmetro@gmail.com`. The **footer (logo, campus name, slogan, rights) shows only on public pages**, not after sign-in. Sign-in pages: logo 64 px, name 24 px, close to the left edge.
- Removed on purpose: Enrollments, and the Levels / Semesters / Subjects list pages and sidebar items (levels, semesters and subjects are added and deleted on the **Faculties** page; subject editing has its own page). Courses stays in the sidebar.
- Attendance, Assignments and Marks use one **card drill-down** (faculty, course, level, semester, subject, with search): `App\Support\AcademicTree` + `admin/academic-browser.blade.php`.
- Faculty and course cards use matching pictures from the website's own images (`public/images/courses`, picked by `App\Support\StockImages` from the course name). No pictures are loaded from other websites.
- Students admin page: four white level cards (Diploma, HND, Top-up, Degree), faculties and courses below.

**Quiz** (short answers are now labelled "Short / Long Answer"; long answers are accepted, about 50,000 characters, stored in a `TEXT` column): blank answers allowed at time-out, server enforces the time limit (60 s grace), written answers always wait for the lecturer, answered counter, unanswered warning, draft kept in the browser.

**Email**: students get a confirmation email when they submit an assignment (existing). The campus inbox `info.ttmetro@gmail.com` also gets an email for every submission (`AssignmentSubmittedMail`, config `mail.submissions_to`, env `SUBMISSIONS_MAIL_TO`). Tested locally with the log mailer; real delivery depends on the live `MAIL_*` settings.

**Security fixes**: upload allow-lists and size limits (notes, assignments, student submissions) plus `.htaccess` that blocks script execution in `storage/app/public` and `public/uploads`; login rate limiting and session regeneration for students and lecturers; one generic login error; content under `/subject/{id}` must belong to that subject.

## 7. Local test setup for the LMS (outside the repo)

- `lms_dev` is a clone of the user's local `lms`. `.env` in `C:\projects\lms` points at it. Known test passwords were set on the copy only (see the auto-memory note `lms-project`).
- `C:\projects\lms-tools` holds helper scripts: `crawl.py` (signs in as admin, lecturer and student and opens every GET page; last result 129 pages, 0 errors), `test_quiz.py`, `test_upload.py`, `test_return.py`, `test_semesters.py`, `test_mail.py`, `seed_quiz.php`, plus the conversion and fix scripts used for the redesign. `routes.json` and `samples.json` feed the crawler; regenerate after route changes (`php artisan route:list --json --except-vendor`, then `php samples.php`).
- Pillow is installed for Python (used to resize the course pictures).

## 8. Where things stand (1 Nov 2026)

- Website: all work is committed and pushed (`main` = `origin/main`, last commit "Team member photo upload in admin"). The last two website changes (Masters removed, team photo upload) are live (user confirmed 1 Nov 2026).
- LMS: redesign merged on GitHub (pull request 1) and pulled live (user confirmed `git pull` and HTTP 200, and showed live screenshots). Later commits are pushed to `origin/main`: footer only on public pages, Students page fix, phone header fix. The phone header fix (5a426aa) is live (user confirmed 1 Nov 2026). The Students page fix (00959d7) is live too (user confirmed 1 Nov 2026). All known pending deploys are done.
- The live LMS students page bug (HND, Top-up, Degree cards empty) and the phone header bug were reported by the user from the live site and fixed locally.

## 9. Still to do or decide

- Confirm the server pulls above (both sites), then run the live test list: public pages, admin, a student (semester rule), a lecturer upload, a real assignment submission (both emails arrive).
- Live LMS `.env`: confirm real `MAIL_*` settings so assignment emails really send; optional `SUBMISSIONS_MAIL_TO`.
- Delete `~/wp-backup` on the server after about 22 Nov 2026. Delete local leftovers when sure: `incoming-media` (2.4 GB zip), `ttmetro.sql`, `uploads.tar.gz`, `exports/` in this folder (all gitignored), and later `C:\projects\lms-backups`.
- Upload the missing website images (see `MISSING-IMAGES.md`) through the admin.
- LMS recommendations not done (see `docs/AUDIT.md` section 5 and 6): password reset for students and lecturers, mass-assignment hygiene on models, queries out of views, automated tests, scheduled backups, grade and attendance export, bulk student import, announcements, forum, assignment feedback, question bank with shuffling, server-side quiz autosave.
- Not every LMS page was reviewed visually (admin and student main pages, forms and phone widths were). When touching a page, look at it and at 320 px.

## 10. Errors found and fixed (so they are not repeated)

Website
- Admin showed no image for new events: images are now `event_images`, shown in a grid with a lightbox like gallery albums.
- Emails showed the long dash and wrong order: dashes replaced everywhere, `email_1` / `email_2` swapped (database settings, so also changed in the live admin).
- Title tag glued text ("TT Metro Campus - ... Sri Lanka"): use `trim($__env->yieldContent('title')) ?:` fallback in the layout.
- Layout file was damaged once by a regex replace (stray text at the end); edit Blade layouts with exact string replacement, not broad regex.
- Mobile header and menu clipped: menu height now `100dvh - 8.5rem`, title size steps (`text-base` under 400 px).

LMS
- Dead routes crashed (Route::resources created show/create URLs with no controller method): restricted with `only()/except()`; legacy student pages redirect to the working portal pages.
- Quiz: time-out with a blank answer lost every answer; GET to a POST-only route (405); time left was wrong because Carbon 3 `diffInSeconds` is **signed** (use `diffInSeconds($deadline, false)`); client-only time limit; "automatic" quizzes marked written answers as graded.
- Students could upload any file type (script execution risk): allow-lists plus `.htaccess`.
- Student page opened any subject by changing the number; the dashboard hid later semesters but the server did not.
- Students page: HND, Top-up and Degree empty because one `</div>` was removed too many (panels nested inside Diploma). A quick check for unbalanced `<div>` across all views found no others.
- Page titles: `@section('title')Text@endsection` on one line does **not** work (Blade ignores `@endsection` glued to a word). Always put the text on its own line.
- Two Back buttons: the old-page back link regex stopped at `>` inside `{{ $subject->id }}`; fixed with a matcher that skips `{{ }}`.
- Heading colour rule forced navy on white text in dark cards: headings now `color: inherit` (zero-specificity rule).
- Table headers were pale because of one global `!important` rule: dark header, light text now.
- Assumed a `lecturer_id` column on subjects (it does not exist) and locked lecturers out: that check was removed.
- Public header overlapped on phones (fixed navbar height): compact styles under 768 px and 390 px.

## 11. Tooling gotchas on this machine

- Shell is PowerShell and Git Bash. The Write/Edit tools require the file to be **read first**. After a session break, files can show as changed on disk: treat disk as truth.
- Python heredocs with backslashes (`\N`, `\U`, `\I` in PHP namespaces) break: write the script to a file with raw strings or build backslashes with `chr(92)`. `php artisan tinker` asks for a trust prompt and hangs: use small PHP scripts that bootstrap the app instead.
- PowerShell: `$home` is read-only; relative paths in `[IO.File]` resolve against the process folder, not the shell folder (use absolute paths).
- Disk space was tight once (1.7 GB free). Never unzip the whole media backup again.

<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>
