# GanaderaSoft - Sistema de gestión ganadera

GanaderaSoft es un sistema de gestión ganadera desarrollado en Laravel 10 con PHP 8.1. Funciona como un gateway API que se conecta a servicios externos para el manejo integral de datos ganaderos incluyendo animales, fincas, rebaños, lactancia, producción lechera y más.

## 1. ⚒️ Stack tecnológico

| Componente | Tecnología | Versión | Propósito |
| :--- | :--- | :--- | :--- |
| **Frontend framework** | Laravel | 10.x | Lógica de presentación y rutas. |
| **Lenguaje servidor** | PHP | 8.1+ | Motor de ejecución del frontend. |
| **Gestor del servidor** | Composer | 2.x | Gestor de dependencias para PHP. |
| **Frontend UI** | Blade | - | Motor de plantillas de Laravel. |
| **Estilos UI** | Tailwind CSS | 3.4 | Framework CSS de utilidades. |
| **Scripting UI** | JavaScript | - | Interactividad del cliente. |
| **Bundler** | Vite | 5.0 | Compilador de assets. |
| **Servidor web** | Nginx | 1.18+ | Servidor web y proxy inverso (Producción). |
| **Entorno local** | Docker | - | Virtualización de servicios. |
| **CI/CD** | GitHub Actions | - | Automatización del despliegue a VPS. |

## 2. 📂 Infraestructura y arquitectura

### 2.1 Arquitectura de software
El proyecto del frontend sigue una arquitectura orientada a la presentación, conectándose a servicios externos de API:

```text
/frontend
├── app/                    # Lógica del servidor frontend
│   ├── Console/            # Comandos de consola
│   ├── Exceptions/         # Manejo de excepciones
│   ├── Http/               # Controladores y middleware
│   │   ├── Controllers/    # Controladores web (Dashboard, Animales, etc.)
│   │   └── Middleware/     # Filtros de peticiones HTTP
│   ├── Providers/          # Proveedores de servicios
│   └── Services/           # Servicios y conexión a la API externa
│       ├── Api/            # Consumo de API REST real
│       ├── Contracts/      # Interfaces de servicios
│       └── Mock/           # Datos simulados para testing
├── bootstrap/              # Arranque del framework
├── config/                 # Configuraciones
├── database/               # Migraciones locales (si aplica)
├── docs/                   # Documentación de endpoints externos
├── public/                 # Archivos públicos y assets compilados
├── resources/              # Código fuente del frontend
│   ├── css/                # Estilos base de Tailwind
│   ├── js/                 # Código fuente JavaScript
│   └── views/              # Plantillas Blade por módulos
├── routes/                 # Rutas de la aplicación (web.php)
├── scripts/                # Scripts utilitarios
├── storage/                # Almacenamiento temporal y logs
└── tests/                  # Pruebas automatizadas
```

### 2.2 Infraestructura de servidores

El proyecto maneja dos entornos con comportamientos diferentes:

1. Entorno local (Dockerizado para Desarrollo)
   - Orquestación: Usa `docker-compose.yml` montando volúmenes en tiempo real.
   - Servidor web: Servidor local de Artisan o Nginx interno.
   - Base de datos: Contenedor **MySQL**.
2. Entorno producción (Dockerizado Optimizado)
   - Orquestación: Usa `docker-compose.prod.yml` con imágenes compiladas independientemente.
   - Proxy externo: **Nginx** nativo en el servidor VPS (Reverse Proxy hacia Docker).
   - Procesador PHP: **PHP-FPM 8.2** ejecutándose internamente en el contenedor.
   - Base de datos: Servidor **MySQL** externo a la red de los contenedores web.

## Arquitectura de servicios

El proyecto utiliza **Dependency Injection** y **Service Layer Pattern**:

1. **Interfaces** (`/Services/Contracts`): Definen contratos para cada servicio
2. **Implementaciones API** (`/Services/Api`): Se conectan a APIs externas reales
3. **Implementaciones Mock** (`/Services/Mock`): Datos simulados para desarrollo
4. **Binding**: En `AppServiceProvider` se configuran qué implementaciones usar

## Módulos principales

### Gestión de animales
- CRUD completo de animales
- Seguimiento de etapas de vida
- Estados de salud
- Cambios y transiciones

### Gestión de fincas y rebaños
- Administración de propiedades ganaderas
- Organización por rebaños
- Gestión de personal

### Producción lechera
- Registros de lactancia
- Control de producción diaria
- Análisis de rendimiento

### Dashboard y reportes
- KPIs ganaderos
- Visualización de datos
- Métricas principales

## 🚀 Pasos para desarrollo local

> [!IMPORTANT]
> Para el entorno de desarrollo local, es indispensable el uso de **Docker Compose** para la orquestación de los servicios.
> En caso de usar Windows, para garantizar la compatibilidad de los volúmenes y el rendimiento de los contenedores, es obligatorio ejecutar este proyecto utilizando **WSL2** (Windows Subsystem for Linux) integrado con **Docker Desktop**. Evite ejecutar los comandos directamente sobre PowerShell o CMD si no es a través de una terminal de WSL.

### 1. Estructura de archivos
Para comenzar, debe configurar la siguiente estructura de directorios en su entorno local dentro de una carpeta raíz (por ejemplo, `GanaderasoftPro/`):

```text
GanaderasoftPro/
├── backend/                  # Repositorio del API (Laravel)
├── frontend/                 # Repositorio de la interfaz (Laravel + Vue/Blade)
├── docker-compose.yml        # Orquestador de servicios (Desarrollo)
└── docker-compose.prod.yml   # Orquestador de servicios (Producción)
```
> [!NOTE]
> Tanto el código del `backend` como del `frontend` corresponden a sub-proyectos. Debe clonarlos o mantenerlos dentro de la carpeta principal para que el orquestador pueda localizar los archivos de configuración y los Dockerfiles.

### 2. Configuración de variables de entorno

Debe solicitar al equipo de desarrollo los archivos `.env` correspondientes al entorno de desarrollo.
- El archivo `.env` del backend debe colocarse en `GanaderasoftPro/backend/.env`.
- El archivo `.env` del frontend debe colocarse en `GanaderasoftPro/frontend/.env`.

Alternativamente, puede copiar los archivos de ejemplo si están disponibles (`cp .env.dev .env`).

### 3. Orquestación con docker

En la raíz de la carpeta `GanaderasoftPro/`, asegúrese de tener (o crear) un archivo llamado `docker-compose.yml` preconfigurado que orquestará los servicios de desarrollo:

<details>
<summary><b>Ver contenido de <code>docker-compose.yml</code> (Desarrollo)</b></summary>

```yaml
services:
  # Base de datos compartida (usada principalmente en desarrollo)
  db:
    image: mysql:8.0
    container_name: ganaderasoft-db
    restart: unless-stopped
    env_file:
      - ./backend/.env
    ports:
      - "3306:3306"
    volumes:
      # Persistencia de datos de MySQL
      - db_data:/var/lib/mysql
    healthcheck:
      # Verifica que MySQL esté listo antes de arrancar el backend
      test: [ "CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-proot_password" ]
      interval: 10s
      timeout: 5s
      retries: 5
      start_period: 30s
    networks:
      - ganaderasoft-network

  # Backend (API Laravel)
  ganaderasoft-backend:
    build:
      context: ./backend
      dockerfile: Dockerfile
    container_name: ganaderasoft-backend
    restart: unless-stopped
    ports:
      - "8001:80"
    env_file:
      - ./backend/.env
    depends_on:
      db:
        condition: service_healthy
    networks:
      - ganaderasoft-network
    volumes:
      # Monta el código local para desarrollo en tiempo real
      - ./backend:/var/www/html
      # Ignora la carpeta local vendor (se instala dentro del contenedor en el entrypoint)
      - /var/www/html/vendor

  # Frontend (Laravel + Vue/Blade con Vite)
  ganaderasoft-frontend:
    build:
      context: ./frontend
      dockerfile: Dockerfile
    container_name: ganaderasoft-frontend
    restart: unless-stopped
    ports:
      - "8000:80"
      - "5173:5173" # Puerto para Vite (HMR)
    env_file:
      - ./frontend/.env
    depends_on:
      - ganaderasoft-backend
    networks:
      - ganaderasoft-network
    volumes:
      # Monta el código local para desarrollo en tiempo real
      - ./frontend:/var/www/html
      # Ignora las carpetas locales para evitar conflictos de sistema operativo
      - /var/www/html/vendor
      - /var/www/html/node_modules

volumes:
  db_data:

networks:
  ganaderasoft-network:
    driver: bridge
```
</details>

Y de igual forma, para producción, asegúrese de tener el archivo `docker-compose.prod.yml`:

<details>
<summary><b>Ver contenido de <code>docker-compose.prod.yml</code> (Producción)</b></summary>

```yaml
services:
  # Backend de Producción
  ganaderasoft-backend:
    build:
      context: ./backend
      # Usa el Dockerfile optimizado sin dependencias de desarrollo
      dockerfile: Dockerfile.prod
    container_name: ganaderasoft-backend-prod
    restart: always
    ports:
      - "127.0.0.1:8001:80"
    extra_hosts:
      - "host.docker.internal:host-gateway"
    env_file:
      - ./backend/.env
    networks:
      - ganaderasoft-network

  # Frontend de Producción
  ganaderasoft-frontend:
    build:
      context: ./frontend
      # Usa el Dockerfile que compila Vite internamente
      dockerfile: Dockerfile.prod
    container_name: ganaderasoft-frontend-prod
    restart: always
    ports:
      - "127.0.0.1:8000:80"
    env_file:
      - ./frontend/.env
    depends_on:
      - ganaderasoft-backend
    networks:
      - ganaderasoft-network

networks:
  # Usa la misma red para que puedan conectarse a la BD de desarrollo si es necesario
  ganaderasoft-network:
    driver: bridge
```
</details>

### 4. Configuración de la base de datos
Para el entorno de desarrollo, el contenedor `ganaderasoft-db` de MySQL se encargará de proveer la base de datos con las siguientes credenciales configuradas por defecto:

| Credencial | Valor |
| :--- | :--- |
| **Servidor / Host** | ganaderasoft-db |
| **Puerto** | 3306 |
| **Base de Datos** | ganaderasoft |
| **Usuario** | ganaderasoft_user |
| **Contraseña** | ganaderasoft_pass |

> [!IMPORTANT]
> **Importación de datos y migraciones**: 
> Si es la primera vez que levanta el proyecto o si la base de datos está vacía, es indispensable ingresar al contenedor del backend y ejecutar las migraciones junto con los seeders (datos semilla) iniciales:
> ```bash
> docker compose exec ganaderasoft-backend bash
> php artisan migrate --seed
> ```
> *(Alternativamente, si cuenta con un archivo SQL de respaldo como `bd_ganadera_soft.sql`, puede restaurarlo directamente en el gestor de base de datos de su preferencia utilizando las credenciales provistas).*

### 5. Ejecución del entorno con docker compose

Una vez configurada la estructura de archivos y las variables de entorno, inicie la orquestación de los contenedores ejecutando el siguiente comando en la raíz del proyecto (`/GanaderasoftPro`):

```bash
docker compose up --build
```
> [!TIP]
> Use el flag `--build` la primera vez o cuando realice cambios en los archivos `Dockerfile` o `entrypoint.sh` para asegurar que las imágenes se actualicen correctamente. Si desea ejecutar los contenedores en segundo plano y dejar la terminal libre, añada el flag `-d`.

### 6. Ejecución del entorno de producción (opcional)
Si desea probar cómo se comportará la aplicación en el servidor real (sin mapeo de volúmenes locales y con assets compilados), utilice el orquestador de producción.

> [!WARNING]
> **Requisito de base de datos**: 
> El archivo `docker-compose.prod.yml` **no incluye un contenedor de base de datos** por defecto. Por lo tanto, antes de levantarlo debe asegurarse de que la base de datos de desarrollo esté corriendo (`docker compose up -d db`), o bien, haber configurado las credenciales de un servidor MySQL externo en los archivos `.env`.

> [!IMPORTANT]
> **Variables de entorno para producción**: 
> Antes de construir las imágenes de producción, abra los archivos `.env` del Frontend y del Backend y asegúrese de configurar:
> - `APP_ENV=production`
> - `APP_DEBUG=false`
> 
> De lo contrario, Laravel seguirá intentando comportarse como si estuviera en desarrollo.

```bash
docker compose -f docker-compose.prod.yml up -d --build
```

## Notas técnicas

- **Autenticación**: Sistema personalizado con middleware `CheckMockAuth`
- **API Gateway**: No usa base de datos local, se conecta a servicios externos
- **Frontend**: Server-side rendering con Blade, CSS con Tailwind
- **Servicios**: Intercambiables entre Mock y Api según configuración en `AppServiceProvider`