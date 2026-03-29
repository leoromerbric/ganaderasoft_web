#!/bin/bash

# 🔧 Fix GanaderaSoft Command Setup
# Repara el comando global "gs update" si está dañado o no configurado
# Uso: sudo bash fix-gs-command.sh

set -e

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

PROJECT_DIR="/var/www/ganaderasoft_web"
SCRIPT_SOURCE="$PROJECT_DIR/scripts/ganaderasoft-update.sh"
GLOBAL_COMMAND="/usr/local/bin/gs"

success() {
    echo -e "${GREEN}✅ $1${NC}"
}

warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

error() {
    echo -e "${RED}❌ $1${NC}"
    exit 1
}

info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Verificar permisos
if [ "$EUID" -ne 0 ]; then
    error "Este script debe ejecutarse como root o con sudo"
fi

echo -e "${BLUE}"
echo "🔧 Diagnóstico y reparación del comando 'gs'"
echo "============================================="
echo -e "${NC}"

# 1. Verificar directorio del proyecto
info "1. Verificando directorio del proyecto..."
if [ ! -d "$PROJECT_DIR" ]; then
    error "Directorio del proyecto no encontrado: $PROJECT_DIR"
fi
success "Directorio del proyecto encontrado"

# 2. Verificar script principal
info "2. Verificando script principal..."
if [ ! -f "$SCRIPT_SOURCE" ]; then
    error "Script principal no encontrado: $SCRIPT_SOURCE"
fi
success "Script principal encontrado"

# 3. Hacer script principal ejecutable
info "3. Configurando permisos del script principal..."
chmod +x "$SCRIPT_SOURCE"
chown root:root "$SCRIPT_SOURCE"
success "Permisos del script configurados"

# 4. Verificar comando global existente
info "4. Verificando comando global existente..."
if [ -f "$GLOBAL_COMMAND" ]; then
    warning "Comando global ya existe, será reemplazado"
    rm -f "$GLOBAL_COMMAND"
fi

# 5. Crear comando global
info "5. Creando comando global 'gs'..."
cat > "$GLOBAL_COMMAND" << 'EOF'
#!/bin/bash

# GanaderaSoft Global Command
# Permite ejecutar: gs update, gs status, gs logs

SCRIPT_DIR="/var/www/ganaderasoft_web/scripts"
PROJECT_DIR="/var/www/ganaderasoft_web"
LOG_FILE="/var/log/ganaderasoft-update.log"

case "$1" in
    update)
        echo "🚀 Ejecutando GanaderaSoft Update..."
        if [ "$EUID" -ne 0 ]; then
            echo "❌ Error: El comando 'gs update' debe ejecutarse con sudo"
            echo "💡 Uso correcto: sudo gs update"
            exit 1
        fi
        "$SCRIPT_DIR/ganaderasoft-update.sh"
        ;;
    status)
        echo "📊 Estado de GanaderaSoft:"
        echo "=========================="
        cd "$PROJECT_DIR"
        
        # Configurar directorio como seguro para Git
        git config --global --add safe.directory "$PROJECT_DIR" 2>/dev/null || true
        
        # Git status
        echo "📝 Git Status:"
        git status --short 2>/dev/null || echo "Error accediendo a Git"
        echo ""
        
        # Laravel status
        echo "⚡ Laravel Status:"
        if sudo -u www-data php artisan about 2>/dev/null | head -10; then
            echo "✅ Laravel funcionando"
        else
            echo "❌ Laravel con problemas"
        fi
        echo ""
        
        # Disk space
        echo "💽 Espacio en disco:"
        df -h "$PROJECT_DIR" | tail -1
        ;;
    logs)
        echo "📋 Últimos logs de GanaderaSoft:"
        echo "================================"
        if [ -f "$LOG_FILE" ]; then
            tail -50 "$LOG_FILE"
        else
            echo "Log file no encontrado: $LOG_FILE"
        fi
        ;;
    laravel-logs)
        echo "📋 Logs de Laravel:"
        echo "=================="
        if [ -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
            tail -50 "$PROJECT_DIR/storage/logs/laravel.log"
        else
            echo "Log de Laravel no encontrado"
        fi
        ;;
    test)
        echo "🧪 Test del comando 'gs':"
        echo "========================="
        echo "✅ Comando 'gs' funcionando correctamente"
        echo "📁 Directorio del proyecto: $PROJECT_DIR"
        echo "📜 Script principal: $SCRIPT_DIR/ganaderasoft-update.sh"
        echo "📝 Log file: $LOG_FILE"
        echo ""
        echo "Para ver todos los comandos disponibles: gs help"
        ;;
    help|--help|-h|"")
        echo "🚀 GanaderaSoft Command Line Tool"
        echo "=================================="
        echo ""
        echo "Comandos disponibles:"
        echo "  gs update       - Actualizar aplicación desde Git (requiere sudo)"
        echo "  gs status       - Ver estado del sistema"
        echo "  gs logs         - Ver logs de actualización" 
        echo "  gs laravel-logs - Ver logs de Laravel"
        echo "  gs test         - Probar que el comando funciona"
        echo "  gs help         - Mostrar esta ayuda"
        echo ""
        echo "Ejemplos:"
        echo "  sudo gs update     # Actualizar sistema completo"
        echo "  gs status          # Ver estado actual"
        echo "  gs logs            # Ver últimas actualizaciones"
        echo "  gs test            # Probar comando"
        ;;
    *)
        echo "❌ Comando no reconocido: $1"
        echo "💡 Usa 'gs help' para ver comandos disponibles"
        exit 1
        ;;
esac
EOF

# 6. Hacer el comando ejecutable
info "6. Configurando permisos del comando global..."
chmod +x "$GLOBAL_COMMAND"
chown root:root "$GLOBAL_COMMAND"
success "Comando global configurado"

# 7. Crear directorio de logs
info "7. Configurando logs..."
mkdir -p /var/log
touch /var/log/ganaderasoft-update.log
chmod 644 /var/log/ganaderasoft-update.log
chown www-data:www-data /var/log/ganaderasoft-update.log
success "Logs configurados"

# 8. Verificar instalación
info "8. Verificando instalación..."
if [ -x "$GLOBAL_COMMAND" ]; then
    success "Comando 'gs' instalado correctamente en $GLOBAL_COMMAND"
else
    error "Error en la instalación del comando"
fi

# 9. Test básico
info "9. Ejecutando test básico..."
if "$GLOBAL_COMMAND" test &>/dev/null; then
    success "Test básico pasado"
else
    warning "Test básico falló, pero comando está instalado"
fi

echo ""
echo -e "${GREEN}🎉 Reparación completada!${NC}"
echo ""
echo "📋 Comandos disponibles:"
echo "  sudo gs update        - Actualizar aplicación"
echo "  gs status             - Ver estado del sistema"  
echo "  gs logs               - Ver logs de actualización"
echo "  gs laravel-logs       - Ver logs de Laravel"
echo "  gs test               - Probar comando"
echo "  gs help               - Ver ayuda"
echo ""
echo -e "${BLUE}Prueba ahora:${NC}"
echo "  gs test"
echo "  sudo gs update"
echo ""
echo -e "${YELLOW}Nota:${NC} Si aún no funciona, reinicia la sesión (logout/login)"