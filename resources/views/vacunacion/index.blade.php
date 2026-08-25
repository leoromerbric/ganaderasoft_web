@extends('layouts.authenticated')

@section('title', 'Gestión de vacunación')

@section('content')
<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de vacunación</h1>
            <p class="text-gray-500 text-sm mt-1">Registro, control sanitario e historial de dosis aplicadas al rebaño</p>
        </div>
        <a href="{{ route('vacunacion.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
            + Nueva vacunación
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
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                    <input type="text" id="filtroTexto" placeholder="Animal, código, lote..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Vacuna</label>
                    <select id="filtroVacuna"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todas las vacunas</option>
                        @foreach($vacunas as $vac)
                            @php
                                $vId = $vac['id'] ?? $vac['vacuna_id'] ?? '';
                                $vNombre = $vac['nombre'] ?? $vac['vacuna_nombre'] ?? 'Vacuna';
                            @endphp
                            <option value="{{ $vId }}">
                                {{ $vNombre }}
                            </option>
                        @endforeach
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
                            <option value="{{ $fId }}">
                                {{ $fNombre }}
                            </option>
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
                            <option value="{{ $rId }}" data-finca-id="{{ $rFincaId }}">
                                {{ $rNombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

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
                    <a href="{{ route('vacunacion.index') }}"
                       class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px]">
                        Limpiar filtros
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Vacunaciones -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($vacunaciones) > 0)
            <div class="overflow-x-auto" id="tableContainer">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Vacuna</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosis / lote</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Costo</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aplicador</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($vacunaciones as $item)
                            @php
                                $id = $item['id'] ?? null;
                                $animal = $item['animal'] ?? [];
                                $animalId = $item['animal_id'] ?? data_get($animal, 'id');
                                $animalNombre = data_get($animal, 'nombre') ?? ('Animal #'.$animalId);
                                $animalCodigo = data_get($animal, 'codigo_animal') ?? 'S/C';
                                $animalSexo = data_get($animal, 'sexo');
                                $isArchivado = (bool) data_get($animal, 'archivado', false);
                                $animalEstado = $isArchivado ? 'archivado' : 'activo';

                                $rebanoId = data_get($animal, 'rebano_id') ?? data_get($animal, 'rebano.id');
                                $rebanoNombre = data_get($animal, 'rebano.nombre') ?? ('Rebaño #'.$rebanoId);
                                $fincaId = data_get($animal, 'rebano.finca_id') ?? data_get($animal, 'rebano.finca.id');
                                $fincaNombre = data_get($animal, 'rebano.finca.nombre') ?? '';

                                $vacuna = $item['vacuna'] ?? [];
                                $vacunaId = $item['vacuna_id'] ?? data_get($vacuna, 'id');
                                $vacunaNombre = data_get($vacuna, 'nombre') ?? ('Vacuna #'.$vacunaId);

                                $aplicador = $item['aplicador'] ?? [];
                                $aplicadorNombre = $aplicador ? (trim((data_get($aplicador, 'nombre') ?? '').' '.(data_get($aplicador, 'apellido') ?? '')) ?: data_get($aplicador, 'cedula')) : null;

                                $fechaRaw = $item['fecha'] ?? null;
                                $fechaIso = $fechaRaw ? \Illuminate\Support\Carbon::parse($fechaRaw)->format('Y-m-d') : '';
                                $fechaFormat = $fechaRaw ? \Illuminate\Support\Carbon::parse($fechaRaw)->format('d/m/Y') : '-';

                                $dosis = $item['dosis'] ?? null;
                                $lote = $item['lote'] ?? null;
                                $costo = (float)($item['costo'] ?? 0);

                                $searchable = strtolower($animalNombre . ' ' . $animalCodigo . ' ' . $vacunaNombre . ' ' . $lote . ' ' . $rebanoNombre . ' ' . $fincaNombre);
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors fila-vacunacion"
                                data-texto="{{ $searchable }}"
                                data-vacuna="{{ $vacunaId }}"
                                data-finca="{{ $fincaId }}"
                                data-rebano="{{ $rebanoId }}"
                                data-fecha="{{ $fechaIso }}"
                                data-archivado="{{ $animalEstado }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 shrink-0 rounded-xl {{ $animalSexo === 'M' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-lg">
                                            {{ $animalSexo === 'M' ? '🐂' : '🐄' }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                            <p class="text-xs text-gray-400">
                                                ID: #{{ $animalId }} • Código: {{ $animalCodigo }}
                                                @if($rebanoNombre) • {{ $rebanoNombre }} @endif
                                                @if($fincaNombre) ({{ $fincaNombre }}) @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold border border-emerald-200">
                                        💉 {{ $vacunaNombre }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                    {{ $fechaFormat }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-gray-900 font-medium">{{ $dosis ? $dosis . ' ml' : 'Dosis estándar' }}</p>
                                    @if($lote)
                                        <span class="text-xs text-gray-500 font-mono">Lote: {{ $lote }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">Sin lote</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    {{ number_format($costo, 2, ',', '.') }} $
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ $aplicadorNombre ?: 'No especificado' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <div class="flex justify-center space-x-2">
                                        <!-- Botón de Ver Detalles -->
                                        <a href="{{ route('vacunacion.show', $id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                           title="Ver detalle">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Editar -->
                                        <a href="{{ route('vacunacion.edit', $id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                           title="Editar registro">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Eliminar -->
                                        <form method="POST" action="{{ route('vacunacion.destroy', $id) }}" class="inline-block" id="form-delete-{{ $id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="openGenericConfirmModal({
                                                formId: 'form-delete-{{ $id }}',
                                                intent: 'danger',
                                                title: 'Eliminar registro de vacunación',
                                                message: '¿Estás seguro de que deseas eliminar este registro de vacunación? Esta acción no se puede deshacer.',
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

            <!-- Mensaje de Sin Resultados Filtrados -->
            <div id="sinResultadosFiltro" class="hidden p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-2xl shadow-2xs">
                    🔍
                </div>
                <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">No se encontraron vacunaciones</h4>
                <p class="text-gray-500 text-xs mb-4">No hay registros que coincidan con los filtros aplicados.</p>
                <button type="button" onclick="limpiarFiltros(event)"
                        class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-xs inline-flex items-center gap-1.5 cursor-pointer">
                    Limpiar filtros
                </button>
            </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-3xl">
                        💉
                    </div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay vacunaciones registradas</h3>
                    <p class="text-gray-500 text-sm mb-6">Prueba cambiando los filtros o registra una nueva vacunación.</p>
                    <a href="{{ route('vacunacion.create') }}"
                       class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        + Nueva vacunación
                    </a>
                </div>
            @endif
        </div>
    </div>

    <x-ui.confirm-modal />

    <script>
        const filtroTexto = document.getElementById('filtroTexto');
        const filtroVacuna = document.getElementById('filtroVacuna');
        const filtroFinca = document.getElementById('filtroFinca');
        const filtroRebano = document.getElementById('filtroRebano');
        const filtroFechaInicio = document.getElementById('filtroFechaInicio');
        const filtroFechaFin = document.getElementById('filtroFechaFin');
        const filtroArchivado = document.getElementById('filtroArchivado');

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

        filtroTexto?.addEventListener('input', aplicarFiltros);
        filtroVacuna?.addEventListener('change', aplicarFiltros);
        
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

        function aplicarFiltros() {
            const texto = (filtroTexto?.value || '').toLowerCase().trim();
            const vacuna = (filtroVacuna?.value || '').toString();
            const finca = (filtroFinca?.value || '').toString();
            const rebano = (filtroRebano?.value || '').toString();
            const fechaInicio = filtroFechaInicio?.value || '';
            const fechaFin = filtroFechaFin?.value || '';
            const archivado = filtroArchivado?.value || '';
            const sinResultados = document.getElementById('sinResultadosFiltro');
            const tableContainer = document.getElementById('tableContainer');
            const filas = document.querySelectorAll('.fila-vacunacion');

            let totalVisibles = 0;

            filas.forEach(function(row) {
                const rowTexto = row.getAttribute('data-texto') || '';
                const rowVacuna = (row.getAttribute('data-vacuna') || '').toString();
                const rowFinca = (row.getAttribute('data-finca') || '').toString();
                const rowRebano = (row.getAttribute('data-rebano') || '').toString();
                const rowFecha = row.getAttribute('data-fecha') || '';
                const rowArchivado = row.getAttribute('data-archivado') || '';

                const matchTexto = !texto || rowTexto.includes(texto);
                const matchVacuna = !vacuna || rowVacuna === vacuna;
                const matchFinca = !finca || rowFinca === finca;
                const matchRebano = !rebano || rowRebano === rebano;
                const matchFechaInicio = !fechaInicio || rowFecha >= fechaInicio;
                const matchFechaFin = !fechaFin || rowFecha <= fechaFin;
                const matchArchivado = !archivado || rowArchivado === archivado;

                const matchesAll = matchTexto && matchVacuna && matchFinca && matchRebano && matchFechaInicio && matchFechaFin && matchArchivado;
                row.style.display = matchesAll ? '' : 'none';
                if (matchesAll) totalVisibles++;
            });

            if (sinResultados) {
                const isEmpty = totalVisibles === 0 && filas.length > 0;
                sinResultados.classList.toggle('hidden', !isEmpty);
                if (tableContainer) {
                    tableContainer.classList.toggle('hidden', isEmpty);
                }
            }
        }

        window.limpiarFiltros = function(e) {
            if (e) e.preventDefault();
            if (filtroTexto) filtroTexto.value = '';
            if (filtroVacuna) filtroVacuna.value = '';
            if (filtroFinca) filtroFinca.value = '';
            repopularRebanosPorFinca();
            if (filtroRebano) filtroRebano.value = '';
            if (filtroFechaInicio) filtroFechaInicio.value = '';
            if (filtroFechaFin) filtroFechaFin.value = '';
            if (filtroArchivado) filtroArchivado.value = '';
            aplicarFiltros();
        };
    </script>
@endsection