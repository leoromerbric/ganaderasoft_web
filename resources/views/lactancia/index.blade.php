@extends('layouts.authenticated')

@section('title', 'Períodos de lactancia')

@section('content')
<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Períodos de lactancia</h1>
            <p class="text-gray-500 text-sm mt-1">Gestión, control y seguimiento de los ciclos de producción láctea del rebaño</p>
        </div>
        <a href="{{ route('lactancia.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
            + Nuevo período
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
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                <input type="text" id="filtroBuscar" placeholder="Nombre o código de hembra..."
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
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado del ciclo</label>
                <select id="filtroEstado" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ ($activa ?? null) === true ? 'selected' : '' }}>🟢 Solo activas</option>
                    <option value="0" {{ ($activa ?? null) === false ? 'selected' : '' }}>⚪ Solo finalizadas</option>
                </select>
            </div>
            <div>
                <a href="{{ route('lactancia.index') }}" onclick="limpiarFiltros(event)"
                   class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px]">
                    Limpiar filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Lactancias -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($lactancias) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tablaContenedor">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ejemplar</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha inicio</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha fin / secado</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaLactancias">
                        @foreach($lactancias as $lactancia)
                        @php
                            $lactanciaId = $lactancia['id'] ?? null;
                            $animalIdReg = $lactancia['animal_id'] ?? data_get($lactancia, 'animal.id') ?? '';
                            $rebanoId    = $lactancia['rebano_id'] ?? '';
                            $fincaId     = $lactancia['finca_id'] ?? '';
                            $animalNombre = $lactancia['animal_nombre'] ?? data_get($lactancia, 'animal.nombre') ?? 'Hembra no disponible';
                            $animalCodigo = $lactancia['animal_codigo'] ?? data_get($lactancia, 'animal.codigo_animal') ?? '';
                            $fechaInicio = $lactancia['fecha_inicio'] ?? null;
                            $fechaFin = $lactancia['fecha_fin'] ?? null;
                            $secado = $lactancia['secado'] ?? null;

                            $isActiva = empty($fechaFin) || (strtotime($fechaFin) && strtotime($fechaFin) > time());
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-lactancia"
                            data-animal-id="{{ $animalIdReg }}"
                            data-rebano-id="{{ $rebanoId }}"
                            data-finca-id="{{ $fincaId }}"
                            data-nombre="{{ strtolower($animalNombre) }}"
                            data-codigo="{{ strtolower($animalCodigo) }}"
                            data-activa="{{ $isActiva ? '1' : '0' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-pink-50 border border-pink-100 text-pink-600 font-bold flex items-center justify-center text-lg shadow-2xs">
                                        🐄
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                        <p class="text-xs text-gray-400 font-mono">
                                            {{ $animalCodigo ? '#'.$animalCodigo : 'ID: #'.$animalIdReg }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                                {{ $fechaInicio ? \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') : '--/--/----' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                @if($fechaFin)
                                    <span>{{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-emerald-700 font-semibold text-xs px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-100">En curso</span>
                                @endif
                                @if($secado)
                                    <p class="text-xs text-gray-400 mt-0.5">Secado: {{ \Carbon\Carbon::parse($secado)->format('d/m/Y') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isActiva)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        🟢 Activa
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                        ⚪ Finalizada
                                    </span>
                                @endif
                            </td>
                            <td class="w-1/5 px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="flex justify-center space-x-2">
                                    @if($lactanciaId)
                                        <!-- Botón de Ver Detalles -->
                                        <a href="{{ route('lactancia.show', $lactanciaId) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                           title="Ver detalle del período">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Editar -->
                                        <a href="{{ route('lactancia.edit', $lactanciaId) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                           title="Editar período">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Control de Leche (si está activa) -->
                                        @if($isActiva)
                                            <a href="{{ route('leche.index', ['lactancia_id' => $lactanciaId]) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white transition-colors"
                                               title="Registros de leche">
                                                🥛
                                            </a>
                                        @endif

                                        <!-- Botón de Eliminar con Modal -->
                                        <form method="POST" action="{{ route('lactancia.destroy', $lactanciaId) }}" class="inline-block" id="form-delete-lactancia-{{ $lactanciaId }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="openGenericConfirmModal({
                                                formId: 'form-delete-lactancia-{{ $lactanciaId }}',
                                                intent: 'danger',
                                                title: 'Eliminar período de lactancia',
                                                message: '¿Estás seguro de que deseas eliminar este ciclo de lactancia? Esta acción no se puede deshacer.',
                                                confirmText: 'Sí, eliminar'
                                            })"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                               title="Eliminar período">
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
                    <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">No se encontraron períodos de lactancia</h4>
                    <p class="text-gray-500 text-xs mb-4">No hay registros que coincidan con los filtros aplicados.</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay períodos de lactancia</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando el primer ciclo productivo de una hembra del rebaño.</p>
                <a href="{{ route('lactancia.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    + Nuevo período
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
    const est = document.getElementById('filtroEstado');
    const filas = document.querySelectorAll('.fila-lactancia');
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

    function aplicarFiltros() {
        const query = txtBuscar ? txtBuscar.value.toLowerCase().trim() : '';
        const sf = f ? f.value : '';
        const sr = r ? r.value : '';
        const valEst = est ? est.value : '';

        let totalVisibles = 0;

        filas.forEach(fila => {
            const rowAnId   = String(fila.dataset.animalId || '');
            const rowRebano = String(fila.dataset.rebanoId || '');
            const rowFinca  = String(fila.dataset.fincaId || '');
            const rowNombre = (fila.dataset.nombre || '').toLowerCase();
            const rowCodigo = (fila.dataset.codigo || '').toLowerCase();
            const rowActiva = fila.dataset.activa || '';

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

            if (visible && valEst !== '') {
                if (rowActiva !== valEst) visible = false;
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

    if (est) est.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function(e) {
        if (e) e.preventDefault();
        if (txtBuscar) txtBuscar.value = '';
        if (f) f.value = '';
        if (r) r.value = '';
        if (est) est.value = '';
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