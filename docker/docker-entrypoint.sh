#!/bin/bash
set -e

if [ "$RUN_SETUP" = "true" ]; then
    echo ">>> Iniciando proceso de SETUP..."
    
    if [ ! -z "$COMPOSER_AUTH" ]; then
        echo ">>> Configurando COMPOSER_AUTH..."
        export COMPOSER_AUTH="$COMPOSER_AUTH"
    fi

    echo ">>> Instalando dependencias de PHP (Composer)..."
    if composer install --no-interaction --no-scripts --prefer-dist --no-dev --optimize-autoloader; then
        echo ">>> Composer instalado correctamente."
        
        echo ">>> Instalando dependencias de JS (NPM)..."
        npm install --no-audit --no-fund
        
        echo ">>> Compilando assets (Vite)..."
        npm run build

        echo ">>> Ejecutando migraciones..."
        php artisan migrate --force
        
        echo ">>> Optimizando Laravel..."
        php artisan optimize:clear
        php artisan storage:link || true
        
        echo ">>> SETUP finalizado con éxito."
    else
        echo "!!! ERROR CRÍTICO: Composer falló. Revisa tus credenciales en COMPOSER_AUTH."
        exit 1
    fi
else
    echo ">>> Modo ejecutor (Worker/Cron). Esperando a que las dependencias estén listas..."
    timeout=300
    while [ ! -f "/var/www/html/vendor/autoload.php" ]; do
        if [ "$timeout" -le 0 ]; then
            echo "!!! ERROR: Tiempo de espera agotado para las dependencias."
            exit 1
        fi
        sleep 5
        timeout=$((timeout - 5))
    done
    echo ">>> Dependencias detectadas. Iniciando comando..."
fi

exec "$@"
