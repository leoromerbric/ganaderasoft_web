@extends('layouts.authenticated')

@section('title', 'Peso Corporal')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">📊 Peso Corporal</h2>
            <p class="mt-1 text-gray-600">Registro y seguimiento del peso de los animales</p>
        </div>
        <a href="{{ route('peso-corporal.create') }}" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
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
        <form method="GET" action="{{ route('peso-corporal.index') }}" class="grid grid-cols-1 gap-4 p-6 md:grid-cols-4">
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
            <div>
                <label for="fecha_inicio" class="mb-1 block text-sm font-medium text-gray-700">Desde</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
            </div>
            <div>
                <label for="fecha_fin" class="mb-1 block text-sm font-medium text-gray-700">Hasta</label>
                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ $fechaFin }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">Filtrar</button>
                <a href="{{ route('peso-corporal.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-4">
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Total registros</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ count($pesosCorporales) }}</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Peso promedio</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['peso_promedio'] }} kg</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Peso máximo</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['peso_maximo'] }} kg</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Peso mínimo</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['peso_minimo'] }} kg</p>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-ganaderasoft-negro">Registros</h3>
        </div>

        @if(count($pesosCorporales) === 0)
            <div class="p-8 text-center text-gray-500">No hay registros de peso para los filtros seleccionados.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Animal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Peso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Comentario</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($pesosCorporales as $peso)
                            @php
                                $pesoId = $peso['id_Peso'] ?? $peso['id'] ?? null;
                                $animalNombre = $peso['animal_nombre'] ?? ('Animal #'.($peso['peso_etapa_anid'] ?? 'N/A'));
                                $fechaPeso = $peso['Fecha_Peso'] ?? null;
                                $comentario = $peso['Comentario'] ?? null;
                                $valorPeso = $peso['Peso'] ?? null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $animalNombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $fechaPeso ? \Carbon\Carbon::parse($fechaPeso)->format('d/m/Y') : '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $valorPeso !== null ? number_format((float) $valorPeso, 2, ',', '.') : '-' }} kg</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $comentario ?: '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($pesoId)
                                        <form action="{{ route('peso-corporal.destroy', $pesoId) }}" method="POST" class="inline" onsubmit="return confirm('¿Desea eliminar este registro?')">
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

@section('title', 'Peso Corporal')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">📊 Peso Corporal</h2>
            <p class="mt-1 text-gray-600">Registro y seguimiento de peso de los animales</p>
        </div>
        <a href="{{ route('peso-corporal.create') }}"
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
        <form method="GET" action="{{ route('peso-corporal.index') }}" class="grid grid-cols-1 gap-4 p-6 md:grid-cols-4">
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

            <div>
                <label for="fecha_inicio" class="mb-1 block text-sm font-medium text-gray-700">Desde</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio }}"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
            </div>

            <div>
                <label for="fecha_fin" class="mb-1 block text-sm font-medium text-gray-700">Hasta</label>
                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ $fechaFin }}"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">Filtrar</button>
                <a href="{{ route('peso-corporal.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-4">
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Total registros</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ count($pesosCorporales) }}</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Peso promedio</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['peso_promedio'] }} kg</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Peso máximo</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['peso_maximo'] }} kg</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-md">
            <p class="text-sm text-gray-500">Peso mínimo</p>
            <p class="mt-2 text-2xl font-bold text-ganaderasoft-negro">{{ $estadisticas['peso_minimo'] }} kg</p>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-ganaderasoft-negro">Registros</h3>
        </div>

        @if(count($pesosCorporales) === 0)
            <div class="p-8 text-center text-gray-500">No hay registros de peso para los filtros seleccionados.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Animal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Peso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Comentario</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($pesosCorporales as $peso)
                            @php
                                $pesoId = $peso['id_Peso'] ?? $peso['id'] ?? null;
                                $animalNombre = $peso['animal_nombre'] ?? ('Animal #'.($peso['peso_etapa_anid'] ?? 'N/A'));
                                $fechaPeso = $peso['Fecha_Peso'] ?? $peso['fecha_control'] ?? null;
                                $comentario = $peso['Comentario'] ?? $peso['observaciones'] ?? null;
                                $valorPeso = $peso['Peso'] ?? $peso['peso'] ?? null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $animalNombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $fechaPeso ? \Carbon\Carbon::parse($fechaPeso)->format('d/m/Y') : '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $valorPeso !== null ? number_format((float) $valorPeso, 2, ',', '.') : '-' }} kg</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $comentario ?: '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($pesoId)
                                        <form action="{{ route('peso-corporal.destroy', $pesoId) }}" method="POST" class="inline" onsubmit="return confirm('¿Desea eliminar este registro?')">
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