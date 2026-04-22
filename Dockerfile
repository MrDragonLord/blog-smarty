FROM php:8.4-apache

ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y --no-install-recommends \
    default-mysql-client \
    libzip-dev \
    nodejs \
    npm \
    unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite \
    && sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && rm -rf /var/lib/apt/lists/*

RUN groupmod -o -g ${GID} www-data \
    && usermod -o -u ${UID} -g www-data www-data

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-interaction

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

RUN npm run build:css

RUN mkdir -p \
    /var/www/html/var/cache/templates \
    /var/www/html/var/cache/compile \
    /var/www/html/var/cache/config \
    /var/www/html/var/cache/output \
    /var/www/html/public/assets/css \
    /var/www/html/vendor \
    /var/www/html/node_modules \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/var /var/www/html/public/assets

EXPOSE 80
