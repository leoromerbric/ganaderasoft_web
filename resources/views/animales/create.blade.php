@extends('layouts.authenticated')

@section('title', 'Crear nuevo animal')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                🐄
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Crear nuevo animal
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra un nuevo ejemplar en el inventario ganadero</p>
            </div>
        </div>
        <div>
            <a href="{{ route('animales.index') }}" 
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
    <form action="{{ route('animales.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Identificación del Animal -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Datos de identificación
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Nombre del animal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('nombre') border-red-500 @enderror"
                                   placeholder="Ej: Vaca lechera #1">
                            @error('nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Código Identificador -->
                        <div>
                            <label for="codigo_animal" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Código identificador <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="codigo_animal" name="codigo_animal" value="{{ old('codigo_animal') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('codigo_animal') border-red-500 @enderror"
                                   placeholder="Ej: Bov-001">
                            @error('codigo_animal')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Sexo -->
                        <div>
                            <label for="sexo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Sexo <span class="text-red-500">*</span>
                            </label>
                            <select id="sexo" name="sexo" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('sexo') border-red-500 @enderror">
                                <option value="">Seleccione el sexo...</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Macho (♂)</option>
                                <option value="H" {{ old('sexo') == 'H' ? 'selected' : '' }}>Hembra (♀)</option>
                            </select>
                            @error('sexo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label for="fecha_nacimiento" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_nacimiento') border-red-500 @enderror">
                            @error('fecha_nacimiento')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Ubicación, Genética y Salud Inicial -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🏡</span> Ubicación, genética y salud inicial
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Rebaño -->
                        <div>
                            <label for="rebano_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Rebaño perteneciente <span class="text-red-500">*</span>
                            </label>
                            <select id="rebano_id" name="rebano_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('rebano_id') border-red-500 @enderror">
                                <option value="">Seleccione un rebaño...</option>
                                @foreach($rebanos as $rebano)
                                    <option value="{{ $rebano['id'] }}" {{ old('rebano_id') == $rebano['id'] ? 'selected' : '' }}>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('composicion_raza_id') border-red-500 @enderror">
                                <option value="">Seleccione una raza...</option>
                                @foreach($razas as $raza)
                                    @php
                                        $siglasRaza = $raza['siglas'] ?? '';
                                    @endphp
                                    <option value="{{ $raza['id'] }}" {{ old('composicion_raza_id') == $raza['id'] ? 'selected' : '' }}>
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
                            <input type="text" id="procedencia" name="procedencia" value="{{ old('procedencia') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('procedencia') border-red-500 @enderror"
                                   placeholder="Ej: Nacido en finca, compra local">
                            @error('procedencia')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Estado de Salud Inicial -->
                        <div>
                            <label for="estado_inicial_estado_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Estado de salud inicial <span class="text-red-500">*</span>
                            </label>
                            <select id="estado_inicial_estado_id" name="estado_inicial[estado_salud_id]" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('estado_inicial.estado_salud_id') border-red-500 @enderror">
                                <option value="">Seleccione un estado de salud...</option>
                                @foreach($estados as $estado)
                                    @php
                                        $estId = $estado['id'] ?? null;
                                        $estNombre = $estado['nombre'] ?? '';
                                    @endphp
                                    <option value="{{ $estId }}" {{ old('estado_inicial.estado_salud_id') == $estId ? 'selected' : '' }}>
                                        {{ $estNombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado_inicial.estado_salud_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Ficha previa del ejemplar
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl flex items-center space-x-3">
                            <div id="previewIcono" class="w-12 h-12 rounded-xl bg-white border border-emerald-200 text-emerald-700 font-bold flex items-center justify-center text-2xl shadow-xs">
                                🐄
                            </div>
                            <div>
                                <p id="previewNombre" class="text-base font-bold text-gray-900">Sin nombre</p>
                                <p id="previewCodigo" class="text-xs text-gray-400 font-mono">#Codigo</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>Sexo:</span>
                                <span id="previewSexo" class="font-bold text-gray-900">No especificado</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Rebaño:</span>
                                <span id="previewRebano" class="font-bold text-gray-900">No seleccionado</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Raza:</span>
                                <span id="previewRaza" class="font-bold text-gray-900">No seleccionada</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Procedencia:</span>
                                <span id="previewProcedencia" class="font-semibold text-gray-900">No especificada</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar ejemplar
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
    const nombreInput     = document.getElementById('nombre');
    const codigoInput     = document.getElementById('codigo_animal');
    const sexoSelect      = document.getElementById('sexo');
    const rebanoSelect    = document.getElementById('rebano_id');
    const razaSelect      = document.getElementById('composicion_raza_id');
    const procedenciaInput= document.getElementById('procedencia');

    const previewNombre      = document.getElementById('previewNombre');
    const previewCodigo      = document.getElementById('previewCodigo');
    const previewSexo        = document.getElementById('previewSexo');
    const previewRebano      = document.getElementById('previewRebano');
    const previewRaza        = document.getElementById('previewRaza');
    const previewProcedencia = document.getElementById('previewProcedencia');
    const previewIcono       = document.getElementById('previewIcono');

    function updatePreview() {
        previewNombre.textContent = nombreInput.value.trim() || 'Sin nombre';
        previewCodigo.textContent = codigoInput.value.trim() ? `#${codigoInput.value.trim()}` : '#CODIGO';

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
