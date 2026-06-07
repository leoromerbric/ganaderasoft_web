@extends('layouts.authenticated')

@section('title', 'Detalle Tratamiento')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('tratamiento.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">💊 Tratamiento #{{ $tratamiento['tratamiento_id'] }}</h2>
        </div>
        <a href="{{ route('tratamiento.edit', $tratamiento['tratamiento_id']) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Diagnóstico</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    @if(isset($tratamiento['diagnostico']))
                        {{ $tratamiento['diagnostico']['diagnostico_tipo'] ?? '' }}
                        @if(isset($tratamiento['diagnostico']['diagnostico_fecha']))
                            ({{ date('d/m/Y', strtotime($tratamiento['diagnostico']['diagnostico_fecha'])) }})
                        @endif
                        #{{ $tratamiento['diagnostico']['diagnostico_id'] ?? '' }}
                    @else
                        {{ isset($tratamiento['tratamiento_diagnostico_id']) ? 'Diag. #'.$tratamiento['tratamiento_diagnostico_id'] : '-' }}
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha de Inicio</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ isset($tratamiento['tratamiento_fecha_ini']) ? date('d/m/Y', strtotime($tratamiento['tratamiento_fecha_ini'])) : 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha de Fin</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ isset($tratamiento['tratamiento_fecha_fin']) ? date('d/m/Y', strtotime($tratamiento['tratamiento_fecha_fin'])) : 'N/A' }}
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 uppercase tracking-wider">Plan de Tratamiento</p>
                <p class="text-base text-ganaderasoft-negro mt-1">{{ $tratamiento['tratamiento_plan'] ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
