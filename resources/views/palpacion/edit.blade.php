@extends('layouts.authenticated')

@section('title', 'Editar Palpación')

@section('content')
@php
    $id = $palpacion['id'] ?? $palpacion['palpacion_id'] ?? null;
    $animalId = $palpacion['animal_id'] ?? $palpacion['palpacion_etapa_anid'] ?? data_get($palpacion, 'etapa_animal.animal_id');
    $animalNombre = data_get($palpacion, 'animal.Nombre') ?? ('Animal #'.$animalId);
    $etapaId = $palpacion['etapa_id'] ?? $palpacion['palpacion_etapa_etid'] ?? data_get($palpacion, 'etapa_animal.etapa_id');
    $etapaNombre = data_get($palpacion, 'etapa_animal.etapa.nombre') ?? data_get($palpacion, 'etapa_animal.etapa.etapa_nombre') ?? data_get($palpacion, 'etapa.nombre') ?? data_get($palpacion, 'etapa.etapa_nombre') ?? ('Etapa #'.$etapaId);
    $tecnicoId = $palpacion['tecnico_id'] ?? $palpacion['id_Tecnico'] ?? null;
    $tipo = $palpacion['tipo'] ?? $palpacion['palpacion_tipo'] ?? '';
    $fechaRaw = old('fecha', $palpacion['fecha'] ?? $palpacion['palpacion_fecha'] ?? null);
    $fechaValue = '';
    if (!empty($fechaRaw)) {
        try {
            $fechaValue = \Carbon\Carbon::parse($fechaRaw)->format('Y-m-d');
        } catch (\Exception $e) {
            $fechaValue = '';
        }
    }
    $borderClass = fn (string $field) => $errors->has($field) ? 'border-red-500' : 'border-gray-300';
@endphp
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('palpacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🩺 Editar Palpación #{{ $id }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Modificar Datos</h3>
        </div>
        <form action="{{ route('palpacion.update', $id) }}" method="POST" class="p-6">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Técnico</label>
                    <select name="tecnico_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('tecnico_id') }}">
                        <option value="">-- Sin técnico --</option>
                        @foreach($personal as $persona)
                            @php
                                $personalId = data_get($persona, 'id') ?? data_get($persona, 'id_Tecnico') ?? data_get($persona, 'id_Personal') ?? data_get($persona, 'personal.id') ?? data_get($persona, 'personal.id_Tecnico') ?? data_get($persona, 'personal.id_Personal');
                                $personalNombre = trim((data_get($persona, 'Nombre') ?? data_get($persona, 'personal.Nombre') ?? '') . ' ' . (data_get($persona, 'Apellido') ?? data_get($persona, 'personal.Apellido') ?? ''));
                            @endphp
                            @continue(!$personalId)
                            <option value="{{ $personalId }}" {{ old('tecnico_id', $tecnicoId) == $personalId ? 'selected' : '' }}>
                                {{ $personalNombre !== '' ? $personalNombre : 'Personal #'.$personalId }}
                            </option>
                        @endforeach
                    </select>
                    @error('tecnico_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Palpación</label>
                    <select name="tipo"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('tipo') }}">
                        <option value="">Seleccione un tipo</option>
                        <option value="Preñez" {{ old('tipo', $tipo) == 'Preñez' ? 'selected' : '' }}>Preñez</option>
                        <option value="Revision" {{ old('tipo', $tipo) == 'Revision' ? 'selected' : '' }}>Revisión</option>
                    </select>
                    @error('tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Palpación</label>
                    <input type="date" name="fecha"
                           value="{{ $fechaValue }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('fecha') }}">
                    @error('fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('palpacion.index') }}"
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
