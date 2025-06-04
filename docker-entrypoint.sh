#!/bin/bash

if [ ! -d "vendor" ]; then
  echo "Running composer install (folder does not exists - RENDER)..."
  composer install --no-interaction --prefer-dist
else
  echo "vendor/ already exists, jumping"
fi

echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
php artisan migrate --force
php-fpm &
nginx -g "daemon off;"
