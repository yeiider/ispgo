#!/bin/bash
set -e

echo "=== Limpiando cache y optimizando... ==="
php artisan optimize:clear
php artisan storage:link 2>/dev/null || true

echo "=== Ejecutando migraciones... ==="
php artisan migrate --force

echo "=== Validando schema GraphQL... ==="
php artisan lighthouse:validate-schema || echo "⚠️ Advertencia: Error en schema"

echo "=== Verificando licencia Laravel Nova... ==="
php artisan nova:check-license || echo "⚠️ Advertencia: Error en licencia"

echo "=== Iniciando php-fpm... ==="
php-fpm -y /assets/php-fpm.conf -D

echo "=== Iniciando nginx... ==="
exec nginx -c /app/nginx.conf
