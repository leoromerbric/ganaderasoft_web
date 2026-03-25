# GanaderaSoft - AI Coding Instructions

## Project Overview
GanaderaSoft is a **prototype livestock management system** built with Laravel 10 and PHP 8.1. This is a fully functional prototype that uses **mock services instead of real databases or APIs**, designed for easy transition to production services.

## Architecture Patterns

### Service Layer with Dependency Injection
- **All business logic is abstracted through interfaces** in `app/Services/Contracts/`
- **Mock implementations** are in `app/Services/Mock/` (e.g., `MockAuthService`, `MockDashboardService`)
- **Service binding** happens in `AppServiceProvider::register()` - change bindings here to switch from mock to real services
- Controllers inject interfaces, never concrete classes: `public function __construct(AuthServiceInterface $authService)`

### Mock Authentication System
- **Custom middleware**: `CheckMockAuth` (alias: `mock.auth`) - checks `session('authenticated')`
- **Mock credentials**: `admin@demo.cl` / `Password123!` (hardcoded in `MockAuthService`)
- **Session-based auth**: No Laravel Auth guards, uses manual session management
- **Logout**: Always call `session()->invalidate()` and `session()->regenerateToken()`

### Data Flow (Mock Services)
```
Controller → Service Interface → Mock Service → Hardcoded Data
```
Example: `DashboardController` → `DashboardServiceInterface` → `MockDashboardService::getKPIs()`

## Development Workflows

### Frontend Asset Pipeline
```bash
# Development (watch mode)
npm run dev

# Production build
npm run build
```

### Key Commands
```bash
# Install dependencies
composer install && npm install

# Start development
php artisan serve & npm run dev

# Laravel optimization (when deploying)
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Project-Specific Conventions

### Color System (Tailwind Extended)
- Use **custom color classes**: `bg-ganaderasoft-celeste`, `text-ganaderasoft-verde`
- **Brand colors** defined in `tailwind.config.js`:
  - `celeste`: `#6EC1E4` (primary)
  - `verde`: `#B3D335` (accent)
  - `azul`: `#007B92` (secondary)
  - `negro`: `#333333` (text)

### Localization
- **Spanish (Venezuela)**: All text in Spanish, timezone `America/Caracas`
- **Validation messages**: Always provide Spanish error messages in controllers
- **Currency/Numbers**: Venezuelan format (periods for thousands, commas for decimals)

### File Organization
- **Mock services**: All mock data and logic in `app/Services/Mock/`
- **UI prototypes**: 4 design variations in `pantallas/` directory (separate from Laravel views)
- **Blade templates**: Minimal, use `@vite` for assets, extend `layouts.app`

### Data Patterns
- **Mock data structure**: Services return arrays with specific formats (see `MockDashboardService::getKPIs()`)
- **Chart.js integration**: Mock services provide Chart.js-compatible data formats
- **Icons**: Emoji icons in KPIs (`🐄`, `🏡`, `🥛`, `⚠️`)

## Database Context
- **SQL file**: `database/bd_ganadera_soft.sql` contains the target production schema
- **Tables**: Buffalo inventory, farms (`finca`), livestock tracking - but **NOT USED in prototype**
- **Migration strategy**: Mock services simulate this data structure

## Critical Integration Points

### Switching from Mock to Real Services
1. Create real service classes implementing existing interfaces
2. Update bindings in `AppServiceProvider::register()`
3. Replace `CheckMockAuth` middleware with Laravel's built-in auth
4. Connect to actual database using Eloquent models

### Frontend-Backend Communication
- **No AJAX**: Uses traditional form submissions and server-side rendering
- **CSRF protection**: All forms need `@csrf` directive
- **Flash messages**: Use `session()->with('error', 'message')` pattern

## Common Tasks

### Adding New Mock Data
1. Update mock service methods with new hardcoded arrays
2. Follow existing data structure patterns (consistent array keys)
3. Use Spanish field names and values

### New Feature Development
1. Create interface in `app/Services/Contracts/`
2. Implement mock version in `app/Services/Mock/`
3. Bind in `AppServiceProvider`
4. Inject interface into controller
5. Create Blade views following existing patterns

### UI Customization
- **Reference**: Check `pantallas/` for approved design patterns
- **Tailwind**: Use extended color system, Nunito font
- **Components**: Keep minimal, use Blade partials for reusable elements