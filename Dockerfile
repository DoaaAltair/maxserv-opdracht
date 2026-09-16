FROM composer:2.6 AS composer

# Use the official PHP image as a base image to construct our own image from
FROM php:8.2.12-apache

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install and enable mysql modules for PHP
RUN apt-get update && \
    apt-get install -y unzip libzip-dev && \
    docker-php-ext-install mysqli pdo pdo_mysql zip && \
    docker-php-ext-enable mysqli pdo pdo_mysql zip && \
    a2enmod rewrite

WORKDIR /var/www/html

COPY composer.json composer.lock ./
COPY packages ./packages

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set an environment variable which contains the apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update the apache configuration using the `APACHE_DOCUMENT_ROOT` environment variable
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
