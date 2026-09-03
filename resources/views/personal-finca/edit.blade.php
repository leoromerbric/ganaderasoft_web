@extends('layouts.authenticated')

@section('title', 'Editar personal de finca')

@section('content')
@php
    $pId = $personalFinca['id'] ?? $personalFinca['id_Tecnico'] ?? null;
    $personaSub = $personalFinca['persona'] ?? [];
    
    $nombre = $personaSub['nombre'] ?? $personalFinca['nombre'] ?? $personalFinca['Nombre'] ?? '';
    $apellido = $personaSub['apellido'] ?? $personalFinca['apellido'] ?? $personalFinca['Apellido'] ?? '';
    $nombreEmp = $nombre;
    $apellidoEmp = $apellido;
    $cedulaEmp = $personaSub['cedula'] ?? $personalFinca['cedula'] ?? $personalFinca['Cedula'] ?? '';
    $telefonoEmp = $personaSub['telefono'] ?? $personalFinca['telefono'] ?? $personalFinca['Telefono'] ?? '';
    $correoEmp = $personaSub['correo'] ?? $personaSub['email'] ?? $personalFinca['correo'] ?? $personalFinca['Correo'] ?? $personalFinca['email'] ?? $personalFinca['Email'] ?? '';
    $fechaNacimiento = $personaSub['fecha_nacimiento'] ?? $personalFinca['fecha_nacimiento'] ?? $personalFinca['Fecha_Nacimiento'] ?? '';
    if ($fechaNacimiento && is_string($fechaNacimiento)) {
        $fechaNacimiento = substr($fechaNacimiento, 0, 10);
    }
    $fechaIngreso = $personalFinca['fecha_ingreso'] ?? $personalFinca['Fecha_Ingreso'] ?? '';
    if ($fechaIngreso && is_string($fechaIngreso)) {
        $fechaIngreso = substr($fechaIngreso, 0, 10);
    }

    $tipoObj = $personalFinca['tipo_trabajador'] ?? [];
    $currTipoId = $personalFinca['tipo_trabajador_id'] ?? (is_array($tipoObj) ? ($tipoObj['id'] ?? null) : null);
    $tipoNombre = is_array($tipoObj) ? ($tipoObj['nombre'] ?? 'Trabajador') : ($personalFinca['Tipo_Trabajador'] ?? 'Trabajador');

    $currFincaId = $personalFinca['finca_id'] ?? $personalFinca['id_Finca'] ?? null;
    $fincaObj = $personalFinca['finca'] ?? [];
    $fincaNombre = is_array($fincaObj) ? ($fincaObj['nombre'] ?? ('Finca #' . ($currFincaId ?: 'N/A'))) : ('Finca #' . ($currFincaId ?: 'N/A'));
    $rawStatus = $personalFinca['status'] ?? 'activo';
    $status = is_bool($rawStatus) ? $rawStatus : in_array(strtolower((string)$rawStatus), ['activo', 'active', '1', 'true'], true);
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-2xl shadow-xs border border-orange-100 shrink-0">
                👥
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar personal de finca #{{ $pId }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualice los datos personales, cargo o asignación del trabajador</p>
            </div>
        </div>
        <div>
            <a href="{{ route('personal-finca.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm space-y-1">
            <div class="flex items-center space-x-2 font-bold mb-1">
                <span class="text-lg">⚠️</span>
                <p class="text-sm">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario Principal -->
    <form method="POST" action="{{ route('personal-finca.update', $pId) }}" id="formEditPersonal" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card 1: Finca y Cargo -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🏡</span> Asignación de finca y cargo
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Finca Destino -->
                        <div>
                            <label for="finca_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Finca asignada <span class="text-red-500">*</span>
                            </label>
                            <select name="finca_id" id="finca_id" required
                                    class="w-full px-4 py-3 border @error('finca_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar finca --</option>
                                @foreach($fincas as $finca)
                                    @php
                                        $fId = $finca['id'] ?? null;
                                        $fNom = $finca['nombre'] ?? ('Finca #' . $fId);
                                        $fTipo = $finca['explotacion_tipo'] ?? 'General';
                                        $isSelected = (string) old('finca_id', $currFincaId) === (string) $fId;
                                    @endphp
                                    <option value="{{ $fId }}" data-nombre="{{ $fNom }}" data-tipo="{{ $fTipo }}" {{ $isSelected ? 'selected' : '' }}>
                                        🏡 {{ $fNom }} ({{ $fTipo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('finca_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Tipo de Trabajador / Cargo -->
                        <div>
                            <label for="tipo_trabajador_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Cargo o rol de trabajo <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo_trabajador_id" id="tipo_trabajador_id" required
                                    class="w-full px-4 py-3 border @error('tipo_trabajador_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar cargo --</option>
                                @foreach($tiposTrabajador as $tipo)
                                    @php
                                        $tId = $tipo['id'] ?? null;
                                        $tNom = $tipo['nombre'] ?? '';
                                        $isSelected = (string) old('tipo_trabajador_id', $currTipoId) === (string) $tId;
                                    @endphp
                                    <option value="{{ $tId }}" data-nombre="{{ $tNom }}" {{ $isSelected ? 'selected' : '' }}>
                                        💼 {{ $tNom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_trabajador_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Estado Laboral -->
                        <div>
                            <label for="status" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Estado laboral <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-3 border @error('status') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="activo" {{ old('status', $status ? 'activo' : 'inactivo') === 'activo' ? 'selected' : '' }}>🟢 Activo (en nómina / funciones)</option>
                                <option value="inactivo" {{ old('status', $status ? 'activo' : 'inactivo') === 'inactivo' ? 'selected' : '' }}>⚪ Inactivo (desvinculado / suspendido)</option>
                            </select>
                            @error('status')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha de Ingreso -->
                        <div>
                            <label for="fecha_ingreso" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de ingreso
                            </label>
                            <input type="date" name="fecha_ingreso" id="fecha_ingreso" 
                                   value="{{ old('fecha_ingreso', $fechaIngreso) }}"
                                   class="w-full px-4 py-3 border @error('fecha_ingreso') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium text-gray-700">
                            @error('fecha_ingreso')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Datos Personales y Contacto -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>👤</span> Datos personales y de contacto
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Cédula -->
                        <div>
                            <label for="cedula" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Cédula / Identificación <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="cedula" id="cedula" required 
                                   value="{{ old('cedula', $cedulaEmp) }}" maxlength="20"
                                   placeholder="Ej: V12345678"
                                   class="w-full px-4 py-3 border @error('cedula') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-mono uppercase">
                            @error('cedula')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Nombres <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" required 
                                   value="{{ old('nombre', $nombre) }}" maxlength="25"
                                   placeholder="Ej: Juan Antonio"
                                   class="w-full px-4 py-3 border @error('nombre') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label for="apellido" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Apellidos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="apellido" id="apellido" required 
                                   value="{{ old('apellido', $apellido) }}" maxlength="25"
                                   placeholder="Ej: Pérez Mendoza"
                                   class="w-full px-4 py-3 border @error('apellido') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('apellido')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Teléfono de contacto <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="telefono" id="telefono" required 
                                   value="{{ old('telefono', $telefonoEmp) }}" maxlength="15"
                                   placeholder="Ej: 04141234567"
                                   class="w-full px-4 py-3 border @error('telefono') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('telefono')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div>
                            <label for="correo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Correo electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="correo" id="correo" required 
                                   value="{{ old('correo', $correoEmp) }}" maxlength="40"
                                   placeholder="Ej: empleado@ganaderasoft.com"
                                   class="w-full px-4 py-3 border @error('correo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('correo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label for="fecha_nacimiento" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de nacimiento
                            </label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento', $fechaNacimiento) }}"
                                   class="w-full px-4 py-3 border @error('fecha_nacimiento') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium text-gray-700">
                            @error('fecha_nacimiento')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <!-- Card 1: Resumen y Acciones -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen del personal
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3">
                            <div id="previewAvatar" class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-xl shadow-xs shrink-0">
                                {{ strtoupper(substr($nombre ?: 'P', 0, 1)) }}
                            </div>
                            <div class="overflow-hidden">
                                <p id="previewNombreCompleto" class="text-base font-bold text-gray-900 truncate">{{ trim($nombre . ' ' . $apellido) ?: 'Personal #' . $pId }}</p>
                                <p id="previewCedula" class="text-xs text-gray-500 font-mono">Cédula: #{{ $cedulaEmp ?: '---' }}</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">ID registro:</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $pId }}</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Finca asignada:</span>
                                <span id="previewFincaNombre" class="font-bold text-gray-900 text-right truncate">🏡 {{ $fincaNombre }}</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Cargo / Rol:</span>
                                <span id="previewCargoNombre" class="font-bold text-purple-700 text-right">{{ $tipoNombre }}</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Estado:</span>
                                <span id="previewStatus" class="font-bold {{ $status ? 'text-emerald-700' : 'text-gray-500' }} text-right">{{ $status ? '🟢 Activo' : '⚪ Inactivo' }}</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Teléfono:</span>
                                <span id="previewTelefono" class="font-semibold text-gray-800 text-right truncate">{{ $telefonoEmp ?: '---' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons en el Sidebar -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                💾 Actualizar personal
                            </button>

                            @if($status)
                                <button type="button"
                                    onclick="openGenericConfirmModal({
                                        formId: 'form-disable-personal',
                                        intent: 'danger',
                                        title: 'Desactivar personal de finca',
                                        message: '¿Estás seguro de que deseas desactivar a {{ $nombreEmp }} de {{ $fincaNombre }}? Pasará al estado inactivo y no se contabilizará en los indicadores de campo.',
                                        confirmText: 'Sí, desactivar'
                                    })"
                                    class="w-full py-3 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 hover:border-red-600 font-bold rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    <span>Desactivar empleado</span>
                                </button>
                            @else
                                <button type="button"
                                    onclick="openGenericConfirmModal({
                                        formId: 'form-enable-personal',
                                        intent: 'success',
                                        title: 'Activar personal de finca',
                                        message: '¿Estás seguro de que deseas reactivar a {{ $nombreEmp }} en {{ $fincaNombre }}? Pasará al estado activo.',
                                        confirmText: 'Sí, activar'
                                    })"
                                    class="w-full py-3 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-600 font-bold rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span>Activar empleado</span>
                                </button>
                            @endif

                            <a href="{{ route('personal-finca.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Zona de Peligro (Pie de página horizontal) -->
    <div class="mt-10 pt-8 border-t border-gray-200">
        <div class="bg-white rounded-2xl border border-red-200 shadow-xs p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1 max-w-2xl">
                <h4 class="text-base font-bold text-red-900 flex items-center gap-2">
                    <span>⚠️</span> Zona de peligro
                </h4>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Al eliminar este registro se desvinculará a <span class="font-semibold text-red-600">{{ $nombreEmp ?: 'este trabajador' }}</span> de la finca <span class="font-semibold text-gray-800">{{ $fincaNombre }}</span>. La ficha personal de la persona se conservará en el sistema, pero se borrará su asignación laboral en esta finca.
                </p>
            </div>
            <div class="shrink-0">
                <button type="button"
                    onclick="openGenericConfirmModal({
                        formId: 'form-delete-personal',
                        intent: 'danger',
                        title: 'Eliminar personal de finca',
                        message: '¿Estás seguro de que deseas eliminar este registro de personal de {{ $fincaNombre }}? Esta acción eliminará su asignación laboral en la finca.',
                        confirmText: 'Sí, eliminar de esta finca'
                    })"
                    class="py-3 px-5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 hover:border-red-600 font-bold rounded-xl transition-all duration-200 text-xs flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Eliminar de esta finca</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Formularios Ocultos -->
    <form id="form-delete-personal" method="POST" action="{{ route('personal-finca.destroy', $pId) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="form-disable-personal" method="POST" action="{{ route('personal-finca.disable', $pId) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>

    <form id="form-enable-personal" method="POST" action="{{ route('personal-finca.enable', $pId) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fincaSelect = document.getElementById('finca_id');
    const tipoSelect = document.getElementById('tipo_trabajador_id');
    const statusSelect = document.getElementById('status');
    const cedulaInput = document.getElementById('cedula');
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const telefonoInput = document.getElementById('telefono');

    const previewAvatar = document.getElementById('previewAvatar');
    const previewNombreCompleto = document.getElementById('previewNombreCompleto');
    const previewCedula = document.getElementById('previewCedula');
    const previewFincaNombre = document.getElementById('previewFincaNombre');
    const previewCargoNombre = document.getElementById('previewCargoNombre');
    const previewStatus = document.getElementById('previewStatus');
    const previewTelefono = document.getElementById('previewTelefono');

    function updatePreview() {
        const nom = (nombreInput ? nombreInput.value : '').trim();
        const ape = (apellidoInput ? apellidoInput.value : '').trim();
        const full = [nom, ape].filter(Boolean).join(' ');

        if (previewNombreCompleto) {
            previewNombreCompleto.textContent = full || 'Personal #{{ $pId }}';
        }

        if (previewAvatar) {
            previewAvatar.textContent = (nom || ape || 'P').charAt(0).toUpperCase();
        }

        const ced = (cedulaInput ? cedulaInput.value : '').trim();
        if (previewCedula) {
            previewCedula.textContent = ced ? ('Cédula: #' + ced.toUpperCase()) : 'Cédula: #---';
        }

        const tel = (telefonoInput ? telefonoInput.value : '').trim();
        if (previewTelefono) {
            previewTelefono.textContent = tel || '---';
        }

        const optFinca = fincaSelect ? fincaSelect.options[fincaSelect.selectedIndex] : null;
        if (optFinca && optFinca.value) {
            const fNom = optFinca.dataset.nombre || optFinca.textContent.replace(/🏡|\(.*\)/g, '').trim();
            if (previewFincaNombre) previewFincaNombre.textContent = '🏡 ' + fNom;
        }

        const optTipo = tipoSelect ? tipoSelect.options[tipoSelect.selectedIndex] : null;
        if (optTipo && optTipo.value) {
            const tNom = optTipo.dataset.nombre || optTipo.textContent.replace(/💼/g, '').trim();
            if (previewCargoNombre) previewCargoNombre.textContent = tNom;
        }

        if (statusSelect && previewStatus) {
            const isAct = statusSelect.value === 'activo' || statusSelect.value === '1';
            previewStatus.textContent = isAct ? '🟢 Activo' : '⚪ Inactivo';
            previewStatus.className = 'font-bold text-right ' + (isAct ? 'text-emerald-700' : 'text-gray-500');
        }
    }

    fincaSelect?.addEventListener('change', updatePreview);
    tipoSelect?.addEventListener('change', updatePreview);
    statusSelect?.addEventListener('change', updatePreview);
    cedulaInput?.addEventListener('input', updatePreview);
    nombreInput?.addEventListener('input', updatePreview);
    apellidoInput?.addEventListener('input', updatePreview);
    telefonoInput?.addEventListener('input', updatePreview);
});
</script>
@endsection