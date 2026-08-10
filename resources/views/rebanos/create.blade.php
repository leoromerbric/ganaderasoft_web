@extends('layouts.authenticated')

@section('title', 'Crear Rebaño')

@section('content')
@php
    $fincaNombre = $selectedFinca['nombre'] ?? $selectedFinca['Nombre'] ?? 'Finca';
    $fincaId = $selectedFinca['id'] ?? $selectedFinca['id_Finca'] ?? null;
@endphp
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header & Breadcrumb -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Crear Nuevo Rebaño</h1>
            <p class="text-sm text-gray-500 mt-1">Registre un grupo o lote de ganado para la API V2</p>
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
                <h3 class="text-lg font-bold text-ganaderasoft-negro">Información del Rebaño</h3>
                <p class="text-xs text-gray-500">Asignado a la finca en sesión</p>
            </div>
        </div>

        <form method="POST" action="{{ route('rebanos.store') }}" class="space-y-6">
            @csrf

            <!-- Finca Read-Only -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Finca Destino</label>
                <div class="flex items-center px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 space-x-2">
                    <span>🏡</span>
                    <span>{{ $fincaNombre }}</span>
                    <span class="text-xs text-gray-400">(ID: #{{ $fincaId }})</span>
                </div>
            </div>

            <!-- Nombre -->
            <div>
                <label for="Nombre" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Nombre del Rebaño <span class="text-red-500">*</span>
                </label>
                <input type="text" name="Nombre" id="Nombre" required
                       value="{{ old('Nombre') }}"
                       placeholder="Ej: Rebaño Vacas Lecheras, Rebaño Norte"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 mt-1">Nombre distintivo para agrupar los animales</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                <a href="{{ route('rebanos.index') }}"
                   class="px-6 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul text-white text-sm font-semibold rounded-xl hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste transition-all duration-200 shadow-md">
                    Guardar Rebaño
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
