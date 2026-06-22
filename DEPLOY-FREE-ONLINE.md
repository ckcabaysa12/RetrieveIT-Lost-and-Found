# Free Online Deployment (RetrieveIT)

Best **free online** option for Laravel + MySQL + **XML/XSLT**.

---

## Recommended: Oracle Cloud Always Free VM

**Cost:** $0/month (Always Free tier)  
**Why:** Full server control — you can install PHP **xsl**, MySQL, Nginx, SSL, and image uploads.

> Oracle may ask for a **credit card to verify identity**. Stay on **Always Free** resources only — you will not be charged if you pick the free ARM VM shape.

### What you get

- Public URL (your VM IP or free domain)
- MySQL database
- PHP 8.3 with **xsl** extension (for XSLT reports)
- Real online deployment for IPT defense

---

## Step-by-step (Oracle Cloud)

### 1. Create account

1. Go to https://www.oracle.com/cloud/free/
2. Sign up and create a **Always Free** ARM VM (Ubuntu 22.04 or 24.04)
3. Open ports in **Networking → Security List**:
   - `22` (SSH)
   - `80` (HTTP)
   - `443` (HTTPS, optional)

### 2. SSH into the server

```bash
ssh ubuntu@YOUR_VM_PUBLIC_IP
```

### 3. Install Docker

```bash
sudo apt update
sudo apt install -y docker.io docker-compose-plugin git
sudo usermod -aG docker $USER
newgrp docker
```

### 4. Clone your project

```bash
git clone https://github.com/ckcabaysa12/RetrieveIT-Lost-and-Found.git
cd RetrieveIT-Lost-and-Found
```

### 5. Create `.env`

```bash
cp .env.example .env
nano .env
```

Set:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_VM_PUBLIC_IP

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=lost_found_db
DB_USERNAME=retrieveit
DB_PASSWORD=choose-a-strong-password

DB_ROOT_PASSWORD=another-strong-password
```

Generate key **on your PC** (or install composer on VM):

```bash
php artisan key:generate --show
```

Paste the key into `APP_KEY=` on the server.

### 6. Start the app

```bash
docker compose -f docker-compose.prod.yml up -d --build
docker compose -f docker-compose.prod.yml exec app php artisan migrate --seed --force
```

The container auto-runs `storage:link` on startup.

### 7. Open in browser

```
http://YOUR_VM_PUBLIC_IP
```

Login: `admin@lostfound.test` / `password`

### 9. Test XML / XSLT

- `http://YOUR_VM_PUBLIC_IP/admin/reports/xml`
- `http://YOUR_VM_PUBLIC_IP/admin/reports/transform`

---

## Alternative free options (comparison)

| Platform | Free? | Laravel | MySQL | XSL/XSLT | Verdict |
|----------|-------|---------|-------|----------|---------|
| **Oracle Cloud VM** | Yes (always) | Yes | Yes | Yes | **Best** |
| InfinityFree | Yes | Hard | Yes | Usually no | Not recommended |
| Railway | Trial credits | Yes | Yes | Yes (Docker) | Runs out of credits |
| Render | Limited free | Yes | Paid | Yes (Docker) | DB costs money |
| ngrok / tunnel | Yes | N/A | N/A | N/A | Still local, not real hosting |

---

## If you have a `.edu` student email

Check **GitHub Student Developer Pack** — may include free credits for:

- DigitalOcean
- Azure
- Heroku alternatives

Still use the same Docker setup above.

---

## After deployment checklist

- [ ] Site loads at public IP
- [ ] Register / login works
- [ ] Image upload works
- [ ] Admin reports → XML opens
- [ ] Admin reports → XSLT transform opens
- [ ] `APP_DEBUG=false` in production

---

## Share with classmates

They do **not** need to deploy separately. One person deploys online; everyone else uses the public URL.

For development, classmates still use local WAMP + `git pull`.
