@extends('layouts.authenticated')

@section('title', 'Semen de Toro')

@section('content')
<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🧪 Semen de Toro</h2>
            <p class="text-gray-600 mt-1">Gestión del inventario de semen de toros</p>
        </div>
        <a href="{{ route('semen-toro.create') }}"
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
        <form method="GET" action="{{ route('semen-toro.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Toro</label>
                <select name="toro_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todos</option>
                    @foreach($toros as $toro)
                        <option value="{{ $toro['id_Animal'] }}" {{ $toroId == $toro['id_Animal'] ? 'selected' : '' }}>
                            {{ $toro['Nombre'] ?? 'Toro #'.$toro['id_Animal'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="activo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todos</option>
                    <option value="1" {{ $activo === '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ $activo === '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">
                Filtrar
            </button>
            <a href="{{ route('semen-toro.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                Limpiar
            </a>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if(count($semenToros) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($semenToros as $semen)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $semen['semen_id'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $semen['toro']['Nombre'] ?? ('Toro #'.($semen['id_Toro'] ?? 'N/A')) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(isset($semen['semen_estado']))
                                    <span class="px-2 py-1 text-xs rounded-full {{ $semen['semen_estado'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $semen['semen_estado'] ? 'Activo' : 'Inactivo' }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ isset($semen['semen_fecha']) ? date('d/m/Y', strtotime($semen['semen_fecha'])) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('semen-toro.show', $semen['semen_id']) }}"
                                       class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">Ver</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('semen-toro.edit', $semen['semen_id']) }}"
                                       class="text-ganaderasoft-verde hover:text-green-700">Editar</a>
                                    <span class="text-gray-300">|</span>
                                    <form method="POST" action="{{ route('semen-toro.destroy', $semen['semen_id']) }}" class="inline"
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
                <div class="text-6xl mb-4">🧪</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay registros de semen</h3>
                <p class="text-gray-500 mb-6">Comienza registrando el primer semen de toro</p>
                <a href="{{ route('semen-toro.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    Nuevo Registro
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
