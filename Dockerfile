FROM php:8.2-fpm

# Installation des dépendences 
RUN apt-get update && apt-get install -y \
git \
unzip \
libssl-dev \
libcurl4-openssl-dev \
pkg-config

# Installation dde MySQL pour Doctrine
RUN docker-php-ext-install pdo pdo_mysql

# Installation de MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html