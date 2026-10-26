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
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]

FROM base AS prod
RUN printf "opcache.enable=1\nopcache.memory_consumption=128\nopcache.max_accelerated_files=10000\nopcache.validate_timestamps=0\n" \
    > /usr/local/etc/php/conf.d/zz-opcache-prod.ini
# Defensa en profundidad: desactiva el upload progress de sesion (mitiga vectores RCE tipo PHP2Shell)
RUN printf "session.upload_progress.enabled=0\n" > /usr/local/etc/php/conf.d/zz-security.ini
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
COPY . .
RUN mkdir -p var/cache var/log
RUN chmod -R 777 var/
ENV APP_ENV=prod
ENV APP_DEBUG=0
EXPOSE 8000
RUN php bin/console cache:clear --no-warmup || true
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]
