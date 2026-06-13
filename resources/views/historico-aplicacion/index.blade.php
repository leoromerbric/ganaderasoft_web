@extends('layouts.authenticated')

@section('title', 'Histórico de Aplicación')

@section('content')
<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">📋 Histórico de Aplicación</h2>
            <p class="text-gray-600 mt-1">Registro histórico de aplicaciones de vacunas</p>
        </div>
        <a href="{{ route('historico-aplicacion.create') }}"
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
        <form method="GET" action="{{ route('historico-aplicacion.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Vacuna</label>
                <select name="vacuna_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todas</option>
                    @foreach($vacunas as $vacuna)
                        <option value="{{ $vacuna['vacuna_id'] }}" {{ $vacunaId == $vacuna['vacuna_id'] ? 'selected' : '' }}>
                            {{ $vacuna['vacuna_nombre'] ?? 'Vacuna #'.$vacuna['vacuna_id'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste">
            </div>
            <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">
                Filtrar
            </button>
            <a href="{{ route('historico-aplicacion.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                Limpiar
            </a>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if(count($historicos) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacuna</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Casa Comercial</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inyección</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($historicos as $historico)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $historico['ha_id'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $historico['vacuna']['vacuna_nombre'] ?? (isset($historico['ha_vacuna_id']) ? 'Vacuna #'.$historico['ha_vacuna_id'] : '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $historico['casaComercial']['laboratorio'] ?? (isset($historico['ha_casa_id']) ? 'Casa #'.$historico['ha_casa_id'] : '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ data_get($historico, 'animal.Nombre') ?? (isset($historico['ha_animal_id']) ? 'Animal #'.$historico['ha_animal_id'] : '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $historico['dosis']['dosis_frecuencia'] ?? (isset($historico['ha_dosis_id']) ? 'Dosis #'.$historico['ha_dosis_id'] : '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $historico['ha_origen_tipo'] ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ isset($historico['fecha_inyeccion']) ? date('d/m/Y', strtotime($historico['fecha_inyeccion'])) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $historico['observacion'] ?? '' }}">{{ $historico['observacion'] ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('historico-aplicacion.show', $historico['ha_id']) }}"
                                       class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">Ver</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('historico-aplicacion.edit', $historico['ha_id']) }}"
                                       class="text-ganaderasoft-verde hover:text-green-700">Editar</a>
                                    <span class="text-gray-300">|</span>
                                    <form method="POST" action="{{ route('historico-aplicacion.destroy', $historico['ha_id']) }}" class="inline"
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
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay registros de aplicación</h3>
                <p class="text-gray-500 mb-6">Comienza registrando la primera aplicación de vacuna</p>
                <a href="{{ route('historico-aplicacion.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    Nuevo Registro
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
