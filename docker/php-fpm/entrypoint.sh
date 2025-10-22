#!/bin/bash
set -e

echo "Starting entrypoint script..."

if [ -d "/var/www/storage" ]; then
    echo "Setting permissions for /var/www/storage"
    chown -R www-data:www-data /var/www/storage
    chmod -R 775 /var/www/storage
fi

if [ -d "/var/www/bootstrap/cache" ]; then
    echo "Setting permissions for /var/www/bootstrap/cache"
    chown -R www-data:www-data /var/www/bootstrap/cache
    chmod -R 775 /var/www/bootstrap/cache
fi

if [ -f "/var/log/php-fpm-slowlog-site.log" ]; then
    echo "Setting permissions for slowlog"
    chown www-data:www-data /var/log/php-fpm-slowlog-site.log
    chmod 664 /var/log/php-fpm-slowlog-site.log
fi

echo "Entrypoint complete, starting: $@"

exec "$@"