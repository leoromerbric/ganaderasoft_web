@extends('layouts.app')

@section('title', 'Medidas Corporales de Animales - GanaderaSoft')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">📏 Medidas Corporales de Animales</h1>
            <a href="{{ route('medidas-corporales.create') }}" 
               class="bg-ganaderasoft-verde text-white px-4 py-2 rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                + Nuevo Registro de Medidas
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">¡Éxito! </strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">Error: </strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-ganaderasoft-negro">🔍 Filtros de Búsqueda</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <!-- Filtro por Animal -->
                <div>
                    <label for="filtroAnimal" class="block text-sm font-medium text-gray-700 mb-1">Animal</label>
                    <select id="filtroAnimal" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Todos los animales</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id'] }}">
                                {{ $animal['identificacion'] }} - {{ $animal['nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Etapa -->
                <div>
                    <label for="filtroEtapa" class="block text-sm font-medium text-gray-700 mb-1">Etapa</label>
                    <select id="filtroEtapa" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Todas las etapas</option>
                        @foreach($configuraciones['etapas'] as $etapa)
                            <option value="{{ $etapa['id'] }}">{{ $etapa['descripcion'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Fecha Inicial -->
                <div>
                    <label for="filtroFechaInicio" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                    <input type="date" id="filtroFechaInicio" class="form-input w-full border-gray-300 rounded-md">
                </div>

                <!-- Filtro por Fecha Final -->
                <div>
                    <label for="filtroFechaFin" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                    <input type="date" id="filtroFechaFin" class="form-input w-full border-gray-300 rounded-md">
                </div>

                <!-- Botón Limpiar -->
                <div class="flex items-end">
                    <button onclick="limpiarFiltros()" 
                            class="w-full bg-gray-500 text-white px-3 py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">#</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Registros</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ count($medidasCorporales) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">📐</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Height Promedio</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['altura_promedio'] ?? '0' }} cm</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">📏</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Largura Promedio</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['largura_promedio'] ?? '0' }} cm</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">🔵</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Circ. Promedio</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['circunferencia_promedio'] ?? '0' }} cm</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Registros -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-ganaderasoft-negro">Registros de Medidas Corporales</h3>
            </div>

            @if(empty($medidasCorporales))
                <div class="p-8 text-center">
                    <div class="text-6xl mb-4">📏</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay registros de medidas corporales</h3>
                    <p class="text-gray-500 mb-4">Comienza registrando las medidas corporales de tus animales</p>
                    <a href="{{ route('medidas-corporales.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-ganaderasoft-verde hover:bg-ganaderasoft-verde/80">
                        + Registrar Primeras Medidas
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="tablaMedidasCorporales">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Altura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Largura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Circunferencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Etapa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($medidasCorporales as $medida)
                                <tr class="hover:bg-gray-50 registro-medida"
                                    data-animal="{{ $medida['animal_id'] }}"
                                    data-etapa="{{ $medida['etapa_id'] }}"
                                    data-fecha="{{ $medida['fecha_control'] }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <div class="w-10 h-10 bg-ganaderasoft-celeste rounded-full flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">🐄</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $medida['animal_identificacion'] ?? 'ID-' . $medida['animal_id'] }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $medida['animal_nombre'] ?? 'Nombre no disponible' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($medida['fecha_control'])->format('d/m/Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($medida['fecha_control'])->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $medida['altura'] ?? 'N/A' }} cm</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $medida['largura'] ?? 'N/A' }} cm</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $medida['circunferencia_toracica'] ?? 'N/A' }} cm</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $medida['etapa_descripcion'] ?? 'Etapa ' . $medida['etapa_id'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('medidas-corporales.show', $medida['id']) }}" 
                                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                                                Ver
                                            </a>
                                            <a href="{{ route('medidas-corporales.edit', $medida['id']) }}" 
                                               class="text-ganaderasoft-verde hover:text-ganaderasoft-verde/80">
                                                Editar
                                            </a>
                                            <form action="{{ route('medidas-corporales.destroy', $medida['id']) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('¿Está seguro de eliminar este registro de medidas?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        function filtrarRegistros() {
            const filtroAnimal = document.getElementById('filtroAnimal').value.toLowerCase();
            const filtroEtapa = document.getElementById('filtroEtapa').value;
            const filtroFechaInicio = document.getElementById('filtroFechaInicio').value;
            const filtroFechaFin = document.getElementById('filtroFechaFin').value;
            
            const registros = document.querySelectorAll('.registro-medida');
            
            registros.forEach(function(registro) {
                let mostrar = true;
                
                // Filtro por animal
                if (filtroAnimal && !registro.querySelector('td:first-child .text-sm').textContent.toLowerCase().includes(filtroAnimal)) {
                    mostrar = false;
                }
                
                // Filtro por etapa
                if (filtroEtapa && registro.dataset.etapa !== filtroEtapa) {
                    mostrar = false;
                }
                
                // Filtro por fecha
                const fechaRegistro = registro.dataset.fecha;
                if (filtroFechaInicio && fechaRegistro < filtroFechaInicio) {
                    mostrar = false;
                }
                if (filtroFechaFin && fechaRegistro > filtroFechaFin) {
                    mostrar = false;
                }
                
                registro.style.display = mostrar ? '' : 'none';
            });
        }
        
        function limpiarFiltros() {
            document.getElementById('filtroAnimal').value = '';
            document.getElementById('filtroEtapa').value = '';
            document.getElementById('filtroFechaInicio').value = '';
            document.getElementById('filtroFechaFin').value = '';
            
            const registros = document.querySelectorAll('.registro-medida');
            registros.forEach(function(registro) {
                registro.style.display = '';
            });
        }
        
        // Agregar event listeners
        document.getElementById('filtroAnimal').addEventListener('change', filtrarRegistros);
        document.getElementById('filtroEtapa').addEventListener('change', filtrarRegistros);
        document.getElementById('filtroFechaInicio').addEventListener('change', filtrarRegistros);
        document.getElementById('filtroFechaFin').addEventListener('change', filtrarRegistros);
    </script>
@endsection