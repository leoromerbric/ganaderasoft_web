@extends('layouts.authenticated')

@section('title', 'Detalle Diagnóstico')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('diagnostico.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🏥 Diagnóstico #{{ $diagnostico['diagnostico_id'] }}</h2>
        </div>
        <a href="{{ route('diagnostico.edit', $diagnostico['diagnostico_id']) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Animal</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $diagnostico['animal']['Nombre'] ?? ('Animal #'.($diagnostico['fk_etapa_animal_anid'] ?? 'N/A')) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Etapa</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $diagnostico['etapa']['etapa_nombre'] ?? ('ID: '.($diagnostico['fk_etapa_animal_etid'] ?? 'N/A')) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Tipo de Diagnóstico</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $diagnostico['diagnostico_tipo'] ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ isset($diagnostico['diagnostico_fecha']) ? date('d/m/Y', strtotime($diagnostico['diagnostico_fecha'])) : 'N/A' }}
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 uppercase tracking-wider">Descripción</p>
                <p class="text-base text-ganaderasoft-negro mt-1">{{ $diagnostico['diagnostico_descripcion'] ?? '-' }}</p>
            </div>
        </div>
    </div>

    @if(isset($diagnostico['tratamientos']) && count($diagnostico['tratamientos']) > 0)
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-ganaderasoft-negro">Tratamientos asociados</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($diagnostico['tratamientos'] as $tratamiento)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $tratamiento['tratamiento_id'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $tratamiento['tratamiento_plan'] ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ isset($tratamiento['tratamiento_fecha_ini']) ? date('d/m/Y', strtotime($tratamiento['tratamiento_fecha_ini'])) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ isset($tratamiento['tratamiento_fecha_fin']) ? date('d/m/Y', strtotime($tratamiento['tratamiento_fecha_fin'])) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('tratamiento.show', $tratamiento['tratamiento_id']) }}"
                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
