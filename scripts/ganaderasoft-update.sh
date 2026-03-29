#!/bin/bash

# 🚀 GanaderaSoft Update Script
# Automatiza todo el proceso de deployment y configuración
# Versión: 1.0
# Fecha: $(date '+%Y-%m-%d')

set -e  # Exit on any error

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuración
PROJECT_DIR="/var/www/ganaderasoft_web"
WEB_USER="www-data"
BACKUP_DIR="/var/backups/ganaderasoft"
LOG_FILE="/var/log/ganaderasoft-update.log"

# Funciones auxiliares
log() {
    echo -e "${BLUE}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

success() {
    echo -e "${GREEN}✅ $1${NC}" | tee -a "$LOG_FILE"
}

warning() {
    echo -e "${YELLOW}⚠️  $1${NC}" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}❌ $1${NC}" | tee -a "$LOG_FILE"
    exit 1
}

# Verificar que se ejecuta como root o con sudo
check_permissions() {
    if [ "$EUID" -ne 0 ]; then
        error "Este script debe ejecutarse como root o con sudo"
    fi
}

# Crear backup antes de actualizar
create_backup() {
    log "Creando backup del estado actual..."
    
    # Crear directorio de backup si no existe
    mkdir -p "$BACKUP_DIR"
    
    # Backup timestamp
    BACKUP_TIMESTAMP=$(date '+%Y%m%d_%H%M%S')
    BACKUP_PATH="$BACKUP_DIR/ganaderasoft_backup_$BACKUP_TIMESTAMP"
    
    # Crear backup de archivos críticos
    mkdir -p "$BACKUP_PATH"
    
    # Backup .env si existe
    if [ -f "$PROJECT_DIR/.env" ]; then
        cp "$PROJECT_DIR/.env" "$BACKUP_PATH/.env.backup"
        success "Backup de .env creado"
    fi
    
    # Backup storage si existe
    if [ -d "$PROJECT_DIR/storage" ]; then
        cp -r "$PROJECT_DIR/storage" "$BACKUP_PATH/storage_backup"
        success "Backup de storage creado"
    fi
    
    success "Backup creado en: $BACKUP_PATH"
}

# Verificar estado del repositorio Git
check_git_status() {
    log "Verificando estado del repositorio Git..."
    
    cd "$PROJECT_DIR"
    
    # Solucionar problema de "dubious ownership"
    git config --global --add safe.directory "$PROJECT_DIR"
    
    # Verificar si hay cambios no commiteados
    if [ -n "$(git status --porcelain)" ]; then
        warning "Hay cambios no commiteados. Guardando stash..."
        git stash push -m "Auto-stash antes de update $(date '+%Y-%m-%d %H:%M:%S')"
    fi
    
    success "Estado Git verificado"
}

# Descargar últimos cambios
update_code() {
    log "Descargando últimos cambios del repositorio..."
    
    cd "$PROJECT_DIR"
    
    # Asegurar que el directorio es seguro para Git
    git config --global --add safe.directory "$PROJECT_DIR"
    
    # Fetch latest changes
    git fetch origin
    
    # Get current branch
    CURRENT_BRANCH=$(git branch --show-current)
    log "Rama actual: $CURRENT_BRANCH"
    
    # Pull changes
    git pull origin "$CURRENT_BRANCH"
    
    success "Código actualizado desde Git"
}

# Verificar y generar .env si no existe
setup_env() {
    log "Verificando archivo .env..."
    
    cd "$PROJECT_DIR"
    
    if [ ! -f ".env" ]; then
        warning ".env no existe. Creando desde .env.example..."
        
        if [ -f ".env.example" ]; then
            cp .env.example .env
        else
            # Crear .env básico
            cat > .env << EOF
APP_NAME="GanaderaSoft"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=America/Caracas
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ganaderasoft
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"

# API Configuration
API_BASE_URL=https://api.example.com
API_TIMEOUT=30
EOF
        fi
        
        success ".env creado"
    fi
    
    # Generar APP_KEY si no existe
    if ! grep -q "APP_KEY=base64:" .env; then
        warning "Generando nueva APP_KEY..."
        sudo -u "$WEB_USER" php artisan key:generate --force
        success "APP_KEY generada"
    fi
}

# Actualizar dependencias de Composer
update_composer() {
    log "Verificando dependencias de Composer..."
    
    cd "$PROJECT_DIR"
    
    if [ -f "composer.json" ]; then
        log "Instalando/actualizando dependencias de Composer..."
        sudo -u "$WEB_USER" composer install --no-dev --optimize-autoloader --no-interaction
        success "Dependencias de Composer actualizadas"
    else
        warning "composer.json no encontrado"
    fi
}

# Actualizar dependencias de Node.js
update_npm() {
    log "Verificando dependencias de Node.js..."
    
    cd "$PROJECT_DIR"
    
    if [ -f "package.json" ]; then
        log "Instalando dependencias de NPM..."
        npm ci --silent
        
        log "Compilando assets..."
        npm run build
        
        success "Assets compilados"
    else
        warning "package.json no encontrado"
    fi
}

# Configurar permisos de archivos y directorios
fix_permissions() {
    log "Configurando permisos de archivos y directorios..."
    
    cd "$PROJECT_DIR"
    
    # Permisos generales del proyecto
    chown -R "$WEB_USER:$WEB_USER" .
    
    # Permisos específicos para storage
    mkdir -p storage/framework/cache/data
    mkdir -p storage/framework/sessions
    mkdir -p storage/framework/views
    mkdir -p storage/logs
    mkdir -p storage/app/public
    
    # Aplicar permisos a storage
    chown -R "$WEB_USER:$WEB_USER" storage/
    chmod -R 775 storage/
    
    # Permisos para bootstrap/cache
    mkdir -p bootstrap/cache
    chown -R "$WEB_USER:$WEB_USER" bootstrap/cache/
    chmod -R 775 bootstrap/cache/
    
    # Hacer ejecutable artisan
    chmod +x artisan
    
    success "Permisos configurados"
}

# Ejecutar migraciones y optimizaciones de Laravel
optimize_laravel() {
    log "Ejecutando optimizaciones de Laravel..."
    
    cd "$PROJECT_DIR"
    
    # Limpiar caches
    sudo -u "$WEB_USER" php artisan config:clear
    sudo -u "$WEB_USER" php artisan cache:clear
    sudo -u "$WEB_USER" php artisan view:clear
    sudo -u "$WEB_USER" php artisan route:clear
    
    # Optimizar para producción
    sudo -u "$WEB_USER" php artisan config:cache
    sudo -u "$WEB_USER" php artisan route:cache
    sudo -u "$WEB_USER" php artisan view:cache
    
    # Optimizar autoloader
    sudo -u "$WEB_USER" composer dump-autoload --optimize
    
    success "Laravel optimizado"
}

# Verificar estado de servicios
check_services() {
    log "Verificando estado de servicios..."
    
    # Verificar Apache/Nginx
    if systemctl is-active --quiet apache2; then
        success "Apache2 está ejecutándose"
        systemctl reload apache2
    elif systemctl is-active --quiet nginx; then
        success "Nginx está ejecutándose"
        systemctl reload nginx
    else
        warning "No se detectó Apache2 ni Nginx ejecutándose"
    fi
    
    # Verificar MySQL
    if systemctl is-active --quiet mysql; then
        success "MySQL está ejecutándose"
    elif systemctl is-active --quiet mariadb; then
        success "MariaDB está ejecutándose"
    else
        warning "No se detectó MySQL/MariaDB ejecutándose"
    fi
}

# Verificar que la aplicación funciona
health_check() {
    log "Realizando verificación de salud de la aplicación..."
    
    cd "$PROJECT_DIR"
    
    # Verificar que Laravel puede ejecutarse
    if sudo -u "$WEB_USER" php artisan about &>/dev/null; then
        success "Laravel está funcionando correctamente"
    else
        error "Laravel no puede ejecutarse correctamente"
    fi
    
    # Verificar permisos críticos
    if [ -w "storage/logs" ]; then
        success "Permisos de escritura en logs OK"
    else
        error "No se puede escribir en storage/logs"
    fi
}

# Función principal
main() {
    echo -e "${BLUE}"
    echo "🚀 GanaderaSoft Update Script"
    echo "=============================="
    echo "Iniciando actualización del sistema..."
    echo -e "${NC}"
    
    # Crear log file
    touch "$LOG_FILE"
    log "=== INICIO DE ACTUALIZACIÓN ==="
    
    # Ejecutar pasos
    check_permissions
    create_backup
    check_git_status
    update_code
    setup_env
    update_composer
    update_npm
    fix_permissions
    optimize_laravel
    check_services
    health_check
    
    success "🎉 ¡Actualización completada exitosamente!"
    log "=== FIN DE ACTUALIZACIÓN ==="
    
    echo ""
    echo -e "${GREEN}📋 Resumen de la actualización:${NC}"
    echo "• Backup creado en: $BACKUP_DIR"
    echo "• Log disponible en: $LOG_FILE"
    echo "• Aplicación funcionando correctamente"
    echo ""
    echo -e "${BLUE}Para ver los logs: tail -f $LOG_FILE${NC}"
}

# Ejecutar si se llama directamente
if [[ "${BASH_SOURCE[0]}" == "${0}" ]]; then
    main "$@"
fi