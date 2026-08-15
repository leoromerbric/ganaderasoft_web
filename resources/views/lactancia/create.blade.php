@extends('layouts.authenticated')

@section('title', 'Nuevo Período de Lactancia')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                🐄
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Nuevo Período de Lactancia
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra el inicio de un nuevo ciclo de producción láctea para una hembra</p>
            </div>
        </div>
        <div>
            <a href="{{ route('lactancia.index') }}" 
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

    <!-- Form Container -->
    <form action="{{ route('lactancia.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Selección de Hembra -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Seleccionar Hembra del Rebaño
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Animal Hembra <span class="text-red-500">*</span>
                            </label>
                            <select name="animal_id" id="animal_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('animal_id') border-red-500 @enderror">
                                <option value="">Seleccione una hembra...</option>
                                @foreach($animales as $animal)
                                    <option value="{{ $animal['id'] }}" {{ old('animal_id') == $animal['id'] ? 'selected' : '' }}
                                            data-nombre="{{ $animal['nombre'] ?? ('Animal #'.$animal['id']) }}"
                                            data-codigo="{{ $animal['codigo_animal'] ?? '' }}">
                                        {{ $animal['nombre'] ?? ('Animal #'.$animal['id']) }} ({{ $animal['codigo_animal'] ?? 'Sin código' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('animal_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Etapa Actual Activa <span class="text-red-500">*</span>
                            </label>
                            <select name="etapa_id" id="etapa_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('etapa_id') border-red-500 @enderror">
                                <option value="">Seleccione primero el animal</option>
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Se detecta automáticamente según la ficha del animal.</p>
                            @error('etapa_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Fechas del Ciclo -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📅</span> Fechas del Período de Lactancia
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de Inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" required value="{{ old('fecha_inicio', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_inicio') border-red-500 @enderror">
                            @error('fecha_inicio')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Fecha de Fin (Opcional)</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_fin') border-red-500 @enderror">
                            <p class="text-xs text-gray-400 mt-1">Vacío si está activa.</p>
                            @error('fecha_fin')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Fecha de Secado (Opcional)</label>
                            <input type="date" name="secado" id="secado" value="{{ old('secado') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('secado') border-red-500 @enderror">
                            <p class="text-xs text-gray-400 mt-1">Preparación para el próximo parto.</p>
                            @error('secado')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen del Registro
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Animal -->
                        <div class="p-4 bg-pink-50/60 border border-pink-100 rounded-2xl space-y-2">
                            <span class="text-xs font-bold text-pink-900 uppercase tracking-wider">Hembra Seleccionada:</span>
                            <p id="previewAnimalNombre" class="text-base font-bold text-gray-900">No seleccionada</p>
                            <p id="previewAnimalCodigo" class="text-xs text-gray-500 font-mono">-</p>
                        </div>

                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>Etapa:</span>
                                <span id="previewEtapa" class="font-semibold text-gray-900">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fecha Inicio:</span>
                                <span id="previewFechaInicio" class="font-semibold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estado Ciclo:</span>
                                <span id="previewEstado" class="font-bold text-emerald-600">🟢 Activa</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar Lactancia
                            </button>
                            <a href="{{ route('lactancia.index') }}"
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
document.addEventListener('DOMContentLoaded', function() {
    const animalSelect = document.getElementById('animal_id');
    const etapaSelect = document.getElementById('etapa_id');
    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');

    const previewNombre = document.getElementById('previewAnimalNombre');
    const previewCodigo = document.getElementById('previewAnimalCodigo');
    const previewEtapa  = document.getElementById('previewEtapa');
    const previewFechaInicio = document.getElementById('previewFechaInicio');
    const previewEstado = document.getElementById('previewEstado');

    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    function resetEtapa(message = 'Seleccione una etapa') {
        etapaSelect.innerHTML = `<option value="">${message}</option>`;
        etapaSelect.value = '';
        previewEtapa.textContent = '-';
    }

    async function cargarEtapaActual(animalId) {
        resetEtapa('Cargando etapa actual...');

        try {
            const response = await fetch(endpointTemplate.replace('__ID__', animalId), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const payload = await response.json();
            const animal = payload?.data?.animal || payload?.data || {};
            const etapaActual = payload?.data?.etapa_actual || animal?.etapa_actual || null;
            const etapa = etapaActual?.etapa || etapaActual;
            const etapaId = etapa?.id ?? etapaActual?.etapa_id ?? etapaActual?.id ?? null;
            const etapaNombre = etapa?.nombre ?? etapaActual?.nombre ?? null;

            if (!etapaId) {
                resetEtapa('Animal sin etapa activa');
                return;
            }

            resetEtapa();
            const option = document.createElement('option');
            option.value = etapaId;
            option.textContent = etapaNombre || 'Etapa actual';
            etapaSelect.appendChild(option);
            etapaSelect.value = String(etapaId);

            previewEtapa.textContent = option.textContent;
        } catch (error) {
            resetEtapa('Error al cargar etapa');
        }
    }

    function updateLivePreview() {
        const option = animalSelect.options[animalSelect.selectedIndex];
        if (!animalSelect.value) {
            previewNombre.textContent = 'No seleccionada';
            previewCodigo.textContent = '-';
        } else {
            previewNombre.textContent = option.dataset.nombre || option.textContent;
            previewCodigo.textContent = option.dataset.codigo ? '#' + option.dataset.codigo : '-';
        }

        if (fechaInicioInput.value) {
            const parts = fechaInicioInput.value.split('-');
            if (parts.length === 3) previewFechaInicio.textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
        }

        if (fechaFinInput.value) {
            previewEstado.textContent = '⚪ Finalizada';
            previewEstado.className = 'font-bold text-gray-600';
        } else {
            previewEstado.textContent = '🟢 Activa';
            previewEstado.className = 'font-bold text-emerald-600';
        }
    }

    animalSelect.addEventListener('change', function(e) {
        const animalId = e.target.value;
        if (!animalId) {
            resetEtapa();
            updateLivePreview();
            return;
        }
        cargarEtapaActual(animalId);
        updateLivePreview();
    });

    fechaInicioInput.addEventListener('change', updateLivePreview);
    fechaFinInput.addEventListener('change', updateLivePreview);

    if (animalSelect.value) {
        cargarEtapaActual(animalSelect.value);
        updateLivePreview();
    }
});
</script>
@endsection