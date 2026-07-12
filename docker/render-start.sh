#!/usr/bin/env bash
set -e

cd /var/www/html

export PORT="${PORT:-10000}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache database
touch database/database.sqlite
chown -R www-data:www-data storage bootstrap/cache database
chmod -R ug+rwx storage bootstrap/cache database

php artisan optimize:clear
php artisan migrate --force

if [ "${RUN_SEEDERS:-true}" = "true" ]; then
    php artisan db:seed --force
fi

php artisan storage:link --force || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
