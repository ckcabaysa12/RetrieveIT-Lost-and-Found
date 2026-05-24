# Lost & Found Management System

Capstone project: **Web-Based Lost and Found Management System** with user verification (blue check), trust-based claiming, and safe pickup scheduling.

**Stack:** Laravel 13 · MySQL (`lost_found_db`) · Bootstrap 5 · WAMP64

---

## WAMP setup

1. Start **WAMP** — Apache and MySQL must be green.
2. Project path: `C:\wamp64\www\lost-found-ms`
3. Open in browser: **http://localhost/lost-found-ms/public**

If you use Laravel’s dev server instead:

```bash
cd C:\wamp64\www\lost-found-ms
php artisan serve
```

Then open **http://127.0.0.1:8000**

---

## Database

| Setting   | Value            |
|-----------|------------------|
| Database  | `lost_found_db`  |
| Host      | `127.0.0.1`      |
| Port      | `3306`           |
| Username  | `root`           |
| Password  | *(empty on WAMP)* |

Create DB manually (if needed):

```sql
CREATE DATABASE lost_found_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Run migrations and seed:

```bash
php artisan migrate:fresh --seed
```

---

## Default login accounts

| Role  | Email                   | Password   |
|-------|-------------------------|------------|
| Admin | `admin@lostfound.test`  | `password` |
| User  | `user@lostfound.test`   | `password` |

---

## Database tables

- **users** — auth, `role`, ID upload, verification (pending / verified / rejected), `is_verified`
- **categories** — wallet, phone, bag, etc.
- **items** — lost/found posts
- **claims** — claim requests with optional `claim_code`
- **pickups** — safe location, date, time

---

## Next steps (development)

- [ ] Auth UI (Breeze Bootstrap) — login, register, profile
- [ ] ID upload on registration
- [ ] Admin verification dashboard
- [ ] Lost/Found item CRUD + image upload
- [ ] Claim flow + admin approve/reject
- [ ] Pickup scheduling (restricted locations)
- [ ] Smart item matching (keyword/category/location/date)
- [ ] Reports for admin

---

## Useful commands

```bash
composer install
npm install
npm run build
php artisan migrate:fresh --seed
php artisan breeze:install blade       # auth scaffolding (Bootstrap UI via CDN in layouts)
```
