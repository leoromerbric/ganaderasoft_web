@extends('layouts.authenticated')

@section('title', 'Detalle de Personal de Finca')

@section('content')
@php
    $pId = $personalFinca['id'] ?? null;
    $personaSub = $personalFinca['persona'] ?? null;
    $nombreEmp = $personaSub ? trim(($personaSub['nombre'] ?? '').' '.($personaSub['apellido'] ?? '')) : 'Personal';
    $cedulaEmp = $personaSub['cedula'] ?? '-';
    $telefonoEmp = $personaSub['telefono'] ?? '-';
    $correoEmp = $personaSub['correo'] ?? '-';

    $tipoObj = $personalFinca['tipo_trabajador'] ?? null;
    $tipoNombre = $tipoObj['nombre'] ?? 'Trabajador';

    $fincaObj = $personalFinca['finca'] ?? null;
    $fincaNombre = $fincaObj['nombre'] ?? ('Finca #'.($personalFinca['finca_id'] ?? 'N/A'));
    $fincaTipo = $fincaObj['explotacion_tipo'] ?? '-';
@endphp
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl font-bold text-ganaderasoft-azul">
                👤
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">{{ $nombreEmp }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $tipoNombre }} • {{ $fincaNombre }} (API V2)</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('personal-finca.edit', $pId) }}" 
               class="px-4 py-2 bg-ganaderasoft-verde-oscuro text-white text-sm font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                ✏️ Editar
            </a>
            <a href="{{ route('personal-finca.index') }}" 
               class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                Volver
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

    <!-- Profile Details Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-4">
                <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center pb-2 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-lg bg-ganaderasoft-celeste/15 flex items-center justify-center mr-3 text-base">📄</span>
                    Datos Personales
                </h3>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre Completo</p>
                        <p class="font-bold text-gray-900">{{ $nombreEmp }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cédula / Identificación</p>
                        <p class="font-bold text-gray-900">{{ $cedulaEmp }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Cargo / Especialidad</p>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-ganaderasoft-celeste/15 text-ganaderasoft-azul border border-ganaderasoft-celeste/30">
                            {{ $tipoNombre }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">ID Registro</p>
                        <p class="font-bold text-gray-900">#{{ $pId }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-4">
                <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center pb-2 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-lg bg-ganaderasoft-verde/20 flex items-center justify-center mr-3 text-base">📞</span>
                    Información de Contacto
                </h3>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Teléfono</p>
                        <p class="font-bold text-gray-900">📞 {{ $telefonoEmp }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Correo Electrónico</p>
                        <p class="font-bold text-gray-900">✉️ {{ $correoEmp }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-4">
                <h3 class="text-lg font-bold text-ganaderasoft-negro flex items-center pb-2 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-lg bg-ganaderasoft-celeste/15 flex items-center justify-center mr-3 text-base">🏡</span>
                    Finca Asignada
                </h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre</p>
                        <p class="font-bold text-gray-900">{{ $fincaNombre }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo de Explotación</p>
                        <p class="font-semibold text-gray-700">{{ $fincaTipo }}</p>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-red-100 space-y-4">
                <h3 class="text-sm font-bold text-red-700 uppercase tracking-wider pb-2 border-b border-red-100">
                    Acciones de Gestión
                </h3>

                <form action="{{ route('personal-finca.destroy', $pId) }}" method="POST"
                      onsubmit="return confirm('¿Está seguro de eliminar a este personal de finca? esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl text-xs transition-colors shadow-sm">
                        🗑️ Eliminar Registro
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection