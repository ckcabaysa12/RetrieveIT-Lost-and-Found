#!/bin/sh
set -e

if [ -f artisan ]; then
    echo "[entrypoint] Linking storage..."
    php artisan storage:link --force 2>/dev/null || true

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
