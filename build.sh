#!/usr/bin/env bash

echo "🚀 Starting Laravel deployment on Render..."

# Copy production environment file if it exists
if [ -f ".env.production" ]; then
    echo "📄 Copying production environment file..."
    cp .env.production .env
fi

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Generate application key if not exists
echo "🔑 Generating application key..."
php artisan key:generate --force

# Create cache and session tables if they don't exist
echo "🗄️ Setting up database tables..."
php artisan session:table
php artisan cache:table

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Cache everything for production
echo "⚡ Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build complete!"