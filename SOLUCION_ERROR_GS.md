# 🔧 Solución para Error del Comando `gs update`

## ❌ Problema
```bash
sudo gs update
🚀 Ejecutando GanaderaSoft Update...
sudo: /var/www/ganaderasoft_web/scripts/ganaderasoft-update.sh: command not found
```

## ✅ Solución Rápida

### Paso 1: Obtener últimos cambios
```bash
cd /var/www/ganaderasoft_web
sudo git pull
```

### Paso 2: Ejecutar diagnóstico
```bash
sudo bash scripts/diagnose-gs.sh
```

### Paso 3: Reparar comando (si es necesario)
```bash
sudo bash scripts/fix-gs-command.sh
```

### Paso 4: Probar comando
```bash
gs test
sudo gs update
```

## 🎯 Comandos Disponibles Después de la Reparación

- **`gs test`** - Probar que el comando funciona
- **`gs status`** - Ver estado del sistema
- **`gs logs`** - Ver logs de actualización  
- **`gs laravel-logs`** - Ver logs de Laravel
- **`gs help`** - Ver ayuda completa
- **`sudo gs update`** - Actualizar aplicación (requiere sudo)

## 📋 Detalles Técnicos

### Lo que hace `diagnose-gs.sh`:
- ✅ Verifica si el comando `gs` existe
- ✅ Comprueba permisos y propietarios
- ✅ Valida rutas de archivos
- ✅ Ejecuta test básico

### Lo que hace `fix-gs-command.sh`:
- 🔧 Repara permisos de archivos
- 🔧 Recrea comando global `/usr/local/bin/gs`
- 🔧 Configura logs correctamente
- 🔧 Ejecuta test de validación

### Archivos involucrados:
- `/usr/local/bin/gs` - Comando global
- `/var/www/ganaderasoft_web/scripts/ganaderasoft-update.sh` - Script principal
- `/var/log/ganaderasoft-update.log` - Logs de actualización

## ⚠️ Notas Importantes

1. **Siempre usar `sudo` para `gs update`**
2. Si persiste el problema, reiniciar sesión (logout/login)
3. Verificar que `/usr/local/bin` esté en el PATH
4. El script principal debe tener permisos ejecutables

## 🚨 Si Nada Funciona

### Opción alternativa - Ejecución directa:
```bash
cd /var/www/ganaderasoft_web
sudo bash scripts/ganaderasoft-update.sh
```

### Debug completo:
```bash
echo $PATH | grep usr/local/bin  # Verificar PATH
ls -la /usr/local/bin/gs         # Verificar archivo
which gs                         # Verificar comando
```