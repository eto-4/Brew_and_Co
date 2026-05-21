#!/bin/sh
set -e

echo "==> Waiting for MySQL to be ready..."
DB_HOST=$(grep ^DB_HOST /var/www/html/.env | cut -d '=' -f2)
DB_PORT=$(grep ^DB_PORT /var/www/html/.env | cut -d '=' -f2)
DB_DATABASE=$(grep ^DB_DATABASE /var/www/html/.env | cut -d '=' -f2)
DB_USERNAME=$(grep ^DB_USERNAME /var/www/html/.env | cut -d '=' -f2)
DB_PASSWORD=$(grep ^DB_PASSWORD /var/www/html/.env | cut -d '=' -f2)

until php -r "
  try {
    new PDO(
      'mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}',
      '${DB_USERNAME}',
      '${DB_PASSWORD}'
    );
    exit(0);
  } catch (Exception \$e) {
    exit(1);
  }
"; do
  echo "    MySQL not available yet, retrying in 3s..."
  sleep 3
done
echo "    MySQL ready."

echo "==> Running Laravel migrations..."
php artisan migrate --force

echo "==> Starting PHP-FPM in background..."
php-fpm -D

echo "==> Starting Nginx..."
exec nginx -g "daemon off;"