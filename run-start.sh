#!/bin/bash

# Ejecutar las demás tareas
echo "Limpiando la caché y optimizando..."
php artisan optimize:clear
php artisan storage:link

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Validacion del schema graphql"
php artisan lighthouse:validate-schema || echo "Advertencia: Error en schema"

echo "Verificando la licencia de Laravel Nova..."
php artisan nova:check-license || echo "Advertencia: Error en licencia"

php artisan route:clear

echo "¡Script completado con éxito!"
