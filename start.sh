#!/bin/bash

# Exit on error
set -e

# Fix permissions for storage (critical for Railway Volumes)
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Run Migrations
# Run Migrations
echo "Running Migrations..."
php artisan migrate --force

# Link Storage
echo "Linking Storage..."
php artisan storage:link

# Clear and Cache Config
echo "Caching Configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM in background
echo "Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx..."
nginx -g "daemon off;"
