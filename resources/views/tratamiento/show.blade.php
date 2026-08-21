@extends('layouts.authenticated')

@section('title', 'Detalle tratamiento')

@section('content')
@php
    $id = $tratamiento['id'] ?? $tratamiento['tratamiento_id'] ?? null;
    $diagId = $tratamiento['diagnostico_id'] ?? $tratamiento['tratamiento_diagnostico_id'] ?? null;
    $fechaIni = $tratamiento['fecha_ini'] ?? $tratamiento['tratamiento_fecha_ini'] ?? null;
    $fechaFin = $tratamiento['fecha_fin'] ?? $tratamiento['tratamiento_fecha_fin'] ?? null;
    $plan = $tratamiento['plan'] ?? $tratamiento['tratamiento_plan'] ?? '-';
    $diag = $tratamiento['diagnostico'] ?? null;
    $diagTipo = data_get($diag, 'tipo') ?? data_get($diag, 'diagnostico_tipo');
    $diagFecha = data_get($diag, 'fecha') ?? data_get($diag, 'diagnostico_fecha');
    $diagRefId = data_get($diag, 'id') ?? data_get($diag, 'diagnostico_id') ?? $diagId;
@endphp
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('tratamiento.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">💊 Tratamiento #{{ $id }}</h2>
        </div>
        <a href="{{ route('tratamiento.edit', $id) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Diagnóstico</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    @if($diag)
                        {{ $diagTipo ?? '' }}
                        @if($diagFecha)
                            ({{ date('d/m/Y', strtotime($diagFecha)) }})
                        @endif
                        #{{ $diagRefId ?? '' }}
                    @else
                        {{ $diagId ? 'Diag. #'.$diagId : '-' }}
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha de inicio</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $fechaIni ? date('d/m/Y', strtotime($fechaIni)) : 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha de fin</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $fechaFin ? date('d/m/Y', strtotime($fechaFin)) : 'N/A' }}
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 uppercase tracking-wider">Plan de tratamiento</p>
                <p class="text-base text-ganaderasoft-negro mt-1">{{ $plan }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
