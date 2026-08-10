@extends('layouts.authenticated')

@section('title', 'Editar Finca')

@section('content')
@php
    $fincaId = $finca['id'] ?? $finca['id_Finca'] ?? null;
    $nombreFinca = $finca['nombre'] ?? $finca['Nombre'] ?? '';
    $tipoExpFinca = $finca['explotacion_tipo'] ?? $finca['Explotacion_Tipo'] ?? '';
    $terreno = $finca['terreno'] ?? [];
@endphp
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header & Breadcrumb -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Editar Finca</h1>
            <p class="text-sm text-gray-500 mt-1">Actualice la información de la finca #{{ $fincaId }} (API V2)</p>
        </div>
        <a href="{{ route('fincas.index') }}" class="inline-flex items-center text-sm text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-medium transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a la Lista
        </a>
    </div>

    <!-- Error Alert -->
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm" role="alert">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Form Container -->
    <form method="POST" action="{{ route('fincas.update', $fincaId) }}" class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-8">
        @csrf
        @method('PUT')

        <!-- General Info Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center pb-2 border-b border-gray-100">
                <span class="w-8 h-8 rounded-lg bg-ganaderasoft-celeste/15 flex items-center justify-center mr-3 text-lg">🏡</span>
                Información General
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="Nombre" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Nombre de la Finca <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="Nombre" id="Nombre" required
                        value="{{ old('Nombre', $nombreFinca) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="Explotacion_Tipo" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Tipo de Explotación <span class="text-red-500">*</span>
                    </label>
                    <select name="Explotacion_Tipo" id="Explotacion_Tipo" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione el tipo...</option>
                        @foreach($tipoExplotacion as $tipo)
                            @php
                                $val = is_array($tipo) ? ($tipo['nombre'] ?? $tipo['valor'] ?? '') : $tipo;
                            @endphp
                            <option value="{{ $val }}" {{ old('Explotacion_Tipo', $tipoExpFinca) == $val ? 'selected' : '' }}>
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Terreno Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center pb-2 border-b border-gray-100">
                <span class="w-8 h-8 rounded-lg bg-ganaderasoft-verde/20 flex items-center justify-center mr-3 text-lg">🌱</span>
                Información del Terreno
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="Superficie" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Superficie (ha) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="Superficie" id="Superficie" step="0.01" required
                        value="{{ old('Superficie', $terreno['superficie'] ?? $terreno['Superficie'] ?? '') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="Relieve" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Tipo de Relieve <span class="text-red-500">*</span>
                    </label>
                    <select name="Relieve" id="Relieve" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione...</option>
                        @foreach($tipoRelieve as $relieve)
                            @php
                                $val = is_array($relieve) ? ($relieve['valor'] ?? $relieve['nombre'] ?? '') : $relieve;
                                $currVal = $terreno['relieve'] ?? $terreno['Relieve'] ?? '';
                            @endphp
                            <option value="{{ $val }}" {{ old('Relieve', $currVal) == $val ? 'selected' : '' }}>
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="Suelo_Textura" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Textura del Suelo <span class="text-red-500">*</span>
                    </label>
                    <select name="Suelo_Textura" id="Suelo_Textura" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione...</option>
                        @foreach($texturaSuelo as $textura)
                            @php
                                $val = is_array($textura) ? ($textura['nombre'] ?? $textura['valor'] ?? '') : $textura;
                                $currVal = $terreno['suelo_textura'] ?? $terreno['Suelo_Textura'] ?? '';
                            @endphp
                            <option value="{{ $val }}" {{ old('Suelo_Textura', $currVal) == $val ? 'selected' : '' }}>
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="ph_Suelo" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        pH del Suelo <span class="text-red-500">*</span>
                    </label>
                    <select name="ph_Suelo" id="ph_Suelo" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione...</option>
                        @foreach($phSuelo as $ph)
                            @php
                                $code = is_array($ph) ? ($ph['codigo'] ?? $ph['valor'] ?? $ph['nombre'] ?? '') : $ph;
                                $label = is_array($ph) ? ($ph['nombre'] ?? $code) : $ph;
                                $currVal = $terreno['ph_suelo'] ?? $terreno['ph_Suelo'] ?? '';
                            @endphp
                            <option value="{{ $code }}" {{ old('ph_Suelo', $currVal) == $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="Precipitacion" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Precipitación Anual (mm) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="Precipitacion" id="Precipitacion" step="0.01" required
                        value="{{ old('Precipitacion', $terreno['precipitacion'] ?? $terreno['Precipitacion'] ?? '') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="Velocidad_Viento" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Velocidad Viento (km/h) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="Velocidad_Viento" id="Velocidad_Viento" step="0.01" required
                        value="{{ old('Velocidad_Viento', $terreno['velocidad_viento'] ?? $terreno['Velocidad_Viento'] ?? '') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
            </div>
        </div>

        <!-- Climatic Info Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center pb-2 border-b border-gray-100">
                <span class="w-8 h-8 rounded-lg bg-yellow-100 text-yellow-800 flex items-center justify-center mr-3 text-lg">☀️</span>
                Información Climática
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="Temp_Anual" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Temp. Anual (°C) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="Temp_Anual" id="Temp_Anual" required
                        value="{{ old('Temp_Anual', $terreno['temp_anual'] ?? $terreno['Temp_Anual'] ?? '') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="Temp_Min" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Temp. Mínima (°C) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="Temp_Min" id="Temp_Min" required
                        value="{{ old('Temp_Min', $terreno['temp_min'] ?? $terreno['Temp_Min'] ?? '') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="Temp_Max" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Temp. Máxima (°C) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="Temp_Max" id="Temp_Max" required
                        value="{{ old('Temp_Max', $terreno['temp_max'] ?? $terreno['Temp_Max'] ?? '') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="Radiacion" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Radiación Solar <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="Radiacion" id="Radiacion" step="0.01" required
                        value="{{ old('Radiacion', $terreno['radiacion'] ?? $terreno['Radiacion'] ?? '') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
            </div>
        </div>

        <!-- Water & Irrigation Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center pb-2 border-b border-gray-100">
                <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center mr-3 text-lg">💧</span>
                Recursos Hídricos y Riego
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="Fuente_Agua" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Fuente de Agua <span class="text-red-500">*</span>
                    </label>
                    <select name="Fuente_Agua" id="Fuente_Agua" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione...</option>
                        @foreach($fuenteAgua as $fuente)
                            @php
                                $val = is_array($fuente) ? ($fuente['nombre'] ?? $fuente['valor'] ?? '') : $fuente;
                                $currVal = $terreno['fuente_agua'] ?? $terreno['Fuente_Agua'] ?? '';
                            @endphp
                            <option value="{{ $val }}" {{ old('Fuente_Agua', $currVal) == $val ? 'selected' : '' }}>
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="Caudal_Disponible" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Caudal Disponible (L/día) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="Caudal_Disponible" id="Caudal_Disponible" required
                        value="{{ old('Caudal_Disponible', $terreno['caudal_disponible'] ?? $terreno['Caudal_Disponible'] ?? '') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="Riego_Metodo" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Método de Riego <span class="text-red-500">*</span>
                    </label>
                    <select name="Riego_Metodo" id="Riego_Metodo" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione...</option>
                        @foreach($metodoRiego as $metodo)
                            @php
                                $val = is_array($metodo) ? ($metodo['nombre'] ?? $metodo['valor'] ?? '') : $metodo;
                                $currVal = $terreno['riego_metodo'] ?? $terreno['Riego_Metodo'] ?? '';
                            @endphp
                            <option value="{{ $val }}" {{ old('Riego_Metodo', $currVal) == $val ? 'selected' : '' }}>
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
            <a href="{{ route('fincas.index') }}" 
                class="px-6 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                class="px-8 py-3 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul text-white text-sm font-semibold rounded-xl hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste transition-all duration-200 shadow-md">
                Actualizar Finca
            </button>
        </div>
    </form>
</div>
@endsection
