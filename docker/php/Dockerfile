FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    && docker-php-ext-install zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html