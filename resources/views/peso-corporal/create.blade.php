@extends('layouts.app')

@section('title', 'Nuevo Registro de Peso - GanaderaSoft')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">📊 Nuevo Registro de Peso</h1>
                <p class="text-gray-600 mt-2">Registra el peso corporal de un animal</p>
            </div>
            <a href="{{ route('peso-corporal.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                ← Volver a Lista
            </a>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-ganaderasoft-negro">Información del Peso</h2>
                <p class="text-gray-600 mt-1">Complete todos los campos requeridos marcados con *</p>
            </div>

            <form action="{{ route('peso-corporal.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Animal -->
                    <div class="md:col-span-2">
                        <label for="animal_id" class="block text-sm font-medium text-gray-700 mb-2">
                            🐄 Animal * 
                        </label>
                        <select name="animal_id" id="animal_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('animal_id') ? 'border-red-500' : '' }}">
                            <option value="">Seleccione un animal</option>
                            @foreach($animales as $animal)
                                <option value="{{ $animal['id'] }}" {{ old('animal_id') == $animal['id'] ? 'selected' : '' }}>
                                    {{ $animal['identificacion'] }} - {{ $animal['nombre'] }}
                                    @if(isset($animal['sexo_descripcion']))
                                        ({{ $animal['sexo_descripcion'] }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('animal_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha y Hora -->
                    <div>
                        <label for="fecha_control" class="block text-sm font-medium text-gray-700 mb-2">
                            📅 Fecha y Hora de Control *
                        </label>
                        <input type="datetime-local" name="fecha_control" id="fecha_control" required
                               value="{{ old('fecha_control', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('fecha_control') ? 'border-red-500' : '' }}">
                        @error('fecha_control')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Peso -->
                    <div>
                        <label for="peso" class="block text-sm font-medium text-gray-700 mb-2">
                            ⚖️ Peso (kg) *
                        </label>
                        <input type="number" name="peso" id="peso" step="0.1" min="0" max="9999" required
                               value="{{ old('peso') }}" placeholder="Ej: 450.5"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('peso') ? 'border-red-500' : '' }}">
                        @error('peso')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Ingrese el peso en kilogramos (0.0 - 9999.0)</p>
                    </div>

                    <!-- Etapa -->
                    <div>
                        <label for="etapa_id" class="block text-sm font-medium text-gray-700 mb-2">
                            🏃 Etapa del Animal *
                        </label>
                        <select name="etapa_id" id="etapa_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('etapa_id') ? 'border-red-500' : '' }}">
                            <option value="">Seleccione la etapa</option>
                            @foreach($configuraciones['etapas'] as $etapa)
                                <option value="{{ $etapa['id'] }}" {{ old('etapa_id') == $etapa['id'] ? 'selected' : '' }}>
                                    {{ $etapa['descripcion'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('etapa_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Condición Corporal -->
                    <div>
                        <label for="condicion_corporal" class="block text-sm font-medium text-gray-700 mb-2">
                            💪 Condición Corporal (1-5)
                        </label>
                        <select name="condicion_corporal" id="condicion_corporal"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('condicion_corporal') ? 'border-red-500' : '' }}">
                            <option value="">Seleccione la condición</option>
                            <option value="1" {{ old('condicion_corporal') == '1' ? 'selected' : '' }}>1 - Muy delgado</option>
                            <option value="2" {{ old('condicion_corporal') == '2' ? 'selected' : '' }}>2 - Delgado</option>
                            <option value="3" {{ old('condicion_corporal') == '3' ? 'selected' : '' }}>3 - Normal</option>
                            <option value="4" {{ old('condicion_corporal') == '4' ? 'selected' : '' }}>4 - Gordo</option>
                            <option value="5" {{ old('condicion_corporal') == '5' ? 'selected' : '' }}>5 - Muy gordo</option>
                        </select>
                        @error('condicion_corporal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                            📝 Observaciones
                        </label>
                        <textarea name="observaciones" id="observaciones" rows="4" 
                                  placeholder="Escriba cualquier observación relevante sobre el pesaje..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('observaciones') ? 'border-red-500' : '' }}">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('peso-corporal.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                        💾 Guardar Registro
                    </button>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div class="bg-blue-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">💡 Consejos para el Registro de Peso</h3>
            <ul class="list-disc list-inside text-blue-800 space-y-1">
                <li><strong>Consistencia:</strong> Pese los animales a la misma hora del día para obtener datos comparables</li>
                <li><strong>Condiciones:</strong> Registre el peso en condiciones similares (antes/después de comer)</li>
                <li><strong>Frecuencia:</strong> Se recomienda pesar semanalmente para un mejor seguimiento</li>
                <li><strong>Precisión:</strong> Use básculas calibradas y registre con precisión decimal</li>
                <li><strong>Observaciones:</strong> Anote cualquier factor que pueda afectar el peso (enfermedad, estrés, etc.)</li>
            </ul>
        </div>
    </div>

    <script>
        // Auto-llenar fecha actual
        document.addEventListener('DOMContentLoaded', function() {
            const fechaControl = document.getElementById('fecha_control');
            if (!fechaControl.value) {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                fechaControl.value = now.toISOString().slice(0, 16);
            }
        });

        // Validación en tiempo real del peso
        document.getElementById('peso').addEventListener('input', function() {
            const peso = parseFloat(this.value);
            if (peso && (peso < 0 || peso > 9999)) {
                this.setCustomValidity('El peso debe estar entre 0 y 9999 kg');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
@endsection