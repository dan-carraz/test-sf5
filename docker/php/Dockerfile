FROM php:fpm-alpine
WORKDIR /srv/app

RUN apk add --update autoconf
RUN apk add --update icu-dev

RUN docker-php-ext-install opcache \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install intl \
    && docker-php-ext-configure intl

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install apcu && docker-php-ext-enable apcu

COPY ./conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN EXPECTED_CHECKSUM="$(wget -q -O - https://composer.github.io/installer.sig)" \
&& php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

RUN if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then echo 'ERROR: Invalid installer checksum'; exit 1; fi;

RUN php composer-setup.php --quiet --filename=composer --install-dir="/usr/bin" \
&& rm composer-setup.php
