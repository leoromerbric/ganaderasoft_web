@extends('layouts.authenticated')

@section('title', 'Nuevo semen de toro')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('semen-toro.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🧪 Nuevo semen de toro</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos del semen</h3>
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
                    <select name="animal_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('animal_id') border-red-500 @enderror">
                        <option value="">Seleccione un toro</option>
                        @foreach($toros as $toro)
                            @php $tId = $toro['id'] ?? $toro['id_Animal'] ?? ''; @endphp
                            <option value="{{ $tId }}" {{ old('animal_id') == $tId ? 'selected' : '' }}>
                                {{ $toro['nombre'] ?? $toro['Nombre'] ?? 'Animal #'.$tId }}
                            </option>
                        @endforeach
                    </select>
                    @error('animal_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('estado') border-red-500 @enderror">
                        <option value="">Seleccione estado</option>
                        <option value="1" {{ old('estado') === '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado') === '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('fecha') border-red-500 @enderror">
                    @error('fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('semen-toro.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
