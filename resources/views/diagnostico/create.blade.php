@extends('layouts.authenticated')

@section('title', 'Nuevo Diagnóstico')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('diagnostico.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🏥 Nuevo Diagnóstico</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos del Diagnóstico</h3>
        </div>
        <form action="{{ route('diagnostico.store') }}" method="POST" class="p-6">
            @csrf
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Animal <span class="text-red-500">*</span></label>
                    <select name="fk_etapa_animal_anid" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('fk_etapa_animal_anid') border-red-500 @enderror">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ old('fk_etapa_animal_anid') == $animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] ?? 'Animal #'.$animal['id_Animal'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('fk_etapa_animal_anid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Diagnóstico</label>
                    <input type="text" name="diagnostico_tipo" value="{{ old('diagnostico_tipo') }}" maxlength="30"
                           placeholder="Tipo de diagnóstico..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('diagnostico_tipo') border-red-500 @enderror">
                    @error('diagnostico_tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha del Diagnóstico</label>
                    <input type="date" name="diagnostico_fecha" value="{{ old('diagnostico_fecha', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('diagnostico_fecha') border-red-500 @enderror">
                    @error('diagnostico_fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Etapa Animal <span class="text-red-500">*</span></label>
                    <input type="number" name="fk_etapa_animal_etid" required
                           value="{{ old('fk_etapa_animal_etid') }}" placeholder="ID de etapa activa del animal"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('fk_etapa_animal_etid') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Ingrese el ID de la etapa activa del animal seleccionado</p>
                    @error('fk_etapa_animal_etid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="diagnostico_descripcion" rows="4"
                              placeholder="Descripción del diagnóstico..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('diagnostico_descripcion') border-red-500 @enderror">{{ old('diagnostico_descripcion') }}</textarea>
                    @error('diagnostico_descripcion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('diagnostico.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                    💾 Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
