FROM composer:2.1 AS composer

FROM php:7.4-fpm as base

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    openssh-client \
    libpq-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libmagickwand-dev \
    ffmpeg


RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Gcron for scheduler
COPY contrib/gcron /usr/local/bin/

# Script that will wait on the availability of a host and TCP port
# for synchronizing the spin-up of linked docker containers in CI (https://github.com/vishnubob/wait-for-it)
COPY contrib/wait-for-it.sh /usr/local/bin/

# PHP extensions
COPY contrib/php.ini $PHP_INI_DIR/conf.d/php.ini
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install  pdo_pgsql pdo_mysql mysqli bcmath opcache gd exif && \
    docker-php-ext-enable pdo_mysql mysqli opcache gd pdo_pgsql

WORKDIR /src

ENV PATH="$PATH:/src/vendor/bin"
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY composer.* ./
RUN pecl install imagick && docker-php-ext-enable imagick

FROM base as prod
RUN composer install --no-scripts --no-autoloader --no-interaction --no-dev

COPY . ./
RUN chgrp -R www-data storage bootstrap/cache && chmod -R ug+rwx storage bootstrap/cache \
    && composer dump-autoload --optimize

FROM base as dev
RUN composer install --no-scripts --no-autoloader --no-interaction --dev

COPY . ./
RUN chgrp -R www-data storage bootstrap/cache && chmod -R ug+rwx storage bootstrap/cache \
    && composer dump-autoload --optimize
