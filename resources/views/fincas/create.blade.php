@extends('layouts.authenticated')

@section('title', 'Nueva Finca')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Nueva Finca</h2>
            <p class="text-gray-600 mt-1">Registre una nueva finca en el sistema</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('fincas.store') }}" class="bg-white rounded-xl shadow-md p-6">
            @csrf

            <!-- Información General -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4 flex items-center">
                    <span class="text-2xl mr-2">🏡</span>
                    Información General
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Finca <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="Nombre" id="Nombre" required
                            value="{{ old('Nombre') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    </div>

                    <div>
                        <label for="Explotacion_Tipo" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Explotación <span class="text-red-500">*</span>
                        </label>
                        <select name="Explotacion_Tipo" id="Explotacion_Tipo" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione...</option>
                            @foreach($tipoExplotacion as $tipo)
                                <option value="{{ $tipo['nombre'] }}" {{ old('Explotacion_Tipo') == $tipo['nombre'] ? 'selected' : '' }}>
                                    {{ $tipo['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Información del Terreno -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4 flex items-center">
                    <span class="text-2xl mr-2">🌍</span>
                    Información del Terreno
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="Superficie" class="block text-sm font-medium text-gray-700 mb-2">
                            Superficie (ha) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="Superficie" id="Superficie" step="0.01" required
                            value="{{ old('Superficie') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    </div>

                    <div>
                        <label for="Relieve" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Relieve <span class="text-red-500">*</span>
                        </label>
                        <select name="Relieve" id="Relieve" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione...</option>
                            @foreach($tipoRelieve as $relieve)
                                <option value="{{ $relieve['valor'] }}" {{ old('Relieve') == $relieve['valor'] ? 'selected' : '' }}>
                                    {{ $relieve['valor'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="Suelo_Textura" class="block text-sm font-medium text-gray-700 mb-2">
                            Textura del Suelo <span class="text-red-500">*</span>
                        </label>
                        <select name="Suelo_Textura" id="Suelo_Textura" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione...</option>
                            @foreach($texturaSuelo as $textura)
                                <option value="{{ $textura['nombre'] }}" {{ old('Suelo_Textura') == $textura['nombre'] ? 'selected' : '' }}>
                                    {{ $textura['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="ph_Suelo" class="block text-sm font-medium text-gray-700 mb-2">
                            pH del Suelo <span class="text-red-500">*</span>
                        </label>
                        <select name="ph_Suelo" id="ph_Suelo" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione...</option>
                            @foreach($phSuelo as $ph)
                                <option value="{{ $ph['codigo'] }}" {{ old('ph_Suelo') == $ph['codigo'] ? 'selected' : '' }}>
                                    {{ $ph['nombre'] }} - {{ $ph['descripcion'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="Precipitacion" class="block text-sm font-medium text-gray-700 mb-2">
                            Precipitación Anual (mm) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="Precipitacion" id="Precipitacion" step="0.01" required
                            value="{{ old('Precipitacion') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    </div>

                    <div>
                        <label for="Velocidad_Viento" class="block text-sm font-medium text-gray-700 mb-2">
                            Velocidad del Viento (km/h) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="Velocidad_Viento" id="Velocidad_Viento" step="0.01" required
                            value="{{ old('Velocidad_Viento') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Información Climática -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4 flex items-center">
                    <span class="text-2xl mr-2">🌡️</span>
                    Información Climática
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="Temp_Anual" class="block text-sm font-medium text-gray-700 mb-2">
                            Temperatura Anual (°C) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="Temp_Anual" id="Temp_Anual" required
                            value="{{ old('Temp_Anual') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    </div>

                    <div>
                        <label for="Temp_Min" class="block text-sm font-medium text-gray-700 mb-2">
                            Temperatura Mínima (°C) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="Temp_Min" id="Temp_Min" required
                            value="{{ old('Temp_Min') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    </div>

                    <div>
                        <label for="Temp_Max" class="block text-sm font-medium text-gray-700 mb-2">
                            Temperatura Máxima (°C) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="Temp_Max" id="Temp_Max" required
                            value="{{ old('Temp_Max') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    </div>

                    <div>
                        <label for="Radiacion" class="block text-sm font-medium text-gray-700 mb-2">
                            Radiación Solar <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="Radiacion" id="Radiacion" step="0.01" required
                            value="{{ old('Radiacion') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Recursos Hídricos -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4 flex items-center">
                    <span class="text-2xl mr-2">💧</span>
                    Recursos Hídricos
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="Fuente_Agua" class="block text-sm font-medium text-gray-700 mb-2">
                            Fuente de Agua <span class="text-red-500">*</span>
                        </label>
                        <select name="Fuente_Agua" id="Fuente_Agua" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione...</option>
                            @foreach($fuenteAgua as $fuente)
                                <option value="{{ $fuente['nombre'] }}" {{ old('Fuente_Agua') == $fuente['nombre'] ? 'selected' : '' }}>
                                    {{ $fuente['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="Caudal_Disponible" class="block text-sm font-medium text-gray-700 mb-2">
                            Caudal Disponible (L/día) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="Caudal_Disponible" id="Caudal_Disponible" required
                            value="{{ old('Caudal_Disponible') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                    </div>

                    <div>
                        <label for="Riego_Metodo" class="block text-sm font-medium text-gray-700 mb-2">
                            Método de Riego <span class="text-red-500">*</span>
                        </label>
                        <select name="Riego_Metodo" id="Riego_Metodo" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione...</option>
                            @foreach($metodoRiego as $metodo)
                                <option value="{{ $metodo['nombre'] }}" {{ old('Riego_Metodo') == $metodo['nombre'] ? 'selected' : '' }}>
                                    {{ $metodo['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('fincas.index') }}" 
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-ganaderasoft-verde-oscurohover:bg-opacity-90 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                    Guardar
                </button>
            </div>
        </form>
    </div>
@endsection
