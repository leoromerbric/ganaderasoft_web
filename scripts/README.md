# 📋 GanaderaSoft Scripts - Guía Rápida

## 🎯 ¿Qué hace este sistema?

Te permite actualizar tu servidor de producción con **UN SOLO COMANDO**:

```bash
sudo gs update
```

Eso es todo. El sistema hace automáticamente:
- ✅ Git pull (descarga cambios)
- ✅ Composer install (dependencias PHP)  
- ✅ NPM build (compilar CSS/JS)
- ✅ Permisos de archivos
- ✅ Optimización Laravel
- ✅ Verificación de salud

## 🚀 Instalación (Solo una vez)

### Paso 1: Subir archivos al servidor
```bash
# Desde Windows, subir carpeta scripts/
scp -r scripts/ usuario@servidor:/var/www/ganaderasoft_web/
```

### Paso 2: Configurar comando global
```bash
# En el servidor Ubuntu
ssh usuario@servidor
sudo chmod +x /var/www/ganaderasoft_web/scripts/setup-gs-command.sh  
sudo /var/www/ganaderasoft_web/scripts/setup-gs-command.sh
```

### Paso 3: Verificar que funciona
```bash
# Validar instalación
sudo chmod +x /var/www/ganaderasoft_web/scripts/validate-setup.sh
sudo /var/www/ganaderasoft_web/scripts/validate-setup.sh

# Primer actualización
sudo gs update
```

## 📁 Archivos Incluidos

| Archivo | Propósito |
|---------|-----------|
| `ganaderasoft-update.sh` | Script principal de actualización |
| `setup-gs-command.sh` | Configura comando global `gs` |
| `validate-setup.sh` | Valida que todo esté configurado |

## 🎮 Comandos Disponibles

```bash
sudo gs update      # Actualización completa del sistema
gs status           # Ver estado actual  
gs logs            # Ver logs de actualización
gs backup-logs     # Ver logs de Laravel
gs help            # Ayuda
```

## 🔄 Uso Diario

### Desarrollo → Producción

**En tu máquina (Windows):**
```bash
git add .
git commit -m "Nueva funcionalidad"  
git push
```

**En el servidor (Ubuntu):**
```bash
sudo gs update
```

**¡Listo!** Tu código está desplegado con todas las optimizaciones.

## 🛡️ Características

- **🔒 Backup automático** antes de cada actualización
- **🩺 Health checks** para verificar que funciona
- **📋 Logs detallados** de cada operación  
- **⚡ Optimización automática** de Laravel
- **🔧 Gestión de permisos** automática

## 📊 Monitoreo

Ver logs en tiempo real:
```bash
# Durante actualización
tail -f /var/log/ganaderasoft-update.log

# Errores de aplicación  
tail -f /var/www/ganaderasoft_web/storage/logs/laravel.log
```

## 🚨 Si algo sale mal

```bash
# Ver qué pasó
gs logs

# Ver estado del sistema
gs status

# Restaurar backup manual (si es necesario)
ls /var/backups/ganaderasoft/
sudo cp /var/backups/ganaderasoft/ganaderasoft_backup_FECHA/.env /var/www/ganaderasoft_web/
```

## ✅ Validación  

Ejecutar después de instalar para confirmar que todo funciona:
```bash
sudo /var/www/ganaderasoft_web/scripts/validate-setup.sh
```

---

### 💡 TL;DR (Demasiado Largo; No Leí)

1. **Instalar**: `sudo /var/www/ganaderasoft_web/scripts/setup-gs-command.sh`
2. **Usar**: `sudo gs update` 
3. **Monitorear**: `gs status` y `gs logs`

**¡Eso es todo!** 🎉