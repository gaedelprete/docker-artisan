FROM php:7.4-fpm-alpine

MAINTAINER Gaetano Del Prete <gaedelprete@gmail.com>

RUN apk update && apk add --no-cache \
    bash \
    alpine-sdk shadow vim curl \
    zip libzip-dev \
    libpng-dev

RUN docker-php-ext-install pdo_mysql zip exif gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG INSTALL_XDEBUG=false

RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    apk add --no-cache $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.remote_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
;fi

RUN rm -rf /var/cache/apk/*

RUN rm -r /var/www/html
