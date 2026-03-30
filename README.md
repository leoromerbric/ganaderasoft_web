# GanaderaSoft - Sistema de Gestión Ganadera

## Descripción

GanaderaSoft es un sistema de gestión ganadera desarrollado en Laravel 10 con PHP 8.1. Funciona como un gateway API que se conecta a servicios externos para el manejo integral de datos ganaderos incluyendo animales, fincas, rebaños, lactancia, producción lechera y más.

## Tecnologías

- **Backend**: Laravel 10, PHP 8.1
- **Frontend**: Blade Templates, Tailwind CSS, JavaScript
- **Arquitectura**: Service Layer Pattern con Dependency Injection
- **Autenticación**: Sistema personalizado basado en sesiones

## Estructura de Directorios

### `/app`
**Propósito**: Lógica principal de la aplicación Laravel

- **`/Console`**: Comandos de consola Artisan
- **`/Exceptions`**: Manejo de excepciones personalizadas
- **`/Http`**: Controladores, middleware y requests HTTP
  - **`/Controllers`**: Controladores de la aplicación (Dashboard, Animales, Lactancia, Leche, etc.)
  - **`/Middleware`**: Middleware personalizado (`CheckMockAuth` para autenticación)
- **`/Providers`**: Service Providers de Laravel (bindings, configuraciones)
- **`/Services`**: Capa de servicios con patrón de arquitectura limpia
  - **`/Api`**: Implementaciones que se conectan a APIs externas
  - **`/Contracts`**: Interfaces que definen contratos de servicios
  - **`/Mock`**: Implementaciones mock para desarrollo y testing

### `/bootstrap`
**Propósito**: Archivos de arranque de Laravel
- Configuración inicial del framework

### `/config`
**Propósito**: Archivos de configuración de Laravel
- Configuraciones de base de datos, servicios, autenticación, etc.

### `/database`
**Propósito**: Esquema y migraciones de base de datos
- `bd_ganadera_soft.sql`: Esquema SQL de referencia para producción

### `/docs`
**Propósito**: Documentación técnica del proyecto
- **`/apis_docs`**: Documentación detallada de endpoints de APIs externas (41 archivos)

### `/public`
**Propósito**: Archivos públicos accesibles por el navegador
- Punto de entrada principal (`index.php`)
- Assets estáticos (imágenes, CSS, JS compilados)

### `/resources`
**Propósito**: Recursos de frontend no compilados
- **`/css`**: Stylesheets CSS fuente
- **`/js`**: JavaScript fuente
- **`/views`**: Templates Blade organizados por módulo (dashboard, animales, lactancia, etc.)

### `/routes`
**Propósito**: Definición de rutas de la aplicación
- `web.php`: Rutas web principales
- `api.php`: Rutas de API

### `/scripts`
**Propósito**: Scripts de utilidades y despliegue
- `ganaderasoft-update.sh`: Script principal de actualización
- `setup-gs-command.sh`: Configuración de comandos de sistema

### `/storage`
**Propósito**: Almacenamiento de archivos temporales y logs
- Logs de aplicación, cache, sesiones, uploads

### `/tests`
**Propósito**: Tests automatizados
- Configuración base para PHPUnit

## Arquitectura de Servicios

El proyecto utiliza **Dependency Injection** y **Service Layer Pattern**:

1. **Interfaces** (`/Services/Contracts`): Definen contratos para cada servicio
2. **Implementaciones API** (`/Services/Api`): Se conectan a APIs externas reales
3. **Implementaciones Mock** (`/Services/Mock`): Datos simulados para desarrollo
4. **Binding**: En `AppServiceProvider` se configuran qué implementaciones usar

## Módulos Principales

### Gestión de Animales
- CRUD completo de animales
- Seguimiento de etapas de vida
- Estados de salud
- Cambios y transiciones

### Gestión de Fincas y Rebaños
- Administración de propiedades ganaderas
- Organización por rebaños
- Gestión de personal

### Producción Lechera
- Registros de lactancia
- Control de producción diaria
- Análisis de rendimiento

### Dashboard y Reportes
- KPIs ganaderos
- Visualización de datos
- Métricas principales

## Comandos de Desarrollo

```bash
# Instalar dependencias
composer install && npm install

# Desarrollo con watch
npm run dev

# Servir aplicación
php artisan serve

# Construcción para producción
npm run build
```

## Configuración

1. Copiar `.env.example` a `.env`
2. Configurar variables de entorno (URLs de APIs, credenciales)
3. Instalar dependencias
4. Iniciar servidor de desarrollo

## Notas Técnicas

- **Autenticación**: Sistema personalizado con middleware `CheckMockAuth`
- **API Gateway**: No usa base de datos local, se conecta a servicios externos
- **Frontend**: Server-side rendering con Blade, CSS con Tailwind
- **Servicios**: Intercambiables entre Mock y Api según configuración en `AppServiceProvider`