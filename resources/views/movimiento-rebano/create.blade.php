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
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Origen y Destino -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2 border-b border-gray-100 pb-4">
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
                                        <option value="{{ $finca['id'] }}" {{ (string)old('finca_id') === (string)$finca['id'] ? 'selected' : '' }}>
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
                                        <option value="{{ $rebano['id'] }}" {{ (string)old('rebano_id') === (string)$rebano['id'] ? 'selected' : '' }}
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
                                        <option value="{{ $finca['id'] }}" {{ (string)old('finca_destino_id') === (string)$finca['id'] ? 'selected' : '' }}>
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
                                        <option value="{{ $rebano['id'] }}" {{ (string)old('rebano_destino_id') === (string)$rebano['id'] ? 'selected' : '' }}
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
                                   placeholder="Se autocompleta con el rebaño destino"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600 font-medium focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Comentario / Motivo</label>
                            <input type="text" name="comentario" id="comentario" value="{{ old('comentario') }}" maxlength="40"
                                   placeholder="Ej: Rotación de pastoreo, engorde..."
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('comentario')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Selección de Animales -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                                <span>🐄</span> Selección de animales
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Selecciona los ejemplares que formarán parte de este traslado</p>
                        </div>

                        <!-- Search Animal Input -->
                        <div class="w-full sm:w-64">
                            <div class="relative">
                                <input type="text" id="buscarAnimalInput" placeholder="Filtrar por nombre o código..."
                                       class="w-full px-3.5 py-2 pl-9 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Master Checkbox & Count Bar -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <label class="inline-flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" id="selectAllAnimales" class="rounded border-gray-300 text-ganaderasoft-verde-oscuro focus:ring-ganaderasoft-verde-oscuro h-4 w-4">
                            <span class="text-xs font-bold text-gray-700 select-none">Seleccionar todos los visibles</span>
                        </label>
                        <span id="labelAnimalesVisibles" class="text-xs text-gray-500 font-medium">
                            0 animales disponibles
                        </span>
                    </div>

                    <!-- Animal Items List Container -->
                    <div class="max-h-96 overflow-y-auto space-y-2 pr-1" id="animalesListContainer">
                        @php
                            $oldSelected = old('animales', []);
                            if (!is_array($oldSelected)) $oldSelected = [];
                        @endphp

                        @foreach($animales as $animal)
                            @php
                                $aId = $animal['id'] ?? null;
                                $aNombre = $animal['nombre'] ?? 'Animal #'.$aId;
                                $aCodigo = $animal['codigo_animal'] ?? '';
                                $aRebanoId = data_get($animal, 'rebano.id') ?? data_get($animal, 'rebano_id');
                                $aSexo = $animal['sexo'] ?? '';
                                $isSelected = in_array((string)$aId, array_map('strval', $oldSelected), true);
                            @endphp
                            @if($aId)
                                <label class="animal-item flex items-center justify-between p-3 border border-gray-200 hover:border-ganaderasoft-celeste hover:bg-ganaderasoft-celeste/5 rounded-xl cursor-pointer transition-all duration-150"
                                       data-rebano-id="{{ $aRebanoId }}"
                                       data-search="{{ strtolower($aNombre.' '.$aCodigo.' #'.$aId) }}"
                                       style="display: none;">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" name="animales[]" value="{{ $aId }}" {{ $isSelected ? 'checked' : '' }}
                                               class="animal-checkbox rounded border-gray-300 text-ganaderasoft-verde-oscuro focus:ring-ganaderasoft-verde-oscuro h-4 w-4">
                                        <div class="w-8 h-8 rounded-lg {{ $aSexo === 'M' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }} font-bold flex items-center justify-center text-sm">
                                            {{ $aSexo === 'M' ? '🐂' : '🐄' }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $aNombre }}</p>
                                            @if($aCodigo)
                                                <p class="text-xs font-mono text-gray-500">Código: #{{ $aCodigo }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2.5">
                                        <span class="text-xs font-mono font-semibold text-gray-400">ID #{{ $aId }}</span>
                                        <a href="{{ route('animales.show', $aId) }}" target="_blank"
                                           onclick="event.stopPropagation();"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors shrink-0"
                                           title="Ver expediente del animal">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </label>
                            @endif
                        @endforeach

                        <!-- Empty Placeholder when no rebano selected -->
                        <div id="noRebanoSelectedPlaceholder" class="p-8 text-center bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                            <span class="text-3xl mb-2 block">👈</span>
                            <p class="text-sm font-bold text-gray-700">Selecciona el rebaño de origen</p>
                            <p class="text-xs text-gray-500 mt-1">Los animales asociados al rebaño de origen se cargarán automáticamente aquí.</p>
                        </div>

                        <!-- Zero Search Results -->
                        <div id="noAnimalMatchesPlaceholder" class="hidden p-6 text-center bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                            <p class="text-xs text-gray-500">No hay animales que coincidan con la búsqueda en este rebaño.</p>
                        </div>
                    </div>
                    @error('animales')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Columna Derecha: Panel de Resumen y Guardado (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-gray-50/80 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <span>📋</span> Resumen del traslado
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Origin Summary -->
                        <div class="p-3.5 bg-amber-50/60 rounded-xl border border-amber-100 text-xs space-y-1">
                            <span class="font-bold text-amber-900 uppercase tracking-wider text-[10px]">Origen</span>
                            <p class="font-bold text-gray-900" id="previewOrigenFinca">Finca: —</p>
                            <p class="text-amber-800" id="previewOrigenRebano">Rebaño: —</p>
                        </div>

                        <!-- Destination Summary -->
                        <div class="p-3.5 bg-blue-50/60 rounded-xl border border-blue-100 text-xs space-y-1">
                            <span class="font-bold text-blue-900 uppercase tracking-wider text-[10px]">Destino</span>
                            <p class="font-bold text-gray-900" id="previewDestinoFinca">Finca: —</p>
                            <p class="text-blue-800" id="previewDestinoRebano">Rebaño: —</p>
                        </div>

                        <!-- Animals Count -->
                        <div class="flex items-center justify-between p-3.5 bg-green-50/60 rounded-xl border border-green-100">
                            <div>
                                <span class="text-xs font-semibold text-green-900">Total a trasladar:</span>
                                <p class="text-2xl font-extrabold text-ganaderasoft-verde-oscuro" id="previewCountAnimales">0</p>
                            </div>
                            <span class="text-2xl">🐄</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit" id="btnSubmit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Registrar traslado
                            </button>

                            <a href="{{ route('movimiento-rebano.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fincaOrigenSelect    = document.getElementById('finca_id');
    const rebanoOrigenSelect   = document.getElementById('rebano_id');
    const fincaDestinoSelect   = document.getElementById('finca_destino_id');
    const rebanoDestinoSelect  = document.getElementById('rebano_destino_id');
    const rebanoDestinoInput   = document.getElementById('rebano_destino');
    const buscarAnimalInput    = document.getElementById('buscarAnimalInput');
    const selectAllAnimales    = document.getElementById('selectAllAnimales');
    const labelAnimalesVisibles= document.getElementById('labelAnimalesVisibles');
    const noRebanoPlaceholder  = document.getElementById('noRebanoSelectedPlaceholder');
    const noAnimalMatches      = document.getElementById('noAnimalMatchesPlaceholder');

    const previewOrigenFinca   = document.getElementById('previewOrigenFinca');
    const previewOrigenRebano  = document.getElementById('previewOrigenRebano');
    const previewDestinoFinca  = document.getElementById('previewDestinoFinca');
    const previewDestinoRebano = document.getElementById('previewDestinoRebano');
    const previewCountAnimales = document.getElementById('previewCountAnimales');

    const animalItems          = document.querySelectorAll('.animal-item');
    const animalCheckboxes     = document.querySelectorAll('.animal-checkbox');

    function filterSelectRebanos(selectRebano, fincaId) {
        if (!selectRebano) return;
        Array.from(selectRebano.options).forEach((opt, idx) => {
            if (idx === 0) return;
            const matches = !fincaId || opt.dataset.finca === fincaId;
            opt.style.display = matches ? '' : 'none';
        });
        if (selectRebano.value && selectRebano.options[selectRebano.selectedIndex]?.style.display === 'none') {
            selectRebano.value = '';
        }
    }

    function updateAnimalesList() {
        const selRebanoId = rebanoOrigenSelect.value;
        const query = buscarAnimalInput ? buscarAnimalInput.value.trim().toLowerCase() : '';

        if (!selRebanoId) {
            if (noRebanoPlaceholder) noRebanoPlaceholder.classList.remove('hidden');
            if (noAnimalMatches) noAnimalMatches.classList.add('hidden');
            animalItems.forEach(item => item.style.display = 'none');
            if (labelAnimalesVisibles) labelAnimalesVisibles.textContent = '0 animales disponibles';
            updateSelectedCount();
            return;
        }

        if (noRebanoPlaceholder) noRebanoPlaceholder.classList.add('hidden');

        let visibles = 0;
        animalItems.forEach(item => {
            const matchesRebano = item.dataset.rebanoId === selRebanoId;
            const matchesSearch = !query || (item.dataset.search && item.dataset.search.includes(query));

            if (matchesRebano && matchesSearch) {
                item.style.display = 'flex';
                visibles++;
            } else {
                item.style.display = 'none';
            }
        });

        if (labelAnimalesVisibles) {
            labelAnimalesVisibles.textContent = `${visibles} ${visibles === 1 ? 'animal disponible' : 'animales disponibles'}`;
        }

        if (noAnimalMatches) {
            if (visibles === 0) noAnimalMatches.classList.remove('hidden');
            else noAnimalMatches.classList.add('hidden');
        }

        updateSelectedCount();
    }

    function updateSelectedCount() {
        let count = 0;
        let visibleCount = 0;
        let visibleChecked = 0;

        animalItems.forEach(item => {
            if (item.style.display !== 'none') {
                visibleCount++;
                const cb = item.querySelector('.animal-checkbox');
                if (cb && cb.checked) {
                    visibleChecked++;
                }
            }
        });

        document.querySelectorAll('.animal-checkbox:checked').forEach(cb => {
            const parent = cb.closest('.animal-item');
            if (parent && parent.dataset.rebanoId === rebanoOrigenSelect.value) {
                count++;
            }
        });
        if (previewCountAnimales) previewCountAnimales.textContent = count;

        if (selectAllAnimales) {
            if (visibleCount > 0 && visibleChecked === visibleCount) {
                selectAllAnimales.checked = true;
                selectAllAnimales.indeterminate = false;
            } else if (visibleChecked > 0 && visibleChecked < visibleCount) {
                selectAllAnimales.checked = false;
                selectAllAnimales.indeterminate = true;
            } else {
                selectAllAnimales.checked = false;
                selectAllAnimales.indeterminate = false;
            }
        }
    }

    function updatePreviews() {
        if (previewOrigenFinca) {
            const fOpt = fincaOrigenSelect.options[fincaOrigenSelect.selectedIndex];
            previewOrigenFinca.textContent = `Finca: ${fincaOrigenSelect.value && fOpt ? fOpt.textContent.trim() : '—'}`;
        }
        if (previewOrigenRebano) {
            const rOpt = rebanoOrigenSelect.options[rebanoOrigenSelect.selectedIndex];
            previewOrigenRebano.textContent = `Rebaño: ${rebanoOrigenSelect.value && rOpt ? rOpt.textContent.trim() : '—'}`;
        }
        if (previewDestinoFinca) {
            const fDestOpt = fincaDestinoSelect.options[fincaDestinoSelect.selectedIndex];
            previewDestinoFinca.textContent = `Finca: ${fincaDestinoSelect.value && fDestOpt ? fDestOpt.textContent.trim() : '—'}`;
        }
        if (previewDestinoRebano) {
            const rDestOpt = rebanoDestinoSelect.options[rebanoDestinoSelect.selectedIndex];
            previewDestinoRebano.textContent = `Rebaño: ${rebanoDestinoSelect.value && rDestOpt ? rDestOpt.textContent.trim() : '—'}`;
        }
        if (rebanoDestinoInput && rebanoDestinoSelect) {
            const rDestOpt = rebanoDestinoSelect.options[rebanoDestinoSelect.selectedIndex];
            rebanoDestinoInput.value = (rebanoDestinoSelect.value && rDestOpt) ? rDestOpt.textContent.trim() : '';
        }
        updateSelectedCount();
    }

    // Event listeners
    if (fincaOrigenSelect) {
        fincaOrigenSelect.addEventListener('change', function () {
            filterSelectRebanos(rebanoOrigenSelect, this.value);
            updatePreviews();
            updateAnimalesList();
        });
    }

    if (rebanoOrigenSelect) {
        rebanoOrigenSelect.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.dataset.finca && fincaOrigenSelect) {
                fincaOrigenSelect.value = selectedOpt.dataset.finca;
                filterSelectRebanos(rebanoOrigenSelect, selectedOpt.dataset.finca);
            }
            updatePreviews();
            updateAnimalesList();
        });
    }

    if (fincaDestinoSelect) {
        fincaDestinoSelect.addEventListener('change', function () {
            filterSelectRebanos(rebanoDestinoSelect, this.value);
            updatePreviews();
        });
    }

    if (rebanoDestinoSelect) {
        rebanoDestinoSelect.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.dataset.finca && fincaDestinoSelect) {
                fincaDestinoSelect.value = selectedOpt.dataset.finca;
                filterSelectRebanos(rebanoDestinoSelect, selectedOpt.dataset.finca);
            }
            updatePreviews();
        });
    }

    if (buscarAnimalInput) {
        buscarAnimalInput.addEventListener('input', updateAnimalesList);
    }

    if (selectAllAnimales) {
        selectAllAnimales.addEventListener('change', function () {
            const isChecked = this.checked;
            animalItems.forEach(item => {
                if (item.style.display !== 'none') {
                    const cb = item.querySelector('.animal-checkbox');
                    if (cb) cb.checked = isChecked;
                }
            });
            updateSelectedCount();
        });
    }

    animalCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    // Initial setup
    if (fincaOrigenSelect && fincaOrigenSelect.value) {
        filterSelectRebanos(rebanoOrigenSelect, fincaOrigenSelect.value);
    }
    if (fincaDestinoSelect && fincaDestinoSelect.value) {
        filterSelectRebanos(rebanoDestinoSelect, fincaDestinoSelect.value);
    }
    updatePreviews();
    updateAnimalesList();
});
</script>
@endsection
