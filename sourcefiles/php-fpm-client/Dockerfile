FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
        libicu-dev zlib1g-dev libzip-dev unzip \
    && docker-php-ext-install intl zip

WORKDIR "/client"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1000 www-data
