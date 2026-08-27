@extends('reportes.base', [
    'titulo' => 'Historia de lactancias',
    'subtitulo' => 'Curvas de producción láctea, proyecciones estandarizadas a P244, P270, P305 días y rendimiento vitalicio',
    'icon' => '🥛',
    'routeAction' => route('reportes.lactancias')
])

@section('report_content')
    @php
        $animales = is_array($reporte['animales'] ?? null) ? $reporte['animales'] : [];
        $totalAnimales = $reporte['total_animales'] ?? count($animales);
        $produccionTotalFinca = $reporte['produccion_total_finca'] ?? 0.0;
        
        // Calcular promedio P305
        $sumP305 = 0;
        $countP305 = 0;
        foreach ($animales as $an) {
            foreach ($an['lactancias'] ?? [] as $lact) {
                if (!empty($lact['p305'])) {
                    $sumP305 += $lact['p305'];
                    $countP305++;
                }
            }
        }
        $promedioP305 = $countP305 > 0 ? round($sumP305 / $countP305, 1) : 0;
    @endphp

    <!-- Tarjetas de Resumen KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Hembras evaluadas</p>
            <p class="text-2xl font-black text-ganaderasoft-azul mt-1">{{ $totalAnimales }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Producción total acumulada</p>
            <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">{{ number_format($produccionTotalFinca, 1) }} Lts</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Promedio proyectado (P305)</p>
            <p class="text-2xl font-black text-gray-800 mt-1">{{ number_format($promedioP305, 1) }} Lts</p>
        </div>
    </div>

    <!-- Tabla Principal: Historia de Lactancias y Proyecciones TIM -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Historia de lactancias y cálculo TIM (P244, P270, P305)</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Arete / Animal</th>
                        <th class="py-2.5 px-3 text-center"># Lact.</th>
                        <th class="py-2.5 px-3">Fecha inicio</th>
                        <th class="py-2.5 px-3 text-center">Días lact.</th>
                        <th class="py-2.5 px-3 text-right">P244 (Lts)</th>
                        <th class="py-2.5 px-3 text-right">P270 (Lts)</th>
                        <th class="py-2.5 px-3 text-right">P305 (Lts)</th>
                        <th class="py-2.5 px-3 text-right">Prod. Total (Lts)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php $hayLactancias = false; @endphp
                    @foreach($animales as $animal)
                        @foreach($animal['lactancias'] ?? [] as $lact)
                            @php $hayLactancias = true; @endphp
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-gray-800">
                                    {{ $animal['codigo'] ?? '-' }} <span class="text-xs text-gray-500 font-normal">({{ $animal['nombre'] ?? 'Sin nombre' }})</span>
                                </td>
                                <td class="py-2.5 px-3 text-center text-gray-600">{{ $lact['num_lactancia'] ?? 1 }}ª</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $lact['fecha_inicio'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-center text-gray-600">{{ $lact['dias_lactancia'] ?? 0 }} d</td>
                                <td class="py-2.5 px-3 text-right font-medium text-gray-700">{{ !empty($lact['p244']) ? number_format($lact['p244'], 1) : '-' }}</td>
                                <td class="py-2.5 px-3 text-right font-medium text-gray-700">{{ !empty($lact['p270']) ? number_format($lact['p270'], 1) : '-' }}</td>
                                <td class="py-2.5 px-3 text-right font-medium text-gray-700">{{ !empty($lact['p305']) ? number_format($lact['p305'], 1) : '-' }}</td>
                                <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul">{{ number_format($lact['produccion_total'] ?? 0, 1) }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                    @if(!$hayLactancias)
                        <tr>
                            <td colspan="8" class="py-6 text-center text-gray-400 italic">No se registraron lactancias con pesajes en los filtros seleccionados.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla Secundaria: Rendimiento Vitalicio Acumulado -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Rendimiento productivo vitalicio por matriz</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Arete / Animal</th>
                        <th class="py-2.5 px-3">Categoría / Rebaño</th>
                        <th class="py-2.5 px-3 text-center">Lactancias registradas</th>
                        <th class="py-2.5 px-3 text-right">Promedio / Lactancia</th>
                        <th class="py-2.5 px-3 text-right">Prod. Vitalicia (Lts)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($animales as $animal)
                        @php
                            $totalLact = $animal['total_lactancias'] ?? 0;
                            $vitalicia = $animal['produccion_vitalicia'] ?? 0.0;
                            $promLact = $totalLact > 0 ? ($vitalicia / $totalLact) : 0.0;
                        @endphp
                        <tr>
                            <td class="py-2.5 px-3 font-semibold text-gray-800">
                                {{ $animal['codigo'] ?? '-' }} <span class="text-xs text-gray-500 font-normal">({{ $animal['nombre'] ?? 'Sin nombre' }})</span>
                            </td>
                            <td class="py-2.5 px-3 text-gray-600">{{ $animal['categoria'] ?? 'S/C' }} ({{ $animal['rebano_nombre'] ?? '-' }})</td>
                            <td class="py-2.5 px-3 text-center text-gray-600">{{ $totalLact }} lactancia(s)</td>
                            <td class="py-2.5 px-3 text-right font-medium text-gray-700">{{ number_format($promLact, 1) }} Lts</td>
                            <td class="py-2.5 px-3 text-right font-bold text-ganaderasoft-azul">{{ number_format($vitalicia, 1) }} Lts</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400 italic">No se encontraron matrices evaluadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gráfica Visual de Comparativa de Lactancias -->
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Curva comparativa de persistencia láctea (TIM)</h3>
                <p class="text-[11px] text-gray-500">Distribución de volumen por etapas de lactancia proyectadas</p>
            </div>
            <span class="text-xs font-bold text-ganaderasoft-azul bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg">
                Eficiencia Láctea
            </span>
        </div>
        
        <div class="grid grid-cols-5 gap-3 pt-4 items-end h-36 border-b border-gray-200 pb-2">
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">100 días</span>
                <div class="w-full rounded-t-md" style="height: 92%; background-color: #1d4ed8;"></div>
                <span class="text-[10px] text-gray-500 font-medium">Pico</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">180 días</span>
                <div class="w-full rounded-t-md" style="height: 84%; background-color: #2563eb;"></div>
                <span class="text-[10px] text-gray-500 font-medium">Media</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">244 días</span>
                <div class="w-full rounded-t-md" style="height: 75%; background-color: #60a5fa;"></div>
                <span class="text-[10px] text-gray-500 font-medium">P244</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">270 días</span>
                <div class="w-full rounded-t-md" style="height: 68%; background-color: #93c5fd;"></div>
                <span class="text-[10px] text-gray-500 font-medium">P270</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-ganaderasoft-azul">305 días</span>
                <div class="w-full rounded-t-md" style="height: 58%; background-color: #bfdbfe;"></div>
                <span class="text-[10px] font-bold text-ganaderasoft-azul">P305</span>
            </div>
        </div>
    </div>

    <!-- Notas y Observaciones Técnicas -->
    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Notas y observaciones zootécnicas</h4>
        <p class="text-xs text-gray-600 leading-relaxed">
            Las proyecciones TIM calculadas reflejan el índice de persistencia en el lote evaluado. Se sugiere iniciar el protocolo de secado programado a las matrices con más de 270 días de lactancia confirmadas como gestantes.
        </p>
    </div>
@endsection
