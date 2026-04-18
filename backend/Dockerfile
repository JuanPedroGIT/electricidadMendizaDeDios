FROM php:8.4-fpm-alpine AS base

RUN apk add --no-cache postgresql-dev icu-dev oniguruma-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_pgsql intl opcache \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

FROM base AS dev
RUN apk add --no-cache linux-headers $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del $PHPIZE_DEPS
COPY composer.json composer.lock* ./
RUN composer install --no-scripts --no-interaction
ENV APP_ENV=dev
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

FROM base AS prod
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction
COPY . .
ENV APP_ENV=prod
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
