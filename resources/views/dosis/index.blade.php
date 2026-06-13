@extends('layouts.authenticated')

@section('title', 'Dosis')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🧪 Dosis</h2>
            <p class="mt-1 text-gray-600">Gestión de dosis asociadas a vacunas y casas comerciales</p>
        </div>
        <a href="{{ route('dosis.create') }}" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md">Nuevo Registro</a>
    </div>

    @if(session('success'))<div class="mb-6 rounded-lg border-l-4 border-green-500 bg-green-50 p-4 text-green-800">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-6 rounded-lg border-l-4 border-red-500 bg-red-50 p-4 text-red-800">{{ session('error') }}</div>@endif

    <div class="mb-6 rounded-xl bg-white shadow-md">
        <form method="GET" action="{{ route('dosis.index') }}" class="grid grid-cols-1 gap-4 p-6 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Vacuna</label>
                <select name="vacuna_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todas</option>
                    @foreach($vacunas as $vacuna)
                        <option value="{{ $vacuna['vacuna_id'] ?? '' }}" {{ (string) $vacunaId === (string) ($vacuna['vacuna_id'] ?? '') ? 'selected' : '' }}>{{ $vacuna['vacuna_nombre'] ?? 'Vacuna' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 md:pt-6">
                <input type="checkbox" id="vigentes" name="vigentes" value="1" {{ $vigentes ? 'checked' : '' }} class="rounded border-gray-300 text-ganaderasoft-celeste focus:ring-ganaderasoft-celeste">
                <label for="vigentes" class="text-sm text-gray-700">Solo vigentes</label>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">Filtrar</button>
                <a href="{{ route('dosis.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-ganaderasoft-negro">Listado</h3>
        </div>
        @if(count($dosis) === 0)
            <div class="p-8 text-center text-gray-500">No hay dosis registradas.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Vacuna</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Casa comercial</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Objetivo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Frecuencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Vigencia</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($dosis as $item)
                            @php $dosisId = $item['dosis_id'] ?? null; @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'vacuna.vacuna_nombre') ?? ('Vacuna #'.data_get($item, 'dosis_vacuna_id')) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'casa_comercial.laboratorio') ?? data_get($item, 'casaComercial.laboratorio') ?? ('Casa #'.data_get($item, 'dosis_casa_id')) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @php $tipo = data_get($item, 'dosis_objetivo_tipo'); @endphp
                                    @if($tipo === 'animal')
                                        Animal: {{ data_get($item, 'animal.Nombre') ?? ('#'.data_get($item, 'dosis_objetivo_animal_id')) }}
                                    @elseif($tipo === 'rebano')
                                        Rebaño: {{ data_get($item, 'rebano.Nombre') ?? ('#'.data_get($item, 'dosis_objetivo_rebano_id')) }}
                                    @elseif($tipo === 'subgrupo')
                                        Subgrupo en rebaño {{ data_get($item, 'rebano.Nombre') ?? ('#'.data_get($item, 'dosis_objetivo_rebano_id')) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'dosis_frecuencia') ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ data_get($item, 'dosis_fecha_uso_ini') }}{{ data_get($item, 'dosis_fecha_uso_fin') ? ' a '.data_get($item, 'dosis_fecha_uso_fin') : '' }}</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    @if($dosisId)
                                        <a href="{{ route('dosis.show', $dosisId) }}" class="mr-2 text-ganaderasoft-azul hover:underline">Ver</a>
                                        <a href="{{ route('dosis.edit', $dosisId) }}" class="mr-2 text-ganaderasoft-celeste hover:underline">Editar</a>
                                        <form action="{{ route('dosis.destroy', $dosisId) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta dosis?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
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
</div>
@endsection