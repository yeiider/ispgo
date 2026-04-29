#!/bin/sh
set -e

# Solo ejecutar tareas de inicio si es el servicio web (detectado por la presencia de PORT)
if [ -n "$PORT" ]; then
    echo "Configurando Nginx para el puerto $PORT..."
    sed -i "s/\${PORT}/${PORT}/g" /etc/nginx/http.d/default.conf

    echo "Optimizando Laravel..."
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link

    if [ "$RUN_MIGRATIONS" = "true" ]; then
        echo "Ejecutando migraciones..."
        php artisan migrate --force
    fi

    echo "Validacion del schema graphql..."
    php artisan lighthouse:validate-schema || echo "Advertencia: Error validando schema"

    echo "Verificando la licencia de Laravel Nova..."
    php artisan nova:check-license || echo "Advertencia: Error verificando licencia"
fi

# Ejecutar el comando pasado al contenedor (exec asegura que el proceso reciba las señales de Railway)
# Si no hay comando, se usará el CMD por defecto del Dockerfile
echo "Iniciando proceso: $@"
exec "$@"
