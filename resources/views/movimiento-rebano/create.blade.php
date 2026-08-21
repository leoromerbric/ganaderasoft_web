@extends('layouts.authenticated')

@section('title', 'Nuevo movimiento de rebaño')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                🔄
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Nuevo movimiento de rebaño
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra y transfiere animales de forma masiva entre rebaños y fincas</p>
            </div>
        </div>
        <div>
            <a href="{{ route('movimiento-rebano.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm">
            <p class="text-sm font-bold mb-1">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm pl-2 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('movimiento-rebano.store') }}" method="POST" id="movimiento-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Configuración del Movimiento y Selección de Animales (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Origen y Destino -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>🚚</span> Configuración del traslado
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Box Origen -->
                        <div class="p-5 bg-amber-50/60 border border-amber-100 rounded-2xl space-y-4">
                            <div class="flex items-center gap-2 border-b border-amber-200/60 pb-2">
                                <span class="text-lg">🏡</span>
                                <h4 class="text-sm font-bold text-amber-900 uppercase tracking-wider">Ubicación de origen</h4>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Finca origen <span class="text-red-500">*</span></label>
                                <select name="finca_id" id="finca_id" required
                                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                                    <option value="">Seleccione finca origen</option>
                                    @foreach($fincas as $finca)
                                        <option value="{{ $finca['id'] }}" {{ old('finca_id') == $finca['id'] ? 'selected' : '' }}>
                                            {{ $finca['nombre'] ?? 'Finca #'.$finca['id'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('finca_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Rebaño origen <span class="text-red-500">*</span></label>
                                <select name="rebano_id" id="rebano_id" required
                                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                                    <option value="">Seleccione rebaño origen</option>
                                    @foreach($rebanos as $rebano)
                                        @php
                                            $fId = data_get($rebano, 'finca.id', $rebano['finca_id'] ?? '');
                                        @endphp
                                        <option value="{{ $rebano['id'] }}" {{ old('rebano_id') == $rebano['id'] ? 'selected' : '' }}
                                                data-finca="{{ $fId }}">
                                            {{ $rebano['nombre'] ?? 'Rebaño #'.$rebano['id'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rebano_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Box Destino -->
                        <div class="p-5 bg-blue-50/60 border border-blue-100 rounded-2xl space-y-4">
                            <div class="flex items-center gap-2 border-b border-blue-200/60 pb-2">
                                <span class="text-lg">🎯</span>
                                <h4 class="text-sm font-bold text-blue-900 uppercase tracking-wider">Ubicación de destino</h4>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Finca destino <span class="text-red-500">*</span></label>
                                <select name="finca_destino_id" id="finca_destino_id" required
                                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="">Seleccione finca destino</option>
                                    @foreach($fincas as $finca)
                                        <option value="{{ $finca['id'] }}" {{ old('finca_destino_id') == $finca['id'] ? 'selected' : '' }}>
                                            {{ $finca['nombre'] ?? 'Finca #'.$finca['id'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('finca_destino_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Rebaño destino <span class="text-red-500">*</span></label>
                                <select name="rebano_destino_id" id="rebano_destino_id" required
                                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="">Seleccione rebaño destino</option>
                                    @foreach($rebanos as $rebano)
                                        @php
                                            $fId = data_get($rebano, 'finca.id', $rebano['finca_id'] ?? '');
                                        @endphp
                                        <option value="{{ $rebano['id'] }}" {{ old('rebano_destino_id') == $rebano['id'] ? 'selected' : '' }}
                                                data-finca="{{ $fId }}">
                                            {{ $rebano['nombre'] ?? 'Rebaño #'.$rebano['id'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rebano_destino_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Campos auxiliares y observaciones -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Nombre rebaño destino</label>
                            <input type="text" name="rebano_destino" id="rebano_destino" value="{{ old('rebano_destino') }}" maxlength="30" readonly
                                   placeholder="Se llena automáticamente..."
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-600 font-semibold">
                            @error('rebano_destino')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Comentarios / observaciones</label>
                            <input type="text" name="comentario" value="{{ old('comentario') }}" maxlength="40"
                                   placeholder="Comentario adicional del movimiento..."
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('comentario')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Selección Interactiva de Animales -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                                <span>🐄</span> Seleccionar animales
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Seleccione los animales pertenecientes al rebaño de origen</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" id="btn-select-all" class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-semibold transition-colors">
                                Seleccionar todos
                            </button>
                            <button type="button" id="btn-deselect-all" class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-xs font-semibold transition-colors">
                                Desmarcar
                            </button>
                        </div>
                    </div>

                    <!-- Buscador rápido interno -->
                    <div class="relative">
                        <input type="text" id="search-animales" placeholder="🔍 Buscar por nombre o código del animal..."
                               class="w-full pl-4 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all">
                    </div>

                    <!-- Contenedor con Tarjetas Interactivas de Animales -->
                    @if(count($animales) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-80 overflow-y-auto p-1" id="animales-grid">
                            @foreach($animales as $animal)
                                @php
                                    $rId = data_get($animal, 'rebano.id', $animal['rebano_id'] ?? '');
                                    $fId = data_get($animal, 'rebano.finca.id', data_get($animal, 'rebano.finca_id', ''));
                                    $rNombre = data_get($animal, 'rebano.nombre', 'Sin Rebaño');
                                    $checked = in_array($animal['id'], old('animales', []));
                                @endphp
                                <label class="animal-card relative flex items-center p-3 rounded-2xl border border-gray-200 hover:border-ganaderasoft-celeste/60 cursor-pointer transition-all duration-200 group {{ $checked ? 'bg-blue-50/60 border-ganaderasoft-celeste shadow-xs' : 'bg-white hover:bg-gray-50/80' }}"
                                       data-rebano="{{ $rId }}"
                                       data-finca="{{ $fId }}"
                                       data-nombre="{{ strtolower($animal['nombre'] ?? '') }}"
                                       data-codigo="{{ strtolower($animal['codigo_animal'] ?? '') }}">
                                    <input type="checkbox" name="animales[]" value="{{ $animal['id'] }}" {{ $checked ? 'checked' : '' }}
                                           class="animal-checkbox w-4 h-4 text-ganaderasoft-celeste border-gray-300 rounded focus:ring-ganaderasoft-celeste mr-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-gray-900 truncate">
                                            🐄 {{ $animal['nombre'] ?? 'Animal #'.$animal['id'] }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate flex items-center gap-1 mt-0.5">
                                            <span class="font-mono">#{{ $animal['codigo_animal'] ?? $animal['id'] }}</span>
                                            <span>•</span>
                                            <span class="truncate">{{ $rNombre }}</span>
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <div id="animales-empty-state" class="p-8 text-center bg-gray-50 rounded-2xl" style="display: none;">
                            <p class="text-gray-500 text-sm font-medium" id="animales-empty-msg">Seleccione un rebaño origen para ver sus animales.</p>
                        </div>
                    @else
                        <div class="p-8 text-center bg-gray-50 rounded-2xl">
                            <p class="text-gray-500 text-sm font-medium">No hay animales disponibles en el sistema.</p>
                        </div>
                    @endif

                    @error('animales')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Columna Derecha: Panel de Resumen y Confirmación (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-4 flex items-center gap-2">
                        <span>📊</span> Resumen del movimiento
                    </h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-gray-500">Finca origen</span>
                            <span class="font-bold text-gray-900 truncate max-w-[140px]" id="summary-finca-origen">No seleccionada</span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-gray-500">Rebaño origen</span>
                            <span class="font-bold text-gray-900 truncate max-w-[140px]" id="summary-rebano-origen">No seleccionado</span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-gray-500">Finca destino</span>
                            <span class="font-bold text-ganaderasoft-azul truncate max-w-[140px]" id="summary-finca-destino">No seleccionada</span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-gray-500">Rebaño destino</span>
                            <span class="font-bold text-ganaderasoft-azul truncate max-w-[140px]" id="summary-rebano-destino">No seleccionado</span>
                        </div>

                        <div class="p-4 bg-blue-50/70 border border-blue-100 rounded-2xl text-center space-y-1">
                            <p class="text-xs font-bold text-blue-700 uppercase tracking-wider">Animales a trasladar</p>
                            <p class="text-3xl font-extrabold text-blue-800" id="summary-count-animales">0</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2">
                        <button type="submit" id="btn-guardar-movimiento"
                                class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                            <span>+</span> Registrar movimiento
                        </button>

                        <a href="{{ route('movimiento-rebano.index') }}"
                           class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fincaOrigen = document.getElementById('finca_id');
    const rebanoOrigen = document.getElementById('rebano_id');
    const fincaDestino = document.getElementById('finca_destino_id');
    const rebanoDestino = document.getElementById('rebano_destino_id');
    const nombreRebanoDestino = document.getElementById('rebano_destino');
    const animalesCards = Array.from(document.querySelectorAll('.animal-card'));
    const searchInput = document.getElementById('search-animales');
    const btnSelectAll = document.getElementById('btn-select-all');
    const btnDeselectAll = document.getElementById('btn-deselect-all');

    // Summary Elements
    const summaryFincaOrigen = document.getElementById('summary-finca-origen');
    const summaryRebanoOrigen = document.getElementById('summary-rebano-origen');
    const summaryFincaDestino = document.getElementById('summary-finca-destino');
    const summaryRebanoDestino = document.getElementById('summary-rebano-destino');
    const summaryCountAnimales = document.getElementById('summary-count-animales');

    function filterRebanos(select, fincaId, excludedRebanoId = null) {
        Array.from(select.options).forEach((option, index) => {
            if (index === 0) {
                option.hidden = false;
                return;
            }

            const belongs = !fincaId || option.dataset.finca === fincaId;
            const excluded = excludedRebanoId && option.value === excludedRebanoId;
            option.hidden = !belongs || excluded;
        });

        if (select.selectedOptions[0]?.hidden) {
            select.value = '';
        }
    }

    function syncSummary() {
        const fOrigenOpt = fincaOrigen.options[fincaOrigen.selectedIndex];
        const rOrigenOpt = rebanoOrigen.options[rebanoOrigen.selectedIndex];
        const fDestinoOpt = fincaDestino.options[fincaDestino.selectedIndex];
        const rDestinoOpt = rebanoDestino.options[rebanoDestino.selectedIndex];

        summaryFincaOrigen.textContent = fOrigenOpt && fOrigenOpt.value ? fOrigenOpt.text.trim() : 'No seleccionada';
        summaryRebanoOrigen.textContent = rOrigenOpt && rOrigenOpt.value ? rOrigenOpt.text.trim() : 'No seleccionado';
        summaryFincaDestino.textContent = fDestinoOpt && fDestinoOpt.value ? fDestinoOpt.text.trim() : 'No seleccionada';
        summaryRebanoDestino.textContent = rDestinoOpt && rDestinoOpt.value ? rDestinoOpt.text.trim() : 'No seleccionado';

        nombreRebanoDestino.value = rDestinoOpt && rDestinoOpt.value ? rDestinoOpt.text.trim() : '';

        const selectedCount = document.querySelectorAll('.animal-checkbox:checked').length;
        summaryCountAnimales.textContent = selectedCount;
    }

    function filterAnimales() {
        const rebanoId = rebanoOrigen.value;
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;

        animalesCards.forEach((card) => {
            const matchesRebano = rebanoId && card.dataset.rebano === rebanoId;
            const matchesQuery = !query || card.dataset.nombre.includes(query) || card.dataset.codigo.includes(query);
            const visible = matchesRebano && matchesQuery;

            card.style.display = visible ? '' : 'none';
            if (visible) {
                visibleCount++;
            } else {
                const checkbox = card.querySelector('.animal-checkbox');
                if (checkbox && checkbox.checked) {
                    checkbox.checked = false;
                    card.classList.remove('bg-blue-50/60', 'border-ganaderasoft-celeste', 'shadow-xs');
                    card.classList.add('bg-white');
                }
            }
        });

        const emptyState = document.getElementById('animales-empty-state');
        const emptyMsg = document.getElementById('animales-empty-msg');
        const grid = document.getElementById('animales-grid');
        
        if (emptyState && emptyMsg) {
            if (!rebanoId) {
                emptyMsg.textContent = 'Seleccione un rebaño origen para ver sus animales.';
                emptyState.style.display = 'block';
                if (grid) grid.style.display = 'none';
            } else if (visibleCount === 0) {
                emptyMsg.textContent = query ? 'No se encontraron animales que coincidan con la búsqueda.' : 'El rebaño seleccionado no tiene animales.';
                emptyState.style.display = 'block';
                if (grid) grid.style.display = 'none';
            } else {
                emptyState.style.display = 'none';
                if (grid) grid.style.display = 'grid'; // Maintain grid layout
            }
        }

        syncSummary();
    }

    // Toggle styling on checkbox click
    animalesCards.forEach((card) => {
        const checkbox = card.querySelector('.animal-checkbox');
        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                card.classList.add('bg-blue-50/60', 'border-ganaderasoft-celeste', 'shadow-xs');
                card.classList.remove('bg-white');
            } else {
                card.classList.remove('bg-blue-50/60', 'border-ganaderasoft-celeste', 'shadow-xs');
                card.classList.add('bg-white');
            }
            syncSummary();
        });
    });

    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function () {
            animalesCards.forEach((card) => {
                if (card.style.display !== 'none') {
                    const checkbox = card.querySelector('.animal-checkbox');
                    if (checkbox) {
                        checkbox.checked = true;
                        card.classList.add('bg-blue-50/60', 'border-ganaderasoft-celeste', 'shadow-xs');
                        card.classList.remove('bg-white');
                    }
                }
            });
            syncSummary();
        });
    }

    if (btnDeselectAll) {
        btnDeselectAll.addEventListener('click', function () {
            animalesCards.forEach((card) => {
                const checkbox = card.querySelector('.animal-checkbox');
                if (checkbox) {
                    checkbox.checked = false;
                    card.classList.remove('bg-blue-50/60', 'border-ganaderasoft-celeste', 'shadow-xs');
                    card.classList.add('bg-white');
                }
            });
            syncSummary();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterAnimales);
    }

    fincaOrigen.addEventListener('change', function () {
        filterRebanos(rebanoOrigen, fincaOrigen.value);
        filterRebanos(rebanoDestino, fincaDestino.value, rebanoOrigen.value);
        filterAnimales();
        syncSummary();
    });

    rebanoOrigen.addEventListener('change', function () {
        // Auto-seleccionar Finca Origen si no está seleccionada o es diferente
        const selectedOption = rebanoOrigen.options[rebanoOrigen.selectedIndex];
        if (selectedOption && selectedOption.dataset.finca) {
            if (fincaOrigen.value !== selectedOption.dataset.finca) {
                fincaOrigen.value = selectedOption.dataset.finca;
            }
        }

        filterRebanos(rebanoDestino, fincaDestino.value, rebanoOrigen.value);
        if (rebanoDestino.value === rebanoOrigen.value) {
            rebanoDestino.value = '';
        }
        filterAnimales();
        syncSummary();
    });

    fincaDestino.addEventListener('change', function () {
        filterRebanos(rebanoDestino, fincaDestino.value, rebanoOrigen.value);
        syncSummary();
    });

    rebanoDestino.addEventListener('change', function() {
        // Auto-seleccionar Finca Destino si no está seleccionada o es diferente
        const selectedOption = rebanoDestino.options[rebanoDestino.selectedIndex];
        if (selectedOption && selectedOption.dataset.finca) {
            if (fincaDestino.value !== selectedOption.dataset.finca) {
                fincaDestino.value = selectedOption.dataset.finca;
            }
        }
        syncSummary();
    });

    filterRebanos(rebanoOrigen, fincaOrigen.value);
    filterRebanos(rebanoDestino, fincaDestino.value, rebanoOrigen.value);
    filterAnimales();
    syncSummary();

    const form = document.getElementById('movimiento-form');
    const submitBtn = document.getElementById('btn-guardar-movimiento');
    if (form && submitBtn) {
        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Guardando...';
        });
    }
});
</script>
@endsection
