#!/bin/bash

# Ejecutar las demás tareas
echo "Ejecutando el build de la aplicación..."
npm run build

echo "Limpiando la caché y optimizando..."
php artisan optimize:clear && php artisan storage:link

echo "Ejecutando migraciones..."
php artisan migrate --force



echo "Verificando la licencia de Laravel Nova..."
php artisan nova:check-license

php artisan route:clear


echo "¡Script completado con éxito!"
