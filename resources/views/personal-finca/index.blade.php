@extends('layouts.authenticated')

@section('title', 'Personal de finca')

@section('content')
@php
    $totalPersonal = $estadisticas['total_personal'] ?? count($personalFinca);
    $personalActivo = $estadisticas['personal_activo'] ?? 0;
    $fincasConPersonal = $estadisticas['fincas_con_personal'] ?? 0;
    $totalTipos = $estadisticas['total_tipos'] ?? count($tiposTrabajador);
@endphp

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                👥
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Personal de finca
                </h1>
                <p class="text-gray-500 text-sm mt-1">Gestión de trabajadores, roles y asignación por unidad de producción</p>
            </div>
        </div>
        <div>
            <a href="{{ route('personal-finca.create') }}"
                class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-base inline-flex items-center gap-2">
                <span>+</span> Registrar personal
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

    <!-- Summary KPIs (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total personal</p>
                <p id="statTotal" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $totalPersonal }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-2xl border border-blue-100">
                👥
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Personal activo</p>
                <p id="statActivos" class="text-3xl font-extrabold text-emerald-600">{{ $personalActivo }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl border border-emerald-100">
                🟢
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fincas con personal</p>
                <p id="statFincas" class="text-3xl font-extrabold text-amber-600">{{ $fincasConPersonal }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl border border-amber-100">
                🏡
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Roles / Cargos</p>
                <p id="statRoles" class="text-3xl font-extrabold text-purple-600">{{ $totalTipos }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl border border-purple-100">
                💼
            </div>
        </div>
    </div>

    <!-- Filter Bar (4 Columnas) -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <!-- Buscar -->
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar empleado</label>
                <input type="text" id="filtroNombre" placeholder="Nombre, cédula, correo..." value="{{ $nombre ?? '' }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Filtrar por Finca -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                <select id="filtroFinca"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="" {{ ($fincaId === '' || $fincaId === null) ? 'selected' : '' }}>Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @php
                            $fId = $finca['id'] ?? null;
                            $fNombre = $finca['nombre'] ?? ('Finca #' . $fId);
                        @endphp
                        <option value="{{ $fId }}" {{ ($fincaId !== '' && (string)$fincaId === (string)$fId) ? 'selected' : '' }}>
                            {{ $fNombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtrar por Tipo de Trabajador -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Cargo / Rol</label>
                <select id="filtroTipo"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="" {{ ($tipoTrabajadorId === '' || $tipoTrabajadorId === null) ? 'selected' : '' }}>Todos los cargos</option>
                    @foreach($tiposTrabajador as $tipo)
                        @php
                            $tId = $tipo['id'] ?? null;
                            $tNombre = $tipo['nombre'] ?? '';
                        @endphp
                        <option value="{{ $tId }}" {{ ($tipoTrabajadorId !== '' && (string)$tipoTrabajadorId === (string)$tId) ? 'selected' : '' }}>{{ $tNombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtrar por Estado -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado</label>
                <select id="filtroEstado"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="activo" {{ ($statusFilter ?? 'activo') === 'activo' ? 'selected' : '' }}>Solo activos</option>
                    <option value="inactivo" {{ ($statusFilter ?? '') === 'inactivo' ? 'selected' : '' }}>Solo inactivos</option>
                    <option value="" {{ ($statusFilter ?? '') === '' ? 'selected' : '' }}>Todos los estados</option>
                </select>
            </div>

            <!-- Botón Limpiar -->
            <div>
                <button type="button" onclick="limpiarFiltros()"
                    class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px] cursor-pointer shadow-2xs">
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Personal Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100" id="tableContainer">
        @if(empty($personalFinca) || count($personalFinca) === 0)
            <div class="p-12 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center text-3xl shadow-xs">
                    👥
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-gray-900">No hay personal registrado</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto">Comienza registrando a los trabajadores, capataces, veterinarios e inseminadores de tus fincas.</p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('personal-finca.create') }}"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-xl hover:bg-opacity-90 transition-all font-semibold text-sm shadow-md hover:shadow-lg inline-flex items-center gap-2">
                        <span>+</span> Registrar nuevo personal
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Empleado
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Cédula
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Cargo / Rol
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Finca asignada
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Contacto
                            </th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaPersonal">
                        @foreach($personalFinca as $persona)
                            @php
                                $pId = $persona['id'] ?? null;

                                // Extraer Persona
                                $personaSub = $persona['persona'] ?? null;
                                $nombreEmp = $personaSub ? trim(($personaSub['nombre'] ?? '') . ' ' . ($personaSub['apellido'] ?? '')) : 'Personal';
                                $cedulaEmp = $personaSub['cedula'] ?? '-';
                                $telefonoEmp = $personaSub['telefono'] ?? '-';
                                $correoEmp = $personaSub['correo'] ?? '-';

                                $tipoObj = $persona['tipo_trabajador'] ?? null;
                                $tipoId = (string)($tipoObj['id'] ?? ($persona['tipo_trabajador_id'] ?? ''));
                                $tipoNombre = $tipoObj['nombre'] ?? 'Trabajador';

                                $fincaObj = $persona['finca'] ?? null;
                                $fincaIdAttr = (string)($persona['finca_id'] ?? ($fincaObj['id'] ?? ''));
                                $fincaNombre = $fincaObj['nombre'] ?? ('Finca #' . ($fincaIdAttr ?: 'N/A'));
                                $fincaTipo = $fincaObj['explotacion_tipo'] ?? 'General';
                                
                                $rawStatus = strtolower((string)($persona['status'] ?? 'activo'));
                                $statusStr = in_array($rawStatus, ['inactivo', '0', 'false'], true) ? 'inactivo' : 'activo';
                                $status = ($statusStr === 'activo');

                                $inicial = strtoupper(substr($nombreEmp ?: 'P', 0, 1));
                                
                                $searchableText = strtolower(implode(' ', array_filter([
                                    $nombreEmp,
                                    $cedulaEmp,
                                    $telefonoEmp,
                                    $correoEmp,
                                    $tipoNombre,
                                    $fincaNombre,
                                    '#'.$pId,
                                    (string)$pId
                                ])));
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors registro-personal" 
                                data-finca="{{ $fincaIdAttr }}"
                                data-tipo="{{ $tipoId }}" 
                                data-tipo-nombre="{{ strtolower($tipoNombre) }}"
                                data-status="{{ $statusStr }}"
                                data-nombre="{{ $searchableText }}">
                                
                                <!-- Empleado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 border border-teal-100 flex items-center justify-center font-bold text-base shadow-2xs shrink-0">
                                            {{ $inicial }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="font-bold text-gray-900 leading-tight truncate">{{ $nombreEmp }}</p>
                                            <p class="text-xs text-gray-400 font-mono mt-0.5">ID: #{{ $pId }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Cédula -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $cedulaEmp }}
                                    </span>
                                </td>

                                <!-- Cargo -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                        💼 {{ $tipoNombre }}
                                    </span>
                                </td>

                                <!-- Finca -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <p class="font-semibold text-gray-900 flex items-center gap-1.5">
                                            <span>🏡</span> {{ $fincaNombre }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $fincaTipo }}</p>
                                    </div>
                                </td>

                                <!-- Contacto -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    <div class="space-y-1">
                                        @if($telefonoEmp && $telefonoEmp !== '-')
                                            <p class="text-gray-700 flex items-center gap-1">
                                                <span class="text-gray-400">📞</span> {{ $telefonoEmp }}
                                            </p>
                                        @endif
                                        @if($correoEmp && $correoEmp !== '-')
                                            <p class="text-gray-500 flex items-center gap-1 truncate max-w-[180px]" title="{{ $correoEmp }}">
                                                <span class="text-gray-400">✉️</span> {{ $correoEmp }}
                                            </p>
                                        @endif
                                        @if((!$telefonoEmp || $telefonoEmp === '-') && (!$correoEmp || $correoEmp === '-'))
                                            <span class="text-gray-400 italic">Sin datos de contacto</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($status)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            🟢 Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                            ⚪ Inactivo
                                        </span>
                                    @endif
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Ver Detalle -->
                                        <a href="{{ route('personal-finca.show', $pId) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                           title="Ver ficha">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Editar -->
                                        <a href="{{ route('personal-finca.edit', $pId) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                           title="Editar personal">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Desactivar / Activar (Rutas dedicadas) -->
                                        @if($status)
                                            <form method="POST" action="{{ route('personal-finca.disable', $pId) }}" class="inline-block" id="form-toggle-{{ $pId }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" onclick="openGenericConfirmModal({
                                                    formId: 'form-toggle-{{ $pId }}',
                                                    intent: 'danger',
                                                    title: 'Desactivar personal de finca',
                                                    message: '¿Estás seguro de que deseas desactivar a {{ $nombreEmp }} de {{ $fincaNombre }}? Pasará al estado inactivo y no se contabilizará en los indicadores de campo.',
                                                    confirmText: 'Sí, desactivar'
                                                })"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                                    title="Desactivar empleado">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('personal-finca.enable', $pId) }}" class="inline-block" id="form-toggle-{{ $pId }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" onclick="openGenericConfirmModal({
                                                    formId: 'form-toggle-{{ $pId }}',
                                                    intent: 'success',
                                                    title: 'Activar personal de finca',
                                                    message: '¿Estás seguro de que deseas reactivar a {{ $nombreEmp }} en {{ $fincaNombre }}? Pasará al estado activo.',
                                                    confirmText: 'Sí, activar'
                                                })"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-colors"
                                                    title="Activar empleado">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
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
            </div>
        @endif
    </div>

    <!-- Empty filtered state -->
    <div id="emptyFilteredState" class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center space-y-3">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-50 text-gray-500 border border-gray-200 flex items-center justify-center text-2xl shadow-2xs">
            🔍
        </div>
        <div class="space-y-1">
            <h4 class="text-base font-bold text-gray-900">No se encontró personal</h4>
            <p class="text-sm text-gray-500 max-w-md mx-auto">No hay trabajadores que coincidan con los filtros aplicados. Intenta con otros criterios de búsqueda.</p>
        </div>
        <div class="pt-2">
            <button type="button" onclick="limpiarFiltros()"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-colors shadow-2xs">
                Restablecer filtros
            </button>
        </div>
    </div>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtroFinca = document.getElementById('filtroFinca');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroEstado = document.getElementById('filtroEstado');
    const filtroNombre = document.getElementById('filtroNombre');
    const tableContainer = document.getElementById('tableContainer');
    const emptyFiltered = document.getElementById('emptyFilteredState');

    function recalcularKpis(visibles) {
        const statTotal = document.getElementById('statTotal');
        const statActivos = document.getElementById('statActivos');
        const statFincas = document.getElementById('statFincas');
        const statRoles = document.getElementById('statRoles');

        if (!statTotal) return;

        let total = visibles.length;
        let activos = 0;
        let fincasSet = new Set();
        let rolesSet = new Set();

        visibles.forEach(row => {
            const status = row.getAttribute('data-status');
            if (status === 'activo') activos++;

            const fId = row.getAttribute('data-finca');
            if (fId) fincasSet.add(fId);

            const tId = row.getAttribute('data-tipo');
            if (tId) rolesSet.add(tId);
        });

        statTotal.textContent = total;
        if (statActivos) statActivos.textContent = activos;
        if (statFincas) statFincas.textContent = fincasSet.size;
        if (statRoles) statRoles.textContent = rolesSet.size;
    }

    function aplicarFiltros() {
        const finca = (filtroFinca ? filtroFinca.value : '').trim();
        const tipo = (filtroTipo ? filtroTipo.value : '').trim();
        const estado = (filtroEstado ? filtroEstado.value : '').trim();
        const nombre = (filtroNombre ? filtroNombre.value : '').toLowerCase().trim();

        let visibleCount = 0;
        const visibleRows = [];

        document.querySelectorAll('.registro-personal').forEach(function (row) {
            const rowFinca = (row.getAttribute('data-finca') || '').trim();
            const rowTipo = (row.getAttribute('data-tipo') || '').trim();
            const rowEstado = (row.getAttribute('data-status') || '').trim();
            const rowNombre = (row.getAttribute('data-nombre') || '').toLowerCase().trim();

            const matchFinca = !finca || rowFinca === finca;
            const matchTipo = !tipo || rowTipo === tipo;
            const matchEstado = estado === '' || rowEstado === estado;
            const matchNombre = !nombre || rowNombre.includes(nombre);

            const isVisible = matchFinca && matchTipo && matchEstado && matchNombre;

            row.style.display = isVisible ? '' : 'none';
            if (isVisible) {
                visibleCount++;
                visibleRows.push(row);
            }
        });

        if (emptyFiltered) {
            const totalRows = document.querySelectorAll('.registro-personal').length;
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

    filtroFinca?.addEventListener('change', aplicarFiltros);
    filtroTipo?.addEventListener('change', aplicarFiltros);
    filtroEstado?.addEventListener('change', aplicarFiltros);
    filtroNombre?.addEventListener('input', aplicarFiltros);

    window.limpiarFiltros = function () {
        if (filtroFinca) filtroFinca.value = '';
        if (filtroTipo) filtroTipo.value = '';
        if (filtroEstado) filtroEstado.value = 'activo';
        if (filtroNombre) filtroNombre.value = '';
        if (window.history && window.history.replaceState) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        aplicarFiltros();
    };

    // Aplicar filtros iniciales
    aplicarFiltros();
});
</script>
@endsection