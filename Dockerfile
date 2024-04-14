ARG PHP_VERSION=8
FROM php:${PHP_VERSION}-apache
RUN groupadd -r apache && useradd -r -g apache apache-user && docker-php-ext-install mysqli
USER apache-user
COPY . /var/www/html/