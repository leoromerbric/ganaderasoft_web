@extends('layouts.authenticated')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Page Header & Farm Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Dashboard</h1>
            <p class="text-gray-500 text-sm mt-1">Resumen estadístico del sistema ganadero (API V2)</p>
        </div>
        
        <!-- Farm Filter Dropdown -->
        @if(count($farms) > 0)
        <div class="flex items-center space-x-3 bg-gray-50 p-2.5 rounded-xl border border-gray-200">
            <label for="finca-filter" class="text-xs font-semibold text-gray-600 uppercase tracking-wider pl-2 flex items-center">
                <svg class="w-4 h-4 mr-1 text-ganaderasoft-azul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Finca:
            </label>
            <select id="finca-filter" onchange="filterByFinca(this.value)" 
                    class="bg-white px-3 py-1.5 text-sm font-medium border border-gray-300 rounded-lg text-gray-800 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                <option value="">Todas las Fincas</option>
                @foreach($farms as $farm)
                    @php
                        $farmId = $farm['id'] ?? $farm['finca_id'] ?? null;
                        $farmNombre = $farm['nombre'] ?? 'Finca';
                    @endphp
                    <option value="{{ $farmId }}" {{ (string)$fincaId === (string)$farmId ? 'selected' : '' }}>
                        {{ $farmNombre }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <!-- KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($kpis as $kpi)
            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-ganaderasoft-{{ $kpi['color'] }} hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ $kpi['title'] }}</p>
                        <p class="text-3xl font-extrabold text-ganaderasoft-negro">{{ $kpi['value'] }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-ganaderasoft-{{ $kpi['color'] }}/10 flex items-center justify-center text-3xl">
                        {{ $kpi['icon'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Animals by Sex Chart Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center">
                    <span class="w-3 h-3 rounded-full bg-ganaderasoft-celeste mr-2"></span>
                    Distribución por Sexo
                </h3>
                <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100">API V2</span>
            </div>
            <div class="relative flex-1 min-h-[280px]">
                <canvas id="animalsBySexChart"></canvas>
            </div>
        </div>

        <!-- Staff by Type Chart Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center">
                    <span class="w-3 h-3 rounded-full bg-ganaderasoft-verde mr-2"></span>
                    Personal por Tipo
                </h3>
                <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100">API V2</span>
            </div>
            @if(isset($statistics['data']['personal_por_tipo']) && count($statistics['data']['personal_por_tipo']) > 0)
                <div class="relative flex-1 min-h-[280px]">
                    <canvas id="staffByTypeChart"></canvas>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-sm font-medium">Sin datos de personal registrados</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Data Tables Grid -->
    @if(isset($statistics['data']['fincas']) && count($statistics['data']['fincas']) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Farms Table -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-ganaderasoft-negro">Fincas Registradas</h3>
                <span class="text-xs font-semibold text-ganaderasoft-azul bg-ganaderasoft-celeste/15 px-3 py-1 rounded-full">
                    {{ count($statistics['data']['fincas']) }} finca(s)
                </span>
            </div>
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Rebaños</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Animales</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Personal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($statistics['data']['fincas'] as $finca)
                            @php
                                $fincaNombre = $finca['nombre'] ?? 'Sin Nombre';
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-4 py-3.5 font-medium text-gray-900 flex items-center space-x-2">
                                    <span class="text-base">🏡</span>
                                    <span>{{ $fincaNombre }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-center text-gray-700 font-semibold">{{ $finca['cantidad_rebanos'] ?? 0 }}</td>
                                <td class="px-4 py-3.5 text-center text-gray-700 font-semibold">{{ $finca['cantidad_animales'] ?? 0 }}</td>
                                <td class="px-4 py-3.5 text-center text-gray-700 font-semibold">{{ $finca['cantidad_personal'] ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Herds Table -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-ganaderasoft-negro">Rebaños Activos</h3>
                <span class="text-xs font-semibold text-ganaderasoft-verde-oscuro bg-ganaderasoft-verde/20 px-3 py-1 rounded-full">
                    {{ count($statistics['data']['rebanos'] ?? []) }} rebaño(s)
                </span>
            </div>
            @if(isset($statistics['data']['rebanos']) && count($statistics['data']['rebanos']) > 0)
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Finca ID</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Animales</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($statistics['data']['rebanos'] as $rebano)
                            @php
                                $rebanoNombre = $rebano['nombre'] ?? 'Rebaño';
                                $rebanoFincaId = $rebano['finca_id'] ?? '-';
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-4 py-3.5 font-medium text-gray-900 flex items-center space-x-2">
                                    <span class="text-base">🐑</span>
                                    <span>{{ $rebanoNombre }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-center text-xs text-gray-500">#{{ $rebanoFincaId }}</td>
                                <td class="px-4 py-3.5 text-center text-gray-700 font-semibold">{{ $rebano['cantidad_animales'] ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="py-8 text-center text-gray-400 text-sm italic">
                No hay rebaños registrados para esta consulta.
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Recent System Alerts -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-4">
        <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center">
            <svg class="w-5 h-5 mr-2 text-ganaderasoft-azul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Alertas y Estado del Sistema
        </h3>
        <div class="space-y-3">
            @foreach($alerts as $alert)
                <div class="p-4 rounded-xl border-l-4 transition-all duration-150 
                    @if(($alert['nivel'] ?? '') === 'alta') border-red-500 bg-red-50/60 text-red-900
                    @elseif(($alert['nivel'] ?? '') === 'media') border-yellow-500 bg-yellow-50/60 text-yellow-900
                    @else border-ganaderasoft-azul bg-ganaderasoft-celeste/10 text-ganaderasoft-negro
                    @endif">
                    <div class="flex items-start space-x-3">
                        <div class="text-lg mt-0.5">
                            @if(($alert['nivel'] ?? '') === 'alta') ⚠️
                            @elseif(($alert['nivel'] ?? '') === 'media') ⚡
                            @else ℹ️
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold opacity-75 mb-0.5">{{ $alert['fecha'] ?? '' }}</p>
                            <p class="text-sm font-medium">{{ $alert['mensaje'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
    function filterByFinca(fincaId) {
        const url = new URL(window.location.href);
        if (fincaId) {
            url.searchParams.set('finca_id', fincaId);
        } else {
            url.searchParams.delete('finca_id');
        }
        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Animals by Sex Chart
        const animalsBySexCtx = document.getElementById('animalsBySexChart');
        if (animalsBySexCtx) {
            const chartData = @json($chartData);
            
            new Chart(animalsBySexCtx, {
                type: 'doughnut',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '600'
                                }
                            }
                        }
                    }
                }
            });
        }

        // Staff by Type Chart
        @if(isset($statistics['data']['personal_por_tipo']) && count($statistics['data']['personal_por_tipo']) > 0)
        const staffByTypeCtx = document.getElementById('staffByTypeChart');
        if (staffByTypeCtx) {
            const staffData = @json($statistics['data']['personal_por_tipo']);
            
            new Chart(staffByTypeCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(staffData),
                    datasets: [{
                        label: 'Personal',
                        data: Object.values(staffData),
                        backgroundColor: [
                            'rgba(110, 193, 228, 0.85)', // celeste
                            'rgba(0, 123, 146, 0.85)',   // azul
                            'rgba(179, 211, 53, 0.85)',  // verde
                            'rgba(51, 51, 51, 0.85)'     // negro
                        ],
                        borderRadius: 8,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(229, 231, 235, 0.5)'
                            },
                            ticks: {
                                precision: 0,
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        @endif
    });
</script>
@endsection
