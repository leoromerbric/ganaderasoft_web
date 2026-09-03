@extends('layouts.authenticated')

@section('title', 'Crear ' . $catalog['name'])

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                📋
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Crear registro
                </h1>
                <p class="text-gray-500 text-sm mt-1">Nuevo registro en {{ $catalog['name'] }}</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.'.$catalog['slug'].'.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.'.$catalog['slug'].'.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card: Información del registro -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📝</span> Datos del registro
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nombre de la raza <span class="text-red-500">*</span></label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="siglas" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Siglas</label>
                            <input type="text" id="siglas" name="siglas" value="{{ old('siglas') }}" maxlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('siglas')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="finca_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                            <select id="finca_id" name="finca_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="" {{ old('finca_id') == '' ? 'selected' : '' }}>Ninguna (raza global/pública)</option>
                                @foreach($fincas as $finca)
                                    @php
                                        $fId = is_array($finca) ? ($finca['id'] ?? $finca['finca_id'] ?? '') : $finca->id;
                                        $fNombre = is_array($finca) ? ($finca['nombre'] ?? '') : $finca->nombre;
                                    @endphp
                                    <option value="{{ $fId }}" {{ old('finca_id') == $fId ? 'selected' : '' }}>
                                        {{ $fNombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('finca_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="tipo_animal_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipo de animal <span class="text-red-500">*</span></label>
                            <select id="tipo_animal_id" name="tipo_animal_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="" disabled selected>Seleccione un tipo...</option>
                                @foreach($tiposAnimal as $tipo)
                                    @php
                                        $tId = is_array($tipo) ? ($tipo['id'] ?? '') : $tipo->id;
                                        $tNombre = is_array($tipo) ? ($tipo['nombre'] ?? '') : $tipo->nombre;
                                    @endphp
                                    <option value="{{ $tId }}" {{ old('tipo_animal_id') == $tId ? 'selected' : '' }}>
                                        {{ $tNombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_animal_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="tipo_raza" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipo de raza</label>
                            <select id="tipo_raza" name="tipo_raza"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="Pura" {{ old('tipo_raza') == 'Pura' ? 'selected' : '' }}>Pura</option>
                                <option value="Cruzada" {{ old('tipo_raza') == 'Cruzada' ? 'selected' : '' }}>Cruzada</option>
                                <option value="Sintética" {{ old('tipo_raza') == 'Sintética' ? 'selected' : '' }}>Sintética</option>
                            </select>
                            @error('tipo_raza')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="proposito" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Propósito</label>
                            <select id="proposito" name="proposito"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="Carne" {{ old('proposito') == 'Carne' ? 'selected' : '' }}>Carne</option>
                                <option value="Leche" {{ old('proposito') == 'Leche' ? 'selected' : '' }}>Leche</option>
                                <option value="Doble" {{ old('proposito') == 'Doble' ? 'selected' : '' }}>Doble propósito</option>
                                <option value="Lidia" {{ old('proposito') == 'Lidia' ? 'selected' : '' }}>Lidia</option>
                                <option value="Trabajo" {{ old('proposito') == 'Trabajo' ? 'selected' : '' }}>Trabajo</option>
                            </select>
                            @error('proposito')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="proporcion_raza" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Proporción</label>
                            <select id="proporcion_raza" name="proporcion_raza"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="Grande" {{ old('proporcion_raza') == 'Grande' ? 'selected' : '' }}>Grande</option>
                                <option value="Mediano" {{ old('proporcion_raza') == 'Mediano' ? 'selected' : '' }}>Mediano</option>
                                <option value="Pequeño" {{ old('proporcion_raza') == 'Pequeño' ? 'selected' : '' }}>Pequeño</option>
                            </select>
                            @error('proporcion_raza')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="pelaje" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Pelaje típico</label>
                            <input type="text" id="pelaje" name="pelaje" value="{{ old('pelaje') }}" maxlength="80"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('pelaje')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="origen" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Origen geográfico</label>
                            <input type="text" id="origen" name="origen" value="{{ old('origen') }}" maxlength="60"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('origen')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="caracteristica_especial" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Características especiales</label>
                            <textarea id="caracteristica_especial" name="caracteristica_especial" rows="2" maxlength="80"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('caracteristica_especial') }}</textarea>
                            @error('caracteristica_especial')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Icono e Identificación -->
                        <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-2xl flex items-center space-x-3">
                            <div id="previewIcono" class="w-12 h-12 rounded-xl bg-white border border-blue-200 text-blue-700 font-bold flex items-center justify-center text-2xl shadow-xs uppercase">
                                #
                            </div>
                            <div class="overflow-hidden">
                                <p id="previewNombre" class="text-base font-bold text-gray-900 truncate">Sin registro</p>
                                <p class="text-xs text-gray-500">Nuevo registro</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-500">Siglas:</span>
                                <span id="previewSiglas" class="font-bold text-gray-900 text-right">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-500">Tipo de animal:</span>
                                <span id="previewTipoAnimal" class="font-bold text-gray-900 text-right">No seleccionado</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-500">Propósito:</span>
                                <span id="previewProposito" class="font-bold text-gray-900 text-right">No seleccionado</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-500">Tipo de raza:</span>
                                <span id="previewTipoRaza" class="font-bold text-gray-900 text-right">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-500">Proporción:</span>
                                <span id="previewProporcion" class="font-bold text-gray-900 text-right">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-500">Visibilidad:</span>
                                <span id="previewFinca" class="text-right font-medium text-blue-600">
                                    🌐 Pública
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                💾 Guardar
                            </button>
                            <a href="{{ route('admin.'.$catalog['slug'].'.index') }}"
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
    const nombreInput = document.getElementById('nombre');
    const previewNombre = document.getElementById('previewNombre');
    const previewIcono  = document.getElementById('previewIcono');
    const siglasInput = document.getElementById('siglas');
    const tipoAnimalInput = document.getElementById('tipo_animal_id');
    const propositoInput = document.getElementById('proposito');
    const tipoRazaInput = document.getElementById('tipo_raza');
    const proporcionInput = document.getElementById('proporcion_raza');
    const fincaInput = document.getElementById('finca_id');

    function updatePreview() {
        const nombreVal = nombreInput?.value.trim() || '';
        if (previewNombre) previewNombre.textContent = nombreVal || 'Sin registro';
        if (previewIcono) previewIcono.textContent = nombreVal ? nombreVal.charAt(0).toUpperCase() : '#';
        
        const previewSiglas = document.getElementById('previewSiglas');
        if (previewSiglas) previewSiglas.textContent = siglasInput?.value.trim() || '-';
        
        const previewTipoAnimal = document.getElementById('previewTipoAnimal');
        if (previewTipoAnimal && tipoAnimalInput) {
            const selectedText = tipoAnimalInput.options[tipoAnimalInput.selectedIndex]?.text;
            previewTipoAnimal.textContent = (tipoAnimalInput.value && selectedText && selectedText !== 'Seleccione un tipo...') ? selectedText : 'No seleccionado';
        }

        const previewProposito = document.getElementById('previewProposito');
        if (previewProposito && propositoInput) {
            const selectedText = propositoInput.options[propositoInput.selectedIndex]?.text;
            previewProposito.textContent = (propositoInput.value && selectedText && selectedText !== 'Seleccione...') ? selectedText : 'No seleccionado';
        }

        const previewTipoRaza = document.getElementById('previewTipoRaza');
        if (previewTipoRaza && tipoRazaInput) {
            const selectedText = tipoRazaInput.options[tipoRazaInput.selectedIndex]?.text;
            previewTipoRaza.textContent = (tipoRazaInput.value && selectedText && selectedText !== 'Seleccione...') ? selectedText : '-';
        }

        const previewProporcion = document.getElementById('previewProporcion');
        if (previewProporcion && proporcionInput) {
            const selectedText = proporcionInput.options[proporcionInput.selectedIndex]?.text;
            previewProporcion.textContent = (proporcionInput.value && selectedText && selectedText !== 'Seleccione...') ? selectedText : '-';
        }

        const previewFinca = document.getElementById('previewFinca');
        if (previewFinca && fincaInput) {
            if (fincaInput.value) {
                previewFinca.textContent = '🔒 Privada';
                previewFinca.className = 'text-right font-medium text-ganaderasoft-azul';
            } else {
                previewFinca.textContent = '🌐 Pública';
                previewFinca.className = 'text-right font-medium text-blue-600';
            }
        }
    }

    const inputs = [nombreInput, siglasInput, tipoAnimalInput, propositoInput, tipoRazaInput, proporcionInput, fincaInput];
    inputs.forEach(input => {
        if(input) {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        }
    });
    
    updatePreview();
});
</script>
@endsection