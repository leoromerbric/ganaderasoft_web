@extends('layouts.authenticated')

@section('title', 'Detalle Vacuna')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('vacuna.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">💉 Vacuna #{{ $vacuna['vacuna_id'] }}</h2>
        </div>
        <a href="{{ route('vacuna.edit', $vacuna['vacuna_id']) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Nombre</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $vacuna['vacuna_nombre'] ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Estado</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ (int)($vacuna['activa'] ?? 1) === 1 ? 'Activa' : 'Inactiva' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 uppercase tracking-wider">Descripción</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $vacuna['vacuna_descripcion'] ?? '-' }}</p>
            </div>
        </div>
    </div>

    @if(isset($vacuna['casasComerciales']) && count($vacuna['casasComerciales']) > 0)
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-ganaderasoft-negro">Casas Comerciales</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laboratorio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca Comercial</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($vacuna['casasComerciales'] as $casa)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $casa['laboratorio'] ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $casa['marca_comercial'] ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if(isset($vacuna['dosis']) && count($vacuna['dosis']) > 0)
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-ganaderasoft-negro">Dosis</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frecuencia</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($vacuna['dosis'] as $dosis)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dosis['dosis_id'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dosis['dosis_frecuencia'] ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
