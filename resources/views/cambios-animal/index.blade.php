@extends('layouts.authenticated')

@section('title', 'Cambios de Animal - GanaderaSoft')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">📝 Cambios de Animal</h1>
            <a href="{{ route('cambios-animal.create') }}" 
               class="bg-ganaderasoft-verde text-white px-4 py-2 rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                + Registrar Cambio
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

        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">Información: </strong>
                <span class="block sm:inline">{{ session('info') }}</span>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-ganaderasoft-negro">🔍 Filtros de Búsqueda</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- Filtro por Animal -->
                <div>
                    <label for="filtroAnimal" class="block text-sm font-medium text-gray-700 mb-1">Animal</label>
                    <select id="filtroAnimal" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Todos los animales</option>
                        @if(is_array($animales))
                            @foreach($animales as $animal)
                                @if(is_array($animal) && isset($animal['id_Animal']))
                                    <option value="{{ $animal['id_Animal'] }}" {{ $idAnimal == $animal['id_Animal'] ? 'selected' : '' }}>
                                        {{ $animal['Nombre'] ?? 'Animal #' . $animal['id_Animal'] }}
                                        @if(isset($animal['finca']['Nombre']))
                                            - {{ $animal['finca']['Nombre'] }}
                                        @endif
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Filtro por Finca -->
                <div>
                    <label for="filtroFinca" class="block text-sm font-medium text-gray-700 mb-1">Finca</label>
                    <select id="filtroFinca" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Todas las fincas</option>
                        @if(is_array($fincas))
                            @foreach($fincas as $finca)
                                @if(is_array($finca) && isset($finca['id_Finca']))
                                    <option value="{{ $finca['id_Finca'] }}" {{ $idFinca == $finca['id_Finca'] ? 'selected' : '' }}>
                                        {{ $finca['Nombre'] ?? 'Finca #' . $finca['id_Finca'] }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Filtro por Etapa -->
                <div>
                    <label for="filtroEtapa" class="block text-sm font-medium text-gray-700 mb-1">Etapa</label>
                    <select id="filtroEtapa" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Todas las etapas</option>
                        @if(isset($estadisticas['por_etapa']))
                            @foreach(array_keys($estadisticas['por_etapa']) as $etapa)
                                <option value="{{ $etapa }}">{{ $etapa }}</option>
                            @endforeach
                        @endif
                    </select>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Cambios</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['total_cambios'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">📅</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Últimos 30 Días</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['ultimos_30_dias'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">⚖️</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Peso Promedio</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['promedio_peso'] }} kg</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">📏</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Altura Promedio</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['promedio_altura'] }} cm</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Cambios -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-ganaderasoft-negro">Lista de Cambios Registrados</h3>
            </div>

            @if(empty($cambios))
                <div class="p-8 text-center">
                    <div class="text-6xl mb-4">📝</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay cambios registrados</h3>
                    <p class="text-gray-500 mb-4">Comienza registrando los cambios de etapa de tus animales</p>
                    <a href="{{ route('cambios-animal.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-ganaderasoft-verde hover:bg-ganaderasoft-verde/80">
                        + Registrar Primer Cambio
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="tablaCambiosAnimal">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Cambio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Etapa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peso</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Altura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comentario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(is_array($cambios) && count($cambios) > 0)
                                @foreach($cambios as $cambio)
                                    @if(is_array($cambio))
                                        <tr class="hover:bg-gray-50 registro-cambio"
                                            data-animal="{{ $cambio['cambios_etapa_anid'] ?? '' }}"
                                            data-etapa="{{ strtolower($cambio['Etapa_Cambio'] ?? '') }}"
                                            data-fecha="{{ $cambio['Fecha_Cambio'] ?? '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if(isset($cambio['Fecha_Cambio']))
                                                        {{ date('d/m/Y', strtotime($cambio['Fecha_Cambio'])) }}
                                                    @else
                                                        --/--/----
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    @if(isset($cambio['created_at']))
                                                        {{ date('H:i', strtotime($cambio['created_at'])) }}
                                                    @elseif(isset($cambio['Fecha_Cambio']))
                                                        {{ date('H:i', strtotime($cambio['Fecha_Cambio'])) }}
                                                    @else
                                                        --:--
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-8 h-8">
                                                        <div class="w-8 h-8 bg-ganaderasoft-celeste rounded-full flex items-center justify-center">
                                                            <span class="text-white font-semibold text-xs">🐄</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            Animal #{{ $cambio['cambios_etapa_anid'] ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    @if(isset($cambio['Etapa_Cambio']) && (strtolower($cambio['Etapa_Cambio']) === 'becerro' || strtolower($cambio['Etapa_Cambio']) === 'becerra')) bg-yellow-100 text-yellow-800
                                                    @elseif(isset($cambio['Etapa_Cambio']) && strtolower($cambio['Etapa_Cambio']) === 'juvenil') bg-blue-100 text-blue-800
                                                    @elseif(isset($cambio['Etapa_Cambio']) && (strtolower($cambio['Etapa_Cambio']) === 'adulto' || strtolower($cambio['Etapa_Cambio']) === 'adulta')) bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $cambio['Etapa_Cambio'] ?? 'Sin etapa' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @if(isset($cambio['Peso']) && $cambio['Peso'])
                                                        {{ number_format($cambio['Peso'], 1) }} kg
                                                    @else
                                                        <span class="text-gray-400">No registrado</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @if(isset($cambio['Altura']) && $cambio['Altura'])
                                                        {{ number_format($cambio['Altura'], 1) }} cm
                                                    @else
                                                        <span class="text-gray-400">No registrado</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $cambio['Observacion'] ?? 'Sin observaciones' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if(isset($cambio['cambios_etapa_anid']))
                                                    <a href="{{ route('cambios-animal.show', $cambio['cambios_etapa_anid']) }}" 
                                                       class="text-ganaderasoft-verde hover:text-ganaderasoft-azul transition-colors duration-200">
                                                        👁️ Ver detalles
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No hay cambios de animales registrados
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        function filtrarRegistros() {
            const filtroAnimal = document.getElementById('filtroAnimal').value;
            const filtroFinca = document.getElementById('filtroFinca').value;
            const filtroEtapa = document.getElementById('filtroEtapa').value.toLowerCase();
            
            const registros = document.querySelectorAll('.registro-cambio');
            
            registros.forEach(function(registro) {
                let mostrar = true;
                
                // Filtro por animal
                if (filtroAnimal && registro.dataset.animal !== filtroAnimal) {
                    mostrar = false;
                }
                
                // Filtro por etapa
                if (filtroEtapa && !registro.dataset.etapa.includes(filtroEtapa)) {
                    mostrar = false;
                }
                
                registro.style.display = mostrar ? '' : 'none';
            });
            
            // Update URL with animal filter
            if (filtroAnimal) {
                const url = new URL(window.location);
                url.searchParams.set('animal_id', filtroAnimal);
                window.history.pushState({}, '', url);
            } else {
                const url = new URL(window.location);
                url.searchParams.delete('animal_id');
                window.history.pushState({}, '', url);
            }
            
            // Update URL with finca filter
            if (filtroFinca) {
                const url = new URL(window.location);
                url.searchParams.set('finca_id', filtroFinca);
                window.history.pushState({}, '', url);
            } else {
                const url = new URL(window.location);
                url.searchParams.delete('finca_id');
                window.history.pushState({}, '', url);
            }
        }
        
        function limpiarFiltros() {
            document.getElementById('filtroAnimal').value = '';
            document.getElementById('filtroFinca').value = '';
            document.getElementById('filtroEtapa').value = '';
            
            const registros = document.querySelectorAll('.registro-cambio');
            registros.forEach(function(registro) {
                registro.style.display = '';
            });
            
            // Clear URL parameters
            const url = new URL(window.location);
            url.searchParams.delete('animal_id');
            url.searchParams.delete('finca_id');
            window.history.pushState({}, '', url);
        }
        
        // Agregar event listeners
        document.getElementById('filtroAnimal').addEventListener('change', filtrarRegistros);
        document.getElementById('filtroFinca').addEventListener('change', filtrarRegistros);
        document.getElementById('filtroEtapa').addEventListener('change', filtrarRegistros);
    </script>
@endsection