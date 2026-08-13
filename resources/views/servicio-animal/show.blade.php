@extends('layouts.authenticated')

@section('title', 'Detalle Servicio Animal')

@section('content')
@php
    $id = $servicio['id'] ?? $servicio['servicio_id'] ?? null;
    $animalId = $servicio['animal_id'] ?? $servicio['servicio_id_Animal'] ?? data_get($servicio, 'etapa_animal.animal_id');
    $animalNombre = data_get($servicio, 'animal.Nombre') ?? ('Animal #'.$animalId);
    $tipo = $servicio['tipo'] ?? $servicio['servicio_tipo'] ?? '-';
    $fecha = $servicio['fecha'] ?? $servicio['servicio_fecha'] ?? null;
    $semenId = $servicio['semen_id'] ?? $servicio['servicio_semen_id'] ?? null;
    $semenNombre = data_get($servicio, 'semen.toro.Nombre') ?? data_get($servicio, 'semen.descripcion') ?? data_get($servicio, 'semen.codigo') ?? ($semenId ? 'Semen #'.$semenId : '-');
    $tecnicoId = $servicio['tecnico_id'] ?? $servicio['servicio_id_Tecnico'] ?? null;
    $tecnicoNombre = data_get($servicio, 'tecnico.Nombre') ?? data_get($servicio, 'tecnico.Nombre_Completo') ?? data_get($servicio, 'tecnico.nombre') ?? ($tecnicoId ? 'Personal #'.$tecnicoId : '-');
    $celoId = $servicio['celo_id'] ?? $servicio['servicio_celo_id'] ?? null;
    $observacion = $servicio['observacion'] ?? $servicio['servicio_observacion'] ?? '-';
@endphp
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('servicio-animal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🐂 Servicio Animal #{{ $id }}</h2>
        </div>
        <a href="{{ route('servicio-animal.edit', $id) }}"
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
                <p class="text-sm text-gray-500 uppercase tracking-wider">Tipo de Servicio</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $tipo }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha del Servicio</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Semen / Toro</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $semenNombre }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Técnico</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $tecnicoNombre }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Registro de Celo</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $celoId ? ('Celo #'.$celoId) : '-' }}
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 uppercase tracking-wider">Observación</p>
                <p class="text-lg text-ganaderasoft-negro mt-1">{{ $observacion }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
