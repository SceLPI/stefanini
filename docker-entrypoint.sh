#!/bin/bash

composer install --no-interaction
echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
service nginx start

exec php-fpm
