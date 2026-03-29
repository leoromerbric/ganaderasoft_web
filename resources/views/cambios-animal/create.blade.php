@extends('layouts.authenticated')

@section('title', 'Nuevo Cambio de Animal - GanaderaSoft')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('cambios-animal.index') }}" 
               class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">📝 Nuevo Cambio de Animal</h1>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-md">
            <!-- Header del formulario -->
            <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-lg">
                <h2 class="text-lg font-semibold">Registro de Cambio de Etapa</h2>
                <p class="text-sm opacity-90 mt-1">Registre los cambios de desarrollo y etapa del animal</p>
            </div>

            <form action="{{ route('cambios-animal.store') }}" method="POST" class="p-6">
                @csrf

                <!-- Mensajes de error generales -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">Hay errores en el formulario:</p>
                                <ul class="ml-6 list-disc">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Información Principal -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-ganaderasoft-negro border-b pb-2">Información Principal</h3>

                        <!-- Animal -->
                        <div>
                            <label for="cambios_etapa_anid" class="block text-sm font-medium text-gray-700 mb-1">
                                Animal <span class="text-red-500">*</span>
                            </label>
                            <select name="cambios_etapa_anid" id="cambios_etapa_anid" 
                                    class="form-select w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('cambios_etapa_anid') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccione un animal</option>
                                @if(is_array($animales))
                                    @foreach($animales as $animal)
                                        @if(is_array($animal) && isset($animal['id_Animal']))
                                            <option value="{{ $animal['id_Animal'] }}" 
                                                    {{ old('cambios_etapa_anid') == $animal['id_Animal'] ? 'selected' : '' }}
                                                    data-sexo="{{ $animal['Sexo'] ?? '' }}"
                                                    data-tipo-animal="{{ $animal['fk_composicion_raza'] ?? '3' }}">
                                                {{ $animal['Nombre'] ?? 'Animal #' . $animal['id_Animal'] }}
                                                @if(isset($animal['rebano']['finca']['Nombre']))
                                                    - {{ $animal['rebano']['finca']['Nombre'] }}
                                                @endif
                                                @if(isset($animal['Sexo']))
                                                    ({{ $animal['Sexo'] === 'M' ? 'Macho' : 'Hembra' }})
                                                @endif
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            @error('cambios_etapa_anid')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Seleccione el animal al que se le registrará el cambio</p>
                        </div>

                        <!-- Etapa -->
                        <div>
                            <label for="cambios_etapa_etid" class="block text-sm font-medium text-gray-700 mb-1">
                                Nueva Etapa <span class="text-red-500">*</span>
                            </label>
                            
                            <!-- Debug info -->
                            <div id="debug-info" class="mb-2 p-2 bg-blue-100 text-blue-800 text-xs rounded" style="display: none;">
                                <strong>Debug:</strong> <span id="debug-text">Selecciona un animal para ver su etapa actual</span>
                            </div>
                            
                            <select name="cambios_etapa_etid" id="cambios_etapa_etid" 
                                    class="form-select w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('cambios_etapa_etid') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccione una etapa</option>
                                @if(is_array($etapas))
                                    @foreach($etapas as $etapa)
                                        @if(is_array($etapa) && isset($etapa['etapa_id']))
                                            <option value="{{ $etapa['etapa_id'] }}" 
                                                    {{ old('cambios_etapa_etid') == $etapa['etapa_id'] ? 'selected' : '' }}
                                                    data-sexo="{{ $etapa['etapa_sexo'] ?? '' }}"
                                                    data-tipo-animal="{{ $etapa['etapa_fk_tipo_animal_id'] ?? '3' }}"
                                                    data-edad-ini="{{ $etapa['etapa_edad_ini'] ?? '' }}"
                                                    data-edad-fin="{{ $etapa['etapa_edad_fin'] ?? '' }}">
                                                {{ $etapa['etapa_nombre'] ?? 'Etapa' }}
                                                @if(isset($etapa['etapa_sexo']))
                                                    ({{ $etapa['etapa_sexo'] === 'M' ? 'Macho' : 'Hembra' }})
                                                @endif
                                                @if(isset($etapa['etapa_edad_ini']) && isset($etapa['etapa_edad_fin']))
                                                    - {{ $etapa['etapa_edad_ini'] }} a {{ $etapa['etapa_edad_fin'] }} días
                                                @endif
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            @error('cambios_etapa_etid')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Seleccione la nueva etapa de desarrollo</p>
                        </div>

                        <!-- Nombre de Etapa -->
                        <div>
                            <label for="Etapa_Cambio" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre de la Etapa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Etapa_Cambio" id="Etapa_Cambio" 
                                   value="{{ old('Etapa_Cambio') }}"
                                   class="form-input w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Etapa_Cambio') border-red-500 @enderror"
                                   placeholder="Ej: Juvenil, Adulto, etc."
                                   maxlength="50"
                                   required>
                            @error('Etapa_Cambio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Solo letras y espacios (máximo 50 caracteres)</p>
                        </div>

                        <!-- Fecha del Cambio -->
                        <div>
                            <label for="Fecha_Cambio" class="block text-sm font-medium text-gray-700 mb-1">
                                Fecha del Cambio <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="Fecha_Cambio" id="Fecha_Cambio" 
                                   value="{{ old('Fecha_Cambio', date('Y-m-d')) }}"
                                   class="form-input w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Fecha_Cambio') border-red-500 @enderror"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('Fecha_Cambio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">No puede ser una fecha futura</p>
                        </div>
                    </div>

                    <!-- Medidas Físicas -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-ganaderasoft-negro border-b pb-2">Medidas Físicas (Opcional)</h3>

                        <!-- Peso -->
                        <div>
                            <label for="Peso" class="block text-sm font-medium text-gray-700 mb-1">
                                Peso (kg)
                            </label>
                            <div class="relative">
                                <input type="number" name="Peso" id="Peso" 
                                       value="{{ old('Peso') }}"
                                       class="form-input w-full pr-12 border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Peso') border-red-500 @enderror"
                                       placeholder="0.0"
                                       step="0.1"
                                       min="1"
                                       max="2000">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">kg</span>
                                </div>
                            </div>
                            @error('Peso')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Peso en kilogramos (1 - 2000 kg)</p>
                        </div>

                        <!-- Altura -->
                        <div>
                            <label for="Altura" class="block text-sm font-medium text-gray-700 mb-1">
                                Altura (cm)
                            </label>
                            <div class="relative">
                                <input type="number" name="Altura" id="Altura" 
                                       value="{{ old('Altura') }}"
                                       class="form-input w-full pr-12 border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Altura') border-red-500 @enderror"
                                       placeholder="0.0"
                                       step="0.1"
                                       min="10"
                                       max="300">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">cm</span>
                                </div>
                            </div>
                            @error('Altura')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Altura en centímetros (10 - 300 cm)</p>
                        </div>

                        <!-- Comentario -->
                        <div>
                            <label for="Comentario" class="block text-sm font-medium text-gray-700 mb-1">
                                Comentarios
                            </label>
                            <textarea name="Comentario" id="Comentario" rows="4"
                                      class="form-textarea w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Comentario') border-red-500 @enderror"
                                      placeholder="Observaciones sobre el cambio de etapa, desarrollo del animal, etc."
                                      maxlength="500">{{ old('Comentario') }}</textarea>
                            @error('Comentario')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Descripción del cambio y observaciones relevantes (máximo 500 caracteres)</p>
                        </div>

                        <!-- Indicador de caracteres -->
                        <div class="text-right">
                            <span id="comentario-contador" class="text-xs text-gray-500">0/500 caracteres</span>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('cambios-animal.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-ganaderasoft-verde text-white rounded-md hover:bg-ganaderasoft-verde/90 focus:outline-none focus:ring-2 focus:ring-ganaderasoft-verde transition-colors">
                        💾 Guardar Cambio
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para validaciones y funcionalidades -->
    <script>
        // Función de debug visible
        function showDebug(message, isError = false) {
            const debugInfo = document.getElementById('debug-info');
            const debugText = document.getElementById('debug-text');
            debugInfo.style.display = 'block';
            debugText.textContent = message;
            debugInfo.className = isError ? 
                'mb-2 p-2 bg-red-100 text-red-800 text-xs rounded' : 
                'mb-2 p-2 bg-blue-100 text-blue-800 text-xs rounded';
            console.log('DEBUG:', message);
        }
        
        // Filtrar etapas basándose en el animal seleccionado
        document.getElementById('cambios_etapa_anid').addEventListener('change', function(e) {
            const animalSelect = e.target;
            const etapaSelect = document.getElementById('cambios_etapa_etid');
            const selectedOption = animalSelect.options[animalSelect.selectedIndex];
            
            showDebug('Animal seleccionado, procesando...');
            
            // Reiniciar etapa
            etapaSelect.value = '';
            document.getElementById('Etapa_Cambio').value = '';
            
            if (!animalSelect.value) {
                // Si no hay animal seleccionado, mostrar todas las etapas
                showDebug('No hay animal seleccionado');
                Array.from(etapaSelect.options).forEach((option, index) => {
                    if (index > 0) {
                        option.style.display = 'block';
                    }
                });
                document.getElementById('debug-info').style.display = 'none';
                return;
            }
            
            // Obtener datos del animal seleccionado
            const animalSexo = selectedOption.getAttribute('data-sexo');
            const animalTipoAnimal = selectedOption.getAttribute('data-tipo-animal');
            
            showDebug(`Animal ID: ${animalSelect.value}, Sexo: "${animalSexo}", Tipo: "${animalTipoAnimal}"`);
            console.log('Datos completos del animal seleccionado:', {
                id: animalSelect.value,
                sexo: animalSexo,
                tipoAnimal: animalTipoAnimal,
                optionText: selectedOption.textContent,
                allAttributes: {
                    'data-sexo': selectedOption.getAttribute('data-sexo'),
                    'data-tipo-animal': selectedOption.getAttribute('data-tipo-animal')
                }
            });
            
            // Función para filtrar etapas
            function filtrarEtapas() {
                let visibleOptions = 0;
                Array.from(etapaSelect.options).forEach((option, index) => {
                    if (index === 0) return; // Skip the first "Seleccione una etapa" option
                    
                    const etapaSexo = option.getAttribute('data-sexo');
                    const etapaTipoAnimal = option.getAttribute('data-tipo-animal');
                    
                    // La etapa actual siempre debe ser visible (identificada por el texto "ETAPA ACTUAL")
                    const isEtapaActual = option.textContent.includes('(ETAPA ACTUAL)');
                    
                    if (isEtapaActual) {
                        option.style.display = 'block';
                        visibleOptions++;
                        showDebug(`✅ Etapa actual siempre visible: "${option.textContent}"`);
                    } else {
                        // Verificar compatibilidad para otras etapas
                        const sexoCompatible = !etapaSexo || etapaSexo === animalSexo;
                        const tipoAnimalCompatible = !etapaTipoAnimal || etapaTipoAnimal === animalTipoAnimal;
                        
                        if (sexoCompatible && tipoAnimalCompatible) {
                            option.style.display = 'block';
                            visibleOptions++;
                        } else {
                            option.style.display = 'none';
                            console.log(`Etapa filtrada: "${option.textContent}" - Animal Sexo:${animalSexo} vs Etapa Sexo:${etapaSexo}, Animal Tipo:${animalTipoAnimal} vs Etapa Tipo:${etapaTipoAnimal}`);
                        }
                    }
                });
                showDebug(`Filtrado completado: ${visibleOptions} etapas visibles (incluyendo etapa actual)`);
            }
            
            showDebug('Iniciando llamada AJAX para obtener etapa actual...');
            
            // Obtener etapa actual del animal vía AJAX
            fetch(`/cambios-animal/animal/${animalSelect.value}/etapa`, {
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
                showDebug(`AJAX Success: ${JSON.stringify(data.success)}`);
                console.log('Respuesta completa del servidor:', data);
                
                if (data.success && data.data && data.data.etapa_actual) {
                    const etapaActualData = data.data.etapa_actual;
                    showDebug('Procesando etapa actual...');
                    console.log('Estructura etapa_actual:', etapaActualData);
                    
                    // La estructura puede ser directa o anidada
                    let etapaActual;
                    if (etapaActualData.etapa) {
                        // Estructura: etapa_actual.etapa (anidada)
                        etapaActual = etapaActualData.etapa;
                        showDebug('Usando estructura anidada (etapa_actual.etapa)');
                    } else if (etapaActualData.etapa_id) {
                        // Estructura: etapa_actual directa
                        etapaActual = etapaActualData;
                        showDebug('Usando estructura directa (etapa_actual)');
                    } else {
                        showDebug('ERROR: Estructura de etapa_actual no reconocida', true);
                        console.error('Estructura de etapa_actual no reconocida:', etapaActualData);
                        filtrarEtapas(); // Filtrar etapas normalmente
                        return;
                    }
                    
                    showDebug(`Etapa actual: ID=${etapaActual.etapa_id}, Nombre=${etapaActual.etapa_nombre}`);
                    console.log('Etapa actual procesada:', etapaActual);
                    
                    // Verificar si la etapa actual ya existe en la lista
                    let etapaActualExists = false;
                    let existingOptionText = '';
                    Array.from(etapaSelect.options).forEach((option, index) => {
                        if (option.value == etapaActual.etapa_id) {
                            etapaActualExists = true;
                            existingOptionText = option.textContent;
                        }
                    });
                    
                    if (etapaActualExists) {
                        showDebug(`Etapa actual ya existe: "${existingOptionText}"`);
                    } else {
                        showDebug('Agregando etapa actual a la lista...');
                        
                        const newOption = document.createElement('option');
                        newOption.value = etapaActual.etapa_id;
                        newOption.textContent = `${etapaActual.etapa_nombre} (ETAPA ACTUAL)`;
                        newOption.setAttribute('data-sexo', etapaActual.etapa_sexo || '');
                        newOption.setAttribute('data-tipo-animal', etapaActual.etapa_fk_tipo_animal_id || '3');
                        newOption.setAttribute('data-edad-ini', etapaActual.etapa_edad_ini || '');
                        newOption.setAttribute('data-edad-fin', etapaActual.etapa_edad_fin || '');
                        
                        // Agregar después de la primera opción "Seleccione una etapa"
                        if (etapaSelect.options.length > 1) {
                            etapaSelect.insertBefore(newOption, etapaSelect.options[1]);
                        } else {
                            etapaSelect.appendChild(newOption);
                        }
                        
                        showDebug(`✅ Etapa actual agregada: "${newOption.textContent}"`);
                        
                        console.log('Etapa actual agregada a la lista:', {
                            id: etapaActual.etapa_id,
                            nombre: etapaActual.etapa_nombre,
                            sexo: etapaActual.etapa_sexo,
                            tipo_animal: etapaActual.etapa_fk_tipo_animal_id
                        });
                    }
                } else {
                    showDebug('No se encontró etapa_actual válida', true);
                    console.log('No se encontró etapa_actual o response no exitoso:', {
                        success: data.success,
                        has_data: !!(data.data),
                        has_etapa_actual: !!(data.data && data.data.etapa_actual)
                    });
                }
                
                // Filtrar etapas después de procesar la etapa actual
                filtrarEtapas();
            })
            .catch(error => {
                showDebug(`ERROR AJAX: ${error.message}`, true);
                console.error('Error obteniendo etapa del animal:', error);
                
                // Filtrar etapas aunque haya error en AJAX
                filtrarEtapas();
            });
        });

        // Auto-completar nombre de etapa cuando se selecciona una etapa
        document.getElementById('cambios_etapa_etid').addEventListener('change', function(e) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            if (selectedOption.value) {
                const etapaNombre = selectedOption.text.split(' (')[0]; // Obtener solo el nombre sin la información adicional
                document.getElementById('Etapa_Cambio').value = etapaNombre;
            } else {
                document.getElementById('Etapa_Cambio').value = '';
            }
        });

        // Validación de nombres (solo letras)
        document.getElementById('Etapa_Cambio').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^a-zA-ZáéíóúñÁÉÍÓÚÑ\s]/g, '');
            e.target.value = value;
        });

        // Contador de caracteres para comentario
        const comentarioTextarea = document.getElementById('Comentario');
        const contador = document.getElementById('comentario-contador');
        
        function actualizarContador() {
            const length = comentarioTextarea.value.length;
            contador.textContent = `${length}/500 caracteres`;
            
            if (length > 450) {
                contador.classList.remove('text-gray-500');
                contador.classList.add('text-red-500');
            } else {
                contador.classList.remove('text-red-500');
                contador.classList.add('text-gray-500');
            }
        }
        
        comentarioTextarea.addEventListener('input', actualizarContador);
        actualizarContador(); // Inicial

        // Validación de peso y altura
        document.getElementById('Peso').addEventListener('input', function(e) {
            let value = parseFloat(e.target.value);
            if (value < 1) e.target.value = 1;
            if (value > 2000) e.target.value = 2000;
        });

        document.getElementById('Altura').addEventListener('input', function(e) {
            let value = parseFloat(e.target.value);
            if (value < 10) e.target.value = 10;
            if (value > 300) e.target.value = 300;
        });

        // Validar fecha
        document.getElementById('Fecha_Cambio').addEventListener('change', function(e) {
            const fechaSeleccionada = new Date(e.target.value);
            const fechaActual = new Date();
            
            if (fechaSeleccionada > fechaActual) {
                alert('La fecha del cambio no puede ser futura.');
                e.target.value = new Date().toISOString().split('T')[0];
            }
        });

        // Prevenir envío del formulario con datos inválidos
        document.querySelector('form').addEventListener('submit', function(e) {
            const animal = document.getElementById('cambios_etapa_anid').value;
            const etapa = document.getElementById('cambios_etapa_etid').value;
            const etapaNombre = document.getElementById('Etapa_Cambio').value.trim();
            const fecha = document.getElementById('Fecha_Cambio').value;
            
            if (!animal) {
                alert('Debe seleccionar un animal.');
                e.preventDefault();
                return;
            }
            
            if (!etapa) {
                alert('Debe seleccionar una etapa.');
                e.preventDefault();
                return;
            }
            
            if (!etapaNombre) {
                alert('Debe ingresar el nombre de la etapa.');
                e.preventDefault();
                return;
            }
            
            if (!fecha) {
                alert('Debe seleccionar la fecha del cambio.');
                e.preventDefault();
                return;
            }
        });
    </script>
@endsection