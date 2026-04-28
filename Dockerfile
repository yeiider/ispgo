FROM php:8.3-fpm-alpine

# Permitir que Composer se ejecute como superusuario
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias esenciales
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    bash \
    nodejs \
    npm \
    git \
    mysql-client

# Extensiones PHP (Incluyendo sockets para Mikrotik)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    sockets

# Redis
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos
COPY . .

# Configuración Nginx (para el contenedor Web)
COPY docker/railway/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/railway/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Instalación de dependencias
# Nota: Usamos --ignore-platform-reqs solo si el lock fue generado en un entorno distinto,
# pero al haber instalado 'sockets' arriba, ya no debería ser necesario.
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Instalación de dependencias de JS y build de Vite
RUN npm install && npm run build

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
