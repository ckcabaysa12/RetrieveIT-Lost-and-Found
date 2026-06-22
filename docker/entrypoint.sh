#!/bin/sh
set -e

if [ -f artisan ]; then
    php artisan storage:link --force 2>/dev/null || true
    php artisan migrate --force --no-ansi || true
    php artisan config:cache --no-ansi || true
    php artisan route:cache --no-ansi || true
    php artisan view:cache --no-ansi || true
fi

exec "$@"
