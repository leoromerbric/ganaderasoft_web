@extends('layouts.authenticated')

@section('title', 'Vacunación')

@section('content')
<div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
    <div>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">💉 Vacunación</h2>
        <p class="mt-1 text-gray-600">Registros de vacunación filtrados por rebaño, sexo o etapa</p>
    </div>
    <a href="{{ route('vacunacion.create') }}" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">Nueva vacunación</a>
</div>

@if(session('success'))
    <div class="mb-6 rounded-lg border-l-4 border-green-500 bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="mb-6 rounded-lg border-l-4 border-red-500 bg-red-50 p-4 text-red-800">{{ session('error') }}</div>
@endif

<div class="mb-6 rounded-xl bg-white shadow-md">
    <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white">
        <h3 class="text-lg font-semibold">Filtros</h3>
    </div>
    <form method="GET" action="{{ route('vacunacion.index') }}" class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Vacuna</label>
            <select name="vacuna_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                <option value="">Todas</option>
                @foreach($vacunas as $vacuna)
                    <option value="{{ $vacuna['vacuna_id'] ?? '' }}" {{ ($filters['vacuna_id'] ?? '') == ($vacuna['vacuna_id'] ?? '') ? 'selected' : '' }}>{{ $vacuna['vacuna_nombre'] ?? 'Vacuna' }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Rebaño</label>
            <select name="rebano_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                <option value="">Todos</option>
                @foreach($rebanos as $rebano)
                    <option value="{{ $rebano['id_Rebano'] ?? '' }}" {{ ($filters['rebano_id'] ?? '') == ($rebano['id_Rebano'] ?? '') ? 'selected' : '' }}>{{ $rebano['Nombre'] ?? ('Rebaño #'.($rebano['id_Rebano'] ?? '')) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Desde</label>
            <input type="date" name="fecha_inicio" value="{{ $filters['fecha_inicio'] ?? '' }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Hasta</label>
            <input type="date" name="fecha_fin" value="{{ $filters['fecha_fin'] ?? '' }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
        </div>
        <div class="flex items-end gap-3 lg:col-span-4">
            <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">Filtrar</button>
            <a href="{{ route('vacunacion.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
        </div>
    </form>
</div>

<div class="rounded-xl bg-white shadow-md">
    <div class="rounded-t-xl border-b border-gray-200 bg-gray-50 px-6 py-4">
        <h3 class="text-lg font-semibold text-ganaderasoft-negro">Registros</h3>
    </div>
    @if(count($vacunaciones) === 0)
        <div class="p-8 text-center text-gray-500">No hay vacunaciones registradas.</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Vacuna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Rebaño</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Animales</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Costo dosis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Monto total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($vacunaciones as $item)
                        @php $id = $item['vacunacion_id'] ?? null; @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'vacuna.vacuna_nombre') ?? ('Vacuna #'.data_get($item, 'vacunacion_vacuna_id')) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'rebano.Nombre') ?? ('Rebaño #'.data_get($item, 'vacunacion_rebano_id')) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'vacunacion_fecha') ? \Carbon\Carbon::parse(data_get($item, 'vacunacion_fecha'))->format('d/m/Y') : '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'animales_count', data_get($item, 'vacunacion_total_animales', 0)) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ number_format((float) data_get($item, 'vacunacion_costo_dosis', 0), 2, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-900">{{ number_format((float) data_get($item, 'vacunacion_monto_total', 0), 2, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                @if($id)
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('vacunacion.show', $id) }}" class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">Ver</a>
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('vacunacion.edit', $id) }}" class="text-ganaderasoft-verde hover:text-green-700">Editar</a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('vacunacion.destroy', $id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta vacunación?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-800" type="submit">Eliminar</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
