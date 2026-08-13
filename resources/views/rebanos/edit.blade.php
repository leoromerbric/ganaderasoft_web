@extends('layouts.authenticated')

@section('title', 'Editar Rebaño')

@section('content')
@php
    $rebanoId = $rebano['id'] ?? null;
    $rebanoNombre = $rebano['nombre'] ?? '';
    $fincaObj = $rebano['finca'] ?? null;
    $fincaNombre = $fincaObj['nombre'] ?? ($selectedFinca['nombre'] ?? 'Finca');
    $animalesCount = count($rebano['animales'] ?? []);
@endphp
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header & Breadcrumb -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Editar Rebaño</h1>
            <p class="text-sm text-gray-500 mt-1">Actualice la información del rebaño #{{ $rebanoId }} (API V2)</p>
        </div>
        <a href="{{ route('rebanos.index') }}" class="inline-flex items-center text-sm text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-medium transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a Rebaños
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

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-6">
        <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
            <div class="w-10 h-10 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-xl">
                🐄
            </div>
            <div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro">Detalles del Rebaño</h3>
                <p class="text-xs text-gray-500">ID del recurso: #{{ $rebanoId }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('rebanos.update', $rebanoId) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- ID Read-only -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">ID Rebaño</label>
                    <input type="text" value="#{{ $rebanoId }}" disabled
                           class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-600 font-bold">
                </div>

                <!-- Finca Read-only -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Finca</label>
                    <input type="text" value="{{ $fincaNombre }}" disabled
                           class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-600 font-medium">
                </div>
            </div>

            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Nombre del Rebaño <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" id="nombre" required
                       value="{{ old('nombre', $rebanoNombre) }}"
                       placeholder="Ej: Rebaño Vacas Lecheras"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 mt-1">Ingrese el nombre actualizado para este rebaño</p>
            </div>

            <!-- Animales preview -->
            @if($animalesCount > 0)
                <div class="p-4 bg-ganaderasoft-celeste/10 border border-ganaderasoft-celeste/20 rounded-xl text-xs space-y-2">
                    <p class="font-bold text-ganaderasoft-azul flex items-center">
                        <span class="mr-1.5">📋</span> Animales asignados: {{ $animalesCount }}
                    </p>
                    <a href="{{ route('animales.index', ['rebano_id' => $rebanoId]) }}" class="inline-block text-ganaderasoft-celeste hover:underline font-semibold">
                        Ver listado completo de animales →
                    </a>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                <a href="{{ route('rebanos.index') }}"
                   class="px-6 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul text-white text-sm font-semibold rounded-xl hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste transition-all duration-200 shadow-md">
                    Actualizar Rebaño
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
