FROM php:8.3-fpm

# Argumentos
ARG WWWGROUP=1000
ARG NODE_VERSION=20
ARG COMPOSER_AUTH

# Variables de entorno
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC
ENV COMPOSER_AUTH=$COMPOSER_AUTH

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
    libicu-dev \
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
    fileinfo \
    intl

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

# Copiar archivos de configuración para dependencias
COPY composer.json composer.lock package.json package-lock.json ./
COPY auth.json* ./

# Copiar componentes locales requeridos por composer.json (path repositories)
COPY nova-components/ ./nova-components/
COPY packages/ ./packages/

# Instalar dependencias de Composer (sin dev en producción)
# Usamos --no-scripts para evitar errores si la app no está configurada aún
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Instalar dependencias de Node.js
RUN npm ci

# Copiar el resto de la aplicación
COPY . .

# Compilar assets de Vite
RUN npm run build

# Configurar Nginx y Supervisor
COPY ./docker/nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Preparar scripts de ejecución
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN cp run-start.sh /usr/local/bin/run-start.sh && \
    cp run-worker.sh /usr/local/bin/run-worker.sh && \
    cp run-cron.sh /usr/local/bin/run-cron.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh /usr/local/bin/run-start.sh /usr/local/bin/run-worker.sh /usr/local/bin/run-cron.sh

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

# Exponer puerto
EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# El comando por defecto inicia supervisord para correr Nginx y PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
