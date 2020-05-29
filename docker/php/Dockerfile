FROM php:fpm-alpine
RUN docker-php-ext-install opcache
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install apcu && docker-php-ext-enable apcu

COPY ./conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
