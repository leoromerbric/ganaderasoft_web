@extends('layouts.authenticated')

@section('title', 'Cambios de Animal')

@section('content')
    <div>
        <!-- Page Title and Actions -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-ganaderasoft-negro">Cambios de Animal</h2>
                <p class="text-gray-600 mt-1">Gestiona los cambios de etapa registrados por animal</p>
            </div>
            <a href="{{ route('cambios-animal.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                + Registrar Cambio
            </a>
        </div>

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
        @if(session('info'))
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded-lg">
                <p class="font-medium">{{ session('info') }}</p>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Finca -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Finca</label>
                    <select id="filtroFinca"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todas las fincas</option>
                        @foreach($fincas as $finca)
                            @if(isset($finca['id_Finca']))
                                <option value="{{ $finca['id_Finca'] }}" {{ $idFinca == $finca['id_Finca'] ? 'selected' : '' }}>
                                    {{ $finca['Nombre'] ?? 'Finca #'.$finca['id_Finca'] }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <!-- Rebano -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reba&ntilde;o</label>
                    <select id="filtroRebano"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos los reba&ntilde;os</option>
                        @foreach($rebanos as $rebano)
                            @if(isset($rebano['id_Rebano']))
                                <option value="{{ $rebano['id_Rebano'] }}" {{ $idRebano == $rebano['id_Rebano'] ? 'selected' : '' }}>
                                    {{ $rebano['Nombre'] ?? 'Reba&ntilde;o #'.$rebano['id_Rebano'] }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <!-- Animal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Animal</label>
                    <select id="filtroAnimal"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos los animales</option>
                        @foreach($animales as $animal)
                            @if(is_array($animal) && isset($animal['id_Animal']))
                                <option value="{{ $animal['id_Animal'] }}" {{ $idAnimal == $animal['id_Animal'] ? 'selected' : '' }}>
                                    {{ $animal['Nombre'] ?? 'Animal #'.$animal['id_Animal'] }}
                                    @if(isset($animal['Sexo']))({{ $animal['Sexo'] === 'M' ? 'Macho' : 'Hembra' }})@endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <!-- Limpiar -->
                <div>
                    <button onclick="limpiarFiltros()"
                            class="w-full px-6 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                        Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadisticas -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-azul">{{ $estadisticas['total_cambios'] }}</div>
                    <div class="text-sm text-gray-600">Total Cambios</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-verde">{{ $estadisticas['ultimos_30_dias'] }}</div>
                    <div class="text-sm text-gray-600">Ultimos 30 Dias</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-celeste">{{ $estadisticas['promedio_peso'] }} kg</div>
                    <div class="text-sm text-gray-600">Peso Promedio</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold" style="color:#E07B39;">{{ $estadisticas['promedio_altura'] }} cm</div>
                    <div class="text-sm text-gray-600">Altura Promedio</div>
                </div>
            </div>
        </div>

        <!-- Tabla de Cambios -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if(empty($cambios))
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">&#x1F4DD;</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay cambios registrados</h3>
                    <p class="text-gray-500 mb-4">Comienza registrando los cambios de etapa de tus animales</p>
                    <a href="{{ route('cambios-animal.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md">
                        + Registrar Primer Cambio
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Cambio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Etapa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peso</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Altura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comentario</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cambios as $cambio)
                                @if(is_array($cambio))
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ isset($cambio['Fecha_Cambio']) ? date('d/m/Y', strtotime($cambio['Fecha_Cambio'])) : '--/--/----' }}
                                        <div class="text-xs text-gray-500">
                                            {{ isset($cambio['Fecha_Cambio']) ? date('H:i', strtotime($cambio['Fecha_Cambio'])) : '--:--' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $mapaAnimales[$cambio['cambios_etapa_anid'] ?? null] ?? ('Animal #'.($cambio['cambios_etapa_anid'] ?? 'N/A')) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $etapa = strtolower($cambio['Etapa_Cambio'] ?? ''); @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ in_array($etapa, ['becerro','becerra']) ? 'bg-yellow-100 text-yellow-800' : ($etapa === 'juvenil' ? 'bg-blue-100 text-blue-800' : (in_array($etapa, ['adulto','adulta']) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $cambio['Etapa_Cambio'] ?? 'Sin etapa' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(!empty($cambio['Peso']))
                                            <span class="font-semibold text-ganaderasoft-verde">{{ number_format($cambio['Peso'], 1) }} kg</span>
                                        @else
                                            <span class="text-gray-400">No registrado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(!empty($cambio['Altura']))
                                            {{ number_format($cambio['Altura'], 1) }} cm
                                        @else
                                            <span class="text-gray-400">No registrado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $cambio['Comentario'] ?? 'Sin observaciones' }}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('filtroFinca').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroRebano').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroAnimal').addEventListener('change', aplicarFiltros);

        function aplicarFiltros() {
            const url = new URL(window.location);
            const finca  = document.getElementById('filtroFinca').value;
            const rebano = document.getElementById('filtroRebano').value;
            const animal = document.getElementById('filtroAnimal').value;
            url.searchParams.delete('finca_id');
            url.searchParams.delete('rebano_id');
            url.searchParams.delete('animal_id');
            if (finca)  url.searchParams.set('finca_id',  finca);
            if (rebano) url.searchParams.set('rebano_id', rebano);
            if (animal) url.searchParams.set('animal_id', animal);
            window.location.href = url.toString();
        }

        function limpiarFiltros() {
            const url = new URL(window.location);
            ['finca_id','rebano_id','animal_id'].forEach(k => url.searchParams.delete(k));
            window.location.href = url.toString();
        }
    </script>
@endsection