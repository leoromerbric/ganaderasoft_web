@extends('layouts.authenticated')

@section('title', 'Editar reproducción animal')

@section('content')
@php
    $id = $reproduccion['id'] ?? $reproduccion['repro_id'] ?? null;
    $animalId = $reproduccion['animal_id'] ?? $reproduccion['repro_etapa_anid'] ?? data_get($reproduccion, 'etapa_animal.animal_id');
    $animalNombre = data_get($reproduccion, 'animal.Nombre') ?? ('Animal #'.$animalId);
    $etapaId = $reproduccion['etapa_id'] ?? $reproduccion['repro_etapa_etid'] ?? data_get($reproduccion, 'etapa_animal.etapa_id');
    $etapaNombre = data_get($reproduccion, 'etapa_animal.etapa.nombre') ?? data_get($reproduccion, 'etapa_animal.etapa.etapa_nombre') ?? data_get($reproduccion, 'etapa.nombre') ?? data_get($reproduccion, 'etapa.etapa_nombre') ?? ('Etapa #'.$etapaId);
    $tipo = $reproduccion['tipo'] ?? $reproduccion['repro_tipo_reproduccion'] ?? '';
    $fechaRaw = old('fecha', $reproduccion['fecha'] ?? $reproduccion['repro_fecha_reproduccion'] ?? null);
    $fechaValue = '';
    if (!empty($fechaRaw)) {
        try {
            $fechaValue = \Carbon\Carbon::parse($fechaRaw)->format('Y-m-d');
        } catch (\Exception $e) {
            $fechaValue = '';
        }
    }
    $observacion = $reproduccion['observacion'] ?? $reproduccion['repro_observacion'] ?? '';
@endphp
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('reproduccion-animal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🔬 Editar reproducción #{{ $id }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Modificar datos</h3>
        </div>
        <form action="{{ route('reproduccion-animal.update', $id) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Animal</label>
                    <input type="text" readonly
                           value="{{ $animalNombre }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etapa actual</label>
                    <input type="text" readonly
                           value="{{ $etapaNombre }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de reproducción</label>
                    <select name="tipo"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('tipo') border-red-500 @enderror">
                        <option value="">Seleccione un tipo</option>
                        <option value="Natural" {{ old('tipo', $tipo) == 'Natural' ? 'selected' : '' }}>Natural</option>
                        <option value="IA" {{ old('tipo', $tipo) == 'IA' ? 'selected' : '' }}>Ia</option>
                    </select>
                    @error('tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de reproducción <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha" required
                           value="{{ $fechaValue }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('fecha') border-red-500 @enderror">
                    @error('fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                    <input type="text" name="observacion" maxlength="60"
                           value="{{ old('observacion', $observacion) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('reproduccion-animal.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
