# Multi-stage Dockerfile for Laravel optimized for Render

# Builder: composer + node build
FROM composer:2 AS composer

FROM node:20-bullseye AS node-build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --silent

# Application build stage
FROM php:8.2-fpm-bullseye AS base

# system deps
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev libssl-dev \
    nginx supervisor ca-certificates curl procps libmariadb-dev-compat libmariadb-dev \
  && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Create app dir
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Build frontend assets only if applicable
RUN if [ -f package.json ]; then \
      npm ci --silent && npm run build || true; \
    else \
      echo "No frontend build required"; \
    fi

# Copy CA placeholder (optional) and entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Nginx config and supervisor
COPY deploy/nginx.conf /etc/nginx/sites-available/default
COPY deploy/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n"]
