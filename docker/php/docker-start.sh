#!/bin/bash
composer install
php /var/www/artisan key:generate
php /var/www/artisan migrate
# Cấp quyền ghi cho thư mục storage
# chmod -R 777 /var/www/storage
#php /var/www/artisan queue:listen --timeout=0 &


php-fpm

