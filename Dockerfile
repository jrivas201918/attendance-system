# Use the official PHP image with FPM and required extensions
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip pdo_mysql

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /app

# Copy all project files into the container
COPY . .

# Copy .env.example to .env if .env does not exist
RUN cp .env.example .env

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate Laravel app key (will fail if .env is missing, so make sure it's there)
RUN php artisan key:generate || true

# Expose port 8000 for Laravel's built-in server
EXPOSE 8000

# Run migrations and start the Laravel server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
