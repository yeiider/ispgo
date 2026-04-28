FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    unzip \
    git \
    curl \
    zip \
    gnupg

# Limpiar caché
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd bcmath intl
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

WORKDIR /var/www/html

# Copiar archivos de configuración primero para aprovechar la caché de Docker
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./
COPY auth.json ./

# Copiar el resto del proyecto
COPY . .

# Configurar Nginx y Supervisor
COPY ./docker/nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Preparar scripts
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN cp run-start.sh /usr/local/bin/run-start.sh && \
    cp run-worker.sh /usr/local/bin/run-worker.sh && \
    cp run-cron.sh /usr/local/bin/run-cron.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh /usr/local/bin/run-start.sh /usr/local/bin/run-worker.sh /usr/local/bin/run-cron.sh

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

EXPOSE 80

# El comando por defecto será iniciar supervisord (que arranca nginx y php-fpm)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
