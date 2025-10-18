# Arquitectura de la Implementación

## Vista General del Sistema

```
┌─────────────────────────────────────────────────────────────────────┐
│                         FRONTEND (Laravel Blade)                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐  │
│  │   Dashboard      │  │  Animales List   │  │  Create/Edit     │  │
│  │   index.blade    │  │  index.blade     │  │  Animal Forms    │  │
│  └────────┬─────────┘  └────────┬─────────┘  └────────┬─────────┘  │
│           │                     │                     │             │
│           └─────────────────────┴─────────────────────┘             │
│                                 │                                    │
├─────────────────────────────────┼────────────────────────────────────┤
│                         CONTROLLERS                                  │
├─────────────────────────────────┼────────────────────────────────────┤
│                                 │                                    │
│  ┌──────────────────────────────▼───────────────────────────────┐   │
│  │  DashboardController    │    AnimalesController              │   │
│  │  - index()              │    - index()                       │   │
│  │                         │    - create()                      │   │
│  │                         │    - store()                       │   │
│  │                         │    - show()                        │   │
│  │                         │    - edit()                        │   │
│  │                         │    - update()                      │   │
│  └──────────────┬──────────┴────────────────┬───────────────────┘   │
│                 │                           │                        │
├─────────────────┼───────────────────────────┼────────────────────────┤
│          SERVICE LAYER (Dependency Injection)                        │
├─────────────────┼───────────────────────────┼────────────────────────┤
│                 │                           │                        │
│  ┌──────────────▼─────────────┐  ┌─────────▼────────────────────┐  │
│  │ DashboardServiceInterface  │  │  AnimalesServiceInterface    │  │
│  │  - getKPIs()              │  │  - getAnimales()             │  │
│  │  - getFarmStatistics()    │  │  - getAnimal()               │  │
│  │  - getProductionData()    │  │  - createAnimal()            │  │
│  │  - getFarms()             │  │  - updateAnimal()            │  │
│  └──────────────┬─────────────┘  └─────────┬────────────────────┘  │
│                 │                           │                        │
│  ┌──────────────▼─────────────┐  ┌─────────▼────────────────────┐  │
│  │  ApiDashboardService       │  │  ApiAnimalesService          │  │
│  │  (Implementación)          │  │  (Implementación)            │  │
│  └──────────────┬─────────────┘  └─────────┬────────────────────┘  │
│                 │                           │                        │
│                 └───────────────┬───────────┘                        │
│                                 │                                    │
│                 ┌───────────────▼───────────────┐                   │
│                 │     BaseApiService            │                   │
│                 │  - get(endpoint)              │                   │
│                 │  - post(endpoint, data)       │                   │
│                 │  - put(endpoint, data)        │                   │
│                 └───────────────┬───────────────┘                   │
│                                 │                                    │
└─────────────────────────────────┼────────────────────────────────────┘
                                  │
                                  │ HTTP Requests (Bearer Token)
                                  │
                    ┌─────────────▼──────────────┐
                    │   BACKEND API REST         │
                    │   Port: 9000               │
                    ├────────────────────────────┤
                    │ /api/reportes/fincas       │
                    │ /api/animales              │
                    │ /api/animales/{id}         │
                    │ /api/configuracion/*       │
                    └────────────────────────────┘
```

## Flujo de Datos: Dashboard

```
1. Usuario accede a /dashboard?id_finca=16

2. DashboardController::index()
   ↓
3. $dashboardService->getFarmStatistics(16)
   ↓
4. ApiDashboardService::getFarmStatistics(16)
   ↓
5. HTTP GET /api/reportes/fincas?id_finca=16
   Headers: Authorization: Bearer {token}
   ↓
6. API Response:
   {
     "resumen": { "total_animales": 5, ... },
     "animales_por_sexo": { "M": 1, "F": 4 },
     "personal_por_tipo": { ... },
     "fincas": [...],
     "rebanos": [...]
   }
   ↓
7. Transformación a KPIs y Chart Data
   ↓
8. Return view('dashboard.index', [
     'kpis' => [...],
     'chartData' => [...],
     'statistics' => [...]
   ])
   ↓
9. Blade renderiza con Chart.js
```

## Flujo de Datos: Crear Animal

```
1. Usuario accede a /animales/create

2. AnimalesController::create()
   ↓
3. Obtener datos necesarios:
   - $rebanosService->getRebanos()
   - $animalesService->getRazas()
   - $animalesService->getEstadosSalud()
   - $animalesService->getEtapas()
   ↓
4. Return view('animales.create', [
     'rebanos' => [...],
     'razas' => [...],
     'estados' => [...],
     'etapas' => [...]
   ])
   ↓
5. Usuario completa formulario y envía

6. AnimalesController::store(Request)
   ↓
7. Validación de datos
   ↓
8. $animalesService->createAnimal($validatedData)
   ↓
9. ApiAnimalesService::createAnimal()
   ↓
10. HTTP POST /api/animales
    Body: {
      "id_Rebano": 7,
      "Nombre": "Animal 3",
      "codigo_animal": "ANIMAL-003",
      ...
      "estado_inicial": { ... },
      "etapa_inicial": { ... }
    }
    ↓
11. API Response:
    {
      "success": true,
      "message": "Animal creado exitosamente",
      "data": { ... }
    }
    ↓
12. Redirect to /animales with success message
```

## Estructura de Archivos

```
ganaderasoft_web/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── DashboardController.php          ← MODIFICADO
│   │       └── AnimalesController.php           ← NUEVO
│   │
│   ├── Services/
│   │   ├── Contracts/
│   │   │   ├── DashboardServiceInterface.php    ← MODIFICADO
│   │   │   └── AnimalesServiceInterface.php     ← NUEVO
│   │   │
│   │   ├── Api/
│   │   │   ├── BaseApiService.php
│   │   │   ├── ApiDashboardService.php          ← NUEVO
│   │   │   └── ApiAnimalesService.php           ← NUEVO
│   │   │
│   │   └── Mock/
│   │       └── MockDashboardService.php         ← MODIFICADO
│   │
│   └── Providers/
│       └── AppServiceProvider.php               ← MODIFICADO
│
├── resources/
│   └── views/
│       ├── dashboard/
│       │   └── index.blade.php                  ← MODIFICADO
│       │
│       ├── animales/                            ← NUEVO DIRECTORIO
│       │   ├── index.blade.php                  ← NUEVO
│       │   ├── create.blade.php                 ← NUEVO
│       │   ├── edit.blade.php                   ← NUEVO
│       │   └── show.blade.php                   ← NUEVO
│       │
│       └── layouts/
│           └── authenticated.blade.php          ← MODIFICADO
│
├── routes/
│   └── web.php                                  ← MODIFICADO
│
├── docs/
│   └── apis_docs/
│       ├── reporte_metricas_finca.txt
│       ├── reporte_metricas_finca_by_finca.txt
│       ├── get_animal.txt
│       ├── create_animal.txt
│       └── update_animal.txt
│
├── IMPLEMENTATION_SUMMARY.md                     ← NUEVO
└── USER_GUIDE.md                                ← NUEVO
```

## Service Bindings (AppServiceProvider)

```php
// Dashboard Service
$this->app->bind(
    DashboardServiceInterface::class,
    ApiDashboardService::class  // ← Usando API
);

// Animales Service
$this->app->bind(
    AnimalesServiceInterface::class,
    ApiAnimalesService::class  // ← Usando API
);

// Para cambiar a Mock (pruebas):
// ApiDashboardService::class → MockDashboardService::class
```

## Rutas Web

```
┌─────────────────────────────────────────────────────────┐
│                  Rutas Protegidas                       │
│              (Middleware: mock.auth)                    │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Dashboard:                                             │
│  GET  /dashboard                                        │
│       → DashboardController@index                       │
│       → Soporta ?id_finca=X                            │
│                                                         │
│  Animales:                                              │
│  GET    /animales                                       │
│         → AnimalesController@index                      │
│         → Soporta ?id_rebano=X                         │
│                                                         │
│  GET    /animales/create                                │
│         → AnimalesController@create                     │
│                                                         │
│  POST   /animales                                       │
│         → AnimalesController@store                      │
│                                                         │
│  GET    /animales/{id}                                  │
│         → AnimalesController@show                       │
│                                                         │
│  GET    /animales/{id}/edit                             │
│         → AnimalesController@edit                       │
│                                                         │
│  PUT    /animales/{id}                                  │
│         → AnimalesController@update                     │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

## Componentes de UI

### Dashboard

```
┌──────────────────────────────────────────────────────────┐
│  Dashboard                                               │
│  ┌────────────────────────────────────────────────────┐ │
│  │ Filtrar por Finca: [Todas las Fincas ▼]           │ │
│  └────────────────────────────────────────────────────┘ │
│                                                          │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐      │
│  │ 🐄 5    │ │ 🏡 2    │ │ 🐑 2    │ │ 👥 4    │      │
│  │Animales │ │ Fincas  │ │ Rebaños │ │Personal │      │
│  └─────────┘ └─────────┘ └─────────┘ └─────────┘      │
│                                                          │
│  ┌──────────────────────┐ ┌──────────────────────┐     │
│  │ Animales por Sexo    │ │ Personal por Tipo    │     │
│  │  (Pie Chart)         │ │  (Bar Chart)         │     │
│  │                      │ │                      │     │
│  │    [Gráfico]         │ │    [Gráfico]         │     │
│  │                      │ │                      │     │
│  └──────────────────────┘ └──────────────────────┘     │
│                                                          │
│  ┌────────────────────────────────────────────────────┐ │
│  │ Fincas                                             │ │
│  │ ┌────────────┬──────┬─────────┬─────────┐        │ │
│  │ │ Nombre     │Rebaños│Animales│Personal │        │ │
│  │ ├────────────┼──────┼─────────┼─────────┤        │ │
│  │ │ Finca 1    │  2   │    5    │    4    │        │ │
│  │ └────────────┴──────┴─────────┴─────────┘        │ │
│  └────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────┘
```

### Lista de Animales

```
┌──────────────────────────────────────────────────────────┐
│  Gestión de Animales        [+ Crear Animal]            │
│                                                           │
│  Filtrar por Rebaño: [Todos los Rebaños ▼]             │
│                                                           │
│  ┌───────────────────────────────────────────────────┐  │
│  │ Código  │ Nombre │ Sexo │ Rebaño │ F.Nac │Acciones│  │
│  ├─────────┼────────┼──────┼────────┼───────┼────────┤  │
│  │ VL-001  │Animal 1│  F   │Rebaño 1│01/2024│Ver│Edit│  │
│  │ VM-002  │Animal 2│  M   │Rebaño 2│02/2024│Ver│Edit│  │
│  └───────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────┘
```

### Formulario Crear/Editar

```
┌──────────────────────────────────────────────────────────┐
│  Crear Nuevo Animal                                      │
│                                                           │
│  ┌─────────────────┐  ┌─────────────────┐              │
│  │ Rebaño *        │  │ Nombre *        │              │
│  │ [Mi Rebaño ▼]   │  │ [Animal 1    ]  │              │
│  └─────────────────┘  └─────────────────┘              │
│                                                           │
│  ┌─────────────────┐  ┌─────────────────┐              │
│  │ Código *        │  │ Sexo *          │              │
│  │ [ANIMAL-001  ]  │  │ [Hembra ▼]      │              │
│  └─────────────────┘  └─────────────────┘              │
│                                                           │
│  ┌─────────────────┐  ┌─────────────────┐              │
│  │ Fecha Nac. *    │  │ Procedencia *   │              │
│  │ [2024-01-15  ]  │  │ [Local       ]  │              │
│  └─────────────────┘  └─────────────────┘              │
│                                                           │
│  ┌─────────────────────────────────────┐                │
│  │ Raza *                              │                │
│  │ [Holstein (HOL) ▼]                  │                │
│  └─────────────────────────────────────┘                │
│                                                           │
│  ┌─────────────────┐  ┌─────────────────┐              │
│  │ Estado Salud *  │  │ Fecha Estado *  │              │
│  │ [Sano ▼]        │  │ [2024-01-15  ]  │              │
│  └─────────────────┘  └─────────────────┘              │
│                                                           │
│  ┌─────────────────┐  ┌─────────────────┐              │
│  │ Etapa *         │  │ Fecha Etapa *   │              │
│  │ [Novilla ▼]     │  │ [2024-01-15  ]  │              │
│  └─────────────────┘  └─────────────────┘              │
│                                                           │
│  [Cancelar]  [Crear Animal]                             │
└──────────────────────────────────────────────────────────┘
```

## Patrones de Diseño Utilizados

1. **Repository Pattern** (via Services)
2. **Dependency Injection** (Controllers + Services)
3. **Service Layer Pattern**
4. **Interface Segregation**
5. **MVC (Model-View-Controller)**

## Tecnologías

- **Backend Framework**: Laravel 10
- **Frontend**: Blade Templates
- **Styling**: Tailwind CSS
- **Charts**: Chart.js
- **HTTP Client**: Guzzle (Laravel HTTP)
- **Authentication**: Session-based (Bearer Token to API)
