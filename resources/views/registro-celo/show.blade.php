@extends('layouts.authenticated')

@section('title', 'Detalle del registro de celo')

@section('content')
@php
    $id = $registro['id'] ?? $registro['celo_id'] ?? null;
    $fecha = $registro['fecha'] ?? $registro['celo_fecha'] ?? null;
    $observacion = $registro['observacion'] ?? $registro['celo_observacon'] ?? null;

    // Animal
    $animalId = $registro['animal_id'] ?? $registro['celo_etapa_anid'] ?? data_get($registro, 'etapa_animal.animal_id') ?? '';
    $animalRefId = data_get($registro, 'animal.id') ?? data_get($registro, 'animal.id_Animal') ?? data_get($registro, 'etapa_animal.animal.id') ?? $animalId;
    $animalNombre = data_get($registro, 'etapa_animal.animal.nombre') ?? data_get($registro, 'etapa_animal.animal.Nombre') ?? data_get($registro, 'animal.Nombre') ?? data_get($registro, 'animal.nombre') ?? ('Animal #'.$animalId);
    $animalCodigo = data_get($registro, 'etapa_animal.animal.codigo_animal') ?? data_get($registro, 'animal.codigo_animal') ?? '';

    $etapasMap = [];
    foreach($etapas ?? [] as $e) {
        $eId = (string)($e['id'] ?? $e['etapa_id'] ?? '');
        $eNom = $e['nombre'] ?? $e['etapa_nombre'] ?? $e['Nombre'] ?? '';
        if ($eId && $eNom) {
            $etapasMap[$eId] = $eNom;
        }
    }

    // Etapa
    $etapaId = (string)($registro['etapa_id'] ?? $registro['celo_etapa_etid'] ?? data_get($registro, 'etapa_animal.etapa.id') ?? data_get($registro, 'etapa_animal.etapa_id') ?? '');
    $etapaNombre = data_get($registro, 'etapa_animal.etapa.nombre') 
        ?? data_get($registro, 'etapa_animal.etapa.Nombre') 
        ?? data_get($registro, 'etapa_animal.etapa.etapa_nombre') 
        ?? data_get($registro, 'etapa.nombre') 
        ?? ($etapaId && isset($etapasMap[$etapaId]) ? $etapasMap[$etapaId] : 'En producción');

    // Ubicación
    $rebanoNombre = data_get($registro, 'etapa_animal.animal.rebano.nombre') ?? data_get($registro, 'etapa_animal.animal.rebano.Nombre') ?? data_get($registro, 'animal.rebano.Nombre') ?? data_get($registro, 'animal.rebano.nombre') ?? '';
    $fincaNombre = data_get($registro, 'etapa_animal.animal.rebano.finca.nombre') ?? data_get($registro, 'etapa_animal.animal.rebano.finca.Nombre') ?? data_get($registro, 'animal.rebano.finca.Nombre') ?? data_get($registro, 'animal.rebano.finca.nombre') ?? '';

    // Proyección del próximo ciclo estral (+21 días)
    $proximoCeloEstimado = null;
    $diasTranscurridos = null;
    if ($fecha) {
        $fechaTimestamp = strtotime($fecha);
        $proximoCeloEstimado = date('d/m/Y', strtotime('+21 days', $fechaTimestamp));
        $diasTranscurridos = (int) round((time() - $fechaTimestamp) / 86400);
    }

    // Servicios
    $servicios = $registro['servicios'] ?? [];
    if (!is_array($servicios)) {
        $servicios = [];
    }
@endphp

<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-2xl shadow-sm border border-orange-100 shrink-0">
                🌡️
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Registro de celo #{{ $id ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Fecha: <span class="font-medium text-gray-800">{{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'No especificada' }}</span>
                    <span>•</span>
                    Hembra: <span class="font-medium text-gray-800">{{ $animalNombre }}</span>
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($id)
                <a href="{{ route('registro-celo.edit', $id) }}" 
                   class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar registro
                </a>
            @endif
            <a href="{{ route('registro-celo.index') }}" 
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

            <!-- Card 2: Detalles del Evento de Celo -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>🌡️</span> Datos del ciclo de celo
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Información sobre la fecha de detección y observaciones clínicas</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Fecha de detección</span>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Etapa productiva</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            🏷️ {{ $etapaNombre }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Días transcurridos</span>
                        <p class="text-lg font-bold text-gray-900">
                            @if($diasTranscurridos === 0)
                                Hoy
                            @elseif($diasTranscurridos > 0)
                                {{ $diasTranscurridos }} {{ $diasTranscurridos === 1 ? 'día' : 'días' }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Observaciones y signos de celo</span>
                    @if($observacion)
                        <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-200 text-sm text-gray-800 leading-relaxed font-medium">
                            {!! nl2br(e(trim($observacion))) !!}
                        </div>
                    @else
                        <div class="p-4 bg-gray-50/50 rounded-xl border border-dashed border-gray-200 text-sm text-gray-400 italic">
                            Sin observaciones clínicas o signos particulares registrados.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 3: Servicios Reproductivos Asociados -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>💉</span> Servicios reproductivos vinculados
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Montas o inseminaciones realizadas a partir de este celo</p>
                    </div>
                    @if(Route::has('servicio-animal.create'))
                        <a href="{{ route('servicio-animal.create', ['registro_celo_id' => $id, 'animal_id' => $animalRefId]) }}"
                           class="px-5 py-2.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-semibold rounded-xl text-sm inline-flex items-center gap-1.5 transition-all shadow-xs shrink-0">
                            + Registrar servicio
                        </a>
                    @endif
                </div>

                @if(count($servicios) > 0)
                    <div class="divide-y divide-gray-100 border border-gray-100 rounded-2xl overflow-hidden">
                        @foreach($servicios as $srv)
                            @php
                                $sId = $srv['id'] ?? null;
                                $sFecha = $srv['fecha'] ?? null;
                                $sTipo = $srv['tipo'] ?? $srv['tipo_servicio'] ?? 'Inseminación';
                                $sTecnico = data_get($srv, 'tecnico.name') ?? data_get($srv, 'tecnico.persona.nombre') ?? 'No especificado';
                                $sSemen = data_get($srv, 'semen.codigo') ?? data_get($srv, 'semen.toro.nombre') ?? 'N/A';
                            @endphp
                            <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-gray-50/80 transition-colors">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900 text-base">Servicio #{{ $sId }}</span>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $sTipo }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Fecha: <strong class="text-gray-700 font-semibold">{{ $sFecha ? date('d/m/Y', strtotime($sFecha)) : 'S/F' }}</strong>
                                        <span class="mx-1.5">•</span>
                                        Técnico: <strong class="text-gray-700 font-semibold">{{ $sTecnico }}</strong>
                                    </p>
                                </div>
                                @if($sId && Route::has('servicio-animal.show'))
                                    <a href="{{ route('servicio-animal.show', $sId) }}"
                                       class="px-5 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-100 font-semibold rounded-xl text-sm inline-flex items-center gap-1.5 transition-all shadow-xs self-start sm:self-auto">
                                        Ver servicio
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-10 text-center bg-gray-50/70 rounded-2xl border border-dashed border-gray-200 space-y-3">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shadow-2xs border border-blue-100">
                            💉
                        </div>
                        <div>
                            <p class="text-base font-bold text-gray-800">Aún no se han registrado servicios para este celo</p>
                            <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">Puedes registrar una monta natural o inseminación artificial vinculada directamente a este evento reproductivo.</p>
                        </div>
                        @if(Route::has('servicio-animal.create'))
                            <div class="pt-2">
                                <a href="{{ route('servicio-animal.create', ['registro_celo_id' => $id, 'animal_id' => $animalRefId]) }}"
                                   class="px-6 py-2.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-semibold rounded-xl text-sm inline-flex items-center gap-2 shadow-xs hover:shadow-sm">
                                    + Registrar servicio
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas de Resumen y Metadatos (1 Tercio) -->
        <div class="space-y-6">
            <!-- Proyección del Ciclo Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-ganaderasoft-verde-oscuro text-white px-6 py-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <span>📊</span> Proyección reproductiva
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Próximo celo estimado</label>
                        <p class="text-base font-bold text-gray-900">
                            {{ $proximoCeloEstimado ? '📅 '.$proximoCeloEstimado : 'No calculable' }}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-0.5">Ciclo estral promedio regular de 21 días</p>
                    </div>

                    <div class="pt-3 border-t border-gray-100">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ventana óptima de servicio</label>
                        <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                            ⏱️ 12 a 18 horas tras detección
                        </span>
                    </div>
                </div>
            </div>

            <!-- Registro del Sistema Card (Idéntico a admin/users) -->
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
                            {{ isset($registro['created_at']) ? date('d/m/Y H:i', strtotime($registro['created_at'])) : 'Desconocida' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Última actualización</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($registro['updated_at']) ? date('d/m/Y H:i', strtotime($registro['updated_at'])) : 'Desconocida' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
