@extends('layouts.finca')

@section('title', 'Crear Personal')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('personal.index') }}" class="hover:text-ganaderasoft-celeste">Personal</a>
                <span>/</span>
                <span class="text-ganaderasoft-negro font-medium">Crear Personal</span>
            </div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Crear Nuevo Personal</h2>
            <p class="text-gray-600 mt-1">Complete la información del nuevo empleado</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Create Form -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro">Información del Personal</h3>
            </div>

            <form method="POST" action="{{ route('personal.store') }}" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Finca Info (Read-only) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Finca
                        </label>
                        <input 
                            type="text" 
                            value="{{ $selectedFinca['Nombre'] }}"
                            disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                        >
                        <p class="text-xs text-gray-500 mt-1">El personal será asignado a esta finca</p>
                    </div>

                    <!-- Cedula -->
                    <div>
                        <label for="Cedula" class="block text-sm font-medium text-gray-700 mb-2">
                            Cédula <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="Cedula" 
                            id="Cedula" 
                            value="{{ old('Cedula') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste"
                            placeholder="Ej: 12345678"
                        >
                    </div>

                    <!-- Tipo de Trabajador -->
                    <div>
                        <label for="Tipo_Trabajador" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Trabajador <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="Tipo_Trabajador" 
                            id="Tipo_Trabajador" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                            <option value="">Seleccione tipo...</option>
                            @foreach($tiposTrabajador as $tipo)
                                <option value="{{ $tipo }}" {{ old('Tipo_Trabajador') == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="Nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="Nombre" 
                            id="Nombre" 
                            value="{{ old('Nombre') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste"
                            placeholder="Ej: Juan"
                        >
                    </div>

                    <!-- Apellido -->
                    <div>
                        <label for="Apellido" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="Apellido" 
                            id="Apellido" 
                            value="{{ old('Apellido') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste"
                            placeholder="Ej: Pérez"
                        >
                    </div>

                    <!-- Telefono -->
                    <div>
                        <label for="Telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="Telefono" 
                            id="Telefono" 
                            value="{{ old('Telefono') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste"
                            placeholder="Ej: 3001234567"
                        >
                    </div>

                    <!-- Correo -->
                    <div>
                        <label for="Correo" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="Correo" 
                            id="Correo" 
                            value="{{ old('Correo') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste"
                            placeholder="Ej: juan.perez@email.com"
                        >
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a 
                        href="{{ route('personal.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 font-medium">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="bg-ganaderasoft-verde hover:bg-opacity-90 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                        <span>💾</span>
                        <span>Nuevo</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
