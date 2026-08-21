@extends('layouts.authenticated')

@section('title', $catalog['name'])

@section('content')
    <div class="space-y-8">
        <!-- Header section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">{{ $catalog['name'] }}</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $catalog['description'] }}</p>
            </div>
            <a href="{{ route('admin.'.$catalog['slug'].'.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
                + Nuevo registro
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">✅</span>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">⚠️</span>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Filters Bar -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                    <input type="text" id="filtroBuscador" placeholder="Buscar por nombre..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="document.getElementById('filtroBuscador').value=''; aplicarFiltros();"
                       class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[46px]">
                        Limpiar filtro
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            @if(count($items) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Raza</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Visibilidad</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo animal</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Propósito / pelaje</th>
                                <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Proporción</th>
                                <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @foreach($items as $item)
                                @php
                                    $mainVal = $item['nombre'] ?? 'N/A';
                                    $siglas = $item['siglas'] ?? '';
                                    
                                    // Tipo Animal
                                    if(isset($item['tipo_animal']) && isset($item['tipo_animal']['nombre'])) {
                                        $tipoAnimal = $item['tipo_animal']['nombre'];
                                    } elseif(!empty($item['tipo_animal_id'])) {
                                        $tipoAnimal = $tiposMap[$item['tipo_animal_id']] ?? ('Tipo ID: '.$item['tipo_animal_id']);
                                    } else {
                                        $tipoAnimal = 'General';
                                    }
                                    
                                    $proposito = $item['proposito'] ?? 'N/A';
                                    $pelaje = $item['pelaje'] ?? 'N/A';
                                    $proporcion = $item['proporcion_raza'] ?? '-';
                                    
                                    $searchable = strtolower($mainVal . ' ' . $siglas . ' ' . $tipoAnimal . ' ' . $proposito . ' ' . $pelaje);
                                    $inicial = strtoupper(substr((string)$mainVal, 0, 1));
                                    if(empty($inicial)) $inicial = '#';
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors fila-registro"
                                    data-search="{{ $searchable }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100">
                                                {{ $inicial }}
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="font-bold text-gray-900 truncate">{{ $mainVal }}</p>
                                                <p class="text-xs text-gray-400">ID: #{{ $item['id'] }}{{ $siglas ? ' • Siglas: '.$siglas : '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(empty($item['finca_id']))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                🌐 Pública
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-ganaderasoft-celeste/20 text-ganaderasoft-azul border border-ganaderasoft-celeste/30">
                                                🔒 Privada
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $tipoAnimal }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ $proposito }}</div>
                                        <div class="text-xs text-gray-500">Pelaje: {{ $pelaje }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-ganaderasoft-verde-oscuro">
                                        {{ $proporcion }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex justify-center space-x-2">
                                                <!-- Botón de Ver Detalles -->
                                                <a href="{{ route('admin.'.$catalog['slug'].'.show', $item['id']) }}"
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                                   title="Ver detalle">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>

                                                <!-- Botón de Editar -->
                                                <a href="{{ route('admin.'.$catalog['slug'].'.edit', $item['id']) }}"
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                                   title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                
                                                <!-- Botón de Eliminar -->
                                                <form method="POST" action="{{ route('admin.'.$catalog['slug'].'.destroy', $item['id']) }}" class="inline-block" id="form-delete-{{ $item['id'] }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="openGenericConfirmModal({
                                                        formId: 'form-delete-{{ $item['id'] }}',
                                                        intent: 'danger',
                                                        title: 'Eliminar registro',
                                                        message: '¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.',
                                                        confirmText: 'Sí, eliminar'
                                                    })"
                                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                                       title="Eliminar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Mensaje de Sin Resultados Filtrados -->
                    <div id="sinResultadosFiltro" class="hidden p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-2xl">
                            🔍
                        </div>
                        <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">No se encontraron registros</h4>
                        <p class="text-gray-500 text-xs mb-4">No hay elementos que coincidan con la búsqueda aplicada.</p>
                        <button type="button" onclick="document.getElementById('filtroBuscador').value=''; aplicarFiltros();"
                                class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-xs inline-flex items-center gap-1.5 cursor-pointer">
                            Limpiar filtro
                        </button>
                    </div>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay registros</h3>
                    <p class="text-gray-500 text-sm mb-6">Prueba agregando un nuevo registro al catálogo.</p>
                    <a href="{{ route('admin.'.$catalog['slug'].'.create') }}"
                       class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        + Nuevo registro
                    </a>
                </div>
            @endif
        </div>
    </div>

    <x-ui.confirm-modal />
    
    <script>
        document.getElementById('filtroBuscador')?.addEventListener('input', aplicarFiltros);

        function aplicarFiltros() {
            const buscador = document.getElementById('filtroBuscador')?.value.toLowerCase().trim() || '';
            const tabla = document.getElementById('tablaContenedor') || document.querySelector('table');
            const sinResultados = document.getElementById('sinResultadosFiltro');
            const filas = document.querySelectorAll('.fila-registro');

            let totalVisibles = 0;

            filas.forEach(function(row) {
                const searchData = row.getAttribute('data-search') || '';
                const matchesSearch = !buscador || searchData.includes(buscador);
                if (matchesSearch) totalVisibles++;
                row.style.display = matchesSearch ? '' : 'none';
            });

            if (sinResultados) {
                if (totalVisibles === 0 && filas.length > 0) {
                    sinResultados.classList.remove('hidden');
                    if (tabla) tabla.classList.add('hidden');
                } else {
                    sinResultados.classList.add('hidden');
                    if (tabla) tabla.classList.remove('hidden');
                }
            }
        }
    </script>
@endsection