@extends('layouts.authenticated')

@section('title', 'Diagnósticos veterinarios')

@section('content')
@php
    $fincasDisponibles = [];
    $rebanosDisponibles = [];

    foreach($animales as $an) {
        $fId = $an['rebano']['finca']['id_Finca'] ?? ($an['rebano']['finca']['id'] ?? ($an['rebano']['id_Finca'] ?? ($an['finca_id'] ?? null)));
        $fNom = $an['rebano']['finca']['Nombre'] ?? ($an['rebano']['finca']['nombre'] ?? ($fId ? 'Finca #'.$fId : null));
        if ($fId && $fNom && !isset($fincasDisponibles[$fId])) {
            $fincasDisponibles[$fId] = $fNom;
        }

        $rId = $an['rebano']['id_Rebano'] ?? ($an['rebano']['id'] ?? ($an['id_Rebano'] ?? null));
        $rNom = $an['rebano']['Nombre'] ?? ($an['rebano']['nombre'] ?? ($rId ? 'Rebaño #'.$rId : null));
        if ($rId && $rNom && !isset($rebanosDisponibles[$rId])) {
            $rebanosDisponibles[$rId] = [
                'nombre' => $rNom,
                'finca_id' => $fId ? (string)$fId : ''
            ];
        }
    }

    foreach($diagnosticos as $dg) {
        $fId = data_get($dg, 'animal.rebano.finca.id_Finca') ?? data_get($dg, 'animal.rebano.finca.id') ?? data_get($dg, 'animal.rebano.id_Finca') ?? data_get($dg, 'animal.finca_id');
        $fNom = data_get($dg, 'animal.rebano.finca.Nombre') ?? data_get($dg, 'animal.rebano.finca.nombre') ?? ($fId ? 'Finca #'.$fId : null);
        if ($fId && $fNom && !isset($fincasDisponibles[$fId])) {
            $fincasDisponibles[$fId] = $fNom;
        }

        $rId = data_get($dg, 'animal.rebano.id_Rebano') ?? data_get($dg, 'animal.rebano.id') ?? data_get($dg, 'animal.id_Rebano');
        $rNom = data_get($dg, 'animal.rebano.Nombre') ?? data_get($dg, 'animal.rebano.nombre') ?? ($rId ? 'Rebaño #'.$rId : null);
        if ($rId && $rNom && !isset($rebanosDisponibles[$rId])) {
            $rebanosDisponibles[$rId] = [
                'nombre' => $rNom,
                'finca_id' => $fId ? (string)$fId : ''
            ];
        }
    }
    asort($fincasDisponibles);
@endphp

<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-xs">
                🩺
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Diagnósticos veterinarios</h1>
                <p class="text-gray-500 text-sm mt-1">Gestión clínica, historial de diagnósticos y estado sanitario de los animales</p>
            </div>
        </div>
        <a href="{{ route('diagnostico.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium gap-2">
            + Nuevo diagnóstico
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
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
            <!-- Filtro Unificado Buscar -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                <input type="text" id="filtroBuscar" placeholder="Animal, diagnóstico..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Filtro Finca -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                <select id="filtroFinca"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                    @foreach($fincasDisponibles as $fId => $fNom)
                        <option value="{{ $fId }}">{{ $fNom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro Rebaño -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                <select id="filtroRebano"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                    @foreach($rebanosDisponibles as $rId => $rData)
                        <option value="{{ $rId }}" data-finca-id="{{ $rData['finca_id'] }}">{{ $rData['nombre'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro Fecha Desde -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Desde</label>
                <input type="date" id="filtroFechaInicio" value="{{ $fechaInicio ?? '' }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Filtro Fecha Hasta -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Hasta</label>
                <input type="date" id="filtroFechaFin" value="{{ $fechaFin ?? '' }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Botón Limpiar Filtros -->
            <div>
                <button type="button" onclick="limpiarFiltros(event)"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition-colors text-sm flex items-center justify-center h-[42px] cursor-pointer"
                        title="Limpiar todos los filtros">
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de Diagnósticos -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($diagnosticos) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tablaDiagnosticos">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Etapa</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo de diagnóstico</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tratamientos</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($diagnosticos as $diagnostico)
                            @php
                                $id = $diagnostico['id'] ?? $diagnostico['diagnostico_id'] ?? null;
                                $animalIdVal = $diagnostico['animal_id'] ?? $diagnostico['fk_etapa_animal_anid'] ?? data_get($diagnostico, 'etapa_animal.animal_id') ?? '';
                                $animalRefId = data_get($diagnostico, 'animal.id') ?? data_get($diagnostico, 'animal.id_Animal') ?? data_get($diagnostico, 'etapa_animal.animal.id') ?? $animalIdVal;
                                $animalNombre = data_get($diagnostico, 'animal.Nombre') ?? data_get($diagnostico, 'animal.nombre') ?? data_get($diagnostico, 'etapa_animal.animal.nombre') ?? data_get($diagnostico, 'etapa_animal.animal.Nombre') ?? ('Animal #'.$animalIdVal);
                                
                                $etapaId = $diagnostico['etapa_id'] ?? $diagnostico['fk_etapa_animal_etid'] ?? data_get($diagnostico, 'etapa_animal.etapa_id') ?? '';
                                $etapaNombre = data_get($diagnostico, 'etapa_animal.etapa.nombre') ?? data_get($diagnostico, 'etapa_animal.etapa.etapa_nombre') ?? data_get($diagnostico, 'etapa.nombre') ?? data_get($diagnostico, 'etapa.etapa_nombre') ?? ($etapaId ? 'Etapa #'.$etapaId : 'Sin etapa');
                                
                                $tipoVal = $diagnostico['tipo'] ?? $diagnostico['diagnostico_tipo'] ?? 'General';
                                $fechaVal = $diagnostico['fecha'] ?? $diagnostico['diagnostico_fecha'] ?? null;
                                $fechaIso = '';
                                if (!empty($fechaVal)) {
                                    try {
                                        $fechaIso = \Carbon\Carbon::parse($fechaVal)->format('Y-m-d');
                                    } catch (\Exception $e) {
                                        $fechaIso = date('Y-m-d', strtotime($fechaVal));
                                    }
                                }
                                $descripcionVal = $diagnostico['descripcion'] ?? $diagnostico['diagnostico_descripcion'] ?? 'Sin descripción';
                                
                                $sexoVal = data_get($diagnostico, 'animal.sexo') ?? data_get($diagnostico, 'animal.Sexo') ?? data_get($diagnostico, 'etapa_animal.animal.sexo') ?? data_get($diagnostico, 'etapa_animal.animal.Sexo') ?? 'H';
                                $isMacho = in_array(strtoupper((string)$sexoVal), ['M', 'MACHO', 'MASCULINO']);
                                
                                $rebanoIdRow = data_get($diagnostico, 'animal.rebano.id_Rebano') ?? data_get($diagnostico, 'animal.rebano.id') ?? data_get($diagnostico, 'animal.id_Rebano') ?? data_get($diagnostico, 'etapa_animal.animal.id_Rebano') ?? '';
                                $fincaIdRow = data_get($diagnostico, 'animal.rebano.finca.id_Finca') ?? data_get($diagnostico, 'animal.rebano.finca.id') ?? data_get($diagnostico, 'animal.rebano.id_Finca') ?? data_get($diagnostico, 'animal.finca_id') ?? '';
                                $fincaNombreRow = data_get($diagnostico, 'animal.rebano.finca.Nombre') ?? data_get($diagnostico, 'animal.rebano.finca.nombre') ?? '';
                                $rebanoNombreRow = data_get($diagnostico, 'animal.rebano.Nombre') ?? data_get($diagnostico, 'animal.rebano.nombre') ?? '';

                                $tratamientosCount = isset($diagnostico['tratamientos']) && is_array($diagnostico['tratamientos']) ? count($diagnostico['tratamientos']) : 0;
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors fila-diagnostico"
                                data-animal-id="{{ strtolower((string)$animalRefId) }}"
                                data-animal-nombre="{{ strtolower($animalNombre) }}"
                                data-finca-id="{{ (string)$fincaIdRow }}"
                                data-rebano-id="{{ (string)$rebanoIdRow }}"
                                data-tipo="{{ strtolower($tipoVal) }}"
                                data-fecha="{{ $fechaIso }}"
                                data-descripcion="{{ strtolower($descripcionVal) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 shrink-0 rounded-xl {{ $isMacho ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-lg">
                                            {{ $isMacho ? '🐂' : '🐄' }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="text-xs text-gray-400">ID: #{{ $animalRefId }}</span>
                                                @if($rebanoNombreRow || $fincaNombreRow)
                                                    <span class="text-[10px] text-gray-400">•</span>
                                                    <span class="text-[11px] text-gray-500 font-medium truncate max-w-[130px]">
                                                        {{ $rebanoNombreRow ?: $fincaNombreRow }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $etapaNombre }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ $tipoVal }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                    {{ $fechaVal ? date('d/m/Y', strtotime($fechaVal)) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $descripcionVal }}">
                                    {{ $descripcionVal }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($tratamientosCount > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $tratamientosCount }} {{ $tratamientosCount === 1 ? 'tratamiento' : 'tratamientos' }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Sin tratamientos</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <div class="flex justify-center space-x-2">
                                        <!-- Botón de Ver Detalles -->
                                        <a href="{{ route('diagnostico.show', $id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                           title="Ver detalle del diagnóstico">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Editar -->
                                        <a href="{{ route('diagnostico.edit', $id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                           title="Editar diagnóstico">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Eliminar con Modal Oficial -->
                                        <form method="POST" action="{{ route('diagnostico.destroy', $id) }}" class="inline-block" id="form-delete-diagnostico-{{ $id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="openGenericConfirmModal({
                                                    formId: 'form-delete-diagnostico-{{ $id }}',
                                                    intent: 'danger',
                                                    title: 'Eliminar diagnóstico',
                                                    message: '¿Estás seguro de que deseas eliminar este diagnóstico veterinario? Esta acción no se puede deshacer.',
                                                    confirmText: 'Sí, eliminar'
                                                })"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors cursor-pointer"
                                                title="Eliminar diagnóstico">
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

                <!-- Mensaje de Sin Resultados Filtrados -->
                <div id="sinResultadosFiltro" class="hidden p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-2xl">
                        🔍
                    </div>
                    <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">No se encontraron diagnósticos</h4>
                    <p class="text-gray-500 text-xs mb-4">No hay registros que coincidan con los filtros de búsqueda aplicados.</p>
                    <button type="button" onclick="limpiarFiltros(event)"
                            class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-xs inline-flex items-center gap-1.5 cursor-pointer">
                        Limpiar filtros
                    </button>
                </div>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-3xl">
                    🩺
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay diagnósticos registrados</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando el primer diagnóstico clínico para tus animales.</p>
                <a href="{{ route('diagnostico.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-semibold text-sm">
                    + Nuevo diagnóstico
                </a>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtroBuscar = document.getElementById('filtroBuscar');
    const filtroFinca = document.getElementById('filtroFinca');
    const filtroRebano = document.getElementById('filtroRebano');
    const filtroFechaInicio = document.getElementById('filtroFechaInicio');
    const filtroFechaFin = document.getElementById('filtroFechaFin');
    const tabla = document.getElementById('tablaDiagnosticos');
    const sinResultados = document.getElementById('sinResultadosFiltro');
    const filas = document.querySelectorAll('.fila-diagnostico');

    // Almacenar las opciones originales de rebaños
    const listaRebanosOriginal = Array.from(filtroRebano?.options || []).map(opt => ({
        value: opt.value,
        text: opt.textContent.trim(),
        fincaId: (opt.dataset.fincaId || '').toString()
    }));

    function repopularRebanosPorFinca() {
        if (!filtroRebano) return;
        const fincaSeleccionada = (filtroFinca?.value || '').toString();
        const rebanoActual = filtroRebano.value;

        // Limpiar opciones
        filtroRebano.innerHTML = '';

        listaRebanosOriginal.forEach(r => {
            if (!r.value || !fincaSeleccionada || r.fincaId === fincaSeleccionada) {
                const opt = document.createElement('option');
                opt.value = r.value;
                opt.textContent = r.text;
                opt.dataset.fincaId = r.fincaId;
                if (r.value === rebanoActual) {
                    opt.selected = true;
                }
                filtroRebano.appendChild(opt);
            }
        });

        // Si la opción seleccionada no pertenece a la finca seleccionada, resetear a todos
        if (rebanoActual && !Array.from(filtroRebano.options).some(o => o.value === rebanoActual)) {
            filtroRebano.value = '';
        }
    }

    // Al cambiar finca -> filtra los rebaños
    filtroFinca?.addEventListener('change', function() {
        repopularRebanosPorFinca();
        aplicarFiltros();
    });

    // Al seleccionar un rebaño -> autoselecciona su finca asociada
    filtroRebano?.addEventListener('change', function() {
        if (filtroRebano.value && filtroFinca) {
            const opt = listaRebanosOriginal.find(r => r.value === filtroRebano.value);
            if (opt && opt.fincaId && filtroFinca.value !== opt.fincaId) {
                filtroFinca.value = opt.fincaId;
                repopularRebanosPorFinca();
            }
        }
        aplicarFiltros();
    });

    filtroBuscar?.addEventListener('input', aplicarFiltros);
    filtroFechaInicio?.addEventListener('change', aplicarFiltros);
    filtroFechaFin?.addEventListener('change', aplicarFiltros);
    filtroFechaInicio?.addEventListener('input', aplicarFiltros);
    filtroFechaFin?.addEventListener('input', aplicarFiltros);

    function aplicarFiltros() {
        const texto = (filtroBuscar?.value || '').toLowerCase().trim();
        const finca = (filtroFinca?.value || '').toString();
        const rebano = (filtroRebano?.value || '').toString();
        const fechaInicio = filtroFechaInicio?.value || '';
        const fechaFin = filtroFechaFin?.value || '';

        let visibles = 0;

        filas.forEach(function(row) {
            const rowAnimalNombre = (row.dataset.animalNombre || '').toLowerCase();
            const rowAnimalId = (row.dataset.animalId || '').toLowerCase();
            const rowFincaId = (row.dataset.fincaId || '').toString();
            const rowRebanoId = (row.dataset.rebanoId || '').toString();
            const rowTipo = (row.dataset.tipo || '').toLowerCase();
            const rowFecha = row.dataset.fecha || ''; // YYYY-MM-DD
            const rowDesc = (row.dataset.descripcion || '').toLowerCase();

            // Coincidencia con nombre del animal, ID, tipo de diagnóstico o descripción
            const matchTexto = !texto || 
                rowAnimalNombre.includes(texto) || 
                rowAnimalId.includes(texto) || 
                rowTipo.includes(texto) || 
                rowDesc.includes(texto);

            const matchFinca = !finca || rowFincaId === finca;
            const matchRebano = !rebano || rowRebanoId === rebano;
            const matchFechaInicio = !fechaInicio || (rowFecha && rowFecha >= fechaInicio);
            const matchFechaFin = !fechaFin || (rowFecha && rowFecha <= fechaFin);

            const isVisible = matchTexto && matchFinca && matchRebano && matchFechaInicio && matchFechaFin;

            if (isVisible) visibles++;
            row.style.display = isVisible ? '' : 'none';
        });

        if (sinResultados) {
            if (visibles === 0 && filas.length > 0) {
                sinResultados.classList.remove('hidden');
                if (tabla) tabla.querySelector('tbody').classList.add('hidden');
            } else {
                sinResultados.classList.add('hidden');
                if (tabla) tabla.querySelector('tbody').classList.remove('hidden');
            }
        }
    }

    window.limpiarFiltros = function (e) {
        if (e) e.preventDefault();
        if (filtroBuscar) filtroBuscar.value = '';
        if (filtroFinca) filtroFinca.value = '';
        if (filtroRebano) filtroRebano.value = '';
        if (filtroFechaInicio) filtroFechaInicio.value = '';
        if (filtroFechaFin) filtroFechaFin.value = '';

        repopularRebanosPorFinca();
        aplicarFiltros();
    };

    aplicarFiltros();
});
</script>
@endsection