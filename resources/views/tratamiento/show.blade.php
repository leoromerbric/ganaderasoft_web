@extends('layouts.authenticated')

@section('title', 'Detalle de tratamiento médico')

@section('content')
@php
    $id = $tratamiento['id'] ?? $tratamiento['tratamiento_id'] ?? null;
    $diagId = $tratamiento['diagnostico_id'] ?? $tratamiento['tratamiento_diagnostico_id'] ?? null;
    $fechaIni = $tratamiento['fecha_ini'] ?? $tratamiento['tratamiento_fecha_ini'] ?? null;
    $fechaFin = $tratamiento['fecha_fin'] ?? $tratamiento['tratamiento_fecha_fin'] ?? null;
    $plan = $tratamiento['plan'] ?? $tratamiento['tratamiento_plan'] ?? 'Sin plan detallado';
    
    $diag = $tratamiento['diagnostico'] ?? null;
    $diagTipo = data_get($diag, 'tipo') ?? data_get($diag, 'diagnostico_tipo') ?? ($diagId ? 'Diagnóstico #'.$diagId : 'No especificado');
    $diagFecha = data_get($diag, 'fecha') ?? data_get($diag, 'diagnostico_fecha');
    $diagDescripcion = data_get($diag, 'descripcion') ?? data_get($diag, 'diagnostico_descripcion');
    $diagRefId = data_get($diag, 'id') ?? data_get($diag, 'diagnostico_id') ?? $diagId;

    $animalId = data_get($diag, 'animal_id') ?? data_get($diag, 'fk_etapa_animal_anid') ?? data_get($diag, 'etapa_animal.animal_id') ?? '';
    $animalRefId = data_get($diag, 'animal.id') ?? data_get($diag, 'animal.id_Animal') ?? data_get($diag, 'etapa_animal.animal.id') ?? $animalId;
    $animalNombre = data_get($diag, 'animal.Nombre') ?? data_get($diag, 'animal.nombre') ?? data_get($diag, 'etapa_animal.animal.nombre') ?? data_get($diag, 'etapa_animal.animal.Nombre') ?? ($animalId ? 'Animal #'.$animalId : 'Animal no identificado');
    $animalCodigo = data_get($diag, 'animal.codigo_animal') ?? data_get($diag, 'etapa_animal.animal.codigo_animal') ?? '';
    
    $rebanoId = data_get($diag, 'animal.rebano.id') ?? data_get($diag, 'animal.rebano.id_Rebano') ?? data_get($diag, 'animal.id_Rebano') ?? data_get($diag, 'animal.rebano_id') ?? '';
    $rebanoNombre = data_get($diag, 'animal.rebano.Nombre') ?? data_get($diag, 'animal.rebano.nombre') ?? ($rebanoId ? 'Rebaño #'.$rebanoId : '');
    
    $fincaId = data_get($diag, 'animal.rebano.finca_id') ?? data_get($diag, 'animal.rebano.id_Finca') ?? data_get($diag, 'animal.rebano.finca.id') ?? '';
    $fincaNombre = data_get($diag, 'animal.rebano.finca.Nombre') ?? data_get($diag, 'animal.rebano.finca.nombre') ?? ($fincaId ? 'Finca #'.$fincaId : '');

    $sexoVal = data_get($diag, 'animal.sexo') ?? data_get($diag, 'animal.Sexo') ?? data_get($diag, 'etapa_animal.animal.sexo') ?? 'H';
    $isMacho = in_array(strtoupper((string)$sexoVal), ['M', 'MACHO', 'MASCULINO']);

    // Cálculo del estado
    $isActivo = false;
    if ($fechaFin) {
        $isActivo = strtotime($fechaFin) >= strtotime(date('Y-m-d'));
    } elseif ($fechaIni) {
        $isActivo = true;
    }

    // Duración estimada
    $duracionDias = null;
    if ($fechaIni && $fechaFin) {
        $start = \Carbon\Carbon::parse($fechaIni);
        $end = \Carbon\Carbon::parse($fechaFin);
        if ($end->greaterThanOrEqualTo($start)) {
            $duracionDias = $start->diffInDays($end) + 1;
        }
    }
@endphp

<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center font-bold text-2xl shadow-xs">
                💊
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Tratamiento médico #{{ $id ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Plan terapéutico formulado para <span class="font-bold text-gray-800">{{ $animalNombre }}</span>
                    @if($animalCodigo)
                        <span class="font-mono text-gray-500">(#{{ $animalCodigo }})</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($id)
                <a href="{{ route('tratamiento.edit', $id) }}"
                   class="px-6 py-3 bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar tratamiento
                </a>
            @endif
            <a href="{{ route('tratamiento.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            
            <!-- Card 1: Animal Asociado -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🐮</span> Animal receptor del tratamiento
                </h3>

                <div class="p-5 bg-gray-50/90 border border-gray-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl {{ $isMacho ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} font-bold flex items-center justify-center text-3xl shadow-xs shrink-0">
                            {{ $isMacho ? '🐂' : '🐄' }}
                        </div>
                        <div>
                            <p class="text-xl font-bold text-gray-900">{{ $animalNombre }}</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                @if($animalCodigo)
                                    <span class="text-xs font-mono text-gray-600 bg-white px-2.5 py-0.5 rounded-md border border-gray-200 font-semibold">
                                        #{{ $animalCodigo }}
                                    </span>
                                @endif
                                @if($rebanoNombre)
                                    <span class="text-xs font-semibold text-gray-700 bg-white px-2.5 py-0.5 rounded-md border border-gray-200">
                                        {{ $rebanoNombre }}
                                    </span>
                                @endif
                                @if($fincaNombre)
                                    <span class="text-xs text-gray-500">
                                        • {{ $fincaNombre }}
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

            <!-- Card 2: Plan Terapéutico y Prescripción -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>📋</span> Plan terapéutico y prescripción médica
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Indicaciones de dosis, fármacos, frecuencia y duración del tratamiento</p>
                </div>

                <div class="p-5 bg-purple-50/50 border border-purple-100 rounded-2xl space-y-3">
                    <span class="text-xs font-bold text-purple-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span>💊</span> Indicaciones terapéuticas
                    </span>
                    <div class="text-base text-gray-900 font-medium leading-relaxed">
                        {!! nl2br(e(trim($plan))) !!}
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Fecha de inicio</span>
                        <p class="text-lg font-black text-gray-900">
                            {{ $fechaIni ? date('d/m/Y', strtotime($fechaIni)) : 'N/A' }}
                        </p>
                    </div>

                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Fecha de culminación</span>
                        <p class="text-lg font-black text-gray-900">
                            {{ $fechaFin ? date('d/m/Y', strtotime($fechaFin)) : 'Indefinida' }}
                        </p>
                    </div>

                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Duración estimada</span>
                        <p class="text-lg font-black text-gray-900">
                            {{ $duracionDias ? $duracionDias.' días' : 'En evaluación' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 3: Diagnóstico Clínico de Origen -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>🩺</span> Diagnóstico clínico de origen
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Evaluación médica previa que motiva este tratamiento</p>
                    </div>
                    @if($diagRefId)
                        <a href="{{ route('diagnostico.show', $diagRefId) }}"
                           class="px-5 py-2.5 bg-white hover:bg-gray-100 border border-gray-300 text-gray-800 font-semibold rounded-xl text-sm inline-flex items-center gap-2 transition-all shadow-xs hover:shadow-sm self-start sm:self-auto shrink-0">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Ver diagnóstico
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Tipo de diagnóstico</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                            🩺 {{ $diagTipo }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Fecha del diagnóstico</span>
                        <p class="text-base font-bold text-gray-900">
                            {{ $diagFecha ? date('d/m/Y', strtotime($diagFecha)) : 'No especificada' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">ID Diagnóstico</span>
                        <p class="text-base font-bold font-mono text-gray-900">
                            #{{ $diagRefId ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                @if($diagDescripcion)
                    <div class="space-y-2 pt-2 border-t border-gray-100">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Observaciones clínicas iniciales</span>
                        <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-200 text-sm text-gray-800 leading-relaxed">
                            {!! nl2br(e(trim($diagDescripcion))) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas de Estado y Metadatos (1 Tercio) -->
        <div class="space-y-6">
            <!-- Ficha del Tratamiento Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-ganaderasoft-verde-oscuro text-white px-6 py-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <span>📋</span> Ficha del tratamiento
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Estado de la terapia</label>
                        @if($isActivo)
                            <span class="inline-flex px-3.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                🟢 Terapia activa
                            </span>
                        @else
                            <span class="inline-flex px-3.5 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                ⚪ Terapia concluida
                            </span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Diagnóstico base</label>
                        <p class="text-sm font-bold text-gray-900">{{ $diagTipo }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de inicio</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $fechaIni ? date('d/m/Y', strtotime($fechaIni)) : 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de fin</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $fechaFin ? date('d/m/Y', strtotime($fechaFin)) : 'Indefinida' }}</p>
                    </div>
                </div>
            </div>

            <!-- Registro del Sistema Card -->
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
                            ID #{{ $id ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de registro</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($tratamiento['created_at']) ? date('d/m/Y H:i', strtotime($tratamiento['created_at'])) : 'Desconocida' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Última actualización</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($tratamiento['updated_at']) ? date('d/m/Y H:i', strtotime($tratamiento['updated_at'])) : 'Desconocida' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
