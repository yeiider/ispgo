#!/bin/bash
set -e

# Solo ejecutar configuración si RUN_SETUP es true
if [ "$RUN_SETUP" = "true" ]; then
    echo "Iniciando configuración de la aplicación (Migraciones y Optimización)..."

    # Ejecutar el script de inicio para migraciones y cache
    # Nota: El build de assets ya se hizo durante el build de la imagen
    /usr/local/bin/run-start.sh
else
    echo "Omitiendo configuración inicial (RUN_SETUP=false)..."
fi

# Ejecutar el comando pasado al contenedor (usualmente supervisord)
exec "$@"
