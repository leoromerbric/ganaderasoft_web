@extends('layouts.authenticated')

@section('title', 'Editar Rebaño')

@section('content')
<div>
    <div class="mb-8 flex items-center">
        <a href="{{ route('rebanos.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Editar Rebaño</h2>
            <p class="text-gray-600 mt-1">Actualice la información del rebaño</p>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
            <p class="font-medium mb-2">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md p-8">
        <form method="POST" action="{{ route('rebanos.update', $rebano['id_Rebano']) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ID (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ID del Rebaño</label>
                    <input type="text" value="{{ $rebano['id_Rebano'] }}" disabled
                           class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-lg text-gray-600">
                </div>

                <!-- Finca (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Finca</label>
                    <input type="text" value="{{ $rebano['finca']['Nombre'] ?? 'N/A' }}" disabled
                           class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-lg text-gray-600">
                </div>
            </div>

            <!-- Nombre -->
            <div>
                <label for="Nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Rebaño <span class="text-red-500">*</span>
                </label>
                <input type="text" name="Nombre" id="Nombre"
                       value="{{ old('Nombre', $rebano['Nombre']) }}"
                       required
                       placeholder="Ej: Rebaño Norte, Rebaño Principal"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste @error('Nombre') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Ingrese un nombre descriptivo para el rebaño</p>
                @error('Nombre')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Animales en el rebaño -->
            @if(isset($rebano['animales']) && count($rebano['animales']) > 0)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">
                        🐄 Animales en este rebaño: {{ count($rebano['animales']) }}
                    </p>
                    <div class="text-sm text-ganaderasoft-azul space-y-1">
                        @foreach(array_slice($rebano['animales'], 0, 5) as $animal)
                            <div>• {{ $animal['Nombre'] }} ({{ $animal['codigo_animal'] }})</div>
                        @endforeach
                        @if(count($rebano['animales']) > 5)
                            <div class="text-gray-500 mt-1">+ {{ count($rebano['animales']) - 5 }} más</div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('rebanos.index') }}"
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancelar</a>
                <button type="submit"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
