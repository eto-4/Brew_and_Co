#!/bin/sh
set -e

echo "==> Waiting for MySQL to be ready..."
until php -r "
  \$conn = @mysqli_connect(
    getenv('DB_HOST'), getenv('DB_USERNAME'),
    getenv('DB_PASSWORD'), getenv('DB_DATABASE'),
    (int)getenv('DB_PORT')
  );
  if (\$conn) { exit(0); }
  exit(1);
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