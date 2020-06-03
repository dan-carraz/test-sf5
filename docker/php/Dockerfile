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

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php  --filename=composer --install-dir="/usr/bin"\
    && php -r "unlink('composer-setup.php');"
