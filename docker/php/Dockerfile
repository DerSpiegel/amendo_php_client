FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y libxml2-dev \
    && docker-php-ext-install soap
