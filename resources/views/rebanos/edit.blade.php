@extends('layouts.authenticated')

@section('title', 'Editar rebaño')

@section('content')
@php
    $rebanoId = $rebano['id'] ?? null;
    $rebanoNombre = $rebano['nombre'] ?? '';
    $fincaObj = $rebano['finca'] ?? null;
    $fincaNombre = $fincaObj['nombre'] ?? 'Finca';
    $fincaTipo = $fincaObj['explotacion_tipo'] ?? 'General';
    $animalesCount = (int)($rebano['total_animales'] ?? count($rebano['animales'] ?? []));
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-2xl shadow-xs border border-orange-100 shrink-0">
                🐄
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar rebaño #{{ $rebanoId }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualice la información y parámetros de la agrupación ganadera</p>
            </div>
        </div>
        <div>
            <a href="{{ route('rebanos.index') }}" 
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
    <form method="POST" action="{{ route('rebanos.update', $rebanoId) }}" id="formEditRebano" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            <!-- Columna Izquierda: 2 Cajas Independientes (2 Tercios) -->
            <div class="lg:col-span-2 flex flex-col gap-6 justify-between h-full">
                <!-- Caja 1: Datos principales -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📋</span> Datos principales
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Finca de Ubicación (Solo lectura) -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Finca de ubicación
                            </label>
                            <div class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl text-sm font-medium text-gray-700 flex items-center justify-between">
                                <span>🏡 {{ $fincaNombre }}</span>
                                <span class="text-xs px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-100 font-semibold">{{ $fincaTipo }}</span>
                            </div>
                        </div>

                        <!-- Nombre del Rebaño -->
                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Nombre del rebaño <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" required 
                                   value="{{ old('nombre', $rebanoNombre) }}" maxlength="100"
                                   placeholder="Ej: Vacas en ordeño, mautas norte, lote de ceba..."
                                   class="w-full px-4 py-3 border @error('nombre') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Caja 2: Animales vinculados al rebaño (Caja separada alineada) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>🐄</span> Animales vinculados al rebaño
                        </h3>
                        <span class="text-xs font-bold px-3 py-1 rounded-lg {{ $animalesCount > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500' }}">
                            {{ $animalesCount }} {{ $animalesCount === 1 ? 'animal registrado' : 'animales registrados' }}
                        </span>
                    </div>

                    <div class="flex-1 flex flex-col justify-between gap-4">
                        @if($animalesCount > 0)
                            <div class="p-4 bg-emerald-50/70 border border-emerald-100 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-emerald-900">
                                        {{ $animalesCount === 1 ? '1 animal activo asignado a este lote' : "{$animalesCount} animales activos asignados a este lote" }}
                                    </p>
                                    <p class="text-xs text-emerald-700 mt-0.5">El rebaño cuenta con animales activos. Puedes consultar el listado o realizar traslados.</p>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('animales.index', ['rebano_id' => $rebanoId]) }}"
                                       class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl text-xs inline-flex items-center gap-2 transition-all shadow-xs">
                                        <span>Ver animales</span>
                                        <span>➔</span>
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div class="p-3.5 bg-gray-50/80 border border-gray-100 rounded-xl space-y-1">
                                    <span class="font-bold text-gray-800 flex items-center gap-1.5">
                                        <span>➕</span> Registro de ganado
                                    </span>
                                    <p class="text-gray-500 leading-relaxed text-[11px]">
                                        Incorpora nuevos animales directamente a tu inventario.
                                    </p>
                                    <a href="{{ route('animales.create') }}"
                                       class="text-ganaderasoft-verde-oscuro font-bold hover:underline inline-flex items-center gap-1 pt-1 text-[11px]">
                                        <span>+ Registrar animal</span>
                                    </a>
                                </div>

                                <div class="p-3.5 bg-gray-50/80 border border-gray-100 rounded-xl space-y-1">
                                    <span class="font-bold text-gray-800 flex items-center gap-1.5">
                                        <span>🔄</span> Movimiento de lote
                                    </span>
                                    <p class="text-gray-500 leading-relaxed text-[11px]">
                                        Transfiere animales de este grupo hacia otro rebaño.
                                    </p>
                                    <a href="{{ route('movimiento-rebano.create') }}"
                                       class="text-blue-600 font-bold hover:underline inline-flex items-center gap-1 pt-1 text-[11px]">
                                        <span>Trasladar animales ➔</span>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-gray-50 border border-gray-200/80 rounded-2xl flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">No hay animales asignados a este rebaño todavía</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Puedes incorporar animales al rebaño mediante registro directo o traslados de lote.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div class="p-4 bg-emerald-50/60 border border-emerald-100 rounded-xl space-y-2 flex flex-col justify-between">
                                    <div>
                                        <span class="font-bold text-emerald-900 flex items-center gap-1.5">
                                            <span>➕</span> Registro de ganado
                                        </span>
                                        <p class="text-emerald-800/80 leading-relaxed text-[11px] mt-1">
                                            Registra nuevos animales para incorporarlos a tu finca.
                                        </p>
                                    </div>
                                    <a href="{{ route('animales.create') }}"
                                       class="w-fit px-3 py-1.5 bg-white hover:bg-emerald-100 border border-emerald-300 text-emerald-900 font-bold rounded-lg text-xs inline-flex items-center gap-1 transition-all shadow-2xs">
                                        <span>+ Nuevo animal</span>
                                    </a>
                                </div>

                                <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-xl space-y-2 flex flex-col justify-between">
                                    <div>
                                        <span class="font-bold text-blue-900 flex items-center gap-1.5">
                                            <span>🔄</span> Traslado de lote
                                        </span>
                                        <p class="text-blue-800/80 leading-relaxed text-[11px] mt-1">
                                            Mueve animales existentes desde otros rebaños hacia este grupo.
                                        </p>
                                    </div>
                                    <a href="{{ route('movimiento-rebano.create') }}"
                                       class="w-fit px-3 py-1.5 bg-white hover:bg-blue-100 border border-blue-300 text-blue-900 font-bold rounded-lg text-xs inline-flex items-center gap-1 transition-all shadow-2xs">
                                        <span>Mover animales ➔</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo y Zona de Peligro (1 Tercio) -->
            <div class="lg:col-span-1 h-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col justify-between h-full">
                    <div>
                        <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                            <h3 class="text-lg font-bold flex items-center gap-2">
                                <span>📋</span> Resumen del rebaño
                            </h3>
                        </div>

                        <div class="p-6 space-y-5">
                            <!-- Preview Avatar e Identificación -->
                            <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-2xl shadow-xs shrink-0">
                                    🐄
                                </div>
                                <div class="overflow-hidden">
                                    <p id="previewNombre" class="text-base font-bold text-gray-900 truncate">{{ $rebanoNombre }}</p>
                                    <p class="text-xs text-gray-500 font-mono">ID Rebaño: #{{ $rebanoId }}</p>
                                </div>
                            </div>

                            <!-- Mini Stats Preview -->
                            <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                                <div class="flex justify-between items-center gap-2">
                                    <span class="text-gray-500">Ubicación:</span>
                                    <span class="font-bold text-gray-900 text-right truncate">🏡 {{ $fincaNombre }}</span>
                                </div>
                                <div class="flex justify-between items-center gap-2">
                                    <span class="text-gray-500">Tipo explotación:</span>
                                    <span class="font-bold text-blue-700 text-right">{{ $fincaTipo }}</span>
                                </div>
                                <div class="flex justify-between items-center gap-2">
                                    <span class="text-gray-500">Animales actuales:</span>
                                    <span class="font-bold text-emerald-700 text-right">{{ $animalesCount }} {{ $animalesCount === 1 ? 'animal' : 'animales' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons en el fondo de la columna derecha -->
                    <div class="p-6 pt-0 space-y-3">
                        <button type="submit"
                                class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                            💾 Actualizar rebaño
                        </button>

                        @if(!empty($rebano['archivado']))
                            <button type="button"
                                onclick="openGenericConfirmModal({
                                    formId: 'formUnarchiveRebano',
                                    intent: 'success',
                                    title: 'Desarchivar rebaño',
                                    message: '¿Estás seguro de que deseas reactivar este rebaño? Volverá a estar visible en todas las operaciones activas del sistema.',
                                    confirmText: 'Sí, desarchivar'
                                })"
                                class="w-full py-3 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-600 font-bold rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>Desarchivar rebaño</span>
                            </button>
                        @else
                            <button type="button"
                                onclick="openGenericConfirmModal({
                                    formId: 'formArchiveRebano',
                                    intent: 'danger',
                                    title: 'Archivar rebaño',
                                    message: '¿Estás seguro de que deseas archivar este rebaño? Se ocultará de las operaciones activas pero conservará todos sus registros históricos.',
                                    confirmText: 'Sí, archivar'
                                })"
                                class="w-full py-3 bg-amber-50 hover:bg-amber-600 text-amber-700 hover:text-white border border-amber-200 hover:border-amber-600 font-bold rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <span>Archivar rebaño</span>
                            </button>
                        @endif

                        <a href="{{ route('rebanos.index') }}"
                           class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                            Cancelar
                        </a>
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
                    Al eliminar este rebaño se borrarán permanentemente <span class="font-semibold text-red-600">todos los animales asignados a él</span>, incluyendo su historial productivo, pesajes, lactancias, eventos reproductivos y registros médicos de salud.
                </p>
            </div>
            <div class="shrink-0">
                <button type="button"
                    onclick="openGenericConfirmModal({
                        formId: 'formDeleteRebano',
                        intent: 'danger',
                        title: 'Eliminar rebaño definitivamente',
                        message: '¿Estás seguro de que deseas eliminar este rebaño permanentemente? Se eliminarán de forma irreversible TODOS los animales pertenecientes a él, sus pesajes, lactancias, genealogías y registros veterinarios asociados.',
                        confirmText: 'Sí, eliminar definitivamente'
                    })"
                    class="py-3 px-5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 hover:border-red-600 font-bold rounded-xl transition-all duration-200 text-xs flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Eliminar rebaño definitivamente</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para Archivar / Desarchivar -->
    @if(!empty($rebano['archivado']))
        <form id="formUnarchiveRebano" action="{{ route('rebanos.desarchivar', $rebanoId) }}" method="POST" class="hidden">
            @csrf
        </form>
    @else
        <form id="formArchiveRebano" action="{{ route('rebanos.archivar', $rebanoId) }}" method="POST" class="hidden">
            @csrf
        </form>
    @endif

    <!-- Formulario oculto para Eliminación Definitiva -->
    <form id="formDeleteRebano" action="{{ route('rebanos.destroy', $rebanoId) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<x-ui.confirm-modal />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nombreInput = document.getElementById('nombre');
    const previewNombre = document.getElementById('previewNombre');

    nombreInput?.addEventListener('input', function () {
        if (previewNombre) {
            previewNombre.textContent = nombreInput.value.trim() || 'Rebaño #{{ $rebanoId }}';
        }
    });
});
</script>
@endsection