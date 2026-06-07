@extends('layouts.authenticated')

@section('title', 'Detalle Movimiento de Rebaño')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('movimiento-rebano.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🔄 Movimiento #{{ $movimiento['id_Movimiento'] }}</h2>
        </div>
        <a href="{{ route('movimiento-rebano.edit', $movimiento['id_Movimiento']) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Finca Origen</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $movimiento['fincaOrigen']['Nombre'] ?? (isset($movimiento['id_Finca']) ? 'Finca #'.$movimiento['id_Finca'] : '-') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Rebaño Origen</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $movimiento['rebanoOrigen']['Nombre'] ?? (isset($movimiento['id_Rebano']) ? 'Rebaño #'.$movimiento['id_Rebano'] : '-') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Finca Destino</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $movimiento['fincaDestino']['Nombre'] ?? (isset($movimiento['id_Finca_Destino']) ? 'Finca #'.$movimiento['id_Finca_Destino'] : '-') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Rebaño Destino</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $movimiento['rebanoDestino']['Nombre'] ?? $movimiento['Rebano_Destino'] ?? (isset($movimiento['id_Rebano_Destino']) ? 'Rebaño #'.$movimiento['id_Rebano_Destino'] : '-') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Nombre Rebaño Destino</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $movimiento['Rebano_Destino'] ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Comentario</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">{{ $movimiento['Comentario'] ?? '-' }}</p>
            </div>
        </div>
    </div>

    @if(isset($movimiento['animales']) && count($movimiento['animales']) > 0)
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-ganaderasoft-negro">
                Animales Movidos ({{ count($movimiento['animales']) }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($movimiento['animales'] as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $item['animal']['id_Animal'] ?? $item['id_Animal'] ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item['animal']['Nombre'] ?? $item['Nombre'] ?? '-' }}
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
