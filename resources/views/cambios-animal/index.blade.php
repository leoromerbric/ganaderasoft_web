@extends('layouts.authenticated')

@section('title', 'Historial de cambios de animal')

@section('content')
<div class="space-y-8">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-sm border border-ganaderasoft-celeste/20">
                📝
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Historial de cambios de animal
                </h1>
                <p class="text-gray-500 text-sm mt-1">Monitoreo de desarrollo, transiciones de etapa y registros biométricos del rebaño</p>
            </div>
        </div>
        <a href="{{ route('cambios-animal.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
            + Nuevo cambio
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Registros -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total cambios</p>
                <p class="text-3xl font-extrabold text-ganaderasoft-negro" id="kpiTotalCambios">
                    {{ $estadisticas['total_cambios'] ?? count($cambios ?? []) }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Registros en sistema</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/10 text-ganaderasoft-azul flex items-center justify-center text-xl font-bold">
                📊
            </div>
        </div>

        <!-- Últimos 30 días -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Últimos 30 días</p>
                <p class="text-3xl font-extrabold text-ganaderasoft-azul" id="kpiRecientes">
                    {{ $estadisticas['ultimos_30_dias'] ?? 0 }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Actividad reciente</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                ⏱️
            </div>
        </div>

        <!-- Promedio Peso -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Promedio peso</p>
                <p class="text-3xl font-extrabold text-emerald-600" id="kpiPromedioPeso">
                    {{ number_format($estadisticas['promedio_peso'] ?? 0, 1) }} <span class="text-sm font-normal text-gray-500">kg</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Muestra general</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                ⚖️
            </div>
        </div>

        <!-- Promedio Altura -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Promedio altura</p>
                <p class="text-3xl font-extrabold text-purple-600" id="kpiPromedioAltura">
                    {{ number_format($estadisticas['promedio_altura'] ?? 0, 1) }} <span class="text-sm font-normal text-gray-500">cm</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Crecimiento estimado</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                📐
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Búsqueda por Nombre o Código -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre o código del animal</label>
                <div class="relative">
                    <input type="text" id="filtroBuscarAnimal"
                           placeholder="Buscar por nombre o código..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:bg-white transition-all">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Filtro Finca -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Finca</label>
                <select id="filtroFinca"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:bg-white transition-all">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @if(is_array($finca) && isset($finca['id']))
                            <option value="{{ $finca['id'] }}" {{ $idFinca == $finca['id'] ? 'selected' : '' }}>
                                {{ $finca['nombre'] ?? ('Finca #' . $finca['id']) }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Filtro Rebaño -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Rebaño</label>
                <select id="filtroRebano"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:bg-white transition-all">
                    <option value="">Todos los rebaños</option>
                    @foreach($rebanos as $rebano)
                        @if(is_array($rebano) && isset($rebano['id']))
                            @php
                                $rFincaId = $rebano['finca_id'] ?? data_get($rebano, 'finca.id') ?? '';
                            @endphp
                            <option value="{{ $rebano['id'] }}" 
                                    data-finca="{{ $rFincaId }}"
                                    {{ $idRebano == $rebano['id'] ? 'selected' : '' }}>
                                {{ $rebano['nombre'] ?? ('Rebaño #' . $rebano['id']) }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Botón Limpiar -->
            <div>
                <button type="button" onclick="limpiarFiltros(event)"
                        class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if(!empty($cambios) && count($cambios) > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="tablaCambios">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="flex justify-between items-center w-full">
                            <th class="w-1/6 px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha / ID</th>
                            <th class="w-1/5 px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="w-1/6 px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Etapa</th>
                            <th class="w-1/6 px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peso / Altura</th>
                            <th class="w-1/5 px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Comentarios</th>
                            <th class="w-1/6 px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($cambios as $cambio)
                            @if(is_array($cambio))
                                @php
                                    $idCambio     = $cambio['id'] ?? 'N/A';
                                    $anId         = $cambio['animal_id'] ?? data_get($cambio, 'animal.id') ?? '';
                                    $nombreAnimal = $cambio['animal_nombre'] ?? data_get($cambio, 'animal.nombre') ?? ($anId ? ('Animal #'.$anId) : 'Animal no asignado');
                                    $codigoAnimal = data_get($cambio, 'animal.codigo_animal') ?? '';
                                    $sexoAnimal   = data_get($cambio, 'animal.sexo') ?? '';
                                    $fincaId      = data_get($cambio, 'animal.rebano.finca_id') ?? data_get($cambio, 'animal.rebano.finca.id') ?? '';
                                    $rebanoId     = data_get($cambio, 'animal.rebano_id') ?? data_get($cambio, 'animal.rebano.id') ?? '';
                                    $etapa        = strtolower($cambio['etapa_cambio'] ?? '');
                                    $fechaCambio  = isset($cambio['fecha_cambio']) ? date('d/m/Y', strtotime($cambio['fecha_cambio'])) : '--/--/----';
                                    $peso         = !empty($cambio['peso']) ? (float)$cambio['peso'] : null;
                                    $altura       = !empty($cambio['altura']) ? (float)$cambio['altura'] : null;
                                    $comentario   = $cambio['comentario'] ?? '';
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors fila-cambio flex justify-between items-center w-full"
                                    data-animal-id="{{ $anId }}"
                                    data-animal-nombre="{{ strtolower($nombreAnimal) }}"
                                    data-animal-codigo="{{ strtolower($codigoAnimal) }}"
                                    data-finca-id="{{ $fincaId }}"
                                    data-rebano-id="{{ $rebanoId }}"
                                    data-peso="{{ $peso ?? '' }}"
                                    data-altura="{{ $altura ?? '' }}"
                                    data-fecha="{{ isset($cambio['fecha_cambio']) ? substr($cambio['fecha_cambio'], 0, 10) : '' }}">
                                    
                                    <!-- Fecha / ID -->
                                    <td class="w-1/6 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 shrink-0 rounded-xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-lg border border-ganaderasoft-celeste/20">
                                                📝
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="font-bold text-gray-900 font-mono text-sm">#{{ $idCambio }}</p>
                                                <p class="text-xs text-gray-400 font-sans">📅 {{ $fechaCambio }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Animal -->
                                    <td class="w-1/5 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                        <div class="overflow-hidden">
                                            <p class="font-bold text-gray-900 text-sm truncate">{{ $nombreAnimal }}</p>
                                            <p class="text-xs text-gray-400 font-mono truncate">
                                                {{ $codigoAnimal ? 'Código: #'.$codigoAnimal : ($anId ? 'ID Animal: #'.$anId : 'Sin ID') }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Etapa -->
                                    <td class="w-1/6 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                        <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full border
                                            {{ in_array($etapa, ['becerro','becerra']) ? 'bg-amber-50 text-amber-700 border-amber-200' : ($etapa === 'juvenil' ? 'bg-blue-50 text-blue-700 border-blue-200' : (in_array($etapa, ['adulto','adulta']) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-700 border-gray-200')) }}">
                                            {{ $cambio['etapa_cambio'] ?? 'Sin etapa' }}
                                        </span>
                                    </td>

                                    <!-- Peso / Altura -->
                                    <td class="w-1/6 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                        <div class="text-xs space-y-0.5">
                                            <p class="font-semibold text-gray-800">
                                                ⚖️ {{ $peso ? number_format($peso, 1) . ' kg' : '--' }}
                                            </p>
                                            <p class="text-gray-500">
                                                📐 {{ $altura ? number_format($altura, 1) . ' cm' : '--' }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Comentarios -->
                                    <td class="w-1/5 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                        @if(!empty($comentario))
                                            <span class="text-xs text-gray-600 truncate block max-w-xs" title="{{ $comentario }}">
                                                💬 {{ $comentario }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Sin observaciones</span>
                                        @endif
                                    </td>

                                    <!-- Acciones -->
                                    <td class="w-1/6 px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center">
                                            <a href="{{ route('cambios-animal.show', $idCambio) }}"
                                               class="w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white flex items-center justify-center transition-all duration-150"
                                               title="Ver detalle del cambio">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Estado Vacío cuando los filtros no coinciden -->
            <div id="sinResultadosFiltro" class="hidden p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center text-3xl">
                    🔍
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">No se encontraron cambios</h3>
                <p class="text-xs text-gray-500 mb-4">No hay registros que coincidan con los filtros seleccionados</p>
                <button type="button" onclick="limpiarFiltros(event)"
                        class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl text-xs font-semibold transition-colors inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Restablecer filtros
                </button>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                    📝
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay cambios de animal registrados</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando la primera transición de etapa o medida de desarrollo del rebaño</p>
                <a href="{{ route('cambios-animal.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                    + Registrar cambio
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtroFinca         = document.getElementById('filtroFinca');
    const filtroRebano        = document.getElementById('filtroRebano');
    const filtroBuscarAnimal  = document.getElementById('filtroBuscarAnimal');
    const sinResultadosFiltro = document.getElementById('sinResultadosFiltro');
    const tablaCambios        = document.getElementById('tablaCambios');

    function filterSelectRebanos(fincaId) {
        if (!filtroRebano) return;
        Array.from(filtroRebano.options).forEach((opt, idx) => {
            if (idx === 0) return;
            const matches = !fincaId || opt.dataset.finca === fincaId;
            opt.style.display = matches ? '' : 'none';
        });
        if (filtroRebano.value && filtroRebano.options[filtroRebano.selectedIndex]?.style.display === 'none') {
            filtroRebano.value = '';
        }
    }

    function recalcularEstadisticas() {
        const visibleRows = Array.from(document.querySelectorAll('.fila-cambio')).filter(row => row.style.display !== 'none');
        const total = visibleRows.length;

        let pesoSum = 0, pesoCount = 0;
        let altSum = 0, altCount = 0;
        let ultimos30Count = 0;

        const now = new Date();
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(now.getDate() - 30);

        visibleRows.forEach(row => {
            const p = parseFloat(row.dataset.peso);
            const al = parseFloat(row.dataset.altura);
            const fStr = row.dataset.fecha;

            if (!isNaN(p) && p > 0) { pesoSum += p; pesoCount++; }
            if (!isNaN(al) && al > 0) { altSum += al; altCount++; }

            if (fStr) {
                const rowDate = new Date(fStr);
                if (!isNaN(rowDate.getTime()) && rowDate >= thirtyDaysAgo) {
                    ultimos30Count++;
                }
            }
        });

        const kpiTotal = document.getElementById('kpiTotalCambios');
        const kpiRec   = document.getElementById('kpiRecientes');
        const kpiPeso  = document.getElementById('kpiPromedioPeso');
        const kpiAlt   = document.getElementById('kpiPromedioAltura');

        if (kpiTotal) kpiTotal.textContent = total;
        if (kpiRec)   kpiRec.textContent = ultimos30Count;
        if (kpiPeso)  kpiPeso.innerHTML = `${pesoCount > 0 ? (pesoSum / pesoCount).toFixed(1) : '0.0'} <span class="text-sm font-normal text-gray-500">kg</span>`;
        if (kpiAlt)   kpiAlt.innerHTML = `${altCount > 0 ? (altSum / altCount).toFixed(1) : '0.0'} <span class="text-sm font-normal text-gray-500">cm</span>`;
    }

    function aplicarFiltros() {
        const fId = filtroFinca ? String(filtroFinca.value).trim() : '';
        const rId = filtroRebano ? String(filtroRebano.value).trim() : '';
        const search = filtroBuscarAnimal ? String(filtroBuscarAnimal.value).toLowerCase().trim() : '';

        let total = 0;

        document.querySelectorAll('.fila-cambio').forEach(row => {
            const rowFinca  = String(row.dataset.fincaId || '').trim();
            const rowRebano = String(row.dataset.rebanoId || '').trim();
            const rowNombre = String(row.dataset.animalNombre || '').toLowerCase();
            const rowCodigo = String(row.dataset.animalCodigo || '').toLowerCase();

            const matchFinca  = !fId || rowFinca === fId;
            const matchRebano = !rId || rowRebano === rId;
            const matchAnimal = !search || rowNombre.includes(search) || rowCodigo.includes(search);

            const visible = matchFinca && matchRebano && matchAnimal;
            row.style.display = visible ? '' : 'none';
            if (visible) total++;
        });

        recalcularEstadisticas();

        if (sinResultadosFiltro && tablaCambios) {
            const thead = tablaCambios.querySelector('thead');
            if (total === 0) {
                sinResultadosFiltro.classList.remove('hidden');
                if (thead) thead.classList.add('hidden');
            } else {
                sinResultadosFiltro.classList.add('hidden');
                if (thead) thead.classList.remove('hidden');
            }
        }
    }

    // Event listeners
    if (filtroFinca) {
        filtroFinca.addEventListener('change', function () {
            filterSelectRebanos(this.value);
            aplicarFiltros();
        });
    }

    if (filtroRebano) {
        filtroRebano.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.dataset.finca && filtroFinca) {
                filtroFinca.value = selectedOpt.dataset.finca;
                filterSelectRebanos(selectedOpt.dataset.finca);
            }
            aplicarFiltros();
        });
    }

    if (filtroBuscarAnimal) {
        filtroBuscarAnimal.addEventListener('input', aplicarFiltros);
    }

    window.limpiarFiltros = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        if (filtroFinca)        filtroFinca.value        = '';
        if (filtroRebano)       filtroRebano.value       = '';
        if (filtroBuscarAnimal) filtroBuscarAnimal.value = '';

        filterSelectRebanos('');

        if (window.history && window.history.pushState) {
            window.history.pushState({}, '', '{{ route('cambios-animal.index') }}');
        }
        aplicarFiltros();
    };

    // Initial setup
    if (filtroFinca && filtroFinca.value) {
        filterSelectRebanos(filtroFinca.value);
    }
    aplicarFiltros();
});
</script>
@endsection