FROM php:8.4-fpm

WORKDIR /var/www/html
COPY . /var/www/html

ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public

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
    bash \
    libpq-dev \
    nginx

RUN pecl install redis && \
    pecl install xdebug && \
    docker-php-ext-install mysqli pdo_mysql && \
    docker-php-ext-enable redis && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-install pdo pdo_pgsql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    chmod 777 composer.phar && \
    mv composer.phar /usr/local/bin/composer

RUN chown -R www-data:www-data /var/www/html

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN chmod +x /usr/local/bin/docker-entrypoint.sh
RUN sed -i 's/^;daemonize = yes/daemonize = no/' /usr/local/etc/php-fpm.conf

RUN rm -f /etc/nginx/sites-enabled/default
RUN nginx -t

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
