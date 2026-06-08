@extends('layouts.authenticated')

@section('title', 'Editar Palpación')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('palpacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🩺 Editar Palpación #{{ $palpacion['palpacion_id'] }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Modificar Datos</h3>
        </div>
        <form action="{{ route('palpacion.update', $palpacion['palpacion_id']) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @php
                $borderClass = fn (string $field) => $errors->has($field) ? 'border-red-500' : 'border-gray-300';
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Animal</label>
                    <input type="text" readonly
                           value="{{ $palpacion['animal']['Nombre'] ?? ('Animal #'.($palpacion['palpacion_etapa_anid'] ?? '')) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etapa actual</label>
                    <input type="text" readonly
                           value="{{ $palpacion['etapa']['Nombre'] ?? $palpacion['etapa']['descripcion'] ?? ('Etapa #'.($palpacion['palpacion_etapa_etid'] ?? '')) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Técnico</label>
                            <select name="id_Tecnico"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('id_Tecnico') }}">
                        <option value="">-- Sin técnico --</option>
                        @foreach($personal as $persona)
                            @php
                                $personalId = data_get($persona, 'id_Tecnico') ?? data_get($persona, 'id_Personal') ?? data_get($persona, 'personal.id_Tecnico') ?? data_get($persona, 'personal.id_Personal');
                                $personalNombre = trim((data_get($persona, 'Nombre') ?? data_get($persona, 'personal.Nombre') ?? '') . ' ' . (data_get($persona, 'Apellido') ?? data_get($persona, 'personal.Apellido') ?? ''));
                            @endphp
                            @continue(!$personalId)
                            <option value="{{ $personalId }}" {{ old('id_Tecnico', $palpacion['id_Tecnico'] ?? '') == $personalId ? 'selected' : '' }}>
                                {{ $personalNombre !== '' ? $personalNombre : 'Personal #'.$personalId }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_Tecnico')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Palpación</label>
                            <select name="palpacion_tipo"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('palpacion_tipo') }}">
                        <option value="">Seleccione un tipo</option>
                        <option value="Preñez" {{ old('palpacion_tipo', $palpacion['palpacion_tipo'] ?? '') == 'Preñez' ? 'selected' : '' }}>Preñez</option>
                        <option value="Revision" {{ old('palpacion_tipo', $palpacion['palpacion_tipo'] ?? '') == 'Revision' ? 'selected' : '' }}>Revisión</option>
                    </select>
                    @error('palpacion_tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Palpación</label>
                              <input type="date" name="palpacion_fecha"
                           value="{{ old('palpacion_fecha', $palpacion['palpacion_fecha'] ?? date('Y-m-d')) }}"
                                  class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('palpacion_fecha') }}">
                    @error('palpacion_fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('palpacion.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                    💾 Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
