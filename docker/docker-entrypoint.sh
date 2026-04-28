#!/bin/bash
set -e

if [ "$RUN_SETUP" = "true" ]; then
    echo ">>> Corriendo migraciones..."
    php artisan migrate --force
    php artisan optimize:clear
    php artisan storage:link
fi

exec "$@"
