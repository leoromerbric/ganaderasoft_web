@extends('layouts.authenticated')

@section('title', 'Nuevo Semen de Toro')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('semen-toro.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🧪 Nuevo Semen de Toro</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos del Semen</h3>
        </div>
        <form action="{{ route('semen-toro.store') }}" method="POST" class="p-6">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Toro <span class="text-red-500">*</span></label>
                    <select name="id_Toro" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('id_Toro') border-red-500 @enderror">
                        <option value="">Seleccione un toro</option>
                        @foreach($toros as $toro)
                            <option value="{{ $toro['id_Animal'] }}" {{ old('id_Toro') == $toro['id_Animal'] ? 'selected' : '' }}>
                                {{ $toro['Nombre'] ?? 'Animal #'.$toro['id_Animal'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_Toro')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="semen_estado"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('semen_estado') border-red-500 @enderror">
                        <option value="">Seleccione estado</option>
                        <option value="1" {{ old('semen_estado') === '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('semen_estado') === '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('semen_estado')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" name="semen_fecha" value="{{ old('semen_fecha', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('semen_fecha') border-red-500 @enderror">
                    @error('semen_fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('semen-toro.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    💾 Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
