@extends('layouts.app')

@section('title', 'Nuevo Registro de Medidas Corporales - GanaderaSoft')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">📏 Nuevo Registro de Medidas Corporales</h1>
                <p class="text-gray-600 mt-2">Registra las medidas corporales de un animal</p>
            </div>
            <a href="{{ route('medidas-corporales.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                ← Volver a Lista
            </a>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-ganaderasoft-negro">Información de Medidas Corporales</h2>
                <p class="text-gray-600 mt-1">Complete todos los campos requeridos marcados con *</p>
            </div>

            <form action="{{ route('medidas-corporales.store') }}" method="POST" class="p-6">
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

                    <!-- Altura -->
                    <div>
                        <label for="altura" class="block text-sm font-medium text-gray-700 mb-2">
                            📐 Altura (cm)
                        </label>
                        <input type="number" name="altura" id="altura" step="0.1" min="0" max="500"
                               value="{{ old('altura') }}" placeholder="Ej: 120.5"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('altura') ? 'border-red-500' : '' }}">
                        @error('altura')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Altura hasta la cruz en centímetros</p>
                    </div>

                    <!-- Largura -->
                    <div>
                        <label for="largura" class="block text-sm font-medium text-gray-700 mb-2">
                            📏 Largura (cm)
                        </label>
                        <input type="number" name="largura" id="largura" step="0.1" min="0" max="500"
                               value="{{ old('largura') }}" placeholder="Ej: 180.0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('largura') ? 'border-red-500' : '' }}">
                        @error('largura')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Longitud del cuerpo en centímetros</p>
                    </div>

                    <!-- Circunferencia Torácica -->
                    <div>
                        <label for="circunferencia_toracica" class="block text-sm font-medium text-gray-700 mb-2">
                            🔵 Circunferencia Torácica (cm)
                        </label>
                        <input type="number" name="circunferencia_toracica" id="circunferencia_toracica" step="0.1" min="0" max="500"
                               value="{{ old('circunferencia_toracica') }}" placeholder="Ej: 160.0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('circunferencia_toracica') ? 'border-red-500' : '' }}">
                        @error('circunferencia_toracica')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Perímetro torácico en centímetros</p>
                    </div>

                    <!-- Circunferencia Abdominal -->
                    <div>
                        <label for="circunferencia_abdominal" class="block text-sm font-medium text-gray-700 mb-2">
                            ⭕ Circunferencia Abdominal (cm)
                        </label>
                        <input type="number" name="circunferencia_abdominal" id="circunferencia_abdominal" step="0.1" min="0" max="500"
                               value="{{ old('circunferencia_abdominal') }}" placeholder="Ej: 170.0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('circunferencia_abdominal') ? 'border-red-500' : '' }}">
                        @error('circunferencia_abdominal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Perímetro abdominal en centímetros</p>
                    </div>

                    <!-- Anchura de Cadera -->
                    <div>
                        <label for="anchura_cadera" class="block text-sm font-medium text-gray-700 mb-2">
                            ↔️ Anchura de Cadera (cm)
                        </label>
                        <input type="number" name="anchura_cadera" id="anchura_cadera" step="0.1" min="0" max="500"
                               value="{{ old('anchura_cadera') }}" placeholder="Ej: 40.0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('anchura_cadera') ? 'border-red-500' : '' }}">
                        @error('anchura_cadera')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Ancho de cadera en centímetros</p>
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                            📝 Observaciones
                        </label>
                        <textarea name="observaciones" id="observaciones" rows="4" 
                                  placeholder="Escriba cualquier observación relevante sobre las medidas..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent {{ $errors->has('observaciones') ? 'border-red-500' : '' }}">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('medidas-corporales.index') }}" 
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
            <h3 class="text-lg font-semibold text-blue-900 mb-3">💡 Consejos para el Registro de Medidas Corporales</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold text-blue-800 mb-2">🔧 Técnicas de Medición:</h4>
                    <ul class="list-disc list-inside text-blue-800 space-y-1 text-sm">
                        <li><strong>Altura:</strong> Medir desde el suelo hasta el punto más alto de la cruz</li>
                        <li><strong>Largura:</strong> Desde el punto de la punta del hombro hasta el isquion</li>
                        <li><strong>Circunferencia:</strong> Usar cinta métrica flexible, sin comprimir</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-blue-800 mb-2">📊 Buenas Prácticas:</h4>
                    <ul class="list-disc list-inside text-blue-800 space-y-1 text-sm">
                        <li>Tomar medidas con el animal en posición estándar</li>
                        <li>Registrar a la misma hora para consistencia</li>
                        <li>Usar el mismo método de medición siempre</li>
                        <li>Anotar condiciones especiales en observaciones</li>
                    </ul>
                </div>
            </div>
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

        // Validación en tiempo real de las medidas
        ['altura', 'largura', 'circunferencia_toracica', 'circunferencia_abdominal', 'anchura_cadera'].forEach(function(campo) {
            document.getElementById(campo).addEventListener('input', function() {
                const valor = parseFloat(this.value);
                if (valor && (valor < 0 || valor > 500)) {
                    this.setCustomValidity('La medida debe estar entre 0 y 500 cm');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
@endsection