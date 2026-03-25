@extends('layouts.authenticated')

@section('title', 'Períodos de Lactancia')

@section('content')
    <div>
        <!-- Page Title and Actions -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-ganaderasoft-negro">Períodos de Lactancia</h2>
                <p class="text-gray-600 mt-1">Gestiona los períodos de lactancia de los animales</p>
            </div>
            <a href="{{ route('lactancia.create') }}" 
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                Nuevo Período
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

        <!-- Filter by Animal -->
        @if(count($animales) > 0)
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-end space-x-4">
                <div class="flex-1">
                    <label for="animal_select" class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Animal
                    </label>
                    <select 
                        id="animal_select" 
                        onchange="filterByAnimal(this.value)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos los Animales</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ (string)$animalId === (string)$animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] }} ({{ $animal['codigo_animal'] ?? 'Sin código' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @endif

        <!-- Lactation Periods List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if(count($lactancias) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($lactancias as $lactancia)
                                @php
                                    $fechaFin = $lactancia['Lactancia_fecha_fin'];
                                    $isActiva = is_null($fechaFin) || strtotime($fechaFin) > time();
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        Animal ID: {{ $lactancia['lactancia_etapa_anid'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ isset($lactancia['lactancia_fecha_inicio']) ? date('d/m/Y', strtotime($lactancia['lactancia_fecha_inicio'])) : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $fechaFin ? date('d/m/Y', strtotime($fechaFin)) : 'En curso' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($isActiva)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Activa
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Finalizada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('lactancia.show', $lactancia['lactancia_id']) }}" 
                                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">
                                                Ver
                                            </a>
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('lactancia.edit', $lactancia['lactancia_id']) }}" 
                                               class="text-ganaderasoft-verde hover:text-green-700">
                                                Editar
                                            </a>
                                            @if($isActiva)
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('leche.index', ['lactancia_id' => $lactancia['lactancia_id']]) }}" 
                                               class="text-blue-600 hover:text-blue-800">
                                                Ver Registros
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">🐄</div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay períodos de lactancia registrados</h3>
                    <p class="text-gray-500 mb-6">Comienza registrando el primer período de lactancia</p>
                    <a href="{{ route('lactancia.create') }}" 
                       class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                        Nuevo Período
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        function filterByAnimal(animalId) {
            const url = new URL(window.location.href);
            if (animalId) {
                url.searchParams.set('animal_id', animalId);
            } else {
                url.searchParams.delete('animal_id');
            }
            window.location.href = url.toString();
        }
    </script>
@endsection