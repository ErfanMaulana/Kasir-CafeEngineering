FROM node:22-bookworm AS frontend

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM composer:2 AS dependencies

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

FROM dunglas/frankenphp:php8.3-bookworm

WORKDIR /app

RUN install-php-extensions pdo_pgsql opcache

COPY --from=dependencies /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY . .

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["frankenphp", "php-server", "-r", "public", "--listen", "0.0.0.0:8000"]