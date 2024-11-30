# Imagen base con PHP 8.2 y soporte para FPM
FROM php:8.2-fpm

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias del sistema necesarias para Laravel y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libjpeg-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    ghostscript \
    postgresql-client \
    nginx \
    supervisor \
    && docker-php-ext-configure gd --with-jpeg --with-png \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip gd opcache \
    && apt-get clean

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código de Laravel al contenedor
COPY . .

# Configurar permisos correctos para los directorios de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Instalar las dependencias de Laravel usando Composer
RUN composer install --no-dev --optimize-autoloader

# Correr comandos de optimización de Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Exponer el puerto 80 para NGINX
EXPOSE 80

# Copiar archivo de configuración de NGINX
COPY ./nginx.conf /etc/nginx/sites-available/default

# Copiar archivo de configuración de Supervisor
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Comando de inicio: NGINX y PHP-FPM gestionados por Supervisor
CMD ["/usr/bin/supervisord"]
