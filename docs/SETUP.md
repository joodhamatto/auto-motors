# AUTO MOTORS SARL — setup and architecture

## Local installation (XAMPP / Windows)

Requirements verified for this project: PHP 8.2+, Composer 2, MySQL or XAMPP MariaDB, Node.js and npm. XAMPP's `php_zip.dll` is present but disabled in this installation, so Composer commands below enable it for that process only.

```powershell
Copy-Item .env.example .env
& 'C:\xampp\php\php.exe' -d extension=zip path\to\composer.phar install
& 'C:\Program Files\nodejs\npm.cmd' install
& 'C:\xampp\php\php.exe' artisan key:generate
```

Start MySQL in XAMPP, create a UTF-8 database named `auto_motors`, confirm the `DB_*` values in `.env`, then run:

```powershell
& 'C:\xampp\php\php.exe' artisan migrate --seed
& 'C:\xampp\php\php.exe' artisan storage:link
& 'C:\xampp\php\php.exe' artisan admin:create admin@example.com --name="Administrator"
& 'C:\Program Files\nodejs\npm.cmd' run build
& 'C:\xampp\php\php.exe' artisan serve
```

The `admin:create` command asks for and confirms a password without printing it. It enforces at least 12 characters. There is no public registration and no built-in default password.

For local development use `npm run dev`. For production set `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, `SESSION_SECURE_COOKIE=true`, real database credentials and the correct `APP_URL`; then run `php artisan optimize`.

## Database schema

- `users`: authenticated accounts with an indexed `is_admin` authorization flag.
- `site_settings`: central grouped company, hero, about, contact and SEO values; bilingual settings use `value_fr`/`value_en`.
- `services`: bilingual service copy, icon/image, status and order.
- `product_categories` → `products` (one-to-many): bilingual categories and products, optional price/reference/main image.
- `products` → `product_images` (one-to-many, cascading delete): optional gallery images.
- `vehicle_categories` → `vehicles` (one-to-many): vehicle type, brand/item, optional bilingual details and image.
- `advantages`: bilingual approved selling points, icon, status and order.
- `faqs`: bilingual question/answer, status and order.
- Laravel `sessions`, `cache`, `jobs` and password-reset support tables.

Category deletion is restricted while products/vehicles reference it. Product gallery rows cascade when a product is deleted. Display and active fields are indexed where they affect public queries.

## How it works

- Localization: static interface copy lives in `lang/fr` and `lang/en`; the session locale middleware defaults to French. Dynamic models read `*_fr` or `*_en`, falling back to French.
- CMS synchronization: public controllers query active database rows on every request, ordered by `display_order`; an admin save is therefore immediately visible without duplicated content.
- Pagination: products use Eloquent `paginate(8)` publicly and `paginate(12)` in admin; SQL applies limit/offset before records reach PHP.
- Upload security: Laravel checks upload success, actual image MIME/content, JPEG/PNG/WebP type, 4 MB size and dimensions. Laravel Storage generates random hashed filenames. Replaced/deleted files are removed only when their path starts with the dedicated `uploads/` prefix, protecting bundled defaults.
- Authentication: Laravel session authentication, hashed passwords, session ID regeneration after login, invalidation and CSRF-token regeneration at logout, five login attempts per minute, and both `auth` and `admin` middleware on every CMS route.
- Hero motion: the fixed-size cinematic hero uses CSS entrance/glow/road movement and subtle pointer-based background perspective on desktop. Mobile reduces the visual complexity; `prefers-reduced-motion` disables motion.
- Product filtering: filtering animates only the current database-paginated page. It never downloads the whole catalog.

## Content still needed from the company

Approved product rows/prices/images, FAQ answers, project/work samples, business hours, social media URLs, and verified map coordinates were not supplied. The CMS is ready for them where applicable; none were invented. A map and social icons remain absent until verified data is entered.
