@extends('layouts.authenticated')

@section('title', 'Registro de Producción de Leche')

@section('content')
    <div>
        <!-- Page Title and Actions -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-ganaderasoft-negro">Registro de Producción de Leche</h2>
                <p class="text-gray-600 mt-1">Gestiona los registros diarios de producción lechera</p>
            </div>
            <a href="{{ route('leche.create', ['lactancia_id' => $lactanciaId]) }}" 
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                Nuevo Registro
            </a>
        </div>

        <!-- Success/Error Messages -->
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Animal / Periodo</label>
                    <select name="lactancia_id" id="filtroAnimal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos los periodos</option>
                        @foreach($lactancias as $lact)
                            @php
                                $li   = $lact['lactancia_id'] ?? '';
                                $anId = $lact['lactancia_etapa_anid'] ?? '';
                                $anNm = $lact['animal']['Nombre'] ?? ($lact['animal_nombre'] ?? '');
                                $rId  = $lact['animal']['rebano']['id_Rebano'] ?? ($lact['animal']['id_Rebano'] ?? '');
                                $rNm  = $lact['animal']['rebano']['Nombre'] ?? '';
                                $fId  = $lact['animal']['rebano']['id_Finca'] ?? '';
                                $fNm  = $lact['animal']['rebano']['finca']['Nombre'] ?? ('Finca #'.$fId);
                                $fi   = isset($lact['lactancia_fecha_inicio']) ? date('d/m/Y', strtotime($lact['lactancia_fecha_inicio'])) : '?';
                                $ff   = $lact['Lactancia_fecha_fin'] ? date('d/m/Y', strtotime($lact['Lactancia_fecha_fin'])) : 'en curso';
                            @endphp
                            <option value="{{ $li }}"
                                    data-animal-id="{{ $anId }}"
                                    data-rebano-id="{{ $rId }}"
                                    data-rebano-nombre="{{ $rNm }}"
                                    data-finca-id="{{ $fId }}"
                                    data-finca-nombre="{{ $fNm }}"
                                    {{ (string)$lactanciaId === (string)$li ? 'selected' : '' }}>
                                {{ $anNm }} — {{ $fi }} / {{ $ff }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-none flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors">Filtrar</button>
                    <a href="{{ route('leche.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Limpiar</a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        @if(count($registrosLeche) > 0)
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $totalProduccion = array_sum(array_column($registrosLeche, 'leche_pesaje_Total'));
                    $registrosCount = count($registrosLeche);
                    $promedioDiario = $registrosCount > 0 ? $totalProduccion / $registrosCount : 0;
                @endphp
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-azul">{{ $registrosCount }}</div>
                    <div class="text-sm text-gray-600">Registros</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-verde">{{ number_format($totalProduccion, 2) }} L</div>
                    <div class="text-sm text-gray-600">Total Producido</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-celeste">{{ number_format($promedioDiario, 2) }} L</div>
                    <div class="text-sm text-gray-600">Promedio por Registro</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Milk Production Records List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if(count($registrosLeche) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Pesaje</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad (Litros)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lactancia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($registrosLeche as $registro)
                                <tr class="hover:bg-gray-50 transition-colors" data-lactancia-id="{{ $registro['leche_lactancia_id'] ?? '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ date('d/m/Y', strtotime($registro['leche_fecha_pesaje'])) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="font-semibold text-ganaderasoft-verde">{{ number_format($registro['leche_pesaje_Total'], 2) }} L</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $registro['animal_nombre'] ?? 'Animal no disponible' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $registro['leche_lactancia_id'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('leche.show', $registro['leche_id']) }}" 
                                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">
                                                Ver
                                            </a>
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('leche.edit', $registro['leche_id']) }}" 
                                               class="text-ganaderasoft-verde hover:text-green-700">
                                                Editar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">🥛</div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay registros de leche</h3>
                    <p class="text-gray-500 mb-6">
                        @if($lactanciaId)
                            No hay registros para este período de lactancia
                        @else
                            Comienza registrando la primera producción lechera
                        @endif
                    </p>
                    <div class="space-x-4">
                        @if(!$lactanciaId)
                            <a href="{{ route('lactancia.index') }}" 
                               class="inline-block px-6 py-3 bg-ganaderasoft-azul text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 mr-2">
                                Ver Lactancias
                            </a>
                        @endif
                        <a href="{{ route('leche.create', ['lactancia_id' => $lactanciaId]) }}" 
                           class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                            Nuevo Registro
                        </a>
                    </div>
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
            var rows=document.querySelectorAll('tbody tr[data-lactancia-id]');
            if(!fv&&!rv2&&!av){rows.forEach(function(row){row.style.display='';});return;}
            var allowed={};
            if(av){allowed[String(av)]=true;}
            else{opts.forEach(function(o){if(!o.hidden&&o.value)allowed[String(o.value)]=true;});}
            rows.forEach(function(row){row.style.display=allowed[String(row.dataset.lactanciaId)]?'':'none';});
        }
        f.addEventListener('change',cas);r.addEventListener('change',cas);a.addEventListener('change',cas);
        if(a.value){var s=opts.find(function(o){return o.value===a.value;});if(s){f.value=s.dataset.fincaId||'';r.value=s.dataset.rebanoId||'';cas();}}
    })();
    </script>
@endsection