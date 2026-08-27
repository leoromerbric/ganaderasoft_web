@extends('reportes.base', [
    'titulo' => 'Reporte de pesaje de leche',
    'subtitulo' => 'Monitoreo de pesajes diarios, promedios de lactancia y rendimiento por ordeño',
    'icon' => '🥛',
    'routeAction' => route('reportes.pesaje-leche')
])

@section('report_content')
    @php
        $resumen = is_array($reporte['resumen'] ?? null) ? $reporte['resumen'] : ($reporte['kpis'] ?? []);
        $items = is_array($reporte['items'] ?? null) ? $reporte['items'] : [];
        $pesajes = is_array($reporte['pesajes'] ?? null) ? $reporte['pesajes'] : [];
        $rendimiento = is_array($reporte['rendimiento_individual'] ?? null) ? $reporte['rendimiento_individual'] : [];

        $totalProduccion = $resumen['total_produccion'] ?? ($resumen['produccion_total_ordeno'] ?? 0.0);
        $promedioDiario = $resumen['promedio_diario_vaca'] ?? ($resumen['promedio_pesaje'] ?? 0.0);
        $vacasOrdeno = $resumen['vacas_en_ordeno'] ?? 0;
    @endphp

    <!-- Tarjetas de Resumen KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Producción total ordeño</p>
            <p class="text-2xl font-black text-ganaderasoft-azul mt-1">{{ number_format($totalProduccion, 1) }} Lts</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Promedio diario / vaca</p>
            <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">{{ number_format($promedioDiario, 1) }} Lts</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Vacas en ordeño</p>
            <p class="text-2xl font-black text-gray-800 mt-1">{{ $vacasOrdeno }}</p>
        </div>
    </div>

    <!-- Tabla Principal de Pesajes Consolidados -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Pesajes consolidados por lote</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Fecha pesaje</th>
                        <th class="py-2.5 px-3">Rebaño / lote</th>
                        <th class="py-2.5 px-3 text-center">Vacas evaluadas</th>
                        <th class="py-2.5 px-3 text-right">Promedio / vaca</th>
                        <th class="py-2.5 px-3 text-right">Total producción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                        @if(is_array($item))
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $item['fecha_pesaje'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $item['rebano_nombre'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-center font-medium text-gray-700">{{ $item['vacas_pesadas'] ?? 1 }} vacas</td>
                                <td class="py-2.5 px-3 text-right text-gray-700">{{ number_format($item['promedio_vaca_litros'] ?? $item['total_dia_litros'] ?? 0, 1) }} Lts</td>
                                <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul">{{ number_format($item['total_dia_litros'] ?? 0, 1) }} Lts</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400 italic">No se encontraron registros de pesajes consolidados en el periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla Secundaria de Rendimiento Individual -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Rendimiento individual por vaca</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Arete / animal</th>
                        <th class="py-2.5 px-3">Lactancia</th>
                        <th class="py-2.5 px-3">Días en ordeño</th>
                        <th class="py-2.5 px-3">Litros / día</th>
                        <th class="py-2.5 px-3 text-right">Variación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rendimiento as $r)
                        @if(is_array($r))
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $r['animal_identificador'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $r['lactancia'] ?? 'Lactancia actual' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $r['dias_en_ordeno'] ?? 0 }} días</td>
                                <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">{{ number_format($r['litros_dia'] ?? 0, 1) }} lts</td>
                                <td class="py-2.5 px-3 text-right font-semibold {{ str_starts_with($r['variacion'] ?? '', '-') ? 'text-red-600' : 'text-emerald-600' }}">{{ $r['variacion'] ?? '+0.0 lts' }}</td>
                            </tr>
                        @endif
                    @empty
                        @forelse($pesajes as $p)
                            @if(is_array($p))
                                <tr>
                                    <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $p['codigo'] ?? '-' }} ({{ $p['nombre'] ?? 'Sin nombre' }})</td>
                                    <td class="py-2.5 px-3 text-gray-600">{{ $p['categoria'] ?? 'En ordeño' }}</td>
                                    <td class="py-2.5 px-3 text-gray-600">{{ $p['lote'] ?? '-' }}</td>
                                    <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">{{ number_format($p['peso_total'] ?? 0, 1) }} lts</td>
                                    <td class="py-2.5 px-3 text-right text-emerald-600 font-semibold">{{ $p['fecha_evento'] ?? '-' }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400 italic">No se registraron pesajes individuales para las matrices evaluadas.</td>
                            </tr>
                        @endforelse
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @php
        $chartItems = array_slice($items, -10);
        $maxLitros = !empty($chartItems) ? max(array_map(fn($it) => (float)($it['total_dia_litros'] ?? 0), $chartItems) ?: [0]) : 0;
        $maxEscala = $maxLitros > 0 ? ceil($maxLitros * 1.15) : 50;
    @endphp

    <!-- Gráfica Visual de Tendencia de Ordeño -->
    <div class="p-4 bg-slate-50/70 rounded-2xl border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 mb-2.5">
            <div>
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                    <span>📊</span> Tendencia de producción diaria por lote
                </h3>
                <p class="text-[11px] text-gray-500">Volumen diario total ordeñado (Litros / Día)</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 text-[10.5px] font-semibold text-blue-700 bg-blue-50 border border-blue-200/60 px-2 py-0.5 rounded-lg">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Total diario (Lts)
                </span>
            </div>
        </div>
        
        @if(!empty($chartItems) && $maxLitros > 0)
            <div class="relative pt-4 pb-1">
                <!-- Líneas guía de fondo -->
                <div class="absolute inset-x-0 top-4 bottom-8 flex flex-col justify-between pointer-events-none opacity-40">
                    <div class="border-b border-dashed border-gray-300 w-full flex justify-end"><span class="text-[9px] text-gray-400 font-mono -mt-3 mr-1">{{ $maxEscala }} L</span></div>
                    <div class="border-b border-dashed border-gray-300 w-full flex justify-end"><span class="text-[9px] text-gray-400 font-mono -mt-3 mr-1">{{ round($maxEscala / 2) }} L</span></div>
                    <div class="border-b border-gray-300 w-full flex justify-end"><span class="text-[9px] text-gray-400 font-mono -mt-3 mr-1">0 L</span></div>
                </div>

                <!-- Contenedor de barras centrado -->
                <div class="relative z-10 flex items-end justify-center gap-4 sm:gap-6 min-h-[125px] px-4">
                    @foreach($chartItems as $cItem)
                        @php
                            $litros = (float) ($cItem['total_dia_litros'] ?? 0);
                            $pct = min(100, max(12, round(($litros / $maxEscala) * 100)));
                            $fPesaje = !empty($cItem['fecha_pesaje']) ? \Carbon\Carbon::parse($cItem['fecha_pesaje']) : null;
                            $fechaLabel = $fPesaje ? $fPesaje->format('d/m/Y') : '-';
                            $fechaCorta = $fPesaje ? $fPesaje->format('d M') : '-';
                            $rebNombre = $cItem['rebano_nombre'] ?? 'Lote';
                            $manana = (float)($cItem['ordeno_manana_litros'] ?? 0);
                            $tarde = (float)($cItem['ordeno_tarde_litros'] ?? 0);
                        @endphp
                        <div class="flex flex-col items-center justify-end h-full w-20 max-w-[85px] group">
                            <!-- Valor en Litros ARRIBA de la barra -->
                            <div class="mb-1 text-center">
                                <span class="inline-block px-1.5 py-0.5 text-[10.5px] font-black text-blue-900 bg-blue-100/80 rounded-md border border-blue-200/50">
                                    {{ number_format($litros, 1) }} L
                                </span>
                            </div>

                            <!-- Barra vertical con degradado y sombra -->
                            <div class="w-10 sm:w-12 rounded-t-lg bg-gradient-to-t from-blue-700 via-blue-600 to-indigo-500 shadow-xs border border-blue-700/30 flex flex-col justify-end overflow-hidden" 
                                 style="height: {{ $pct * 0.85 }}px; min-height: 18px;"
                                 title="Fecha: {{ $fechaLabel }}&#10;Total: {{ number_format($litros, 1) }} Lts">
                            </div>

                            <!-- Línea base y Etiquetas ABAJO -->
                            <div class="mt-1.5 text-center w-full border-t-2 border-gray-300 pt-1 flex flex-col items-center">
                                <span class="text-[10.5px] font-bold text-gray-700 leading-tight">{{ $fechaCorta }}</span>
                                <span class="text-[9.5px] text-gray-500 truncate max-w-[80px]" title="{{ $rebNombre }}">{{ $rebNombre }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Leyenda explicativa al pie -->
            <div class="mt-2 pt-2 border-t border-gray-200/70 flex flex-wrap items-center justify-between text-[10px] text-gray-500 gap-2">
                <span>📍 Mostrando los últimos {{ count($chartItems) }} registro(s) de pesaje consolidado.</span>
                <span class="font-medium text-gray-600">Promedio general: {{ number_format($promedioDiario, 1) }} Lts/vaca.</span>
            </div>
        @else
            <div class="h-32 flex flex-col items-center justify-center text-gray-400 italic text-xs border border-dashed border-gray-200 rounded-xl bg-white/60">
                <svg class="w-6 h-6 text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>No hay suficientes registros de pesajes en los filtros seleccionados para graficar la tendencia diaria.</span>
            </div>
        @endif
    </div>
@endsection
