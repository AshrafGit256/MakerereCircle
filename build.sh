#!/usr/bin/env bash

echo "🚀 Preparing Laravel for Docker deployment..."

# Create .env file from production template if it exists
if [ -f ".env.production" ]; then
    echo "📄 Setting up production environment..."
    cp .env.production .env
else
    echo "📄 Creating default .env file..."
    cat > .env << EOF
APP_ENV=production
APP_DEBUG=false
APP_KEY=
DB_CONNECTION=pgsql
SESSION_DRIVER=database
CACHE_DRIVER=database
FILESYSTEM_DISK=database
EOF
fi

# Install dependencies locally for pre-build checks
echo "📦 Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --force

echo "✅ Laravel is ready for Docker build!"