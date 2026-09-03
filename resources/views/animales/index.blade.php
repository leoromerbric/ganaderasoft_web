@extends('layouts.authenticated')

@section('title', 'Gestión de animales')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🏷️
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Gestión de animales
                </h1>
                <p class="text-gray-500 text-sm mt-1">Administración del inventario de ganado, genealogía y registro por rebaños y fincas</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('animales.importar', ['finca_id' => $idFinca]) }}"
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-2xs font-medium text-base inline-flex items-center justify-center gap-2 min-w-[195px]">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Importar CSV / TXT
            </a>
            <a href="{{ route('animales.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-base inline-flex items-center justify-center gap-2 min-w-[195px]">
                <span>+</span> Registrar animal
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

    <!-- 4 Global KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Animales -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total animales</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1" id="kpiTotal">{{ $estadisticas['total'] ?? count($animales) }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">En el inventario</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl font-bold border border-emerald-100 shadow-2xs">
                🐄
            </div>
        </div>

        <!-- Machos -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Machos (Toros / Novillos)</p>
                <h3 class="text-2xl font-black text-blue-700 mt-1" id="kpiMachos">{{ $estadisticas['machos'] ?? 0 }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Ejemplares machos</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100 shadow-2xs">
                🐂
            </div>
        </div>

        <!-- Hembras -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Hembras (Vacas / Novillas)</p>
                <h3 class="text-2xl font-black text-pink-700 mt-1" id="kpiHembras">{{ $estadisticas['hembras'] ?? 0 }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Ejemplares hembras</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-xl font-bold border border-pink-100 shadow-2xs">
                🥛
            </div>
        </div>

        <!-- Animales Activos -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Animales activos</p>
                <h3 class="text-2xl font-black text-emerald-700 mt-1" id="kpiActivos">{{ $estadisticas['activos'] ?? count($animales) }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">En producción / hato</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl font-bold border border-emerald-100 shadow-2xs">
                🟢
            </div>
        </div>
    </div>

    <!-- Filters Bar (5 columns) -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <!-- Buscar -->
            <div class="lg:col-span-1">
                <label for="filtroNombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar animal</label>
                <input type="text" id="filtroNombre" value="{{ $nombre }}" placeholder="Nombre o código..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Finca -->
            <div>
                <label for="filtroFinca" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                <select id="filtroFinca"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @php
                            $fArchivada = !empty($finca['archivado']);
                        @endphp
                        <option value="{{ $finca['id'] }}" 
                                data-archivado="{{ $fArchivada ? '1' : '0' }}"
                                {{ (string)$idFinca === (string)$finca['id'] ? 'selected' : '' }}>
                            🏡 {{ $finca['nombre'] ?? 'Finca #'.$finca['id'] }}{{ $fArchivada ? ' (Archivada)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Rebaño (con cascada Finca -> Rebaño y filtro de archivado dinámico) -->
            <div>
                <label for="filtroRebano" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                <select id="filtroRebano"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                    <option value="">Todos los rebaños</option>
                    @foreach($rebanos as $rebano)
                        @php
                            $rfId = $rebano['finca_id'] ?? ($mapaRebanoFinca[$rebano['id']] ?? '');
                            $rArchivado = !empty($rebano['archivado']);
                        @endphp
                        <option value="{{ $rebano['id'] }}"
                                data-finca="{{ $rfId }}"
                                data-archivado="{{ $rArchivado ? '1' : '0' }}"
                                {{ (string)$idRebano === (string)$rebano['id'] ? 'selected' : '' }}>
                            🐄 {{ $rebano['nombre'] ?? 'Rebaño #'.$rebano['id'] }}{{ $rArchivado ? ' (Archivado)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sexo -->
            <div>
                <label for="filtroSexo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Sexo</label>
                <select id="filtroSexo"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                    <option value="">Todos los sexos</option>
                    <option value="M" {{ $sexo === 'M' ? 'selected' : '' }}>🐂 Macho (♂)</option>
                    <option value="H" {{ $sexo === 'H' ? 'selected' : '' }}>🥛 Hembra (♀)</option>
                </select>
            </div>

            <!-- Estado -->
            <div>
                <label for="filtroArchivado" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado</label>
                <select id="filtroArchivado"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                    <option value="false" {{ ($archivado ?? 'false') === 'false' ? 'selected' : '' }}>🟢 Solo activos</option>
                    <option value="true" {{ ($archivado ?? '') === 'true' ? 'selected' : '' }}>⚪ Solo archivados</option>
                </select>
            </div>

            <!-- Limpiar -->
            <div>
                <button type="button" onclick="limpiarFiltros()"
                        class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px] cursor-pointer shadow-2xs">
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de Animales -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($animales) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sexo</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rebaño & Finca</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nacimiento / Edad</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaAnimales">
                        @foreach($animales as $animal)
                            @php
                                $rebanoId    = $animal['rebano_id'] ?? ($animal['rebano']['id'] ?? '');
                                $fincaId     = $animal['finca_id'] ?? ($animal['rebano']['finca_id'] ?? ($animal['rebano']['finca']['id'] ?? ($mapaRebanoFinca[$rebanoId] ?? '')));
                                $rebanoNombre = $animal['rebano']['nombre'] ?? ($mapaRebanoNombres[$rebanoId] ?? ($rebanoId ? ('Rebaño #' . $rebanoId) : 'Sin rebaño'));
                                $fincaNombre  = $animal['rebano']['finca']['nombre'] ?? ($mapaFincaNombres[$fincaId] ?? ($fincaId ? ('Finca #' . $fincaId) : 'Sin finca'));
                                
                                $isMacho     = strtoupper((string)($animal['sexo'] ?? '')) === 'M';
                                $isArchivado = !empty($animal['archivado']);
                                $inicial     = strtoupper(substr($animal['nombre'] ?? 'A', 0, 1));
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors fila-animal {{ $isArchivado ? 'bg-gray-50/40' : '' }}"
                                data-rebano="{{ $rebanoId }}"
                                data-finca="{{ $fincaId }}"
                                data-sexo="{{ $animal['sexo'] ?? '' }}"
                                data-archivado="{{ $isArchivado ? 'true' : 'false' }}"
                                data-nombre="{{ strtolower(($animal['nombre'] ?? '').' '.($animal['codigo_animal'] ?? '')) }}">
                                <!-- Animal -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3.5">
                                        <div class="w-10 h-10 shrink-0 rounded-xl {{ $isMacho ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-lg shadow-2xs">
                                            {{ $isMacho ? '🐂' : '🥛' }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <a href="{{ route('animales.show', $animal['id']) }}" class="font-bold text-gray-900 hover:text-ganaderasoft-azul transition-colors truncate block">
                                                {{ $animal['nombre'] ?? 'Sin Nombre' }}
                                            </a>
                                            <p class="text-xs text-gray-400 font-mono">ID: #{{ $animal['id'] }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Código -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $animal['codigo_animal'] ?? 'S/C' }}
                                    </span>
                                </td>

                                <!-- Sexo -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $isMacho ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200' }}">
                                        {{ $isMacho ? 'Macho ♂' : 'Hembra ♀' }}
                                    </span>
                                </td>

                                <!-- Rebaño & Finca -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="font-bold text-gray-900 flex items-center gap-1.5">
                                        <span>🐄</span> {{ $rebanoNombre }}
                                    </p>
                                    <p class="text-xs text-gray-500 font-medium mt-0.5 flex items-center gap-1">
                                        <span>🏡</span> {{ $fincaNombre }}
                                    </p>
                                </td>

                                <!-- Nacimiento / Edad -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    <p class="font-bold text-gray-900">{{ isset($animal['fecha_nacimiento']) ? date('d/m/Y', strtotime($animal['fecha_nacimiento'])) : 'N/A' }}</p>
                                    @if(!empty($animal['edad_formateada']))
                                        <p class="text-xs text-gray-400 font-medium">{{ $animal['edad_formateada'] }}</p>
                                    @elseif(!empty($animal['fecha_nacimiento']))
                                        <p class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($animal['fecha_nacimiento'])->diffForHumans(null, true) }}</p>
                                    @endif
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isArchivado)
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                            ⚪ Archivado
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            🟢 Activo
                                        </span>
                                    @endif
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <div class="flex justify-center items-center space-x-2">
                                        <!-- Ver Detalle -->
                                        <a href="{{ route('animales.show', $animal['id']) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white transition-all shadow-2xs"
                                           title="Ver detalle">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Árbol Genealógico -->
                                        <a href="{{ route('arbol-gen.show', $animal['id']) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-all shadow-2xs"
                                           title="Árbol genealógico">
                                            <span class="text-xs">🌳</span>
                                        </a>
                                        
                                        <!-- Editar -->
                                        <a href="{{ route('animales.edit', $animal['id']) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white transition-all shadow-2xs"
                                           title="Editar animal">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Botón Toggle Archivar / Desarchivar -->
                                        @if($isArchivado)
                                            <form action="{{ route('animales.desarchivar', $animal['id']) }}" method="POST" class="inline-block" id="form-unarchive-animal-{{ $animal['id'] }}">
                                                @csrf
                                                <button type="button" onclick="openGenericConfirmModal({
                                                    formId: 'form-unarchive-animal-{{ $animal['id'] }}',
                                                    intent: 'success',
                                                    title: 'Desarchivar animal',
                                                    message: '¿Estás seguro de que deseas reactivar este animal? Volverá a estar presente en el inventario activo del rebaño.',
                                                    confirmText: 'Sí, desarchivar'
                                                })"
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-2xs cursor-pointer"
                                                   title="Desarchivar animal">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('animales.archivar', $animal['id']) }}" method="POST" class="inline-block" id="form-archive-animal-{{ $animal['id'] }}">
                                                @csrf
                                                <button type="button" onclick="openGenericConfirmModal({
                                                    formId: 'form-archive-animal-{{ $animal['id'] }}',
                                                    intent: 'danger',
                                                    title: 'Archivar animal',
                                                    message: '¿Estás seguro de que deseas archivar este animal? Se ocultará del inventario activo pero conservará su historial.',
                                                    confirmText: 'Sí, archivar'
                                                })"
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-2xs cursor-pointer"
                                                   title="Archivar animal">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Fila para estado vacío cuando se filtra en el cliente -->
                        <tr id="filasVacias" class="hidden">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center text-2xl border border-gray-100">
                                    🔍
                                </div>
                                <p class="text-base font-bold text-gray-800">No se encontraron animales con los filtros seleccionados</p>
                                <p class="text-xs text-gray-400 mt-1">Prueba seleccionando otra finca, rebaño o limpiando los filtros.</p>
                                <button type="button" onclick="limpiarFiltros()" class="mt-4 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition-all">
                                    Limpiar filtros
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-3xl">
                    🐄
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay animales registrados</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza agregando ejemplares al rebaño de tu finca o importa un archivo CSV.</p>
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('animales.create') }}"
                       class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                        + Nuevo animal
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

<script>
    const mapaRebanoFinca = @json($mapaRebanoFinca);
    const todasLasFincas  = @json($fincas);
    const todosLosRebanos = @json($rebanos);

    function actualizarDesplegables(targetRebanoVal = null) {
        const fincaSel   = document.getElementById('filtroFinca');
        const rebanoSel  = document.getElementById('filtroRebano');
        const archivado  = document.getElementById('filtroArchivado').value;

        const currentFincaVal = fincaSel.value;
        const currentRebanoVal = targetRebanoVal !== null ? String(targetRebanoVal) : rebanoSel.value;

        // 1. Reconstruir opciones de Fincas según Estado
        fincaSel.innerHTML = '<option value="">Todas las fincas</option>';
        todasLasFincas.forEach(function (finca) {
            const isArchivada = Boolean(finca.archivado);
            // Si el estado es "false", mostrar solo activas
            if (archivado === 'false' && isArchivada) {
                return;
            }
            // Si el estado es "true", mostrar solo archivadas
            if (archivado === 'true' && !isArchivada) {
                return;
            }

            const opt = document.createElement('option');
            opt.value = String(finca.id);
            opt.textContent = '🏡 ' + (finca.nombre || ('Finca #' + finca.id));
            if (String(currentFincaVal) === String(finca.id)) {
                opt.selected = true;
            }
            fincaSel.appendChild(opt);
        });

        const effectiveFincaId = fincaSel.value;

        // 2. Reconstruir opciones de Rebaños según Finca seleccionada + Estado
        rebanoSel.innerHTML = '<option value="">Todos los rebaños</option>';
        todosLosRebanos.forEach(function (rebano) {
            const isArchivado = Boolean(rebano.archivado);
            const rFincaId = rebano.finca_id || (rebano.finca && rebano.finca.id) || (mapaRebanoFinca ? mapaRebanoFinca[rebano.id] : null);

            // Filtrar por finca seleccionada si existe
            if (effectiveFincaId && String(rFincaId) !== String(effectiveFincaId)) {
                return;
            }

            // Si el estado es "false", mostrar solo activos
            if (archivado === 'false' && isArchivado) {
                return;
            }
            // Si el estado es "true", mostrar solo archivados
            if (archivado === 'true' && !isArchivado) {
                return;
            }

            const opt = document.createElement('option');
            opt.value = String(rebano.id);
            opt.dataset.finca = rFincaId ? String(rFincaId) : '';
            opt.textContent = '🐄 ' + (rebano.nombre || ('Rebaño #' + rebano.id));
            if (String(currentRebanoVal) === String(rebano.id)) {
                opt.selected = true;
            }
            rebanoSel.appendChild(opt);
        });
    }

    document.getElementById('filtroFinca').addEventListener('change', function () {
        actualizarDesplegables();
        aplicarFiltros();
    });

    document.getElementById('filtroArchivado').addEventListener('change', function () {
        actualizarDesplegables();
        aplicarFiltros();
    });

    document.getElementById('filtroRebano').addEventListener('change', function () {
        const rebVal = this.value;
        if (rebVal && mapaRebanoFinca && mapaRebanoFinca[rebVal]) {
            const fincaIdOfReb = String(mapaRebanoFinca[rebVal]);
            const fincaSel = document.getElementById('filtroFinca');
            if (fincaSel && fincaSel.value !== fincaIdOfReb) {
                fincaSel.value = fincaIdOfReb;
                actualizarDesplegables(rebVal);
            }
        }
        aplicarFiltros();
    });

    document.getElementById('filtroSexo').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroNombre').addEventListener('input', aplicarFiltros);

    function aplicarFiltros() {
        const fincaId   = document.getElementById('filtroFinca').value;
        const rebanoId  = document.getElementById('filtroRebano').value;
        const sexo      = document.getElementById('filtroSexo').value;
        const archivado = document.getElementById('filtroArchivado').value;
        const nombre    = document.getElementById('filtroNombre').value.trim().toLowerCase();

        let countTotal = 0;
        let countActivos = 0;
        let countMachos = 0;
        let countHembras = 0;

        const rows = document.querySelectorAll('.fila-animal');
        
        rows.forEach(function (row) {
            const rowFinca     = row.dataset.finca || '';
            const rowRebano    = row.dataset.rebano || '';
            const rowSexo      = row.dataset.sexo || '';
            const rowArchivado = row.dataset.archivado || 'false';
            const rowNombre    = row.dataset.nombre || '';

            const matchFinca     = !fincaId || (String(rowFinca) === String(fincaId));
            const matchRebano    = !rebanoId || (String(rowRebano) === String(rebanoId));
            const matchSexo      = !sexo || (rowSexo.toUpperCase() === sexo.toUpperCase());
            const matchArchivado = (rowArchivado === archivado);
            const matchNombre    = !nombre || rowNombre.includes(nombre);

            const isVisible = matchFinca && matchRebano && matchSexo && matchArchivado && matchNombre;

            row.style.display = isVisible ? '' : 'none';

            if (isVisible) {
                countTotal++;
                if (rowArchivado !== 'true') countActivos++;
                if (rowSexo.toUpperCase() === 'M') countMachos++;
                if (rowSexo.toUpperCase() === 'H') countHembras++;
            }
        });

        // Actualizar KPIs de forma reactiva
        const kpiTotal = document.getElementById('kpiTotal');
        const kpiActivos = document.getElementById('kpiActivos');
        const kpiMachos = document.getElementById('kpiMachos');
        const kpiHembras = document.getElementById('kpiHembras');

        if (kpiTotal) kpiTotal.textContent = countTotal;
        if (kpiActivos) kpiActivos.textContent = countActivos;
        if (kpiMachos) kpiMachos.textContent = countMachos;
        if (kpiHembras) kpiHembras.textContent = countHembras;

        // Mostrar / ocultar fila de vacíos
        const emptyRow = document.getElementById('filasVacias');
        if (emptyRow) {
            if (countTotal === 0 && rows.length > 0) {
                emptyRow.classList.remove('hidden');
            } else {
                emptyRow.classList.add('hidden');
            }
        }
    }

    function limpiarFiltros() {
        document.getElementById('filtroNombre').value = '';
        document.getElementById('filtroFinca').value = '';
        document.getElementById('filtroRebano').value = '';
        document.getElementById('filtroSexo').value = '';
        document.getElementById('filtroArchivado').value = 'false';
        
        if (window.history && window.history.replaceState) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        actualizarDesplegables();
        aplicarFiltros();
    }

    document.addEventListener('DOMContentLoaded', function () {
        actualizarDesplegables();
        aplicarFiltros();
    });
</script>
@endsection