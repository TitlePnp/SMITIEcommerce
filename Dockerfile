ARG PHP_VERSION=8
FROM php:${PHP_VERSION}-apache
RUN apt-get update && \
    apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mysqli \
    && groupadd -r apache \
    && useradd -r -g apache apache-user
USER apache-user
COPY . /var/www/html/