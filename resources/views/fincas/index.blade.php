@extends('layouts.authenticated')

@section('title', 'Gestión de fincas')

@section('content')
@php
    $totalFincas = count($fincas);
    $totalSuperficie = array_sum(array_map(function ($f) {
        return (float) ($f['terreno']['superficie'] ?? 0);
    }, $fincas));
    $promedioSuperficie = $totalFincas > 0 ? ($totalSuperficie / $totalFincas) : 0;
    $totalTipos = count($tipos);
@endphp

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🏡
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Gestión de fincas
                </h1>
                <p class="text-gray-500 text-sm mt-1">Administración de fincas, unidades de producción ganadera y configuración territorial</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('fincas.importar') }}"
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-2xs font-medium text-base inline-flex items-center justify-center gap-2 min-w-[195px]">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Importar CSV / TXT
            </a>
            <a href="{{ route('fincas.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-base inline-flex items-center justify-center gap-2 min-w-[195px]">
                <span>+</span> Registrar finca
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
        <!-- Total Fincas -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de fincas</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1" id="statTotal">{{ $totalFincas }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Unidades registradas</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl font-bold border border-emerald-100 shadow-2xs">
                🏡
            </div>
        </div>

        <!-- Superficie Total -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Superficie total</p>
                <h3 class="text-2xl font-black text-blue-700 mt-1" id="statSuperficie">{{ number_format($totalSuperficie, 1, ',', '.') }} ha</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Área territorial</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold border border-blue-100 shadow-2xs">
                📐
            </div>
        </div>

        <!-- Promedio por Finca -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Superficie promedio</p>
                <h3 class="text-2xl font-black text-teal-700 mt-1" id="statPromedio">{{ number_format($promedioSuperficie, 1, ',', '.') }} ha</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Por unidad de producción</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-xl font-bold border border-teal-100 shadow-2xs">
                📊
            </div>
        </div>

        <!-- Tipos de Explotación -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Modelos productivos</p>
                <h3 class="text-2xl font-black text-purple-700 mt-1" id="statTipos">{{ $totalTipos }}</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Tipos de explotación</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold border border-purple-100 shadow-2xs">
                💼
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label for="filtroNombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar por nombre</label>
                <input type="text" id="filtroNombre" value="{{ $nombre }}" placeholder="Ej: Finca San José..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <div>
                <label for="filtroTipo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipo de explotación</label>
                <select id="filtroTipo"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                    <option value="">Todos los tipos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo }}" {{ $tipoFiltro === $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filtroArchivado" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado</label>
                @php
                    $estadoFiltro = !empty($incluirArchivados) ? 'todos' : (!empty($archivado) ? 'true' : 'false');
                @endphp
                <select id="filtroArchivado"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                    <option value="false" {{ $estadoFiltro === 'false' ? 'selected' : '' }}>Solo activas</option>
                    <option value="true" {{ $estadoFiltro === 'true' ? 'selected' : '' }}>Solo archivadas</option>
                    <option value="todos" {{ $estadoFiltro === 'todos' ? 'selected' : '' }}>Todas las fincas</option>
                </select>
            </div>

            <div>
                <button type="button" onclick="limpiarFiltros()"
                        class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px] cursor-pointer shadow-2xs">
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Fincas List Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($fincas) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Finca</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo explotación</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Propietario / Contacto</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Superficie</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaFincas">
                        @foreach($fincas as $finca)
                            @php
                                $fincaId = $finca['id'] ?? null;
                                $nombreFinca = $finca['nombre'] ?? 'Sin Nombre';
                                $tipoExp = $finca['explotacion_tipo'] ?? 'General';
                                $superficie = (float) ($finca['terreno']['superficie'] ?? 0);
                                $isArchivado = !empty($finca['archivado']);

                                // Formateo de propietario V2
                                $propObj = $finca['propietario'] ?? null;
                                $persona = $propObj['persona'] ?? null;
                                $nombreProp = $persona ? trim(($persona['nombre'] ?? '') . ' ' . ($persona['apellido'] ?? '')) : null;
                                $telefonoProp = $persona['telefono'] ?? null;
                                $correoProp = $persona['correo'] ?? null;
                                $inicial = strtoupper(substr($nombreFinca, 0, 1));
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors fila-finca {{ $isArchivado ? 'bg-gray-50/40' : '' }}"
                                data-nombre="{{ strtolower($nombreFinca) }}" 
                                data-tipo="{{ $tipoExp }}"
                                data-archivado="{{ $isArchivado ? 'true' : 'false' }}"
                                data-superficie="{{ $superficie }}">
                                <!-- Finca -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3.5">
                                        <div class="w-11 h-11 shrink-0 rounded-xl bg-teal-50 text-teal-700 border border-teal-100 flex items-center justify-center font-bold text-lg shadow-2xs">
                                            {{ $inicial ?: '🏡' }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <a href="{{ route('fincas.show', $fincaId) }}" class="font-bold text-gray-900 hover:text-ganaderasoft-azul transition-colors truncate block">
                                                {{ $nombreFinca }}
                                            </a>
                                            <p class="text-xs text-gray-400 font-mono mt-0.5">ID: #{{ $fincaId }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Tipo Explotación -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-100">
                                        💼 {{ $tipoExp }}
                                    </span>
                                </td>

                                <!-- Propietario / Contacto -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($nombreProp)
                                        <p class="font-bold text-gray-900">{{ $nombreProp }}</p>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 mt-0.5">
                                            @if($telefonoProp && $telefonoProp !== '-')
                                                <span class="flex items-center gap-1">📞 {{ $telefonoProp }}</span>
                                            @endif
                                            @if($correoProp)
                                                <span class="flex items-center gap-1">✉️ {{ $correoProp }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic text-xs">Sin propietario registrado</span>
                                    @endif
                                </td>

                                <!-- Superficie -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($superficie > 0)
                                        <span class="font-bold text-gray-900">{{ number_format($superficie, 1, ',', '.') }}</span>
                                        <span class="text-xs text-gray-500 font-medium">ha</span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">No registrada</span>
                                    @endif
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isArchivado)
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                            ⚪ Archivada
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            🟢 Activa
                                        </span>
                                    @endif
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <div class="flex justify-center items-center space-x-2">
                                        <!-- Ver Detalle / Ficha -->
                                        <a href="{{ route('fincas.show', $fincaId) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors shadow-2xs"
                                           title="Ver detalle de la finca">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Editar Finca -->
                                        <a href="{{ route('fincas.edit', $fincaId) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors shadow-2xs"
                                           title="Editar finca">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Botón Toggle Archivar / Desarchivar -->
                                        @if($isArchivado)
                                            <form action="{{ route('fincas.desarchivar', $fincaId) }}" method="POST" class="inline-block" id="form-unarchive-finca-{{ $fincaId }}">
                                                @csrf
                                                <button type="button"
                                                    onclick="openGenericConfirmModal({
                                                        formId: 'form-unarchive-finca-{{ $fincaId }}',
                                                        intent: 'success',
                                                        title: 'Desarchivar finca',
                                                        message: '¿Estás seguro de que deseas reactivar esta finca? Volverá a estar visible en todas las operaciones activas del sistema.',
                                                        confirmText: 'Sí, desarchivar'
                                                    })"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-2xs cursor-pointer"
                                                    title="Desarchivar finca">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('fincas.archivar', $fincaId) }}" method="POST" class="inline-block" id="form-archive-finca-{{ $fincaId }}">
                                                @csrf
                                                <button type="button"
                                                    onclick="openGenericConfirmModal({
                                                        formId: 'form-archive-finca-{{ $fincaId }}',
                                                        intent: 'danger',
                                                        title: 'Archivar finca',
                                                        message: '¿Estás seguro de que deseas archivar esta finca? Se ocultará de las operaciones activas pero conservará todos sus registros históricos.',
                                                        confirmText: 'Sí, archivar'
                                                    })"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-2xs cursor-pointer"
                                                    title="Archivar finca">
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

                        <!-- Fila vacía para filtro de cliente -->
                        <tr id="filasVacias" class="hidden">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center text-2xl border border-gray-100">
                                    🔍
                                </div>
                                <p class="text-base font-bold text-gray-800">No se encontraron fincas con los filtros seleccionados</p>
                                <p class="text-xs text-gray-400 mt-1">Prueba con otro término de búsqueda o limpia los filtros.</p>
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
                    🏡
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay fincas registradas</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando la primera unidad de producción de tu explotación ganadera.</p>
                <a href="{{ route('fincas.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-base inline-flex items-center gap-2">
                    <span>+</span> Registrar finca
                </a>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

<script>
    document.getElementById('filtroNombre').addEventListener('input', aplicarFiltros);
    document.getElementById('filtroTipo').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroArchivado').addEventListener('change', aplicarFiltros);

    function aplicarFiltros() {
        const nombre = document.getElementById('filtroNombre').value.trim().toLowerCase();
        const tipo = document.getElementById('filtroTipo').value;
        const archivado = document.getElementById('filtroArchivado').value;

        let total = 0;
        let superficie = 0;
        const tiposSet = new Set();

        const rows = document.querySelectorAll('.fila-finca');

        rows.forEach(function (row) {
            const rowNombre = row.dataset.nombre || '';
            const rowTipo = row.dataset.tipo || '';
            const rowArchivado = row.dataset.archivado || 'false';
            const rowSuperficie = parseFloat(row.dataset.superficie) || 0;

            const matchNombre = (!nombre || rowNombre.includes(nombre));
            const matchTipo = (!tipo || rowTipo === tipo);
            const matchArchivado = (archivado === 'todos') || (rowArchivado === archivado);

            const ok = matchNombre && matchTipo && matchArchivado;

            row.style.display = ok ? '' : 'none';

            if (ok) {
                total++;
                superficie += rowSuperficie;
                if (rowTipo) tiposSet.add(rowTipo);
            }
        });

        // Actualizar KPIs
        const statTotal = document.getElementById('statTotal');
        const statSuperficie = document.getElementById('statSuperficie');
        const statPromedio = document.getElementById('statPromedio');
        const statTipos = document.getElementById('statTipos');

        if (statTotal) statTotal.textContent = total;
        if (statSuperficie) statSuperficie.textContent = superficie.toLocaleString('es-ES', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' ha';
        
        const promedio = total > 0 ? (superficie / total) : 0;
        if (statPromedio) statPromedio.textContent = promedio.toLocaleString('es-ES', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' ha';
        if (statTipos) statTipos.textContent = tiposSet.size;

        // Mostrar / ocultar fila vacía
        const emptyRow = document.getElementById('filasVacias');
        if (emptyRow) {
            if (total === 0 && rows.length > 0) {
                emptyRow.classList.remove('hidden');
            } else {
                emptyRow.classList.add('hidden');
            }
        }
    }

    function limpiarFiltros() {
        document.getElementById('filtroNombre').value = '';
        document.getElementById('filtroTipo').value = '';
        document.getElementById('filtroArchivado').value = 'false';
        if (window.history && window.history.replaceState) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        aplicarFiltros();
    }

    // Aplicar filtros iniciales
    document.addEventListener('DOMContentLoaded', aplicarFiltros);
</script>
@endsection