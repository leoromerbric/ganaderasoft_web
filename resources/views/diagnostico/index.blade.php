@extends('layouts.authenticated')

@section('title', 'Diagnósticos')

@section('content')
<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🏥 Diagnósticos</h2>
            <p class="text-gray-600 mt-1">Gestión de diagnósticos veterinarios de los animales</p>
        </div>
        <a href="{{ route('diagnostico.create') }}"
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
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6">
        <form method="GET" action="{{ route('diagnostico.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                    <select id="filtroFinca"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todas las fincas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                    <select id="filtroRebano"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los rebaños</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Animal</label>
                    <select name="animal_id" id="filtroAnimal"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los animales</option>
                        @foreach($animales as $animal)
                            @php
                                $aId = $animal['id'] ?? $animal['id_Animal'] ?? '';
                            @endphp
                            <option value="{{ $aId }}"
                                    data-rebano-id="{{ $animal['rebano']['id_Rebano'] ?? ($animal['id_Rebano'] ?? '') }}"
                                    data-rebano-nombre="{{ $animal['rebano']['Nombre'] ?? '' }}"
                                    data-finca-id="{{ $animal['rebano']['id_Finca'] ?? '' }}"
                                    data-finca-nombre="{{ $animal['rebano']['finca']['Nombre'] ?? ('Finca #'.($animal['rebano']['id_Finca'] ?? '')) }}"
                                    {{ $animalId == $aId ? 'selected' : '' }}>
                                {{ $animal['Nombre'] ?? 'Animal #'.$aId }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipo</label>
                    <input type="text" name="tipo" value="{{ $tipo }}" placeholder="Tipo de diagnóstico..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Desde</label>
                    <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Hasta</label>
                    <input type="date" name="fecha_fin" value="{{ $fechaFin }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
                <div class="flex gap-2 w-full">
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-ganaderasoft-celeste text-white rounded-xl hover:bg-ganaderasoft-azul font-medium transition-colors text-sm h-[42px] flex items-center justify-center">
                        Filtrar
                    </button>
                    <a href="{{ route('diagnostico.index') }}"
                       class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors text-sm h-[42px] flex items-center justify-center text-center">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if(count($diagnosticos) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($diagnosticos as $diagnostico)
                        @php
                            $id = $diagnostico['id'] ?? $diagnostico['diagnostico_id'] ?? null;
                            $animalId = $diagnostico['animal_id'] ?? $diagnostico['fk_etapa_animal_anid'] ?? data_get($diagnostico, 'etapa_animal.animal_id') ?? '';
                            $animalRefId = data_get($diagnostico, 'animal.id') ?? data_get($diagnostico, 'animal.id_Animal') ?? data_get($diagnostico, 'etapa_animal.animal.id') ?? $animalId;
                            $animalNombre = data_get($diagnostico, 'animal.Nombre') ?? data_get($diagnostico, 'animal.nombre') ?? data_get($diagnostico, 'etapa_animal.animal.nombre') ?? data_get($diagnostico, 'etapa_animal.animal.Nombre') ?? ('Animal #'.$animalId);
                            $tipo = $diagnostico['tipo'] ?? $diagnostico['diagnostico_tipo'] ?? '-';
                            $fecha = $diagnostico['fecha'] ?? $diagnostico['diagnostico_fecha'] ?? null;
                            $descripcion = $diagnostico['descripcion'] ?? $diagnostico['diagnostico_descripcion'] ?? '-';
                            
                            $sexoVal = data_get($diagnostico, 'animal.sexo') ?? data_get($diagnostico, 'animal.Sexo') ?? data_get($diagnostico, 'etapa_animal.animal.sexo') ?? data_get($diagnostico, 'etapa_animal.animal.Sexo') ?? 'H';
                            $isMacho = in_array(strtoupper((string)$sexoVal), ['M', 'MACHO', 'MASCULINO']);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors" data-animal-id="{{ $animalRefId }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 shrink-0 rounded-xl {{ $isMacho ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-lg">
                                        {{ $isMacho ? '🐂' : '🐄' }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                                        <p class="text-xs text-gray-400">ID: #{{ $animalRefId }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tipo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $descripcion }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="flex justify-center space-x-2">
                                    <!-- Botón de Ver Detalles -->
                                    <a href="{{ route('diagnostico.show', $id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                       title="Ver detalle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Botón de Editar -->
                                    <a href="{{ route('diagnostico.edit', $id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                       title="Editar registro">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Botón de Eliminar con Modal -->
                                    <form method="POST" action="{{ route('diagnostico.destroy', $id) }}" class="inline-block" id="form-delete-diagnostico-{{ $id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openGenericConfirmModal({
                                            formId: 'form-delete-diagnostico-{{ $id }}',
                                            intent: 'danger',
                                            title: 'Eliminar registro de diagnóstico',
                                            message: '¿Estás seguro de que deseas eliminar este diagnóstico? Esta acción no se puede deshacer.',
                                            confirmText: 'Sí, eliminar'
                                        })"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                           title="Eliminar registro">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
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
                <div class="text-6xl mb-4">🏥</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay diagnósticos registrados</h3>
                <p class="text-gray-500 mb-6">Comienza registrando el primer diagnóstico</p>
                <a href="{{ route('diagnostico.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    Nuevo diagnóstico
                </a>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

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