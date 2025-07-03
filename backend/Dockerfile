FROM composer:2.7 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist

FROM php:8.2-fpm

WORKDIR /var/www
RUN apt-get update && apt-get install -y libpng-dev libonig-dev libxml2-dev zip unzip git curl && \
    docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm

RUN npm install && npm run build || true

CMD php artisan migrate --force && php-fpm
