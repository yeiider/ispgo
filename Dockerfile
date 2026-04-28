FROM php:8.3-fpm

# Instalar dependencias del sistema esenciales
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libzip-dev libonig-dev libicu-dev \
    libjpeg62-turbo-dev libfreetype6-dev nginx supervisor curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip bcmath sockets intl pcntl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

WORKDIR /var/www/html

# Copiamos el código (esto se usará si no hay volúmenes montados)
COPY . .

# Permisos iniciales
RUN chown -R www-data:www-data /var/www/html

COPY ./docker/nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
