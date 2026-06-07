@extends('layouts.authenticated')

@section('title', 'Detalle Reproducción Animal')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('reproduccion-animal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🔬 Reproducción #{{ $reproduccion['repro_id'] }}</h2>
        </div>
        <a href="{{ route('reproduccion-animal.edit', $reproduccion['repro_id']) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Animal</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $reproduccion['animal']['Nombre'] ?? ('Animal #'.($reproduccion['repro_etapa_anid'] ?? 'N/A')) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Etapa</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $reproduccion['etapa']['etapa_nombre'] ?? ('ID: '.($reproduccion['repro_etapa_etid'] ?? 'N/A')) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Tipo de Reproducción</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $reproduccion['repro_tipo_reproduccion'] ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha de Reproducción</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ isset($reproduccion['repro_fecha_reproduccion']) ? date('d/m/Y', strtotime($reproduccion['repro_fecha_reproduccion'])) : 'N/A' }}
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 uppercase tracking-wider">Observación</p>
                <p class="text-lg text-ganaderasoft-negro mt-1">{{ $reproduccion['repro_observacion'] ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
