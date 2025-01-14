#!/bin/bash

PRIVATE_KEY_PATH="storage/oauth-private.key"
PUBLIC_KEY_PATH="storage/oauth-public.key"

if [[ ! -f "$PRIVATE_KEY_PATH" || ! -f "$PUBLIC_KEY_PATH" ]]; then
  echo "Las claves RSA no existen. Generando nuevas claves..."
  php artisan passport:keys --force
  if [[ $? -eq 0 ]]; then
    echo "Claves RSA generadas exitosamente."
  else
    echo "Error al generar las claves RSA."
    exit 1
  fi
else
  echo "Las claves RSA ya existen. No es necesario generarlas."
fi

# Ejecutar las demás tareas
echo "Ejecutando el build de la aplicación..."
npm run build

echo "Limpiando la caché y optimizando..."
php artisan optimize:clear && php artisan storage:link

echo "Ejecutando migraciones..."
php artisan migrate --force


php artisan db:seed --force

echo "Verificando la licencia de Laravel Nova..."
php artisan nova:check-license

echo "¡Script completado con éxito!"
