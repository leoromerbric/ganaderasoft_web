@extends('layouts.authenticated')

@section('title', 'Detalle de medidas e índices corporales')

@section('content')
@php
    $medidaId = data_get($medidaCorporal, 'id');
    $animalId = data_get($medidaCorporal, 'animal_id') 
        ?? data_get($medidaCorporal, 'animal.id')
        ?? data_get($medidaCorporal, 'etapa_animal.animal_id')
        ?? data_get($indicesData, 'animal.id');
    
    $animalNombre = data_get($medidaCorporal, 'animal.nombre')
        ?? data_get($indicesData, 'animal.nombre')
        ?? ($animalId ? ('Animal #'.$animalId) : 'Animal no disponible');

    $animalCodigo = data_get($medidaCorporal, 'animal.codigo_animal')
        ?? data_get($indicesData, 'animal.codigo_animal')
        ?? '';

    $alturaHc    = (float) data_get($medidaCorporal, 'altura_hc', 0);
    $alturaHg    = (float) data_get($medidaCorporal, 'altura_hg', 0);
    $perimetroPt = (float) data_get($medidaCorporal, 'perimetro_pt', 0);
    $perimetroPca= (float) data_get($medidaCorporal, 'perimetro_pca', 0);
    $longitudLc  = (float) data_get($medidaCorporal, 'longitud_lc', 0);
    $longitudLg  = (float) data_get($medidaCorporal, 'longitud_lg', 0);
    $anchuraAg   = (float) data_get($medidaCorporal, 'anchura_ag', 0);

    $indices = data_get($indicesData, 'indices', []);
    $interpretacion = data_get($indicesData, 'interpretacion', []);
    $biotipo = data_get($interpretacion, 'biotipo');
    $biotipoDesc = data_get($interpretacion, 'biotipo_descripcion');
    $pelvisConformacion = data_get($interpretacion, 'pelvis_conformacion');
    $esqueletoTipo = data_get($interpretacion, 'esqueleto_tipo');
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                📐
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Medidas e índices corporales #{{ $medidaId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Morfometría física y cálculo automático de índices zoométricos del animal</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('medidas-corporales.edit', $medidaId) }}"
               class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Editar
            </a>
            <a href="{{ route('medidas-corporales.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Seccion de Índices Zoométricos Calculados On-The-Fly -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
            <div>
                <h2 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    <span>🧬</span> Índices zoométricos (cálculo dinámico)
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Indicadores morfológicos calculados automáticamente a partir de las 7 medidas corporales</p>
            </div>
            @if($biotipo)
                <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold shadow-xs
                    {{ $biotipo === 'Brevilíneo' ? 'bg-amber-100 text-amber-900 border border-amber-200' : '' }}
                    {{ $biotipo === 'Mediolíneo' ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : '' }}
                    {{ $biotipo === 'Longilíneo' ? 'bg-blue-100 text-blue-900 border border-blue-200' : '' }}">
                    <span>Biotipo:</span>
                    <span class="text-sm font-black">{{ $biotipo }}</span>
                </div>
            @endif
        </div>

        @if($biotipoDesc)
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 flex items-start space-x-3">
                <span class="text-lg">💡</span>
                <div>
                    <span class="font-bold">Interpretación conformacional:</span> {{ $biotipoDesc }}
                    @if($pelvisConformacion)
                        <span class="mx-2">•</span> <span class="font-bold">Pelvis:</span> {{ $pelvisConformacion }}
                    @endif
                    @if($esqueletoTipo)
                        <span class="mx-2">•</span> <span class="font-bold">Estructura ósea:</span> {{ $esqueletoTipo }}
                    @endif
                </div>
            </div>
        @endif

        <!-- Grid de los 7 Índices -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Anamorfosis -->
            @php $ia = data_get($indices, 'anamorfosis.valor'); @endphp
            <div class="p-4 bg-gradient-to-br from-purple-50/70 to-purple-100/40 border border-purple-100 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-purple-900 uppercase tracking-wider">Anamorfosis (ia)</span>
                    <span class="text-xs font-mono text-purple-600 bg-purple-100 px-2 py-0.5 rounded-md">Pt² / hc</span>
                </div>
                <p class="text-2xl font-black text-purple-800">
                    {{ $ia !== null ? number_format($ia, 2) : 'N/D' }}
                </p>
                <p class="text-[11px] text-purple-700 leading-tight">Compacidad toraco-abdominal en relación a la alzada.</p>
            </div>

            <!-- 2. Corporal -->
            @php $ic = data_get($indices, 'corporal.valor'); $icClasif = data_get($indices, 'corporal.clasificacion'); @endphp
            <div class="p-4 bg-gradient-to-br from-emerald-50/70 to-emerald-100/40 border border-emerald-100 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-900 uppercase tracking-wider">Corporal (ic)</span>
                    <span class="text-xs font-mono text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-md">(Lc/pt)*100</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <p class="text-2xl font-black text-emerald-800">
                        {{ $ic !== null ? number_format($ic, 2) : 'N/D' }}
                    </p>
                    @if($icClasif)
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-200 text-emerald-900 rounded-lg">
                            {{ $icClasif }}
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-emerald-700 leading-tight">&Lt;85 brevilíneo, 85-90 mediolíneo, &gt;90 longilíneo.</p>
            </div>

            <!-- 3. Pelviano -->
            @php $ip = data_get($indices, 'pelviano.valor'); $ipClasif = data_get($indices, 'pelviano.clasificacion'); @endphp
            <div class="p-4 bg-gradient-to-br from-pink-50/70 to-pink-100/40 border border-pink-100 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-pink-900 uppercase tracking-wider">Pelviano (ip)</span>
                    <span class="text-xs font-mono text-pink-600 bg-pink-100 px-2 py-0.5 rounded-md">(Ag/lg)*100</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <p class="text-2xl font-black text-pink-800">
                        {{ $ip !== null ? number_format($ip, 2) : 'N/D' }}
                    </p>
                    @if($ipClasif)
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-pink-200 text-pink-900 rounded-lg">
                            {{ $ipClasif }}
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-pink-700 leading-tight">Amplitud pélvica y aptitud materna de parto.</p>
            </div>

            <!-- 4. Proporcionalidad -->
            @php $ipr = data_get($indices, 'proporcionalidad.valor'); @endphp
            <div class="p-4 bg-gradient-to-br from-sky-50/70 to-sky-100/40 border border-sky-100 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-sky-900 uppercase tracking-wider">Proporcionalidad (ipr)</span>
                    <span class="text-xs font-mono text-sky-600 bg-sky-100 px-2 py-0.5 rounded-md">(Hc/lc)*100</span>
                </div>
                <p class="text-2xl font-black text-sky-800">
                    {{ $ipr !== null ? number_format($ipr, 2) : 'N/D' }}
                </p>
                <p class="text-[11px] text-sky-700 leading-tight">Relación entre la alzada y longitud del tronco.</p>
            </div>

            <!-- 5. Dáctilo-Torácico -->
            @php $idt = data_get($indices, 'dactilo_toracico.valor'); $idtClasif = data_get($indices, 'dactilo_toracico.clasificacion'); @endphp
            <div class="p-4 bg-gradient-to-br from-amber-50/70 to-amber-100/40 border border-amber-100 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-900 uppercase tracking-wider">Dáctilo-torácico (idt)</span>
                    <span class="text-xs font-mono text-amber-600 bg-amber-100 px-2 py-0.5 rounded-md">(Pca/pt)*100</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <p class="text-2xl font-black text-amber-800">
                        {{ $idt !== null ? number_format($idt, 2) : 'N/D' }}
                    </p>
                    @if($idtClasif)
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-amber-200 text-amber-900 rounded-lg">
                            {{ $idtClasif }}
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-amber-700 leading-tight">Fortaleza ósea respecto a la masa torácica.</p>
            </div>

            <!-- 6. Pelviano Transversal -->
            @php $ipt = data_get($indices, 'pelviano_transversal.valor'); @endphp
            <div class="p-4 bg-gradient-to-br from-indigo-50/70 to-indigo-100/40 border border-indigo-100 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-indigo-900 uppercase tracking-wider">Pelviano transv. (Ipt)</span>
                    <span class="text-xs font-mono text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-md">(Ag/hc)*100</span>
                </div>
                <p class="text-2xl font-black text-indigo-800">
                    {{ $ipt !== null ? number_format($ipt, 2) : 'N/D' }}
                </p>
                <p class="text-[11px] text-indigo-700 leading-tight">Amplitud de la grupa relativa a la alzada.</p>
            </div>

            <!-- 7. Pelviano Longitudinal -->
            @php $ipl = data_get($indices, 'pelviano_longitudinal.valor'); @endphp
            <div class="p-4 bg-gradient-to-br from-teal-50/70 to-teal-100/40 border border-teal-100 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-teal-900 uppercase tracking-wider">Pelviano longit. (Ipl)</span>
                    <span class="text-xs font-mono text-teal-600 bg-teal-100 px-2 py-0.5 rounded-md">(Lg/hc)*100</span>
                </div>
                <p class="text-2xl font-black text-teal-800">
                    {{ $ipl !== null ? number_format($ipl, 2) : 'N/D' }}
                </p>
                <p class="text-[11px] text-teal-700 leading-tight">Longitud de la grupa relativa a la alzada.</p>
            </div>

            <!-- Resumen de Cobertura -->
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-2xl flex flex-col justify-center items-center text-center space-y-1">
                <span class="text-xs font-bold text-gray-500 uppercase">Índices procesados</span>
                <p class="text-3xl font-black text-ganaderasoft-negro">
                    {{ data_get($interpretacion, 'total_calculados', 0) }} / 7
                </p>
                <span class="text-[10px] text-gray-400">100% Dinámico</span>
            </div>
        </div>
    </div>

    <!-- Content Grid (Medidas Físicas) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Detalles de Mediciones Físicas (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Alturas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>📏</span> Alturas
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl">
                        <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1">Altura a la cruz (hc)</p>
                        <p class="text-2xl font-black text-emerald-700">
                            {{ $alturaHc > 0 ? number_format($alturaHc, 1).' cm' : 'No registrada' }}
                        </p>
                    </div>

                    <div class="p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl">
                        <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1">Altura a la grupa (hg)</p>
                        <p class="text-2xl font-black text-emerald-700">
                            {{ $alturaHg > 0 ? number_format($alturaHg, 1).' cm' : 'No registrada' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Perímetros -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>⭕</span> Perímetros
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-2xl">
                        <p class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-1">Perímetro torácico (pt)</p>
                        <p class="text-2xl font-black text-purple-700">
                            {{ $perimetroPt > 0 ? number_format($perimetroPt, 1).' cm' : 'No registrado' }}
                        </p>
                    </div>

                    <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-2xl">
                        <p class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-1">Perímetro de caña (pca)</p>
                        <p class="text-2xl font-black text-purple-700">
                            {{ $perimetroPca > 0 ? number_format($perimetroPca, 1).' cm' : 'No registrado' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Longitudes y Anchura -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>📐</span> Longitudes y anchura
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-cyan-50/60 border border-cyan-100 rounded-2xl">
                        <p class="text-xs font-bold text-cyan-900 uppercase tracking-wider mb-1">Longitud corporal (lc)</p>
                        <p class="text-xl font-black text-cyan-700">
                            {{ $longitudLc > 0 ? number_format($longitudLc, 1).' cm' : 'N/R' }}
                        </p>
                    </div>

                    <div class="p-4 bg-cyan-50/60 border border-cyan-100 rounded-2xl">
                        <p class="text-xs font-bold text-cyan-900 uppercase tracking-wider mb-1">Longitud de grupa (lg)</p>
                        <p class="text-xl font-black text-cyan-700">
                            {{ $longitudLg > 0 ? number_format($longitudLg, 1).' cm' : 'N/R' }}
                        </p>
                    </div>

                    <div class="p-4 bg-cyan-50/60 border border-cyan-100 rounded-2xl">
                        <p class="text-xs font-bold text-cyan-900 uppercase tracking-wider mb-1">Anchura de grupa (ag)</p>
                        <p class="text-xl font-black text-cyan-700">
                            {{ $anchuraAg > 0 ? number_format($anchuraAg, 1).' cm' : 'N/R' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Animal Evaluado -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🐄</span> Animal evaluado
                </h3>

                <div class="p-5 bg-pink-50/60 border border-pink-100 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-white border border-pink-200 text-pink-700 font-bold flex items-center justify-center text-2xl shadow-xs">
                            🐄
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $animalNombre }}</p>
                            @if($animalCodigo)
                                <p class="text-xs text-gray-500 font-mono mt-0.5">Código: #{{ $animalCodigo }}</p>
                            @endif
                        </div>
                    </div>

                    @if($animalId)
                        <div>
                            <a href="{{ route('animales.show', $animalId) }}" 
                                class="px-4 py-2 bg-pink-100 hover:bg-pink-200 text-pink-800 font-bold rounded-xl text-xs transition-colors">
                                Ver animal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Panel de Metadatos (1 Tercio) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Información del registro
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-3 text-xs text-gray-600">
                        <div class="flex justify-between">
                            <span>ID medida:</span>
                            <span class="font-bold text-gray-900 font-mono">#{{ $medidaId }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Fecha de toma:</span>
                            <span class="font-semibold text-gray-800">{{ isset($medidaCorporal['created_at']) ? date('d/m/Y H:i', strtotime($medidaCorporal['created_at'])) : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Última actualización:</span>
                            <span class="font-semibold text-gray-800">{{ isset($medidaCorporal['updated_at']) ? date('d/m/Y H:i', strtotime($medidaCorporal['updated_at'])) : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
