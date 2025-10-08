# Use the official PHP-FPM image
FROM php:8.2-fpm

# Install system dependencies and PostgreSQL extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Expose port 8000 for Laravel
EXPOSE 8000

# Start Laravel's development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
