@extends('layouts.authenticated')

@section('title', 'Detalle Histórico de Aplicación')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('historico-aplicacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">📋 Histórico #{{ $historico['ha_id'] }}</h2>
        </div>
        <a href="{{ route('historico-aplicacion.edit', $historico['ha_id']) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Vacuna</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $historico['vacuna']['vacuna_nombre'] ?? (isset($historico['ha_vacuna_id']) ? 'Vacuna #'.$historico['ha_vacuna_id'] : '-') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Casa Comercial</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    @if(isset($historico['casaComercial']))
                        {{ $historico['casaComercial']['laboratorio'] ?? '' }} - {{ $historico['casaComercial']['marca_comercial'] ?? '' }}
                    @else
                        {{ isset($historico['ha_casa_id']) ? 'Casa #'.$historico['ha_casa_id'] : '-' }}
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Dosis</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $historico['dosis']['dosis_frecuencia'] ?? (isset($historico['ha_dosis_id']) ? 'Dosis #'.$historico['ha_dosis_id'] : '-') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Animal</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ data_get($historico, 'animal.Nombre') ?? (isset($historico['ha_animal_id']) ? 'Animal #'.$historico['ha_animal_id'] : '-') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Origen</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $historico['ha_origen_tipo'] ?? '-' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha de Inyección</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ isset($historico['fecha_inyeccion']) ? date('d/m/Y', strtotime($historico['fecha_inyeccion'])) : 'N/A' }}
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 uppercase tracking-wider">Observación</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $historico['observacion'] ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
