@extends('layouts.authenticated')

@section('title', 'Editar usuario')

@section('content')
@php
    $st = strtolower($user['status'] ?? 'active');
    $isSuspended = in_array($st, ['suspended', 'inactive', 'suspendido', 'inactivo'], true);
@endphp
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                👤
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar usuario
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualiza la información, rol o estado de acceso</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user['id']) }}" novalidate class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Información de la persona -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📋</span> Información de la persona
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nombre <span class="text-red-500">*</span></label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $user['persona']['nombre'] ?? explode(' ', $user['name'] ?? '')[0]) }}" required 
                                   class="w-full px-4 py-3 border @error('nombre') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="apellido" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Apellido <span class="text-red-500">*</span></label>
                            <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $user['persona']['apellido'] ?? (explode(' ', $user['name'] ?? ' ')[1] ?? '')) }}" required 
                                   class="w-full px-4 py-3 border @error('apellido') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('apellido')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="cedula" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Cédula / documento <span class="text-red-500">*</span></label>
                            <input type="text" id="cedula" name="cedula" value="{{ old('cedula', $user['persona']['cedula'] ?? '') }}" required placeholder="Ej: V12345678"
                                   class="w-full px-4 py-3 border @error('cedula') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all uppercase">
                            @error('cedula')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="telefono" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Teléfono de contacto</label>
                            <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $user['persona']['telefono'] ?? '') }}" placeholder="Ej: 04141234567"
                                   class="w-full px-4 py-3 border @error('telefono') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('telefono')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Datos de acceso -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🔐</span> Datos de acceso
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="correo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Correo electrónico <span class="text-red-500">*</span></label>
                            <input type="email" id="correo" name="correo" value="{{ old('correo', $user['email'] ?? '') }}" required 
                                   class="w-full px-4 py-3 border @error('correo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('correo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nueva contraseña (opcional)</label>
                            <input type="password" id="password" name="password" placeholder="Dejar en blanco para conservar" 
                                   class="w-full px-4 py-3 border @error('password') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('password')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 3: Configuración de Rol -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🛡️</span> Configuración de acceso
                    </h3>
                    <div>
                        @php
                            $currentRoles = [];
                            if (!empty($user['roles'])) {
                                foreach($user['roles'] as $r) {
                                    $currentRoles[] = is_array($r) ? ($r['code'] ?? '') : $r;
                                }
                            }
                        @endphp
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Roles de usuario <span class="text-red-500">*</span></label>
                        <div class="space-y-2 @error('roles') p-2 border border-red-300 bg-red-50/20 rounded-xl @enderror">
                            @foreach($availableRoles as $r)
                                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition-colors bg-white">
                                    <input type="checkbox" name="roles[]" value="{{ $r['code'] }}" class="role-checkbox w-5 h-5 text-ganaderasoft-azul border-gray-300 rounded focus:ring-ganaderasoft-celeste"
                                           {{ (is_array(old('roles')) && in_array($r['code'], old('roles'))) || (!old('roles') && in_array($r['code'], $currentRoles)) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-gray-700">{{ $r['name'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror

                        <!-- Mensaje de advertencia para roles combinados -->
                        <div id="roles-warning" class="hidden mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-xl flex items-start gap-3 transition-all duration-300">
                            <span class="text-yellow-600 text-lg leading-none">⚠️</span>
                            <p class="text-xs text-yellow-800 leading-relaxed font-medium">
                                Has seleccionado rol de <strong>Administrador</strong> Y <strong>Propietario</strong>. 
                                Si el usuario crea fincas, no se le podrá quitar el rol de propietario posteriormente para evitar inconsistencias de datos, solo se le podrá remover el rol de administrador.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen del usuario
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-2xl flex items-center space-x-3">
                            <div id="previewIcono" class="w-12 h-12 rounded-xl bg-white border border-blue-200 text-blue-700 font-bold flex items-center justify-center text-2xl shadow-xs uppercase">
                                U
                            </div>
                            <div>
                                <p id="previewNombre" class="text-base font-bold text-gray-900">Sin nombre</p>
                                <p id="previewCorreo" class="text-xs text-gray-500">Sin@correo.Com</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>Cédula:</span>
                                <span id="previewCedula" class="font-bold text-gray-900">No especificada</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Teléfono:</span>
                                <span id="previewTelefono" class="font-bold text-gray-900">No especificado</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Rol:</span>
                                <span id="previewRol" class="font-bold text-gray-900">No seleccionado</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Contraseña:</span>
                                <span id="previewPassword" class="font-bold text-gray-400">Sin cambios</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Estado:</span>
                                <span class="font-bold {{ $isSuspended ? 'text-red-600' : 'text-emerald-700' }}">
                                    {{ $isSuspended ? '🔴 Suspendido' : '🟢 Activo' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                💾 Actualizar usuario
                            </button>

                            @if($isSuspended)
                                <button type="button"
                                    onclick="openGenericConfirmModal({
                                        formId: 'form-enable-user',
                                        intent: 'success',
                                        title: 'Activar usuario',
                                        message: '¿Estás seguro de que deseas reactivar a {{ $user['name'] ?? 'este usuario' }}? Recuperará el acceso completo al sistema inmediatamente.',
                                        confirmText: 'Sí, activar usuario'
                                    })"
                                    class="w-full py-3 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-600 font-bold rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Activar usuario</span>
                                </button>
                            @else
                                <button type="button"
                                    onclick="openGenericConfirmModal({
                                        formId: 'form-disable-user',
                                        intent: 'danger',
                                        title: 'Suspender usuario',
                                        message: '¿Estás seguro de que deseas suspender a {{ $user['name'] ?? 'este usuario' }}? No podrá iniciar sesión ni acceder al sistema hasta que sea reactivado.',
                                        confirmText: 'Sí, suspender usuario'
                                    })"
                                    class="w-full py-3 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 hover:border-red-600 font-bold rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    <span>Suspender usuario</span>
                                </button>
                            @endif

                            <a href="{{ route('admin.users.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Zona de Peligro (Pie de página horizontal) -->
    <div class="mt-10 pt-8 border-t border-gray-200">
        <div class="bg-white rounded-2xl border border-red-200 shadow-xs p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1 max-w-2xl">
                <h4 class="text-base font-bold text-red-900 flex items-center gap-2">
                    <span>⚠️</span> Zona de peligro
                </h4>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Al eliminar la cuenta de usuario se revocarán sus credenciales de acceso y roles. Si el usuario es propietario de fincas, se eliminarán sus fincas y todos los registros asociados a ellas en cascada (rebaños, animales, fichas laborales). La persona física permanecerá registrada en el sistema. Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="shrink-0">
                <button type="button"
                    onclick="openGenericConfirmModal({
                        formId: 'form-delete-user',
                        intent: 'danger',
                        title: 'Eliminar cuenta de usuario',
                        message: '¿Estás seguro de que deseas eliminar permanentemente la cuenta de usuario de {{ $user['name'] ?? 'este usuario' }}? Si es propietario, se eliminarán todas sus fincas, rebaños y animales en cascada. La persona física permanecerá registrada en el sistema.',
                        confirmText: 'Sí, eliminar usuario'
                    })"
                    class="py-3 px-5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 hover:border-red-600 font-bold rounded-xl transition-all duration-200 text-xs flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Eliminar usuario</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para Activar / Suspender -->
    @if($isSuspended)
        <form id="form-enable-user" action="{{ route('admin.users.enable', $user['id']) }}" method="POST" class="hidden">
            @csrf
            @method('PATCH')
        </form>
    @else
        <form id="form-disable-user" action="{{ route('admin.users.disable', $user['id']) }}" method="POST" class="hidden">
            @csrf
            @method('PATCH')
        </form>
    @endif

    <!-- Formulario oculto para eliminación -->
    <form id="form-delete-user" action="{{ route('admin.users.destroy', $user['id']) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nombreInput       = document.getElementById('nombre');
    const apellidoInput     = document.getElementById('apellido');
    const correoInput       = document.getElementById('correo');
    const cedulaInput       = document.getElementById('cedula');
    const telefonoInput     = document.getElementById('telefono');
    const passwordInput     = document.getElementById('password');
    const roleCheckboxes    = document.querySelectorAll('.role-checkbox');

    const previewNombre   = document.getElementById('previewNombre');
    const previewCorreo   = document.getElementById('previewCorreo');
    const previewCedula   = document.getElementById('previewCedula');
    const previewTelefono = document.getElementById('previewTelefono');
    const previewRol      = document.getElementById('previewRol');
    const previewPassword = document.getElementById('previewPassword');
    const previewIcono    = document.getElementById('previewIcono');
    const warningDiv      = document.getElementById('roles-warning');

    function updatePreview() {
        const nom = nombreInput.value.trim();
        const ape = apellidoInput.value.trim();
        const full = `${nom} ${ape}`.trim();
        
        previewNombre.textContent = full || 'Sin nombre';
        previewIcono.textContent = nom ? nom.charAt(0) : 'U';
        
        previewCorreo.textContent = correoInput.value.trim() || 'sin@correo.com';
        previewCedula.textContent = cedulaInput.value.trim() || 'No especificada';
        previewTelefono.textContent = telefonoInput.value.trim() || 'No especificado';

        const selectedRoles = Array.from(document.querySelectorAll('.role-checkbox:checked')).map(cb => cb.nextElementSibling.textContent.trim());
        previewRol.textContent = selectedRoles.length > 0 ? selectedRoles.join(', ') : 'No seleccionado';

        let hasAdmin = false;
        let hasPropietario = false;
        document.querySelectorAll('.role-checkbox:checked').forEach(cb => {
            if (cb.value === 'admin' || cb.value === 'global_admin') hasAdmin = true;
            if (cb.value === 'propietario') hasPropietario = true;
        });

        if (warningDiv) {
            if (hasAdmin && hasPropietario) warningDiv.classList.remove('hidden');
            else warningDiv.classList.add('hidden');
        }

        if (passwordInput.value.trim().length > 0) {
            previewPassword.textContent = 'Actualizada (Oculta)';
            previewPassword.classList.remove('text-gray-400');
            previewPassword.classList.add('text-emerald-600');
        } else {
            previewPassword.textContent = 'Sin cambios';
            previewPassword.classList.remove('text-emerald-600');
            previewPassword.classList.add('text-gray-400');
        }
    }

    nombreInput.addEventListener('input', updatePreview);
    apellidoInput.addEventListener('input', updatePreview);
    correoInput.addEventListener('input', updatePreview);
    cedulaInput.addEventListener('input', updatePreview);
    telefonoInput.addEventListener('input', updatePreview);
    passwordInput.addEventListener('input', updatePreview);
    roleCheckboxes.forEach(cb => cb.addEventListener('change', updatePreview));

    updatePreview();
});
</script>
@endsection
