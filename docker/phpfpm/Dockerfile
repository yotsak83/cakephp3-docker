FROM php:7.3-fpm-buster

RUN apt-get update && \
    apt-get install -y curl git zip unzip libicu-dev && \ 
    docker-php-ext-install intl pdo_mysql mbstring
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

