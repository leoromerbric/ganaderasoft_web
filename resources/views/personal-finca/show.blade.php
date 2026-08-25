@extends('layouts.authenticated')

@section('title', 'Detalle de personal de finca')

@section('content')
@php
    $pId = $personalFinca['id'] ?? $personalFinca['id_Tecnico'] ?? null;
    $personaSub = $personalFinca['persona'] ?? [];
    
    $nombre = $personaSub['nombre'] ?? $personalFinca['nombre'] ?? $personalFinca['Nombre'] ?? '';
    $apellido = $personaSub['apellido'] ?? $personalFinca['apellido'] ?? $personalFinca['Apellido'] ?? '';
    $nombreEmp = trim($nombre . ' ' . $apellido) ?: ($personalFinca['nombre_completo'] ?? ('Personal #' . $pId));
    
    $cedulaEmp = $personaSub['cedula'] ?? $personalFinca['cedula'] ?? $personalFinca['Cedula'] ?? '-';
    $telefonoEmp = $personaSub['telefono'] ?? $personalFinca['telefono'] ?? $personalFinca['Telefono'] ?? null;
    $correoEmp = $personaSub['correo'] ?? $personaSub['email'] ?? $personalFinca['correo'] ?? $personalFinca['Correo'] ?? $personalFinca['email'] ?? $personalFinca['Email'] ?? null;

    $tipoObj = $personalFinca['tipo_trabajador'] ?? [];
    $tipoNombre = is_string($tipoObj) ? $tipoObj : ($tipoObj['nombre'] ?? $personalFinca['tipo_trabajador_nombre'] ?? $personalFinca['Tipo_Trabajador'] ?? 'Trabajador');

    $fincaObj = $personalFinca['finca'] ?? [];
    $fincaId = $personalFinca['finca_id'] ?? $personalFinca['id_Finca'] ?? (is_array($fincaObj) ? ($fincaObj['id'] ?? null) : null);
    $fincaNombre = is_array($fincaObj) ? ($fincaObj['nombre'] ?? null) : null;
    if (!$fincaNombre) {
        $fincaNombre = $personalFinca['finca_nombre'] ?? ('Finca #' . ($fincaId ?: 'N/A'));
    }
    $fincaTipo = is_array($fincaObj) ? ($fincaObj['explotacion_tipo'] ?? 'General') : 'General';
    
    $status = (bool)($personalFinca['status'] ?? true);
    $createdAt = $personalFinca['created_at'] ?? null;
    $fechaIngreso = $personalFinca['fecha_ingreso'] ?? $personalFinca['Fecha_Ingreso'] ?? null;
    $fechaNacimiento = $personaSub['fecha_nacimiento'] ?? $personalFinca['fecha_nacimiento'] ?? $personalFinca['Fecha_Nacimiento'] ?? null;
    
    $inicial = strtoupper(substr($nombre ?: ($nombreEmp ?: 'P'), 0, 1));
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-700 border border-teal-100 flex items-center justify-center font-bold text-2xl shadow-xs shrink-0">
                {{ $inicial }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    {{ $nombreEmp }}
                </h1>
                <p class="text-gray-500 text-sm mt-0.5 flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                        💼 {{ $tipoNombre }}
                    </span>
                    <span>•</span>
                    <span class="font-medium text-gray-700">🏡 {{ $fincaNombre }}</span>
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($pId)
                <a href="{{ route('personal-finca.edit', $pId) }}"
                   class="px-6 py-3 bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar personal
                </a>
            @endif
            <a href="{{ route('personal-finca.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center space-x-2">
            <span class="text-lg">✅</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Columna Izquierda: Detalles (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card 1: Identificación y Datos Personales -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>👤</span> Datos personales y de identificación
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Nombre</span>
                        <p class="text-base font-bold text-gray-900">{{ $nombre ?: 'No especificado' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Apellido</span>
                        <p class="text-base font-bold text-gray-900">{{ $apellido ?: 'No especificado' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Cédula / Identificación</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-mono font-bold bg-gray-100 text-gray-800 border border-gray-200">
                            {{ $cedulaEmp }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Cargo / Especialidad</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                            💼 {{ $tipoNombre }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Estado en la finca</span>
                        @if($status)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                🟢 Activo
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                ⚪ Inactivo
                            </span>
                        @endif
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Fecha de nacimiento</span>
                        <p class="text-base font-bold text-gray-900">
                            {{ $fechaNacimiento ? date('d/m/Y', strtotime($fechaNacimiento)) : 'No especificada' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">ID del registro</span>
                        <p class="text-base font-bold text-gray-900 font-mono">#{{ $pId }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Información de Contacto -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>📞</span> Información de contacto
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Teléfono -->
                    <div class="p-4 bg-gray-50/80 border border-gray-200/80 rounded-2xl flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xl shrink-0">
                            📞
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-0.5">Teléfono principal</span>
                            @if($telefonoEmp && $telefonoEmp !== '-')
                                <a href="tel:{{ $telefonoEmp }}" class="text-base font-bold text-gray-900 hover:text-ganaderasoft-azul transition-colors truncate block">
                                    {{ $telefonoEmp }}
                                </a>
                            @else
                                <p class="text-gray-400 italic text-sm">No registrado</p>
                            @endif
                        </div>
                    </div>

                    <!-- Correo -->
                    <div class="p-4 bg-gray-50/80 border border-gray-200/80 rounded-2xl flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-xl shrink-0">
                            ✉️
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-0.5">Correo electrónico</span>
                            @if($correoEmp && $correoEmp !== '-')
                                <a href="mailto:{{ $correoEmp }}" class="text-base font-bold text-gray-900 hover:text-ganaderasoft-azul transition-colors truncate block" title="{{ $correoEmp }}">
                                    {{ $correoEmp }}
                                </a>
                            @else
                                <p class="text-gray-400 italic text-sm">No registrado</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Finca y Metadatos (1 Tercio) -->
        <div class="space-y-6">
            <!-- Card 1: Finca Asignada -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>🏡</span> Finca asignada
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3.5">
                        <div class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-2xl shadow-xs shrink-0">
                            🏡
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-base font-bold text-gray-900 truncate">{{ $fincaNombre }}</p>
                            <p class="text-xs text-gray-500 font-medium mt-0.5">Explotación: {{ $fincaTipo }}</p>
                        </div>
                    </div>

                    @if($fincaId)
                        <div class="pt-1">
                            <a href="{{ route('rebanos.index', ['finca_id' => $fincaId]) }}"
                               class="w-full py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-semibold rounded-xl text-sm flex items-center justify-center gap-2 transition-all shadow-2xs">
                                <span>🐄</span> Ver rebaños de esta finca
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 2: Registro del Sistema -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Identificador único</span>
                        <p class="text-sm font-bold text-gray-900 font-mono">
                            ID #{{ $pId ?? 'N/A' }}
                        </p>
                    </div>
                    @if($fechaIngreso)
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Fecha de ingreso a finca</span>
                            <p class="text-sm font-bold text-gray-900">
                                {{ date('d/m/Y', strtotime($fechaIngreso)) }}
                            </p>
                        </div>
                    @endif
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Fecha de registro</span>
                        <p class="text-sm font-bold text-gray-900">
                            {{ $createdAt ? date('d/m/Y H:i', strtotime($createdAt)) : 'Desconocida' }}
                        </p>
                    </div>
                    @if(isset($personalFinca['updated_at']) && $personalFinca['updated_at'])
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Última actualización</span>
                            <p class="text-sm font-bold text-gray-900">
                                {{ date('d/m/Y H:i', strtotime($personalFinca['updated_at'])) }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection