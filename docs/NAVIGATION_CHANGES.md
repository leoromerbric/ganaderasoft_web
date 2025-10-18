# Cambios en la Estructura de Navegación

## Resumen de Cambios

Este documento describe los cambios implementados en la estructura de navegación de GanaderaSoft según el issue #[número].

## Nueva Funcionalidad: Dashboard por Finca

### 1. Acceso desde Listado de Fincas

En la vista de listado de fincas (`/fincas`), cada finca ahora incluye un botón verde **"Ir a Finca"** que redirige al usuario a un dashboard específico de gestión para esa finca.

### 2. Vista de Dashboard de Finca

Ruta: `/fincas/{id}/dashboard`

Esta nueva vista proporciona acceso a todos los módulos de gestión de la finca seleccionada mediante botones grandes y visualmente atractivos, organizados en una cuadrícula.

**Diferencias clave con el dashboard principal:**
- ✅ **Sin sidebar vertical**: Layout más limpio y enfocado
- ✅ **Breadcrumb**: Muestra "Lista de Fincas / [Nombre de Finca]" para fácil navegación
- ✅ **Módulos como botones**: En lugar de menú vertical, se muestran como cards clickeables
- ✅ **Información de la finca**: Panel con datos del propietario y terreno

### 3. Módulos Disponibles

#### Módulos Activos (con enlaces funcionales):
- **🐄 Rebaños**: Gestión de rebaños y grupos de animales
- **👥 Personal de Finca**: Gestión de trabajadores y personal

#### Módulos Próximamente:
- **📋 Animales**: Registro individual de animales
- **💝 Calendario Reproductivo**: Control de ciclos y eventos reproductivos
- **🥛 Registro Diario**: Registro de producción lechera diaria
- **🏥 Plan de Vacunación**: Control de vacunas y tratamientos
- **📊 Reportes Productivos**: Informes y análisis de producción

## Filtrado Automático por Finca

### Comportamiento del Sistema

1. **Selección de Finca**: 
   - Al hacer clic en "Ir a Finca", el sistema almacena la finca seleccionada en la sesión del usuario
   - Esta selección persiste durante toda la sesión

2. **Filtrado en Rebaños**:
   - Al acceder a la vista de rebaños desde el dashboard de finca, solo se muestran los rebaños pertenecientes a esa finca
   - Si se intenta acceder directamente a `/rebanos` sin finca seleccionada, se muestra un mensaje de error

3. **Filtrado en Personal**:
   - Similar a rebaños, el personal se filtra automáticamente por la finca seleccionada
   - El campo de filtro por finca se pre-llena con el ID de la finca seleccionada

4. **Cambio de Finca**:
   - Para gestionar otra finca, el usuario debe regresar al listado de fincas usando el breadcrumb
   - Luego seleccionar "Ir a Finca" en una finca diferente

## Implementación Técnica

### Session Storage
```php
// Al seleccionar una finca
session(['selected_finca' => $finca]);

// Al acceder a vistas filtradas
$selectedFinca = session('selected_finca');
```

### Layouts Dinámicos
Las vistas de rebaños y personal ahora usan layouts dinámicos:
```blade
@extends(session('selected_finca') ? 'layouts.finca' : 'layouts.authenticated')
```

### Filtrado en Controladores
```php
// RebanosController
$selectedFinca = session('selected_finca');
if (!$selectedFinca) {
    return view('rebanos.index', ['error' => 'Debe seleccionar una finca primero']);
}

// Filtrar por finca
$rebanos = array_filter($allRebanos, function($rebano) use ($selectedFinca) {
    return $rebano['id_Finca'] == $selectedFinca['id_Finca'];
});
```

## APIs REST Habilitadas

### Endpoints Disponibles

1. **POST /api/login** - Autenticación
2. **POST /api/logout** - Cerrar sesión
3. **GET /api/fincas** - Listar fincas
4. **GET /api/rebanos** - Listar rebaños
5. **GET /api/personal?id_finca={id}** - Listar personal

### Configuración

Las APIs están implementadas y listas para usar. Por defecto, el sistema usa Mock services para demostración.

**Para habilitar APIs reales:**

Editar `app/Providers/AppServiceProvider.php` y cambiar de `Mock` a `Api`:

```php
$this->app->bind(
    \App\Services\Contracts\AuthServiceInterface::class,
    \App\Services\Api\ApiAuthService::class  // Cambiar de MockAuthService
);

$this->app->bind(
    \App\Services\Contracts\FincasServiceInterface::class,
    \App\Services\Api\ApiFincasService::class  // Cambiar de MockFincasService
);

$this->app->bind(
    \App\Services\Contracts\RebanosServiceInterface::class,
    \App\Services\Api\ApiRebanosService::class  // Cambiar de MockRebanosService
);

$this->app->bind(
    \App\Services\Contracts\PersonalServiceInterface::class,
    \App\Services\Api\ApiPersonalService::class  // Cambiar de MockPersonalService
);
```

## Próximos Pasos

1. Implementar los módulos marcados como "Próximamente"
2. Agregar funcionalidad CRUD completa para cada módulo
3. Implementar filtros adicionales y búsqueda
4. Agregar reportes específicos por finca
5. Implementar notificaciones por finca

## Archivos Clave

- `resources/views/fincas/dashboard.blade.php` - Vista principal del dashboard de finca
- `resources/views/layouts/finca.blade.php` - Layout sin sidebar para vistas de finca
- `app/Http/Controllers/FincasController.php` - Lógica de dashboard y API
- `routes/web.php` - Rutas web incluyendo la nueva ruta del dashboard
- `routes/api.php` - Endpoints API habilitados
- `docs/API_USAGE.md` - Documentación completa de las APIs
