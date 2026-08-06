@extends('layouts.authenticated')

@section('title', 'Editar Vacuna')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('vacuna.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">💉 Editar Vacuna #{{ $vacuna['id'] ?? $vacuna['vacuna_id'] }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Modificar Datos</h3>
        </div>
        <form action="{{ route('vacuna.update', $vacuna['id'] ?? $vacuna['vacuna_id']) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Vacuna</label>
                    <input type="text" name="nombre" maxlength="80"
                           value="{{ old('nombre', $vacuna['nombre'] ?? $vacuna['vacuna_nombre'] ?? '') }}"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                    @error('nombre')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">{{ old('descripcion', $vacuna['descripcion'] ?? $vacuna['vacuna_descripcion'] ?? '') }}</textarea>
                    @error('descripcion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                        <input type="hidden" name="activa" value="0">
                        <input type="checkbox" name="activa" value="1" {{ old('activa', (int)($vacuna['activa'] ?? 1)) == 1 ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-ganaderasoft-verde focus:ring-ganaderasoft-celeste">
                        Vacuna activa
                    </label>
                    @error('activa')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('vacuna.index') }}"
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
