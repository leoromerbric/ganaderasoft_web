@extends('layouts.authenticated')

@section('title', 'Vacunación')

@section('content')
<div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
    <div>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">💉 Vacunación</h2>
        <p class="mt-1 text-gray-600">Registros de vacunación filtrados por rebaño, sexo o etapa</p>
    </div>
    <a href="{{ route('vacunacion.create') }}" class="rounded-lg bg-ganaderasoft-verde-oscuro px-6 py-3 text-white shadow-md transition-all duration-200 hover:bg-opacity-90">Nueva vacunación</a>
</div>

<div class="mb-6 rounded-xl border border-ganaderasoft-celeste/30 bg-white shadow-sm">
    <form method="GET" action="{{ route('vacunacion.index') }}" class="grid grid-cols-1 gap-4 p-6 md:grid-cols-5">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Vacuna</label>
            <select name="vacuna_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Todas</option>
                @foreach($vacunas as $vacuna)
                    <option value="{{ $vacuna['vacuna_id'] ?? '' }}" {{ ($filters['vacuna_id'] ?? '') == ($vacuna['vacuna_id'] ?? '') ? 'selected' : '' }}>{{ $vacuna['vacuna_nombre'] ?? 'Vacuna' }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Rebaño</label>
            <select name="rebano_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Todos</option>
                @foreach($rebanos as $rebano)
                    <option value="{{ $rebano['id_Rebano'] ?? '' }}" {{ ($filters['rebano_id'] ?? '') == ($rebano['id_Rebano'] ?? '') ? 'selected' : '' }}>{{ $rebano['Nombre'] ?? ('Rebaño #'.($rebano['id_Rebano'] ?? '')) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Desde</label>
            <input type="date" name="fecha_inicio" value="{{ $filters['fecha_inicio'] ?? '' }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Hasta</label>
            <input type="date" name="fecha_fin" value="{{ $filters['fecha_fin'] ?? '' }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="rounded-lg bg-ganaderasoft-celeste px-4 py-2 text-sm font-medium text-white">Filtrar</button>
            <a href="{{ route('vacunacion.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600">Limpiar</a>
        </div>
    </form>
</div>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
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
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($vacunaciones as $item)
                        @php $id = $item['vacunacion_id'] ?? null; @endphp
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'vacuna.vacuna_nombre') ?? ('Vacuna #'.data_get($item, 'vacunacion_vacuna_id')) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'rebano.Nombre') ?? ('Rebaño #'.data_get($item, 'vacunacion_rebano_id')) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'vacunacion_fecha') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'animales_count', data_get($item, 'vacunacion_total_animales', 0)) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ number_format((float) data_get($item, 'vacunacion_costo_dosis', 0), 2, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ number_format((float) data_get($item, 'vacunacion_monto_total', 0), 2, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                @if($id)
                                    <a href="{{ route('vacunacion.show', $id) }}" class="mr-2 text-ganaderasoft-azul hover:underline">Ver</a>
                                    <a href="{{ route('vacunacion.edit', $id) }}" class="mr-2 text-ganaderasoft-celeste hover:underline">Editar</a>
                                    <form action="{{ route('vacunacion.destroy', $id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta vacunación?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline" type="submit">Eliminar</button>
                                    </form>
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
