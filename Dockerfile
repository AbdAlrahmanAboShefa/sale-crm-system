FROM php:8.3-cli

RUN apt-get update -y && apt-get install -y \
    openssl zip unzip git libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean

WORKDIR /app

COPY . /app

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]