FROM node:20 as build
WORKDIR /var/www
COPY . /var/www

RUN npm install && npm run build


FROM php:8.2-apache as production

WORKDIR /var/www
ENV APACHE_DOCUMENT_ROOT /var/www/public
ENV NODE_VERSION=20.10.0
ENV NVM_DIR=/root/.nvm

RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN apt-get update

#install some basic tools
RUN apt-get install -y \
        git \
        libzip-dev \
        zlib1g-dev \
        zip \
        curl \
  && docker-php-ext-install zip pdo pdo_mysql

COPY . /var/www
COPY --from=build /var/www/public /var/www/public

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN /usr/local/bin/composer install --no-dev
