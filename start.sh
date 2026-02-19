#!/bin/bash

# Exit on error
set -e

# Run Migrations
echo "Running Migrations..."
php artisan migrate --force

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
