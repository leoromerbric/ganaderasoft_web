@extends('layouts.authenticated')

@section('title', 'Editar Reproducción Animal')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('reproduccion-animal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🔬 Editar Reproducción #{{ $reproduccion['repro_id'] }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Modificar Datos</h3>
        </div>
        <form action="{{ route('reproduccion-animal.update', $reproduccion['repro_id']) }}" method="POST" class="p-6">
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
                           value="{{ $reproduccion['animal']['Nombre'] ?? ('Animal #'.($reproduccion['repro_etapa_anid'] ?? '')) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etapa actual</label>
                    <input type="text" readonly
                           value="{{ $reproduccion['etapa']['Nombre'] ?? $reproduccion['etapa']['descripcion'] ?? ('Etapa #'.($reproduccion['repro_etapa_etid'] ?? '')) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Reproducción</label>
                    <select name="repro_tipo_reproduccion"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('repro_tipo_reproduccion') border-red-500 @enderror">
                        <option value="">Seleccione un tipo</option>
                        <option value="Natural" {{ old('repro_tipo_reproduccion', $reproduccion['repro_tipo_reproduccion'] ?? '') == 'Natural' ? 'selected' : '' }}>Natural</option>
                        <option value="IA" {{ old('repro_tipo_reproduccion', $reproduccion['repro_tipo_reproduccion'] ?? '') == 'IA' ? 'selected' : '' }}>IA</option>
                    </select>
                    @error('repro_tipo_reproduccion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Reproducción <span class="text-red-500">*</span></label>
                    <input type="date" name="repro_fecha_reproduccion" required
                           value="{{ old('repro_fecha_reproduccion', $reproduccion['repro_fecha_reproduccion'] ?? date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('repro_fecha_reproduccion') border-red-500 @enderror">
                    @error('repro_fecha_reproduccion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                    <input type="text" name="repro_observacion" maxlength="60"
                           value="{{ old('repro_observacion', $reproduccion['repro_observacion'] ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('reproduccion-animal.index') }}"
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
