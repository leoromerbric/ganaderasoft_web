@extends('layouts.authenticated')

@section('title', 'Editar Animal — ' . ($animal['nombre'] ?? 'Animal'))

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl {{ ($animal['sexo'] ?? '') === 'M' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-2xl shadow-xs">
                {{ ($animal['sexo'] ?? '') === 'M' ? '🐂' : '🐄' }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar animal
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Expediente: <span class="font-bold text-gray-800">{{ $animal['nombre'] ?? 'Animal' }}</span> 
                    @if($animal['codigo_animal'] ?? null)
                        <span class="text-xs px-2.5 py-0.5 bg-gray-100 text-gray-600 rounded-md font-mono font-bold">#{{ $animal['codigo_animal'] }}</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('animales.show', $animal['id']) }}"
               class="px-5 py-2.5 bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver detalle
            </a>
            <a href="{{ route('animales.index') }}" 
               class="px-5 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <form action="{{ route('animales.update', $animal['id']) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario de Edición (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Identificación del Animal -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📋</span> Datos de identificación
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Nombre del animal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $animal['nombre'] ?? '') }}" required
                                   class="w-full px-4 py-3 border @error('nombre') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all"
                                   placeholder="Ej: Vaca lechera #1">
                            @error('nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Código Identificador -->
                        <div>
                            <label for="codigo_animal" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Código identificador <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="codigo_animal" name="codigo_animal" value="{{ old('codigo_animal', $animal['codigo_animal'] ?? '') }}" required
                                   class="w-full px-4 py-3 border @error('codigo_animal') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all"
                                   placeholder="Ej: BOV-001">
                            @error('codigo_animal')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Sexo -->
                        <div>
                            <label for="sexo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Sexo <span class="text-red-500">*</span>
                            </label>
                            <select id="sexo" name="sexo" required
                                    class="w-full px-4 py-3 border @error('sexo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Seleccione el sexo...</option>
                                <option value="M" {{ old('sexo', $animal['sexo'] ?? '') == 'M' ? 'selected' : '' }}>Macho (♂)</option>
                                <option value="H" {{ old('sexo', $animal['sexo'] ?? '') == 'H' ? 'selected' : '' }}>Hembra (♀)</option>
                            </select>
                            @error('sexo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label for="fecha_nacimiento" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento', isset($animal['fecha_nacimiento']) ? date('Y-m-d', strtotime($animal['fecha_nacimiento'])) : '') }}" required
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border @error('fecha_nacimiento') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('fecha_nacimiento')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Ubicación, Genética y Estado -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🏡</span> Ubicación y clasificación
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Rebaño -->
                        <div>
                            <label for="rebano_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Rebaño perteneciente <span class="text-red-500">*</span>
                            </label>
                            <select id="rebano_id" name="rebano_id" required
                                    class="w-full px-4 py-3 border @error('rebano_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Seleccione un rebaño...</option>
                                @foreach($rebanos as $rebano)
                                    @php
                                        $currentRebanoId = data_get($animal, 'rebano.id', $animal['rebano_id'] ?? null);
                                    @endphp
                                    <option value="{{ $rebano['id'] }}" 
                                        {{ (old('rebano_id', $currentRebanoId) == $rebano['id']) ? 'selected' : '' }}>
                                        {{ $rebano['nombre'] }} {{ data_get($rebano, 'finca.nombre') ? '— '.data_get($rebano, 'finca.nombre') : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rebano_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Raza -->
                        <div>
                            <label for="composicion_raza_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Raza / genética <span class="text-red-500">*</span>
                            </label>
                            <select id="composicion_raza_id" name="composicion_raza_id" required
                                    class="w-full px-4 py-3 border @error('composicion_raza_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Seleccione una raza...</option>
                                @foreach($razas as $raza)
                                    @php
                                        $currentRazaId = data_get($animal, 'composicion_raza.id', $animal['composicion_raza_id'] ?? null);
                                        $siglasRaza = $raza['siglas'] ?? '';
                                    @endphp
                                    <option value="{{ $raza['id'] }}" 
                                        {{ old('composicion_raza_id', $currentRazaId) == $raza['id'] ? 'selected' : '' }}>
                                        {{ $raza['nombre'] }} {{ $siglasRaza ? '('.$siglasRaza.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('composicion_raza_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Procedencia -->
                        <div>
                            <label for="procedencia" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Procedencia <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="procedencia" name="procedencia" value="{{ old('procedencia', $animal['procedencia'] ?? '') }}" required
                                   class="w-full px-4 py-3 border @error('procedencia') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all"
                                   placeholder="Ej: Nacido en finca, compra local">
                            @error('procedencia')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Archivado Checkbox -->
                        <div class="flex items-center pt-2">
                            <label class="relative flex items-center p-3.5 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer w-full transition-colors">
                                <input type="checkbox" name="archivado" value="1" 
                                       {{ old('archivado', $animal['archivado'] ?? false) ? 'checked' : '' }}
                                       class="w-4 h-4 text-ganaderasoft-celeste border-gray-300 rounded focus:ring-ganaderasoft-celeste">
                                <span class="ml-3 text-sm font-semibold text-gray-800">Archivar del inventario activo</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>📋</span> Resumen de ficha
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl flex items-center space-x-3">
                            <div id="previewIcono" class="w-12 h-12 rounded-xl bg-white border border-emerald-200 text-emerald-700 font-bold flex items-center justify-center text-2xl shadow-xs">
                                {{ ($animal['sexo'] ?? '') === 'M' ? '🐂' : '🐄' }}
                            </div>
                            <div class="overflow-hidden">
                                <p id="previewNombre" class="text-base font-bold text-gray-900 truncate">{{ $animal['nombre'] ?? 'Sin nombre' }}</p>
                                <p id="previewCodigo" class="text-xs text-gray-400 font-mono">#{{ $animal['codigo_animal'] ?? 'CODIGO' }}</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Sexo:</span>
                                <span id="previewSexo" class="font-bold text-gray-900">
                                    {{ ($animal['sexo'] ?? '') === 'M' ? 'Macho (♂)' : (($animal['sexo'] ?? '') === 'H' ? 'Hembra (♀)' : 'No especificado') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Rebaño:</span>
                                <span id="previewRebano" class="font-bold text-gray-900 truncate max-w-[140px] text-right">
                                    {{ data_get($animal, 'rebano.nombre', 'No seleccionado') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Raza:</span>
                                <span id="previewRaza" class="font-bold text-gray-900 truncate max-w-[140px] text-right">
                                    {{ data_get($animal, 'composicion_raza.nombre', 'No seleccionada') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Procedencia:</span>
                                <span id="previewProcedencia" class="font-semibold text-gray-900 truncate max-w-[140px] text-right">
                                    {{ $animal['procedencia'] ?? 'No especificada' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                💾 Actualizar ejemplar
                            </button>
                            <a href="{{ route('animales.index') }}"
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
    const nombreInput      = document.getElementById('nombre');
    const codigoInput      = document.getElementById('codigo_animal');
    const sexoSelect       = document.getElementById('sexo');
    const rebanoSelect     = document.getElementById('rebano_id');
    const razaSelect       = document.getElementById('composicion_raza_id');
    const procedenciaInput = document.getElementById('procedencia');

    const previewNombre      = document.getElementById('previewNombre');
    const previewCodigo      = document.getElementById('previewCodigo');
    const previewSexo        = document.getElementById('previewSexo');
    const previewRebano      = document.getElementById('previewRebano');
    const previewRaza        = document.getElementById('previewRaza');
    const previewProcedencia = document.getElementById('previewProcedencia');
    const previewIcono       = document.getElementById('previewIcono');

    function updatePreview() {
        previewNombre.textContent = nombreInput.value.trim() || 'Sin nombre';
        previewCodigo.textContent = codigoInput.value.trim() ? `#${codigoInput.value.trim().toUpperCase()}` : '#CODIGO';

        const sVal = sexoSelect.value;
        if (sVal === 'M') {
            previewSexo.textContent = 'Macho (♂)';
            previewIcono.textContent = '🐂';
        } else if (sVal === 'H') {
            previewSexo.textContent = 'Hembra (♀)';
            previewIcono.textContent = '🐄';
        } else {
            previewSexo.textContent = 'No especificado';
            previewIcono.textContent = '🐄';
        }

        const rOpt = rebanoSelect.options[rebanoSelect.selectedIndex];
        previewRebano.textContent = (rebanoSelect.value && rOpt) ? rOpt.textContent.trim() : 'No seleccionado';

        const rzOpt = razaSelect.options[razaSelect.selectedIndex];
        previewRaza.textContent = (razaSelect.value && rzOpt) ? rzOpt.textContent.trim() : 'No seleccionada';

        previewProcedencia.textContent = procedenciaInput.value.trim() || 'No especificada';
    }

    nombreInput.addEventListener('input', updatePreview);
    codigoInput.addEventListener('input', updatePreview);
    sexoSelect.addEventListener('change', updatePreview);
    rebanoSelect.addEventListener('change', updatePreview);
    razaSelect.addEventListener('change', updatePreview);
    procedenciaInput.addEventListener('input', updatePreview);

    updatePreview();
});
</script>
@endsection
