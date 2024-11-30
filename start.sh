#!/bin/bash

# Iniciar PHP-FPM
php-fpm &

# Iniciar NGINX en primer plano
nginx -g "daemon off;"
