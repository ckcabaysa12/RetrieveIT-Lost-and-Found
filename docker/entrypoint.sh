#!/bin/sh
set -e

if [ -f artisan ]; then
    if [ -z "$APP_KEY" ]; then
        echo "[entrypoint] ERROR: APP_KEY is not set. Add it under Render → Environment."
        exit 1
    fi

    echo "[entrypoint] Preparing upload directories..."
    mkdir -p storage/app/public/id_cards storage/app/public/items storage/app/public/profiles
    chown -R www-data:www-data storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache

    echo "[entrypoint] Linking storage..."
    php artisan storage:link --force --no-ansi

    echo "[entrypoint] Clearing cached config..."
    php artisan config:clear --no-ansi 2>/dev/null || true

    echo "[entrypoint] Running migrations and seeders..."
    php artisan migrate --seed --force --no-ansi -v

    echo "[entrypoint] Caching config, routes, and views..."
    php artisan config:cache --no-ansi
    php artisan route:cache --no-ansi
    php artisan view:cache --no-ansi

    echo "[entrypoint] Ready."
fi

exec "$@"
