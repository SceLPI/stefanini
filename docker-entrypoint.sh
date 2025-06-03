#!/bin/bash

composer install --no-interaction
echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

php-fpm &
nginx -g "daemon off;"
