@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header/Navbar -->
    <nav class="bg-white shadow-md border-b-4 border-ganaderasoft-celeste">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Title -->
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul p-2 rounded-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-ganaderasoft-negro">GanaderaSoft</h1>
                        <p class="text-xs text-gray-500">Sistema de Gestión</p>
                    </div>
                </div>

                <!-- User Info and Logout -->
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-ganaderasoft-negro">{{ $user['name'] }}</p>
                        <p class="text-xs text-gray-500">{{ $user['email'] }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Dashboard</h2>
            <p class="text-gray-600 mt-1">Vista general del sistema de gestión ganadera</p>
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

        <!-- Chart and Alerts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Production Chart -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Producción de Leche - Última Semana</h3>
                <div class="relative h-80">
                    <canvas id="productionChart"></canvas>
                </div>
            </div>

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

        <!-- Alerts Table -->
        <div class="mt-6 bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Tabla de Alertas</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nivel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensaje</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($alerts as $alert)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $alert['fecha'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($alert['nivel'] === 'alta') bg-red-100 text-red-800
                                        @elseif($alert['nivel'] === 'media') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($alert['nivel']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $alert['mensaje'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script (using bundled version from app.js) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('productionChart');
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' L';
                        }
                    }
                }
            }
        }
    });
    });
</script>
@endsection
