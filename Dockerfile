FROM php:8.4-fpm

EXPOSE 80

WORKDIR /var/www/html
COPY . /var/www/html

RUN apt update && \
    apt install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    git \
    unzip \
    default-mysql-client \
    iputils-ping \
    net-tools \
    nano \
    bash

RUN pecl install redis && \
    pecl install xdebug && \
    docker-php-ext-install mysqli pdo_mysql && \
    docker-php-ext-enable redis && \
    docker-php-ext-enable xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    chmod 777 composer.phar && \
    mv composer.phar /usr/local/bin/composer

RUN chown -R www-data:www-data /var/www/html

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
