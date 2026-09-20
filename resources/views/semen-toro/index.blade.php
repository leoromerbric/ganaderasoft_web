@extends('layouts.authenticated')

@section('title', 'Semen de toros')

@section('content')
@php
    $totalRegistros = count($semenToros);
    $totalPajuelas = array_sum(array_map(fn($s) => (int)($s['cantidad_pajuelas'] ?? 1), $semenToros));
    
    $activos = array_filter($semenToros, fn($s) => (bool)($s['estado'] ?? false));
    $inactivos = array_filter($semenToros, fn($s) => !(bool)($s['estado'] ?? false));
    
    $totalActivos = count($activos);
    $totalInactivos = $totalRegistros - $totalActivos;
    
    $pajuelasActivas = array_sum(array_map(fn($s) => (int)($s['cantidad_pajuelas'] ?? 1), $activos));
    $pajuelasInactivas = array_sum(array_map(fn($s) => (int)($s['cantidad_pajuelas'] ?? 1), $inactivos));
    
    $torosUnicos = count(array_unique(array_filter(array_map(fn($s) => $s['animal_id'] ?? data_get($s, 'toro.id'), $semenToros))));
    
    $currentMonth = date('Y-m');
    $esteMes = count(array_filter($semenToros, function($s) use ($currentMonth) {
        $f = $s['fecha'] ?? '';
        return str_starts_with((string)$f, $currentMonth);
    }));
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col min-[900px]:flex-row min-[900px]:items-center min-[900px]:justify-between gap-4">
        <div class="flex items-center space-x-3.5 sm:space-x-4 min-w-0">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xl sm:text-2xl shadow-xs border border-teal-100 shrink-0">
                🧬
            </div>
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2 tracking-tight">
                    Semen de toros
                </h1>
                <p class="text-gray-500 text-xs sm:text-sm mt-0.5 sm:mt-1">Inventario de pajuelas, toros donantes y banco genético de la finca</p>
            </div>
        </div>
        <div class="w-full min-[900px]:w-auto shrink-0">
            <a href="{{ route('semen-toro.create') }}"
               class="w-full min-[900px]:w-auto px-5 sm:px-6 py-2.5 sm:py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm sm:text-base flex items-center justify-center gap-2 text-center whitespace-nowrap shrink-0">
                <span>+</span> Registrar lote / pajuela
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
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Inventario de pajuelas</p>
                <div class="flex items-baseline gap-2">
                    <p id="statTotalPajuelas" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $totalPajuelas }}</p>
                    <span class="text-xs font-semibold text-gray-500">uds</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">en <span id="statTotalRegistros" class="font-bold text-gray-700">{{ $totalRegistros }}</span> {{ $totalRegistros === 1 ? 'lote' : 'lotes' }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-ganaderasoft-azul flex items-center justify-center text-2xl border border-blue-100">
                🧬
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pajuelas disponibles</p>
                <div class="flex items-baseline gap-2">
                    <p id="statPajuelasActivas" class="text-3xl font-extrabold text-emerald-600">{{ $pajuelasActivas }}</p>
                    <span class="text-xs font-semibold text-emerald-700">uds</span>
                </div>
                <p class="text-xs text-emerald-700/80 mt-1"><span id="statTotalActivos" class="font-bold">{{ $totalActivos }}</span> {{ $totalActivos === 1 ? 'lote activo' : 'lotes activos' }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl border border-emerald-100">
                🟢
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pajuelas agotadas</p>
                <div class="flex items-baseline gap-2">
                    <p id="statPajuelasInactivas" class="text-3xl font-extrabold text-amber-600">{{ $pajuelasInactivas }}</p>
                    <span class="text-xs font-semibold text-amber-700">uds</span>
                </div>
                <p class="text-xs text-amber-700/80 mt-1"><span id="statTotalInactivos" class="font-bold">{{ $totalInactivos }}</span> {{ $totalInactivos === 1 ? 'lote inactivo' : 'lotes inactivos' }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl border border-amber-100">
                ⚪
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Toros donantes</p>
                <div class="flex items-baseline gap-2">
                    <p id="statTorosUnicos" class="text-3xl font-extrabold text-purple-600">{{ $torosUnicos }}</p>
                    <span class="text-xs font-semibold text-purple-700">{{ $torosUnicos === 1 ? 'toro' : 'toros' }}</span>
                </div>
                <p class="text-xs text-purple-700/80 mt-1"><span id="statRegistrosMes" class="font-bold">{{ $esteMes }}</span> {{ $esteMes === 1 ? 'lote este mes' : 'lotes este mes' }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl border border-purple-100">
                🐂
            </div>
        </div>
    </div>

    <!-- Filters Bar (Formato Vacunación: 2 Filas x 4 Columnas) -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="space-y-4">
            <!-- Fila 1: Buscar, Estado, Finca, Rebaño -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                    <input type="text" id="filtroTexto" placeholder="Toro, código pajuela, raza..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Disponibilidad</label>
                    <select id="filtroEstado"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los estados</option>
                        <option value="1">Disponibles / Activas</option>
                        <option value="0">Agotadas / Inactivas</option>
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
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado del toro</label>
                    <select id="filtroArchivado"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los estados</option>
                        <option value="activo">Solo toros activos</option>
                        <option value="archivado">Solo toros archivados</option>
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
        @if(count($semenToros) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tablaSemen">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Toro donante</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lote / Registro</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Disponibilidad</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Servicios aplicados</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha de colecta / ingreso</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($semenToros as $semen)
                        @php
                            $sId = $semen['id'] ?? null;
                            $toroId = $semen['animal_id'] ?? data_get($semen, 'toro.id') ?? '';
                            $toroNombre = data_get($semen, 'toro.Nombre') ?? data_get($semen, 'toro.nombre') ?? ('Toro #'.$toroId);
                            $toroCodigo = data_get($semen, 'toro.codigo_animal') ?? data_get($semen, 'toro.Codigo') ?? '';
                            $toroRaza = data_get($semen, 'toro.composicion_raza.nombre') ?? data_get($semen, 'toro.composicionRaza.nombre') ?? data_get($semen, 'toro.raza.Nombre') ?? data_get($semen, 'toro.raza.nombre') ?? '';
                            
                            $fincaId = (string)(data_get($semen, 'toro.rebano.finca.id') ?? data_get($semen, 'toro.rebano.finca_id') ?? '');
                            $fincaNombre = data_get($semen, 'toro.rebano.finca.Nombre') ?? data_get($semen, 'toro.rebano.finca.nombre') ?? '';
                            
                            $rebanoId = (string)(data_get($semen, 'toro.rebano.id') ?? data_get($semen, 'toro.rebano_id') ?? '');
                            $rebanoNombre = data_get($semen, 'toro.rebano.Nombre') ?? data_get($semen, 'toro.rebano.nombre') ?? '';
                            
                            $estado = (bool)($semen['estado'] ?? false);
                            $fecha = $semen['fecha'] ?? null;
                            $cantidadPajuelas = (int)($semen['cantidad_pajuelas'] ?? 1);
                            $serviciosCount = count($semen['servicios'] ?? []);

                            $isArchivado = (bool)(data_get($semen, 'toro.archivado') ?? data_get($semen, 'toro.is_archivado') ?? false);

                            $searchString = strtolower(implode(' ', array_filter([
                                $toroNombre,
                                $toroCodigo,
                                '#'.$toroCodigo,
                                (string)$toroId,
                                '#'.$toroId,
                                'pajuela #'.$sId,
                                'lote #'.$sId,
                                (string)$sId,
                                (string)$cantidadPajuelas,
                                $cantidadPajuelas.' pajuelas',
                                $toroRaza,
                                $rebanoNombre,
                                $fincaNombre
                            ])));
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors fila-semen" 
                            data-texto="{{ $searchString }}"
                            data-estado="{{ $estado ? '1' : '0' }}"
                            data-finca="{{ $fincaId }}"
                            data-rebano="{{ $rebanoId }}"
                            data-fecha="{{ $fecha ? date('Y-m-d', strtotime($fecha)) : '' }}"
                            data-pajuelas="{{ $cantidadPajuelas }}"
                            data-toro-id="{{ $toroId }}"
                            data-archivado="{{ $isArchivado ? 'archivado' : 'activo' }}">
                            
                            <!-- Toro Donante -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center font-bold text-lg shadow-2xs">
                                        🐂
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-900 truncate">{{ $toroNombre }}</p>
                                        <div class="flex items-center gap-1.5 text-xs text-gray-500 font-mono">
                                            @if($toroCodigo)
                                                 <span class="bg-gray-100 text-gray-700 px-1.5 py-0.2 rounded font-semibold">#{{ $toroCodigo }}</span>
                                            @else
                                                <span>ID: #{{ $toroId }}</span>
                                            @endif
                                            @if($toroRaza)
                                                <span class="text-blue-700">• {{ $toroRaza }}</span>
                                            @endif
                                            @if($rebanoNombre)
                                                <span class="truncate">• {{ $rebanoNombre }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Pajuela / Lote -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-cyan-50 text-cyan-800 border border-cyan-200">
                                    🧬 Lote #{{ $sId }}
                                </span>
                            </td>

                            <!-- Cantidad de pajuelas -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-gray-900 bg-gray-100 border border-gray-200/80 px-2.5 py-1 rounded-lg text-xs">
                                    {{ $cantidadPajuelas }} {{ $cantidadPajuelas === 1 ? 'pajuela' : 'pajuelas' }}
                                </span>
                            </td>

                            <!-- Disponibilidad -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($estado)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Disponible / Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold bg-gray-50 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Agotada / Inactiva
                                    </span>
                                @endif
                            </td>

                            <!-- Servicios Vinculados -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                @if($serviciosCount > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                        {{ $serviciosCount }} {{ $serviciosCount === 1 ? 'servicio' : 'servicios' }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">0 servicios</span>
                                @endif
                            </td>

                            <!-- Fecha -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'S/F' }}
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Botón Ver Detalles -->
                                    <a href="{{ route('semen-toro.show', $sId) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                       title="Ver detalle del lote de semen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Botón Editar -->
                                    <a href="{{ route('semen-toro.edit', $sId) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                       title="Editar lote de semen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Botón Eliminar con Modal -->
                                    <form method="POST" action="{{ route('semen-toro.destroy', $sId) }}" class="inline-block" id="form-delete-semen-{{ $sId }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openGenericConfirmModal({
                                            formId: 'form-delete-semen-{{ $sId }}',
                                            intent: 'danger',
                                            title: 'Eliminar registro de semen',
                                            message: '¿Estás seguro de que deseas eliminar esta muestra de semen de toro? Esta acción no se puede revertir.',
                                            confirmText: 'Sí, eliminar'
                                        })"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
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
        @else
            <div class="p-12 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center text-3xl shadow-xs">
                    🧬
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-gray-900">No hay registros de semen de toro guardados</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto">Comienza registrando lotes de pajuelas de tus toros donantes para gestionar la inseminación artificial en el rebaño.</p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('semen-toro.create') }}"
                       class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-xl hover:bg-opacity-90 transition-all font-semibold text-sm shadow-md hover:shadow-lg inline-flex items-center gap-2">
                        <span>+</span> Registrar nuevo semen de toro
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
            <h4 class="text-base font-bold text-gray-900">No se encontraron registros de semen</h4>
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
    const filtroEstado = document.getElementById('filtroEstado');
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
        const statTotalPaj = document.getElementById('statTotalPajuelas');
        const statTotalReg = document.getElementById('statTotalRegistros');
        const statPajAct = document.getElementById('statPajuelasActivas');
        const statTotAct = document.getElementById('statTotalActivos');
        const statPajInact = document.getElementById('statPajuelasInactivas');
        const statTotInact = document.getElementById('statTotalInactivos');
        const statToros = document.getElementById('statTorosUnicos');
        const statMes = document.getElementById('statRegistrosMes');

        if (!statTotalPaj && !statTotalReg) return;

        const currentMonth = new Date().toISOString().slice(0, 7); // 'YYYY-MM'
        let countTotalReg = visibles.length;
        let countTotalPaj = 0;
        let countPajAct = 0;
        let countTotAct = 0;
        let countPajInact = 0;
        let countTotInact = 0;
        let countMes = 0;
        const torosSet = new Set();

        visibles.forEach(row => {
            const estado = row.getAttribute('data-estado') || '';
            const fecha = row.getAttribute('data-fecha') || '';
            const toroId = row.getAttribute('data-toro-id') || '';
            const paj = parseInt(row.getAttribute('data-pajuelas') || '1', 10);
            const cant = isNaN(paj) ? 1 : paj;

            countTotalPaj += cant;
            if (toroId) torosSet.add(toroId);

            if (estado === '1') {
                countTotAct++;
                countPajAct += cant;
            } else {
                countTotInact++;
                countPajInact += cant;
            }

            if (fecha && fecha.startsWith(currentMonth)) {
                countMes++;
            }
        });

        if (statTotalPaj) statTotalPaj.textContent = countTotalPaj;
        if (statTotalReg) statTotalReg.textContent = countTotalReg;
        if (statPajAct) statPajAct.textContent = countPajAct;
        if (statTotAct) statTotAct.textContent = countTotAct;
        if (statPajInact) statPajInact.textContent = countPajInact;
        if (statTotInact) statTotInact.textContent = countTotInact;
        if (statToros) statToros.textContent = torosSet.size;
        if (statMes) statMes.textContent = countMes;
    }

    function aplicarFiltros() {
        const texto = (filtroTexto?.value || '').toLowerCase().trim();
        const estado = (filtroEstado?.value || '').toString();
        const finca = (filtroFinca?.value || '').toString();
        const rebano = (filtroRebano?.value || '').toString();
        const fechaInicio = filtroFechaInicio?.value || '';
        const fechaFin = filtroFechaFin?.value || '';
        const archivado = filtroArchivado?.value || '';

        let visibleCount = 0;
        const visibleRows = [];

        document.querySelectorAll('.fila-semen').forEach(function(row) {
            const rowTexto = row.getAttribute('data-texto') || '';
            const rowEstado = (row.getAttribute('data-estado') || '').toString();
            const rowFinca = (row.getAttribute('data-finca') || '').toString();
            const rowRebano = (row.getAttribute('data-rebano') || '').toString();
            const rowFecha = row.getAttribute('data-fecha') || '';
            const rowArchivado = row.getAttribute('data-archivado') || '';

            const matchTexto = !texto || rowTexto.includes(texto);
            const matchEstado = !estado || rowEstado === estado;
            const matchFinca = !finca || rowFinca === finca;
            const matchRebano = !rebano || rowRebano === rebano;
            const matchFechaInicio = !fechaInicio || rowFecha >= fechaInicio;
            const matchFechaFin = !fechaFin || rowFecha <= fechaFin;
            const matchArchivado = !archivado || rowArchivado === archivado;

            if (matchTexto && matchEstado && matchFinca && matchRebano && matchFechaInicio && matchFechaFin && matchArchivado) {
                row.style.display = '';
                visibleCount++;
                visibleRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });

        if (emptyFiltered) {
            const totalRows = document.querySelectorAll('.fila-semen').length;
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
    filtroEstado?.addEventListener('change', aplicarFiltros);

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

    filtroFechaInicio?.addEventListener('change', aplicarFiltros);
    filtroFechaFin?.addEventListener('change', aplicarFiltros);
    filtroArchivado?.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        if (filtroTexto) filtroTexto.value = '';
        if (filtroEstado) filtroEstado.value = '';
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