#!/bin/sh
# set -e  <-- COMENTAMOS ESTA LÍNEA TEMPORALMENTE

# Nos aseguramos de que PORT exista, si no, usamos 80 por defecto
PORT=${PORT:-80}

# Solo ejecutar tareas de inicio si es el servicio web
if [ -n "$PORT" ]; then
    echo "Configurando Nginx para el puerto $PORT..."
    sed -i "s/\${PORT}/${PORT}/g" /etc/nginx/http.d/default.conf

    echo "Optimizando Laravel (Modo Seguro)..."
    # Añadimos || true para ignorar errores fatales y que el contenedor siga vivo
    php artisan optimize:clear || true
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
    php artisan storage:link || true

    if [ "$RUN_MIGRATIONS" = "true" ]; then
        echo "Ejecutando migraciones..."
        php artisan migrate --force || true
    fi

    echo "Validacion del schema graphql..."
    php artisan lighthouse:validate-schema || true

    echo "Verificando la licencia de Laravel Nova..."
    php artisan nova:check-license || true
fi

# Ejecutar el comando pasado al contenedor
echo "Iniciando proceso: $@"
exec "$@"
