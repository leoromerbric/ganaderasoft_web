@extends('layouts.authenticated')

@section('title', 'Crear Personal')

@section('content')
    @php
        $fincaNombre = $selectedFinca['nombre'] ?? 'Finca';
        $fincaId = $selectedFinca['id'] ?? null;
    @endphp
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header & Breadcrumb -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Registrar Nuevo Personal</h1>
                <p class="text-sm text-gray-500 mt-1">Complete la información del trabajador de finca</p>
            </div>
            <a href="{{ route('personal.index') }}"
                class="inline-flex items-center text-sm text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-medium transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Personal
            </a>
        </div>

        <!-- Error Alert -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm" role="alert">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">⚠️</span>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Form Container -->
        <form method="POST" action="{{ route('personal.store') }}"
            class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-6">
            @csrf

            <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-xl">
                    👤
                </div>
                <div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro">Datos del Empleado</h3>
                    <p class="text-xs text-gray-500">Asignación a finca activa</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Finca Read-Only -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Finca
                        Asignada</label>
                    <div
                        class="flex items-center px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 space-x-2">
                        <span>🏡</span>
                        <span>{{ $fincaNombre }}</span>
                        <span class="text-xs text-gray-400">(ID: #{{ $fincaId }})</span>
                    </div>
                </div>

                <!-- Cedula -->
                <div>
                    <label for="cedula" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Cédula / Identificación <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cedula" id="cedula" required value="{{ old('cedula') }}"
                        placeholder="Ej: V12345678"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Tipo de Trabajador -->
                <div>
                    <label for="tipo_trabajador"
                        class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Tipo de Trabajador <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_trabajador" id="tipo_trabajador" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione cargo...</option>
                        @foreach($tiposTrabajador as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_trabajador') == $tipo ? 'selected' : '' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" id="nombre" required value="{{ old('nombre') }}"
                        placeholder="Ej: Carlos"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Apellido -->
                <div>
                    <label for="apellido" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Apellido <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="apellido" id="apellido" required value="{{ old('apellido') }}"
                        placeholder="Ej: Rodríguez"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Telefono -->
                <div>
                    <label for="telefono" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Teléfono de Contacto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="telefono" id="telefono" required value="{{ old('telefono') }}"
                        placeholder="Ej: 04141234567"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Correo -->
                <div>
                    <label for="correo" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="correo" id="correo" required value="{{ old('correo') }}"
                        placeholder="Ej: carlos.rodriguez@email.com"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                <a href="{{ route('personal.index') }}"
                    class="px-6 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-ganaderasoft-verde-oscuro text-white text-sm font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    Guardar Personal
                </button>
            </div>
        </form>
    </div>
@endsection