@extends('layouts.authenticated')

@section('title', 'Editar peso corporal')

@section('content')
@php
    $pesoId = $pesoCorporal['id'] ?? null;
    $animalId = data_get($pesoCorporal, 'animal.id') ?? ($pesoCorporal['animal_id'] ?? '');
    $animalNombre = data_get($pesoCorporal, 'animal.nombre') ?? ('Animal #'.($animalId ?: 'N/A'));
    $animalCodigo = data_get($pesoCorporal, 'animal.codigo_animal') ?? '';
    $valFecha = old('fecha_peso') ?: (isset($pesoCorporal['fecha_peso']) ? substr($pesoCorporal['fecha_peso'], 0, 10) : date('Y-m-d'));
    $valPeso = old('peso', $pesoCorporal['peso'] ?? '');
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
                    Editar pesaje #{{ $pesoId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Modifica la fecha, el valor del peso o las observaciones registradas</p>
            </div>
        </div>
        <div>
            <a href="{{ route('peso-corporal.index') }}" 
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
    <form action="{{ route('peso-corporal.update', $pesoId) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="animal_id" value="{{ old('animal_id', $animalId) }}">
        <input type="hidden" name="etapa_id" value="{{ old('etapa_id', $pesoCorporal['etapa_id'] ?? '') }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Campos del Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Animal del Registro (Solo Lectura) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>🐄</span> Animal asociado
                        </h3>
                        <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-600 rounded-full border border-gray-200">
                            🔒 Animal inmutable
                        </span>
                    </div>

                    <div class="p-5 bg-emerald-50/60 border border-emerald-100 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-white border border-emerald-200 text-emerald-700 font-bold flex items-center justify-center text-2xl shadow-xs">
                                🐄
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-900">{{ $animalNombre }}</p>
                                @if($animalCodigo)
                                    <p class="text-xs text-gray-500 font-mono">Código: #{{ $animalCodigo }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Campos Modificables -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>⚖️</span> Modificar datos del pesaje
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de pesaje <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_peso" required value="{{ $valFecha }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all @error('fecha_peso') border-red-500 @enderror">
                            @error('fecha_peso')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Peso registrado (kg) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="peso" required step="0.01" min="0.01" max="9999"
                                       value="{{ $valPeso }}" placeholder="Ej: 350.50"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all pr-12 font-bold text-gray-900 @error('peso') border-red-500 @enderror">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">Kg</span>
                            </div>
                            @error('peso')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Observaciones o comentarios</label>
                            <textarea name="comentario" rows="3" maxlength="255"
                                      placeholder="Agregue notas o comentarios sobre el pesaje..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('comentario', $pesoCorporal['comentario'] ?? '') }}</textarea>
                            @error('comentario')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Panel de Guardado (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>⚙️</span> Actualizar registro
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="text-xs text-gray-500 space-y-2 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>ID pesaje:</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $pesoId }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fecha registro:</span>
                                <span class="font-semibold text-gray-800">{{ isset($pesoCorporal['created_at']) ? date('d/m/Y H:i', strtotime($pesoCorporal['created_at'])) : 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Actualizar pesaje
                            </button>

                            <a href="{{ route('peso-corporal.index') }}"
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
