#!/usr/bin/env bash

echo "🚀 Starting Laravel deployment..."

# Install dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate --force

# Clear all caches
php artisan optimize:clear

# Cache everything for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build complete!"