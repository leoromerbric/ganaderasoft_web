# API Usage Guide

Este documento describe cómo usar las APIs REST habilitadas en GanaderaSoft.

## Endpoints Disponibles

### 1. Autenticación

#### Login
```bash
POST /api/login
Content-Type: application/json
Accept: application/json

{
  "email": "admin@demo.cl",
  "password": "Password123!"
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "user": {
      "email": "admin@demo.cl",
      "name": "Administrador GanaderaSoft",
      "role": "admin",
      "token": "8da327c71e84d7a2cdc7ce2d49b3d2e8d29868dee1ea028e2db2600b2da5fad9"
    },
    "token": "8da327c71e84d7a2cdc7ce2d49b3d2e8d29868dee1ea028e2db2600b2da5fad9",
    "token_type": "Bearer"
  }
}
```

#### Logout
```bash
POST /api/logout
Accept: application/json
Cookie: laravel_session=<session_cookie>
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Sesión cerrada exitosamente."
}
```

### 2. Gestión de Fincas

#### Listar Fincas
```bash
GET /api/fincas
Accept: application/json
Cookie: laravel_session=<session_cookie>
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Lista de fincas",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id_Finca": 15,
        "id_Propietario": 6,
        "Nombre": "Finca La Nueva Esperanza",
        "Explotacion_Tipo": "Bovinos y Porcinos",
        "propietario": {
          "id": 6,
          "Nombre": "Leonel",
          "Apellido": "Romero",
          "Telefono": "04140659739"
        },
        "terreno": null
      }
    ],
    "total": 3
  }
}
```

### 3. Gestión de Rebaños

#### Listar Rebaños
```bash
GET /api/rebanos
Accept: application/json
Cookie: laravel_session=<session_cookie>
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Lista de rebaños",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id_Rebano": 6,
        "id_Finca": 15,
        "Nombre": "Rebaño Principal",
        "finca": {
          "id_Finca": 15,
          "Nombre": "Finca La Nueva Esperanza",
          "Explotacion_Tipo": "Bovinos"
        },
        "animales": []
      }
    ],
    "total": 2
  }
}
```

### 4. Gestión de Personal

#### Listar Personal por Finca
```bash
GET /api/personal?id_finca=15
Accept: application/json
Cookie: laravel_session=<session_cookie>
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Lista de personal",
  "data": [
    {
      "id": 1,
      "nombre": "Juan Pérez",
      "tipo": "Tecnico",
      "telefono": "3001234567",
      "email": "juan.perez@email.com"
    }
  ]
}
```

## Cambiar entre Mock y API Services

Para habilitar las APIs reales (conectadas al backend), editar el archivo `app/Providers/AppServiceProvider.php`:

```php
// Cambiar de Mock a Api namespace
$this->app->bind(
    \App\Services\Contracts\AuthServiceInterface::class,
    \App\Services\Api\ApiAuthService::class  // En lugar de MockAuthService
);

$this->app->bind(
    \App\Services\Contracts\FincasServiceInterface::class,
    \App\Services\Api\ApiFincasService::class  // En lugar de MockFincasService
);

$this->app->bind(
    \App\Services\Contracts\RebanosServiceInterface::class,
    \App\Services\Api\ApiRebanosService::class  // En lugar de MockRebanosService
);

$this->app->bind(
    \App\Services\Contracts\PersonalServiceInterface::class,
    \App\Services\Api\ApiPersonalService::class  // En lugar de MockPersonalService
);
```

## Notas Importantes

1. **Autenticación**: Los endpoints API utilizan sesiones de Laravel. Después del login, la cookie `laravel_session` debe incluirse en todas las solicitudes subsecuentes.

2. **Mock vs Real API**: Por defecto, el sistema usa servicios Mock para demostración. Para conectar con el backend real, cambiar los bindings en `AppServiceProvider` como se muestra arriba.

3. **Base URL del Backend**: La URL del backend API está configurada en `app/Services/Api/BaseApiService.php`:
   ```php
   protected string $baseUrl = 'http://ec2-54-219-108-54.us-west-1.compute.amazonaws.com:9000/api';
   ```

4. **Filtrado por Finca**: El sistema ahora filtra automáticamente rebaños y personal por la finca seleccionada en la sesión cuando se navega desde el dashboard de una finca específica.
