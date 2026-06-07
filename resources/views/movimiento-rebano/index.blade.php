@extends('layouts.authenticated')

@section('title', 'Movimiento de Rebaño')

@section('content')
<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🔄 Movimiento de Rebaño</h2>
            <p class="text-gray-600 mt-1">Gestión de movimientos entre fincas y rebaños</p>
        </div>
        <a href="{{ route('movimiento-rebano.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
            Nuevo
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('movimiento-rebano.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Finca Origen</label>
                <select name="finca_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todas</option>
                    @foreach($fincas as $finca)
                        <option value="{{ $finca['id_Finca'] }}" {{ $fincaId == $finca['id_Finca'] ? 'selected' : '' }}>
                            {{ $finca['Nombre'] ?? 'Finca #'.$finca['id_Finca'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rebaño Origen</label>
                <select name="rebano_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todos</option>
                    @foreach($rebanos as $rebano)
                        <option value="{{ $rebano['id_Rebano'] }}" {{ $rebanoId == $rebano['id_Rebano'] ? 'selected' : '' }}>
                            {{ $rebano['Nombre'] ?? 'Rebaño #'.$rebano['id_Rebano'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">
                Filtrar
            </button>
            <a href="{{ route('movimiento-rebano.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                Limpiar
            </a>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if(count($movimientos) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Finca Origen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rebaño Origen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Finca Destino</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rebaño Destino</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($movimientos as $movimiento)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $movimiento['id_Movimiento'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $movimiento['fincaOrigen']['Nombre'] ?? (isset($movimiento['id_Finca']) ? 'Finca #'.$movimiento['id_Finca'] : '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $movimiento['rebanoOrigen']['Nombre'] ?? (isset($movimiento['id_Rebano']) ? 'Rebaño #'.$movimiento['id_Rebano'] : '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $movimiento['fincaDestino']['Nombre'] ?? (isset($movimiento['id_Finca_Destino']) ? 'Finca #'.$movimiento['id_Finca_Destino'] : '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $movimiento['rebanoDestino']['Nombre'] ?? $movimiento['Rebano_Destino'] ?? (isset($movimiento['id_Rebano_Destino']) ? 'Rebaño #'.$movimiento['id_Rebano_Destino'] : '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ isset($movimiento['animales']) ? count($movimiento['animales']) : 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('movimiento-rebano.show', $movimiento['id_Movimiento']) }}"
                                       class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">Ver</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('movimiento-rebano.edit', $movimiento['id_Movimiento']) }}"
                                       class="text-ganaderasoft-verde hover:text-green-700">Editar</a>
                                    <span class="text-gray-300">|</span>
                                    <form method="POST" action="{{ route('movimiento-rebano.destroy', $movimiento['id_Movimiento']) }}" class="inline"
                                          onsubmit="return confirm('¿Está seguro de que desea eliminar este registro?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">🔄</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay movimientos registrados</h3>
                <p class="text-gray-500 mb-6">Comienza registrando el primer movimiento de rebaño</p>
                <a href="{{ route('movimiento-rebano.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    Nuevo Movimiento
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
