#!/usr/bin/env bash

echo "🚀 Deploying Mak Social..."

# Check if we're on Render
if [ -n "$RENDER" ]; then
    echo "📋 Using Render environment variables"
else
    echo "📋 Using local .env file"
    if [ -f ".env.production" ]; then
        cp .env.production .env
    fi
fi

# Install dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Generate key if not set
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "base64:" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment complete!"

#!/usr/bin/env bash

echo "🚀 Deploying..."

# Install dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --ignore-platform-reqs

# Create .env if missing
touch .env

# Generate key
php artisan key:generate --force

# Wait for database
echo "⏳ Waiting for PostgreSQL..."
sleep 15

# Run migrations
php artisan migrate --force

echo "✅ Done!"