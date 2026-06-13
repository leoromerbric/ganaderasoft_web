@extends('layouts.authenticated')

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

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('peso-corporal.index') }}">
            <div class="flex flex-nowrap gap-3 items-end">
                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Finca</label>
                    <select id="filtroFinca" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todas las fincas</option>
                    </select>
                </div>
                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rebano</label>
                    <select id="filtroRebano" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos los rebanos</option>
                    </select>
                </div>
                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Animal</label>
                    <select name="animal_id" id="filtroAnimal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}"
                                    data-rebano-id="{{ $animal['rebano']['id_Rebano'] ?? ($animal['id_Rebano'] ?? '') }}"
                                    data-rebano-nombre="{{ $animal['rebano']['Nombre'] ?? '' }}"
                                    data-finca-id="{{ $animal['rebano']['id_Finca'] ?? '' }}"
                                    data-finca-nombre="{{ $animal['rebano']['finca']['Nombre'] ?? ('Finca #'.($animal['rebano']['id_Finca'] ?? '')) }}"
                                    {{ $animalId == $animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] ?? 'Animal #'.$animal['id_Animal'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-none">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                    <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div class="flex-none">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                    <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div class="flex-none flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">Filtrar</button>
                    <a href="{{ route('peso-corporal.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
                </div>
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
                            <tr data-animal-id="{{ $peso['peso_etapa_anid'] ?? '' }}">
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
    <script>
    (function(){
        var f=document.getElementById('filtroFinca'),r=document.getElementById('filtroRebano'),a=document.getElementById('filtroAnimal');
        if(!f||!r||!a)return;
        var opts=Array.prototype.slice.call(a.options).filter(function(o){return!!o.value;});
        var fM={},rM={};
        opts.forEach(function(o){var fi=o.dataset.fincaId,fn=o.dataset.fincaNombre,ri=o.dataset.rebanoId,rn=o.dataset.rebanoNombre;if(fi&&!fM[fi])fM[fi]=fn||'Finca #'+fi;if(ri&&!rM[ri])rM[ri]={n:rn||'Rebano #'+ri,f:fi};});
        Object.keys(fM).sort(function(a,b){return fM[a].localeCompare(fM[b]);}).forEach(function(id){var o=document.createElement('option');o.value=id;o.textContent=fM[id];f.appendChild(o);});
        Object.keys(rM).sort(function(a,b){return rM[a].n.localeCompare(rM[b].n);}).forEach(function(id){var o=document.createElement('option');o.value=id;o.textContent=rM[id].n;o.dataset.fincaId=rM[id].f;r.appendChild(o);});
        function cas(){
            var fv=f.value,rv=r.value;
            Array.prototype.forEach.call(r.options,function(o){if(o.value)o.hidden=!!(fv&&o.dataset.fincaId!==fv);});
            if(r.value&&r.options[r.selectedIndex]&&r.options[r.selectedIndex].hidden)r.value='';
            var rv2=r.value;
            opts.forEach(function(o){o.hidden=!!(fv&&o.dataset.fincaId!==fv)||!!(rv2&&o.dataset.rebanoId!==rv2);});
            if(a.value&&a.options[a.selectedIndex]&&a.options[a.selectedIndex].hidden)a.value='';
            var av=a.value;
            var rows=document.querySelectorAll('tbody tr[data-animal-id]');
            if(!fv&&!rv2&&!av){rows.forEach(function(row){row.style.display='';});return;}
            var allowed={};
            if(av){allowed[String(av)]=true;}
            else{opts.forEach(function(o){if(!o.hidden&&o.value)allowed[String(o.value)]=true;});}
            rows.forEach(function(row){row.style.display=allowed[String(row.dataset.animalId)]?'':'none';});
        }
        f.addEventListener('change',cas);r.addEventListener('change',cas);a.addEventListener('change',cas);
        if(a.value){var s=opts.find(function(o){return o.value===a.value;});if(s){f.value=s.dataset.fincaId||'';r.value=s.dataset.rebanoId||'';cas();}}
    })();
    </script>
@endsection