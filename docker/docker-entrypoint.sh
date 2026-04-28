#!/bin/bash
set -e

if [ "$RUN_SETUP" = "true" ]; then
    echo ">>> Iniciando proceso de SETUP..."
    
    # Intentar usar COMPOSER_AUTH si está disponible
    if [ ! -z "$COMPOSER_AUTH" ]; then
        echo ">>> Configurando COMPOSER_AUTH..."
        export COMPOSER_AUTH="$COMPOSER_AUTH"
    fi

    echo ">>> Instalando dependencias de PHP (Composer)..."
    composer install --no-interaction --no-scripts --prefer-dist --no-dev --optimize-autoloader || echo "!!! Error en composer install"

    echo ">>> Instalando dependencias de JS (NPM)..."
    npm install --no-audit --no-fund || echo "!!! Error en npm install"

    echo ">>> Compilando assets (Vite)..."
    npm run build || echo "!!! Error en npm run build"

    echo ">>> Ejecutando migraciones..."
    php artisan migrate --force || echo "!!! Error en migraciones"

    echo ">>> Optimizando Laravel..."
    php artisan optimize:clear
    php artisan storage:link || true
    
    echo ">>> SETUP finalizado."
fi

exec "$@"
