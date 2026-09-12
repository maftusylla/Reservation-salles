FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

COPY . .

COPY docker/ssl/ca.pem /etc/ssl/aiven/ca.pem

RUN chown -R www-data:www-data /var/www/html

EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]