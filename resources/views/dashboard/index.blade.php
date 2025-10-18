@extends('layouts.authenticated')

@section('title', 'Dashboard')

@section('content')
    <div>
        <!-- Page Title and Filter -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-ganaderasoft-negro">Dashboard</h2>
                <p class="text-gray-600 mt-1">Vista general del sistema de gestión ganadera</p>
            </div>
            
            <!-- Farm Filter -->
            @if(count($farms) > 0)
            <div class="flex items-center space-x-3">
                <label for="finca-filter" class="text-sm font-medium text-gray-700">Filtrar por Finca:</label>
                <select id="finca-filter" onchange="filterByFinca(this.value)" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    <option value="">Todas las Fincas</option>
                    @foreach($farms as $farm)
                        <option value="{{ $farm['id_Finca'] }}" {{ $fincaId == $farm['id_Finca'] ? 'selected' : '' }}>
                            {{ $farm['Nombre'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($kpis as $kpi)
                <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-ganaderasoft-{{ $kpi['color'] }} hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">{{ $kpi['title'] }}</p>
                            <p class="text-3xl font-bold text-ganaderasoft-negro">{{ $kpi['value'] }}</p>
                        </div>
                        <div class="text-5xl">{{ $kpi['icon'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Animals by Sex Chart -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Distribución de Animales por Sexo</h3>
                <div class="relative h-80">
                    <canvas id="animalsBySexChart"></canvas>
                </div>
            </div>

            <!-- Staff by Type Chart -->
            @if(isset($statistics['data']['personal_por_tipo']) && count($statistics['data']['personal_por_tipo']) > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Personal por Tipo</h3>
                <div class="relative h-80">
                    <canvas id="staffByTypeChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- Farms and Herds Details -->
        @if(isset($statistics['data']['fincas']) && count($statistics['data']['fincas']) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Farms Table -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Fincas</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rebaños</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Animales</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Personal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($statistics['data']['fincas'] as $finca)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $finca['Nombre'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $finca['cantidad_rebanos'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $finca['cantidad_animales'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $finca['cantidad_personal'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Herds Table -->
            @if(isset($statistics['data']['rebanos']) && count($statistics['data']['rebanos']) > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Rebaños</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Finca</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Animales</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($statistics['data']['rebanos'] as $rebano)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $rebano['Nombre'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">ID: {{ $rebano['id_Finca'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $rebano['cantidad_animales'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Recent Alerts -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Alertas Recientes</h3>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($alerts as $alert)
                    <div class="p-3 rounded-lg border-l-4 
                        @if($alert['nivel'] === 'alta') border-red-500 bg-red-50
                        @elseif($alert['nivel'] === 'media') border-yellow-500 bg-yellow-50
                        @else border-blue-500 bg-blue-50
                        @endif">
                        <div class="flex items-start space-x-2">
                            <div class="flex-shrink-0 mt-0.5">
                                @if($alert['nivel'] === 'alta')
                                    <span class="text-red-500 text-lg">⚠️</span>
                                @elseif($alert['nivel'] === 'media')
                                    <span class="text-yellow-500 text-lg">⚡</span>
                                @else
                                    <span class="text-blue-500 text-lg">ℹ️</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">{{ $alert['fecha'] }}</p>
                                <p class="text-sm text-gray-800 font-medium">{{ $alert['mensaje'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // Filter by farm function
        function filterByFinca(fincaId) {
            const url = new URL(window.location.href);
            if (fincaId) {
                url.searchParams.set('id_finca', fincaId);
            } else {
                url.searchParams.delete('id_finca');
            }
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Animals by Sex Chart
            const animalsBySexCtx = document.getElementById('animalsBySexChart');
            if (animalsBySexCtx) {
                const chartData = @json($chartData);
                
                new Chart(animalsBySexCtx, {
                    type: 'pie',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
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
                                'rgba(34, 197, 94, 0.85)',   // Vivid green
                                'rgba(59, 130, 246, 0.85)',  // Vivid blue
                                'rgba(168, 85, 247, 0.85)',  // Vivid purple
                                'rgba(249, 115, 22, 0.85)'   // Vivid orange
                            ],
                            borderColor: [
                                'rgb(22, 163, 74)',          // Darker green border
                                'rgb(37, 99, 235)',          // Darker blue border
                                'rgb(147, 51, 234)',         // Darker purple border
                                'rgb(234, 88, 12)'           // Darker orange border
                            ],
                            borderWidth: 2,
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
                                ticks: {
                                    stepSize: 1
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
