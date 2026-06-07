@extends('layouts.authenticated')

@section('title', 'Detalle de Lactancia')

@section('content')
<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Detalle del Período de Lactancia</h2>
            <p class="text-gray-600 mt-1">Información del período registrado</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('lactancia.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Volver</a>
            <a href="{{ route('lactancia.edit', $lactancia['lactancia_id']) }}" class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80">Editar</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        @php
            $animalNombre = data_get($lactancia, 'animal.Nombre')
                ?? ('Animal #'.(data_get($lactancia, 'lactancia_etapa_anid') ?? 'N/A'));
            $etapaNombre = data_get($lactancia, 'etapaAnimal.etapa.etapa_nombre')
                ?? data_get($lactancia, 'etapaAnimal.etapa.Nombre')
                ?? data_get($lactancia, 'etapa.Nombre')
                ?? data_get($lactancia, 'etapa.descripcion')
                ?? 'Etapa no disponible';
            $fechaInicio = data_get($lactancia, 'lactancia_fecha_inicio');
            $fechaFin = data_get($lactancia, 'Lactancia_fecha_fin');
            $secado = data_get($lactancia, 'lactancia_secado');
        @endphp

        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm text-gray-500">ID Lactancia</dt>
                <dd class="text-base font-semibold text-gray-900">{{ data_get($lactancia, 'lactancia_id') }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Animal</dt>
                <dd class="text-base font-semibold text-gray-900">{{ $animalNombre }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Etapa</dt>
                <dd class="text-base font-semibold text-gray-900">{{ $etapaNombre }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Fecha de Inicio</dt>
                <dd class="text-base font-semibold text-gray-900">{{ $fechaInicio ? date('d/m/Y', strtotime($fechaInicio)) : 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Fecha de Fin</dt>
                <dd class="text-base font-semibold text-gray-900">{{ $fechaFin ? date('d/m/Y', strtotime($fechaFin)) : 'En curso' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Fecha de Secado</dt>
                <dd class="text-base font-semibold text-gray-900">{{ $secado ? date('d/m/Y', strtotime($secado)) : 'No registrada' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
