# ============================================================
# STAGE 1: Composer - Install PHP dependencies without dev
# ============================================================
FROM composer:2.7 AS composer-stage

WORKDIR /app

# Copy backend source code
COPY . .

# Install production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ============================================================
# STAGE 2: Runtime - PHP-FPM + Nginx serving Laravel
# ============================================================
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
        nginx \
        bash \
        git \
        curl \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        zip \
        unzip \
        oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache

WORKDIR /var/www/html

COPY --from=composer-stage /app /var/www/html

COPY deployment/backend/.env.production /var/www/html/.env

COPY deployment/backend/nginx.conf /etc/nginx/nginx.conf

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN php artisan config:cache && \
    php artisan route:cache

COPY deployment/backend/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]