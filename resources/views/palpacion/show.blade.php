@extends('layouts.authenticated')

@section('title', 'Detalle Palpación')

@section('content')
@php
    $id = $palpacion['id'] ?? $palpacion['palpacion_id'] ?? null;
    $animalId = $palpacion['animal_id'] ?? $palpacion['palpacion_etapa_anid'] ?? data_get($palpacion, 'etapa_animal.animal_id');
    $animalNombre = data_get($palpacion, 'animal.Nombre') ?? ('Animal #'.$animalId);
    $etapaId = $palpacion['etapa_id'] ?? $palpacion['palpacion_etapa_etid'] ?? data_get($palpacion, 'etapa_animal.etapa_id');
    $etapaNombre = data_get($palpacion, 'etapa_animal.etapa.nombre') ?? data_get($palpacion, 'etapa_animal.etapa.etapa_nombre') ?? data_get($palpacion, 'etapa.nombre') ?? data_get($palpacion, 'etapa.etapa_nombre') ?? ('ID: '.($etapaId ?? 'N/A'));
    $tipo = $palpacion['tipo'] ?? $palpacion['palpacion_tipo'] ?? '-';
    $fecha = $palpacion['fecha'] ?? $palpacion['palpacion_fecha'] ?? null;
    $tecnicoId = $palpacion['tecnico_id'] ?? $palpacion['id_Tecnico'] ?? null;
    $tecnicoNombre = data_get($palpacion, 'tecnico.Nombre') ?? data_get($palpacion, 'tecnico.Nombre_Completo') ?? data_get($palpacion, 'tecnico.nombre') ?? ($tecnicoId ? 'Personal #'.$tecnicoId : '-');
@endphp
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('palpacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🩺 Palpación #{{ $id }}</h2>
        </div>
        <a href="{{ route('palpacion.edit', $id) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Animal</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $animalNombre }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Etapa</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $etapaNombre }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Tipo de Palpación</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $tipo }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha de Palpación</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Técnico</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $tecnicoNombre }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
