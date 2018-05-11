FROM php:7.2-apache

MAINTAINER Brian J. Sanders

COPY . /opt/user-details-poc
COPY .docker/apache-000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /opt/user-details-poc

RUN docker-php-ext-install pdo_mysql \
    && chown -R www-data:www-data /opt/user-details-poc \
    && a2enmod rewrite

