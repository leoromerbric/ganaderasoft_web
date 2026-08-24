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

    $animalSexo = data_get($medidaCorporal, 'animal.sexo')
        ?? data_get($indicesData, 'animal.sexo')
        ?? '';

    $alturaHc    = (float) data_get($medidaCorporal, 'altura_hc', 0);
    $alturaHg    = (float) data_get($medidaCorporal, 'altura_hg', 0);
    $perimetroPt = (float) data_get($medidaCorporal, 'perimetro_pt', 0);
    $perimetroPca= (float) data_get($medidaCorporal, 'perimetro_pca', 0);
    $longitudLc  = (float) data_get($medidaCorporal, 'longitud_lc', 0);
    $longitudLg  = (float) data_get($medidaCorporal, 'longitud_lg', 0);
    $anchuraAg   = (float) data_get($medidaCorporal, 'anchura_ag', 0);

    $fechaMedicion = data_get($medidaCorporal, 'fecha_medicion') 
        ?? data_get($medidaCorporal, 'created_at') 
        ?? data_get($medidaCorporal, 'fecha')
        ?? data_get($indicesData, 'created_at');
    $observaciones = data_get($medidaCorporal, 'observaciones')
        ?? data_get($medidaCorporal, 'comentario');

    $indices = data_get($indicesData, 'indices', []);
    $interpretacion = data_get($indicesData, 'interpretacion', []);
    $biotipo = data_get($interpretacion, 'biotipo');
    $biotipoDesc = data_get($interpretacion, 'biotipo_descripcion');
    $pelvisConformacion = data_get($interpretacion, 'pelvis_conformacion');
    $esqueletoTipo = data_get($interpretacion, 'esqueleto_tipo');
@endphp

<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-xs">
                📐
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Medidas corporales #{{ $medidaId ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Evaluación biométrica de <span class="font-bold text-gray-800">{{ $animalNombre }}</span>
                    @if($animalCodigo)
                        <span class="font-mono text-gray-500">(#{{ $animalCodigo }})</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($medidaId)
                <a href="{{ route('medidas-corporales.edit', $medidaId) }}"
                   class="px-6 py-3 bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar medición
                </a>
            @endif
            <a href="{{ route('medidas-corporales.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Medidas e Índices (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card 1: Dimensiones Morfométricas Directas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>📏</span> Dimensiones corporales evaluadas
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Mediciones biométricas registradas en campo</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Hc -->
                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Altura cruz (Hc)</span>
                        <p class="text-2xl font-black text-gray-900">{{ $alturaHc > 0 ? number_format($alturaHc, 1).' cm' : 'Sin registro' }}</p>
                    </div>

                    <!-- Hg -->
                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Altura grupa (Hg)</span>
                        <p class="text-2xl font-black text-gray-900">{{ $alturaHg > 0 ? number_format($alturaHg, 1).' cm' : 'Sin registro' }}</p>
                    </div>

                    <!-- Pt -->
                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Perímetro tórax (Pt)</span>
                        <p class="text-2xl font-black text-gray-900">{{ $perimetroPt > 0 ? number_format($perimetroPt, 1).' cm' : 'Sin registro' }}</p>
                    </div>

                    <!-- Pca -->
                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Perímetro caña (Pca)</span>
                        <p class="text-2xl font-black text-gray-900">{{ $perimetroPca > 0 ? number_format($perimetroPca, 1).' cm' : 'Sin registro' }}</p>
                    </div>

                    <!-- Lc -->
                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Longitud cuerpo (Lc)</span>
                        <p class="text-2xl font-black text-gray-900">{{ $longitudLc > 0 ? number_format($longitudLc, 1).' cm' : 'Sin registro' }}</p>
                    </div>

                    <!-- Lg -->
                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Longitud grupa (Lg)</span>
                        <p class="text-2xl font-black text-gray-900">{{ $longitudLg > 0 ? number_format($longitudLg, 1).' cm' : 'Sin registro' }}</p>
                    </div>

                    <!-- Ag -->
                    <div class="p-5 bg-gray-50/90 rounded-2xl border border-gray-200/80 space-y-1.5 shadow-xs">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Ancho de grupa (Ag)</span>
                        <p class="text-2xl font-black text-gray-900">{{ $anchuraAg > 0 ? number_format($anchuraAg, 1).' cm' : 'Sin registro' }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Índices Zoométricos Dinámicos -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>🧬</span> Índices zoométricos
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Indicadores morfológicos calculados automáticamente a partir de las dimensiones físicas</p>
                    </div>
                    @if($biotipo)
                        <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full text-xs font-bold shadow-xs
                            {{ $biotipo === 'Brevilíneo' ? 'bg-amber-50 text-amber-900 border border-amber-200' : '' }}
                            {{ $biotipo === 'Mediolíneo' ? 'bg-emerald-50 text-emerald-900 border border-emerald-200' : '' }}
                            {{ $biotipo === 'Longilíneo' ? 'bg-blue-50 text-blue-900 border border-blue-200' : '' }}">
                            <span>Biotipo:</span>
                            <span class="text-sm font-black">{{ $biotipo }}</span>
                        </div>
                    @endif
                </div>

                @if($biotipoDesc)
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 leading-relaxed flex items-start space-x-3">
                        <span class="text-xl leading-none mt-0.5">💡</span>
                        <div>
                            <span class="font-bold text-slate-900">Interpretación biotipológica:</span> {{ $biotipoDesc }}
                        </div>
                    </div>
                @endif

                <!-- Grid de Índices -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- 1. Anamorfosis -->
                    @php $ia = data_get($indices, 'anamorfosis.valor'); @endphp
                    <div class="p-5 bg-purple-50/70 border border-purple-100 rounded-2xl space-y-2.5 shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-purple-900 uppercase tracking-wider">Anamorfosis (Ia)</span>
                            <span class="text-xs font-mono text-purple-700 bg-purple-100/90 px-2.5 py-1 rounded-lg font-bold">Pt² / Hc</span>
                        </div>
                        <p class="text-3xl font-black text-purple-900">
                            {{ $ia !== null ? number_format($ia, 2) : 'N/D' }}
                        </p>
                        <p class="text-xs text-purple-800 leading-normal font-medium">Compacidad toraco-abdominal en relación a la alzada.</p>
                    </div>

                    <!-- 2. Corporal -->
                    @php $ic = data_get($indices, 'corporal.valor'); $icClasif = data_get($indices, 'corporal.clasificacion'); @endphp
                    <div class="p-5 bg-emerald-50/70 border border-emerald-100 rounded-2xl space-y-2.5 shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-900 uppercase tracking-wider">Corporal (Ic)</span>
                            <span class="text-xs font-mono text-emerald-700 bg-emerald-100/90 px-2.5 py-1 rounded-lg font-bold">(Lc / Pt) * 100</span>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-3xl font-black text-emerald-900">
                                {{ $ic !== null ? number_format($ic, 2) : 'N/D' }}
                            </p>
                            @if($icClasif)
                                <span class="text-xs font-bold px-3 py-1 bg-emerald-200 text-emerald-900 rounded-full border border-emerald-300">
                                    {{ $icClasif }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-emerald-800 leading-normal font-medium">Formato corporal (&lt;85 brevilíneo, 85-90 mediolíneo, &gt;90 longilíneo).</p>
                    </div>

                    <!-- 3. Pelviano -->
                    @php $ip = data_get($indices, 'pelviano.valor'); $ipClasif = data_get($indices, 'pelviano.clasificacion'); @endphp
                    <div class="p-5 bg-pink-50/70 border border-pink-100 rounded-2xl space-y-2.5 shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-pink-900 uppercase tracking-wider">Pelviano (Ip)</span>
                            <span class="text-xs font-mono text-pink-700 bg-pink-100/90 px-2.5 py-1 rounded-lg font-bold">(Ag / Lg) * 100</span>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-3xl font-black text-pink-900">
                                {{ $ip !== null ? number_format($ip, 2) : 'N/D' }}
                            </p>
                            @if($ipClasif)
                                <span class="text-xs font-bold px-3 py-1 bg-pink-200 text-pink-900 rounded-full border border-pink-300">
                                    {{ $ipClasif }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-pink-800 leading-normal font-medium">Conformación pelviana y amplitud de la grupa.</p>
                    </div>

                    <!-- 4. Dáctilo-Torácico -->
                    @php $idt = data_get($indices, 'dactilo_toracico.valor'); $idtClasif = data_get($indices, 'dactilo_toracico.clasificacion'); @endphp
                    <div class="p-5 bg-blue-50/70 border border-blue-100 rounded-2xl space-y-2.5 shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-blue-900 uppercase tracking-wider">Dáctilo-Torácico (Idt)</span>
                            <span class="text-xs font-mono text-blue-700 bg-blue-100/90 px-2.5 py-1 rounded-lg font-bold">(Pca / Pt) * 100</span>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-3xl font-black text-blue-900">
                                {{ $idt !== null ? number_format($idt, 2) : 'N/D' }}
                            </p>
                            @if($idtClasif)
                                <span class="text-xs font-bold px-3 py-1 bg-blue-200 text-blue-900 rounded-full border border-blue-300">
                                    {{ $idtClasif }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-blue-800 leading-normal font-medium">Desarrollo del esqueleto y grosor de extremidades.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas Laterales (1 Tercio) -->
        <div class="space-y-6">
            <!-- Card 1: Ficha del Ejemplar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>🐄</span> Ejemplar evaluado
                    </h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl {{ $animalSexo === 'M' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} font-bold flex items-center justify-center text-3xl shrink-0 shadow-xs">
                            {{ $animalSexo === 'M' ? '🐂' : '🐄' }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-lg font-bold text-gray-900 truncate">{{ $animalNombre }}</p>
                            <p class="text-xs text-gray-500 font-mono font-semibold mt-0.5">
                                {{ $animalCodigo ? 'Código: #'.$animalCodigo : 'ID: #'.$animalId }}
                            </p>
                        </div>
                    </div>

                    @if($animalId)
                        <div class="pt-1">
                            <a href="{{ route('animales.show', $animalId) }}"
                               class="w-full py-3 px-4 bg-white hover:bg-gray-100 border border-gray-300 text-gray-800 font-semibold rounded-xl text-sm flex items-center justify-center gap-2 transition-all shadow-xs hover:shadow-sm">
                                <svg class="w-4 h-4 text-ganaderasoft-azul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver expediente del animal
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 2: Diagnóstico Conformacional -->
            @if($biotipo || $pelvisConformacion || $esqueletoTipo)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-ganaderasoft-verde-oscuro text-white px-6 py-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <span>🎯</span> Diagnóstico conformacional
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($biotipo)
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1.5">Biotipo morfológico</span>
                        <span class="inline-flex px-3.5 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                            {{ $biotipo }}
                        </span>
                    </div>
                    @endif
                    @if($pelvisConformacion)
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1.5">Conformación de pelvis</span>
                        <span class="inline-flex px-3.5 py-1 text-xs font-bold rounded-full bg-purple-50 text-purple-800 border border-purple-200">
                            {{ $pelvisConformacion }}
                        </span>
                    </div>
                    @endif
                    @if($esqueletoTipo)
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1.5">Estructura ósea</span>
                        <span class="inline-flex px-3.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                            {{ $esqueletoTipo }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Card 3: Metadatos del Registro -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>📝</span> Datos del registro
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Fecha de evaluación</span>
                        <p class="text-sm font-bold text-gray-900">
                            {{ $fechaMedicion ? \Carbon\Carbon::parse($fechaMedicion)->format('d/m/Y') : 'No disponible' }}
                        </p>
                    </div>

                    @if($observaciones)
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1.5">Observaciones</span>
                        <p class="text-sm font-medium text-gray-800 bg-gray-50/80 p-4 rounded-xl border border-gray-200 leading-relaxed">
                            {{ $observaciones }}
                        </p>
                    </div>
                    @endif

                    <div class="border-t border-gray-100 pt-3 flex items-center justify-between text-xs text-gray-500">
                        <span>ID Medición:</span>
                        <span class="font-mono text-gray-900 font-bold">#{{ $medidaId }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
