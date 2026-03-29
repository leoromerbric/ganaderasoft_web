# 🚀 Comandos de Deployment - GanaderaSoft

## 1. Subir archivo corregido al servidor
```bash
# Copiar el archivo ApiCambiosAnimalService.php al servidor
scp app/Services/Api/ApiCambiosAnimalService.php usuario@servidor:/var/www/ganaderasoft_web/app/Services/Api/
```

## 2. Ejecutar en el servidor Ubuntu (como root o con sudo)
```bash
# Acceder al servidor
ssh usuario@servidor

# Ir al directorio del proyecto
cd /var/www/ganaderasoft_web

# Corregir permisos de directorios de storage
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/

# Crear directorios faltantes si no existen
sudo mkdir -p storage/framework/views
sudo mkdir -p storage/framework/cache/data
sudo mkdir -p storage/framework/sessions
sudo mkdir -p storage/logs

# Aplicar permisos específicos
sudo chown -R www-data:www-data storage/framework/
sudo chmod -R 775 storage/framework/

# Limpiar cache de Laravel
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear

# Verificar que el .env existe y tiene APP_KEY
sudo -u www-data php artisan config:cache
```

## 3. Verificar que el archivo está correctamente ubicado
```bash
# Verificar que el archivo existe
ls -la /var/www/ganaderasoft_web/app/Services/Api/ApiCambiosAnimalService.php

# Verificar permisos
ls -la /var/www/ganaderasoft_web/storage/framework/views/
```

## 4. Probar la funcionalidad
1. Accede a: `http://tuservidor.com/cambios-animal`
2. Intenta crear un nuevo cambio de animal
3. Verifica que no aparezcan errores de `makeRequest()`

## 🔍 Verificación de Errores
Si aún hay problemas, revisa los logs:
```bash
sudo tail -f /var/www/ganaderasoft_web/storage/logs/laravel.log
```

## ✅ Estado Actual
- ❌ ~~Error: Call to undefined method makeRequest()~~
- ✅ **CORREGIDO**: Todos los métodos ahora usan BaseApiService correctamente
- ✅ **Autenticación**: Agregada verificación de token de usuario
- ✅ **Headers**: Authorization Bearer incluido en todas las llamadas API