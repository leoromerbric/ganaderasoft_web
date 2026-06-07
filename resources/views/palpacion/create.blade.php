@extends('layouts.authenticated')

@section('title', 'Nueva Palpación')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('palpacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🩺 Nueva Palpación</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos de la Palpación</h3>
        </div>
        <form action="{{ route('palpacion.store') }}" method="POST" class="p-6">
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
                    <select name="palpacion_etapa_anid" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('palpacion_etapa_anid') border-red-500 @enderror">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ old('palpacion_etapa_anid') == $animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] ?? 'Animal #'.$animal['id_Animal'] }}
                                @if(isset($animal['Sexo'])) ({{ $animal['Sexo'] === 'F' ? 'Hembra' : 'Macho' }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('palpacion_etapa_anid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Etapa Animal <span class="text-red-500">*</span></label>
                    <input type="number" name="palpacion_etapa_etid" required
                           value="{{ old('palpacion_etapa_etid') }}" placeholder="ID de etapa activa del animal"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('palpacion_etapa_etid') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Ingrese el ID de la etapa activa del animal</p>
                    @error('palpacion_etapa_etid')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Técnico</label>
                    <select name="id_Tecnico"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('id_Tecnico') border-red-500 @enderror">
                        <option value="">-- Sin técnico --</option>
                        @foreach($personal as $persona)
                            <option value="{{ $persona['id_Personal'] }}" {{ old('id_Tecnico') == $persona['id_Personal'] ? 'selected' : '' }}>
                                {{ $persona['Nombre'] ?? 'Personal #'.$persona['id_Personal'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_Tecnico')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Palpación</label>
                    <select name="palpacion_tipo"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('palpacion_tipo') border-red-500 @enderror">
                        <option value="">Seleccione un tipo</option>
                        <option value="Preñez" {{ old('palpacion_tipo') == 'Preñez' ? 'selected' : '' }}>Preñez</option>
                        <option value="Revision" {{ old('palpacion_tipo') == 'Revision' ? 'selected' : '' }}>Revisión</option>
                    </select>
                    @error('palpacion_tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Palpación</label>
                    <input type="date" name="palpacion_fecha" value="{{ old('palpacion_fecha', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('palpacion_fecha') border-red-500 @enderror">
                    @error('palpacion_fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('palpacion.index') }}"
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
