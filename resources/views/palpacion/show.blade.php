@extends('layouts.authenticated')

@section('title', 'Detalle de palpación animal')

@section('content')
@php
    $id = $palpacion['id'] ?? $palpacion['palpacion_id'] ?? null;
    $animalId = $palpacion['animal_id'] ?? $palpacion['palpacion_etapa_anid'] ?? data_get($palpacion, 'etapa_animal.animal_id');
    $animalRefId = data_get($palpacion, 'animal.id') ?? data_get($palpacion, 'animal.id_Animal') ?? $animalId;
    $animalNombre = data_get($palpacion, 'animal.Nombre') ?? data_get($palpacion, 'animal.nombre') ?? ('Animal #'.$animalId);
    $animalCodigo = data_get($palpacion, 'animal.codigo_animal') ?? data_get($palpacion, 'animal.Codigo') ?? '';
    
    $etapaId = (string)($palpacion['etapa_id'] ?? $palpacion['palpacion_etapa_etid'] ?? data_get($palpacion, 'etapa_animal.etapa_id') ?? '');
    $etapaNombre = data_get($palpacion, 'etapa_animal.etapa.nombre') 
        ?? data_get($palpacion, 'etapa_animal.etapa.Nombre') 
        ?? data_get($palpacion, 'etapa.nombre') 
        ?? data_get($palpacion, 'etapa.Nombre') 
        ?? ($etapaId ? 'Etapa #'.$etapaId : 'En producción');

    $fincaNombre = data_get($palpacion, 'etapa_animal.animal.rebano.finca.Nombre') ?? data_get($palpacion, 'animal.rebano.finca.Nombre') ?? data_get($palpacion, 'animal.rebano.finca.nombre') ?? '';
    $rebanoNombre = data_get($palpacion, 'etapa_animal.animal.rebano.Nombre') ?? data_get($palpacion, 'animal.rebano.Nombre') ?? data_get($palpacion, 'animal.rebano.nombre') ?? '';

    $tipo = $palpacion['tipo'] ?? $palpacion['palpacion_tipo'] ?? 'Revisión';
    $fecha = $palpacion['fecha'] ?? $palpacion['palpacion_fecha'] ?? null;

    $tecnicoId = $palpacion['tecnico_id'] ?? $palpacion['personal_finca_id'] ?? $palpacion['id_Tecnico'] ?? null;
    $tecnicoNombre = trim((data_get($palpacion, 'tecnico.persona.nombre') ?? data_get($palpacion, 'tecnico.Nombre') ?? data_get($palpacion, 'tecnico.nombre') ?? '') . ' ' . (data_get($palpacion, 'tecnico.persona.apellido') ?? data_get($palpacion, 'tecnico.Apellido') ?? ''));
    $tecnicoCargo = data_get($palpacion, 'tecnico.tipo_trabajador.nombre') ?? data_get($palpacion, 'tecnico.tipoTrabajador.nombre') ?? data_get($palpacion, 'tecnico.Tipo_Trabajador') ?? 'Veterinario';

    $createdAt = $palpacion['created_at'] ?? null;
    $updatedAt = $palpacion['updated_at'] ?? null;

    $tipoLower = strtolower((string)$tipo);
    $isPrenada = str_contains($tipoLower, 'preñ') || str_contains($tipoLower, 'gestan') || str_contains($tipoLower, 'positiv');
    $isVacia = str_contains($tipoLower, 'vac') || str_contains($tipoLower, 'negativ') || str_contains($tipoLower, 'abiert');
    $isEco = str_contains($tipoLower, 'eco') || str_contains($tipoLower, 'ultra');

    // Fechas calculadas
    $fechaObj = $fecha ? \Carbon\Carbon::parse($fecha) : null;
    $fechaPartoEstimada = ($fechaObj && $isPrenada) ? $fechaObj->copy()->addDays(283)->format('d/m/Y') : null;
    $fechaSecadoEstimada = ($fechaObj && $isPrenada) ? $fechaObj->copy()->addDays(220)->format('d/m/Y') : null;
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🩺
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Palpación animal #{{ $id }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Detalle del diagnóstico ginecológico y evaluación reproductiva</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($id)
                <a href="{{ route('palpacion.edit', $id) }}" 
                   class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar registro
                </a>
            @endif
            <a href="{{ route('palpacion.index') }}" 
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
            
            <!-- Card 1: Hembra Evaluada -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🐄</span> Hembra evaluada
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

            <!-- Card 2: Datos del Diagnóstico Clínico -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>🩺</span> Resultados de la palpación
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Diagnóstico / Resultado -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Resultado clínico</p>
                        @if($isPrenada)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                🤰 Preñada / Gestante
                            </span>
                        @elseif($isVacia)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                ⭕ Vacía / Abierta
                            </span>
                        @elseif($isEco)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                🔬 Ecografía
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                🩺 {{ $tipo }}
                            </span>
                        @endif
                    </div>

                    <!-- Fecha de Ejecución -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Fecha de la evaluación</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">
                            {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'No especificada' }}
                        </p>
                    </div>

                    <!-- Etapa al momento del registro -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Etapa productiva registrada</p>
                        <p class="text-sm font-bold text-gray-800">
                            {{ $etapaNombre }}
                        </p>
                    </div>

                    <!-- Técnico Evaluador -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Veterinario / Técnico evaluador</p>
                        @if($tecnicoNombre)
                            <div class="flex items-center gap-2">
                                <span class="text-xl">👨‍⚕️</span>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 leading-tight">{{ $tecnicoNombre }}</p>
                                    <p class="text-xs text-gray-500 leading-tight">{{ $tecnicoCargo }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic">Sin técnico asignado</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card 3: Estado Reproductivo y Plan de Manejo -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>🗓️</span> Plan reproductivo asociado
                    </h3>
                </div>

                @if($isPrenada)
                    <div class="p-5 bg-emerald-50/80 rounded-2xl border border-emerald-200 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">✅</span>
                            <h4 class="text-base font-bold text-emerald-900">Gestación confirmada</h4>
                        </div>
                        <p class="text-xs text-emerald-800 leading-relaxed">
                            La hembra ha sido diagnosticada como gestante. Fecha estimada de parto calculada a 283 días: 
                            <strong class="font-bold font-mono">{{ $fechaPartoEstimada ?: 'Pendiente' }}</strong>. 
                            Se recomienda planificar el secado a partir del <strong class="font-bold font-mono">{{ $fechaSecadoEstimada ?: 'día 220' }}</strong>.
                        </p>
                    </div>
                @else
                    <div class="p-5 bg-amber-50/80 rounded-2xl border border-amber-200 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">ℹ️</span>
                            <h4 class="text-base font-bold text-amber-900">Hembra no gestante / En revisión</h4>
                        </div>
                        <p class="text-xs text-amber-800 leading-relaxed">
                            No se detectó preñez confirmada en esta evaluación. Se recomienda monitorear la manifestación de celo en los próximos 21 días o evaluar la sincronización para un nuevo servicio reproductivo.
                        </p>
                        <div class="pt-1">
                            <a href="{{ route('servicio-animal.create', ['animal_id' => $animalRefId]) }}"
                               class="px-5 py-2 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-semibold rounded-xl text-xs inline-flex items-center gap-1.5 shadow-xs">
                                + Programar nuevo servicio reproductivo
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas de Resumen y Metadatos (1 Tercio) -->
        <div class="space-y-6">
            <!-- Proyección Ginecológica Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>📅</span> Proyección ginecológica
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($isPrenada)
                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 space-y-1">
                            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider block">Parto estimado (+283d)</span>
                            <p class="text-xl font-bold text-emerald-900 font-mono">
                                {{ $fechaPartoEstimada ?: 'Pendiente' }}
                            </p>
                            <p class="text-[11px] text-emerald-700 leading-tight">
                                Fecha probable para la preparación de maternidad y paritorio.
                            </p>
                        </div>

                        <div class="p-4 bg-purple-50 rounded-2xl border border-purple-100 space-y-1">
                            <span class="text-xs font-semibold text-purple-600 uppercase tracking-wider block">Secado estimado (+220d)</span>
                            <p class="text-xl font-bold text-purple-900 font-mono">
                                {{ $fechaSecadoEstimada ?: 'Pendiente' }}
                            </p>
                            <p class="text-[11px] text-purple-700 leading-tight">
                                Cese de ordeño para permitir el descanso de la ubre previo al parto.
                            </p>
                        </div>
                    @else
                        <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 space-y-1">
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider block">Retorno al celo</span>
                            <p class="text-base font-bold text-blue-900">
                                Monitoreo cada 18-24 días
                            </p>
                            <p class="text-[11px] text-blue-700 leading-tight">
                                Vigilar signos de celo matutino y vespertino para sincronizar servicio.
                            </p>
                        </div>
                    @endif
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
                        <span class="text-gray-500">ID de palpación:</span>
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
