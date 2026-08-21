@extends('layouts.authenticated')

@section('title', 'Registrar nueva vacunación')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-verde/15 text-ganaderasoft-verde-oscuro flex items-center justify-center font-bold text-2xl shadow-sm border border-ganaderasoft-verde/20">
                💉
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Registrar nueva vacunación
                </h1>
                <p class="text-gray-500 text-sm mt-1">Aplica una dosis de vacuna a uno o múltiples animales del rebaño</p>
            </div>
        </div>
        <div>
            <a href="{{ route('vacunacion.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('vacunacion.store') }}" novalidate class="space-y-6" id="vacunacionForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Datos de la Vacunación -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🧪</span> Información del biológico y aplicación
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="vacuna_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Vacuna utilizada <span class="text-red-500">*</span>
                            </label>
                            <select id="vacuna_id" name="vacuna_id" required
                                    class="w-full px-4 py-3 border @error('vacuna_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Seleccione una vacuna...</option>
                                @foreach($vacunas as $vacuna)
                                    @php
                                        $vacId = $vacuna['id'] ?? $vacuna['vacuna_id'] ?? '';
                                        $vacNombre = $vacuna['nombre'] ?? $vacuna['vacuna_nombre'] ?? 'Vacuna';
                                    @endphp
                                    <option value="{{ $vacId }}" {{ old('vacuna_id') == $vacId ? 'selected' : '' }}>
                                        {{ $vacNombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vacuna_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="fecha" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de aplicación <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required
                                   class="w-full px-4 py-3 border @error('fecha') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('fecha')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="dosis" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Dosis por animal (ml)
                            </label>
                            <input type="number" step="0.01" min="0" id="dosis" name="dosis" value="{{ old('dosis') }}" placeholder="Ej: 2.50"
                                   class="w-full px-4 py-3 border @error('dosis') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('dosis')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="costo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Costo individual por dosis ($)
                            </label>
                            <input type="number" step="0.01" min="0" id="costo" name="costo" value="{{ old('costo', '0.00') }}" placeholder="0.00"
                                   class="w-full px-4 py-3 border @error('costo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('costo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="lote" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Número de lote
                            </label>
                            <input type="text" id="lote" name="lote" value="{{ old('lote') }}" placeholder="Ej: Lote-2026-X"
                                   class="w-full px-4 py-3 border @error('lote') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('lote')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Selección de Animales -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>🐮</span> Animales a vacunar <span class="text-red-500">*</span>
                        </h3>
                        <span id="selected-badge" class="px-3 py-1 bg-ganaderasoft-verde/15 text-ganaderasoft-verde-oscuro font-bold rounded-full text-xs">
                            0 Seleccionados
                        </span>
                    </div>

                    <!-- Filtros reactivos de selección de animales -->
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200/70 space-y-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Filtros rápidos de selección</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Finca</label>
                                <select id="filtro_finca" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-ganaderasoft-celeste bg-white">
                                    <option value="">Todas las fincas</option>
                                    @foreach($fincas as $finca)
                                        @php
                                            $fId = (string) ($finca['id'] ?? $finca['id_Finca'] ?? '');
                                            $fNombre = $finca['nombre'] ?? $finca['Nombre'] ?? ('Finca #'.$fId);
                                        @endphp
                                        <option value="{{ $fId }}">{{ $fNombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Rebaño</label>
                                <select id="filtro_rebano" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-ganaderasoft-celeste bg-white">
                                    <option value="">Todos los rebaños</option>
                                    @foreach($rebanos as $rebano)
                                        @php
                                            $rId = (string) ($rebano['id'] ?? $rebano['id_Rebano'] ?? '');
                                            $rNombre = $rebano['nombre'] ?? $rebano['Nombre'] ?? ('Rebaño #'.$rId);
                                            $rFinca = (string) (data_get($rebano, 'finca_id') ?? data_get($rebano, 'finca.id') ?? data_get($rebano, 'id_Finca') ?? '');
                                        @endphp
                                        <option value="{{ $rId }}" data-finca-id="{{ $rFinca }}" data-finca="{{ $rFinca }}">{{ $rNombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sexo</label>
                                <select id="filtro_sexo" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-ganaderasoft-celeste bg-white">
                                    <option value="">Todos</option>
                                    <option value="H">Hembras</option>
                                    <option value="M">Machos</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    @error('animal_ids')
                        <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror

                    <!-- Tabla Interactiva de Selección de Animales -->
                    <div class="max-h-80 overflow-y-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="sticky top-0 bg-gray-50">
                                <tr>
                                    <th class="w-12 px-4 py-3 text-left">
                                        <input type="checkbox" id="check-all" class="w-4 h-4 rounded border-gray-300 text-ganaderasoft-verde focus:ring-ganaderasoft-verde" title="Marcar/desmarcar todos">
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Animal</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Código</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Sexo</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rebaño</th>
                                </tr>
                            </thead>
                            <tbody id="animales-lista" class="divide-y divide-gray-100 bg-white text-sm">
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                        <p class="animate-pulse text-lg">⌛ Cargando animales disponibles...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card 3: Observaciones -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>📝</span> Observaciones o notas sanitarias
                    </h3>
                    <textarea name="observacion" rows="3" placeholder="Detalles de la jornada, reacciones observadas, estado de los animales..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('observacion') }}</textarea>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de la Jornada (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6 sticky top-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📊</span> Resumen de jornada
                    </h3>

                    <div class="space-y-4">
                        <div class="p-4 bg-ganaderasoft-celeste/10 rounded-xl border border-ganaderasoft-celeste/20 text-center">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Costo total estimado</p>
                            <p id="monto-total-label" class="text-3xl font-extrabold text-ganaderasoft-azul font-mono">0,00 $</p>
                            <p id="animales-count-label" class="text-xs text-gray-500 mt-1">0 Animales seleccionados</p>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 border-t border-gray-100 pt-4">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Costo unitario:</span>
                                <span id="resumen-costo-unitario" class="font-bold text-gray-900">0,00 $</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Animales a procesar:</span>
                                <span id="resumen-total-animales" class="font-bold text-gray-900">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 space-y-3">
                        <button type="submit"
                                class="w-full py-3.5 px-6 bg-ganaderasoft-verde-oscuro text-white font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Guardar vacunación
                        </button>
                        <a href="{{ route('vacunacion.index') }}"
                           class="w-full py-3 px-6 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const finca = document.getElementById('filtro_finca');
    const rebano = document.getElementById('filtro_rebano');
    const sexo = document.getElementById('filtro_sexo');
    const lista = document.getElementById('animales-lista');
    const checkAll = document.getElementById('check-all');
    const costoInput = document.getElementById('costo');
    const montoLabel = document.getElementById('monto-total-label');
    const countLabel = document.getElementById('animales-count-label');
    const badgeCount = document.getElementById('selected-badge');
    const resumenCosto = document.getElementById('resumen-costo-unitario');
    const resumenTotal = document.getElementById('resumen-total-animales');

    const endpoint = '{{ route('vacunacion.animales-elegibles') }}';

    // Lista original de opciones de rebaño para reconstrucción en caliente
    const listaRebanosOriginal = Array.from(rebano?.options || []).map(opt => ({
        value: (opt.value || '').toString().trim(),
        text: opt.textContent,
        fincaId: (opt.dataset.fincaId || opt.dataset.finca || opt.getAttribute('data-finca-id') || opt.getAttribute('data-finca') || '').toString().trim()
    }));

    function repopularRebanos() {
        if (!rebano || !finca) return;
        const fVal = (finca.value || '').toString().trim();
        const currentVal = (rebano.value || '').toString().trim();

        rebano.innerHTML = '';

        listaRebanosOriginal.forEach(r => {
            if (!r.value) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = r.text;
                rebano.appendChild(opt);
                return;
            }

            if (!fVal || r.fincaId === fVal) {
                const opt = document.createElement('option');
                opt.value = r.value;
                opt.textContent = r.text;
                opt.dataset.fincaId = r.fincaId;
                opt.dataset.finca = r.fincaId;
                opt.setAttribute('data-finca-id', r.fincaId);
                opt.setAttribute('data-finca', r.fincaId);
                if (r.value === currentVal) {
                    opt.selected = true;
                }
                rebano.appendChild(opt);
            }
        });

        // Si el valor seleccionado ya no existe en las opciones de esta finca, resetear
        const optionsValues = Array.from(rebano.options).map(o => o.value);
        if (!optionsValues.includes(rebano.value)) {
            rebano.value = '';
        }
    }

    function formatMoney(value) {
        return new Intl.NumberFormat('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value || 0) + ' $';
    }

    function checkedBoxes() {
        return Array.from(lista.querySelectorAll('input.animal-check:checked'));
    }

    function updateTotals() {
        const allBoxes = Array.from(lista.querySelectorAll('input.animal-check'));
        const count = checkedBoxes().length;
        const totalCount = allBoxes.length;
        const costo = parseFloat(costoInput.value) || 0;
        const total = count * costo;

        // Sincronizar el checkbox maestro "Marcar todos"
        if (totalCount > 0) {
            checkAll.checked = (count === totalCount);
            checkAll.indeterminate = (count > 0 && count < totalCount);
        } else {
            checkAll.checked = false;
            checkAll.indeterminate = false;
        }

        countLabel.textContent = `${count} animales seleccionados`;
        badgeCount.textContent = `${count} seleccionados`;
        resumenTotal.textContent = `${count}`;
        resumenCosto.textContent = formatMoney(costo);
        montoLabel.textContent = formatMoney(total);
    }

    function bindBoxes() {
        lista.querySelectorAll('input.animal-check').forEach((cb) => cb.addEventListener('change', updateTotals));
    }

    // Al cambiar Finca -> filtra rebaños y recarga animales
    if (finca) {
        finca.addEventListener('change', function() {
            repopularRebanos();
            cargarAnimales();
        });
    }

    // Al cambiar Rebaño -> autoselecciona su Finca asociada y recarga animales
    if (rebano) {
        rebano.addEventListener('change', function() {
            const selectedRebanoVal = (rebano.value || '').toString().trim();
            if (selectedRebanoVal && finca) {
                const opt = listaRebanosOriginal.find(r => r.value === selectedRebanoVal);
                if (opt && opt.fincaId && finca.value !== opt.fincaId) {
                    finca.value = opt.fincaId;
                    repopularRebanos();
                    rebano.value = selectedRebanoVal; // Asegurar que el rebaño permanezca seleccionado
                }
            }
            cargarAnimales();
        });
    }

    sexo?.addEventListener('change', cargarAnimales);

    async function cargarAnimales() {
        lista.innerHTML = `<tr><td colspan="5" class="px-6 py-6 text-center text-gray-400"><p class="animate-pulse">⌛ Cargando animales...</p></td></tr>`;

        const params = new URLSearchParams();
        if (finca.value) params.append('finca_id', finca.value);
        if (rebano.value) params.append('rebano_id', rebano.value);
        if (sexo.value) params.append('sexo', sexo.value);

        try {
            const response = await fetch(`${endpoint}?${params.toString()}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const json = await response.json();

            if (!json.success) {
                lista.innerHTML = `<tr><td colspan="5" class="px-6 py-6 text-center text-red-500 font-medium">${json.message || 'No se pudieron cargar los animales.'}</td></tr>`;
                updateTotals();
                return;
            }

            const animales = json.data || [];
            if (animales.length === 0) {
                lista.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-400"><p class="text-xl mb-1">📭</p><p>No se encontraron animales activos con estos filtros.</p></td></tr>';
                updateTotals();
                return;
            }

            lista.innerHTML = animales.map((a) => {
                const id = a.id || a.id_Animal;
                const nombre = a.nombre || a.Nombre || ('Animal #' + id);
                const codigo = a.codigo_animal || 'S/C';
                const sx = a.sexo || a.Sexo || '—';
                const sxLabel = sx === 'H' ? 'Hembra' : (sx === 'M' ? 'Macho' : sx);
                const rebNombre = a.rebano?.nombre || a.rebano?.Nombre || '—';

                return `<tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="px-4 py-3"><input type="checkbox" class="animal-check w-4 h-4 rounded border-gray-300 text-ganaderasoft-verde focus:ring-ganaderasoft-verde" name="animal_ids[]" value="${id}" checked></td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">${nombre}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">${codigo}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${sxLabel}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">${rebNombre}</td>
                </tr>`;
            }).join('');

            checkAll.checked = true;
            checkAll.indeterminate = false;
            bindBoxes();
            updateTotals();
        } catch (e) {
            lista.innerHTML = '<tr><td colspan="5" class="px-6 py-6 text-center text-red-500 font-medium">Error al comunicar con el servidor.</td></tr>';
        }
    }

    checkAll.addEventListener('change', function () {
        lista.querySelectorAll('input.animal-check').forEach((cb) => { cb.checked = checkAll.checked; });
        updateTotals();
    });

    costoInput.addEventListener('input', updateTotals);

    // Carga inicial automática de animales
    cargarAnimales();
})();
</script>
@endpush
