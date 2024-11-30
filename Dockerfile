FROM php:8.2-fpm

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    ghostscript \
    postgresql-client \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip gd opcache

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia el código del proyecto
WORKDIR /var/www/html
COPY . .

# Configura permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Instala dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Configura Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Exponer puerto para PHP-FPM
EXPOSE 9000

# Configura PHP-FPM como comando principal
CMD ["php-fpm"]
