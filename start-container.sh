#!/bin/bash
set -euo pipefail

php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan optimize

php artisan queue:work --sleep=3 --tries=3 --timeout=0 --max-time=3600 --no-interaction &
php artisan schedule:work --no-interaction &
if [ -n "${NIGHTWATCH_TOKEN:-}" ]; then
    php artisan nightwatch:agent &
fi

exec docker-php-entrypoint --config /Caddyfile --adapter caddyfile
