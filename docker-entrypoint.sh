#!/bin/bash
set -e

# Wait for database to be ready (optional but helpful)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database to be ready..."
    until nc -z -v -w30 $DB_HOST ${DB_PORT:-5432}
    do
        echo "Waiting for database connection..."
        sleep 1
    done
    echo "Database is ready!"
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force --no-interaction

# Create storage symlink if it doesn't exist
echo "Creating storage symlink..."
php artisan storage:link || true

# Start Apache
echo "Starting Apache..."
exec "$@"