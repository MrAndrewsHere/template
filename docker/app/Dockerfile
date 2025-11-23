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

# RUN install-php-extensions \
#     sockets \
#     xhprof

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

RUN node --version \
    && npm --version

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*


EXPOSE 80
EXPOSE 5173
