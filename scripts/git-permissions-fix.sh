#!/bin/bash

# 🔧 GanaderaSoft Git Permissions Fix
# Soluciona permisos de Git después del deployment exitoso

set -e

PROJECT_DIR="/var/www/ganaderasoft_web"
WEB_USER="www-data"

echo "🔧 Corrigiendo permisos de Git..."
echo "================================="

cd "$PROJECT_DIR"

# Corregir permisos específicos del directorio .git
echo "📁 Corrigiendo permisos de directorio .git..."
chown -R "$WEB_USER:$WEB_USER" .git/
chmod -R 755 .git/

# Limpiar locks de Git si existen
echo "🧹 Limpiando locks de Git..."
rm -f .git/index.lock 2>/dev/null || true
rm -f .git/refs/heads/*.lock 2>/dev/null || true

# Corregir permisos de archivos modificados por el sistema
echo "🔐 Corrigiendo permisos de archivos del sistema..."
chown "$WEB_USER:$WEB_USER" artisan
chown -R "$WEB_USER:$WEB_USER" bootstrap/cache/
chown -R "$WEB_USER:$WEB_USER" storage/

# Limpiar archivos modificados que no deben estar en Git
echo "🗑️ Limpiando archivos temporales..."
git restore artisan 2>/dev/null || true
git restore bootstrap/cache/.gitignore 2>/dev/null || true
git restore storage/ 2>/dev/null || true

echo ""
echo "✅ Permisos de Git corregidos"
echo ""
echo "🎯 Ahora puedes ejecutar:"
echo "git add scripts/emergency-fix.sh"
echo "git commit -m 'Add emergency fix script'"
echo "git status"