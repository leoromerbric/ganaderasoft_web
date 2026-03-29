#!/bin/bash

# 🔧 GanaderaSoft Git Fix
# Solución rápida para el problema "dubious ownership"
# Ejecutar este comando en el servidor ANTES de usar gs update

echo "🔧 Solucionando problema de Git ownership..."

# Agregar directorio como seguro para Git
git config --global --add safe.directory /var/www/ganaderasoft_web

echo "✅ Directorio agregado como seguro para Git"
echo ""
echo "🚀 Ahora puedes ejecutar:"
echo "sudo gs update"