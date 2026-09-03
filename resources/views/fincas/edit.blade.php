@extends('layouts.authenticated')

@section('title', 'Editar finca')

@section('content')
@php
    $fincaId = $finca['id'] ?? $finca['id_Finca'] ?? null;
    $nombreFinca = $finca['nombre'] ?? $finca['Nombre'] ?? '';
    $tipoExpFinca = $finca['explotacion_tipo'] ?? $finca['Explotacion_Tipo'] ?? '';
    $terreno = $finca['terreno'] ?? [];
    
    $superficie = $terreno['superficie'] ?? $terreno['Superficie'] ?? '';
    $relieveVal = $terreno['relieve'] ?? $terreno['Relieve'] ?? '';
    $texturaVal = $terreno['suelo_textura'] ?? $terreno['Suelo_Textura'] ?? '';
    $phVal = $terreno['ph_suelo'] ?? $terreno['ph_Suelo'] ?? '';
    $precipitacion = $terreno['precipitacion'] ?? $terreno['Precipitacion'] ?? '1200';
    $viento = $terreno['velocidad_viento'] ?? $terreno['Velocidad_Viento'] ?? '15';
    
    $tempAnual = $terreno['temp_anual'] ?? $terreno['Temp_Anual'] ?? '28';
    $tempMin = $terreno['temp_min'] ?? $terreno['Temp_Min'] ?? '22';
    $tempMax = $terreno['temp_max'] ?? $terreno['Temp_Max'] ?? '34';
    $radiacion = $terreno['radiacion'] ?? $terreno['Radiacion'] ?? '4.5';
    
    $fuenteAguaVal = $terreno['fuente_agua'] ?? $terreno['Fuente_Agua'] ?? '';
    $riegoVal = $terreno['riego_metodo'] ?? $terreno['Riego_Metodo'] ?? '';

    $createdAt = $finca['created_at'] ?? null;
    $updatedAt = $finca['updated_at'] ?? null;
    $inicial = strtoupper(substr($nombreFinca ?: 'F', 0, 1));
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🏡
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar finca: {{ $nombreFinca }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualice los parámetros de producción y características territoriales</p>
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

    <form method="POST" action="{{ route('fincas.update', $fincaId) }}" id="formEditarFinca" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                
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
                                   value="{{ old('Nombre', $nombreFinca) }}" maxlength="100"
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
                                        $isSelected = old('Explotacion_Tipo', $tipoExpFinca) === $val;
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
                                   value="{{ old('Superficie', $superficie) }}" placeholder="Ej: 150.5"
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
                                    <option value="{{ $val }}" {{ old('Relieve', $relieveVal) === $val ? 'selected' : '' }}>
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
                                    <option value="{{ $val }}" {{ old('Suelo_Textura', $texturaVal) === $val ? 'selected' : '' }}>
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
                                    <option value="{{ $code }}" {{ old('ph_Suelo', $phVal) == $code ? 'selected' : '' }}>
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
                                   value="{{ old('Precipitacion', $precipitacion) }}" placeholder="Ej: 1200"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>

                        <!-- Velocidad del viento -->
                        <div>
                            <label for="vientoInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Velocidad viento (km/h) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="Velocidad_Viento" id="vientoInput" step="0.01" min="0" required
                                   value="{{ old('Velocidad_Viento', $viento) }}" placeholder="Ej: 15"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>
                    </div>
                </div>

                <!-- Card 3: Recursos Hídricos y Clima -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
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
                                    <option value="{{ $val }}" {{ old('Fuente_Agua', $fuenteAguaVal) === $val ? 'selected' : '' }}>
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
                                    <option value="{{ $val }}" {{ old('Riego_Metodo', $riegoVal) === $val ? 'selected' : '' }}>
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
                                   value="{{ old('Temp_Anual', $tempAnual) }}" placeholder="Ej: 28"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>

                        <!-- Temp Mínima -->
                        <div>
                            <label for="tempMin" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Temp. Mínima (°C) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Temp_Min" id="tempMin" required 
                                   value="{{ old('Temp_Min', $tempMin) }}" placeholder="Ej: 22"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>

                        <!-- Temp Máxima -->
                        <div>
                            <label for="tempMax" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Temp. Máxima (°C) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Temp_Max" id="tempMax" required 
                                   value="{{ old('Temp_Max', $tempMax) }}" placeholder="Ej: 34"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>

                        <!-- Radiación -->
                        <div>
                            <label for="radiacionInput" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Radiación Solar <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="Radiacion" id="radiacionInput" step="0.01" min="0" required 
                                   value="{{ old('Radiacion', $radiacion) }}" placeholder="Ej: 4.5"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Sidebar Interactivo en Vivo (1 Tercio) -->
            <div class="space-y-6 flex flex-col justify-between h-full">
                <!-- Card 1: Ficha Previa de la Finca -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>🏡</span> Ficha de la finca
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3.5">
                            <div class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-2xl shadow-xs shrink-0" id="previewInicial">
                                {{ $inicial ?: '🏡' }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-base font-bold text-gray-900 truncate" id="previewNombre">{{ $nombreFinca }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-100 mt-1" id="previewTipo">
                                    {{ $tipoExpFinca ?: 'General' }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3.5 text-sm pt-1">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Superficie</span>
                                <span class="font-bold text-gray-900" id="previewSuperficie">{{ $superficie ? number_format((float)$superficie, 1, ',', '.') . ' ha' : '0 ha' }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Relieve</span>
                                <span class="font-medium text-gray-900" id="previewRelieve">{{ $relieveVal ?: 'No especificado' }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fuente de agua</span>
                                <span class="font-medium text-gray-900" id="previewAgua">{{ $fuenteAguaVal ?: 'No especificada' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons en el Sidebar -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar cambios
                            </button>
                            <a href="{{ route('fincas.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Registro del Sistema -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex-1 flex flex-col">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>⚙️</span> Registro del sistema
                        </h3>
                    </div>
                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-around">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Identificador único</span>
                            <p class="text-sm font-bold text-gray-900 font-mono">
                                ID #{{ $fincaId ?? 'N/A' }}
                            </p>
                        </div>
                        @if($createdAt)
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Fecha de registro</span>
                                <p class="text-sm font-bold text-gray-900">
                                    {{ date('d/m/Y H:i', strtotime($createdAt)) }}
                                </p>
                            </div>
                        @endif
                        @if($updatedAt)
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Última actualización</span>
                                <p class="text-sm font-bold text-gray-900">
                                    {{ date('d/m/Y H:i', strtotime($updatedAt)) }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Zona de Peligro (Pie de página horizontal) -->
    <div class="mt-10 pt-8 border-t border-gray-200">
        <div class="bg-white rounded-2xl border border-red-200 shadow-xs p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1 max-w-2xl">
                <h4 class="text-base font-bold text-red-900 flex items-center gap-2">
                    <span>⚠️</span> Zona de peligro
                </h4>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Al eliminar esta finca se borrarán permanentemente <span class="font-semibold text-red-600">todos sus rebaños asociados</span>, <span class="font-semibold text-red-600">todos los animales registrados en ellos</span>, pesajes de leche, lactancias, pesajes corporales, eventos sanitarios/vacunas, árboles genealógicos y asignaciones de personal.
                </p>
            </div>
            <div class="shrink-0">
                <button type="button"
                    onclick="openGenericConfirmModal({
                        formId: 'formDeleteFinca',
                        intent: 'danger',
                        title: 'Eliminar finca definitivamente',
                        message: '¿Estás seguro de que deseas eliminar esta finca permanentemente? Se eliminarán de forma irreversible TODOS sus rebaños, animales, producciones lecheras, lactancias, historiales de peso y registros sanitarios vinculados a ella.',
                        confirmText: 'Sí, eliminar definitivamente'
                    })"
                    class="py-3 px-5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 hover:border-red-600 font-bold rounded-xl transition-all duration-200 text-xs flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Eliminar finca definitivamente</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para Eliminación Definitiva -->
    <form id="formDeleteFinca" action="{{ route('fincas.destroy', $fincaId) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<x-ui.confirm-modal />


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
        previewNombre.textContent = nom || 'Finca #{{ $fincaId }}';
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
</script>
@endsection