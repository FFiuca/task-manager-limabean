# Stage 1: Composer
FROM composer:2 AS build

WORKDIR /app

# Copy all application files
COPY . .

# Install dependencies, including dev dependencies
RUN composer install --prefer-dist --optimize-autoloader

# Stage 2: Laravel + Nginx + PHP-FPM
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev nginx netcat-openbsd \
    && docker-php-ext-install pdo pdo_mysql zip

# Configure Nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Set working directory
WORKDIR /var/www/html

# Copy built app from build stage
COPY --from=build /app /var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose ports for Nginx
EXPOSE 80

# Start Nginx and PHP-FPM
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
