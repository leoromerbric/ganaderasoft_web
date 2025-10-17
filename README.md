# GanaderaSoft - Sistema de Gestión de Ganadería

Prototipo web desarrollado con Laravel 10 y PHP 8.1 para la gestión de ganadería.

## 🚀 Características del Prototipo

Este es un **prototipo funcional** con las siguientes pantallas:
- **Login**: Autenticación de usuarios con credenciales simuladas
- **Dashboard**: Panel principal con KPIs, gráficos y alertas

### ⚠️ Importante - Prototipo
- **No utiliza base de datos** - Todos los datos son simulados (mocks)
- **No conecta con APIs reales** - Servicios mock con datos de prueba
- **Arquitectura preparada** para reemplazar fácilmente los mocks por servicios reales

## 🎨 Stack Tecnológico

- **Backend**: Laravel 10, PHP 8.1
- **Frontend**: Blade Templates, Tailwind CSS 3, Vite
- **Gráficos**: Chart.js
- **Idioma**: Español (es-VE)
- **Timezone**: America/Caracas

## 🎨 Identidad Visual GanaderaSoft

El sistema utiliza la paleta de colores corporativa:
- **Celeste principal**: `#6EC1E4`
- **Verde lima**: `#B3D335`
- **Azul profundo**: `#007B92`
- **Negro suave**: `#333333`
- **Blanco puro**: `#FFFFFF`

Tipografía: Nunito (sans-serif limpia y moderna)

## 📦 Instalación

### Requisitos Previos
- PHP 8.1+
- Composer
- Node.js y npm

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/leoromerbric/ganaderasoft_web.git
cd ganaderasoft_web
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Configurar el entorno**
```bash
cp .env.example .env  # Si no existe .env
php artisan key:generate
```

4. **Instalar dependencias de Node.js**
```bash
npm install
```

5. **Compilar assets**
```bash
npm run build
```

Para desarrollo con hot-reload:
```bash
npm run dev
```

6. **Iniciar el servidor**
```bash
php artisan serve
```

Acceder a: http://localhost:8000

## 🔐 Credenciales de Acceso (Demo)

- **Email**: `admin@demo.cl`
- **Contraseña**: `Password123!`

## 📱 Pantallas

### 1. Login (`/login`)
- Formulario de autenticación
- Validaciones server-side
- Credenciales de demostración visibles
- Diseño responsive con branding GanaderaSoft

### 2. Dashboard (`/dashboard`)
Pantalla principal protegida que incluye:

#### KPIs (Tarjetas de métricas)
- Total de animales: 1,247
- Total de fincas: 18
- Producción diaria: 4,582 L
- Alertas activas: 7

#### Gráfico de Producción
- Chart.js mostrando producción de leche de la última semana
- Datos simulados con tendencias realistas

#### Alertas Recientes
- Panel lateral con últimas alertas
- Niveles: Alta (rojo), Media (amarillo), Baja (azul)

#### Tabla de Alertas
- Listado completo de alertas con fecha, nivel y mensaje
- Diseño responsive

## 🏗️ Arquitectura

### Estructura de Servicios

El sistema está diseñado con **interfaces y servicios mock** para facilitar la migración futura a APIs reales:

```
app/
├── Services/
│   ├── Contracts/
│   │   ├── AuthServiceInterface.php
│   │   └── DashboardServiceInterface.php
│   └── Mock/
│       ├── MockAuthService.php
│       └── MockDashboardService.php
```

### Reemplazo de Mocks por APIs Reales

Para conectar con APIs reales, simplemente:

1. Crear nuevas implementaciones de las interfaces en `app/Services/Api/`
2. Actualizar el binding en `app/Providers/AppServiceProvider.php`

```php
// En AppServiceProvider::register()
$this->app->bind(
    \App\Services\Contracts\AuthServiceInterface::class,
    \App\Services\Api\RealAuthService::class  // Reemplazar MockAuthService
);
```

### Middleware de Autenticación

- `CheckMockAuth`: Middleware personalizado para proteger rutas
- Utiliza sesiones de Laravel
- Fácilmente reemplazable por Laravel Sanctum o Passport

## 🛠️ Desarrollo

### Linting
```bash
./vendor/bin/pint  # Formato PSR-12
```

### Testing
```bash
php artisan test
```

### Compilar assets para producción
```bash
npm run build
```

## 📄 Licencia

MIT

## 👤 Autor

Sistema desarrollado para GanaderaSoft

