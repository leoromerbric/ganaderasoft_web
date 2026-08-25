@extends('layouts.authenticated')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Encabezado del Dashboard -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Panel general</h1>
            <p class="text-sm text-gray-500 mt-1">Resumen estadístico del inventario ganadero y métricas productivas</p>
        </div>
    </div>

    <!-- 4 Core KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
        <!-- KPI: Total Animales -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total animales</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">
                    {{ number_format($statistics['data']['resumen']['total_animales'] ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Inventario activo
                </p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-2xl shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                🐄
            </div>
        </div>

        <!-- KPI: Fincas -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Fincas</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">
                    {{ number_format($statistics['data']['resumen']['total_fincas'] ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Unidades registradas
                </p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-2xl shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                🏡
            </div>
        </div>

        <!-- KPI: Rebaños -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rebaños y lotes</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">
                    {{ number_format($statistics['data']['resumen']['total_rebanos'] ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span> Grupos de manejo
                </p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center text-2xl shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                🏷️
            </div>
        </div>

        <!-- KPI: Personal -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Personal</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">
                    {{ number_format($statistics['data']['resumen']['total_personal'] ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span> Trabajadores en campo
                </p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-2xl shrink-0 group-hover:scale-105 transition-transform shadow-2xs">
                👥
            </div>
        </div>
    </div>

    <!-- Gráficas Analíticas -->
    @php
        $machosCount = (int)($statistics['data']['animales_por_sexo']['M'] ?? 0);
        $hembrasCount = (int)($statistics['data']['animales_por_sexo']['F'] ?? 0);
        $totalConSexo = $machosCount + $hembrasCount;

        $rebanosList = $statistics['data']['rebanos'] ?? [];
        usort($rebanosList, function($a, $b) {
            return ((int)($b['cantidad_animales'] ?? 0)) <=> ((int)($a['cantidad_animales'] ?? 0));
        });

        $totalEnRebanos = 0;
        foreach($rebanosList as $r) {
            $totalEnRebanos += (int)($r['cantidad_animales'] ?? 0);
        }
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Gráfica de Distribución por Sexo -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <span>🧬</span> Distribución por sexo
                </h3>
                <span class="text-xs text-gray-400 font-medium">Machos vs hembras</span>
            </div>
            @if($totalConSexo > 0)
                <div class="relative h-64 w-full flex items-center justify-center">
                    <canvas id="animalsBySexChart"></canvas>
                </div>
            @else
                <div class="h-64 flex flex-col items-center justify-center text-gray-400 space-y-2">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-2xl border border-gray-100 shadow-2xs">
                        🐄
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Sin animales registrados</p>
                    <p class="text-xs text-gray-400 text-center max-w-xs">No hay animales en el inventario para calcular la distribución.</p>
                </div>
            @endif
        </div>

        <!-- Gráfica de Animales por Rebaño -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <span>🏷️</span> Animales por rebaño o lote
                </h3>
                <span class="text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md">Top 7 por población</span>
            </div>
            @if(count($rebanosList) > 0 && $totalEnRebanos > 0)
                <div class="relative h-64 w-full flex items-center justify-center">
                    <canvas id="animalsByHerdChart"></canvas>
                </div>
            @else
                <div class="h-64 flex flex-col items-center justify-center text-gray-400 space-y-2">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-2xl border border-gray-100 shadow-2xs">
                        🏷️
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Sin animales en rebaños</p>
                    <p class="text-xs text-gray-400 text-center max-w-xs">Asigna animales a tus rebaños para visualizar la distribución.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Secciones Inferiores: Fincas/Rebaños Recientes y Operaciones Rápidas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Fincas y Rebaños Recientes (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tabla de Fincas Recientes -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            Fincas recientes
                            <span class="text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md">Últimas 5</span>
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Mostrando las 5 unidades productivas más recientes registradas</p>
                    </div>
                    <a href="{{ route('fincas.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-lg border border-gray-200 shadow-2xs transition-all">
                        Ver todas
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="overflow-x-auto min-w-full rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="py-3 px-4">Finca</th>
                                <th class="py-3 px-4 text-center">Rebaños</th>
                                <th class="py-3 px-4 text-center">Animales</th>
                                <th class="py-3 px-4 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse(array_slice($statistics['data']['fincas'] ?? [], 0, 5) as $finca)
                                @php
                                    $fId = $finca['id'] ?? $finca['finca_id'] ?? null;
                                    $fNombre = $finca['nombre'] ?? 'Sin nombre';
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-4 font-semibold text-gray-900 flex items-center space-x-2">
                                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($fNombre, 0, 2)) }}
                                        </span>
                                        <span>{{ $fNombre }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                            {{ $finca['cantidad_rebanos'] ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            {{ $finca['cantidad_animales'] ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        @if($fId)
                                            <a href="{{ route('fincas.show', $fId) }}" class="text-xs font-medium text-purple-700 hover:text-purple-900">
                                                Detalle
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-gray-400">
                                        No hay fincas registradas recientemente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla de Rebaños Recientes -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            Rebaños recientes
                            <span class="text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md">Últimos 5</span>
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Mostrando los 5 lotes y grupos de manejo más recientes</p>
                    </div>
                    <a href="{{ route('rebanos.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-lg border border-gray-200 shadow-2xs transition-all">
                        Ver todos
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="overflow-x-auto min-w-full rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="py-3 px-4">Rebaño</th>
                                <th class="py-3 px-4 text-center">Finca ID</th>
                                <th class="py-3 px-4 text-center">Animales</th>
                                <th class="py-3 px-4 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse(array_slice($statistics['data']['rebanos'] ?? [], 0, 5) as $rebano)
                                @php
                                    $rId = $rebano['id'] ?? null;
                                    $rebanoNombre = $rebano['nombre'] ?? 'Rebaño';
                                    $rebanoFincaId = $rebano['finca_id'] ?? '-';
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-4 font-semibold text-gray-900 flex items-center space-x-2">
                                        <span class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($rebanoNombre, 0, 2)) }}
                                        </span>
                                        <span>{{ $rebanoNombre }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center text-xs text-gray-500 font-mono">
                                        #{{ $rebanoFincaId }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            {{ $rebano['cantidad_animales'] ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('rebanos.index') }}" class="text-xs font-medium text-purple-700 hover:text-purple-900">
                                            Detalle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-gray-400">
                                        No hay rebaños registrados recientemente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Accesos Rápidos y Alertas (1 Tercio) -->
        <div class="space-y-6">
            <!-- Accesos Rápidos Card -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-ganaderasoft-verde/15 text-ganaderasoft-verde-oscuro flex items-center justify-center text-xl font-bold">
                        ⚡
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Operaciones rápidas</h3>
                        <p class="text-xs text-gray-400">Atajos de registro frecuente</p>
                    </div>
                </div>

                <div class="space-y-2.5">
                    <a href="{{ route('animales.create') }}" 
                       class="w-full py-3 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded-xl text-sm flex items-center justify-between border border-gray-200/80 transition-all shadow-2xs group">
                        <span class="flex items-center gap-2.5">
                            <span>🐄</span> Registrar nuevo animal
                        </span>
                        <span class="text-gray-400 group-hover:text-gray-900 group-hover:translate-x-0.5 transition-all">→</span>
                    </a>

                    <a href="{{ route('leche.create') }}" 
                       class="w-full py-3 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded-xl text-sm flex items-center justify-between border border-gray-200/80 transition-all shadow-2xs group">
                        <span class="flex items-center gap-2.5">
                            <span>🥛</span> Registrar pesaje de leche
                        </span>
                        <span class="text-gray-400 group-hover:text-gray-900 group-hover:translate-x-0.5 transition-all">→</span>
                    </a>

                    <a href="{{ route('vacunacion.create') }}" 
                       class="w-full py-3 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded-xl text-sm flex items-center justify-between border border-gray-200/80 transition-all shadow-2xs group">
                        <span class="flex items-center gap-2.5">
                            <span>💉</span> Registrar vacunación
                        </span>
                        <span class="text-gray-400 group-hover:text-gray-900 group-hover:translate-x-0.5 transition-all">→</span>
                    </a>

                    <a href="{{ route('peso-corporal.create') }}" 
                       class="w-full py-3 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded-xl text-sm flex items-center justify-between border border-gray-200/80 transition-all shadow-2xs group">
                        <span class="flex items-center gap-2.5">
                            <span>⚖️</span> Control de peso corporal
                        </span>
                        <span class="text-gray-400 group-hover:text-gray-900 group-hover:translate-x-0.5 transition-all">→</span>
                    </a>

                    <a href="{{ route('fincas.create') }}" 
                       class="w-full py-3 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded-xl text-sm flex items-center justify-between border border-gray-200/80 transition-all shadow-2xs group">
                        <span class="flex items-center gap-2.5">
                            <span>🏡</span> Crear nueva finca
                        </span>
                        <span class="text-gray-400 group-hover:text-gray-900 group-hover:translate-x-0.5 transition-all">→</span>
                    </a>
                </div>
            </div>

            <!-- Alertas y Estado del Sistema -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                            🔔
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Estado del sistema</h3>
                            <p class="text-xs text-gray-400">Alertas y notificaciones</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($alerts as $alert)
                        @php
                            $nivel = strtolower($alert['nivel'] ?? 'info');
                            $isAlta = $nivel === 'alta' || $nivel === 'high';
                            $isMedia = $nivel === 'media' || $nivel === 'medium';
                        @endphp
                        <div class="p-3.5 rounded-xl border transition-all duration-150 
                            {{ $isAlta ? 'border-rose-200 bg-rose-50/70 text-rose-900' : ($isMedia ? 'border-amber-200 bg-amber-50/70 text-amber-900' : 'border-blue-200 bg-blue-50/70 text-blue-900') }}">
                            <div class="flex items-start space-x-2.5">
                                <div class="text-base mt-0.5 shrink-0">
                                    {{ $isAlta ? '⚠️' : ($isMedia ? '⚡' : 'ℹ️') }}
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    @if(!empty($alert['fecha']))
                                        <p class="text-[11px] font-bold opacity-70 mb-0.5">{{ $alert['fecha'] }}</p>
                                    @endif
                                    <p class="text-xs font-semibold leading-relaxed">{{ $alert['mensaje'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200/80 text-emerald-800 text-xs text-center flex items-center justify-center gap-2 font-semibold">
                            <span>✓</span> Todos los servicios del sistema operan normalmente
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Datos de PHP
        const machos = {{ $machosCount }};
        const hembras = {{ $hembrasCount }};

        // Opciones Globales
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.color = '#64748b';

        // 1. Gráfica de Distribución por Sexo (Doughnut Chart con Gradientes)
        const sexElem = document.getElementById('animalsBySexChart');
        if (sexElem) {
            const ctx = sexElem.getContext('2d');
            
            const gradMacho = ctx.createLinearGradient(0, 0, 0, 300);
            gradMacho.addColorStop(0, '#60a5fa'); // blue-400
            gradMacho.addColorStop(1, '#2563eb'); // blue-600

            const gradHembra = ctx.createLinearGradient(0, 0, 0, 300);
            gradHembra.addColorStop(0, '#fb7185'); // rose-400
            gradHembra.addColorStop(1, '#e11d48'); // rose-600

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Machos', 'Hembras'],
                    datasets: [{
                        data: [machos, hembras],
                        backgroundColor: [gradMacho, gradHembra],
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

        // 2. Gráfica de Animales por Rebaño (Bar Chart con Gradiente)
        @if(count($rebanosList) > 0 && $totalEnRebanos > 0)
        const herdElem = document.getElementById('animalsByHerdChart');
        if (herdElem) {
            const ctxHerd = herdElem.getContext('2d');
            
            const gradHerd = ctxHerd.createLinearGradient(0, 0, 0, 300);
            gradHerd.addColorStop(0, '#34d399'); // emerald-400
            gradHerd.addColorStop(1, '#059669'); // emerald-600

            const rebanosData = @json(array_slice($rebanosList, 0, 7));
            const labels = rebanosData.map(r => r.nombre || ('Rebaño #' + (r.rebano_id || '')));
            const data = rebanosData.map(r => parseInt(r.cantidad_animales, 10) || 0);

            new Chart(ctxHerd, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Animales',
                        data: data,
                        backgroundColor: gradHerd,
                        borderRadius: 8,
                        borderSkipped: false,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.parsed.y + ' animales';
                                }
                            }
                        }
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
        @endif
    });
</script>
@endpush
@endsection