# Analisis para soporte de Administrador Global, Propietario y Operativo

## 1. Objetivo
Definir los cambios necesarios en modelo de datos, API y Web para soportar:

- Administrador Global: crea cuentas de propietarios y supervisa el sistema completo.
- Propietario: acceso total sobre sus fincas y su operacion.
- Operativo: acceso limitado por permisos y por finca.
- Multi-finca para operativos: un mismo personal (ej. veterinario) puede trabajar en varias fincas de propietarios distintos.

## 2. Fuentes analizadas

### 2.1 Codigo API
- app/Models/User.php
- app/Models/Propietario.php
- app/Models/Finca.php
- app/Models/PersonalFinca.php
- app/Http/Controllers/Api/AuthController.php
- app/Http/Controllers/Api/FincaController.php
- app/Http/Controllers/Api/PropietarioController.php
- app/Http/Controllers/Api/PersonalFincaController.php
- routes/api.php

### 2.2 Codigo Web
- app/Http/Controllers/AuthController.php
- app/Services/Api/ApiAuthService.php
- app/Http/Middleware/CheckMockAuth.php
- app/Http/Controllers/FincasController.php
- resources/views/layouts/authenticated.blade.php
- routes/web.php

### 2.3 Modelo de datos (conexion DB de VS Code)
Se confirmo la base ganaderasoft con tablas clave presentes: users, propietario, finca, personal_finca, personal_access_tokens y 51 tablas en total.

### 2.4 SQL de referencia
- database/bd_ganadera_soft.sql

## 3. Estado actual detectado

### 3.1 Identidad y perfiles
- users contiene campo type_user (actualmente orientado a admin, propietario, tecnico).
- No existe un perfil Operativo formal con permisos por modulo/accion.
- AuthController API autentica por email/password y emite token Sanctum sin contexto de finca.

### 3.2 Relacion propietario-finca
- finca.id_Propietario referencia propietario.id.
- propietario.id referencia users.id (1:1).
- Es un buen inicio para tenancy por propietario.

### 3.3 Personal de finca
- personal_finca mezcla datos de persona + asignacion a finca en una sola tabla.
- personal_finca tiene una sola FK id_Finca por fila, por lo que la multi-finca real requiere duplicar persona (inconsistencia potencial).
- No hay FK directa de personal_finca a users, por lo que el personal no tiene identidad de login propia en el modelo actual.

### 3.4 Autorizacion en API
- El patron dominante es if isAdmin else if isPropietario.
- No existe rama isOperativo ni verificacion granular por permisos.
- Los controladores filtran por propietario, pero no por membresia operativa en finca.

### 3.5 Autorizacion en Web
- Middleware CheckMockAuth solo valida sesion autenticada (no perfil/permisos).
- Menu lateral muestra todos los modulos sin recorte por rol.
- selected_finca se maneja en sesion, pero sin una capa robusta de autorizacion por contexto para todos los casos.

## 4. Brechas frente al requerimiento

1. Falta Administrador Global explicito y casos de uso para alta de propietarios.
2. Falta perfil Operativo autenticable.
3. Falta modelo multi-finca para el mismo personal sin duplicidad de identidad.
4. Falta control de acceso por vista/funcionalidad para Operativo.
5. Falta seleccion y validacion consistente de finca activa por usuario.

## 5. Propuesta objetivo (target)

## 5.1 Modelo de datos propuesto

### A. Identidad de usuario
Mantener users y ajustar perfilado:

- users.type_user: migrar a valores canonicos:
  - global_admin
  - propietario
  - operativo

Recomendado (escalable):
- tabla roles (id, code, name)
- tabla user_roles (user_id, role_id)

Si se quiere ir rapido en Fase 1, se puede mantener type_user y migrar a RBAC despues.

### B. Desacoplar persona de asignacion a finca
Agregar:

1) persona
- id_persona (PK)
- cedula (unique)
- nombre
- apellido
- telefono
- correo (unique)
- estado
- created_at, updated_at

2) finca_personal (membresia)
- id (PK)
- id_finca (FK a finca)
- id_persona (FK a persona)
- tipo_trabajador (veterinario, tecnico, operario, etc.)
- perfil_acceso (operativo)
- estado (activo/inactivo)
- fecha_ingreso
- unique(id_finca, id_persona)

3) user_persona
- user_id (FK a users)
- id_persona (FK a persona)
- unique(user_id)

Con esto, una persona puede pertenecer a muchas fincas sin duplicarse.

### C. Acceso a fincas por usuario
Agregar tabla user_finca_access:
- id
- user_id
- id_finca
- access_level (owner, operator)
- is_default
- estado
- unique(user_id, id_finca)

Reglas:
- Propietario: sus fincas por relacion finca.id_Propietario.
- Operativo: sus fincas por user_finca_access + finca_personal.

### D. Permisos por funcionalidad
Agregar:
- permissions (code, module, action)
- role_permissions (role_id, permission_id)

Permisos recomendados por defecto:
- Propietario: full sobre sus fincas.
- Operativo: lectura/escritura acotada por modulo (ej. reproduccion si aplica, pero sin gestion de usuarios/propietarios).

## 5.2 Cambios requeridos en API

### A. Auth y contexto
1) Extender login para devolver:
- roles
- fincas disponibles
- finca_activa (si existe)
- permisos efectivos

2) Endpoints nuevos:
- POST /auth/switch-finca
- GET /auth/me-context

3) Middleware nuevo de autorizacion:
- EnsureRole (global_admin, propietario, operativo)
- EnsurePermission (module.action)
- EnsureFincaAccess (valida que el usuario puede operar la finca activa)

### B. Administrador Global
Nuevos casos de uso:
- Crear usuario propietario + registro propietario en una transaccion.
- Listar/editar/desactivar propietarios.

Endpoints sugeridos:
- POST /admin/owners
- GET /admin/owners
- PUT /admin/owners/{id}
- POST /admin/owners/{id}/disable

### C. Operativo y personal
- Crear operativo desde propietario:
  - crear usuario operativo
  - vincular con persona
  - asignar una o mas fincas
  - asignar permisos o plantilla de permisos

Endpoints sugeridos:
- POST /owners/{ownerId}/operativos
- POST /operativos/{userId}/fincas
- DELETE /operativos/{userId}/fincas/{idFinca}
- GET /operativos/{userId}/fincas

### D. Refactor de controladores existentes
Actualmente hay checks manuales repetidos de isAdmin/isPropietario.
Se recomienda mover a policies/middleware centralizados para evitar divergencias.

Prioridad de refactor:
1) FincaController, PersonalFincaController, AnimalController
2) Modulo reproductivo y sanitario
3) Resto de CRUDs

## 5.3 Cambios requeridos en Web

### A. Sesion y contexto
Guardar en sesion:
- user
- roles
- permissions
- fincas_disponibles
- finca_activa

Agregar selector de finca activa (header/sidebar) para usuarios con multiples fincas.

### B. Navegacion por perfil
- Filtrar menu por permisos.
- Propietario: menu completo de su operacion.
- Operativo: menu reducido (solo modulos habilitados).

### C. Flujos nuevos
1) Pantallas Admin Global:
- gestion de propietarios
- alta/edicion/desactivacion

2) Pantallas Propietario:
- alta de operativos
- asignacion de operativos a fincas
- matriz simple de permisos operativos

3) Pantallas Operativo:
- dashboard y CRUDs permitidos, siempre acotados a finca activa.

### D. Seguridad de UI
No depender solo de ocultar menu:
- Toda accion debe validarse en API por rol + permiso + acceso a finca.

## 6. Plan de migracion recomendado

## Fase 0 - Preparacion
- Respaldos completos.
- Inventario de usuarios y limpieza de correos duplicados.

## Fase 1 - Esquema minimo viable
- Crear persona, finca_personal, user_persona, user_finca_access.
- Poblar desde personal_finca (deduplicando por cedula/correo).
- Mantener compatibilidad temporal con personal_finca para no romper modulos.

## Fase 2 - Auth y autorizacion
- Incorporar roles/permisos y middleware.
- Exponer switch de finca activa.
- Actualizar login API + sesion web.

## Fase 3 - Flujos funcionales
- Admin Global: gestionar propietarios.
- Propietario: gestionar operativos y asignaciones.
- Operativo: acceso limitado por permisos.

## Fase 4 - Endurecimiento
- Refactor de todos los controladores a policy/middleware.
- Pruebas de seguridad multitenant (aislamiento por finca/propietario).
- Retiro gradual de personal_finca heredado (o redefinirlo como vista/materializada).

## 7. Reglas de negocio clave a implementar

1. Un global_admin puede crear propietarios.
2. Un propietario solo administra su(s) finca(s).
3. Un operativo solo opera en fincas asignadas.
4. La finca activa debe ser valida para el usuario en cada request.
5. Un veterinario puede estar asignado a varias fincas y propietarios sin duplicar identidad.

## 8. Riesgos actuales y mitigacion

### Riesgo 1: Duplicidad de personal
Causa: personal_finca mezcla identidad y membresia.
Mitigacion: separar persona y asignacion.

### Riesgo 2: Fugas de datos entre fincas
Causa: checks de permisos dispersos en controladores.
Mitigacion: middleware/policies centralizados + tests de aislamiento.

### Riesgo 3: Deriva de esquema
Causa: diferencias entre migraciones y SQL real.
Mitigacion: consolidar migraciones, ejecutar auditoria de drift y documentar baseline.

## 9. Entregables sugeridos para implementacion

1) ADR de multitenencia y RBAC.
2) Script SQL de migracion de datos (persona/finca_personal/user_finca_access).
3) Endpoints nuevos de contexto y gestion de operativos.
4) Middleware de autorizacion por rol, permiso y finca.
5) Ajuste de menu y guardas de rutas web.
6) Suite de pruebas de autorizacion (API + web).

## 10. Conclusion
El sistema ya tiene una base util (users, propietario, finca, tokens y filtros por propietario), pero para cumplir el objetivo solicitado necesita evolucionar a un modelo de identidad + membresia multi-finca + autorizacion por permisos. El cambio principal es separar identidad de personal de su pertenencia a fincas y formalizar el contexto de finca activa en toda la plataforma.
