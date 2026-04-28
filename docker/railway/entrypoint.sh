#!/bin/sh
set -e

# Solo ejecutar optimizaciones si es el servicio web (detectado por la presencia de PORT)
if [ -n "$PORT" ]; then
    echo "Configurando Nginx para el puerto $PORT..."
    sed -i "s/\${PORT}/${PORT}/g" /etc/nginx/http.d/default.conf

    echo "Optimizando Laravel..."
    php artisan optimize:clear
    php artisan storage:link

    if [ "$RUN_MIGRATIONS" = "true" ]; then
        echo "Ejecutando migraciones..."
        php artisan migrate --force
    fi
fi

# Ejecutar el comando pasado al contenedor (exec asegura que el proceso reciba las señales de Railway)
exec "$@"
