@extends('layouts.authenticated')

@section('title', 'Medidas corporales')

@section('content')
<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Medidas corporales</h1>
            <p class="text-gray-500 text-sm mt-1">Evaluación biométrica, zoométrica y conformación morfológica del rebaño</p>
        </div>
        <a href="{{ route('medidas-corporales.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
            + Nueva evaluación
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar ejemplar</label>
                <input type="text" id="filtroBuscar" placeholder="Nombre o código del animal..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                <select id="filtroFinca" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                <select id="filtroRebano" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                </select>
            </div>
            <div>
                <a href="{{ route('medidas-corporales.index') }}" onclick="limpiarFiltros(event)"
                   class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px]">
                    Limpiar filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Medidas Corporales -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($medidasCorporales) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tablaContenedor">
                    <thead class="bg-gray-50">
                        <tr class="flex justify-between items-center w-full">
                            <th class="w-1/6 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ejemplar</th>
                            <th class="w-1/6 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="w-1/6 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Alturas (cruz / grupa)</th>
                            <th class="w-1/6 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Perímetros (tórax / caña)</th>
                            <th class="w-1/6 px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Longitudes (cuerpo / grupa)</th>
                            <th class="w-1/6 px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaMedidas">
                        @foreach($medidasCorporales as $medida)
                        @php
                            $medidaId = $medida['id'] ?? null;
                            $anIdRef  = $medida['animal_id_ref'] ?? $medida['animal_id'] ?? data_get($medida, 'animal.id') ?? '';
                            $anNombre = $medida['animal_nombre'] ?? 'Animal no disponible';
                            $anCodigo = $medida['animal_identificacion'] ?? '';
                            $fechaMedida = $medida['fecha_medicion'] ?? $medida['created_at'] ?? $medida['fecha'] ?? null;
                            
                            $alturaHc    = (float)($medida['altura_hc'] ?? 0);
                            $alturaHg    = (float)($medida['altura_hg'] ?? 0);
                            $perimetroPt = (float)($medida['perimetro_pt'] ?? 0);
                            $perimetroPca= (float)($medida['perimetro_pca'] ?? 0);
                            $longitudLc  = (float)($medida['longitud_lc'] ?? 0);
                            $longitudLg  = (float)($medida['longitud_lg'] ?? 0);
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-medida flex justify-between items-center w-full"
                            data-animal-id="{{ $anIdRef }}"
                            data-nombre="{{ strtolower($anNombre) }}"
                            data-codigo="{{ strtolower($anCodigo) }}"
                            data-altura-hc="{{ $alturaHc }}"
                            data-longitud-lc="{{ $longitudLc }}"
                            data-perimetro-pt="{{ $perimetroPt }}">
                            <td class="w-1/6 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 font-bold flex items-center justify-center text-lg">
                                        🐄
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-900 truncate">{{ $anNombre }}</p>
                                        <p class="text-xs text-gray-400 font-mono">
                                            {{ $anCodigo ? '#'.$anCodigo : 'ID: #'.$anIdRef }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="w-1/6 px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                                {{ $fechaMedida ? \Carbon\Carbon::parse($fechaMedida)->format('d/m/Y') : '--/--/----' }}
                            </td>
                            <td class="w-1/6 px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 inline-block">
                                        Hc: {{ $alturaHc > 0 ? number_format($alturaHc, 1).' cm' : '-' }}
                                    </span>
                                    <span class="text-xs text-gray-500 block">
                                        Hg: {{ $alturaHg > 0 ? number_format($alturaHg, 1).' cm' : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="w-1/6 px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-200 inline-block">
                                        Pt: {{ $perimetroPt > 0 ? number_format($perimetroPt, 1).' cm' : '-' }}
                                    </span>
                                    <span class="text-xs text-gray-500 block">
                                        Pca: {{ $perimetroPca > 0 ? number_format($perimetroPca, 1).' cm' : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="w-1/6 px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200 inline-block">
                                        Lc: {{ $longitudLc > 0 ? number_format($longitudLc, 1).' cm' : '-' }}
                                    </span>
                                    <span class="text-xs text-gray-500 block">
                                        Lg: {{ $longitudLg > 0 ? number_format($longitudLg, 1).' cm' : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="w-1/6 px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="flex justify-center space-x-2">
                                    @if($medidaId)
                                        <!-- Botón de Ver Detalles -->
                                        <a href="{{ route('medidas-corporales.show', $medidaId) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                           title="Ver detalle de evaluación">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Editar -->
                                        <a href="{{ route('medidas-corporales.edit', $medidaId) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                           title="Editar evaluación">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Eliminar con Modal -->
                                        <form method="POST" action="{{ route('medidas-corporales.destroy', $medidaId) }}" class="inline-block" id="form-delete-medida-{{ $medidaId }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="openGenericConfirmModal({
                                                formId: 'form-delete-medida-{{ $medidaId }}',
                                                intent: 'danger',
                                                title: 'Eliminar evaluación biométrica',
                                                message: '¿Estás seguro de que deseas eliminar este registro de medidas corporales? Esta acción no se puede deshacer.',
                                                confirmText: 'Sí, eliminar'
                                            })"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                               title="Eliminar evaluación">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
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
                    <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">No se encontraron evaluaciones morfométricas</h4>
                    <p class="text-gray-500 text-xs mb-4">No hay medidas corporales que coincidan con los filtros aplicados.</p>
                    <button type="button" onclick="limpiarFiltros(event)"
                            class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-xs inline-flex items-center gap-1.5 cursor-pointer">
                        Limpiar filtros
                    </button>
                </div>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay medidas corporales</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando la primera evaluación morfométrica de un ejemplar.</p>
                <a href="{{ route('medidas-corporales.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    + Nueva evaluación
                </a>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const txtBuscar = document.getElementById('filtroBuscar');
    const f = document.getElementById('filtroFinca');
    const r = document.getElementById('filtroRebano');
    const filas = document.querySelectorAll('.fila-medida');
    const tabla = document.getElementById('tablaContenedor');
    const sinResultados = document.getElementById('sinResultadosFiltro');

    const animalesData = @json($animales ?? []);
    const fM = {}, rM = {};

    animalesData.forEach(an => {
        const fi = an.rebano?.finca?.id || an.rebano?.finca_id;
        const fn = an.rebano?.finca?.nombre;
        const ri = an.rebano?.id || an.rebano_id;
        const rn = an.rebano?.nombre;
        if (fi && !fM[fi]) fM[fi] = fn || 'Finca #' + fi;
        if (ri && !rM[ri]) rM[ri] = { n: rn || 'Rebaño #' + ri, f: fi };
    });

    Object.keys(fM).sort((x, y) => fM[x].localeCompare(fM[y])).forEach(id => {
        const opt = document.createElement('option');
        opt.value = id;
        opt.textContent = fM[id];
        f.appendChild(opt);
    });

    function poblarRebanos(preserveId = null) {
        const sf = f.value;
        const prv = preserveId !== null ? preserveId : r.value;
        r.innerHTML = '<option value="">Todos los rebaños</option>';

        Object.keys(rM).forEach(id => {
            if (!sf || String(rM[id].f) === String(sf)) {
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = rM[id].n;
                r.appendChild(opt);
            }
        });

        if (prv && Array.from(r.options).some(o => String(o.value) === String(prv))) {
            r.value = String(prv);
        } else {
            r.value = '';
        }
    }

    function aplicarFiltros() {
        const query = txtBuscar ? txtBuscar.value.toLowerCase().trim() : '';
        const sf = f ? f.value : '';
        const sr = r ? r.value : '';

        const idsValidos = new Set();
        animalesData.forEach(an => {
            const fi = an.rebano?.finca?.id || an.rebano?.finca_id;
            const ri = an.rebano?.id || an.rebano_id;
            if ((!sf || String(fi) === String(sf)) && (!sr || String(ri) === String(sr))) {
                idsValidos.add(String(an.id));
            }
        });

        let totalVisibles = 0;

        filas.forEach(fila => {
            const rowAnId   = fila.dataset.animalId || '';
            const rowNombre = fila.dataset.nombre || '';
            const rowCodigo = fila.dataset.codigo || '';

            let visible = true;

            if (query !== '') {
                if (!rowNombre.includes(query) && !rowCodigo.includes(query)) {
                    visible = false;
                }
            }

            if (visible && (sf !== '' || sr !== '')) {
                if (!idsValidos.has(String(rowAnId))) visible = false;
            }

            if (visible) totalVisibles++;
            fila.style.display = visible ? '' : 'none';
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

    if (txtBuscar) txtBuscar.addEventListener('input', aplicarFiltros);
    
    if (f) {
        f.addEventListener('change', () => {
            poblarRebanos();
            aplicarFiltros();
        });
    }

    if (r) {
        r.addEventListener('change', () => {
            const selRebano = r.value;
            if (selRebano && rM[selRebano] && rM[selRebano].f) {
                const fincaAsociada = String(rM[selRebano].f);
                if (f.value !== fincaAsociada) {
                    f.value = fincaAsociada;
                    poblarRebanos(selRebano);
                }
            }
            aplicarFiltros();
        });
    }

    poblarRebanos();

    window.limpiarFiltros = function(e) {
        if (e) e.preventDefault();
        if (txtBuscar) txtBuscar.value = '';
        if (f) f.value = '';
        if (r) r.value = '';
        poblarRebanos();
        aplicarFiltros();
    };
});
</script>
@endsection