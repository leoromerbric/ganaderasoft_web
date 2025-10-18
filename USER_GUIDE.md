# Guía de Uso - Dashboard y Gestión de Animales

## 1. Dashboard Principal con Filtros

### Acceso
- URL: `/dashboard`
- Requiere autenticación

### Características

#### Filtro por Finca
El dashboard ahora incluye un selector desplegable para filtrar las estadísticas por finca:

```
Dashboard
┌──────────────────────────────────────────────┐
│ Filtrar por Finca: [Todas las Fincas ▼]     │
└──────────────────────────────────────────────┘
```

Para filtrar por una finca específica:
1. Selecciona la finca del dropdown
2. El sistema recarga automáticamente con el parámetro `?id_finca=X`
3. Todas las métricas se actualizan para mostrar solo datos de esa finca

#### KPIs Mostrados

```
┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│ 🐄           │  │ 🏡           │  │ 🐑           │  │ 👥           │
│ Total        │  │ Total        │  │ Total        │  │ Total        │
│ Animales     │  │ Fincas       │  │ Rebaños      │  │ Personal     │
│              │  │              │  │              │  │              │
│    5         │  │    2         │  │    2         │  │    4         │
└──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
```

Los valores se obtienen de: `GET /api/reportes/fincas?id_finca={id}`

#### Gráficos Implementados

**1. Distribución de Animales por Sexo (Gráfico Circular)**
- Muestra la proporción de machos y hembras
- Tipo: Pie Chart
- Datos: `animales_por_sexo: { "M": X, "F": Y }`

**2. Personal por Tipo (Gráfico de Barras)**
- Muestra la cantidad de personal por cada tipo
- Tipo: Bar Chart
- Datos: `personal_por_tipo: { "Tecnico": X, "Veterinario": Y, ... }`

#### Tablas de Resumen

**Tabla de Fincas:**
- Nombre de la finca
- Cantidad de rebaños
- Cantidad de animales
- Cantidad de personal

**Tabla de Rebaños:**
- Nombre del rebaño
- ID de la finca asociada
- Cantidad de animales

## 2. Gestión de Animales

### 2.1 Lista de Animales

**Acceso:** `/animales` o menú lateral → "Lista de Animales"

**Características:**
- Tabla con todos los animales registrados
- Filtro por rebaño mediante dropdown
- Columnas: Código, Nombre, Sexo, Rebaño, Fecha Nacimiento, Estado, Acciones
- Acciones: Ver detalle, Editar
- Botón "Crear Animal" en la parte superior

**Filtrado:**
```
Filtrar por Rebaño: [Todos los Rebaños ▼]
```

### 2.2 Crear Animal

**Acceso:** `/animales/create` o botón "+ Crear Animal"

**Formulario incluye:**

**Información Básica:**
- Rebaño* (selector con nombre de rebaño y finca)
- Nombre* (texto)
- Código* (texto, ej: ANIMAL-001)
- Sexo* (selector: Macho/Hembra)
- Fecha de Nacimiento* (date picker)
- Procedencia* (texto, ej: Local, Importado)

**Información de Raza:**
- Raza* (selector con nombre y siglas de la raza)

**Estado y Etapa Inicial:**
- Estado de Salud Inicial* (selector)
- Fecha Estado Inicial* (date picker)
- Etapa Inicial* (selector)
- Fecha Etapa Inicial* (date picker)

**Ejemplo de Datos a Enviar:**
```json
{
  "id_Rebano": 7,
  "Nombre": "Animal 3",
  "codigo_animal": "ANIMAL-003",
  "Sexo": "M",
  "fecha_nacimiento": "2025-01-17",
  "Procedencia": "Local",
  "fk_composicion_raza": 72,
  "estado_inicial": {
    "estado_id": 15,
    "fecha_ini": "2025-01-17"
  },
  "etapa_inicial": {
    "etapa_id": 15,
    "fecha_ini": "2025-01-17"
  }
}
```

**Endpoint:** `POST /api/animales`

### 2.3 Ver Detalle del Animal

**Acceso:** `/animales/{id}` o botón "Ver" en la lista

**Información Mostrada:**

**Información General:**
- Nombre, Código
- Sexo (badge con color)
- Fecha de Nacimiento
- Procedencia
- Estado (Activo/Archivado)

**Información de Raza:**
- Nombre de la raza
- Siglas
- Propósito
- Origen

**Rebaño y Finca:**
- Nombre del rebaño
- Nombre de la finca
- (Si disponible) Propietario de la finca

**Estado de Salud Actual:**
- Estado (ej: Sano)
- Fecha de inicio

**Etapa Actual:**
- Nombre de la etapa (ej: Becerro)
- Fecha de inicio
- Rango de edad (en días)

**Fechas de Registro:**
- Fecha de creación
- Última actualización

**Endpoint:** `GET /api/animales/{id}`

### 2.4 Editar Animal

**Acceso:** `/animales/{id}/edit` o botón "Editar" en la lista/detalle

**Formulario incluye:**
- Todos los campos del formulario de creación
- Valores pre-cargados del animal actual
- Checkbox adicional: "Archivar animal"

**Endpoint:** `PUT /api/animales/{id}`

## 3. Flujo de Navegación

```
Login
  │
  ├─→ Dashboard Principal
  │     ├─→ Filtrar por Finca
  │     └─→ Ver métricas y gráficos
  │
  ├─→ Lista de Animales
  │     ├─→ Filtrar por Rebaño
  │     │
  │     ├─→ Ver Detalle Animal
  │     │     └─→ Editar Animal
  │     │
  │     └─→ Crear Nuevo Animal
  │
  └─→ Otras secciones (Fincas, Rebaños, Personal)
```

## 4. Validaciones

### Formulario de Animales

**Campos Requeridos:**
- Todos los campos marcados con asterisco (*)
- Mensajes de error en español

**Validaciones Específicas:**
- `id_Rebano`: Debe ser un entero válido
- `Sexo`: Solo acepta "M" o "F"
- `fecha_nacimiento`: Formato de fecha válido
- `codigo_animal`: Máximo 50 caracteres
- `Nombre`: Máximo 255 caracteres
- `estado_inicial.estado_id`: ID de estado válido
- `etapa_inicial.etapa_id`: ID de etapa válida

## 5. Manejo de Errores

### Errores Comunes

**Error de Conexión:**
```
Error de conexión: Could not connect to server
```
- Solución: Verificar que el API backend esté disponible

**Usuario No Autenticado:**
```
Usuario no autenticado
```
- Solución: Hacer login nuevamente

**Validación Fallida:**
```
El nombre del animal es requerido
Debe seleccionar un rebaño
```
- Solución: Completar todos los campos requeridos

## 6. Ejemplos de Uso

### Ejemplo 1: Filtrar Dashboard por Finca

1. Ir a `/dashboard`
2. Seleccionar "Finca La Romeria" del dropdown
3. URL cambia a `/dashboard?id_finca=16`
4. Métricas se actualizan mostrando solo datos de esa finca

### Ejemplo 2: Crear un Nuevo Animal

1. Ir a `/animales`
2. Click en "+ Crear Animal"
3. Completar formulario:
   - Rebaño: "Mi Rebaño - Finca La Romeria"
   - Nombre: "Vaca Lechera #1"
   - Código: "VL-001"
   - Sexo: "Hembra"
   - Fecha Nacimiento: "2024-01-15"
   - Procedencia: "Local"
   - Raza: "Holstein (HOL)"
   - Estado Salud: "Sano"
   - Fecha Estado: "2024-01-15"
   - Etapa: "Novilla"
   - Fecha Etapa: "2024-01-15"
4. Click "Crear Animal"
5. Redirección a `/animales` con mensaje de éxito

### Ejemplo 3: Ver Detalles de un Animal

1. Ir a `/animales`
2. Click "Ver" en el animal deseado
3. Ver toda la información detallada
4. Opción de "Editar" para modificar

## 7. Integración con API Backend

### Base URL
```
http://ec2-54-219-108-54.us-west-1.compute.amazonaws.com:9000/api
```

### Autenticación
- Tipo: Bearer Token
- Header: `Authorization: Bearer {token}`
- Token almacenado en: `session('user')['token']`

### Endpoints Utilizados

**Dashboard:**
- `GET /reportes/fincas` - Todas las fincas
- `GET /reportes/fincas?id_finca=X` - Finca específica

**Animales:**
- `GET /animales` - Listar todos
- `GET /animales?id_rebano=X` - Filtrar por rebaño
- `GET /animales/{id}` - Detalle
- `POST /animales` - Crear
- `PUT /animales/{id}` - Actualizar

**Configuración:**
- `GET /configuracion/razas` - Lista de razas
- `GET /configuracion/estados-salud` - Estados de salud
- `GET /configuracion/etapas` - Etapas de animales

## 8. Próximas Mejoras Sugeridas

1. **Paginación** en lista de animales
2. **Búsqueda** por nombre o código
3. **Eliminación** lógica de animales
4. **Filtros avanzados** (por sexo, raza, estado)
5. **Exportación** a PDF/Excel
6. **Carga de imágenes** de animales
7. **Historial** de cambios de estado/etapa
8. **Dashboard** individual por animal
