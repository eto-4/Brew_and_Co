#!/bin/sh
set -e

echo "==> Waiting for MySQL to be ready..."
until php -r "
  try {
    new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
      getenv('DB_USERNAME'),
      getenv('DB_PASSWORD')
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