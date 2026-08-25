@extends('layouts.authenticated')

@section('title', 'Gestión de rebaños')

@section('content')
@php
    $totalRebanos = count($rebanos);
    $totalAnimales = array_sum(array_map(fn($r) => (int)($r['total_animales'] ?? count($r['animales'] ?? [])), $rebanos));
    $rebanosConAnimales = count(array_filter($rebanos, function($r) {
        return (int)($r['total_animales'] ?? count($r['animales'] ?? [])) > 0;
    }));
    
    // Fincas únicas
    $fincasUnicas = count(array_unique(array_filter(array_map(function($r) {
        return $r['finca_id'] ?? data_get($r, 'finca.id') ?? $r['id_Finca'] ?? null;
    }, $rebanos))));
@endphp

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🐄
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Gestión de rebaños
                </h1>
                <p class="text-gray-500 text-sm mt-1">Administración de agrupaciones, lotes y distribución de ganado por finca</p>
            </div>
        </div>
        <div>
            <a href="{{ route('rebanos.create') }}"
                class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-base inline-flex items-center gap-2">
                <span>+</span> Nuevo rebaño
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

    <!-- Summary KPIs (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total rebaños</p>
                <p id="statTotal" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $totalRebanos }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-2xl border border-blue-100">
                📊
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Animales asociados</p>
                <p id="statAnimales" class="text-3xl font-extrabold text-emerald-600">{{ $totalAnimales }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl border border-emerald-100">
                🐄
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rebaños con animales</p>
                <p id="statConAnimales" class="text-3xl font-extrabold text-purple-600">{{ $rebanosConAnimales }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl border border-purple-100">
                🏷️
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fincas con rebaños</p>
                <p id="statFincas" class="text-3xl font-extrabold text-amber-600">{{ $fincasUnicas }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl border border-amber-100">
                🏡
            </div>
        </div>
    </div>

    <!-- Filter Bar (4 Columnas) -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <!-- Buscar -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar rebaño</label>
                <input type="text" id="filtroNombre" value="{{ $nombre }}" placeholder="Nombre o código del rebaño..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Filtrar por Finca -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                <select id="filtroFinca"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @php
                            $fId = $finca['id'] ?? null;
                            $fNombre = $finca['nombre'] ?? ('Finca #' . $fId);
                        @endphp
                        <option value="{{ $fId }}" {{ (string) $fincaId === (string) $fId ? 'selected' : '' }}>
                            {{ $fNombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ocupación de Animales -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Ocupación</label>
                <select id="filtroOcupacion"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                    <option value="con_animales">Con animales asociados</option>
                    <option value="sin_animales">Rebaños vacíos</option>
                </select>
            </div>

            <!-- Botón Limpiar -->
            <div>
                <button type="button" onclick="limpiarFiltros()"
                    class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px] cursor-pointer shadow-2xs">
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Grid / Cards List -->
    <div id="cardsContainer">
        @if(count($rebanos) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gridRebanos">
                @foreach($rebanos as $rebano)
                    @php
                        $rebanoId = $rebano['id'] ?? $rebano['id_Rebano'] ?? null;
                        $rebanoNombre = $rebano['nombre'] ?? 'Rebaño';
                        $fincaObj = $rebano['finca'] ?? null;
                        $fincaIdAttr = (string)($rebano['finca_id'] ?? data_get($rebano, 'finca.id') ?? $rebano['id_Finca'] ?? '');
                        $fincaNombre = data_get($rebano, 'finca.nombre') ?? ($fincaObj['nombre'] ?? ('Finca #' . ($fincaIdAttr ?: 'N/A')));
                        $fincaTipo = data_get($rebano, 'finca.explotacion_tipo') ?? ($fincaObj['explotacion_tipo'] ?? 'General');
                        $animalesCount = (int)($rebano['total_animales'] ?? count($rebano['animales'] ?? []));
                        
                        $searchableText = strtolower(implode(' ', array_filter([
                            $rebanoNombre,
                            '#'.$rebanoId,
                            (string)$rebanoId,
                            $fincaNombre,
                            $fincaTipo
                        ])));
                    @endphp
                    <div class="bg-white border border-gray-100 hover:border-ganaderasoft-celeste/60 rounded-2xl p-6 shadow-xs hover:shadow-lg transition-all duration-200 flex flex-col justify-between fila-rebano group"
                        data-finca="{{ $fincaIdAttr }}" 
                        data-nombre="{{ $searchableText }}"
                        data-animales="{{ $animalesCount }}">
                        <div>
                            <!-- Header with icon -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1 pr-3">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-ganaderasoft-azul transition-colors leading-tight truncate">
                                            {{ $rebanoNombre }}
                                        </h3>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center font-medium">
                                        <span class="mr-1.5">🏡</span> {{ $fincaNombre }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 border border-teal-100 flex items-center justify-center text-2xl group-hover:scale-105 transition-transform shrink-0 shadow-2xs">
                                    🐄
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-2.5 py-3.5 border-t border-b border-gray-100 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500">ID del rebaño:</span>
                                    <span class="font-bold text-gray-900 font-mono">#{{ $rebanoId }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500">Explotación finca:</span>
                                    <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-semibold border border-blue-100">
                                        {{ $fincaTipo }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500">Animales asignados:</span>
                                    @if($animalesCount > 0)
                                        <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold border border-emerald-200 badge-animales">
                                            {{ $animalesCount }} {{ $animalesCount === 1 ? 'animal' : 'animales' }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-500 font-semibold badge-animales">
                                            0 animales
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2.5 mt-5 pt-3 border-t border-gray-100">
                            <a href="{{ route('animales.index', ['rebano_id' => $rebanoId]) }}"
                                class="flex-1 px-4 py-2.5 bg-ganaderasoft-celeste/15 hover:bg-ganaderasoft-azul text-ganaderasoft-azul hover:text-white rounded-xl text-sm font-bold flex items-center justify-center gap-2 transition-all duration-200 shadow-2xs">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>Ver animales ({{ $animalesCount }})</span>
                            </a>
                            <a href="{{ route('rebanos.edit', $rebanoId) }}"
                                class="p-2.5 bg-white border border-gray-200 hover:border-ganaderasoft-azul text-gray-600 hover:text-ganaderasoft-azul rounded-xl transition-all shadow-2xs flex items-center justify-center"
                                title="Editar rebaño">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center text-3xl shadow-xs">
                    🐄
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-gray-900">No hay rebaños registrados</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto">Comienza agregando un nuevo rebaño o lote para organizar los animales en tus fincas.</p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('rebanos.create') }}"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-xl hover:bg-opacity-90 transition-all font-semibold text-sm shadow-md hover:shadow-lg inline-flex items-center gap-2">
                        <span>+</span> Registrar nuevo rebaño
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Empty filtered state -->
    <div id="emptyFilteredState" class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center space-y-3">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-50 text-gray-500 border border-gray-200 flex items-center justify-center text-2xl shadow-2xs">
            🔍
        </div>
        <div class="space-y-1">
            <h4 class="text-base font-bold text-gray-900">No se encontraron rebaños</h4>
            <p class="text-sm text-gray-500 max-w-md mx-auto">No hay rebaños que coincidan con los filtros aplicados. Intenta con otros criterios de búsqueda.</p>
        </div>
        <div class="pt-2">
            <button type="button" onclick="limpiarFiltros()"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-colors shadow-2xs">
                Restablecer filtros
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtroFinca = document.getElementById('filtroFinca');
    const filtroNombre = document.getElementById('filtroNombre');
    const filtroOcupacion = document.getElementById('filtroOcupacion');
    const cardsContainer = document.getElementById('cardsContainer');
    const emptyFiltered = document.getElementById('emptyFilteredState');

    function recalcularKpis(visibles) {
        const statTotal = document.getElementById('statTotal');
        const statAnimales = document.getElementById('statAnimales');
        const statConAnimales = document.getElementById('statConAnimales');
        const statFincas = document.getElementById('statFincas');

        if (!statTotal) return;

        let totalRebanos = visibles.length;
        let totalAnimales = 0;
        let conAnimalesCount = 0;
        let fincasSet = new Set();

        visibles.forEach(row => {
            const count = parseInt(row.getAttribute('data-animales')) || 0;
            totalAnimales += count;
            if (count > 0) conAnimalesCount++;

            const fId = row.getAttribute('data-finca');
            if (fId) fincasSet.add(fId);
        });

        statTotal.textContent = totalRebanos;
        if (statAnimales) statAnimales.textContent = totalAnimales;
        if (statConAnimales) statConAnimales.textContent = conAnimalesCount;
        if (statFincas) statFincas.textContent = fincasSet.size;
    }

    function aplicarFiltros() {
        const finca = (filtroFinca ? filtroFinca.value : '').trim();
        const nombre = (filtroNombre ? filtroNombre.value : '').toLowerCase().trim();
        const ocupacion = (filtroOcupacion ? filtroOcupacion.value : '').trim();

        let visibleCount = 0;
        const visibleRows = [];

        document.querySelectorAll('.fila-rebano').forEach(function (row) {
            const rowFinca = (row.getAttribute('data-finca') || '').trim();
            const rowNombre = (row.getAttribute('data-nombre') || '').toLowerCase().trim();
            const rowAnimales = parseInt(row.getAttribute('data-animales')) || 0;

            const matchFinca = !finca || rowFinca === finca;
            const matchNombre = !nombre || rowNombre.includes(nombre);
            
            let matchOcupacion = true;
            if (ocupacion === 'con_animales') {
                matchOcupacion = rowAnimales > 0;
            } else if (ocupacion === 'sin_animales') {
                matchOcupacion = rowAnimales === 0;
            }

            const isVisible = matchFinca && matchNombre && matchOcupacion;

            row.style.display = isVisible ? '' : 'none';
            if (isVisible) {
                visibleCount++;
                visibleRows.push(row);
            }
        });

        if (emptyFiltered) {
            const totalRows = document.querySelectorAll('.fila-rebano').length;
            if (visibleCount === 0 && totalRows > 0) {
                emptyFiltered.classList.remove('hidden');
                if (cardsContainer) cardsContainer.classList.add('hidden');
            } else {
                emptyFiltered.classList.add('hidden');
                if (cardsContainer) cardsContainer.classList.remove('hidden');
            }
        }

        recalcularKpis(visibleRows);
    }

    filtroFinca?.addEventListener('change', aplicarFiltros);
    filtroNombre?.addEventListener('input', aplicarFiltros);
    filtroOcupacion?.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function () {
        if (filtroFinca) filtroFinca.value = '';
        if (filtroNombre) filtroNombre.value = '';
        if (filtroOcupacion) filtroOcupacion.value = '';
        aplicarFiltros();
    };

    // Aplicar filtros iniciales (si vienen predefinidos en la URL)
    aplicarFiltros();
});
</script>
@endsection