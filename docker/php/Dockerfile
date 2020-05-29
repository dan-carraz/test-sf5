FROM php:fpm-alpine
WORKDIR /srv/app

RUN apk add --update autoconf
RUN apk add --update icu-dev

RUN docker-php-ext-install opcache \
    && docker-php-ext-install intl \
    && docker-php-ext-configure intl

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install apcu && docker-php-ext-enable apcu

COPY ./conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
