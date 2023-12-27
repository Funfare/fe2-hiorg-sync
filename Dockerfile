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

# install nvm, node and npm
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"

COPY . /var/www

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN /usr/local/bin/composer install --no-dev
RUN npm install && npm run build
