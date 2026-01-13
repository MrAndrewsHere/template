ARG PHP_VERSION=8.3
ARG NODE_VERSION=22

FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

# RUN --mount=type=cache,target=/tmp/cache \
#     composer install \
#     --no-interaction \
#     --prefer-dist \
#     --profile \
#     --ansi \
#     2>&1 | tee /var/log/composer-install.log

FROM node:${NODE_VERSION}-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
COPY vite.config.js ./
COPY resources/ ./resources/

RUN --mount=type=cache,target=/root/.npm \
    npm ci --quiet && npm run build

FROM dunglas/frankenphp:1.0-php${PHP_VERSION} AS base

RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
    git \
    unzip \
    supervisor \
    librabbitmq-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm

RUN install-php-extensions \
    # bcmath \
    curl \
    # exif \
    # gd \
    intl \
    mbstring \
    # opcache \
    pcntl \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    # pgsql \
    redis \
    # xsl \
    zip

WORKDIR /var/www/html

COPY --from=frontend /app/public/build ./public/build/
COPY --from=vendor /usr/bin/composer /usr/local/bin/composer

COPY . .

RUN chmod -R 777 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

RUN git config --global --add safe.directory /var/www/html

# HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
#     CMD curl -f http://localhost/health || exit 1

EXPOSE 80
EXPOSE 5173

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
