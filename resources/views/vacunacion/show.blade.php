@extends('layouts.authenticated')

@section('title', 'Detalle de vacunación')

@section('content')
@php
    $id = $vacunacion['id'] ?? null;
    $animal = $vacunacion['animal'] ?? [];
    $animalId = $vacunacion['animal_id'] ?? data_get($animal, 'id');
    $animalNombre = data_get($animal, 'nombre') ?? ('Animal #'.$animalId);
    $animalCodigo = data_get($animal, 'codigo_animal') ?? 'S/C';
    $animalSexo = data_get($animal, 'sexo');
    $isArchivado = (bool) data_get($animal, 'archivado', false);
    $rebanoNombre = data_get($animal, 'rebano.nombre') ?? ('Rebaño #'.data_get($animal, 'rebano_id', ''));
    $fincaNombre = data_get($animal, 'rebano.finca.nombre') ?? '';

    $vacuna = $vacunacion['vacuna'] ?? [];
    $vacunaNombre = data_get($vacuna, 'nombre') ?? ('Vacuna #'.($vacunacion['vacuna_id'] ?? ''));

    $aplicador = $vacunacion['aplicador'] ?? [];
    $aplicadorNombre = $aplicador ? (trim((data_get($aplicador, 'nombre') ?? '').' '.(data_get($aplicador, 'apellido') ?? '')) ?: data_get($aplicador, 'cedula')) : null;

    $fecha = $vacunacion['fecha'] ?? null;
    $dosis = $vacunacion['dosis'] ?? null;
    $lote = $vacunacion['lote'] ?? null;
    $costo = (float)($vacunacion['costo'] ?? 0);
    $observacion = $vacunacion['observacion'] ?? null;
    $createdAt = $vacunacion['created_at'] ?? null;
    $updatedAt = $vacunacion['updated_at'] ?? null;
@endphp

<div class="space-y-8">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-2xl shadow-sm border border-blue-100">
                💉
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    {{ $animalCodigo }} - {{ $animalNombre }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Vacunación: <span class="font-medium text-gray-800">{{ $vacunaNombre }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('vacunacion.edit', $id) }}" 
               class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar vacunación
            </a>
            <a href="{{ route('vacunacion.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Información Principal (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información del Animal -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>🐮</span> Información del animal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre</p>
                        <p class="text-lg font-bold text-gray-900">{{ $animalNombre }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Código identificador</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">{{ $animalCodigo }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Sexo</p>
                        <p class="text-lg font-bold text-gray-900">{{ $animalSexo === 'H' ? 'Hembra' : ($animalSexo === 'M' ? 'Macho' : 'N/A') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rebaño / finca</p>
                        <p class="text-lg font-bold text-gray-900">{{ $rebanoNombre }} @if($fincaNombre) • {{ $fincaNombre }} @endif</p>
                    </div>
                </div>
            </div>

            <!-- Detalles del Biológico y Aplicación -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>🧪</span> Detalles del biológico y aplicación
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Vacuna</p>
                        <p class="text-lg font-bold text-gray-900">{{ $vacunaNombre }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de aplicación</p>
                        <p class="text-lg font-bold text-gray-900">{{ $fecha ? \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') : 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Dosis aplicada</p>
                        <p class="text-lg font-bold text-gray-900">{{ $dosis ? $dosis . ' ml' : 'Dosis estándar' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Número de lote</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">{{ $lote ?: 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Costo individual</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">{{ number_format($costo, 2, ',', '.') }} $</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Aplicador / veterinario</p>
                        <p class="text-lg font-bold text-gray-900">{{ $aplicadorNombre ?: 'No especificado' }}</p>
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-4 flex items-center gap-2">
                    <span>📝</span> Observaciones
                </h3>
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                    <p class="text-sm font-medium text-gray-700 leading-relaxed">
                        {{ $observacion ?: 'Sin observaciones registradas.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas de Estado y Sistema (1 Tercio) -->
        <div class="space-y-6">
            <!-- Estado de la Dosis Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-ganaderasoft-verde-oscuro text-white px-6 py-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <span>💉</span> Estado de la dosis
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado de aplicación</label>
                        <span class="inline-flex px-3 py-1 text-base font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Aplicada
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado del animal</label>
                        @if($isArchivado)
                            <span class="inline-flex px-3 py-1 text-base font-bold rounded-full bg-red-50 text-red-800 border border-red-200">
                                Archivado
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-base font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Activo
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información del Sistema Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Identificador único</label>
                        <p class="text-sm font-semibold text-gray-900 font-mono">
                            ID #{{ $id }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de registro</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $createdAt ? \Illuminate\Support\Carbon::parse($createdAt)->format('d/m/Y H:i') : 'Desconocida' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Última actualización</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $updatedAt ? \Illuminate\Support\Carbon::parse($updatedAt)->format('d/m/Y H:i') : 'Desconocida' }}
                        </p>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <form method="POST" action="{{ route('vacunacion.destroy', $id) }}" id="form-delete-show">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="openGenericConfirmModal({
                                formId: 'form-delete-show',
                                intent: 'danger',
                                title: 'Eliminar registro de vacunación',
                                message: '¿Estás seguro de que deseas eliminar este registro de vacunación? Esta acción no se puede deshacer.',
                                confirmText: 'Sí, eliminar'
                            })"
                               class="w-full py-3 px-4 border border-red-200 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition-colors text-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar vacunación
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-ui.confirm-modal />
@endsection
