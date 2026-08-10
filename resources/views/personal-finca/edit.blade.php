@extends('layouts.authenticated')

@section('title', 'Editar Personal de Finca')

@section('content')
@php
    $pId = $personalFinca['id'] ?? $personalFinca['id_Tecnico'] ?? null;
    $personaSub = $personalFinca['persona'] ?? null;
    $nombreEmp = $personaSub['nombre'] ?? $personalFinca['Nombre'] ?? '';
    $apellidoEmp = $personaSub['apellido'] ?? $personalFinca['Apellido'] ?? '';
    $cedulaEmp = $personaSub['cedula'] ?? $personalFinca['Cedula'] ?? '';
    $telefonoEmp = $personaSub['telefono'] ?? $personalFinca['Telefono'] ?? '';
    $correoEmp = $personaSub['correo'] ?? $personalFinca['Correo'] ?? '';

    $tipoObj = $personalFinca['tipo_trabajador'] ?? null;
    $tipoNombre = $tipoObj['nombre'] ?? $personalFinca['Tipo_Trabajador'] ?? '';
    $currFincaId = $personalFinca['finca_id'] ?? $personalFinca['id_Finca'] ?? null;
@endphp
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header & Breadcrumb -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Editar Personal de Finca</h1>
            <p class="text-sm text-gray-500 mt-1">Actualice los datos del trabajador #{{ $pId }} (API V2)</p>
        </div>
        <a href="{{ route('personal-finca.index') }}" class="inline-flex items-center text-sm text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-medium transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
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
    <form method="POST" action="{{ route('personal-finca.update', $pId) }}" class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-6">
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
                <label for="id_Finca" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Finca Asignada <span class="text-red-500">*</span>
                </label>
                <select name="id_Finca" id="id_Finca" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Seleccione una finca...</option>
                    @foreach($fincas as $finca)
                        @php
                            $fId = $finca['id'] ?? $finca['id_Finca'] ?? null;
                            $fNombre = $finca['nombre'] ?? $finca['Nombre'] ?? ('Finca #'.$fId);
                        @endphp
                        <option value="{{ $fId }}" {{ old('id_Finca', $currFincaId) == $fId ? 'selected' : '' }}>
                            {{ $fNombre }} (ID: #{{ $fId }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cedula -->
            <div>
                <label for="Cedula" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Cédula / Identificación <span class="text-red-500">*</span>
                </label>
                <input type="text" name="Cedula" id="Cedula" required
                       value="{{ old('Cedula', $cedulaEmp) }}"
                       placeholder="Ej: V12345678"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Tipo de Trabajador -->
            <div>
                <label for="Tipo_Trabajador" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Cargo / Tipo de Trabajador <span class="text-red-500">*</span>
                </label>
                <select name="Tipo_Trabajador" id="Tipo_Trabajador" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Seleccione tipo...</option>
                    @foreach(['Técnico', 'Veterinario', 'Operario', 'Vigilante', 'Supervisor', 'Administrador'] as $t)
                        <option value="{{ $t }}" {{ old('Tipo_Trabajador', $tipoNombre) == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nombre -->
            <div>
                <label for="Nombre" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="Nombre" id="Nombre" required
                       value="{{ old('Nombre', $nombreEmp) }}"
                       placeholder="Ej: Juan"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Apellido -->
            <div>
                <label for="Apellido" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Apellido <span class="text-red-500">*</span>
                </label>
                <input type="text" name="Apellido" id="Apellido" required
                       value="{{ old('Apellido', $apellidoEmp) }}"
                       placeholder="Ej: Pérez"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Telefono -->
            <div>
                <label for="Telefono" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Teléfono <span class="text-red-500">*</span>
                </label>
                <input type="text" name="Telefono" id="Telefono" required
                       value="{{ old('Telefono', $telefonoEmp) }}"
                       placeholder="Ej: 04121234567"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>

            <!-- Correo -->
            <div>
                <label for="Correo" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Correo Electrónico <span class="text-red-500">*</span>
                </label>
                <input type="email" name="Correo" id="Correo" required
                       value="{{ old('Correo', $correoEmp) }}"
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
                    class="px-8 py-3 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul text-white text-sm font-semibold rounded-xl hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste transition-all duration-200 shadow-md">
                Actualizar Personal
            </button>
        </div>
    </form>
</div>
@endsection