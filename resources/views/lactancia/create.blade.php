@extends('layouts.authenticated')

@section('title', 'Nuevo Período de Lactancia')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('lactancia.index') }}" 
                   class="text-ganaderasoft-azul hover:text-ganaderasoft-celeste transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-3xl font-bold text-ganaderasoft-negro">Nuevo Período de Lactancia</h2>
                    <p class="text-gray-600 mt-1">Registra un nuevo período de lactancia para un animal</p>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <p class="font-medium mb-2">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-md p-8">
            @php
                $borderClass = fn (string $field) => $errors->has($field) ? 'border-red-500' : 'border-gray-300';
            @endphp
            <form method="POST" action="{{ route('lactancia.store') }}" class="space-y-6">
                @csrf

                <!-- Animal Selection -->
                <div>
                    <label for="lactancia_etapa_anid" class="block text-sm font-medium text-gray-700 mb-2">
                        Animal <span class="text-red-500">*</span>
                    </label>
                            <select name="lactancia_etapa_anid" id="lactancia_etapa_anid" required
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste {{ $borderClass('lactancia_etapa_anid') }}">
                        <option value="">Seleccionar Animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ old('lactancia_etapa_anid') == $animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] }} - {{ $animal['codigo_animal'] ?? 'Sin código' }}
                            </option>
                        @endforeach
                    </select>
                    @error('lactancia_etapa_anid')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Etapa Animal -->
                <div>
                    <label for="lactancia_etapa_etid" class="block text-sm font-medium text-gray-700 mb-2">
                        Etapa Actual del Animal <span class="text-red-500">*</span>
                    </label>
                            <select name="lactancia_etapa_etid" id="lactancia_etapa_etid" required
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste {{ $borderClass('lactancia_etapa_etid') }}">
                        <option value="">Seleccione una etapa</option>
                    </select>
                    @error('lactancia_etapa_etid')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Se muestra automáticamente la etapa actual del animal seleccionado</p>
                </div>

                <!-- Fecha de Inicio -->
                <div>
                    <label for="lactancia_fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Inicio <span class="text-red-500">*</span>
                    </label>
                              <input type="date" 
                           name="lactancia_fecha_inicio" 
                           id="lactancia_fecha_inicio" 
                           value="{{ old('lactancia_fecha_inicio') }}" 
                           required
                                  class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste {{ $borderClass('lactancia_fecha_inicio') }}">
                    @error('lactancia_fecha_inicio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Fin -->
                <div>
                    <label for="Lactancia_fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Fin (Opcional)
                    </label>
                              <input type="date" 
                           name="Lactancia_fecha_fin" 
                           id="Lactancia_fecha_fin" 
                           value="{{ old('Lactancia_fecha_fin') }}"
                                  class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste {{ $borderClass('Lactancia_fecha_fin') }}">
                    @error('Lactancia_fecha_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Deja vacío si la lactancia está aún activa</p>
                </div>

                <!-- Fecha de Secado -->
                <div>
                    <label for="lactancia_secado" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Secado (Opcional)
                    </label>
                              <input type="date" 
                           name="lactancia_secado" 
                           id="lactancia_secado" 
                           value="{{ old('lactancia_secado') }}"
                                  class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste {{ $borderClass('lactancia_secado') }}">
                    @error('lactancia_secado')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Fecha en que el animal fue secado preparándolo para el próximo parto</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6">
                    <a href="{{ route('lactancia.index') }}" 
                       class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        Registrar Lactancia
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const animalSelect = document.getElementById('lactancia_etapa_anid');
    const etapaSelect = document.getElementById('lactancia_etapa_etid');
    if (!animalSelect || !etapaSelect) {
        return;
    }

    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    function resetEtapa(message = 'Seleccione una etapa') {
        etapaSelect.innerHTML = `<option value="">${message}</option>`;
        etapaSelect.value = '';
    }

    async function cargarEtapaActual(animalId) {
        resetEtapa('Cargando etapa actual...');

        try {
            const response = await fetch(endpointTemplate.replace('__ID__', animalId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const payload = await response.json();
            const animal = payload?.data?.animal || payload?.data || {};
            const etapaActual = payload?.data?.etapa_actual || payload?.data?.etapaActual || animal?.etapa_actual || animal?.etapaActual || null;
            const etapa = etapaActual?.etapa || etapaActual;
            const etapaId = etapa?.etapa_id || etapaActual?.etan_etapa_id || etapaActual?.etanEtapaId || null;
            const etapaNombre = etapa?.etapa_nombre || etapa?.Nombre || etapa?.nombre || etapa?.descripcion || etapaActual?.etapa_nombre || etapaActual?.nombre || null;

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
        } catch (error) {
            resetEtapa('Error al cargar etapa');
        }
    }

    animalSelect.addEventListener('change', function(e) {
        const animalId = e.target.value;
        if (!animalId) {
            resetEtapa();
            return;
        }
        cargarEtapaActual(animalId);
    });

    if (animalSelect.value) {
        cargarEtapaActual(animalSelect.value);
    }
});
</script>
@endpush