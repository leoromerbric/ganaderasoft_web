@extends('layouts.authenticated')

@section('title', 'Control de peso corporal')

@section('content')
<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Control de peso corporal</h1>
            <p class="text-gray-500 text-sm mt-1">Registro, monitoreo y seguimiento del desarrollo ponderal del ganado</p>
        </div>
        <a href="{{ route('peso-corporal.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
            + Nuevo pesaje
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                <input type="text" id="filtroBuscar" placeholder="Nombre o código..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                <select id="filtroFinca" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @php
                            $fId = $finca['id'] ?? $finca['id_Finca'] ?? '';
                            $fNom = $finca['nombre'] ?? $finca['Nombre'] ?? ('Finca #'.$fId);
                        @endphp
                        @if($fId)
                            <option value="{{ $fId }}" {{ (int)$fId === (int)($fincaId ?? 0) ? 'selected' : '' }}>{{ $fNom }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                <select id="filtroRebano" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                    <option value="">Todos los rebaños</option>
                    @foreach($rebanos as $rebano)
                        @php
                            $rId = $rebano['id'] ?? $rebano['id_Rebano'] ?? '';
                            $rNom = $rebano['nombre'] ?? $rebano['Nombre'] ?? ('Rebaño #'.$rId);
                            $rFinca = $rebano['finca_id'] ?? data_get($rebano, 'finca.id') ?? '';
                        @endphp
                        @if($rId)
                            <option value="{{ $rId }}" data-finca="{{ $rFinca }}" {{ (int)$rId === (int)($rebanoId ?? 0) ? 'selected' : '' }}>{{ $rNom }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Desde</label>
                <input type="date" id="filtroFechaInicio" value="{{ $fechaInicio }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Hasta</label>
                <input type="date" id="filtroFechaFin" value="{{ $fechaFin }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <a href="{{ route('peso-corporal.index') }}" onclick="limpiarFiltros(event)"
                   class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px]">
                    Limpiar filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Pesos Corporales -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($pesosCorporales) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tablaContenedor">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ejemplar</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha pesaje</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peso registrado</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Observaciones</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaPesos">
                        @foreach($pesosCorporales as $peso)
                        @php
                            $pesoId = $peso['id'] ?? null;
                            $animalId = $peso['animal_id'] ?? null;
                            $rebanoId = $peso['rebano_id'] ?? '';
                            $fincaId  = $peso['finca_id'] ?? '';
                            $animalNombre = $peso['animal_nombre'] ?? ('Animal #'.($animalId ?? 'N/A'));
                            $animalCodigo = $peso['animal_identificacion'] ?? '';
                            $fechaPeso = $peso['fecha_peso'] ?? null;
                            $comentario = $peso['comentario'] ?? null;
                            $valorPeso = (float) ($peso['peso'] ?? 0);
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-peso"
                            data-animal-id="{{ $animalId }}"
                            data-rebano-id="{{ $rebanoId }}"
                            data-finca-id="{{ $fincaId }}"
                            data-nombre="{{ strtolower($animalNombre) }}"
                            data-codigo="{{ strtolower($animalCodigo) }}"
                            data-fecha="{{ $fechaPeso ? substr($fechaPeso, 0, 10) : '' }}"
                            data-peso="{{ $valorPeso }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 font-bold flex items-center justify-center text-lg shadow-2xs">
                                        🐄
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                        <p class="text-xs text-gray-500 font-mono">
                                            {{ $animalCodigo ? '#'.$animalCodigo : 'ID: #'.($animalId ?? 'N/A') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                                {{ $fechaPeso ? \Carbon\Carbon::parse($fechaPeso)->format('d/m/Y') : '--/--/----' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ⚖️ {{ number_format($valorPeso, 2, ',', '.') }} kg
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium">
                                {{ $comentario ?: 'Sin observaciones' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="flex justify-center space-x-2">
                                    @if($pesoId)
                                        <!-- Botón de Editar -->
                                        <a href="{{ route('peso-corporal.edit', $pesoId) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                           title="Editar pesaje">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Eliminar con Modal -->
                                        <form method="POST" action="{{ route('peso-corporal.destroy', $pesoId) }}" class="inline-block" id="form-delete-{{ $pesoId }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="openGenericConfirmModal({
                                                formId: 'form-delete-{{ $pesoId }}',
                                                intent: 'danger',
                                                title: 'Eliminar pesaje',
                                                message: '¿Estás seguro de que deseas eliminar este registro de peso corporal? Esta acción no se puede deshacer.',
                                                confirmText: 'Sí, eliminar'
                                            })"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                               title="Eliminar pesaje">
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
                    <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">No se encontraron registros de peso</h4>
                    <p class="text-gray-500 text-xs mb-4">No hay pesajes que coincidan con los filtros aplicados.</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay registros de peso</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza guardando el primer pesaje corporal de un ejemplar.</p>
                <a href="{{ route('peso-corporal.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    + Nuevo pesaje
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
    const fInicio = document.getElementById('filtroFechaInicio');
    const fFin = document.getElementById('filtroFechaFin');
    const filas = document.querySelectorAll('.fila-peso');
    const tabla = document.getElementById('tablaContenedor');
    const sinResultados = document.getElementById('sinResultadosFiltro');

    function filterSelectRebanos(fincaId) {
        if (!r) return;
        Array.from(r.options).forEach((opt, idx) => {
            if (idx === 0) return;
            const matches = !fincaId || opt.dataset.finca === fincaId;
            opt.style.display = matches ? '' : 'none';
        });
        if (r.value && r.options[r.selectedIndex]?.style.display === 'none') {
            r.value = '';
        }
    }

    function recalcularEstadisticas() {
        const visibleRows = Array.from(filas).filter(row => row.style.display !== 'none');
        const count = visibleRows.length;
        const pesos = visibleRows.map(row => parseFloat(row.dataset.peso || 0)).filter(p => !isNaN(p) && p > 0);

        const totalPesos = pesos.reduce((acc, p) => acc + p, 0);
        const avg = pesos.length > 0 ? (totalPesos / pesos.length) : 0;
        const max = pesos.length > 0 ? Math.max(...pesos) : 0;
        const min = pesos.length > 0 ? Math.min(...pesos) : 0;

        const statTotal = document.getElementById('statTotalRegistros');
        const statProm = document.getElementById('statPesoPromedio');
        const statMax = document.getElementById('statPesoMaximo');
        const statMin = document.getElementById('statPesoMinimo');

        if (statTotal) statTotal.textContent = count;
        if (statProm) statProm.textContent = avg.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' kg';
        if (statMax) statMax.textContent = max.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' kg';
        if (statMin) statMin.textContent = min.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' kg';
    }

    function aplicarFiltros() {
        const query = txtBuscar ? txtBuscar.value.toLowerCase().trim() : '';
        const sf = f ? f.value : '';
        const sr = r ? r.value : '';
        const d1 = fInicio ? fInicio.value : '';
        const d2 = fFin ? fFin.value : '';

        let totalVisibles = 0;

        filas.forEach(fila => {
            const rowAnId   = String(fila.dataset.animalId || '');
            const rowRebano = String(fila.dataset.rebanoId || '');
            const rowFinca  = String(fila.dataset.fincaId || '');
            const rowNombre = (fila.dataset.nombre || '').toLowerCase();
            const rowCodigo = (fila.dataset.codigo || '').toLowerCase();
            const rowFecha  = fila.dataset.fecha || '';

            let visible = true;

            if (query !== '') {
                if (!rowNombre.includes(query) && !rowCodigo.includes(query) && !rowAnId.includes(query)) {
                    visible = false;
                }
            }

            if (visible && sf !== '') {
                if (rowFinca !== String(sf)) visible = false;
            }

            if (visible && sr !== '') {
                if (rowRebano !== String(sr)) visible = false;
            }

            if (visible && d1 && rowFecha && rowFecha < d1) visible = false;
            if (visible && d2 && rowFecha && rowFecha > d2) visible = false;

            if (visible) totalVisibles++;
            fila.style.display = visible ? '' : 'none';
        });

        recalcularEstadisticas();

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
        f.addEventListener('change', function () {
            filterSelectRebanos(this.value);
            aplicarFiltros();
        });
    }

    if (r) {
        r.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.dataset.finca && f && !f.value) {
                f.value = selectedOpt.dataset.finca;
                filterSelectRebanos(selectedOpt.dataset.finca);
            }
            aplicarFiltros();
        });
    }

    if (fInicio) fInicio.addEventListener('change', aplicarFiltros);
    if (fFin) fFin.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function(e) {
        if (e) e.preventDefault();
        if (txtBuscar) txtBuscar.value = '';
        if (f) f.value = '';
        if (r) r.value = '';
        if (fInicio) fInicio.value = '';
        if (fFin) fFin.value = '';
        filterSelectRebanos('');
        if (window.history && window.history.replaceState) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        aplicarFiltros();
    };

    if (f && f.value) {
        const currentReb = r ? r.value : '';
        filterSelectRebanos(f.value);
        if (currentReb && r) r.value = currentReb;
    }

    aplicarFiltros();
});
</script>
@endsection