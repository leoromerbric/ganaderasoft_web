#!/bin/bash
set -e

echo "========================================================="
if [ "$APP_ENV" = "production" ]; then
    echo "🚀 Iniciando Frontend en modo: PRODUCCIÓN"
else
    echo "🛠️  Iniciando Frontend en modo: DESARROLLO ($APP_ENV)"
fi
echo "========================================================="
# Asegurar que las dependencias estén instaladas
if [ ! -f "vendor/autoload.php" ]; then
    echo "Instalando dependencias de Composer..."
    composer install --no-interaction
fi

if [ "$APP_ENV" != "production" ]; then
    # node_modules podría existir como un directorio de volumen vacío
    if [ ! -d "node_modules" ] || [ -z "$(ls -A node_modules)" ]; then
        echo "Instalando dependencias de NPM..."
        npm install
    fi
fi

# Arreglar permisos para el almacenamiento de Laravel
# Asegurar que las carpetas de framework existan (por si el .dockerignore las omitió)
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

if [ "$APP_ENV" = "production" ]; then
    # Optimizar Laravel para producción
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    # Limpiar cachés en desarrollo
    php artisan config:clear || true
    php artisan cache:clear || true
    
    # Iniciar Vite en segundo plano para HMR (Hot Module Replacement) en desarrollo
    echo "Iniciando Vite para desarrollo..."
    npm run dev -- --host 0.0.0.0 > /dev/stdout 2>&1 &
    
    # Parche para el puerto del archivo 'hot' en Docker
    (
      while true; do
        if [ -f /var/www/html/public/hot ]; then
          sed -i 's|http://[0-9\.]*:5173|http://localhost:5173|g' /var/www/html/public/hot || true
        fi
        sleep 2
      done
    ) &
fi

# Iniciar Supervisor (el cual inicia Nginx y PHP-FPM)
echo "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
