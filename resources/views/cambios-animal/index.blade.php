@extends('layouts.authenticated')

@section('title', 'Cambios de animal')

@section('content')
    <div class="space-y-8">
        <!-- Header section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Cambios de animal</h1>
                <p class="text-gray-500 text-sm mt-1">Gestiona y monitorea los cambios de etapa registrados por animal</p>
            </div>
            <a href="{{ route('cambios-animal.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
                + Registrar cambio
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
        @if(session('info'))
            <div class="p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">ℹ️</span>
                    <p class="text-sm font-medium">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Filters Bar -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                    <select id="filtroFinca"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todas las fincas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                    <select id="filtroRebano"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los rebaños</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Animal</label>
                    <select id="filtroAnimal"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los animales</option>
                        @foreach($animales as $animal)
                            @if(is_array($animal) && isset($animal['id']))
                                @php
                                    $anId = $animal['id'];
                                    $rId  = data_get($animal, 'rebano.id') ?? $animal['rebano_id'] ?? '';
                                    $rNm  = data_get($animal, 'rebano.nombre') ?? '';
                                    $fId  = data_get($animal, 'rebano.finca.id') ?? data_get($animal, 'rebano.finca_id') ?? '';
                                    $fNm  = data_get($animal, 'rebano.finca.nombre') ?? '';
                                @endphp
                                <option value="{{ $anId }}"
                                        data-rebano-id="{{ $rId }}"
                                        data-rebano-nombre="{{ $rNm }}"
                                        data-finca-id="{{ $fId }}"
                                        data-finca-nombre="{{ $fNm }}"
                                        {{ (string)$idAnimal === (string)$anId ? 'selected' : '' }}>
                                    {{ $animal['nombre'] ?? 'Animal #'.$anId }}
                                    @if(isset($animal['codigo_animal']))(#{{ $animal['codigo_animal'] }})@endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <a href="{{ route('cambios-animal.index') }}" onclick="limpiarFiltros(event)"
                       class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                        Limpiar filtros
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary KPIs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total cambios</p>
                    <p id="statTotalCambios" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $estadisticas['total_cambios'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                    📊
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Últimos 30 días</p>
                    <p id="statUltimos30" class="text-3xl font-extrabold text-ganaderasoft-verde-oscuro">{{ $estadisticas['ultimos_30_dias'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-ganaderasoft-verde/20 flex items-center justify-center text-2xl">
                    📅
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Peso promedio</p>
                    <p id="statPromPeso" class="text-3xl font-extrabold text-ganaderasoft-celeste">{{ $estadisticas['promedio_peso'] }} kg</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-2xl">
                    ⚖️
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Altura promedio</p>
                    <p id="statPromAltura" class="text-3xl font-extrabold text-purple-600">{{ $estadisticas['promedio_altura'] }} cm</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-2xl">
                    📏
                </div>
            </div>
        </div>

        <!-- Content Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if(empty($cambios))
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        🔍
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">No se encontraron registros</h3>
                    <p class="text-gray-500 text-sm mb-6">No hay cambios de etapa o medidas físicas registrados para el filtro seleccionado.</p>
                    <a href="{{ route('cambios-animal.create') }}" class="px-5 py-2.5 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl text-sm hover:bg-opacity-90 transition-all inline-block">
                        Registrar nuevo cambio
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Animal</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Etapa</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peso</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Altura</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Comentario</th>
                                <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @foreach($cambios as $cambio)
                                @if(is_array($cambio))
                                @php
                                    $nombreAnimal = $cambio['animal_nombre'] ?? (data_get($cambio, 'animal.nombre') ?? ($mapaAnimales[$cambio['animal_id'] ?? null] ?? 'Animal sin especificar'));
                                    $etapa = strtolower($cambio['etapa_cambio'] ?? '');
                                    $anId  = $cambio['animal_id'] ?? data_get($cambio, 'animal.id') ?? '';
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors fila-cambio"
                                    data-animal-id="{{ $anId }}"
                                    data-peso="{{ $cambio['peso'] ?? '' }}"
                                    data-altura="{{ $cambio['altura'] ?? '' }}"
                                    data-fecha="{{ isset($cambio['fecha_cambio']) ? substr($cambio['fecha_cambio'], 0, 10) : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ isset($cambio['fecha_cambio']) ? date('d/m/Y', strtotime($cambio['fecha_cambio'])) : '--/--/----' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-ganaderasoft-azul font-bold text-lg">
                                                🐄
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $nombreAnimal }}</p>
                                                <p class="text-xs text-gray-400">ID: #{{ $cambio['id'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full border
                                            {{ in_array($etapa, ['becerro','becerra']) ? 'bg-amber-50 text-amber-700 border-amber-200' : ($etapa === 'juvenil' ? 'bg-blue-50 text-blue-700 border-blue-200' : (in_array($etapa, ['adulto','adulta']) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-700 border-gray-200')) }}">
                                            {{ $cambio['etapa_cambio'] ?? 'Sin etapa' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        @if(!empty($cambio['peso']))
                                            <span class="font-bold text-ganaderasoft-verde-oscuro">{{ number_format($cambio['peso'], 1) }} kg</span>
                                        @else
                                            <span class="text-gray-400">No registrado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        @if(!empty($cambio['altura']))
                                            {{ number_format($cambio['altura'], 1) }} cm
                                        @else
                                            <span class="text-gray-400">No registrado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                        {{ $cambio['comentario'] ?? 'Sin observaciones' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('cambios-animal.show', $cambio['id']) }}" class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul font-semibold transition-colors inline-flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const f = document.getElementById('filtroFinca');
        const r = document.getElementById('filtroRebano');
        const a = document.getElementById('filtroAnimal');

        if (!f || !r || !a) return;

        const opts = Array.from(a.options).filter(o => !!o.value);
        const fM = {}, rM = {};

        opts.forEach(o => {
            const fi = o.dataset.fincaId, fn = o.dataset.fincaNombre;
            const ri = o.dataset.rebanoId, rn = o.dataset.rebanoNombre;
            if (fi && !fM[fi]) fM[fi] = fn || 'Finca #' + fi;
            if (ri && !rM[ri]) rM[ri] = { n: rn || 'Rebaño #' + ri, f: fi };
        });

        Object.keys(fM).sort((x, y) => fM[x].localeCompare(fM[y])).forEach(id => {
            const o = document.createElement('option');
            o.value = id; o.textContent = fM[id];
            f.appendChild(o);
        });

        Object.keys(rM).sort((x, y) => rM[x].n.localeCompare(rM[y].n)).forEach(id => {
            const o = document.createElement('option');
            o.value = id; o.textContent = rM[id].n; o.dataset.fincaId = rM[id].f;
            r.appendChild(o);
        });

        function recalcularEstadisticas() {
            const visibleRows = Array.from(document.querySelectorAll('.fila-cambio')).filter(row => row.style.display !== 'none');
            const total = visibleRows.length;

            let pesoSum = 0, pesoCount = 0;
            let altSum = 0, altCount = 0;

            visibleRows.forEach(row => {
                const p = parseFloat(row.dataset.peso);
                const al = parseFloat(row.dataset.altura);
                if (!isNaN(p) && p > 0) { pesoSum += p; pesoCount++; }
                if (!isNaN(al) && al > 0) { altSum += al; altCount++; }
            });

            const promPeso = pesoCount > 0 ? (pesoSum / pesoCount).toFixed(1) : '0.0';
            const promAlt = altCount > 0 ? (altSum / altCount).toFixed(1) : '0.0';

            const statTotal = document.getElementById('statTotalCambios');
            const statPeso = document.getElementById('statPromPeso');
            const statAlt = document.getElementById('statPromAltura');

            if (statTotal) statTotal.textContent = total;
            if (statPeso) statPeso.textContent = promPeso + ' kg';
            if (statAlt) statAlt.textContent = promAlt + ' cm';
        }

        function aplicarFiltros() {
            const fv = f.value;
            const rv = r.value;
            const av = a.value;

            Array.from(r.options).forEach(o => {
                if (o.value) o.hidden = !!(fv && o.dataset.fincaId !== fv);
            });
            if (r.value && r.options[r.selectedIndex]?.hidden) r.value = '';

            opts.forEach(o => {
                o.hidden = !!(fv && o.dataset.fincaId !== fv) || !!(r.value && o.dataset.rebanoId !== r.value);
            });
            if (a.value && a.options[a.selectedIndex]?.hidden) a.value = '';

            const allowedAnimals = {};
            if (av) {
                allowedAnimals[String(av)] = true;
            } else {
                opts.forEach(o => {
                    if (!o.hidden && o.value) allowedAnimals[String(o.value)] = true;
                });
            }

            document.querySelectorAll('.fila-cambio').forEach(row => {
                const visible = allowedAnimals[String(row.dataset.animalId)];
                row.style.display = visible ? '' : 'none';
            });

            recalcularEstadisticas();
        }

        f.addEventListener('change', aplicarFiltros);
        r.addEventListener('change', aplicarFiltros);
        a.addEventListener('change', aplicarFiltros);

        window.limpiarFiltros = function (e) {
            if (e && e.preventDefault) e.preventDefault();
            f.value = ''; r.value = ''; a.value = '';
            Array.from(r.options).forEach(o => { o.hidden = false; });
            opts.forEach(o => { o.hidden = false; });
            if (window.history && window.history.pushState) {
                window.history.pushState({}, '', '{{ route('cambios-animal.index') }}');
            }
            aplicarFiltros();
        };

        if (a.value) {
            const selectedOpt = opts.find(o => o.value === a.value);
            if (selectedOpt) {
                f.value = selectedOpt.dataset.fincaId || '';
                r.value = selectedOpt.dataset.rebanoId || '';
            }
            aplicarFiltros();
        }
    });
    </script>
@endsection