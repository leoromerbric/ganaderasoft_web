@extends('layouts.authenticated')

@section('title', 'Editar Personal de Finca')

@section('content')
    @php
        $pId = $personalFinca['id'] ?? null;
        $personaSub = $personalFinca['persona'] ?? null;
        $nombreEmp = $personaSub['nombre'] ?? '';
        $apellidoEmp = $personaSub['apellido'] ?? '';
        $cedulaEmp = $personaSub['cedula'] ?? '';
        $telefonoEmp = $personaSub['telefono'] ?? '';
        $correoEmp = $personaSub['correo'] ?? '';

        $tipoObj = $personalFinca['tipo_trabajador'] ?? null;
        $currTipoId = $personalFinca['tipo_trabajador_id'] ?? ($tipoObj['id'] ?? null);
        $currFincaId = $personalFinca['finca_id'] ?? null;
    @endphp
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header & Breadcrumb -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Editar Personal de Finca</h1>
                <p class="text-sm text-gray-500 mt-1">Actualice los datos del trabajador #{{ $pId }}</p>
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
        <form method="POST" action="{{ route('personal-finca.update', $pId) }}"
            class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-6">
            @csrf
            @method('PUT')

            <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-xl">
                    👥
                </div>
                <div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro">Actualizar Ficha de Personal</h3>
                    <p class="text-xs text-gray-500">ID del recurso: #{{ $pId }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Selección de Finca -->
                <div class="md:col-span-2">
                    <label for="finca_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Finca Asignada <span class="text-red-500">*</span>
                    </label>
                    <select name="finca_id" id="finca_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione una finca...</option>
                        @foreach($fincas as $finca)
                            @php
                                $fId = $finca['id'] ?? null;
                                $fNombre = $finca['nombre'] ?? ('Finca #' . $fId);
                            @endphp
                            <option value="{{ $fId }}" {{ old('finca_id', $currFincaId) == $fId ? 'selected' : '' }}>
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
                    <input type="text" name="cedula" id="cedula" required value="{{ old('cedula', $cedulaEmp) }}"
                        placeholder="Ej: V12345678"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Tipo de Trabajador -->
                <div>
                    <label for="tipo_trabajador_id"
                        class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Cargo / Tipo de Trabajador <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_trabajador_id" id="tipo_trabajador_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione tipo...</option>
                        @foreach($tiposTrabajador as $t)
                            @php
                                $tId = $t['id'] ?? null;
                                $tNombre = $t['nombre'] ?? '';
                            @endphp
                            <option value="{{ $tId }}" {{ (string) old('tipo_trabajador_id', $currTipoId) === (string) $tId ? 'selected' : '' }}>
                                {{ $tNombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" id="nombre" required value="{{ old('nombre', $nombreEmp) }}"
                        placeholder="Ej: Juan"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Apellido -->
                <div>
                    <label for="apellido" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Apellido <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="apellido" id="apellido" required value="{{ old('apellido', $apellidoEmp) }}"
                        placeholder="Ej: Pérez"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Telefono -->
                <div>
                    <label for="telefono" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="telefono" id="telefono" required value="{{ old('telefono', $telefonoEmp) }}"
                        placeholder="Ej: 04121234567"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>

                <!-- Correo -->
                <div>
                    <label for="correo" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="correo" id="correo" required value="{{ old('correo', $correoEmp) }}"
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
                    Actualizar Personal
                </button>
            </div>
        </form>
    </div>
@endsection