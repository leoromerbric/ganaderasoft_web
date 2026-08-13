@extends('layouts.authenticated')

@section('title', 'Gestión de Animales')

@section('content')
    <div>
        <!-- Cabecera -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de Animales</h2>
                <p class="text-gray-600 mt-1">Administra los animales del sistema</p>
            </div>
            <a href="{{ route('animales.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                + Nuevo Animal
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

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-nowrap gap-4 items-end">
                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Finca</label>
                    <select id="filtroFinca"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todas las fincas</option>
                        @foreach($fincas as $finca)
                            <option value="{{ $finca['id'] }}" {{ $idFinca == $finca['id'] ? 'selected' : '' }}>
                                {{ $finca['nombre'] ?? 'Finca #'.$finca['id'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rebaño</label>
                    <select id="filtroRebano"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos los rebaños</option>
                        @foreach($rebanos as $rebano)
                            <option value="{{ $rebano['id'] }}"
                                    data-finca="{{ $rebano['finca_id'] ?? '' }}"
                                    {{ $idRebano == $rebano['id'] ? 'selected' : '' }}>
                                {{ $rebano['nombre'] ?? 'Rebaño #'.$rebano['id'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                    <select id="filtroSexo"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos</option>
                        <option value="M" {{ $sexo === 'M' ? 'selected' : '' }}>Macho</option>
                        <option value="H" {{ $sexo === 'H' ? 'selected' : '' }}>Hembra</option>
                    </select>
                </div>
                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre o Código</label>
                    <input type="text" id="filtroNombre" value="{{ $nombre }}" placeholder="Buscar..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div class="flex-none">
                    <button onclick="limpiarFiltros()"
                            class="w-full px-6 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                        Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div id="statTotal" class="text-2xl font-bold text-ganaderasoft-azul">{{ $estadisticas['total'] }}</div>
                    <div class="text-sm text-gray-600">Total Animales</div>
                </div>
                <div class="text-center">
                    <div id="statMachos" class="text-2xl font-bold text-ganaderasoft-celeste">{{ $estadisticas['machos'] }}</div>
                    <div class="text-sm text-gray-600">Machos</div>
                </div>
                <div class="text-center">
                    <div id="statHembras" class="text-2xl font-bold" style="color:#E07B39;">{{ $estadisticas['hembras'] }}</div>
                    <div class="text-sm text-gray-600">Hembras</div>
                </div>
                <div class="text-center">
                    <div id="statActivos" class="text-2xl font-bold text-ganaderasoft-verde">{{ $estadisticas['activos'] }}</div>
                    <div class="text-sm text-gray-600">Activos</div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if(count($animales) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rebaño</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Nacimiento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tablaAnimales">
                            @foreach($animales as $animal)
                                @php
                                    $rebanoId   = $animal['rebano']['id'] ?? ($animal['rebano_id'] ?? '');
                                    $fincaId    = $animal['rebano']['finca_id']  ?? ($mapaRebanoFinca[$rebanoId] ?? '');
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors fila-animal"
                                    data-rebano="{{ $rebanoId }}"
                                    data-finca="{{ $fincaId }}"
                                    data-sexo="{{ $animal['sexo'] ?? '' }}"
                                    data-nombre="{{ strtolower(($animal['nombre'] ?? '').' '.($animal['codigo_animal'] ?? '')) }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $animal['codigo_animal'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $animal['nombre'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ ($animal['sexo'] ?? '') === 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ ($animal['sexo'] ?? '') === 'M' ? 'Macho' : 'Hembra' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $animal['rebano']['nombre'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ isset($animal['fecha_nacimiento']) ? date('d/m/Y', strtotime($animal['fecha_nacimiento'])) : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(!empty($animal['archivado']))
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Archivado</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('animales.show', $animal['id']) }}"
                                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">Ver</a>
                                            <a href="{{ route('animales.edit', $animal['id']) }}"
                                               class="text-ganaderasoft-verde hover:text-ganaderasoft-verde-oscuro">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">&#x1F404;</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay animales registrados</h3>
                    <p class="text-gray-500 mb-4">Comienza agregando tu primer animal al sistema</p>
                    <a href="{{ route('animales.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md">
                        + Nuevo Animal
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        const mapaRebanoFinca = @json($mapaRebanoFinca);

        // Cascada finca → rebano
        document.getElementById('filtroFinca').addEventListener('change', function () {
            const fincaId = this.value;
            const rebanoSel = document.getElementById('filtroRebano');
            Array.from(rebanoSel.options).forEach(opt => {
                if (!opt.value) { opt.style.display = ''; return; }
                opt.style.display = (!fincaId || opt.dataset.finca === fincaId) ? '' : 'none';
            });
            if (rebanoSel.value && rebanoSel.options[rebanoSel.selectedIndex].style.display === 'none') {
                rebanoSel.value = '';
            }
            aplicarFiltros();
        });

        document.getElementById('filtroRebano').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroSexo').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroNombre').addEventListener('input', aplicarFiltros);

        function aplicarFiltros() {
            const finca  = document.getElementById('filtroFinca').value;
            const rebano = document.getElementById('filtroRebano').value;
            const sexo   = document.getElementById('filtroSexo').value;
            const nombre = document.getElementById('filtroNombre').value.toLowerCase();

            let total = 0, machos = 0, hembras = 0, activos = 0;

            document.querySelectorAll('.fila-animal').forEach(function (row) {
                const ok = (!finca  || row.dataset.finca  === finca)
                        && (!rebano || row.dataset.rebano === rebano)
                        && (!sexo   || row.dataset.sexo   === sexo)
                        && (!nombre || row.dataset.nombre.includes(nombre));
                row.style.display = ok ? '' : 'none';
                if (ok) {
                    total++;
                    if (row.dataset.sexo === 'M') machos++; else hembras++;
                    // activo si no tiene badge archivado (usamos clase)
                    const badge = row.querySelector('td:nth-child(6) span');
                    if (badge && badge.textContent.trim() === 'Activo') activos++;
                }
            });

            document.getElementById('statTotal').textContent   = total;
            document.getElementById('statMachos').textContent  = machos;
            document.getElementById('statHembras').textContent = hembras;
            document.getElementById('statActivos').textContent = activos;
        }

        function limpiarFiltros() {
            document.getElementById('filtroFinca').value  = '';
            document.getElementById('filtroRebano').value = '';
            document.getElementById('filtroSexo').value   = '';
            document.getElementById('filtroNombre').value = '';
            // Mostrar todos los options del rebano
            Array.from(document.getElementById('filtroRebano').options).forEach(o => o.style.display = '');
            document.querySelectorAll('.fila-animal').forEach(r => r.style.display = '');
            // Reset stats
            aplicarFiltros();
        }

        // Aplicar filtros iniciales si vienen por URL
        @if($idFinca || $idRebano || $sexo || $nombre)
        document.addEventListener('DOMContentLoaded', function () {
            @if($idFinca)
            document.getElementById('filtroFinca').dispatchEvent(new Event('change'));
            @else
            aplicarFiltros();
            @endif
        });
        @endif
    </script>
@endsection