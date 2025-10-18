@extends('layouts.finca')

@section('title', 'Crear Rebaño')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('rebanos.index') }}" class="hover:text-ganaderasoft-celeste">Rebaños</a>
                <span>/</span>
                <span class="text-ganaderasoft-negro font-medium">Crear Rebaño</span>
            </div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Crear Nuevo Rebaño</h2>
            <p class="text-gray-600 mt-1">Complete la información del nuevo rebaño</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Create Form -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro">Información del Rebaño</h3>
            </div>

            <form method="POST" action="{{ route('rebanos.store') }}" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Finca Info (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Finca
                        </label>
                        <input 
                            type="text" 
                            value="{{ $selectedFinca['Nombre'] }}"
                            disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                        >
                        <p class="text-xs text-gray-500 mt-1">El rebaño será creado en esta finca</p>
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
                            value="{{ old('Nombre') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste"
                            placeholder="Ej: Rebaño Norte, Rebaño Principal"
                        >
                        <p class="text-xs text-gray-500 mt-1">Ingrese un nombre descriptivo para el rebaño</p>
                    </div>
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
                        class="bg-ganaderasoft-verde hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                        <span>💾</span>
                        <span>Guardar Rebaño</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
