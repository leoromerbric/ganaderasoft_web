# 🚀 GanaderaSoft Deployment Automation

## 📋 Descripción

Este sistema de automation permite actualizar GanaderaSoft en el servidor de producción de manera completamente automatizada con un simple comando `gs update`. El proceso incluye descarga de cambios, instalación de dependencias, optimización y verificaciones de salud.

## 📁 Estructura de Scripts

```
scripts/
├── ganaderasoft-update.sh    # Script principal de actualización
└── setup-gs-command.sh       # Configuración del comando global
```

## 🔧 Instalación Inicial

### Paso 1: Subir Scripts al Servidor

```bash
# Desde tu máquina local (Windows), subir los archivos al servidor
scp scripts/* usuario@servidor:/var/www/ganaderasoft_web/scripts/
```

### Paso 2: Configurar Comando Global (Solo una vez)

```bash
# Conectarse al servidor Ubuntu
ssh usuario@servidor

# Dar permisos ejecutables al script de configuración
sudo chmod +x /var/www/ganaderasoft_web/scripts/setup-gs-command.sh

# Ejecutar configuración del comando global
sudo /var/www/ganaderasoft_web/scripts/setup-gs-command.sh
```

✅ **¡Listo!** Ya tienes disponible el comando `gs update`

## 🎯 Uso del Sistema

### Actualización Completa del Sistema

```bash
# Comando principal - Hace todo el proceso automatizado
sudo gs update
```

**Lo que hace internamente:**
1. 🔄 **Git Pull**: Descarga últimos cambios del repositorio
2. 💾 **Backup**: Crea respaldo automático del estado actual  
3. 📦 **Composer**: Instala/actualiza dependencias PHP
4. 🎨 **NPM**: Compila assets (CSS/JS) para producción
5. 🔐 **Permisos**: Configura permisos de archivos y directorios
6. ⚡ **Laravel**: Optimiza caché, rutas, vistas y configuración
7. 🩺 **Health Check**: Verifica que todo funcione correctamente

### Comandos Adicionales Disponibles

```bash
# Ver estado actual del sistema
gs status

# Ver logs de actualización
gs logs

# Ver logs de Laravel (errores de aplicación)
gs backup-logs

# Ver ayuda
gs help
```

## 🛡️ Características de Seguridad

### Backup Automático
- Cada actualización crea un backup automático en `/var/backups/ganaderasoft/`
- Incluye archivos `.env` y directorio `storage`
- Timestamp único para cada backup

### Verificaciones de Salud
- Verifica estado de servicios (Apache/Nginx, MySQL)
- Valida que Laravel puede ejecutarse
- Confirma permisos de escritura
- Logs detallados de cada operación

### Rollback Manual (Si es necesario)
```bash
# Listar backups disponibles
ls -la /var/backups/ganaderasoft/

# Restaurar backup específico (ejemplo)
sudo cp /var/backups/ganaderasoft/ganaderasoft_backup_YYYYMMDD_HHMMSS/.env /var/www/ganaderasoft_web/
```

## 📊 Monitoreo y Logs

### Ubicaciones de Logs
- **Actualización**: `/var/log/ganaderasoft-update.log`
- **Laravel**: `/var/www/ganaderasoft_web/storage/logs/laravel.log`
- **Backups**: `/var/backups/ganaderasoft/`

### Ver Logs en Tiempo Real
```bash
# Durante actualización
tail -f /var/log/ganaderasoft-update.log

# Errores de aplicación
tail -f /var/www/ganaderasoft_web/storage/logs/laravel.log
```

## 🔄 Workflow Típico de Deployment

### En Desarrollo (Tu máquina)
```bash
# 1. Hacer cambios en código
# 2. Commit y push
git add .
git commit -m "Fix: ApiCambiosAnimalService makeRequest error"
git push origin main
```

### En Producción (Servidor)
```bash
# 3. Actualizar servidor con un comando
sudo gs update
```

**¡Eso es todo!** El sistema:
- Descarga tus cambios
- Instala dependencias si hay nuevas
- Optimiza la aplicación
- Verifica que funcione
- Te muestra un resumen del proceso

## 🚨 Resolución de Problemas

### Error: "comando gs no encontrado"
```bash
# Verificar que está instalado
ls -la /usr/local/bin/gs

# Si no existe, ejecutar setup nuevamente
sudo /var/www/ganaderasoft_web/scripts/setup-gs-command.sh

# Reiniciar sesión o recargar PATH
source ~/.bashrc
```

### Error: "permission denied"
```bash
# Verificar permisos del script
sudo chmod +x /var/www/ganaderasoft_web/scripts/ganaderasoft-update.sh
sudo chmod +x /usr/local/bin/gs
```

### Ver Errores Detallados
```bash
# Log completo de última actualización
gs logs

# Errores de Laravel
gs backup-logs

# Estado del sistema
gs status
```

## 📈 Ventajas del Sistema

✅ **Automatización Completa**: Un comando hace todo
✅ **Seguridad**: Backups automáticos antes de cada actualización  
✅ **Verificaciones**: Health checks para confirmar que funciona
✅ **Logs Detallados**: Seguimiento completo de cada operación
✅ **Rollback Fácil**: Backups organizados por timestamp
✅ **Optimización**: Auto-optimiza Laravel para producción
✅ **Monitoreo**: Comandos para ver estado y logs
✅ **Consistencia**: Mismo proceso siempre, menos errores humanos

## 🎯 Casos de Uso

### Actualización Regular
```bash
# Cada vez que hay cambios en Git
sudo gs update
```

### Monitoreo del Sistema  
```bash
# Ver estado general
gs status

# Verificar logs si hay problemas
gs logs
gs backup-logs
```

### Después de Cambios Grandes
```bash
# Actualizar y verificar
sudo gs update
gs status

# Si hay problemas, ver logs detallados
gs logs
```

---

**💡 Tip**: Guarda este documento como referencia. Con `gs update` tienes todo automatizado, pero estos comandos adicionales te ayudan a supervisar y resolver problemas cuando sea necesario.