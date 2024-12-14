#!/bin/bash
 npm run build
 php artisan migrate --force
 php artisan db:seed --force

