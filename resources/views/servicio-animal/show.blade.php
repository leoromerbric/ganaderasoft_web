@extends('layouts.authenticated')

@section('title', 'Detalle Servicio Animal')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('servicio-animal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🐂 Servicio Animal #{{ $servicio['servicio_id'] }}</h2>
        </div>
        <a href="{{ route('servicio-animal.edit', $servicio['servicio_id']) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Animal</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $servicio['animal']['Nombre'] ?? ('Animal #'.($servicio['servicio_id_Animal'] ?? 'N/A')) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Tipo de Servicio</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $servicio['servicio_tipo'] ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha del Servicio</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ isset($servicio['servicio_fecha']) ? date('d/m/Y', strtotime($servicio['servicio_fecha'])) : 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Semen / Toro</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $servicio['semen']['toro']['Nombre'] ?? (isset($servicio['servicio_semen_id']) ? 'Semen #'.$servicio['servicio_semen_id'] : '-') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Técnico</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $servicio['tecnico']['Nombre'] ?? (isset($servicio['servicio_id_Tecnico']) ? 'Personal #'.$servicio['servicio_id_Tecnico'] : '-') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Registro de Celo</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ isset($servicio['servicio_celo_id']) ? ('Celo #'.$servicio['servicio_celo_id']) : '-' }}
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 uppercase tracking-wider">Observación</p>
                <p class="text-lg text-ganaderasoft-negro mt-1">{{ $servicio['servicio_observacion'] ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
