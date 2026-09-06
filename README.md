# Auto Motors

A company website and admin CMS for AUTO MOTORS SARL, an automotive parts supplier. Built with Laravel to present the business and let administrators manage its catalog and website content.

## Features

- Company information, services, vehicle categories, FAQs, and contact links for phone, email, and WhatsApp.
- Product catalog with category filters, pagination, optional prices, and image galleries.
- French and English interface and content, with French selected by default. The language choice is saved in the session.
- Responsive navigation and a scroll-driven perspective effect on the hero image, with reduced-motion support.
- Admin dashboard to add, edit, delete, search, set display order, and publish or hide content. Editors can manage translations, images, company details, and SEO settings.

Admin access uses session authentication and authorization checks. Forms include CSRF protection, server-side validation, and escaped output. Passwords are hashed, login and setup requests are rate-limited, and image uploads are checked for type, size, and dimensions.

## Tech stack

PHP 8.2+, Laravel 12, Blade, MySQL/MariaDB, Bootstrap 5, Bootstrap Icons, CSS, JavaScript, and Vite. Tests use PHPUnit and SQLite; PHP formatting uses Laravel Pint.

## Setup

Install PHP 8.2+, Composer, MySQL/MariaDB, and Node.js 22.12+ with npm. Enable PHP's PDO SQLite extension to run the tests.

From the project directory:

```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci
```

On Windows PowerShell, use `Copy-Item .env.example .env` instead of `cp`.

Create a database named `auto_motors`, set the `DB_*` credentials in `.env`, and set `APP_URL=http://localhost:8000`. Then run:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
```

The seed data includes company information, services, and categories. Products and FAQs can be added through the CMS.

## Run locally

```bash
php artisan serve
```

Open `http://localhost:8000`. Visit `/setup` to create the first administrator, then sign in at `/admin/login`. Setup becomes unavailable once an administrator exists. You can also create an administrator with `php artisan admin:create`.

For frontend development, run `npm run dev` in a second terminal while the PHP server is running.

## Checks

```bash
php artisan test
php vendor/bin/pint --test
npm run build
```

Tests cover localization, administrator setup, login/logout, access control, page rendering, service management, and invalid uploads. They use an in-memory SQLite database rather than the local application database.

## License

This project is intended for the Auto Motors portfolio deployment and authorized reuse by its owner.
