#!/bin/bash
 npm run build
 php artisan optimize:clear && php artisan storage:link && php artisan migrate --force && php artisan passport:keys


