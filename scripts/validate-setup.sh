#!/bin/bash

# 📋 GanaderaSoft Quick Setup Validator
# Valida que todo esté configurado correctamente
# Versión: 1.0

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Variables
PROJECT_DIR="/var/www/ganaderasoft_web"

echo -e "${BLUE}"
echo "🔍 GanaderaSoft Setup Validator"
echo "==============================="
echo -e "${NC}"

# Array para almacenar resultados
declare -a results=()

# Función para agregar resultado
add_result() {
    local status=$1
    local message=$2
    results+=("$status|$message")
}

# Verificar directorio del proyecto
echo "📁 Verificando directorio del proyecto..."
if [ -d "$PROJECT_DIR" ]; then
    add_result "✅" "Directorio del proyecto existe"
else
    add_result "❌" "Directorio del proyecto NO existe: $PROJECT_DIR"
fi

# Verificar Git
echo "📝 Verificando repositorio Git..."
cd "$PROJECT_DIR" 2>/dev/null
if [ -d ".git" ]; then
    add_result "✅" "Repositorio Git inicializado"
    
    # Verificar remote
    if git remote -v &>/dev/null; then
        add_result "✅" "Git remote configurado"
    else
        add_result "⚠️" "Git remote no configurado"
    fi
else
    add_result "❌" "Repositorio Git NO encontrado"
fi

# Verificar archivo .env
echo "⚙️ Verificando configuración..."
if [ -f "$PROJECT_DIR/.env" ]; then
    add_result "✅" ".env existe"
    
    if grep -q "APP_KEY=base64:" "$PROJECT_DIR/.env"; then
        add_result "✅" "APP_KEY configurada"
    else
        add_result "⚠️" "APP_KEY no configurada"
    fi
else
    add_result "❌" ".env NO existe"
fi

# Verificar composer
echo "📦 Verificando Composer..."
if command -v composer &> /dev/null; then
    add_result "✅" "Composer instalado"
    
    if [ -d "$PROJECT_DIR/vendor" ]; then
        add_result "✅" "Dependencias de Composer instaladas"
    else
        add_result "⚠️" "Dependencias de Composer no instaladas"
    fi
else
    add_result "❌" "Composer NO instalado"
fi

# Verificar Node.js/NPM
echo "🎨 Verificando Node.js..."
if command -v npm &> /dev/null; then
    add_result "✅" "NPM instalado"
    
    if [ -d "$PROJECT_DIR/node_modules" ]; then
        add_result "✅" "Dependencias de NPM instaladas"
    else
        add_result "⚠️" "Dependencias de NPM no instaladas"
    fi
else
    add_result "❌" "NPM NO instalado"
fi

# Verificar permisos
echo "🔐 Verificando permisos..."
if [ -w "$PROJECT_DIR/storage" ]; then
    add_result "✅" "Permisos de storage OK"
else
    add_result "❌" "Permisos de storage incorrectos"
fi

if [ -w "$PROJECT_DIR/bootstrap/cache" ]; then
    add_result "✅" "Permisos de bootstrap/cache OK"
else
    add_result "❌" "Permisos de bootstrap/cache incorrectos"
fi

# Verificar servicios
echo "🔧 Verificando servicios..."
if systemctl is-active --quiet apache2; then
    add_result "✅" "Apache2 ejecutándose"
elif systemctl is-active --quiet nginx; then
    add_result "✅" "Nginx ejecutándose"
else
    add_result "⚠️" "Servidor web no detectado"
fi

if systemctl is-active --quiet mysql; then
    add_result "✅" "MySQL ejecutándose"
elif systemctl is-active --quiet mariadb; then
    add_result "✅" "MariaDB ejecutándose"
else
    add_result "⚠️" "Base de datos no detectada"
fi

# Verificar Laravel
echo "⚡ Verificando Laravel..."
cd "$PROJECT_DIR"
if sudo -u www-data php artisan about &>/dev/null; then
    add_result "✅" "Laravel funciona correctamente"
else
    add_result "❌" "Laravel tiene errores"
fi

# Verificar scripts de automation
echo "🚀 Verificando scripts de automatización..."
if [ -f "$PROJECT_DIR/scripts/ganaderasoft-update.sh" ]; then
    add_result "✅" "Script de actualización existe"
    
    if [ -x "$PROJECT_DIR/scripts/ganaderasoft-update.sh" ]; then
        add_result "✅" "Script de actualización es ejecutable"
    else
        add_result "⚠️" "Script de actualización no es ejecutable"
    fi
else
    add_result "❌" "Script de actualización NO existe"
fi

if command -v gs &> /dev/null; then
    add_result "✅" "Comando 'gs' disponible globalmente"
else
    add_result "❌" "Comando 'gs' NO disponible"
fi

# Mostrar resultados
echo ""
echo -e "${BLUE}📊 RESUMEN DE VALIDACIÓN${NC}"
echo "=========================="
echo ""

success_count=0
warning_count=0
error_count=0

for result in "${results[@]}"; do
    status="${result%%|*}"
    message="${result#*|}"
    
    case "$status" in
        "✅")
            echo -e "${GREEN}$status $message${NC}"
            ((success_count++))
            ;;
        "⚠️")
            echo -e "${YELLOW}$status $message${NC}"
            ((warning_count++))
            ;;
        "❌")
            echo -e "${RED}$status $message${NC}"
            ((error_count++))
            ;;
    esac
done

echo ""
echo "📈 ESTADÍSTICAS:"
echo "=================="
echo -e "${GREEN}✅ Éxitos: $success_count${NC}"
echo -e "${YELLOW}⚠️  Advertencias: $warning_count${NC}" 
echo -e "${RED}❌ Errores: $error_count${NC}"

echo ""

# Recomendaciones
if [ $error_count -gt 0 ]; then
    echo -e "${RED}🚨 ACCIÓN REQUERIDA:${NC}"
    echo "Hay errores críticos que deben resolverse antes de usar el sistema."
    echo ""
    echo "💡 Comandos sugeridos para resolver:"
    echo "sudo /var/www/ganaderasoft_web/scripts/setup-gs-command.sh"
    echo "sudo gs update"
elif [ $warning_count -gt 0 ]; then
    echo -e "${YELLOW}⚠️  OPTIMIZACIÓN SUGERIDA:${NC}"
    echo "Hay algunas configuraciones que podrían mejorarse."
    echo ""
    echo "💡 Comando sugerido:"
    echo "sudo gs update"
else
    echo -e "${GREEN}🎉 ¡TODO PERFECTO!${NC}"
    echo "El sistema está completamente configurado y listo para usar."
    echo ""
    echo "💡 Para actualizar en cualquier momento:"
    echo "sudo gs update"
fi