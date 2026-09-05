# Auto Motors Portfolio

A bilingual company portfolio and lightweight content management system built with Laravel 12 and Bootstrap 5. The public website supports French and English, while the protected admin area manages company information, services, products, vehicle categories, advantages, and FAQs.

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- MySQL or another database supported by Laravel

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Update the database values in `.env`, then initialize the application and build its assets:

```bash
php artisan migrate --seed
npm install
npm run build
```

Create the first administrator in either of these ways:

- Open `/setup` and complete the one-time administrator form.
- Run `php artisan admin:create` and follow the prompts.

Until an administrator exists, `/admin` and `/admin/login` redirect to `/setup`. Once setup is complete, the setup route is locked and administrators use `/admin/login`. The application does not provide public registration.

For local development, run:

```bash
composer run dev
```

## Project structure

- `PortfolioController` loads active public content and site settings.
- `ContentController` uses a resource definition map to share standard CRUD behavior without duplicating controllers for every content type.
- `SiteSetting` stores editable company, contact, hero, and SEO values.
- `HasLocalizedContent` provides the French/English fallback used by public content models.
- Laravel authentication and the administrator middleware protect the CMS.

French is the default public language. Editors maintain both French and English content in separate admin form sections.

## Security

- Passwords are hashed through Laravel’s hashing system.
- The first administrator requires a password of at least 12 characters with mixed case, a number, and a symbol.
- Forms use CSRF protection.
- Admin login and setup submissions are throttled.
- Uploaded images are checked for type, size, and dimensions.
- Setup is unavailable after the first administrator is created.

## Testing and formatting

```bash
php artisan test
php vendor/bin/pint --test
npm run build
```

The test suite uses an in-memory SQLite database and does not modify local development data.

## License

This project is intended for the Auto Motors portfolio deployment and authorized reuse by its owner.
