@extends('layouts.authenticated')

@section('title', 'Registro de Celo')

@section('content')
<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🌡️ Registro de Celo</h2>
            <p class="text-gray-600 mt-1">Gestión de registros de celo de los animales</p>
        </div>
        <a href="{{ route('registro-celo.create') }}"
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
        <form method="GET" action="{{ route('registro-celo.index') }}">
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
                    <a href="{{ route('registro-celo.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if(count($registros) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Celo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($registros as $registro)
                        <tr class="hover:bg-gray-50 transition-colors" data-animal-id="{{ $registro['animal']['id_Animal'] ?? $registro['celo_etapa_anid'] ?? '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $registro['celo_id'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $registro['animal']['Nombre'] ?? ('Animal #'.($registro['celo_etapa_anid'] ?? 'N/A')) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ isset($registro['celo_fecha']) ? date('d/m/Y', strtotime($registro['celo_fecha'])) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $registro['celo_observacon'] ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('registro-celo.show', $registro['celo_id']) }}"
                                       class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">Ver</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('registro-celo.edit', $registro['celo_id']) }}"
                                       class="text-ganaderasoft-verde hover:text-green-700">Editar</a>
                                    <span class="text-gray-300">|</span>
                                    <form method="POST" action="{{ route('registro-celo.destroy', $registro['celo_id']) }}" class="inline"
                                          onsubmit="return confirm('¿Eliminar este registro?')">
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
                <div class="text-6xl mb-4">🌡️</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay registros de celo</h3>
                <p class="text-gray-500 mb-6">Comienza registrando el primer evento de celo</p>
                <a href="{{ route('registro-celo.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    Nuevo Registro
                </a>
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