# Despliegue de ISPGO en Portainer

Esta guía te ayudará a desplegar la aplicación ISPGO en Portainer con 3 contenedores separados: App, Worker y Cron.

## Arquitectura

La aplicación se despliega con la siguiente arquitectura:

- **ispgo-app**: Contenedor principal con Nginx + PHP-FPM que ejecuta la aplicación Laravel
- **ispgo-worker**: Contenedor que procesa las colas de trabajos de Laravel
- **ispgo-cron**: Contenedor que ejecuta las tareas programadas (scheduler)
- **ispgo-redis**: Contenedor Redis para cache, sesiones y colas

## Requisitos Previos

1. Tener Portainer instalado y funcionando
2. Tener acceso a una base de datos MySQL externa o configurar una
3. Tener tu licencia de Laravel Nova
4. Tener las credenciales de tu base de datos

## Pasos para el Despliegue

### 1. Preparar el Archivo .env

Copia el archivo `.env.portainer.example` a `.env` y configura las siguientes variables:

```bash
cp .env.portainer.example .env
```

**Variables críticas a configurar:**

```env
# Generar con: php artisan key:generate
APP_KEY=base64:tu-app-key-generada

# URL de tu aplicación
APP_URL=https://tu-dominio.com

# Base de datos (debe ser externa o configurar MySQL en Portainer)
DB_HOST=tu-servidor-mysql.com
DB_PORT=3306
DB_DATABASE=ispgo
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password_seguro

# Laravel Nova - IMPORTANTE
NOVA_LICENSE_KEY=tu-licencia-de-nova

# Email (configura según tu proveedor)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_FROM_ADDRESS=noreply@ispgo.com

# Google Maps (si usas NAP Manager)
MIX_GOOGLE_MAPS_API_KEY=tu-api-key-de-google
```

### 2. Construir la Imagen Docker

Antes de desplegar en Portainer, construye la imagen localmente:

```bash
docker build -t ispgo:latest .
```

O si quieres taggear para un registry:

```bash
docker build -t tu-registry.com/ispgo:latest .
docker push tu-registry.com/ispgo:latest
```

### 3. Desplegar en Portainer

#### Opción A: Usando Stacks (Recomendado)

1. Accede a tu Portainer
2. Ve a **Stacks** → **Add Stack**
3. Dale un nombre: `ispgo`
4. Selecciona **Upload** y sube el archivo `docker-compose.portainer.yml`
5. En la sección **Environment variables**, agrega todas las variables del archivo `.env`
6. Click en **Deploy the stack**

#### Opción B: Usando Git Repository

1. Sube tu código a un repositorio Git privado
2. En Portainer, ve a **Stacks** → **Add Stack**
3. Selecciona **Repository**
4. Configura:
   - Repository URL: tu repositorio
   - Reference: rama (ej: main, master)
   - Compose path: `docker-compose.portainer.yml`
   - Authentication: si es privado
5. Agrega las variables de entorno
6. Click en **Deploy the stack**

### 4. Configurar Variables de Entorno en Portainer

En la sección de Environment Variables, agrega todas las variables del `.env`:

```
APP_NAME=ISPGO
APP_ENV=production
APP_KEY=base64:tu-key
DB_HOST=tu-host
DB_DATABASE=ispgo
DB_USERNAME=usuario
DB_PASSWORD=password
REDIS_HOST=redis
NOVA_LICENSE_KEY=tu-licencia
...
```

### 5. Verificar el Despliegue

Una vez desplegado, verifica que los 3 contenedores estén corriendo:

```bash
docker ps | grep ispgo
```

Deberías ver:
- `ispgo-app` (puerto 80 expuesto)
- `ispgo-worker`
- `ispgo-cron`
- `ispgo-redis`

### 6. Acceder a la Aplicación

Accede a tu aplicación en:
- `http://tu-servidor:80` (o el puerto que configuraste)
- `https://tu-dominio.com` (si configuraste un dominio)

## Configuración de Base de Datos Externa

Si usas una base de datos MySQL externa (recomendado para producción):

1. Asegúrate de que el servidor MySQL esté accesible desde Portainer
2. Configura las reglas de firewall para permitir conexiones desde los contenedores
3. Usa las variables de entorno correctas:

```env
DB_HOST=mysql.tu-servidor.com
DB_PORT=3306
DB_DATABASE=ispgo
DB_USERNAME=ispgo_user
DB_PASSWORD=password-seguro
```

## Volúmenes Persistentes

Los siguientes directorios son persistentes:

- `./storage` - Logs, archivos subidos, cache de Laravel
- `./bootstrap/cache` - Cache de configuración de Laravel
- `redis-data` - Datos de Redis

## Logs y Debugging

Para ver los logs de cada contenedor:

```bash
# Logs de la aplicación
docker logs ispgo-app

# Logs del worker
docker logs ispgo-worker

# Logs del cron
docker logs ispgo-cron

# Logs de Redis
docker logs ispgo-redis
```

## Comandos Útiles

### Ejecutar Artisan Commands

```bash
docker exec -it ispgo-app php artisan [comando]
```

Ejemplos:
```bash
# Limpiar cache
docker exec -it ispgo-app php artisan cache:clear

# Ver rutas
docker exec -it ispgo-app php artisan route:list

# Ejecutar migraciones
docker exec -it ispgo-app php artisan migrate

# Crear usuario de Nova
docker exec -it ispgo-app php artisan nova:user
```

### Reiniciar Servicios

```bash
docker restart ispgo-app
docker restart ispgo-worker
docker restart ispgo-cron
```

## Actualización de la Aplicación

Para actualizar la aplicación:

1. Construye una nueva imagen con los cambios
2. En Portainer, ve al Stack
3. Click en **Update** o **Pull and redeploy**
4. Los contenedores se recrearán con la nueva versión

## Troubleshooting

### La aplicación no carga

1. Verifica que `APP_KEY` esté configurado
2. Revisa los logs: `docker logs ispgo-app`
3. Verifica la conexión a la base de datos
4. Asegúrate de que las migraciones se ejecutaron

### Error con Laravel Nova

1. Verifica que `NOVA_LICENSE_KEY` esté configurado correctamente
2. Ejecuta: `docker exec -it ispgo-app php artisan nova:check-license`

### Las colas no se procesan

1. Verifica que el worker esté corriendo: `docker ps | grep worker`
2. Revisa los logs: `docker logs ispgo-worker`
3. Verifica la conexión a Redis

### El cron no ejecuta tareas

1. Verifica que el cron esté corriendo: `docker ps | grep cron`
2. Revisa los logs: `docker logs ispgo-cron`
3. Lista las tareas: `docker exec -it ispgo-cron php artisan schedule:list`

## Seguridad

Para producción, asegúrate de:

1. ✅ Usar contraseñas seguras
2. ✅ Configurar `APP_ENV=production`
3. ✅ Configurar `APP_DEBUG=false`
4. ✅ Usar HTTPS (configura un reverse proxy como Traefik o Nginx)
5. ✅ Restringir acceso a Redis
6. ✅ Configurar firewall para la base de datos
7. ✅ Usar secrets de Docker para información sensible
8. ✅ Mantener las imágenes actualizadas

## Backup

Realiza backups regulares de:

1. Base de datos MySQL
2. Volumen `./storage` (archivos subidos)
3. Variables de entorno (`.env`)

```bash
# Backup de la base de datos
docker exec ispgo-app php artisan backup:run
```

## Soporte

Para más información sobre Laravel y Nova:
- Laravel: https://laravel.com/docs
- Laravel Nova: https://nova.laravel.com/docs
- Portainer: https://docs.portainer.io
