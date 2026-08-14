@extends('layouts.authenticated')

@section('title', 'Registrar Personal de Finca')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header & Breadcrumb -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Registrar Personal de Finca</h1>
                <p class="text-sm text-gray-500 mt-1">Complete la información del trabajador asignado a la finca</p>
            </div>
            <a href="{{ route('personal-finca.index') }}"
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
        <form method="POST" action="{{ route('personal-finca.store') }}"
            class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-6">
            @csrf

            <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-xl">
                    👥
                </div>
                <div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro">Datos del Personal</h3>
                    <p class="text-xs text-gray-500">Asignación de rol y datos personales</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Selección de Finca -->
                <div class="md:col-span-2">
                    <label for="finca_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Finca Destino <span class="text-red-500">*</span>
                    </label>
                    <select name="finca_id" id="finca_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione una finca...</option>
                        @foreach($fincas as $finca)
                            @php
                                $fId = $finca['id'] ?? null;
                                $fNombre = $finca['nombre'] ?? ('Finca #' . $fId);
                                $selected = old('finca_id') == $fId || session('selected_finca')['id'] ?? null == $fId;
                            @endphp
                            <option value="{{ $fId }}" {{ $selected ? 'selected' : '' }}>
                                {{ $fNombre }} (ID: #{{ $fId }})
                            </option>
                        @endforeach
                    </select>
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
                        Cargo / Tipo de Trabajador <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_trabajador" id="tipo_trabajador" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione tipo...</option>
                        <option value="Técnico" {{ old('tipo_trabajador') == 'Técnico' ? 'selected' : '' }}>Técnico</option>
                        <option value="Veterinario" {{ old('tipo_trabajador') == 'Veterinario' ? 'selected' : '' }}>
                            Veterinario</option>
                        <option value="Operario" {{ old('tipo_trabajador') == 'Operario' ? 'selected' : '' }}>Operario
                        </option>
                        <option value="Vigilante" {{ old('tipo_trabajador') == 'Vigilante' ? 'selected' : '' }}>Vigilante
                        </option>
                        <option value="Supervisor" {{ old('tipo_trabajador') == 'Supervisor' ? 'selected' : '' }}>Supervisor
                        </option>
                        <option value="Administrador" {{ old('tipo_trabajador') == 'Administrador' ? 'selected' : '' }}>
                            Administrador</option>
                    </select>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" id="nombre" required value="{{ old('nombre') }}" placeholder="Ej: Juan"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Apellido -->
                <div>
                    <label for="apellido" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Apellido <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="apellido" id="apellido" required value="{{ old('apellido') }}"
                        placeholder="Ej: Pérez"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Telefono -->
                <div>
                    <label for="telefono" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="telefono" id="telefono" required value="{{ old('telefono') }}"
                        placeholder="Ej: 04121234567"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Correo -->
                <div>
                    <label for="correo" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="correo" id="correo" required value="{{ old('correo') }}"
                        placeholder="Ej: juan.perez@email.com"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                <a href="{{ route('personal-finca.index') }}"
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