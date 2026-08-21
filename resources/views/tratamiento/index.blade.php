@extends('layouts.authenticated')

@section('title', 'Tratamientos')

@section('content')
<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">💊 Tratamientos</h2>
            <p class="text-gray-600 mt-1">Gestión de tratamientos veterinarios</p>
        </div>
        <a href="{{ route('tratamiento.create') }}"
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
        <form method="GET" action="{{ route('tratamiento.index') }}">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diagnostico / animal</label>
                    <select name="diagnostico_id" id="filtroAnimal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos</option>
                        @foreach($diagnosticos as $diag)
                            @php
                                $di  = $diag['id'] ?? $diag['diagnostico_id'] ?? '';
                                $anId= $diag['animal_id'] ?? $diag['fk_etapa_animal_anid'] ?? data_get($diag, 'etapa_animal.animal_id') ?? data_get($diag, 'animal.id') ?? data_get($diag, 'animal.id_Animal') ?? '';
                                $anNm= data_get($diag, 'animal.Nombre') ?? '';
                                $rId = data_get($diag, 'animal.rebano.id') ?? data_get($diag, 'animal.rebano.id_Rebano') ?? data_get($diag, 'animal.id_Rebano') ?? data_get($diag, 'animal.rebano_id') ?? '';
                                $rNm = data_get($diag, 'animal.rebano.Nombre') ?? '';
                                $fId = data_get($diag, 'animal.rebano.finca_id') ?? data_get($diag, 'animal.rebano.id_Finca') ?? '';
                                $fNm = data_get($diag, 'animal.rebano.finca.Nombre') ?? ('Finca #'.$fId);
                                $tip = $diag['tipo'] ?? $diag['diagnostico_tipo'] ?? '';
                            @endphp
                            <option value="{{ $di }}"
                                    data-animal-id="{{ $anId }}"
                                    data-rebano-id="{{ $rId }}"
                                    data-rebano-nombre="{{ $rNm }}"
                                    data-finca-id="{{ $fId }}"
                                    data-finca-nombre="{{ $fNm }}"
                                    {{ $diagnosticoId == $di ? 'selected' : '' }}>
                                {{ $anNm }} — {{ $tip }} (#{{ $di }})
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
                    <a href="{{ route('tratamiento.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if(count($tratamientos) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnóstico</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha inicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha fin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tratamientos as $tratamiento)
                        @php
                            $tId = $tratamiento['id'] ?? $tratamiento['tratamiento_id'] ?? null;
                            $tDiagId = $tratamiento['diagnostico_id'] ?? $tratamiento['tratamiento_diagnostico_id'] ?? '';
                            $tPlan = $tratamiento['plan'] ?? $tratamiento['tratamiento_plan'] ?? '-';
                            $tFechaIni = $tratamiento['fecha_ini'] ?? $tratamiento['tratamiento_fecha_ini'] ?? null;
                            $tFechaFin = $tratamiento['fecha_fin'] ?? $tratamiento['tratamiento_fecha_fin'] ?? null;
                            $tDiagTipo = data_get($tratamiento, 'diagnostico.tipo') ?? data_get($tratamiento, 'diagnostico.diagnostico_tipo') ?? ($tDiagId ? 'Diag. #'.$tDiagId : '-');
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors" data-diag-id="{{ $tDiagId }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $tId ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $tDiagTipo }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $tPlan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $tFechaIni ? date('d/m/Y', strtotime($tFechaIni)) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $tFechaFin ? date('d/m/Y', strtotime($tFechaFin)) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('tratamiento.show', $tId) }}"
                                       class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">Ver</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('tratamiento.edit', $tId) }}"
                                       class="text-ganaderasoft-verde hover:text-green-700">Editar</a>
                                    <span class="text-gray-300">|</span>
                                    <form method="POST" action="{{ route('tratamiento.destroy', $tId) }}" class="inline"
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
                <div class="text-6xl mb-4">💊</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay tratamientos registrados</h3>
                <p class="text-gray-500 mb-6">Comienza registrando el primer tratamiento</p>
                <a href="{{ route('tratamiento.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    Nuevo tratamiento
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
            var rows=document.querySelectorAll('tbody tr[data-diag-id]');
            if(!fv&&!rv2&&!av){rows.forEach(function(row){row.style.display='';});return;}
            var allowed={};
            if(av){allowed[String(av)]=true;}
            else{opts.forEach(function(o){if(!o.hidden&&o.value)allowed[String(o.value)]=true;});}
            rows.forEach(function(row){row.style.display=allowed[String(row.dataset.diagId)]?'':'none';});
        }
        f.addEventListener('change',cas);r.addEventListener('change',cas);a.addEventListener('change',cas);
        if(a.value){var s=opts.find(function(o){return o.value===a.value;});if(s){f.value=s.dataset.fincaId||'';r.value=s.dataset.rebanoId||'';cas();}}
    })();
    </script>
@endsection