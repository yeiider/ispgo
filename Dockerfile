# syntax=docker/dockerfile:1.2

FROM php:8.2-fpm

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias del sistema necesarias
RUN apt-get update && apt-get install -y \
    git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
    unzip \
    ghostscript \
    libpq-dev \
    postgresql-server-dev-all \
    zlib1g-dev \
    nginx \
    && apt-get clean

# Configurar y instalar la extensión GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install gd

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip sockets

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar el código de la aplicación al contenedor
COPY . .

# Configurar permisos correctos para los directorios de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Composer para usar el auth.json
RUN --mount=type=secret,id=auth,target=/var/www/html/auth.json \
    composer install --no-dev --optimize-autoloader

# Eliminar auth.json después de la instalación (opcional)
RUN rm /var/www/html/auth.json

# Correr comandos de optimización de Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Exponer el puerto 80 para NGINX
EXPOSE 80

# Copiar archivo de configuración de NGINX
COPY nginx.conf /etc/nginx/sites-available/default

# Copiar el script de inicio
COPY start.sh /var/www/html/start.sh

# Dar permisos de ejecución al script de inicio
RUN chmod +x /var/www/html/start.sh

# Comando de inicio
CMD ["/var/www/html/start.sh"]
