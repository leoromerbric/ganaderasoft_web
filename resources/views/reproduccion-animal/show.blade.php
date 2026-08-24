@extends('layouts.authenticated')

@section('title', 'Detalle de reproducción animal')

@section('content')
@php
    $id = $reproduccion['id'] ?? $reproduccion['repro_id'] ?? null;
    $animalId = $reproduccion['animal_id'] ?? $reproduccion['repro_etapa_anid'] ?? data_get($reproduccion, 'etapa_animal.animal_id');
    $animalRefId = data_get($reproduccion, 'animal.id') ?? data_get($reproduccion, 'animal.id_Animal') ?? $animalId;
    $animalNombre = data_get($reproduccion, 'animal.Nombre') ?? data_get($reproduccion, 'animal.nombre') ?? ('Animal #'.$animalId);
    $animalCodigo = data_get($reproduccion, 'animal.codigo_animal') ?? data_get($reproduccion, 'animal.Codigo') ?? '';
    
    $etapaId = (string)($reproduccion['etapa_id'] ?? $reproduccion['repro_etapa_etid'] ?? data_get($reproduccion, 'etapa_animal.etapa_id') ?? '');
    $etapaNombre = data_get($reproduccion, 'etapa_animal.etapa.nombre') 
        ?? data_get($reproduccion, 'etapa_animal.etapa.Nombre') 
        ?? data_get($reproduccion, 'etapa.nombre') 
        ?? data_get($reproduccion, 'etapa.Nombre') 
        ?? ($etapaId ? 'Etapa #'.$etapaId : 'En producción');

    $fincaNombre = data_get($reproduccion, 'etapa_animal.animal.rebano.finca.Nombre') ?? data_get($reproduccion, 'animal.rebano.finca.Nombre') ?? data_get($reproduccion, 'animal.rebano.finca.nombre') ?? '';
    $rebanoNombre = data_get($reproduccion, 'etapa_animal.animal.rebano.Nombre') ?? data_get($reproduccion, 'animal.rebano.Nombre') ?? data_get($reproduccion, 'animal.rebano.nombre') ?? '';

    $tipo = $reproduccion['tipo'] ?? $reproduccion['tipo_reproduccion'] ?? $reproduccion['repro_tipo_reproduccion'] ?? 'Evento';
    $fecha = $reproduccion['fecha'] ?? $reproduccion['fecha_reproduccion'] ?? $reproduccion['repro_fecha_reproduccion'] ?? null;
    $observacion = $reproduccion['observacion'] ?? $reproduccion['repro_observacion'] ?? '';

    $createdAt = $reproduccion['created_at'] ?? null;
    $updatedAt = $reproduccion['updated_at'] ?? null;

    $tipoLower = strtolower((string)$tipo);
    $isIA = str_contains($tipoLower, 'ia') || str_contains($tipoLower, 'inseminaci') || str_contains($tipoLower, 'artific');
    $isMonta = str_contains($tipoLower, 'monta') || str_contains($tipoLower, 'natural');
    $isParto = str_contains($tipoLower, 'parto') || str_contains($tipoLower, 'nacimiento');
    $isAborto = str_contains($tipoLower, 'aborto');

    // Fechas calculadas
    $fechaObj = $fecha ? \Carbon\Carbon::parse($fecha) : null;
    $fechaProxRepeticion = $fechaObj ? $fechaObj->copy()->addDays(21)->format('d/m/Y') : null;
    $fechaChequeoPrenez = $fechaObj ? $fechaObj->copy()->addDays(45)->format('d/m/Y') : null;
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🔬
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Reproducción animal #{{ $id }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Detalle del evento reproductivo y seguimiento ginecológico</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($id)
                <a href="{{ route('reproduccion-animal.edit', $id) }}" 
                   class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar registro
                </a>
            @endif
            <a href="{{ route('reproduccion-animal.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Main Content Layout (2 Columnas: 2/3 Principal, 1/3 Lateral) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Información Principal (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card 1: Hembra Receptora -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🐄</span> Hembra receptora
                </h3>

                <div class="p-5 bg-gray-50/90 border border-gray-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 border border-pink-100 font-bold flex items-center justify-center text-3xl shadow-xs shrink-0">
                            🐄
                        </div>
                        <div>
                            <p class="text-xl font-bold text-gray-900">{{ $animalNombre }}</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                @if($animalCodigo)
                                    <span class="text-xs font-mono text-gray-600 bg-white px-2.5 py-0.5 rounded-md border border-gray-200 font-semibold">
                                        #{{ $animalCodigo }}
                                    </span>
                                @endif
                                <span class="text-xs font-bold text-pink-800 bg-pink-50 px-2.5 py-0.5 rounded-md border border-pink-200">
                                    {{ $etapaNombre }}
                                </span>
                                @if($fincaNombre)
                                    <span class="text-xs font-semibold text-gray-700 bg-white px-2.5 py-0.5 rounded-md border border-gray-200">
                                        🏡 {{ $fincaNombre }}
                                    </span>
                                @endif
                                @if($rebanoNombre)
                                    <span class="text-xs font-semibold text-gray-700 bg-white px-2.5 py-0.5 rounded-md border border-gray-200">
                                        {{ $rebanoNombre }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($animalRefId)
                        <div>
                            <a href="{{ route('animales.show', $animalRefId) }}"
                               class="px-5 py-2.5 bg-white hover:bg-gray-100 border border-gray-300 text-gray-800 font-semibold rounded-xl text-sm inline-flex items-center gap-2 transition-all shadow-xs hover:shadow-sm">
                                <svg class="w-4 h-4 text-ganaderasoft-azul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver ficha del animal
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 2: Datos del Evento Reproductivo -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>🔬</span> Detalles del evento reproductivo
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Tipo de Evento -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tipo de evento</p>
                        @if($isIA)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                🧬 Inseminación Artificial (IA)
                            </span>
                        @elseif($isMonta)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                🐂 Monta Natural
                            </span>
                        @elseif($isParto)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                🐣 Parto
                            </span>
                        @elseif($isAborto)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-red-50 text-red-700 border border-red-200">
                                ⚠️ Aborto
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-gray-50 text-gray-700 border border-gray-200">
                                🔬 {{ $tipo }}
                            </span>
                        @endif
                    </div>

                    <!-- Fecha de Ejecución -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Fecha del evento</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">
                            {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'No especificada' }}
                        </p>
                    </div>

                    <!-- Etapa al momento del registro -->
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Etapa productiva registrada</p>
                        <p class="text-sm font-bold text-gray-800">
                            {{ $etapaNombre }}
                        </p>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="space-y-2 pt-2 border-t border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Observaciones y notas clínicas</span>
                    @if($observacion)
                        <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-200 text-sm text-gray-800 leading-relaxed font-medium">
                            {!! nl2br(e(trim($observacion))) !!}
                        </div>
                    @else
                        <div class="p-4 bg-gray-50/50 rounded-xl border border-dashed border-gray-200 text-sm text-gray-400 italic">
                            Sin observaciones clínicas registradas.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 3: Seguimiento Reproductivo y Diagnóstico de Gestación -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>🩺</span> Diagnóstico de gestación y seguimiento
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Chequeos ginecológicos y confirmación de preñez asociados</p>
                    </div>
                    @if(Route::has('diagnostico.create'))
                        <a href="{{ route('diagnostico.create', ['animal_id' => $animalRefId]) }}"
                           class="px-5 py-2.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-semibold rounded-xl text-sm inline-flex items-center gap-1.5 transition-all shadow-xs shrink-0">
                            + Registrar diagnóstico
                        </a>
                    @endif
                </div>

                <div class="p-10 text-center bg-gray-50/70 rounded-2xl border border-dashed border-gray-200 space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-3xl shadow-2xs border border-purple-100">
                        🩺
                    </div>
                    <div>
                        <p class="text-base font-bold text-gray-800">Seguimiento de preñez en curso</p>
                        <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                            Se sugiere programar la palpación o confirmación por ultrasonido a partir del 
                            <strong class="text-gray-700 font-semibold">{{ $fechaChequeoPrenez ?: 'día 45' }}</strong> (+45 días post-evento).
                        </p>
                    </div>
                    @if(Route::has('diagnostico.create'))
                        <div class="pt-2">
                            <a href="{{ route('diagnostico.create', ['animal_id' => $animalRefId]) }}"
                               class="px-6 py-2.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-semibold rounded-xl text-sm inline-flex items-center gap-2 shadow-xs hover:shadow-sm">
                                + Registrar chequeo de preñez
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas de Resumen y Metadatos (1 Tercio) -->
        <div class="space-y-6">
            <!-- Proyección del Ciclo Reproductivo Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>📅</span> Proyección ginecológica
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="p-4 bg-purple-50 rounded-2xl border border-purple-100 space-y-1">
                        <span class="text-xs font-semibold text-purple-600 uppercase tracking-wider block">Retorno al celo (+21d)</span>
                        <p class="text-xl font-bold text-purple-900 font-mono">
                            {{ $fechaProxRepeticion ?: 'Pendiente' }}
                        </p>
                        <p class="text-[11px] text-purple-700 leading-tight">
                            Fecha esperada para vigilar si la hembra no concibió y repite celo.
                        </p>
                    </div>

                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 space-y-1">
                        <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider block">Chequeo de preñez (+45d a +60d)</span>
                        <p class="text-xl font-bold text-emerald-900 font-mono">
                            {{ $fechaChequeoPrenez ?: 'Pendiente' }}
                        </p>
                        <p class="text-[11px] text-emerald-700 leading-tight">
                            Ventana óptima para confirmación ginecológica mediante ecografía o palpación rectal.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Registro del Sistema Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-100 text-gray-800 px-6 py-4">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-3 text-xs text-gray-600">
                    <div class="flex justify-between items-center py-1 border-b border-gray-50">
                        <span class="text-gray-500">ID del registro:</span>
                        <span class="font-bold text-gray-900 font-mono">#{{ $id }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-gray-50">
                        <span class="text-gray-500">Fecha de registro:</span>
                        <span class="font-bold text-gray-900">
                            {{ $createdAt ? date('d/m/Y H:i', strtotime($createdAt)) : 'No disponible' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-500">Última modificación:</span>
                        <span class="font-bold text-gray-900">
                            {{ $updatedAt ? date('d/m/Y H:i', strtotime($updatedAt)) : 'No disponible' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
