#!/bin/bash
set -e

# Solo ejecutar configuración si RUN_SETUP es true
if [ "$RUN_SETUP" = "true" ]; then
    echo ">>> Iniciando despliegue de la aplicación (RUN_SETUP=true)"

    # Verificar si necesitamos instalar dependencias
    # Si no existe vendor o si composer.json es más nuevo que vendor
    if [ ! -d "vendor" ]; then
        echo ">>> Instalando dependencias de PHP (esto puede tardar unos minutos)..."
        # Usamos --no-dev y --optimize-autoloader para producción
        composer install --no-interaction --no-scripts --prefer-dist --no-dev --optimize-autoloader
    fi

    if [ ! -d "node_modules" ]; then
        echo ">>> Instalando dependencias de Node.js..."
        npm install
    fi

    echo ">>> Compilando assets..."
    npm run build

    echo ">>> Ejecutando tareas de Laravel (migraciones, optimización)..."
    /usr/local/bin/run-start.sh
    
    echo ">>> Despliegue completado con éxito."
else
    echo ">>> Omitiendo configuración (RUN_SETUP=false). Esperando a que el contenedor principal prepare el volumen..."
    # Esperar un poco a que el volumen esté listo (opcional pero recomendado para worker/cron)
    sleep 5
fi

# Ejecutar el comando pasado al contenedor
exec "$@"
