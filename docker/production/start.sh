#!/usr/bin/env bash
set -euo pipefail

node /assets/scripts/prestart.mjs /assets/nginx.template.conf /nginx.conf

mkdir -p /app/storage/logs \
    /app/storage/framework/cache \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/bootstrap/cache
chmod -R ug+rwx /app/storage /app/bootstrap/cache || true

php /app/artisan migrate --force

exec supervisord -c /app/docker/production/supervisord.conf -n
