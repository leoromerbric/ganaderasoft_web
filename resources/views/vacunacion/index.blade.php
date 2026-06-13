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

<div class="mb-6 bg-white rounded-xl shadow-md p-6">
    <form method="GET" action="{{ route('vacunacion.index') }}">
        <div class="flex flex-nowrap gap-3 items-end">
            <div class="flex-1 min-w-0">
                <label class="block text-sm font-medium text-gray-700 mb-2">Finca</label>
                <select id="filtroFinca" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todas las fincas</option>
                </select>
            </div>
            <div class="flex-1 min-w-0">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rebano</label>
                <select name="rebano_id" id="filtroAnimal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todos los rebanos</option>
                        @foreach($rebanos as $rebano)
                            <option value="{{ $rebano['id_Rebano'] ?? '' }}"
                                    data-finca-id="{{ $rebano['id_Finca'] ?? '' }}"
                                    {{ ($filters['rebano_id'] ?? '') == ($rebano['id_Rebano'] ?? '') ? 'selected' : '' }}>
                                {{ $rebano['Nombre'] ?? ('Rebano #'.($rebano['id_Rebano'] ?? '')) }}
                            </option>
                        @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-0">
                <label class="block text-sm font-medium text-gray-700 mb-2">Vacuna</label>
                <select name="vacuna_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todas</option>
                        @foreach($vacunas as $vac)
                            <option value="{{ $vac['vacuna_id'] ?? '' }}" {{ ($filters['vacuna_id'] ?? '') == ($vac['vacuna_id'] ?? '') ? 'selected' : '' }}>
                                {{ $vac['vacuna_nombre'] ?? 'Vacuna' }}
                            </option>
                        @endforeach
                </select>
            </div>
            <div class="flex-none">
                <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                <input type="date" name="fecha_inicio" value="{{ $filters['fecha_inicio'] ?? '' }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
            </div>
            <div class="flex-none">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                <input type="date" name="fecha_fin" value="{{ $filters['fecha_fin'] ?? '' }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
            </div>
            <div class="flex-none flex gap-2">
                <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">Filtrar</button>
                <a href="{{ route('vacunacion.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
            </div>
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
    <script>
    (function(){
        var f=document.getElementById('filtroFinca'),r=document.getElementById('filtroAnimal');
        if(!f||!r)return;
        var rebOpts=Array.prototype.slice.call(r.options).filter(function(o){return!!o.value;});
        var fM={};
        rebOpts.forEach(function(o){var fi=o.dataset.fincaId;if(fi&&!fM[fi])fM[fi]='Finca #'+fi;});
        Object.keys(fM).sort().forEach(function(id){var o=document.createElement('option');o.value=id;o.textContent=fM[id];f.appendChild(o);});
        f.addEventListener('change',function(){var fv=f.value;rebOpts.forEach(function(o){o.hidden=!!(fv&&o.dataset.fincaId!==fv);});if(r.value&&r.options[r.selectedIndex]&&r.options[r.selectedIndex].hidden)r.value='';});
        if(r.value){var s=rebOpts.find(function(o){return o.value===r.value;});if(s&&s.dataset.fincaId)f.value=s.dataset.fincaId;}
    })();
    </script>
@endsection