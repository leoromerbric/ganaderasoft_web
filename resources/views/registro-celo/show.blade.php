@extends('layouts.authenticated')

@section('title', 'Detalle Registro de Celo')

@section('content')
@php
    $id = $registro['id'] ?? $registro['celo_id'] ?? null;
    $animalId = $registro['animal_id'] ?? $registro['celo_etapa_anid'] ?? data_get($registro, 'etapa_animal.animal_id');
    $animalNombre = data_get($registro, 'animal.Nombre') ?? ('Animal #'.$animalId);
    $etapaId = $registro['etapa_id'] ?? $registro['celo_etapa_etid'] ?? data_get($registro, 'etapa_animal.etapa_id');
    $etapaNombre = data_get($registro, 'etapa_animal.etapa.nombre') ?? data_get($registro, 'etapa_animal.etapa.etapa_nombre') ?? data_get($registro, 'etapa.nombre') ?? data_get($registro, 'etapa.etapa_nombre') ?? ('ID: '.($etapaId ?? 'N/A'));
    $fecha = $registro['fecha'] ?? $registro['celo_fecha'] ?? null;
    $observacion = $registro['observacion'] ?? $registro['celo_observacon'] ?? '-';
@endphp
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('registro-celo.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🌡️ Registro de Celo #{{ $id }}</h2>
        </div>
        <a href="{{ route('registro-celo.edit', $id) }}"
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
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha de Celo</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Observación</p>
                <p class="text-lg text-ganaderasoft-negro mt-1">{{ $observacion }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
