@extends('layouts.authenticated')

@section('title', 'Nuevo Registro de Leche')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('leche.index') }}" 
                   class="text-ganaderasoft-azul hover:text-ganaderasoft-celeste transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-3xl font-bold text-ganaderasoft-negro">Nuevo Registro de Leche</h2>
                    <p class="text-gray-600 mt-1">Registra la producción diaria de leche</p>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <p class="font-medium mb-2">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <form method="POST" action="{{ route('leche.store') }}" class="space-y-6">
                @csrf

                <!-- Lactation Period Selection -->
                <div>
                    <label for="leche_lactancia_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Período de Lactancia <span class="text-red-500">*</span>
                    </label>
                    <select name="leche_lactancia_id" id="leche_lactancia_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste @error('leche_lactancia_id') border-red-500 @enderror">
                        <option value="">Seleccionar Período de Lactancia</option>
                        @foreach($lactancias as $lactancia)
                            @php
                                $fechaInicio = date('d/m/Y', strtotime($lactancia['lactancia_fecha_inicio']));
                                $fechaFin = $lactancia['Lactancia_fecha_fin'] ? date('d/m/Y', strtotime($lactancia['Lactancia_fecha_fin'])) : 'En curso';
                                $isSelected = old('leche_lactancia_id', $lactanciaId) == $lactancia['lactancia_id'];
                            @endphp
                            <option value="{{ $lactancia['lactancia_id'] }}" {{ $isSelected ? 'selected' : '' }}>
                                Animal {{ $lactancia['lactancia_etapa_anid'] }} - {{ $fechaInicio }} hasta {{ $fechaFin }}
                            </option>
                        @endforeach
                    </select>
                    @error('leche_lactancia_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Pesaje -->
                <div>
                    <label for="leche_fecha_pesaje" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Pesaje <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="leche_fecha_pesaje" 
                           id="leche_fecha_pesaje" 
                           value="{{ old('leche_fecha_pesaje', date('Y-m-d')) }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste @error('leche_fecha_pesaje') border-red-500 @enderror">
                    @error('leche_fecha_pesaje')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cantidad de Leche -->
                <div>
                    <label for="leche_pesaje_Total" class="block text-sm font-medium text-gray-700 mb-2">
                        Cantidad de Leche (Litros) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               name="leche_pesaje_Total" 
                               id="leche_pesaje_Total" 
                               value="{{ old('leche_pesaje_Total') }}" 
                               step="0.01" 
                               min="0" 
                               placeholder="Ej: 25.50" 
                               required
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-ganaderasoft-celeste @error('leche_pesaje_Total') border-red-500 @enderror">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">Litros</span>
                        </div>
                    </div>
                    @error('leche_pesaje_Total')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Ingresa la cantidad total de leche producida en este pesaje</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6">
                    <a href="{{ route('leche.index') }}" 
                       class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde-oscuro transition-colors duration-200 shadow-md hover:shadow-lg">
                        Registrar Producción
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Tips -->
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Consejos para el registro</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Registra la producción inmediatamente después del ordeño</li>
                            <li>Asegúrate de que la cantidad esté en litros</li>
                            <li>Puedes registrar múltiples ordeños del mismo día por separado</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection