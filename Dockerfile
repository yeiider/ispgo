FROM php:8.3-fpm

# Argumentos
ARG WWWGROUP=1000
ARG NODE_VERSION=20

# Variables de entorno
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

# Establecer zona horaria
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    gnupg \
    gosu \
    curl \
    ca-certificates \
    zip \
    unzip \
    git \
    supervisor \
    libcap2-bin \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    nginx \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    fileinfo

# Instalar Redis
RUN pecl install redis && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de la aplicación
COPY . .

# Copiar scripts de ejecución
COPY run-start.sh /usr/local/bin/run-start.sh
COPY run-worker.sh /usr/local/bin/run-worker.sh
COPY run-cron.sh /usr/local/bin/run-cron.sh

# Dar permisos de ejecución a los scripts
RUN chmod +x /usr/local/bin/run-start.sh \
    && chmod +x /usr/local/bin/run-worker.sh \
    && chmod +x /usr/local/bin/run-cron.sh

# Instalar dependencias de Composer (sin dev en producción)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Instalar dependencias de Node.js
RUN npm ci

# Crear directorios necesarios y establecer permisos
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Configurar Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Exponer puerto
EXPOSE 80

# Comando por defecto (será sobrescrito por docker-compose)
CMD ["php-fpm"]
