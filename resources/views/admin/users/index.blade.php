@extends('layouts.authenticated')

@section('title', 'Gestión de usuarios')

@section('content')
    <div class="space-y-8">
        <!-- Header section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Usuarios</h1>
                <p class="text-gray-500 text-sm mt-1">Administra los usuarios del sistema, sus roles y permisos</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
                + Nuevo usuario
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">✅</span>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">⚠️</span>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Filters Bar -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                    <input type="text" id="filtroNombre" placeholder="Nombre o correo..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rol</label>
                    <select id="filtroRole"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los roles</option>
                        <option value="propietario">Propietario</option>
                        <option value="global_admin">Administrador</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado</label>
                    <select id="filtroStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los estados</option>
                        <option value="active">Activos</option>
                        <option value="suspended">Suspendidos</option>
                    </select>
                </div>
                <div>
                    <a href="{{ route('admin.users.index') }}" onclick="limpiarFiltros(event)"
                       class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px]">
                        Limpiar filtros
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabla de Usuarios -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            @if(count($users) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="tablaUsuarios">
                        <thead class="bg-gray-50">
                            <tr class="flex justify-between items-center w-full">
                                <th class="w-1/5 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                                <th class="w-1/5 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Correo</th>
                                <th class="w-1/5 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Roles</th>
                                <th class="w-1/5 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="w-1/5 px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @foreach($users as $user)
                                @php
                                    $st = strtolower($user['status'] ?? 'active');
                                    $isSuspended = in_array($st, ['suspended', 'inactive', 'suspendido', 'inactivo'], true);
                                    $statusVal = $isSuspended ? 'suspended' : 'active';
                                    
                                    $allRoles = '';
                                    if (!empty($user['roles'])) {
                                        foreach($user['roles'] as $r) {
                                            $rC = is_array($r) ? ($r['code'] ?? '') : $r;
                                            $allRoles .= $rC . ' ';
                                        }
                                    }
                                    
                                    $searchableName = strtolower(($user['name'] ?? '') . ' ' . ($user['email'] ?? ''));
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors fila-usuario flex justify-between items-center w-full"
                                    data-nombre="{{ $searchableName }}"
                                    data-role="{{ $allRoles }}"
                                    data-status="{{ $statusVal }}">
                                    <td class="w-1/5 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 shrink-0 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100">
                                                {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="font-bold text-gray-900 truncate">{{ $user['name'] ?? 'Sin Nombre' }}</p>
                                                <p class="text-xs text-gray-400">ID: #{{ $user['id'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-1/5 px-6 py-4 whitespace-nowrap font-medium text-gray-700 overflow-hidden text-ellipsis">
                                        {{ $user['email'] ?? 'N/A' }}
                                    </td>
                                    <td class="w-1/5 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                        <div class="flex flex-wrap gap-1">
                                            @if(!empty($user['roles']) && count($user['roles']) > 0)
                                                @foreach($user['roles'] as $role)
                                                    @php $roleName = is_array($role) ? ($role['name'] ?? $role['code']) : $role; @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                                        {{ $roleName }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-xs text-gray-400">Sin roles</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="w-1/5 px-6 py-4 whitespace-nowrap">
                                        @if($isSuspended)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                                Suspendido
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Activo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="w-1/5 px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex justify-center space-x-2">
                                            <!-- Botón de Ver Detalles -->
                                            <a href="{{ route('admin.users.show', $user['id']) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                               title="Ver detalle del usuario">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            <!-- Botón de Editar -->
                                            <a href="{{ route('admin.users.edit', $user['id']) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                               title="Editar usuario">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>

                                            <!-- Botón de Toggle Activar / Suspender con Modal Oficial -->
                                            <form action="{{ route('admin.users.toggle-status', $user['id']) }}" method="POST" class="inline-block" id="form-toggle-{{ $user['id'] }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" 
                                                    onclick="openGenericConfirmModal({
                                                        formId: 'form-toggle-{{ $user['id'] }}',
                                                        intent: '{{ $isSuspended ? 'success' : 'danger' }}',
                                                        title: '{{ $isSuspended ? 'Activar usuario' : 'Suspender usuario' }}',
                                                        message: '{{ $isSuspended ? '¿Estás seguro de que deseas reactivar a este usuario? Recuperará el acceso completo al sistema inmediatamente.' : '¿Estás seguro de que deseas suspender a este usuario? No podrá acceder al sistema hasta que sea reactivado.' }}',
                                                        confirmText: '{{ $isSuspended ? 'Sí, activar' : 'Sí, suspender' }}'
                                                    })"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors {{ $isSuspended ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white' : 'bg-red-50 text-red-500 hover:bg-red-500 hover:text-white' }}"
                                                    title="{{ $isSuspended ? 'Activar usuario' : 'Suspender usuario' }}">
                                                    @if($isSuspended)
                                                        <!-- Icono Check para Activar -->
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    @else
                                                        <!-- Icono Bloqueo/Ban para Suspender -->
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Mensaje de Sin Resultados Filtrados -->
                    <div id="sinResultadosFiltro" class="hidden p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-2xl">
                            🔍
                        </div>
                        <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">No se encontraron usuarios</h4>
                        <p class="text-gray-500 text-xs mb-4">No hay usuarios que coincidan con los filtros de búsqueda aplicados.</p>
                        <button type="button" onclick="limpiarFiltros(event)"
                                class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-xs inline-flex items-center gap-1.5 cursor-pointer">
                            Limpiar filtros
                        </button>
                    </div>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay usuarios</h3>
                    <p class="text-gray-500 text-sm mb-6">Prueba cambiando los filtros o agrega un nuevo usuario.</p>
                    <a href="{{ route('admin.users.create') }}"
                       class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        + Nuevo usuario
                    </a>
                </div>
            @endif
        </div>
    </div>

    <x-ui.confirm-modal />
    
    <script>
        document.getElementById('filtroNombre').addEventListener('input', aplicarFiltros);
        document.getElementById('filtroRole').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroStatus').addEventListener('change', aplicarFiltros);

        function aplicarFiltros() {
            const nombre = document.getElementById('filtroNombre').value.toLowerCase().trim();
            const role = document.getElementById('filtroRole').value;
            const status = document.getElementById('filtroStatus').value;
            const tabla = document.getElementById('tablaUsuarios');
            const sinResultados = document.getElementById('sinResultadosFiltro');
            const filas = document.querySelectorAll('.fila-usuario');

            let totalVisibles = 0;

            filas.forEach(function(row) {
                const rowNombre = row.getAttribute('data-nombre') || '';
                const rowRole = row.getAttribute('data-role') || '';
                const rowStatus = row.getAttribute('data-status') || '';

                const matchesNombre = !nombre || rowNombre.includes(nombre);
                const matchesRole = !role || rowRole.includes(role);
                const matchesStatus = !status || rowStatus === status;

                const visible = matchesNombre && matchesRole && matchesStatus;
                if (visible) totalVisibles++;
                row.style.display = visible ? '' : 'none';
            });

            if (sinResultados) {
                if (totalVisibles === 0 && filas.length > 0) {
                    sinResultados.classList.remove('hidden');
                    if (tabla) tabla.classList.add('hidden');
                } else {
                    sinResultados.classList.add('hidden');
                    if (tabla) tabla.classList.remove('hidden');
                }
            }
        }

        window.limpiarFiltros = function(e) {
            if (e) e.preventDefault();
            document.getElementById('filtroNombre').value = '';
            document.getElementById('filtroRole').value = '';
            document.getElementById('filtroStatus').value = '';
            aplicarFiltros();
        };
    </script>
@endsection
