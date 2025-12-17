# Zadex Food Backend (Laravel)

Starter skeleton for the multi-restaurant menu and ordering API. This layout mirrors a fresh Laravel 11 project with opinionated defaults for Sanctum auth, roles/permissions, and WhatsApp-ready order messaging.

## Quick start
1. Install PHP 8.2+, Composer, Node 20+.
2. `composer install`
3. Copy `.env.example` to `.env` and update DB/Paymob/WhatsApp values.
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `php artisan storage:link`
7. `php artisan serve` (or your shared-hosting webroot).

## Included
- API routes for admin/menu/orders/payments under `/api/v1/...`.
- Eloquent models with relationships for restaurants, categories, products, options, orders, coupons, and payments.
- Controllers implementing login, menu browsing, cart validation, order creation with WhatsApp payload, and admin order status updates.
- Seeders to create a demo restaurant, users, categories/products/options, and sample coupons.

## Structure
- `app/Models`: Eloquent models and relationships.
- `app/Http/Controllers/Admin`: Auth, CRUD, and order management controllers.
- `app/Http/Controllers/Menu`: Customer menu, cart, and checkout controllers.
- `routes/api.php`: Versioned API definitions with middleware for Sanctum and roles.
- `database/migrations`: Schema creation matching `docs/database-schema.sql`.
- `database/seeders`: Demo data and role/user seeds.

## Notes for shared hosting
- Serve `public/index.php` as the document root.
- Point `storage` to a writable path and run `php artisan storage:link` once.
- Use `php artisan config:cache route:cache` for performance.
