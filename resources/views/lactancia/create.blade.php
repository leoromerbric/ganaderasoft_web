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
            <form method="POST" action="{{ route('lactancia.store') }}" class="space-y-6">
                @csrf

                <!-- Animal Selection -->
                <div>
                    <label for="lactancia_etapa_anid" class="block text-sm font-medium text-gray-700 mb-2">
                        Animal <span class="text-red-500">*</span>
                    </label>
                    <select name="lactancia_etapa_anid" id="lactancia_etapa_anid" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste @error('lactancia_etapa_anid') border-red-500 @enderror">
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

                <!-- Debug Info -->
                <div id="debug-info" class="mb-4" style="display: none;"></div>

                <!-- Etapa Animal -->
                <div>
                    <label for="lactancia_etapa_etid" class="block text-sm font-medium text-gray-700 mb-2">
                        Etapa Actual del Animal <span class="text-red-500">*</span>
                    </label>
                    <select name="lactancia_etapa_etid" id="lactancia_etapa_etid" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste @error('lactancia_etapa_etid') border-red-500 @enderror">
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
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste @error('lactancia_fecha_inicio') border-red-500 @enderror">
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
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste @error('Lactancia_fecha_fin') border-red-500 @enderror">
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
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste @error('lactancia_secado') border-red-500 @enderror">
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
                            class="px-6 py-3 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde-oscuro transition-colors duration-200 shadow-md hover:shadow-lg">
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
    // Función para mostrar información de debug
    function showDebug(message, isError = false) {
        const debugInfo = document.getElementById('debug-info');
        debugInfo.style.display = 'block';
        debugInfo.textContent = message;
        debugInfo.className = isError ? 
            'mb-2 p-2 bg-red-100 text-red-800 text-xs rounded' : 
            'mb-2 p-2 bg-blue-100 text-blue-800 text-xs rounded';
        console.log('DEBUG:', message);
    }
    
    // Filtrar etapas basándose en el animal seleccionado - SOLO ETAPA ACTUAL
    document.getElementById('lactancia_etapa_anid').addEventListener('change', function(e) {
        const animalSelect = e.target;
        const etapaSelect = document.getElementById('lactancia_etapa_etid');
        const selectedOption = animalSelect.options[animalSelect.selectedIndex];
        
        showDebug('Animal seleccionado, obteniendo solo su etapa actual...');
        
        // Reiniciar etapa
        etapaSelect.value = '';
        
        if (!animalSelect.value) {
            showDebug('No hay animal seleccionado');
            // Limpiar select y mostrar solo la opción por defecto
            etapaSelect.innerHTML = '<option value="">Seleccione una etapa</option>';
            document.getElementById('debug-info').style.display = 'none';
            return;
        }
        
        const animalId = animalSelect.value;
        showDebug(`Obteniendo etapa actual para animal ID: ${animalId}`);
        
        // Obtener etapa actual del animal vía AJAX
        fetch(`/lactancia/animal/${animalId}/etapa`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            showDebug(`AJAX Response status: ${response.status}`);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta completa del servidor:', data);
            
            if (data.success && data.data && data.data.etapa_actual) {
                const etapaActualData = data.data.etapa_actual;
                showDebug('✅ Etapa actual encontrada, procesando...');
                
                // La estructura puede ser directa o anidada
                let etapaActual;
                if (etapaActualData.etapa) {
                    etapaActual = etapaActualData.etapa;
                } else if (etapaActualData.etapa_id) {
                    etapaActual = etapaActualData;
                } else {
                    showDebug('ERROR: Estructura de etapa_actual no reconocida', true);
                    return;
                }
                
                showDebug(`✅ Etapa: ${etapaActual.etapa_nombre} (ID: ${etapaActual.etapa_id})`);
                
                // LIMPIAR el select y agregar SOLO la etapa actual
                etapaSelect.innerHTML = '<option value="">Seleccione una etapa</option>';
                
                const etapaOption = document.createElement('option');
                etapaOption.value = etapaActual.etapa_id;
                etapaOption.textContent = `${etapaActual.etapa_nombre} (ETAPA ACTUAL)`;
                etapaSelect.appendChild(etapaOption);
                
                showDebug(`✅ ¡LISTO! Solo se muestra: "${etapaOption.textContent}"`);
                
                // Auto-seleccionar la etapa actual
                etapaSelect.value = etapaActual.etapa_id;
                
                showDebug(`✅ Etapa preseleccionada automáticamente`);
                
            } else {
                showDebug('ERROR: No se encontró etapa_actual válida', true);
                etapaSelect.innerHTML = '<option value="">Sin etapa disponible</option>';
            }
        })
        .catch(error => {
            showDebug(`ERROR AJAX: ${error.message}`, true);
            console.error('Error obteniendo etapa del animal:', error);
            etapaSelect.innerHTML = '<option value="">Error al cargar etapa</option>';
        });
    });
});
</script>
@endpush