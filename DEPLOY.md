# RetrieveIT — Deployment Guide

Capstone deployment checklist for **Laravel 13 + MySQL + XML/XSLT**.

---

## Required PHP extensions

| Extension | Purpose |
|-----------|---------|
| `php` **8.3+** | Laravel 13 |
| `pdo_mysql` | Database |
| `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo` | Laravel core |
| **`xsl`** | **XSLT report transformation (IPT requirement)** |
| `gd` or `imagick` | Image uploads (recommended) |

### Enable XSL on WAMP

1. WAMP tray → **PHP** → **php.ini**
2. Find and uncomment:
   ```ini
   extension=xsl
   ```
3. Restart Apache
4. Verify:
   ```powershell
   php -m | findstr xsl
   ```

---

## Option A — School demo (WAMP / local network)

Best for defense/demo when internet hosting is not required.

1. Clone repo to `C:\wamp64\www\RetrieveIT-Lost-and-Found`
2. Create database `lost_found_db` in phpMyAdmin
3. Copy `.env.example` → `.env`, set:
   ```
   APP_URL=http://localhost/RetrieveIT-Lost-and-Found/public
   DB_DATABASE=lost_found_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Run:
   ```powershell
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
5. Open: `http://localhost/RetrieveIT-Lost-and-Found/public`

### XML / XSLT demo URLs (admin login required)

| URL | Description |
|-----|-------------|
| `/admin/reports` | Reports dashboard |
| `/admin/reports/xml` | Raw XML data export |
| `/admin/reports/transform` | HTML via XSLT (`resources/xslt/reports.xsl`) |

Login: `admin@lostfound.test` / `password`

---

## Option B — Shared hosting (cPanel)

1. Upload project files (or deploy via Git)
2. Point domain document root to **`public/`**
3. Create MySQL database + user in cPanel
4. Set `.env` production values:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```
5. SSH or Terminal in cPanel:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
6. In cPanel **Select PHP Version** → enable **xsl**, **xml**, **dom**

---

## Option C — VPS (Ubuntu + Nginx)

```bash
sudo apt update
sudo apt install nginx mysql-server php8.3-fpm php8.3-mysql php8.3-xml php8.3-xsl php8.3-mbstring php8.3-curl php8.3-zip php8.3-gd unzip git composer
```

1. Clone repo to `/var/www/retrieveit`
2. Set permissions:
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```
3. Nginx `root` → `/var/www/retrieveit/public`
4. Configure `.env`, run migrations, `storage:link`, cache commands (same as above)

---

## Production `.env` checklist

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-actual-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

Never commit `.env` to GitHub.

---

## Post-deploy verification

- [ ] Home page loads
- [ ] Login works (`admin@lostfound.test` / `password`)
- [ ] Image upload works (`php artisan storage:link`)
- [ ] Admin → Reports page loads
- [ ] **View XML report** opens valid XML
- [ ] **View XSLT-transformed report** opens styled HTML
- [ ] `php -m` shows **xsl**

---

## Classmate quick setup (after git pull)

```powershell
git pull origin main
composer install
npm install
npm run build
php artisan migrate
php artisan storage:link
```

Enable `extension=xsl` in php.ini if XSLT report fails.

---

## Troubleshooting

| Issue | Fix |
|-------|-----|
| XSLT page error | Enable `extension=xsl` in php.ini, restart Apache |
| 500 error | Check `storage/logs/laravel.log` |
| Images broken | `php artisan storage:link` |
| `composer` not found | Install Composer: https://getcomposer.org/Composer-Setup.exe |
| PHP version error | Use PHP **8.3+** |
