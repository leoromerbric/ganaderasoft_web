@extends('layouts.authenticated')

@section('title', 'Editar medidas corporales')

@section('content')
@php
    $medidaId = $medidaCorporal['id'] ?? null;
    $anId = old('animal_id', $medidaCorporal['animal_id'] ?? data_get($medidaCorporal, 'animal.id') ?? null);
    $animalNombre = data_get($medidaCorporal, 'animal.nombre') ?? ('Animal #'.($anId ?? 'N/A'));
    $animalCodigo = data_get($medidaCorporal, 'animal.codigo_animal') ?? '';
    $valFecha = old('fecha_medicion', isset($medidaCorporal['fecha_medicion']) ? substr($medidaCorporal['fecha_medicion'], 0, 10) : (isset($medidaCorporal['created_at']) ? substr($medidaCorporal['created_at'], 0, 10) : date('Y-m-d')));
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-azul/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-xs">
                📏
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar medidas corporales #{{ $medidaId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Modifica la evaluación morfométrica y dimensiones físicas del ejemplar</p>
            </div>
        </div>
        <div>
            <a href="{{ route('medidas-corporales.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
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
    <form action="{{ route('medidas-corporales.update', $medidaId) }}" method="POST" id="formEditMedidas" novalidate>
        @csrf
        @method('PUT')

        <input type="hidden" name="animal_id" value="{{ old('animal_id', $anId) }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Animal Asociado -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>🐄</span> Animal asociado
                        </h3>
                        <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-600 rounded-full border border-gray-200">
                            🔒 Animal inmutable
                        </span>
                    </div>

                    <div class="p-4 bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 font-bold flex items-center justify-center text-2xl shadow-xs">
                                🐄
                            </div>
                            <div>
                                <p class="text-base font-bold text-gray-900">{{ $animalNombre }}</p>
                                @if($animalCodigo)
                                    <p class="text-xs text-gray-500 font-mono">Código: #{{ $animalCodigo }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Alturas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📏</span> Modificar alturas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="altura_hc" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Altura a la cruz (Hc)
                            </label>
                            <div class="relative">
                                <input type="number" name="altura_hc" id="altura_hc" value="{{ old('altura_hc', $medidaCorporal['altura_hc'] ?? '') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 135.0"
                                       class="w-full px-4 py-3 pr-14 border @error('altura_hc') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('altura_hc')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="altura_hg" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Altura a la grupa (Hg)
                            </label>
                            <div class="relative">
                                <input type="number" name="altura_hg" id="altura_hg" value="{{ old('altura_hg', $medidaCorporal['altura_hg'] ?? '') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 138.5"
                                       class="w-full px-4 py-3 pr-14 border @error('altura_hg') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('altura_hg')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 3: Perímetros -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>⭕</span> Modificar perímetros
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="perimetro_pt" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Perímetro torácico (Pt)
                            </label>
                            <div class="relative">
                                <input type="number" name="perimetro_pt" id="perimetro_pt" value="{{ old('perimetro_pt', $medidaCorporal['perimetro_pt'] ?? '') }}"
                                       step="0.1" min="0" max="400" placeholder="Ej: 180.0"
                                       class="w-full px-4 py-3 pr-14 border @error('perimetro_pt') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('perimetro_pt')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="perimetro_pca" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Perímetro de caña (Pca)
                            </label>
                            <div class="relative">
                                <input type="number" name="perimetro_pca" id="perimetro_pca" value="{{ old('perimetro_pca', $medidaCorporal['perimetro_pca'] ?? '') }}"
                                       step="0.1" min="0" max="100" placeholder="Ej: 20.5"
                                       class="w-full px-4 py-3 pr-14 border @error('perimetro_pca') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('perimetro_pca')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 4: Longitudes y Anchos -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📐</span> Modificar longitudes y anchos
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="longitud_lc" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Longitud corporal (Lc)
                            </label>
                            <div class="relative">
                                <input type="number" name="longitud_lc" id="longitud_lc" value="{{ old('longitud_lc', $medidaCorporal['longitud_lc'] ?? '') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 155.0"
                                       class="w-full px-4 py-3 pr-14 border @error('longitud_lc') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('longitud_lc')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="longitud_lg" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Longitud de grupa (Lg)
                            </label>
                            <div class="relative">
                                <input type="number" name="longitud_lg" id="longitud_lg" value="{{ old('longitud_lg', $medidaCorporal['longitud_lg'] ?? '') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 52.0"
                                       class="w-full px-4 py-3 pr-14 border @error('longitud_lg') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('longitud_lg')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="anchura_ag" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Ancho de grupa (Ag)
                            </label>
                            <div class="relative">
                                <input type="number" name="anchura_ag" id="anchura_ag" value="{{ old('anchura_ag', $medidaCorporal['anchura_ag'] ?? '') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 50.0"
                                       class="w-full px-4 py-3 pr-14 border @error('anchura_ag') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all font-semibold">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">cm</span>
                            </div>
                            @error('anchura_ag')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 5: Fecha y Observaciones -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📝</span> Datos del registro
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fecha_medicion" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de medición <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_medicion" id="fecha_medicion" required value="{{ $valFecha }}"
                                   class="w-full px-4 py-3 border @error('fecha_medicion') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('fecha_medicion')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="observaciones" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3" maxlength="255"
                                      placeholder="Notas adicionales sobre la conformación o estado del ejemplar..."
                                      class="w-full px-4 py-3 border @error('observaciones') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('observaciones', $medidaCorporal['observaciones'] ?? '') }}</textarea>
                            @error('observaciones')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Panel de Guardado (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>⚙️</span> Actualizar registro
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="text-xs text-gray-500 space-y-2 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>ID medición:</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $medidaId }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fecha registro:</span>
                                <span class="font-semibold text-gray-800">{{ isset($medidaCorporal['created_at']) ? date('d/m/Y H:i', strtotime($medidaCorporal['created_at'])) : 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                <span>💾</span> Actualizar medición
                            </button>

                            <a href="{{ route('medidas-corporales.index') }}"
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
@endsection
