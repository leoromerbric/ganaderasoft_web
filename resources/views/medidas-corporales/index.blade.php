@extends('layouts.authenticated')

@section('title', 'Medidas Corporales')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">📏 Medidas Corporales</h2>
            <p class="mt-1 text-gray-600">Control morfométrico de los animales</p>
        </div>
        <a href="{{ route('medidas-corporales.create') }}" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
            Nuevo Registro
        </a>
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
        <form method="GET" action="{{ route('medidas-corporales.index') }}" class="grid grid-cols-1 gap-4 p-6 md:grid-cols-3">
            <div>
                <label for="animal_id" class="mb-1 block text-sm font-medium text-gray-700">Animal</label>
                <select name="animal_id" id="animal_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todos los animales</option>
                    @foreach($animales as $animal)
                        @php
                            $animalPk = $animal['id_Animal'] ?? null;
                            $animalNombre = $animal['Nombre'] ?? ('Animal #'.$animalPk);
                        @endphp
                        <option value="{{ $animalPk }}" {{ (string) $animalId === (string) $animalPk ? 'selected' : '' }}>
                            {{ $animalNombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3 md:col-span-2">
                <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">Filtrar</button>
                <a href="{{ route('medidas-corporales.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-4">
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Total registros</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ count($medidasCorporales) }}</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Altura promedio</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['altura_promedio'] }} cm</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Longitud promedio</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['largura_promedio'] }} cm</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Perímetro torácico promedio</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['circunferencia_promedio'] }} cm</p>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-ganaderasoft-negro">Registros</h3>
        </div>

        @if(count($medidasCorporales) === 0)
            <div class="p-8 text-center text-gray-500">No hay medidas corporales registradas para los filtros seleccionados.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Animal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Altura HC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Longitud LC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Perímetro PT</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($medidasCorporales as $medida)
                            @php
                                $medidaId = $medida['id_Medidas'] ?? $medida['id'] ?? null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $medida['animal_nombre'] ?? ('Animal #'.($medida['medida_etapa_anid'] ?? 'N/A')) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ isset($medida['Altura_HC']) ? number_format((float) $medida['Altura_HC'], 2, ',', '.') . ' cm' : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ isset($medida['Longitud_LC']) ? number_format((float) $medida['Longitud_LC'], 2, ',', '.') . ' cm' : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ isset($medida['Perimetro_PT']) ? number_format((float) $medida['Perimetro_PT'], 2, ',', '.') . ' cm' : '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($medidaId)
                                        <form action="{{ route('medidas-corporales.destroy', $medidaId) }}" method="POST" class="inline" onsubmit="return confirm('¿Desea eliminar este registro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600 transition-colors hover:bg-red-50">Eliminar</button>
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
@endsection@extends('layouts.authenticated')

@section('title', 'Medidas Corporales')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">📏 Medidas Corporales</h2>
            <p class="mt-1 text-gray-600">Control morfométrico de los animales</p>
        </div>
        <a href="{{ route('medidas-corporales.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
            Nuevo Registro
        </a>
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
        <form method="GET" action="{{ route('medidas-corporales.index') }}" class="grid grid-cols-1 gap-4 p-6 md:grid-cols-3">
            <div>
                <label for="animal_id" class="mb-1 block text-sm font-medium text-gray-700">Animal</label>
                <select name="animal_id" id="animal_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todos los animales</option>
                    @foreach($animales as $animal)
                        @php
                            $animalPk = $animal['id_Animal'] ?? null;
                            $animalNombre = $animal['Nombre'] ?? ('Animal #'.$animalPk);
                        @endphp
                        <option value="{{ $animalPk }}" {{ (string) $animalId === (string) $animalPk ? 'selected' : '' }}>
                            {{ $animalNombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-3 md:col-span-2">
                <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">Filtrar</button>
                <a href="{{ route('medidas-corporales.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-4">
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Total registros</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ count($medidasCorporales) }}</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Altura promedio</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['altura_promedio'] }} cm</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Longitud promedio</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['largura_promedio'] }} cm</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Perímetro torácico promedio</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['circunferencia_promedio'] }} cm</p>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-ganaderasoft-negro">Registros</h3>
        </div>

        @if(count($medidasCorporales) === 0)
            <div class="p-8 text-center text-gray-500">No hay medidas corporales registradas para los filtros seleccionados.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Animal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Altura HC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Longitud LC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Perímetro PT</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($medidasCorporales as $medida)
                            @php
                                $medidaId = $medida['id_Medidas'] ?? $medida['id'] ?? null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $medida['animal_nombre'] ?? ('Animal #'.($medida['medida_etapa_anid'] ?? 'N/A')) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ isset($medida['Altura_HC']) ? number_format((float) $medida['Altura_HC'], 2, ',', '.') . ' cm' : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ isset($medida['Longitud_LC']) ? number_format((float) $medida['Longitud_LC'], 2, ',', '.') . ' cm' : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ isset($medida['Perimetro_PT']) ? number_format((float) $medida['Perimetro_PT'], 2, ',', '.') . ' cm' : '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($medidaId)
                                        <form action="{{ route('medidas-corporales.destroy', $medidaId) }}" method="POST" class="inline" onsubmit="return confirm('¿Desea eliminar este registro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600 transition-colors hover:bg-red-50">Eliminar</button>
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