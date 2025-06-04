#!/bin/bash

if [ ! -d "vendor" ]; then
  echo "Running composer install (folder does not exists - RENDER)..."
  composer install --no-interaction --prefer-dist
else
  echo "vendor/ already exists, jumping"
fi

touch /var/log/php_errors.log
chmod 777 /var/log/php_errors.log
echo "log_errors=On" > /usr/local/etc/php/conf.d/docker-fpm.ini
echo "error_log=/var/log/php_errors.log" >> /usr/local/etc/php/conf.d/docker-fpm.ini
echo "display_errors=On" >> /usr/local/etc/php/conf.d/docker-fpm.ini
echo "display_startup_errors=On" >> /usr/local/etc/php/conf.d/docker-fpm.ini
echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/docker-fpm.ini

cp .env.example .env
php artisan key:generate
rm -rf /var/www/html/storage/logs/laravel.log
touch /var/www/html/storage/logs/laravel.log
chown -R www-data:www-data /var/www/html

echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
php artisan migrate --force
php-fpm &
nginx -g "daemon off;"
