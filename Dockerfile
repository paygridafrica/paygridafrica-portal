# ---- Stage 1: build frontend assets (Tailwind, Alpine, Chart.js) ----
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# ---- Stage 2: PHP application ----
FROM php:8.2-cli

# System dependencies + build tools needed to compile the mongodb PECL extension
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    build-essential autoconf pkg-config libssl-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo mbstring exif pcntl bcmath gd zip

# Install and enable the mongodb extension, then verify it actually loaded
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && php -r 'if (!extension_loaded("mongodb")) { fwrite(STDERR, "mongodb extension failed to load\n"); exit(1); } echo "mongodb extension loaded OK\n";'

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies first (better Docker layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy the rest of the app
COPY . .

# Bring in the built frontend assets from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Finish Composer setup now that all files are present
RUN composer dump-autoload --optimize

# Storage folders Laravel needs to write to
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create the public/storage symlink (for Media Library file access)
RUN php artisan storage:link || true

EXPOSE 10000

CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
