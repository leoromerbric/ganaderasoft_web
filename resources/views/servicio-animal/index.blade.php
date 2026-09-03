@extends('layouts.authenticated')

@section('title', 'Servicios reproductivos')

@section('content')
@php
    $totalServicios = count($servicios);
    $totalIA = count(array_filter($servicios, function($s) {
        $t = strtolower((string)($s['tipo'] ?? $s['servicio_tipo'] ?? ''));
        return str_contains($t, 'ia') || str_contains($t, 'inseminacion') || str_contains($t, 'inseminación') || str_contains($t, 'artificial');
    }));
    $totalMonta = count(array_filter($servicios, function($s) {
        $t = strtolower((string)($s['tipo'] ?? $s['servicio_tipo'] ?? ''));
        return str_contains($t, 'monta') || str_contains($t, 'natural') || str_contains($t, 'directa');
    }));
    $currentMonth = date('Y-m');
    $esteMes = count(array_filter($servicios, function($s) use ($currentMonth) {
        $f = $s['fecha'] ?? $s['servicio_fecha'] ?? '';
        return str_starts_with((string)$f, $currentMonth);
    }));
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-blue-100 shrink-0">
                🐂
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Servicios reproductivos
                </h1>
                <p class="text-gray-500 text-sm mt-1">Gestión de inseminaciones artificiales, montas naturales y control reproductivo</p>
            </div>
        </div>
        <div>
            <a href="{{ route('servicio-animal.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-base inline-flex items-center gap-2">
                <span>+</span> Registrar servicio
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

    <!-- Resumen KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total servicios</p>
                <p id="statTotalServicios" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $totalServicios }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gray-50 text-gray-700 flex items-center justify-center text-2xl border border-gray-100">
                📊
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Inseminación (IA)</p>
                <p id="statTotalIA" class="text-3xl font-extrabold text-blue-600">{{ $totalIA }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl border border-blue-100">
                🧬
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Monta natural</p>
                <p id="statTotalMonta" class="text-3xl font-extrabold text-emerald-600">{{ $totalMonta }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl border border-emerald-100">
                🐂
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Servicios este mes</p>
                <p id="statServiciosMes" class="text-3xl font-extrabold text-purple-600">{{ $esteMes }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl border border-purple-100">
                📅
            </div>
        </div>
    </div>

    <!-- Filters Bar (Formato Vacunación: 2 Filas x 4 Columnas) -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="space-y-4">
            <!-- Fila 1: Buscar, Tipo, Finca, Rebaño -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                    <input type="text" id="filtroTexto" placeholder="Animal, código, lote..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipo de servicio</label>
                    <select id="filtroTipo"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los tipos</option>
                        <option value="ia">Inseminación (IA)</option>
                        <option value="natural">Monta natural</option>
                        <option value="te">Transferencia de embriones (TE)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                    <select id="filtroFinca"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
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

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                    <select id="filtroRebano"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los rebaños</option>
                        @foreach($rebanos as $rebano)
                            @php
                                $rId = $rebano['id'] ?? $rebano['id_Rebano'] ?? '';
                                $rNombre = $rebano['nombre'] ?? $rebano['Nombre'] ?? ('Rebaño #'.$rId);
                                $rFincaId = data_get($rebano, 'finca_id') ?? data_get($rebano, 'finca.id') ?? data_get($rebano, 'id_Finca') ?? '';
                            @endphp
                            <option value="{{ $rId }}" data-finca-id="{{ $rFincaId }}">{{ $rNombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Fila 2: Fecha Desde, Fecha Hasta, Estado de Animal, Limpiar Filtros -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end pt-3 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Fecha desde</label>
                    <input type="date" id="filtroFechaInicio"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Fecha hasta</label>
                    <input type="date" id="filtroFechaFin"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado de animal</label>
                    <select id="filtroArchivado"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los estados</option>
                        <option value="activo">Solo activos</option>
                        <option value="archivado">Solo archivados</option>
                    </select>
                </div>

                <div>
                    <a href="javascript:void(0)" id="btnResetFilters"
                       class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px] cursor-pointer shadow-2xs">
                        Limpiar filtros
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Principal -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100" id="tableContainer">
        @if(count($servicios) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tablaServicios">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hembra receptora</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo de servicio</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Semen / Donante</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Técnico / Responsable</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Celo asociado</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($servicios as $servicio)
                        @php
                            $sId = $servicio['id'] ?? $servicio['servicio_id'] ?? null;
                            $animalId = $servicio['animal_id'] ?? $servicio['servicio_id_Animal'] ?? data_get($servicio, 'etapa_animal.animal_id') ?? '';
                            $animalRefId = data_get($servicio, 'animal.id') ?? data_get($servicio, 'animal.id_Animal') ?? $animalId;
                            $animalNombre = data_get($servicio, 'animal.Nombre') ?? data_get($servicio, 'animal.nombre') ?? ('Animal #'.$animalId);
                            $animalCodigo = data_get($servicio, 'animal.codigo_animal') ?? data_get($servicio, 'animal.Codigo') ?? '';
                            
                            $fincaId = (string)(data_get($servicio, 'animal.rebano.finca.id') ?? data_get($servicio, 'animal.rebano.finca.id_Finca') ?? data_get($servicio, 'animal.rebano.finca_id') ?? '');
                            $fincaNombre = data_get($servicio, 'animal.rebano.finca.Nombre') ?? data_get($servicio, 'animal.rebano.finca.nombre') ?? '';
                            
                            $rebanoId = (string)(data_get($servicio, 'animal.rebano.id') ?? data_get($servicio, 'animal.rebano.id_Rebano') ?? data_get($servicio, 'animal.rebano_id') ?? '');
                            $rebanoNombre = data_get($servicio, 'animal.rebano.Nombre') ?? data_get($servicio, 'animal.rebano.nombre') ?? '';
                            
                            $tipoServicio = $servicio['tipo'] ?? $servicio['servicio_tipo'] ?? 'Servicio';
                            $fecha = $servicio['fecha'] ?? $servicio['servicio_fecha'] ?? null;
                            
                            $semenId = $servicio['semen_toro_id'] ?? $servicio['semen_id'] ?? $servicio['servicio_semen_id'] ?? null;
                            $semenNombre = data_get($servicio, 'semen.toro.Nombre') ?? data_get($servicio, 'semen.toro.nombre') ?? data_get($servicio, 'semen.descripcion') ?? data_get($servicio, 'semen.codigo') ?? ($semenId ? 'Semen #'.$semenId : null);
                            
                            $tecnicoId = $servicio['personal_finca_id'] ?? $servicio['tecnico_id'] ?? $servicio['servicio_id_Tecnico'] ?? null;
                            $tecnicoNombre = data_get($servicio, 'tecnico.Nombre') ?? data_get($servicio, 'tecnico.persona.nombre') ?? data_get($servicio, 'tecnico.persona.Nombre') ?? data_get($servicio, 'tecnico.Nombre_Completo') ?? ($tecnicoId ? 'Personal #'.$tecnicoId : null);
                            
                            $celoId = $servicio['registro_celo_id'] ?? $servicio['celo_id'] ?? $servicio['servicio_celo_id'] ?? null;
                            $celoFecha = data_get($servicio, 'registro_celo.fecha') ?? data_get($servicio, 'registroCelo.fecha') ?? null;
                            
                            $isIA = str_contains(strtolower((string)$tipoServicio), 'ia') || str_contains(strtolower((string)$tipoServicio), 'inseminaci') || str_contains(strtolower((string)$tipoServicio), 'artific');

                            $isArchivado = (bool)(data_get($servicio, 'animal.archivado') ?? data_get($servicio, 'animal.is_archivado') ?? false);

                            $searchString = strtolower(implode(' ', array_filter([
                                $animalNombre,
                                $animalCodigo,
                                '#'.$animalCodigo,
                                (string)$animalRefId,
                                '#'.$animalRefId,
                                $semenNombre,
                                $tecnicoNombre,
                                $tipoServicio,
                                $rebanoNombre,
                                $fincaNombre
                            ])));
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-servicio" 
                            data-texto="{{ $searchString }}"
                            data-tipo="{{ strtolower($tipoServicio) }}"
                            data-finca="{{ $fincaId }}"
                            data-rebano="{{ $rebanoId }}"
                            data-fecha="{{ $fecha ? date('Y-m-d', strtotime($fecha)) : '' }}"
                            data-archivado="{{ $isArchivado ? 'archivado' : 'activo' }}">
                            
                            <!-- Hembra Receptora -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-pink-50 text-pink-600 border border-pink-100 flex items-center justify-center font-bold text-lg shadow-2xs">
                                        🐄
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                        <div class="flex items-center gap-1.5 text-xs text-gray-500 font-mono">
                                            @if($animalCodigo)
                                                <span class="bg-gray-100 text-gray-700 px-1.5 py-0.2 rounded font-semibold">#{{ $animalCodigo }}</span>
                                            @else
                                                <span>ID: #{{ $animalRefId }}</span>
                                            @endif
                                            @if($rebanoNombre)
                                                <span class="truncate">• {{ $rebanoNombre }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Tipo de Servicio -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isIA)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        🧬 Inseminación (IA)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        🐂 Monta natural
                                    </span>
                                @endif
                            </td>

                            <!-- Semen / Donante -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($semenNombre)
                                    <div class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                        <span>🧬</span> {{ $semenNombre }}
                                    </div>
                                    @if($semenId)
                                        <span class="text-xs font-mono text-gray-400">Pajuela #{{ $semenId }}</span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 italic">No especificado</span>
                                @endif
                            </td>

                            <!-- Técnico -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tecnicoNombre)
                                    <p class="text-sm font-medium text-gray-900 flex items-center gap-1.5">
                                        <span>👨‍⚕️</span> {{ $tecnicoNombre }}
                                    </p>
                                @else
                                    <span class="text-xs text-gray-400 italic">No asignado</span>
                                @endif
                            </td>

                            <!-- Fecha -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'S/F' }}
                            </td>

                            <!-- Celo Asociado -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($celoId)
                                    <a href="{{ route('registro-celo.show', $celoId) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 transition-colors shadow-2xs"
                                       title="Ver registro de celo">
                                        <span>🔥</span> Celo #{{ $celoId }}
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Sin celo previo</span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Botón Ver Detalles -->
                                    <a href="{{ route('servicio-animal.show', $sId) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                       title="Ver detalle del servicio">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Botón Editar -->
                                    <a href="{{ route('servicio-animal.edit', $sId) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                       title="Editar servicio">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Botón Eliminar con Modal -->
                                    <form method="POST" action="{{ route('servicio-animal.destroy', $sId) }}" class="inline-block" id="form-delete-servicio-{{ $sId }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openGenericConfirmModal({
                                            formId: 'form-delete-servicio-{{ $sId }}',
                                            intent: 'danger',
                                            title: 'Eliminar servicio reproductivo',
                                            message: '¿Estás seguro de que deseas eliminar este registro de servicio animal? Esta acción no se puede revertir.',
                                            confirmText: 'Sí, eliminar'
                                        })"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                           title="Eliminar servicio">
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
        @else
            <div class="p-12 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-3xl shadow-xs">
                    🐂
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-gray-900">No hay servicios reproductivos registrados</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto">Comienza registrando la primera inseminación artificial o monta natural para el control reproductivo del rebaño.</p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('servicio-animal.create') }}"
                       class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-xl hover:bg-opacity-90 transition-all font-semibold text-sm shadow-md hover:shadow-lg inline-flex items-center gap-2">
                        <span>+</span> Registrar nuevo servicio
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Estado vacío si el filtrado no arroja resultados -->
    <div id="emptyFilteredState" class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center space-y-3">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-50 text-gray-500 border border-gray-200 flex items-center justify-center text-2xl shadow-2xs">
            🔍
        </div>
        <div class="space-y-1">
            <h4 class="text-base font-bold text-gray-900">No se encontraron servicios reproductivos</h4>
            <p class="text-sm text-gray-500 max-w-md mx-auto">No hay registros que coincidan con los filtros aplicados. Intenta cambiar los criterios de búsqueda.</p>
        </div>
        <div class="pt-2">
            <button type="button" onclick="window.limpiarFiltros(event)"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-colors shadow-2xs">
                Restablecer filtros
            </button>
        </div>
    </div>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtroTexto = document.getElementById('filtroTexto');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroFinca = document.getElementById('filtroFinca');
    const filtroRebano = document.getElementById('filtroRebano');
    const filtroFechaInicio = document.getElementById('filtroFechaInicio');
    const filtroFechaFin = document.getElementById('filtroFechaFin');
    const filtroArchivado = document.getElementById('filtroArchivado');
    const btnReset = document.getElementById('btnResetFilters');
    const tableContainer = document.getElementById('tableContainer');
    const emptyFiltered = document.getElementById('emptyFilteredState');

    // Almacenar las opciones originales de rebaños
    const listaRebanosOriginal = Array.from(filtroRebano?.options || []).map(opt => ({
        value: opt.value,
        text: opt.textContent,
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

    function recalcularKpis(visibles) {
        const statTotal = document.getElementById('statTotalServicios');
        const statIA = document.getElementById('statTotalIA');
        const statMonta = document.getElementById('statTotalMonta');
        const statMes = document.getElementById('statServiciosMes');

        if (!statTotal) return;

        const currentMonth = new Date().toISOString().slice(0, 7); // 'YYYY-MM'
        let countTotal = visibles.length;
        let countIA = 0;
        let countMonta = 0;
        let countMes = 0;

        visibles.forEach(row => {
            const tipo = row.getAttribute('data-tipo') || '';
            const fecha = row.getAttribute('data-fecha') || '';

            if (tipo.includes('ia') || tipo.includes('inseminacion') || tipo.includes('inseminación') || tipo.includes('artificial')) {
                countIA++;
            } else if (tipo.includes('monta') || tipo.includes('natural') || tipo.includes('directa')) {
                countMonta++;
            }

            if (fecha && fecha.startsWith(currentMonth)) {
                countMes++;
            }
        });

        statTotal.textContent = countTotal;
        if (statIA) statIA.textContent = countIA;
        if (statMonta) statMonta.textContent = countMonta;
        if (statMes) statMes.textContent = countMes;
    }

    function aplicarFiltros() {
        const texto = (filtroTexto?.value || '').toLowerCase().trim();
        const tipo = (filtroTipo?.value || '').toLowerCase().trim();
        const finca = (filtroFinca?.value || '').toString();
        const rebano = (filtroRebano?.value || '').toString();
        const fechaInicio = filtroFechaInicio?.value || '';
        const fechaFin = filtroFechaFin?.value || '';
        const archivado = filtroArchivado?.value || '';

        let visibleCount = 0;
        const visibleRows = [];

        document.querySelectorAll('.fila-servicio').forEach(function(row) {
            const rowTexto = row.getAttribute('data-texto') || '';
            const rowTipo = (row.getAttribute('data-tipo') || '').toString();
            const rowFinca = (row.getAttribute('data-finca') || '').toString();
            const rowRebano = (row.getAttribute('data-rebano') || '').toString();
            const rowFecha = row.getAttribute('data-fecha') || '';
            const rowArchivado = row.getAttribute('data-archivado') || '';

            const matchTexto = !texto || rowTexto.includes(texto);
            
            let matchTipo = true;
            if (tipo) {
                if (tipo === 'ia' && !(rowTipo.includes('ia') || rowTipo.includes('inseminaci') || rowTipo.includes('artific'))) {
                    matchTipo = false;
                } else if (tipo === 'natural' && !(rowTipo.includes('monta') || rowTipo.includes('natural') || rowTipo.includes('directa'))) {
                    matchTipo = false;
                } else if (tipo === 'te' && !rowTipo.includes('te')) {
                    matchTipo = false;
                }
            }

            const matchFinca = !finca || rowFinca === finca;
            const matchRebano = !rebano || rowRebano === rebano;
            const matchFechaInicio = !fechaInicio || rowFecha >= fechaInicio;
            const matchFechaFin = !fechaFin || rowFecha <= fechaFin;
            const matchArchivado = !archivado || rowArchivado === archivado;

            if (matchTexto && matchTipo && matchFinca && matchRebano && matchFechaInicio && matchFechaFin && matchArchivado) {
                row.style.display = '';
                visibleCount++;
                visibleRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });

        if (emptyFiltered) {
            const totalRows = document.querySelectorAll('.fila-servicio').length;
            if (visibleCount === 0 && totalRows > 0) {
                emptyFiltered.classList.remove('hidden');
                if (tableContainer) tableContainer.classList.add('hidden');
            } else {
                emptyFiltered.classList.add('hidden');
                if (tableContainer) tableContainer.classList.remove('hidden');
            }
        }

        recalcularKpis(visibleRows);
    }

    filtroTexto?.addEventListener('input', aplicarFiltros);
    filtroTipo?.addEventListener('change', aplicarFiltros);

    // Al cambiar finca -> filtra los rebaños eliminando del DOM los que no pertenecen
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

    filtroFechaInicio?.addEventListener('change', aplicarFiltros);
    filtroFechaFin?.addEventListener('change', aplicarFiltros);
    filtroArchivado?.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        if (filtroTexto) filtroTexto.value = '';
        if (filtroTipo) filtroTipo.value = '';
        if (filtroFinca) filtroFinca.value = '';
        if (filtroRebano) filtroRebano.value = '';
        if (filtroFechaInicio) filtroFechaInicio.value = '';
        if (filtroFechaFin) filtroFechaFin.value = '';
        if (filtroArchivado) filtroArchivado.value = '';
        if (window.history && window.history.replaceState) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        repopularRebanosPorFinca();
        aplicarFiltros();
    };

    if (btnReset) {
        btnReset.addEventListener('click', (e) => {
            window.limpiarFiltros(e);
        });
    }

    repopularRebanosPorFinca();
});
</script>
@endsection