#!/bin/bash

# 🔧 GanaderaSoft Command Setup
# Configura el comando global "gs update" 
# Versión: 1.0

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

# Verificar permisos
if [ "$EUID" -ne 0 ]; then
    error "Este script debe ejecutarse como root o con sudo"
fi

echo -e "${BLUE}"
echo "🔧 Configurando comando global 'gs update'"
echo "=========================================="
echo -e "${NC}"

# Verificar que el script principal existe
if [ ! -f "$SCRIPT_SOURCE" ]; then
    error "Script principal no encontrado en: $SCRIPT_SOURCE"
fi

# Hacer el script ejecutable
chmod +x "$SCRIPT_SOURCE"
success "Script principal hecho ejecutable"

# Crear el comando global 'gs'
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
        sudo "$SCRIPT_DIR/ganaderasoft-update.sh"
        ;;
    status)
        echo "📊 Estado de GanaderaSoft:"
        echo "=========================="
        cd "$PROJECT_DIR"
        
        # Git status
        echo "📝 Git Status:"
        git status --short
        echo ""
        
        # Laravel status
        echo "⚡ Laravel Status:"
        if sudo -u www-data php artisan about 2>/dev/null | head -20; then
            echo "✅ Laravel funcionando"
        else
            echo "❌ Laravel con problemas"
        fi
        echo ""
        
        # Disk space
        echo "💽 Espacio en disco:"
        df -h "$PROJECT_DIR" | tail -1
        echo ""
        
        # Services
        echo "🔧 Servicios:"
        systemctl is-active apache2 nginx mysql mariadb 2>/dev/null | paste <(echo -e "Apache2\nNginx\nMySQL\nMariaDB") - | column -t
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
    backup-logs)
        echo "📋 Logs de Laravel:"
        echo "=================="
        if [ -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
            tail -50 "$PROJECT_DIR/storage/logs/laravel.log"
        else
            echo "Log de Laravel no encontrado"
        fi
        ;;
    help|--help|-h)
        echo "🚀 GanaderaSoft Command Line Tool"
        echo "=================================="
        echo ""
        echo "Comandos disponibles:"
        echo "  gs update       - Actualizar aplicación desde Git"
        echo "  gs status       - Ver estado del sistema"
        echo "  gs logs         - Ver logs de actualización" 
        echo "  gs backup-logs  - Ver logs de Laravel"
        echo "  gs help         - Mostrar esta ayuda"
        echo ""
        echo "Ejemplos:"
        echo "  sudo gs update     # Actualizar sistema completo"
        echo "  gs status          # Ver estado actual"
        echo "  gs logs            # Ver últimas actualizaciones"
        ;;
    *)
        echo "❌ Comando no reconocido: $1"
        echo "💡 Usa 'gs help' para ver comandos disponibles"
        exit 1
        ;;
esac
EOF

# Hacer el comando ejecutable
chmod +x "$GLOBAL_COMMAND"
success "Comando global 'gs' creado en /usr/local/bin/"

# Verificar que funciona
if command -v gs &> /dev/null; then
    success "Comando 'gs' disponible globalmente"
else
    warning "Comando 'gs' no disponible. Puede necesitar reiniciar la sesión"
fi

# Crear directorio de logs si no existe
mkdir -p /var/log
touch /var/log/ganaderasoft-update.log
chown www-data:www-data /var/log/ganaderasoft-update.log

echo ""
echo -e "${GREEN}🎉 Configuración completada!${NC}"
echo ""
echo "📋 Comandos disponibles:"
echo "  sudo gs update     - Actualizar aplicación"
echo "  gs status          - Ver estado del sistema"  
echo "  gs logs            - Ver logs de actualización"
echo "  gs backup-logs     - Ver logs de Laravel"
echo "  gs help            - Ver ayuda"
echo ""
echo -e "${BLUE}Ejemplo de uso:${NC}"
echo "  sudo gs update"