FROM php:8.3-fpm

# Instalar solo lo esencial para que el build NO falle
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    nginx \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Extensiones mínimas
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip bcmath sockets intl pcntl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# En este modo simple, NO corremos composer install durante el build
# para evitar que el build de Portainer falle por falta de auth.json
COPY . .

# Permisos
RUN chown -R www-data:www-data /var/www/html

COPY ./docker/nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

# Usamos un script de inicio que nos de logs claros
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
