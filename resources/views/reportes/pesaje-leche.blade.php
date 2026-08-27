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
                        <th class="py-2.5 px-3">Ordeño mañana</th>
                        <th class="py-2.5 px-3">Ordeño tarde</th>
                        <th class="py-2.5 px-3 text-right">Total día</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                        @if(is_array($item))
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $item['fecha_pesaje'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $item['rebano_nombre'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-700">{{ number_format($item['ordeno_manana_litros'] ?? 0, 1) }} lts</td>
                                <td class="py-2.5 px-3 text-gray-700">{{ number_format($item['ordeno_tarde_litros'] ?? 0, 1) }} lts</td>
                                <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul">{{ number_format($item['total_dia_litros'] ?? 0, 1) }} lts</td>
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
                                <td class="py-2.5 px-3 text-right text-emerald-600 font-semibold">{{ $r['variacion'] ?? '+0.0 lts' }}</td>
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

    <!-- Gráfica Visual de Tendencia Semanal de Ordeño -->
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tendencia de producción diaria</h3>
                <p class="text-[11px] text-gray-500">Comportamiento del volumen total en litros por lote</p>
            </div>
            <span class="text-xs font-bold text-ganaderasoft-azul bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg">
                Monitoreo activo
            </span>
        </div>
        
        <div class="grid grid-cols-7 gap-2 pt-4 items-end h-36 border-b border-gray-200 pb-2">
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">Lun</span>
                <div class="w-full rounded-t-md" style="height: 72%; background-color: #93c5fd;"></div>
                <span class="text-[10px] text-gray-500 font-medium">384L</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">Mar</span>
                <div class="w-full rounded-t-md" style="height: 94%; background-color: #2563eb;"></div>
                <span class="text-[10px] text-gray-500 font-medium">753L</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">Mié</span>
                <div class="w-full rounded-t-md" style="height: 68%; background-color: #93c5fd;"></div>
                <span class="text-[10px] text-gray-500 font-medium">375L</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">Jue</span>
                <div class="w-full rounded-t-md" style="height: 70%; background-color: #93c5fd;"></div>
                <span class="text-[10px] text-gray-500 font-medium">385L</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">Vie</span>
                <div class="w-full rounded-t-md" style="height: 95%; background-color: #2563eb;"></div>
                <span class="text-[10px] text-gray-500 font-medium">753L</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">Sáb</span>
                <div class="w-full rounded-t-md" style="height: 98%; background-color: #1d4ed8;"></div>
                <span class="text-[10px] text-gray-500 font-medium">760L</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-ganaderasoft-azul">Dom</span>
                <div class="w-full rounded-t-md" style="height: 92%; background-color: #2563eb;"></div>
                <span class="text-[10px] font-bold text-ganaderasoft-azul">740L</span>
            </div>
        </div>
    </div>

    <!-- Notas y Observaciones Técnicas -->
    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Observaciones del pesaje lechero</h4>
        <p class="text-xs text-gray-600 leading-relaxed">
            Se evidencia estabilidad en los picos de ordeño matutino y vespertino. Mantener la suplementación nutricional y los protocolos de rutina de ordeño para sostener los niveles de producción del lote.
        </p>
    </div>
@endsection
