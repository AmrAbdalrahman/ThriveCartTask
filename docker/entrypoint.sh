#!/bin/bash

# Fix permissions for Laravel storage and cache directories
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel commands
php artisan cache:clear
php artisan config:clear
php artisan config:cache
php artisan migrate --seed

# Execute the original command
exec "$@"
