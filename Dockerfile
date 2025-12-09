# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install PostgreSQL extensions
RUN apt-get update && \
    apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --ignore-platform-reqs

# Generate application key
RUN php artisan key:generate --force

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage && \
    chmod -R 755 /var/www/html/storage

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Create Apache config
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n\
    </VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]