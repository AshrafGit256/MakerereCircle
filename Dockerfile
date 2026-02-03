# Dockerfile for a production Laravel application on Render
# Optimized for Render's deployment environment

# ---- Base PHP Stage ----
FROM php:8.2-apache as base

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive \
    APACHE_DOCUMENT_ROOT=/var/www/html/public \
    PORT=80

# Update Apache configuration to use the /public directory and enable mod_rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite headers

# Install required system packages and PHP extensions for Laravel + Supabase
RUN apt-get update && apt-get install -y \
    netcat \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        exif \
        pcntl \
        bcmath \
        zip \
        pdo \
        pdo_pgsql \
        pgsql \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure PHP for production (OPcache optimization)
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=256'; \
        echo 'opcache.interned_strings_buffer=16'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.validate_timestamps=0'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=0'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Configure Apache to listen on the PORT environment variable (Render requirement)
RUN echo "Listen \${PORT}" > /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf

# ---- Composer Stage ----
FROM composer:2 as composer_deps
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
# Install dependencies, optimizing for production
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

# ---- Frontend Stage ----
FROM node:18-alpine as node_deps
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --only=production
COPY . .
# Build production assets
RUN npm run build

# ---- Production Stage ----
FROM base as production

# Set working directory
WORKDIR /var/www/html

# Copy installed dependencies from previous stages
COPY --from=composer_deps /app/vendor ./vendor
COPY --from=node_deps /app/public/build ./public/build

# Copy the rest of the application code
COPY . .

# Run Laravel optimization command
RUN php artisan optimize

# Set correct permissions for Laravel's storage and cache directories
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Create a startup script to handle migrations and symlinks
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port (Render will use the PORT env var)
EXPOSE ${PORT}

# Use custom entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]