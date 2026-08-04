FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    socat \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js 18 for Vite asset compilation
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache DocumentRoot to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy package files and install Node dependencies
COPY package.json package-lock.json ./
RUN npm install

# Copy the rest of the application
COPY . .

# Re-run composer scripts after full copy
RUN composer dump-autoload --optimize

# Build frontend assets with Vite
RUN npm run build

# Setup environment from .env.example
RUN cp .env.example .env \
    && php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Create entrypoint script
COPY <<'ENTRYPOINT' /usr/local/bin/entrypoint.sh
#!/bin/bash
set -e

# Override .env with runtime env vars if present
[ -n "$APP_URL" ]      && sed -i "s|APP_URL=.*|APP_URL=$APP_URL|" /var/www/html/.env
[ -n "$API_BASE_URL" ] && grep -q "API_BASE_URL" /var/www/html/.env \
    && sed -i "s|API_BASE_URL=.*|API_BASE_URL=$API_BASE_URL|" /var/www/html/.env \
    || echo "API_BASE_URL=$API_BASE_URL" >> /var/www/html/.env

npm run dev -- --host 0.0.0.0 > /var/log/vite.log 2>&1 &
(
  while true; do
    if [ -f /var/www/html/public/hot ]; then
      sed -i 's|http://[0-9\.]*:5173|http://localhost:5173|g' /var/www/html/public/hot
    fi
    sleep 2
  done
) &
php artisan config:clear
php artisan cache:clear
apache2-foreground

ENTRYPOINT

RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
EXPOSE 5173

CMD ["/usr/local/bin/entrypoint.sh"]
