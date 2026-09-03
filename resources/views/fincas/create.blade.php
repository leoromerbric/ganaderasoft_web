@extends('layouts.authenticated')

@section('title', 'Registrar nueva finca')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🏡
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Registrar nueva finca
                </h1>
                <p class="text-gray-500 text-sm mt-1">Configure los datos territoriales, agronómicos y productivos de la unidad ganadera</p>
            </div>
        </div>
        <div>
            <a href="{{ route('fincas.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm space-y-1">
            <div class="flex items-center space-x-2 font-bold mb-1">
                <span class="text-lg">⚠️</span>
                <p class="text-sm">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario Principal -->
    <form method="POST" action="{{ route('fincas.store') }}" id="formCreateFinca" class="space-y-6">
        @csrf

        <!-- Fila Superior: Datos Generales/Terreno (Izquierda) y Ficha Resumen (Derecha) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            <!-- Columna Izquierda Superior (2 Tercios): Card 1 y Card 2 -->
            <div class="lg:col-span-2 space-y-6 flex flex-col justify-between">
                
                <!-- Card 1: Información General -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🏡</span> Información general de la finca
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre de la finca -->
                        <div>
                            <label for="nombreInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Nombre de la finca <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Nombre" id="nombreInput" required 
                                   value="{{ old('Nombre') }}" maxlength="100"
                                   placeholder="Ej: Finca La Esperanza"
                                   class="w-full px-4 py-3 border @error('Nombre') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('Nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Tipo de explotación -->
                        <div>
                            <label for="tipoInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Tipo de explotación <span class="text-red-500">*</span>
                            </label>
                            <select name="Explotacion_Tipo" id="tipoInput" required
                                    class="w-full px-4 py-3 border @error('Explotacion_Tipo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccione modelo productivo --</option>
                                @foreach($tipoExplotacion as $tipo)
                                    @php
                                        $val = is_array($tipo) ? ($tipo['nombre'] ?? $tipo['valor'] ?? '') : $tipo;
                                        $isSelected = old('Explotacion_Tipo') === $val;
                                    @endphp
                                    <option value="{{ $val }}" {{ $isSelected ? 'selected' : '' }}>
                                        💼 {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Explotacion_Tipo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Características del Terreno y Suelo -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🌱</span> Características del terreno y suelo
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Superficie -->
                        <div>
                            <label for="superficieInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Superficie (ha) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="Superficie" id="superficieInput" step="0.01" min="0" required
                                   value="{{ old('Superficie') }}" placeholder="Ej: 150.5"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>

                        <!-- Relieve -->
                        <div>
                            <label for="relieveInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Tipo de relieve <span class="text-red-500">*</span>
                            </label>
                            <select name="Relieve" id="relieveInput" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar relieve --</option>
                                @foreach($tipoRelieve as $relieve)
                                    @php
                                        $val = is_array($relieve) ? ($relieve['valor'] ?? $relieve['nombre'] ?? '') : $relieve;
                                    @endphp
                                    <option value="{{ $val }}" {{ old('Relieve') === $val ? 'selected' : '' }}>
                                        ⛰️ {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Textura del suelo -->
                        <div>
                            <label for="texturaInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Textura del suelo <span class="text-red-500">*</span>
                            </label>
                            <select name="Suelo_Textura" id="texturaInput" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar textura --</option>
                                @foreach($texturaSuelo as $textura)
                                    @php
                                        $val = is_array($textura) ? ($textura['nombre'] ?? $textura['valor'] ?? '') : $textura;
                                    @endphp
                                    <option value="{{ $val }}" {{ old('Suelo_Textura') === $val ? 'selected' : '' }}>
                                        🪨 {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- pH del suelo -->
                        <div>
                            <label for="phInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                pH del suelo <span class="text-red-500">*</span>
                            </label>
                            <select name="ph_Suelo" id="phInput" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar pH --</option>
                                @foreach($phSuelo as $ph)
                                    @php
                                        $code = is_array($ph) ? ($ph['codigo'] ?? $ph['valor'] ?? $ph['nombre'] ?? '') : $ph;
                                        $label = is_array($ph) ? ($ph['nombre'] ?? $code) : $ph;
                                    @endphp
                                    <option value="{{ $code }}" {{ old('ph_Suelo', '6.5') == $code ? 'selected' : '' }}>
                                        🧪 {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Precipitación -->
                        <div>
                            <label for="precipitacionInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Precipitación anual (mm) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="Precipitacion" id="precipitacionInput" step="0.01" min="0" required
                                   value="{{ old('Precipitacion') }}" placeholder="Ej: 1200"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>

                        <!-- Velocidad del viento -->
                        <div>
                            <label for="vientoInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Velocidad viento (km/h) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="Velocidad_Viento" id="vientoInput" step="0.01" min="0" required
                                   value="{{ old('Velocidad_Viento') }}" placeholder="Ej: 15"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha Superior (1 Tercio): Ficha de la Finca con Botones -->
            <div class="lg:col-span-1 h-full">
                <!-- Card 1: Ficha Previa de la Finca -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col justify-between">
                    <div>
                        <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                            <h3 class="text-lg font-bold flex items-center gap-2">
                                <span>🏡</span> Ficha de la nueva finca
                            </h3>
                        </div>

                        <div class="p-6 space-y-5">
                            <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3.5">
                                <div class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-2xl shadow-xs shrink-0" id="previewInicial">
                                    🏡
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-base font-bold text-gray-900 truncate" id="previewNombre">Nueva finca</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-100 mt-1" id="previewTipo">
                                        General
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-3.5 text-sm pt-1">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Superficie</span>
                                    <span class="font-bold text-gray-900" id="previewSuperficie">0 ha</span>
                                </div>

                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Relieve</span>
                                    <span class="font-medium text-gray-900" id="previewRelieve">No especificado</span>
                                </div>

                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fuente de agua</span>
                                    <span class="font-medium text-gray-900" id="previewAgua">No especificada</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons en el Sidebar -->
                    <div class="p-6 pt-0 space-y-3">
                        <button type="submit"
                                class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                            💾 Guardar finca
                        </button>
                        <a href="{{ route('fincas.index') }}"
                           class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila Inferior: Recursos Hídricos/Clima (Izquierda) y Buenas Prácticas (Derecha) - MISMO HEIGHT EXACTO -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            <!-- Columna Izquierda Inferior (2 Tercios): Card 3 Recursos Hídricos y Clima -->
            <div class="lg:col-span-2 h-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6 h-full flex flex-col justify-between">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>💧</span> Recursos hídricos y clima
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Fuente de agua -->
                        <div>
                            <label for="aguaInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fuente de agua principal <span class="text-red-500">*</span>
                            </label>
                            <select name="Fuente_Agua" id="aguaInput" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar fuente --</option>
                                @foreach($fuenteAgua as $fuente)
                                    @php
                                        $val = is_array($fuente) ? ($fuente['nombre'] ?? $fuente['valor'] ?? '') : $fuente;
                                    @endphp
                                    <option value="{{ $val }}" {{ old('Fuente_Agua') === $val ? 'selected' : '' }}>
                                        💧 {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Método de riego -->
                        <div>
                            <label for="riegoInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Método de riego <span class="text-red-500">*</span>
                            </label>
                            <select name="Riego_Metodo" id="riegoInput" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar método --</option>
                                @foreach($metodoRiego as $riego)
                                    @php
                                        $val = is_array($riego) ? ($riego['nombre'] ?? $riego['valor'] ?? '') : $riego;
                                    @endphp
                                    <option value="{{ $val }}" {{ old('Riego_Metodo') === $val ? 'selected' : '' }}>
                                        🚿 {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-2">
                        <!-- Temp Anual -->
                        <div>
                            <label for="tempAnual" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Temp. Promedio (°C) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Temp_Anual" id="tempAnual" required 
                                   value="{{ old('Temp_Anual', '28') }}" placeholder="Ej: 28"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>

                        <!-- Temp Mínima -->
                        <div>
                            <label for="tempMin" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Temp. Mínima (°C) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Temp_Min" id="tempMin" required 
                                   value="{{ old('Temp_Min', '22') }}" placeholder="Ej: 22"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>

                        <!-- Temp Máxima -->
                        <div>
                            <label for="tempMax" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Temp. Máxima (°C) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Temp_Max" id="tempMax" required 
                                   value="{{ old('Temp_Max', '34') }}" placeholder="Ej: 34"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>

                        <!-- Radiación -->
                        <div>
                            <label for="radiacionInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Radiación Solar <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="Radiacion" id="radiacionInput" step="0.01" min="0" required 
                                   value="{{ old('Radiacion', '4.5') }}" placeholder="Ej: 4.5"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha Inferior (1 Tercio): Card 2 Buenas Prácticas de Registro -->
            <div class="lg:col-span-1 h-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3 h-full flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                            <span>📌</span> Buenas prácticas de registro
                        </h4>
                        <ul class="text-xs text-gray-600 space-y-2.5 leading-relaxed pt-2">
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-500 font-bold">✓</span>
                                <span><strong>Superficie exacta:</strong> Permite calcular la carga animal óptima (UA/ha).</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-500 font-bold">✓</span>
                                <span><strong>Textura y pH:</strong> Ayuda a planificar rotación de potreros y fertilizaciones.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-500 font-bold">✓</span>
                                <span><strong>Rebaños:</strong> Podrás crear y asignar rebaños inmediatamente después de guardarla.</span>
                            </li>
                        </ul>
                    </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Live Preview Binding
    const nombreInput = document.getElementById('nombreInput');
    const tipoInput = document.getElementById('tipoInput');
    const superficieInput = document.getElementById('superficieInput');
    const relieveInput = document.getElementById('relieveInput');
    const aguaInput = document.getElementById('aguaInput');

    const previewNombre = document.getElementById('previewNombre');
    const previewInicial = document.getElementById('previewInicial');
    const previewTipo = document.getElementById('previewTipo');
    const previewSuperficie = document.getElementById('previewSuperficie');
    const previewRelieve = document.getElementById('previewRelieve');
    const previewAgua = document.getElementById('previewAgua');

    function actualizarPreview() {
        const nom = nombreInput.value.trim();
        previewNombre.textContent = nom || 'Nueva finca';
        previewInicial.textContent = nom ? nom.charAt(0).toUpperCase() : '🏡';

        const tipo = tipoInput.value;
        previewTipo.textContent = tipo || 'General';

        const sup = parseFloat(superficieInput.value) || 0;
        previewSuperficie.textContent = sup > 0 ? (sup.toLocaleString('es-ES', { minimumFractionDigits: 1, maximumFractionDigits: 2 }) + ' ha') : '0 ha';

        previewRelieve.textContent = relieveInput.value || 'No especificado';
        previewAgua.textContent = aguaInput.value || 'No especificada';
    }

    [nombreInput, tipoInput, superficieInput, relieveInput, aguaInput].forEach(el => {
        if (el) {
            el.addEventListener('input', actualizarPreview);
            el.addEventListener('change', actualizarPreview);
        }
    });

    document.addEventListener('DOMContentLoaded', actualizarPreview);
</script>
@endsection