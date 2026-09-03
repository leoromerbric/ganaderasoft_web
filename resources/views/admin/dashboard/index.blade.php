@extends('layouts.authenticated')

@section('title', 'Panel administrador')

@section('content')
<div class="space-y-6">
    <!-- Encabezado del Dashboard -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Panel administrador</h1>
            <p class="text-sm text-gray-500 mt-1">Resumen general del sistema y métricas globales de registros activos</p>
        </div>
    </div>

    <!-- 4 Core KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
        <!-- KPI: Total Usuarios -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total usuarios</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">
                    {{ number_format($kpis['total_users'] ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span> Cuentas registradas
                </p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-2xl shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                👥
            </div>
        </div>

        <!-- KPI: Fincas -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Fincas activas</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">
                    {{ number_format($kpis['total_fincas'] ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Unidades activas
                </p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-2xl shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                🏡
            </div>
        </div>

        <!-- KPI: Rebaños -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rebaños activos</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">
                    {{ number_format($kpis['total_rebanos'] ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span> Grupos de manejo activos
                </p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center text-2xl shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                🏷️
            </div>
        </div>

        <!-- KPI: Animales -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Animales activos</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">
                    {{ number_format($kpis['total_animales'] ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Inventario activo
                </p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-2xl shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                🐄
            </div>
        </div>
    </div>

    <!-- Gráficas Analíticas -->
    @php
        $propCount = (int)($kpis['total_propietarios'] ?? 0);
        $admCount = (int)($kpis['total_administradores'] ?? 0);
        $totalRoles = $propCount + $admCount;

        $actCount = (int)($kpis['active_users'] ?? 0);
        $suspCount = (int)($kpis['suspended_users'] ?? 0);
        $totalStatus = $actCount + $suspCount;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Gráfica de Roles -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>👨‍🌾</span> Distribución por roles
            </h3>
            @if($totalRoles > 0)
                <div class="relative h-64 w-full flex items-center justify-center">
                    <canvas id="chartRoles"></canvas>
                </div>
            @else
                <div class="h-64 flex flex-col items-center justify-center text-gray-400 space-y-2">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-2xl border border-gray-100 shadow-2xs">
                        👥
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Sin datos de roles</p>
                    <p class="text-xs text-gray-400 text-center max-w-xs">No hay usuarios suficientes registrados para graficar la distribución.</p>
                </div>
            @endif
        </div>

        <!-- Gráfica de Estado -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>🟢</span> Estado de cuentas
            </h3>
            @if($totalStatus > 0)
                <div class="relative h-64 w-full flex items-center justify-center">
                    <canvas id="chartStatus"></canvas>
                </div>
            @else
                <div class="h-64 flex flex-col items-center justify-center text-gray-400 space-y-2">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-2xl border border-gray-100 shadow-2xs">
                        🟢
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Sin datos de estado</p>
                    <p class="text-xs text-gray-400 text-center max-w-xs">No hay cuentas activas o suspendidas para calcular los porcentajes.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Secciones Inferiores: Accesos Rápidos y Tabla de Usuarios Recientes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tabla de Usuarios Recientes -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        Usuarios recientes
                        <span class="text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md">Últimos 5</span>
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">Mostrando los 5 usuarios más recientes registrados en la plataforma</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-lg border border-gray-200 shadow-2xs transition-all">
                    Ver todos
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="overflow-x-auto min-w-full rounded-xl border border-gray-100">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="py-3 px-4">Usuario</th>
                            <th class="py-3 px-4">Correo</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse(array_slice($recentUsers, 0, 5) as $u)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 px-4 font-semibold text-gray-900 flex items-center space-x-2">
                                    <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center">
                                        {{ strtoupper(substr($u['name'] ?? 'U', 0, 2)) }}
                                    </span>
                                    <span>{{ $u['name'] ?? 'Sin nombre' }}</span>
                                </td>
                                <td class="py-3 px-4">{{ $u['email'] ?? 'Sin correo' }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $st = strtolower($u['status'] ?? 'active');
                                        $isSuspended = in_array($st, ['suspended', 'inactive', 'suspendido', 'inactivo'], true);
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isSuspended ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $isSuspended ? 'Suspendido' : 'Activo' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.users.show', $u['id']) }}" class="text-xs font-medium text-purple-700 hover:text-purple-900">
                                        Detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-400">
                                    No hay usuarios registrados recientemente.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panel Lateral de Acceso a Catálogos Maestro -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">
                        ⚙️
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Parámetros del sistema</h3>
                </div>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                    Gestiona los catálogos maestros de referencia utilizados en toda la plataforma: Tipos de trabajador, razas, etapas, estados de salud, vacunas y más.
                </p>
                
                <ul class="mt-4 space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Tipos de trabajador
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Clasificación ganadera y genética
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Variables de sanidad y salud
                    </li>
                </ul>
            </div>

            <div class="mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('admin.tipos-trabajador.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white font-semibold text-sm transition-colors shadow-sm">
                    Administrar catálogos
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Datos de PHP
        const prop = {{ $kpis['total_propietarios'] ?? 0 }};
        const adm = {{ $kpis['total_administradores'] ?? 0 }};
        const act = {{ $kpis['active_users'] ?? 0 }};
        const susp = {{ $kpis['suspended_users'] ?? 0 }};

        // Opciones Globales
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.color = '#64748b';

        // 1. Gráfica de Roles (Bar Chart con gradientes)
        const roleElem = document.getElementById('chartRoles');
        if (roleElem) {
            const ctxRoles = roleElem.getContext('2d');
            
            const gradProp = ctxRoles.createLinearGradient(0, 0, 0, 300);
            gradProp.addColorStop(0, '#34d399'); // emerald-400
            gradProp.addColorStop(1, '#059669'); // emerald-600

            const gradAdm = ctxRoles.createLinearGradient(0, 0, 0, 300);
            gradAdm.addColorStop(0, '#c084fc'); // purple-400
            gradAdm.addColorStop(1, '#7e22ce'); // purple-700

            new Chart(ctxRoles, {
                type: 'bar',
                data: {
                    labels: ['Propietarios', 'Administradores'],
                    datasets: [{
                        label: 'Usuarios',
                        data: [prop, adm],
                        backgroundColor: [gradProp, gradAdm],
                        borderRadius: 8,
                        borderSkipped: false,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4], color: '#f1f5f9' },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false }
                        }
                    }
                }
            });
        }

        // 2. Gráfica de Estado (Doughnut Chart con gradientes)
        const statusElem = document.getElementById('chartStatus');
        if (statusElem) {
            const ctxStatus = statusElem.getContext('2d');
            
            const gradAct = ctxStatus.createLinearGradient(0, 0, 0, 300);
            gradAct.addColorStop(0, '#60a5fa'); // blue-400
            gradAct.addColorStop(1, '#2563eb'); // blue-600

            const gradSusp = ctxStatus.createLinearGradient(0, 0, 0, 300);
            gradSusp.addColorStop(0, '#fb7185'); // rose-400
            gradSusp.addColorStop(1, '#e11d48'); // rose-600

            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Activos', 'Suspendidos'],
                    datasets: [{
                        data: [act, susp],
                        backgroundColor: [gradAct, gradSusp],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 12, weight: '500' }
                            }
                        }
                    },
                    layout: { padding: { bottom: 10 } }
                }
            });
        }
    });
</script>
@endpush
@endsection
