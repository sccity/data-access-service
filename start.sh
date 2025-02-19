#!/bin/bash
mkdir -p /var/www/html/storage/app/public/activity_files
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/bootstrap/cache

php artisan storage:link

php-fpm &

php artisan queue:work --tries=3 --timeout=90 --sleep=3 --max-jobs=1000 --max-time=3600 >> /var/log/queue.log 2>&1 &
php artisan migrate

nginx -g 'daemon off;'
