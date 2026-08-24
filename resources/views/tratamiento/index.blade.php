@extends('layouts.authenticated')

@section('title', 'Tratamientos veterinarios')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center font-bold text-2xl shadow-xs">
                💊
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Tratamientos veterinarios
                </h1>
                <p class="text-gray-500 text-sm mt-1">Gestión y seguimiento de planes terapéuticos aplicados al rebaño</p>
            </div>
        </div>
        <div>
            <a href="{{ route('tratamiento.create') }}" 
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo tratamiento
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-lg">✅</span>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Filtros de búsqueda y fecha -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <form method="GET" action="{{ route('tratamiento.index') }}" id="filterForm">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
                <!-- 1. Búsqueda libre unificada -->
                <div>
                    <label for="filtroBusqueda" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                    <input type="text" id="filtroBusqueda"
                           placeholder="Animal, diagnóstico, plan..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                </div>

                <!-- 2. Finca -->
                <div>
                    <label for="filtroFinca" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                    <select id="filtroFinca"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                        <option value="">Todas las fincas</option>
                        @foreach($fincas as $finca)
                            @php
                                $fId = $finca['id'] ?? $finca['id_Finca'] ?? '';
                                $fNom = $finca['nombre'] ?? $finca['Nombre'] ?? ('Finca #'.$fId);
                            @endphp
                            @if($fId)
                                <option value="{{ $fId }}">{{ $fNom }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- 3. Rebaño -->
                <div>
                    <label for="filtroRebano" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                    <select id="filtroRebano"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                        <option value="">Todos los rebaños</option>
                        @foreach($rebanos as $rebano)
                            @php
                                $rId = $rebano['id'] ?? $rebano['id_Rebano'] ?? '';
                                $rNom = $rebano['nombre'] ?? $rebano['Nombre'] ?? ('Rebaño #'.$rId);
                                $rFincaId = $rebano['finca_id'] ?? $rebano['id_Finca'] ?? '';
                            @endphp
                            @if($rId)
                                <option value="{{ $rId }}" data-finca-id="{{ $rFincaId }}">{{ $rNom }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- 4. Fecha Desde -->
                <div>
                    <label for="filtroFechaInicio" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Desde</label>
                    <input type="date" name="fecha_inicio" id="filtroFechaInicio" value="{{ $fechaInicio }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                </div>

                <!-- 5. Fecha Hasta -->
                <div>
                    <label for="filtroFechaFin" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Hasta</label>
                    <input type="date" name="fecha_fin" id="filtroFechaFin" value="{{ $fechaFin }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                </div>

                <!-- 6. Botón Limpiar Filtros -->
                <div>
                    <a href="{{ route('tratamiento.index') }}" id="btnResetFilters"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition-colors text-sm h-[42px] flex items-center justify-center cursor-pointer shadow-xs"
                       title="Limpiar todos los filtros">
                        Limpiar filtros
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if(count($tratamientos) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Diagnóstico de origen</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan terapéutico</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Período de aplicación</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tratamientosTableBody">
                        @foreach($tratamientos as $tratamiento)
                        @php
                            $tId = $tratamiento['id'] ?? $tratamiento['tratamiento_id'] ?? null;
                            $tDiagId = $tratamiento['diagnostico_id'] ?? $tratamiento['tratamiento_diagnostico_id'] ?? '';
                            $tPlan = $tratamiento['plan'] ?? $tratamiento['tratamiento_plan'] ?? 'Sin descripción de plan';
                            $tFechaIni = $tratamiento['fecha_ini'] ?? $tratamiento['tratamiento_fecha_ini'] ?? null;
                            $tFechaFin = $tratamiento['fecha_fin'] ?? $tratamiento['tratamiento_fecha_fin'] ?? null;
                            $tDiagTipo = data_get($tratamiento, 'diagnostico.tipo') ?? data_get($tratamiento, 'diagnostico.diagnostico_tipo') ?? ($tDiagId ? 'Diagnóstico #'.$tDiagId : 'Diagnóstico no especificado');
                            $tDiagFecha = data_get($tratamiento, 'diagnostico.fecha') ?? data_get($tratamiento, 'diagnostico.diagnostico_fecha');

                            $animalId = data_get($tratamiento, 'diagnostico.animal_id') ?? data_get($tratamiento, 'diagnostico.fk_etapa_animal_anid') ?? data_get($tratamiento, 'diagnostico.etapa_animal.animal_id') ?? data_get($tratamiento, 'diagnostico.animal.id') ?? '';
                            $animalRefId = data_get($tratamiento, 'diagnostico.etapa_animal.animal.id') ?? data_get($tratamiento, 'diagnostico.animal.id') ?? data_get($tratamiento, 'diagnostico.animal.id_Animal') ?? $animalId;
                            $animalNombre = data_get($tratamiento, 'diagnostico.etapa_animal.animal.nombre') ?? data_get($tratamiento, 'diagnostico.etapa_animal.animal.Nombre') ?? data_get($tratamiento, 'diagnostico.animal.Nombre') ?? data_get($tratamiento, 'diagnostico.animal.nombre') ?? ($animalId ? 'Animal #'.$animalId : 'Animal no identificado');
                            $animalCodigo = data_get($tratamiento, 'diagnostico.etapa_animal.animal.codigo_animal') ?? data_get($tratamiento, 'diagnostico.animal.codigo_animal') ?? '';
                            
                            $rebanoId = (string) (data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano.id') ?? data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano.id_Rebano') ?? data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano_id') ?? data_get($tratamiento, 'diagnostico.animal.rebano.id') ?? data_get($tratamiento, 'diagnostico.animal.rebano.id_Rebano') ?? data_get($tratamiento, 'diagnostico.animal.rebano_id') ?? '');
                            $rebanoNombre = data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano.nombre') ?? data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano.Nombre') ?? data_get($tratamiento, 'diagnostico.animal.rebano.Nombre') ?? data_get($tratamiento, 'diagnostico.animal.rebano.nombre') ?? ($rebanoId ? 'Rebaño #'.$rebanoId : '');
                            
                            $fincaId = (string) (data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano.finca.id') ?? data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano.finca.id_Finca') ?? data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano.finca_id') ?? data_get($tratamiento, 'diagnostico.animal.rebano.finca.id') ?? data_get($tratamiento, 'diagnostico.animal.rebano.finca_id') ?? '');
                            $fincaNombre = data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano.finca.nombre') ?? data_get($tratamiento, 'diagnostico.etapa_animal.animal.rebano.finca.Nombre') ?? data_get($tratamiento, 'diagnostico.animal.rebano.finca.Nombre') ?? data_get($tratamiento, 'diagnostico.animal.rebano.finca.nombre') ?? ($fincaId ? 'Finca #'.$fincaId : '');

                            $sexoVal = data_get($tratamiento, 'diagnostico.etapa_animal.animal.sexo') ?? data_get($tratamiento, 'diagnostico.animal.sexo') ?? data_get($tratamiento, 'diagnostico.animal.Sexo') ?? 'H';
                            $isMacho = in_array(strtoupper((string)$sexoVal), ['M', 'MACHO', 'MASCULINO']);

                            // Cálculo del estado
                            $isActivo = false;
                            if ($tFechaFin) {
                                $isActivo = strtotime($tFechaFin) >= strtotime(date('Y-m-d'));
                            } elseif ($tFechaIni) {
                                $isActivo = true;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors tratamiento-row"
                            data-finca-id="{{ $fincaId }}"
                            data-rebano-id="{{ $rebanoId }}"
                            data-fecha-ini="{{ $tFechaIni ? date('Y-m-d', strtotime($tFechaIni)) : '' }}"
                            data-fecha-fin="{{ $tFechaFin ? date('Y-m-d', strtotime($tFechaFin)) : '' }}"
                            data-diag-id="{{ $tDiagId }}"
                            data-search-text="{{ strtolower($animalNombre.' '.$animalCodigo.' #'.$animalRefId.' '.$tDiagTipo.' '.$tPlan.' '.$rebanoNombre.' '.$fincaNombre) }}">
                            
                            <!-- Animal -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-11 h-11 shrink-0 rounded-2xl {{ $isMacho ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-xl shadow-xs">
                                        {{ $isMacho ? '🐂' : '🐄' }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            @if($animalCodigo)
                                                <span class="text-xs font-mono text-gray-500 font-semibold">#{{ $animalCodigo }}</span>
                                            @else
                                                <span class="text-xs font-mono text-gray-400">ID #{{ $animalRefId }}</span>
                                            @endif
                                            @if($rebanoNombre)
                                                <span class="text-[11px] text-gray-400">• {{ $rebanoNombre }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Diagnóstico -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        🩺 {{ $tDiagTipo }}
                                    </span>
                                    @if($tDiagFecha)
                                        <p class="text-xs text-gray-400">Emitido: {{ date('d/m/Y', strtotime($tDiagFecha)) }}</p>
                                    @endif
                                </div>
                            </td>

                            <!-- Plan terapéutico -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-800 line-clamp-2 max-w-sm" title="{{ $tPlan }}">
                                    {{ $tPlan }}
                                </p>
                            </td>

                            <!-- Período -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs space-y-1">
                                    <div class="flex items-center gap-1 text-gray-700">
                                        <span class="text-gray-400">Inicio:</span>
                                        <span class="font-bold">{{ $tFechaIni ? date('d/m/Y', strtotime($tFechaIni)) : 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-gray-700">
                                        <span class="text-gray-400">Fin:</span>
                                        <span class="font-semibold">{{ $tFechaFin ? date('d/m/Y', strtotime($tFechaFin)) : 'Indefinido' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($isActivo)
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        🟢 En curso
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                        ⚪ Concluido
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="flex justify-center items-center space-x-2">
                                    <!-- Ver detalle -->
                                    <a href="{{ route('tratamiento.show', $tId) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors shadow-xs"
                                       title="Ver detalle del tratamiento">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Editar -->
                                    <a href="{{ route('tratamiento.edit', $tId) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors shadow-xs"
                                       title="Editar tratamiento">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Eliminar -->
                                    <form method="POST" action="{{ route('tratamiento.destroy', $tId) }}" class="inline-block" id="form-delete-tratamiento-{{ $tId }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openGenericConfirmModal({
                                            formId: 'form-delete-tratamiento-{{ $tId }}',
                                            intent: 'danger',
                                            title: 'Eliminar tratamiento veterinario',
                                            message: '¿Estás seguro de que deseas eliminar este tratamiento? Esta acción no se puede deshacer.',
                                            confirmText: 'Sí, eliminar'
                                        })"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors shadow-xs"
                                           title="Eliminar tratamiento">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        
                        <!-- Fila dinámica cuando no hay resultados tras filtrar -->
                        <tr id="rowSinResultadosFiltro" class="hidden">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center text-2xl">
                                    🔍
                                </div>
                                <p class="text-base font-bold text-gray-700">No se encontraron tratamientos</p>
                                <p class="text-xs text-gray-400 mt-1">Intenta ajustar los criterios de búsqueda o limpiar los filtros.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-3xl shadow-xs">
                    💊
                </div>
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-1">No hay tratamientos registrados</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">Comienza prescribiendo el primer plan terapéutico para un animal diagnosticado.</p>
                <a href="{{ route('tratamiento.create') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo tratamiento
                </a>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputBusqueda  = document.getElementById('filtroBusqueda');
    const selectFinca    = document.getElementById('filtroFinca');
    const selectRebano   = document.getElementById('filtroRebano');
    const inputFechaIni  = document.getElementById('filtroFechaInicio');
    const inputFechaFin  = document.getElementById('filtroFechaFin');
    const rowSinResultados = document.getElementById('rowSinResultadosFiltro');
    const rows           = Array.from(document.querySelectorAll('.tratamiento-row'));

    // Guardar lista completa original de rebaños
    const listaRebanosOriginal = Array.from(selectRebano.options)
        .filter(opt => !!opt.value)
        .map(opt => ({
            id: opt.value,
            nombre: opt.textContent,
            fincaId: opt.dataset.fincaId || ''
        }));

    function repopularRebanosPorFinca(fincaSeleccionada) {
        const valorActual = selectRebano.value;
        selectRebano.innerHTML = '<option value="">Todos los rebaños</option>';

        listaRebanosOriginal
            .filter(r => !fincaSeleccionada || r.fincaId === fincaSeleccionada)
            .sort((a, b) => a.nombre.localeCompare(b.nombre))
            .forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.id;
                opt.textContent = r.nombre;
                opt.dataset.fincaId = r.fincaId;
                if (r.id === valorActual) {
                    opt.selected = true;
                }
                selectRebano.appendChild(opt);
            });
    }

    // Filtrado reactivo en tiempo real
    function filtrarFilas() {
        if (!rows.length) return;

        const query = (inputBusqueda?.value || '').trim().toLowerCase();
        const fincaId = (selectFinca?.value || '').trim();
        const rebanoId = (selectRebano?.value || '').trim();
        const fechaDesde = (inputFechaIni?.value || '').trim();
        const fechaHasta = (inputFechaFin?.value || '').trim();

        let visibleCount = 0;

        rows.forEach(row => {
            const rowSearchText = row.dataset.searchText || '';
            const rowFinca = row.dataset.fincaId || '';
            const rowRebano = row.dataset.rebanoId || '';
            const rowFechaIni = row.dataset.fechaIni || '';
            const rowFechaFin = row.dataset.fechaFin || '';

            // Criterios
            const matchesQuery = !query || rowSearchText.includes(query);
            const matchesFinca = !fincaId || rowFinca === fincaId;
            const matchesRebano = !rebanoId || rowRebano === rebanoId;
            
            let matchesFechaDesde = true;
            if (fechaDesde && rowFechaIni) {
                matchesFechaDesde = rowFechaIni >= fechaDesde;
            }

            let matchesFechaHasta = true;
            if (fechaHasta) {
                const fechaRowComp = rowFechaFin || rowFechaIni;
                if (fechaRowComp) {
                    matchesFechaHasta = fechaRowComp <= fechaHasta;
                }
            }

            const visible = matchesQuery && matchesFinca && matchesRebano && matchesFechaDesde && matchesFechaHasta;
            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        if (rowSinResultados) {
            rowSinResultados.classList.toggle('hidden', visibleCount > 0);
        }
    }

    // Event Listeners
    inputBusqueda?.addEventListener('input', filtrarFilas);

    selectFinca?.addEventListener('change', function () {
        repopularRebanosPorFinca(this.value);
        filtrarFilas();
    });

    selectRebano?.addEventListener('change', function () {
        const rebVal = this.value;
        if (rebVal) {
            const rebInfo = listaRebanosOriginal.find(r => r.id === rebVal);
            if (rebInfo && rebInfo.fincaId && selectFinca.value !== rebInfo.fincaId) {
                selectFinca.value = rebInfo.fincaId;
                repopularRebanosPorFinca(rebInfo.fincaId);
                selectRebano.value = rebVal;
            }
        }
        filtrarFilas();
    });

    inputFechaIni?.addEventListener('change', filtrarFilas);
    inputFechaFin?.addEventListener('change', filtrarFilas);

    // Filtrado inicial
    filtrarFilas();
});
</script>
@endpush
@endsection