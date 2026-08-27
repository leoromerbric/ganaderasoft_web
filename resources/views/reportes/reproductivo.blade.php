@extends('reportes.base', [
    'titulo' => 'Reporte reproductivo',
    'subtitulo' => 'Análisis integral de celos, servicios, gestaciones, palpaciones y partos',
    'icon' => '🍼',
    'routeAction' => route('reportes.reproductivo')
])

@section('report_content')
    @php
        $resumen = is_array($reporte['resumen'] ?? null) ? $reporte['resumen'] : ($reporte['kpis'] ?? []);
        $items = is_array($reporte['items'] ?? null) ? $reporte['items'] : [];
        $animales = is_array($reporte['animales'] ?? null) ? $reporte['animales'] : [];

        $tasaConcepcion = $resumen['tasa_concepcion'] ?? 0.0;
        $gestacionesConfirmadas = $resumen['gestaciones_confirmadas'] ?? 0;
        $proximosPartos = $resumen['proximos_partos'] ?? 0;
    @endphp

    <!-- Tarjetas de Resumen KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tasa de concepción</p>
            <p class="text-2xl font-black text-ganaderasoft-azul mt-1">{{ number_format($tasaConcepcion, 1) }}%</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestaciones confirmadas</p>
            <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">{{ $gestacionesConfirmadas }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Próximos partos (30 días)</p>
            <p class="text-2xl font-black text-gray-800 mt-1">{{ $proximosPartos }}</p>
        </div>
    </div>

    <!-- Tabla de Servicios y Diagnósticos -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Servicios y diagnósticos de palpación</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Arete / animal</th>
                        <th class="py-2.5 px-3">Último servicio</th>
                        <th class="py-2.5 px-3">Tipo de servicio</th>
                        <th class="py-2.5 px-3">Diagnóstico palpación</th>
                        <th class="py-2.5 px-3 text-right">Fecha prob. parto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                        @if(is_array($item))
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $item['animal_identificador'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $item['ultimo_servicio_fecha'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $item['tipo_servicio'] ?? '-' }}</td>
                                <td class="py-2.5 px-3">
                                    @php
                                        $diag = $item['diagnostico_palpacion'] ?? 'Pendiente';
                                        $isGest = stripos($diag, 'gest') !== false;
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-bold {{ $isGest ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-md">
                                        {{ $diag }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-right font-bold text-ganaderasoft-azul">{{ $item['fecha_probable_parto'] ?? '-' }}</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400 italic">No se encontraron servicios ni diagnósticos reproductivos en el periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de Calendario de Partos y Secados -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Calendario programado de partos y secados</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Arete / animal</th>
                        <th class="py-2.5 px-3">Días de gestación</th>
                        <th class="py-2.5 px-3">Fecha secado</th>
                        <th class="py-2.5 px-3">Fecha probable parto</th>
                        <th class="py-2.5 px-3 text-right">Prioridad</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php $hayCalendario = false; @endphp
                    @foreach($items as $item)
                        @if(is_array($item) && (!empty($item['dias_gestacion']) || (!empty($item['fecha_probable_parto']) && $item['fecha_probable_parto'] !== 'Por confirmar')))
                            @php
                                $hayCalendario = true;
                                $prioridad = $item['prioridad'] ?? 'Normal';
                                $badgeClass = match($prioridad) {
                                    'Inminente' => 'bg-red-100 text-red-800',
                                    'Atención' => 'bg-amber-100 text-amber-800',
                                    default => 'bg-green-100 text-green-800',
                                };
                            @endphp
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $item['animal_identificador'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $item['dias_gestacion'] ?? 0 }} días</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $item['fecha_secado'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">{{ $item['fecha_probable_parto'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-right">
                                    <span class="px-2 py-0.5 text-xs font-bold {{ $badgeClass }} rounded-md">
                                        {{ $prioridad }}
                                    </span>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    @if(!$hayCalendario)
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400 italic">No hay partos ni secados programados para las matrices evaluadas.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
