@extends('layouts.authenticated')

@section('title', 'Registro de Producción de Leche')

@section('content')
    <div>
        <!-- Page Title and Actions -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-ganaderasoft-negro">Registro de Producción de Leche</h2>
                <p class="text-gray-600 mt-1">Gestiona los registros diarios de producción lechera</p>
            </div>
            <a href="{{ route('leche.create', ['lactancia_id' => $lactanciaId]) }}" 
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                Nuevo Registro
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Filter by Lactation Period -->
        @if(count($lactancias) > 0)
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-end space-x-4">
                <div class="flex-1">
                    <label for="lactancia_select" class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Período de Lactancia
                    </label>
                    <select 
                        id="lactancia_select" 
                        onchange="filterByLactancia(this.value)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos los Períodos</option>
                        @foreach($lactancias as $lactancia)
                            @php
                                $fechaInicio = date('d/m/Y', strtotime($lactancia['lactancia_fecha_inicio']));
                                $fechaFin = $lactancia['Lactancia_fecha_fin'] ? date('d/m/Y', strtotime($lactancia['Lactancia_fecha_fin'])) : 'En curso';
                            @endphp
                            <option value="{{ $lactancia['lactancia_id'] }}" {{ (string)$lactanciaId === (string)$lactancia['lactancia_id'] ? 'selected' : '' }}>
                                Animal {{ $lactancia['lactancia_etapa_anid'] }} - {{ $fechaInicio }} hasta {{ $fechaFin }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        @if(count($registrosLeche) > 0)
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $totalProduccion = array_sum(array_column($registrosLeche, 'leche_pesaje_Total'));
                    $registrosCount = count($registrosLeche);
                    $promedioDiario = $registrosCount > 0 ? $totalProduccion / $registrosCount : 0;
                @endphp
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-azul">{{ $registrosCount }}</div>
                    <div class="text-sm text-gray-600">Registros</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-verde">{{ number_format($totalProduccion, 2) }} L</div>
                    <div class="text-sm text-gray-600">Total Producido</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-celeste">{{ number_format($promedioDiario, 2) }} L</div>
                    <div class="text-sm text-gray-600">Promedio por Registro</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Milk Production Records List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if(count($registrosLeche) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Pesaje</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad (Litros)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lactancia ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($registrosLeche as $registro)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ date('d/m/Y', strtotime($registro['leche_fecha_pesaje'])) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="font-semibold text-ganaderasoft-verde">{{ number_format($registro['leche_pesaje_Total'], 2) }} L</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $registro['leche_lactancia_id'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('leche.show', $registro['leche_id']) }}" 
                                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">
                                                Ver
                                            </a>
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('leche.edit', $registro['leche_id']) }}" 
                                               class="text-ganaderasoft-verde hover:text-green-700">
                                                Editar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">🥛</div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay registros de leche</h3>
                    <p class="text-gray-500 mb-6">
                        @if($lactanciaId)
                            No hay registros para este período de lactancia
                        @else
                            Comienza registrando la primera producción lechera
                        @endif
                    </p>
                    <div class="space-x-4">
                        @if(!$lactanciaId)
                            <a href="{{ route('lactancia.index') }}" 
                               class="inline-block px-6 py-3 bg-ganaderasoft-azul text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 mr-2">
                                Ver Lactancias
                            </a>
                        @endif
                        <a href="{{ route('leche.create', ['lactancia_id' => $lactanciaId]) }}" 
                           class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                            Nuevo Registro
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function filterByLactancia(lactanciaId) {
            const url = new URL(window.location.href);
            if (lactanciaId) {
                url.searchParams.set('lactancia_id', lactanciaId);
            } else {
                url.searchParams.delete('lactancia_id');
            }
            window.location.href = url.toString();
        }
    </script>
@endsection