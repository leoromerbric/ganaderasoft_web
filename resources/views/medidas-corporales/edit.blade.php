@extends('layouts.authenticated')

@section('title', 'Editar medidas corporales')

@section('content')
@php
    $medidaId = $medidaCorporal['id'] ?? null;
    $anId = old('animal_id', $medidaCorporal['animal_id'] ?? data_get($medidaCorporal, 'animal.id') ?? null);
    $animalNombre = data_get($medidaCorporal, 'animal.nombre') ?? ('Animal #'.($anId ?? 'N/A'));
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                ✏️
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar medidas corporales #{{ $medidaId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Modifica la evaluación morfométrica y dimensiones físicas</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('medidas-corporales.show', $medidaId) }}"
               class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver detalle
            </a>
            <a href="{{ route('medidas-corporales.index') }}" 
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
    <form action="{{ route('medidas-corporales.update', $medidaId) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Animal -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Animal asociado
                    </h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Animal <span class="text-red-500">*</span>
                        </label>
                        <select name="animal_id" id="animal_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('animal_id') border-red-500 @enderror">
                            @foreach($animales as $animal)
                                @php
                                    $pk = $animal['id'] ?? null;
                                    $isSelected = (string)$anId === (string)$pk;
                                @endphp
                                @if($pk)
                                    <option value="{{ $pk }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ $animal['nombre'] ?? ('Animal #'.$pk) }} ({{ $animal['codigo_animal'] ?? 'Sin código' }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('animal_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Card 2: Alturas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📏</span> Modificar alturas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Altura a la cruz (hc)
                            </label>
                            <div class="relative">
                                <input type="number" name="altura_hc" id="altura_hc" value="{{ old('altura_hc', $medidaCorporal['altura_hc'] ?? '') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 135.0"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('altura_hc') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('altura_hc')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Altura a la grupa (hg)
                            </label>
                            <div class="relative">
                                <input type="number" name="altura_hg" id="altura_hg" value="{{ old('altura_hg', $medidaCorporal['altura_hg'] ?? '') }}"
                                       step="0.1" min="0" max="300" placeholder="Ej: 138.5"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('altura_hg') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
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
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Perímetro torácico (pt)
                            </label>
                            <div class="relative">
                                <input type="number" name="perimetro_pt" id="perimetro_pt" value="{{ old('perimetro_pt', $medidaCorporal['perimetro_pt'] ?? '') }}"
                                       step="0.1" min="0" max="500" placeholder="Ej: 180.0"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('perimetro_pt') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('perimetro_pt')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Perímetro de caña (pca)
                            </label>
                            <div class="relative">
                                <input type="number" name="perimetro_pca" id="perimetro_pca" value="{{ old('perimetro_pca', $medidaCorporal['perimetro_pca'] ?? '') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 21.5"
                                       class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('perimetro_pca') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('perimetro_pca')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 4: Longitudes y Anchura -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📐</span> Modificar longitudes y anchura
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Longitud corporal (lc)
                            </label>
                            <div class="relative">
                                <input type="number" name="longitud_lc" id="longitud_lc" value="{{ old('longitud_lc', $medidaCorporal['longitud_lc'] ?? '') }}"
                                       step="0.1" min="0" max="500" placeholder="Ej: 160.0"
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('longitud_lc') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('longitud_lc')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Longitud de grupa (lg)
                            </label>
                            <div class="relative">
                                <input type="number" name="longitud_lg" id="longitud_lg" value="{{ old('longitud_lg', $medidaCorporal['longitud_lg'] ?? '') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 52.0"
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('longitud_lg') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('longitud_lg')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Anchura de grupa (ag)
                            </label>
                            <div class="relative">
                                <input type="number" name="anchura_ag" id="anchura_ag" value="{{ old('anchura_ag', $medidaCorporal['anchura_ag'] ?? '') }}"
                                       step="0.1" min="0" max="200" placeholder="Ej: 48.0"
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('anchura_ag') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                    Cm
                                </div>
                            </div>
                            @error('anchura_ag')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Panel de Guardado (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>⚙️</span> Actualizar registro
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="text-xs text-gray-500 space-y-2 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>ID registro:</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $medidaId }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Animal:</span>
                                <span class="font-bold text-gray-900">{{ $animalNombre }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Creado el:</span>
                                <span class="font-semibold text-gray-800">{{ isset($medidaCorporal['created_at']) ? date('d/m/Y H:i', strtotime($medidaCorporal['created_at'])) : 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Actualizar registro
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
