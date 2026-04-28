#!/bin/bash
set -e

# Solo ejecutar instalación si RUN_SETUP es true
if [ "$RUN_SETUP" = "true" ]; then
    echo "Iniciando configuración de la aplicación..."

    # Instalar dependencias si no existen o si composer.json es más nuevo que vendor
    if [ ! -d "vendor" ] || [ composer.json -nt vendor ]; then
        echo "Instalando dependencias de PHP con Nova..."
        composer install --no-interaction --optimize-autoloader --no-dev
    fi

    if [ ! -d "node_modules" ] || [ package.json -nt node_modules ]; then
        echo "Instalando dependencias de JS..."
        npm install
    fi

    # Ejecutar el script de inicio original para migraciones y build
    echo "Ejecutando build y migraciones..."
    /usr/local/bin/run-start.sh
else
    echo "Omitiendo configuración (RUN_SETUP=false)..."
fi

# Ejecutar el comando pasado al contenedor
exec "$@"
