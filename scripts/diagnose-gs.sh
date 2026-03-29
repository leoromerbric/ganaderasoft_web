#!/bin/bash

# 🔍 Diagnóstico rápido del comando GS
# Verifica el estado actual del comando 'gs update'

echo "🔍 Diagnóstico del comando 'gs'"
echo "==============================="
echo ""

# 1. Verificar si el comando gs existe
echo "1. Verificando comando 'gs':"
if command -v gs &> /dev/null; then
    echo "✅ Comando 'gs' encontrado en: $(which gs)"
else
    echo "❌ Comando 'gs' NO encontrado"
fi
echo ""

# 2. Verificar archivo en /usr/local/bin/gs
echo "2. Verificando /usr/local/bin/gs:"
if [ -f "/usr/local/bin/gs" ]; then
    echo "✅ Archivo existe"
    echo "   Permisos: $(ls -la /usr/local/bin/gs | awk '{print $1}')"
    echo "   Propietario: $(ls -la /usr/local/bin/gs | awk '{print $3":"$4}')"
    if [ -x "/usr/local/bin/gs" ]; then
        echo "✅ Archivo es ejecutable"
    else
        echo "❌ Archivo NO es ejecutable"
    fi
else
    echo "❌ Archivo NO existe"
fi
echo ""

# 3. Verificar directorio del proyecto
echo "3. Verificando directorio del proyecto:"
PROJECT_DIR="/var/www/ganaderasoft_web"
if [ -d "$PROJECT_DIR" ]; then
    echo "✅ Directorio existe: $PROJECT_DIR"
    echo "   Propietario: $(ls -ld $PROJECT_DIR | awk '{print $3":"$4}')"
else
    echo "❌ Directorio NO existe: $PROJECT_DIR"
fi
echo ""

# 4. Verificar script principal
echo "4. Verificando script principal:"
SCRIPT_FILE="$PROJECT_DIR/scripts/ganaderasoft-update.sh"
if [ -f "$SCRIPT_FILE" ]; then
    echo "✅ Script existe: $SCRIPT_FILE"
    echo "   Permisos: $(ls -la "$SCRIPT_FILE" | awk '{print $1}')"
    if [ -x "$SCRIPT_FILE" ]; then
        echo "✅ Script es ejecutable"
    else
        echo "❌ Script NO es ejecutable"
    fi
else
    echo "❌ Script NO existe: $SCRIPT_FILE"
fi
echo ""

# 5. Verificar PATH
echo "5. Verificando PATH:"
echo "   /usr/local/bin en PATH: $(echo $PATH | grep -o '/usr/local/bin' || echo 'NO')"
echo ""

# 6. Test básico si el comando existe
echo "6. Test básico:"
if command -v gs &> /dev/null; then
    echo "Ejecutando 'gs test'..."
    gs test 2>&1 || echo "Error ejecutando gs test"
else
    echo "❌ No se puede probar - comando 'gs' no disponible"
fi
echo ""

echo "🔧 Para reparar, ejecuta:"
echo "   sudo bash $PROJECT_DIR/scripts/fix-gs-command.sh"