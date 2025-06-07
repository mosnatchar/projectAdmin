FROM php:8.1-apache

RUN apt-get update && apt-get install -y unzip curl \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . /var/www/html
