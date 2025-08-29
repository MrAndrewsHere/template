FROM dunglas/frankenphp

WORKDIR /var/www/html

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
    libxml2-dev

RUN install-php-extensions \
    gd \
    pcntl \
    opcache \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    redis \
    zip \
    mbstring \
    exif \
    bcmath

RUN install-php-extensions \
    intl \
    xsl \
    curl

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

EXPOSE 80
