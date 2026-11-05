# HosterSearch

Production-oriented hosting review aggregator inspired by HostAdvice-style UX: company reviews, plans, coupons, comparisons, hosting advisor quiz, admin panel, and partner claim dashboard.

**Stack:** Laravel 12 (PHP 8.2+) · MySQL 8 / MariaDB 10.4+ · Apache (`.htaccess`) · Tailwind CSS · Git

> Laravel 12 is used because it is the current release compatible with PHP 8.2. Behavior matches the Laravel 11-era app structure.

## Features (roadmap implemented in stages)

1. ✅ Project init, migrations, models, seeders
2. ⏳ Auth + roles + middleware
3. ⏳ Public site (home, categories, company pages)
4. ⏳ Reviews, coupons, comparison, advisor
5. ⏳ Admin panel
6. ⏳ Partner dashboard + claim
7. ⏳ SEO, cache, polish

## Requirements

- PHP 8.2+ with extensions: `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `curl`
- Composer 2.x
- MySQL 8+ or MariaDB 10.4+
- Apache with `mod_rewrite` (or `php artisan serve` for local)
- Node.js 18+ (for Vite / Tailwind frontend assets)

## Quick start (XAMPP / Windows)

```bash
# 1) Clone / open project
cd C:\Users\1\Documents\hostersearch

# 2) Install PHP deps
composer install

# 3) Environment
copy .env.example .env
php artisan key:generate

# 4) Create DB + app user (MySQL / MariaDB via XAMPP)
# CREATE DATABASE hostersearch CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
# CREATE USER 'hoster'@'localhost' IDENTIFIED BY 'hoster';
# GRANT ALL ON hostersearch.* TO 'hoster'@'localhost'; FLUSH PRIVILEGES;

# 5) Configure .env (important on Windows if WSL also uses :3306)
# DB_CONNECTION=mysql
# DB_HOST=localhost
# DB_DATABASE=hostersearch
# DB_USERNAME=hoster
# DB_PASSWORD=hoster

# 6) Migrate + seed
php artisan migrate --seed

# 7) Storage link + frontend
php artisan storage:link
npm install
npm run build

# 8) Run
php artisan serve
# → http://127.0.0.1:8000
```

### Demo accounts (after seed)

| Role        | Email                         | Password  |
|-------------|-------------------------------|-----------|
| Super Admin | superadmin@hostersearch.test  | password  |
| Admin       | admin@hostersearch.test       | password  |
| Partner     | partner@hostersearch.test     | password  |
| User        | user@hostersearch.test        | password  |

Seed data includes **18 hosting companies** (Hostinger, IONOS, Cloudways, Namecheap, Bluehost, SiteGround, …), plans, reviews, coupons, categories, comparisons, advisor questions, and CMS pages.

## Apache vhost

See [`apache-vhost.conf.example`](apache-vhost.conf.example).

1. Enable `mod_rewrite` and vhosts in Apache.
2. Point `DocumentRoot` to `/public`.
3. Add `127.0.0.1 hostersearch.test` to your hosts file.
4. Set `APP_URL=http://hostersearch.test` in `.env`.

Laravel already ships `public/.htaccess` for front-controller routing.

## Database overview

| Area        | Tables |
|-------------|--------|
| Auth        | `users`, `sessions`, `password_reset_tokens` |
| Catalog     | `companies`, `company_locations`, `features`, `company_feature`, `company_types`, `company_company_type`, `categories`, `company_category` |
| Plans       | `plans`, `plan_feature` |
| Reviews     | `reviews`, `review_replies` |
| Marketing   | `coupons`, `comparisons`, `comparison_company`, `click_logs` |
| Advisor     | `advisor_questions`, `advisor_answers`, `advisor_results` |
| CMS/System  | `pages`, `settings`, `media`, `activity_logs`, `claims` |

Indexes: `slug`, scores, status, `created_at`. Full-text indexes on companies and reviews.

## Common artisan commands

```bash
php artisan migrate:fresh --seed   # reset DB + seed
php artisan route:list
php artisan config:cache
php artisan view:cache
php artisan optimize
```

## Project layout

```
app/
  Enums/          # UserRole, CompanyStatus, ReviewStatus…
  Models/         # Eloquent models + relations
database/
  migrations/     # schema
  seeders/        # demo data
config/
  hostersearch.php
public/           # Apache document root
routes/
```

## License

Proprietary / private project — adjust as needed.
