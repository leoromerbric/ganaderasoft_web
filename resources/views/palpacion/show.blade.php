@extends('layouts.authenticated')

@section('title', 'Detalle Palpación')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('palpacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🩺 Palpación #{{ $palpacion['palpacion_id'] }}</h2>
        </div>
        <a href="{{ route('palpacion.edit', $palpacion['palpacion_id']) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Animal</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $palpacion['animal']['Nombre'] ?? ('Animal #'.($palpacion['palpacion_etapa_anid'] ?? 'N/A')) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Etapa</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $palpacion['etapa']['etapa_nombre'] ?? ('ID: '.($palpacion['palpacion_etapa_etid'] ?? 'N/A')) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Tipo de Palpación</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $palpacion['palpacion_tipo'] ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha de Palpación</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ isset($palpacion['palpacion_fecha']) ? date('d/m/Y', strtotime($palpacion['palpacion_fecha'])) : 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Técnico</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $palpacion['tecnico']['Nombre'] ?? (isset($palpacion['id_Tecnico']) ? 'Personal #'.$palpacion['id_Tecnico'] : '-') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
