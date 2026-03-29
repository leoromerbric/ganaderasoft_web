#!/bin/bash

# 🚨 GanaderaSoft Emergency Fix
# Solución inmediata para problemas de permisos y Git ownership
# Ejecutar este comando en el servidor AHORA

set -e

PROJECT_DIR="/var/www/ganaderasoft_web"
WEB_USER="www-data"

echo "🚨 Ejecutando corrección de emergencia..."
echo "========================================"

# Configurar Git ownership - múltiples métodos
echo "🔧 Configurando Git ownership..."
git config --global --add safe.directory "$PROJECT_DIR"
git config --system --add safe.directory "$PROJECT_DIR" 2>/dev/null || true
sudo -u "$WEB_USER" git config --global --add safe.directory "$PROJECT_DIR" 2>/dev/null || true

# Ir al directorio del proyecto
cd "$PROJECT_DIR"

# Limpiar vendor problemático
echo "🗑️ Limpiando directorio vendor problemático..."
rm -rf vendor/ 2>/dev/null || true
rm -f composer.lock 2>/dev/null || true

# Corregir permisos
echo "🔐 Corrigiendo permisos de archivos..."
chown -R "$WEB_USER:$WEB_USER" .
chmod -R 755 .
chmod -R 775 storage/ bootstrap/cache/
chmod +x artisan

# Limpiar caches de Composer
echo "🧹 Limpiando cache de Composer..."
sudo -u "$WEB_USER" composer clear-cache 2>/dev/null || true

# Reinstalar dependencias
echo "📦 Reinstalando dependencias de Composer..."
sudo -u "$WEB_USER" composer install --no-dev --optimize-autoloader --no-interaction

echo ""
echo "✅ Corrección de emergencia completada"
echo ""
echo "🚀 Ahora ejecuta:"
echo "sudo gs update"