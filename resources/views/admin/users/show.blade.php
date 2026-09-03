@extends('layouts.authenticated')

@section('title', 'Detalle de usuario')

@section('content')
<div class="space-y-8">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            @php
                $st = strtolower($user['status'] ?? 'active');
                $isSuspended = in_array($st, ['suspended', 'inactive', 'suspendido', 'inactivo'], true);
                $nom = $user['persona']['nombre'] ?? explode(' ', $user['name'] ?? '')[0] ?? 'U';
                $inicial = strtoupper(substr($nom, 0, 1));
            @endphp
            <div class="w-12 h-12 rounded-2xl {{ $isSuspended ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }} flex items-center justify-center font-bold text-2xl shadow-sm border {{ $isSuspended ? 'border-red-100' : 'border-blue-100' }}">
                {{ $inicial }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    {{ $user['persona']['nombre'] ?? explode(' ', $user['name'] ?? '')[0] }} {{ $user['persona']['apellido'] ?? (explode(' ', $user['name'] ?? ' ')[1] ?? '') }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Correo: <span class="font-medium text-gray-800">{{ $user['email'] ?? 'Sin correo' }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.users.edit', $user['id']) }}" 
               class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar usuario
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información de la Persona -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>📋</span> Información de la persona
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre</p>
                        <p class="text-lg font-bold text-gray-900">{{ $user['persona']['nombre'] ?? explode(' ', $user['name'] ?? '')[0] }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Apellido</p>
                        <p class="text-lg font-bold text-gray-900">{{ $user['persona']['apellido'] ?? (explode(' ', $user['name'] ?? ' ')[1] ?? 'N/A') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cédula / documento</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">{{ $user['persona']['cedula'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Teléfono</p>
                        <p class="text-lg font-bold text-gray-900">{{ $user['persona']['telefono'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Roles y Permisos -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>🛡️</span> Roles y permisos de acceso
                </h3>
                
                @if(!empty($user['roles']))
                    <div class="space-y-3">
                        @foreach($user['roles'] as $r)
                            @php
                                $rCode = is_array($r) ? ($r['code'] ?? '') : $r;
                                $rawRName = is_array($r) ? ($r['name'] ?? ucfirst($rCode)) : ucfirst($rCode);
                                $rName = match(strtolower((string)$rCode)) {
                                    'global_admin', 'admin', 'global admin' => 'Administrador',
                                    'propietario' => 'Propietario de finca',
                                    default => match(strtolower((string)$rawRName)) {
                                        'global_admin', 'global admin', 'admin', 'administrador' => 'Administrador',
                                        default => $rawRName
                                    }
                                };
                                $rPerms = is_array($r) && isset($r['permissions']) ? $r['permissions'] : [];
                                
                                $groupedPerms = [];
                                foreach($rPerms as $perm) {
                                    $parts = explode('.', $perm);
                                    if(count($parts) === 2) {
                                        $group = ucfirst(str_replace('_', ' ', $parts[0]));
                                        $groupedPerms[$group][] = $parts[1];
                                    } else {
                                        $groupedPerms['Otros'][] = $perm;
                                    }
                                }
                                ksort($groupedPerms);
                            @endphp
                            
                            <details class="group bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden [&_summary::-webkit-details-marker]:hidden">
                                <summary class="flex flex-col sm:flex-row sm:items-center justify-between cursor-pointer p-5 select-none hover:bg-slate-50 transition-colors gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-50 to-blue-50 text-indigo-600 flex items-center justify-center shadow-inner border border-indigo-100/50">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-lg">{{ $rName }}</h4>
                                            <p class="text-sm text-gray-500 font-medium mt-0.5">Rol de sistema asignado</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                            {{ count($rPerms) }} permisos asignados
                                        </span>
                                        <div class="hidden sm:flex w-10 h-10 rounded-full items-center justify-center bg-white border border-gray-200 text-gray-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-100 transition-all">
                                            <svg class="w-5 h-5 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </summary>
                                
                                <div class="px-5 pb-6 pt-4 border-t border-gray-100 bg-slate-50/50">
                                    @if(count($groupedPerms) > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                                            @foreach($groupedPerms as $module => $actions)
                                                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-[0_1px_3px_0_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow">
                                                    <div class="flex items-center gap-2.5 mb-4">
                                                        <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center text-gray-500 border border-gray-100">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                                            </svg>
                                                        </div>
                                                        <h5 class="text-xs font-bold text-gray-700 uppercase tracking-widest">{{ $module }}</h5>
                                                    </div>
                                                    
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($actions as $act)
                                                            @php
                                                                $color = match($act) {
                                                                    'read' => 'text-blue-600 bg-blue-50/80 border-blue-100',
                                                                    'create' => 'text-emerald-600 bg-emerald-50/80 border-emerald-100',
                                                                    'update' => 'text-amber-600 bg-amber-50/80 border-amber-100',
                                                                    'delete' => 'text-rose-600 bg-rose-50/80 border-rose-100',
                                                                    default => 'text-slate-600 bg-slate-50/80 border-slate-200'
                                                                };
                                                                $label = match($act) {
                                                                    'read' => 'Lectura',
                                                                    'create' => 'Crear',
                                                                    'update' => 'Editar',
                                                                    'delete' => 'Eliminar',
                                                                    default => ucfirst($act)
                                                                };
                                                            @endphp
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold tracking-wide border {{ $color }}">
                                                                {{ $label }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-8 text-center bg-white rounded-xl border border-gray-200 border-dashed">
                                            <p class="text-sm text-gray-500 font-medium">Este rol no tiene permisos específicos asignados.</p>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">Sin roles asignados.</p>
                @endif
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas de Estado y Sistema -->
        <div class="space-y-6">
            <!-- Estado de Cuenta Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="{{ $isSuspended ? 'bg-red-600' : 'bg-ganaderasoft-verde-oscuro' }} text-white px-6 py-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <span>🔐</span> Estado de cuenta
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado</label>
                        @if($isSuspended)
                            <span class="inline-flex px-3 py-1 text-base font-bold rounded-full bg-red-50 text-red-800 border border-red-200">
                                Suspendida
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-base font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Activa
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información del Sistema Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Identificador único</label>
                        <p class="text-sm font-semibold text-gray-900 font-mono">
                            ID #{{ $user['id'] }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de registro</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : 'Desconocida' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Última actualización</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($user['updated_at']) ? date('d/m/Y H:i', strtotime($user['updated_at'])) : 'Desconocida' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
