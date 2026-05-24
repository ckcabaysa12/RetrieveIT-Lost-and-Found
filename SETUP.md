# Lost & Found MS — WAMP Setup

## Requirements
- WAMP (Apache + MySQL + PHP 8.3+)
- Composer

## 1. Create database
Open phpMyAdmin (`http://localhost/phpmyadmin`) and run:

```sql
CREATE DATABASE lost_found_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 2. Configure `.env`
Already set for WAMP defaults:
- `DB_CONNECTION=mysql`
- `DB_DATABASE=lost_found_db`
- `DB_USERNAME=root`
- `DB_PASSWORD=` (empty)
- `APP_URL=http://localhost/lost-found-ms/public`

## 3. Install & migrate
In project folder:

```bash
composer install
php artisan storage:link
php artisan migrate --seed
```

## 4. Open in browser
```
http://localhost/lost-found-ms/public
```

## Demo accounts (after seed)
| Role  | Email                    | Password  |
|-------|--------------------------|-----------|
| Admin | admin@lostfound.test     | password  |
| User  | user@lostfound.test      | password  |

## Features (capstone scope)
- Registration with valid ID upload
- Admin verification → blue check badge
- Report lost / found items
- Browse & filter listings
- Claim found items → admin approve → claim code + safe pickup
- Smart matching (category, keywords, location, date)
- Admin: users, items, claims, categories, reports

## Troubleshooting
- **Permission denied on vendor**: Run terminal as Administrator or fix folder permissions on `c:\wamp64\www\lost-found-ms`
- **Images not showing**: Run `php artisan storage:link`
- **500 error**: Check `storage/logs/laravel.log`, enable `APP_DEBUG=true`
