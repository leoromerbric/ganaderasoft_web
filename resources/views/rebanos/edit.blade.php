@extends('layouts.finca')

@section('title', 'Editar Rebaño')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('rebanos.index') }}" class="hover:text-ganaderasoft-celeste">Rebaños</a>
                <span>/</span>
                <span class="text-ganaderasoft-negro font-medium">Editar Rebaño</span>
            </div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Editar Rebaño</h2>
            <p class="text-gray-600 mt-1">Actualice la información del rebaño</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Edit Form -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro">Información del Rebaño</h3>
            </div>

            <form method="POST" action="{{ route('rebanos.update', $rebano['id_Rebano']) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- ID (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ID del Rebaño
                        </label>
                        <input 
                            type="text" 
                            value="{{ $rebano['id_Rebano'] }}"
                            disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                        >
                    </div>

                    <!-- Finca Info (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Finca
                        </label>
                        <input 
                            type="text" 
                            value="{{ $rebano['finca']['Nombre'] ?? 'N/A' }}"
                            disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                        >
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="Nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Rebaño <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="Nombre" 
                            id="Nombre" 
                            value="{{ old('Nombre', $rebano['Nombre']) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste"
                            placeholder="Ej: Rebaño Norte, Rebaño Principal"
                        >
                        <p class="text-xs text-gray-500 mt-1">Ingrese un nombre descriptivo para el rebaño</p>
                    </div>

                    <!-- Additional Info -->
                    @if(isset($rebano['animales']) && count($rebano['animales']) > 0)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-blue-900 mb-2">
                                🐄 Animales en este rebaño: {{ count($rebano['animales']) }}
                            </p>
                            <div class="text-xs text-blue-700 space-y-1">
                                @foreach(array_slice($rebano['animales'], 0, 5) as $animal)
                                    <div>• {{ $animal['Nombre'] }} ({{ $animal['codigo_animal'] }})</div>
                                @endforeach
                                @if(count($rebano['animales']) > 5)
                                    <div class="text-blue-600 mt-1">+ {{ count($rebano['animales']) - 5 }} más</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a 
                        href="{{ route('rebanos.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 font-medium">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
