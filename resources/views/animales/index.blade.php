@extends('layouts.authenticated')

@section('title', 'Gestión de Animales')

@section('content')
    <div class="space-y-8">
        <!-- Header section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de Animales</h1>
                <p class="text-gray-500 text-sm mt-1">Administración del inventario de ganado y registro por rebaños</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('animales.importar', ['finca_id' => $idFinca]) }}"
                   class="px-5 py-3 border border-ganaderasoft-verde-oscuro text-ganaderasoft-verde-oscuro hover:bg-ganaderasoft-verde-oscuro hover:text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md inline-flex items-center justify-center font-medium">
                    📥 Importar CSV / TXT
                </a>
                <a href="{{ route('animales.create') }}"
                   class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
                    + Nuevo Animal
                </a>
            </div>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                    <select id="filtroFinca"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todas las fincas</option>
                        @foreach($fincas as $finca)
                            <option value="{{ $finca['id'] }}" {{ $idFinca == $finca['id'] ? 'selected' : '' }}>
                                {{ $finca['nombre'] ?? 'Finca #'.$finca['id'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                    <select id="filtroRebano"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
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
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Sexo</label>
                    <select id="filtroSexo"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos</option>
                        <option value="M" {{ $sexo === 'M' ? 'selected' : '' }}>Macho</option>
                        <option value="H" {{ $sexo === 'H' ? 'selected' : '' }}>Hembra</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado</label>
                    <select id="filtroArchivado" onchange="cambiarFiltroArchivado(this.value)"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="activos" {{ ($archivado ?? 'activos') === 'activos' ? 'selected' : '' }}>Solo Activos</option>
                        <option value="archivados" {{ ($archivado ?? '') === 'archivados' ? 'selected' : '' }}>Solo Archivados</option>
                        <option value="todos" {{ ($archivado ?? '') === 'todos' ? 'selected' : '' }}>Todos los animales</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nombre / Código</label>
                    <input type="text" id="filtroNombre" value="{{ $nombre }}" placeholder="Ej: Lola, BOV-01..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
                <div>
                    <a href="{{ route('animales.index') }}"
                       class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                        Limpiar Filtros
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary KPIs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Listados</p>
                    <p id="statTotal" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $estadisticas['total'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                    🐄
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Machos</p>
                    <p id="statMachos" class="text-3xl font-extrabold text-blue-600">{{ $estadisticas['machos'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-2xl">
                    🐂
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Hembras</p>
                    <p id="statHembras" class="text-3xl font-extrabold text-pink-600">{{ $estadisticas['hembras'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-pink-50 flex items-center justify-center text-2xl">
                    🐄
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Activos</p>
                    <p id="statActivos" class="text-3xl font-extrabold text-emerald-600">{{ $estadisticas['activos'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-2xl">
                    ✅
                </div>
            </div>
        </div>

        <!-- Tabla de Animales -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            @if(count($animales) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Animal</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sexo</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rebaño</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nacimiento / Edad</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaAnimales">
                            @foreach($animales as $animal)
                                @php
                                    $rebanoId   = $animal['rebano']['id'] ?? ($animal['rebano_id'] ?? '');
                                    $fincaId    = $animal['rebano']['finca_id']  ?? ($mapaRebanoFinca[$rebanoId] ?? '');
                                    $isMacho    = ($animal['sexo'] ?? '') === 'M';
                                    $isArchivado = !empty($animal['archivado']);
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors fila-animal {{ $isArchivado ? 'bg-gray-50/50' : '' }}"
                                    data-rebano="{{ $rebanoId }}"
                                    data-finca="{{ $fincaId }}"
                                    data-sexo="{{ $animal['sexo'] ?? '' }}"
                                    data-nombre="{{ strtolower(($animal['nombre'] ?? '').' '.($animal['codigo_animal'] ?? '')) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-xl {{ $isMacho ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }} flex items-center justify-center font-bold text-lg">
                                                {{ $isMacho ? '🐂' : '🐄' }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $animal['nombre'] ?? 'Sin Nombre' }}</p>
                                                <p class="text-xs text-gray-400">ID: #{{ $animal['id'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">
                                        {{ $animal['codigo_animal'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $isMacho ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200' }}">
                                            {{ $isMacho ? 'Macho' : 'Hembra' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                                        {{ $animal['rebano']['nombre'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        <p class="font-medium text-gray-800">{{ isset($animal['fecha_nacimiento']) ? date('d/m/Y', strtotime($animal['fecha_nacimiento'])) : 'N/A' }}</p>
                                        @if(!empty($animal['edad_formateada']))
                                            <p class="text-xs text-gray-400">{{ $animal['edad_formateada'] }}</p>
                                        @elseif(!empty($animal['fecha_nacimiento']))
                                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($animal['fecha_nacimiento'])->diffForHumans(null, true) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($isArchivado)
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-200">Archivado</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Activo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex justify-center space-x-3 items-center">
                                            <a href="{{ route('animales.show', $animal['id']) }}"
                                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul font-semibold transition-colors inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Ver
                                            </a>
                                            <a href="{{ route('animales.edit', $animal['id']) }}"
                                               class="text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-semibold transition-colors inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </a>
                                            @if($isArchivado)
                                                <form action="{{ route('animales.restore', $animal['id']) }}" method="POST" class="inline" onsubmit="return confirm('¿Desea restaurar y reactivar este animal?');">
                                                    @csrf
                                                    <button type="submit" class="text-emerald-600 hover:text-emerald-800 font-semibold transition-colors inline-flex items-center">
                                                        <svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                        Restaurar
                                                    </button>
                                                </form>
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
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                        🐄
                    </div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay animales registrados</h3>
                    <p class="text-gray-500 text-sm mb-6">Comienza agregando tu primer animal al sistema</p>
                    <a href="{{ route('animales.create') }}"
                       class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
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
                    const badge = row.querySelector('td:nth-child(6) span');
                    if (badge && badge.textContent.trim() === 'Activo') activos++;
                }
            });

            document.getElementById('statTotal').textContent   = total;
            document.getElementById('statMachos').textContent  = machos;
            document.getElementById('statHembras').textContent = hembras;
            document.getElementById('statActivos').textContent = activos;
        }

        function cambiarFiltroArchivado(val) {
            const url = new URL(window.location.href);
            if (val && val !== 'activos') {
                url.searchParams.set('archivado', val);
            } else {
                url.searchParams.delete('archivado');
            }
            window.location.href = url.toString();
        }

        function limpiarFiltros() {
            window.location.href = "{{ route('animales.index') }}";
        }

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