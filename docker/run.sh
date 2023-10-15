#!/bin/sh

cd /var/www

rm bootstrap/cache/*.php -f

chmod 777 storage/logs/laravel.log
chmod -R 777 storage/framework/sessions/*

composer install --optimize-autoloader --no-dev

php artisan migrate --force

touch .env
APP_KEY=$(grep "\<APP_KEY\>" .env)

if [ -z "$APP_KEY" ]
then
echo APP_KEY= >> .env
fi

php artisan key:generate --force

php artisan cache:clear
php artisan route:clear
php artisan config:clear
