#!/bin/sh

# Ejecutar migraciones
php artisan migrate --force

# Iniciar PHP-FPM
php-fpm -D

# Iniciar Nginx en primer plano
nginx -g "daemon off;"
