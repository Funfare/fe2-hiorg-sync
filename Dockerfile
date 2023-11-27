FROM php:8.2-apache as production

WORKDIR /var/www
ENV APACHE_DOCUMENT_ROOT /var/www/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN apt-get update

#install some basic tools
RUN apt-get install -y \
        git \
        libzip-dev \
        zlib1g-dev \
        zip \
  && docker-php-ext-install zip

COPY . /var/www

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN /usr/local/bin/composer install --no-dev
