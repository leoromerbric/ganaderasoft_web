@extends('layouts.authenticated')

@section('title', 'Control de celo')

@section('content')
@php
    $countTotal = count($registros);
    $mesActual = date('Y-m');
    $celosMes = 0;
    $hembrasUnicas = [];

    foreach ($registros as $r) {
        $f = $r['fecha'] ?? $r['celo_fecha'] ?? null;
        if ($f && str_starts_with($f, $mesActual)) {
            $celosMes++;
        }
        $anId = $r['animal_id'] ?? $r['celo_etapa_anid'] ?? data_get($r, 'etapa_animal.animal_id') ?? data_get($r, 'animal.id');
        if ($anId) {
            $hembrasUnicas[$anId] = true;
        }
    }
    $totalHembras = count($hembrasUnicas);
@endphp

<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2.5">
                <span>🌡️</span> Control de celo
            </h1>
            <p class="text-gray-500 text-sm mt-1">Seguimiento de ciclos estrales y preparación para servicios o inseminación</p>
        </div>
        <a href="{{ route('registro-celo.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
            + Registrar celo
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-xl shadow-sm flex items-center justify-between">
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

    <!-- Resumen KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total registros</p>
                <p id="statTotalCelos" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $countTotal }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl border border-orange-100">
                🌡️
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Hembras en celo</p>
                <p id="statTotalHembras" class="text-3xl font-extrabold text-pink-600">{{ $totalHembras }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-2xl border border-pink-100">
                🐄
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Celos este mes</p>
                <p id="statCelosMes" class="text-3xl font-extrabold text-emerald-600">{{ $celosMes }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl border border-emerald-100">
                📅
            </div>
        </div>
    </div>

    <!-- Barra de Filtros Reactiva (6 Columnas) -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <form method="GET" action="{{ route('registro-celo.index') }}" id="formFiltros" onsubmit="return false;">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                <!-- 1. Buscar hembra -->
                <div>
                    <label for="filtroBuscar" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar hembra</label>
                    <input type="text" id="filtroBuscar" placeholder="Nombre, código o ID..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                </div>

                <!-- 2. Finca -->
                <div>
                    <label for="filtroFinca" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                    <select id="filtroFinca" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                        <option value="">Todas las fincas</option>
                        @foreach($fincas as $finca)
                            @php
                                $fId = $finca['id'] ?? $finca['id_Finca'] ?? '';
                                $fNombre = $finca['nombre'] ?? $finca['Nombre'] ?? ('Finca #'.$fId);
                            @endphp
                            <option value="{{ $fId }}">{{ $fNombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Rebaño -->
                <div>
                    <label for="filtroRebano" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                    <select id="filtroRebano" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                        <option value="">Todos los rebaños</option>
                        @foreach($rebanos as $rebano)
                            @php
                                $rId = $rebano['id'] ?? $rebano['id_Rebano'] ?? '';
                                $rNombre = $rebano['nombre'] ?? $rebano['Nombre'] ?? ('Rebaño #'.$rId);
                                $rFincaId = $rebano['finca_id'] ?? $rebano['id_Finca'] ?? data_get($rebano, 'finca.id') ?? '';
                            @endphp
                            <option value="{{ $rId }}" data-finca-id="{{ $rFincaId }}">{{ $rNombre }}</option>
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
                    <a href="{{ route('registro-celo.index') }}" id="btnResetFilters"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition-colors text-sm h-[42px] flex items-center justify-center cursor-pointer shadow-xs"
                       title="Limpiar todos los filtros">
                        Limpiar filtros
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla Principal -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($registros) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tablaRegistrosCelo">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ejemplar</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Etapa productiva</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha de celo</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Observaciones</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Servicios</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
@php
    $etapasMap = [];
    foreach($etapas ?? [] as $e) {
        $eId = (string)($e['id'] ?? $e['etapa_id'] ?? '');
        $eNom = $e['nombre'] ?? $e['etapa_nombre'] ?? $e['Nombre'] ?? '';
        if ($eId && $eNom) {
            $etapasMap[$eId] = $eNom;
        }
    }
@endphp

                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tbodyCelo">
                        @foreach($registros as $registro)
                        @php
                            $cId = $registro['id'] ?? $registro['celo_id'] ?? null;
                            $animalId = $registro['animal_id'] ?? $registro['celo_etapa_anid'] ?? data_get($registro, 'etapa_animal.animal_id') ?? '';
                            $animalRefId = data_get($registro, 'animal.id') ?? data_get($registro, 'animal.id_Animal') ?? data_get($registro, 'etapa_animal.animal.id') ?? $animalId;
                            $animalNombre = data_get($registro, 'etapa_animal.animal.nombre') ?? data_get($registro, 'etapa_animal.animal.Nombre') ?? data_get($registro, 'animal.Nombre') ?? data_get($registro, 'animal.nombre') ?? ('Animal #'.$animalId);
                            $animalCodigo = data_get($registro, 'etapa_animal.animal.codigo_animal') ?? data_get($registro, 'animal.codigo_animal') ?? '';
                            $fechaCelo = $registro['fecha'] ?? $registro['celo_fecha'] ?? null;
                            $observacion = $registro['observacion'] ?? $registro['celo_observacon'] ?? null;

                            // Etapa
                            $etapaId = (string)(data_get($registro, 'etapa_animal.etapa.id') ?? data_get($registro, 'etapa_animal.etapa_id') ?? data_get($registro, 'etapa_id') ?? '');
                            $etapaNombre = data_get($registro, 'etapa_animal.etapa.nombre') 
                                ?? data_get($registro, 'etapa_animal.etapa.Nombre') 
                                ?? data_get($registro, 'etapa_animal.etapa.etapa_nombre') 
                                ?? data_get($registro, 'etapa.nombre') 
                                ?? ($etapaId && isset($etapasMap[$etapaId]) ? $etapasMap[$etapaId] : 'En producción');

                            // Ubicación
                            $rebanoId = (string) (data_get($registro, 'etapa_animal.animal.rebano.id') ?? data_get($registro, 'etapa_animal.animal.rebano.id_Rebano') ?? data_get($registro, 'animal.rebano.id') ?? data_get($registro, 'animal.rebano.id_Rebano') ?? data_get($registro, 'etapa_animal.animal.rebano_id') ?? data_get($registro, 'animal.rebano_id') ?? '');
                            $rebanoNombre = data_get($registro, 'etapa_animal.animal.rebano.nombre') ?? data_get($registro, 'etapa_animal.animal.rebano.Nombre') ?? data_get($registro, 'animal.rebano.Nombre') ?? data_get($registro, 'animal.rebano.nombre') ?? '';
                            $fincaId = (string) (data_get($registro, 'etapa_animal.animal.rebano.finca.id') ?? data_get($registro, 'etapa_animal.animal.rebano.finca.id_Finca') ?? data_get($registro, 'animal.rebano.finca.id') ?? data_get($registro, 'animal.rebano.finca.id_Finca') ?? data_get($registro, 'etapa_animal.animal.rebano.finca_id') ?? data_get($registro, 'animal.rebano.finca_id') ?? '');
                            $fincaNombre = data_get($registro, 'etapa_animal.animal.rebano.finca.nombre') ?? data_get($registro, 'etapa_animal.animal.rebano.finca.Nombre') ?? data_get($registro, 'animal.rebano.finca.Nombre') ?? data_get($registro, 'animal.rebano.finca.nombre') ?? '';

                            // Servicios asociados
                            $serviciosList = $registro['servicios'] ?? [];
                            $countServicios = is_array($serviciosList) ? count($serviciosList) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors celo-row"
                            data-finca-id="{{ $fincaId }}"
                            data-rebano-id="{{ $rebanoId }}"
                            data-animal-id="{{ $animalRefId }}"
                            data-fecha="{{ $fechaCelo ? date('Y-m-d', strtotime($fechaCelo)) : '' }}"
                            data-search-text="{{ strtolower($animalNombre.' '.$animalCodigo.' #'.$animalRefId.' '.$etapaNombre.' '.$observacion.' '.$rebanoNombre.' '.$fincaNombre) }}">
                            
                            <!-- Ejemplar -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-11 h-11 shrink-0 rounded-2xl bg-pink-50 text-pink-600 border border-pink-100 flex items-center justify-center font-bold text-xl shadow-xs">
                                        🐄
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

                            <!-- Etapa productiva -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs">
                                    🏷️ {{ $etapaNombre }}
                                </span>
                            </td>

                            <!-- Fecha de celo -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-0.5">
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ $fechaCelo ? date('d/m/Y', strtotime($fechaCelo)) : 'N/A' }}
                                    </p>
                                    @if($fechaCelo)
                                        @php
                                            $dias = (int) round((time() - strtotime($fechaCelo)) / 86400);
                                        @endphp
                                        <p class="text-xs text-gray-400">
                                            @if($dias === 0)
                                                Detectado hoy
                                            @elseif($dias === 1)
                                                Hace 1 día
                                            @elseif($dias > 1)
                                                Hace {{ $dias }} días
                                            @else
                                                Programado
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </td>

                            <!-- Observaciones -->
                            <td class="px-6 py-4">
                                @if($observacion)
                                    <p class="text-sm text-gray-800 line-clamp-2 max-w-sm" title="{{ $observacion }}">
                                        {{ $observacion }}
                                    </p>
                                @else
                                    <span class="text-xs text-gray-400 italic">Sin observaciones adicionales</span>
                                @endif
                            </td>

                            <!-- Servicios vinculados -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($countServicios > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        💉 {{ $countServicios }} {{ $countServicios === 1 ? 'servicio' : 'servicios' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                        Sin servicios
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="flex justify-center items-center space-x-2">
                                    <!-- Ver Detalles -->
                                    <a href="{{ route('registro-celo.show', $cId) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors shadow-xs"
                                       title="Ver detalle del celo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <!-- Editar -->
                                    <a href="{{ route('registro-celo.edit', $cId) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors shadow-xs"
                                       title="Editar registro">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Eliminar con Modal -->
                                    <form method="POST" action="{{ route('registro-celo.destroy', $cId) }}" class="inline-block" id="form-delete-celo-{{ $cId }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openGenericConfirmModal({
                                            formId: 'form-delete-celo-{{ $cId }}',
                                            intent: 'danger',
                                            title: 'Eliminar registro de celo',
                                            message: '¿Estás seguro de que deseas eliminar este registro de celo? Esta acción no se puede deshacer.',
                                            confirmText: 'Sí, eliminar'
                                        })"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors shadow-xs"
                                           title="Eliminar registro">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Estado Vacío por Filtros -->
            <div id="emptyStateFiltrado" class="hidden p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-2xl shadow-xs">
                    🔍
                </div>
                <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">No se encontraron registros de celo</h4>
                <p class="text-gray-500 text-xs mb-4">No hay eventos que coincidan con los criterios de búsqueda aplicados.</p>
                <button type="button" onclick="limpiarFiltros(event)"
                        class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-xs inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                    Limpiar filtros
                </button>
            </div>
        @else
            <!-- Estado Vacío Inicial -->
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 shadow-xs">
                    <span class="text-4xl">🌡️</span>
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay registros de celo</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando la primera detección de celo de tus hembras.</p>
                <a href="{{ route('registro-celo.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                    + Registrar celo
                </a>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const txtBuscar = document.getElementById('filtroBuscar');
    const selectFinca = document.getElementById('filtroFinca');
    const selectRebano = document.getElementById('filtroRebano');
    const fechaDesdeInput = document.getElementById('filtroFechaInicio');
    const fechaHastaInput = document.getElementById('filtroFechaFin');
    const btnReset = document.getElementById('btnResetFilters');

    const rows = Array.from(document.querySelectorAll('.celo-row'));
    const emptyFiltered = document.getElementById('emptyStateFiltrado');
    const tableContainer = document.getElementById('tablaRegistrosCelo');

    const rebanosData = @json($rebanos ?? []);
    const rebanoMap = {};
    rebanosData.forEach(r => {
        const rid = String(r.id || r.id_Rebano || '');
        const fid = String(r.finca_id || r.id_Finca || (r.finca && r.finca.id) || '');
        if (rid) {
            rebanoMap[rid] = fid;
        }
    });

    function poblarRebanosSegunFinca() {
        const fincaSeleccionada = selectFinca ? selectFinca.value : '';
        if (!selectRebano) return;

        Array.from(selectRebano.options).forEach(opt => {
            if (!opt.value) return;
            const rebanoFincaId = opt.dataset.fincaId || rebanoMap[opt.value] || '';
            if (!fincaSeleccionada || rebanoFincaId === fincaSeleccionada) {
                opt.hidden = false;
            } else {
                opt.hidden = true;
            }
        });

        if (selectRebano.selectedOptions[0] && selectRebano.selectedOptions[0].hidden) {
            selectRebano.value = '';
        }
    }

    function recalcularKpis(visibles) {
        const count = visibles.length;
        const statTotal = document.getElementById('statTotalCelos');
        const statHembras = document.getElementById('statTotalHembras');
        const statMes = document.getElementById('statCelosMes');

        const hembras = {};
        let celosEnMes = 0;
        const mesActual = new Date().toISOString().slice(0, 7);

        visibles.forEach(row => {
            const anId = row.dataset.animalId;
            if (anId) hembras[anId] = true;
            const f = row.dataset.fecha || '';
            if (f.startsWith(mesActual)) celosEnMes++;
        });

        if (statTotal) statTotal.textContent = count;
        if (statHembras) statHembras.textContent = Object.keys(hembras).length;
        if (statMes) statMes.textContent = celosEnMes;
    }

    function aplicarFiltros() {
        const query = txtBuscar ? txtBuscar.value.toLowerCase().trim() : '';
        const fincaId = selectFinca ? selectFinca.value : '';
        const rebanoId = selectRebano ? selectRebano.value : '';
        const fechaDesde = fechaDesdeInput ? fechaDesdeInput.value : '';
        const fechaHasta = fechaHastaInput ? fechaHastaInput.value : '';

        let visibleCount = 0;
        const visibleRows = [];

        rows.forEach(row => {
            const rowFinca = row.dataset.fincaId || '';
            const rowRebano = row.dataset.rebanoId || '';
            const rowFecha = row.dataset.fecha || '';
            const rowSearch = row.dataset.searchText || '';

            let match = true;

            // Búsqueda libre
            if (query && !rowSearch.includes(query)) {
                match = false;
            }

            // Finca
            if (match && fincaId && rowFinca !== fincaId) {
                match = false;
            }

            // Rebaño
            if (match && rebanoId && rowRebano !== rebanoId) {
                match = false;
            }

            // Fecha Desde
            if (match && fechaDesde && rowFecha) {
                if (rowFecha < fechaDesde) match = false;
            }

            // Fecha Hasta
            if (match && fechaHasta && rowFecha) {
                if (rowFecha > fechaHasta) match = false;
            }

            if (match) {
                row.style.display = '';
                visibleCount++;
                visibleRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });

        if (emptyFiltered) {
            if (visibleCount === 0 && rows.length > 0) {
                emptyFiltered.classList.remove('hidden');
                if (tableContainer) tableContainer.classList.add('hidden');
            } else {
                emptyFiltered.classList.add('hidden');
                if (tableContainer) tableContainer.classList.remove('hidden');
            }
        }

        recalcularKpis(visibleRows);
    }

    if (txtBuscar) txtBuscar.addEventListener('input', aplicarFiltros);

    if (selectFinca) {
        selectFinca.addEventListener('change', () => {
            poblarRebanosSegunFinca();
            aplicarFiltros();
        });
    }

    if (selectRebano) {
        selectRebano.addEventListener('change', () => {
            const selVal = selectRebano.value;
            if (selVal && selectFinca) {
                const opt = selectRebano.selectedOptions[0];
                const fid = (opt && opt.dataset.fincaId) ? opt.dataset.fincaId : (rebanoMap[selVal] || '');
                if (fid && selectFinca.value !== fid) {
                    selectFinca.value = fid;
                    poblarRebanosSegunFinca();
                    selectRebano.value = selVal;
                }
            }
            aplicarFiltros();
        });
    }

    if (fechaDesdeInput) fechaDesdeInput.addEventListener('change', aplicarFiltros);
    if (fechaHastaInput) fechaHastaInput.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        if (txtBuscar) txtBuscar.value = '';
        if (selectFinca) selectFinca.value = '';
        if (selectRebano) selectRebano.value = '';
        if (fechaDesdeInput) fechaDesdeInput.value = '';
        if (fechaHastaInput) fechaHastaInput.value = '';
        poblarRebanosSegunFinca();
        aplicarFiltros();
    };

    if (btnReset) {
        btnReset.addEventListener('click', (e) => {
            window.limpiarFiltros(e);
        });
    }

    poblarRebanosSegunFinca();
});
</script>
@endsection