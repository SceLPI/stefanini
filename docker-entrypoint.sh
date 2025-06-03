#!/bin/bash

composer install --no-interaction
echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

echo "=== NGINX CONFIG DUMP ==="
cat /etc/nginx/conf.d/default.conf

ps aux | grep php-fpm

cat /etc/nginx/nginx.conf

php-fpm &
nginx -g "daemon off;"
