@extends('reportes.base', [
    'titulo' => 'Historia de lactancias (Cálculo TIM)',
    'subtitulo' => 'Rendimiento y proyecciones de producción de leche estandarizadas a P244, P270 y P305 días',
    'icon' => '🥛',
    'routeAction' => route('reportes.lactancias')
])

@section('report_content')
    @php
        $animales = is_array($reporte['animales'] ?? null) ? $reporte['animales'] : [];
        $totalAnimales = $reporte['total_animales'] ?? count($animales);
        $prodTotalFinca = $reporte['produccion_total_finca'] ?? 0.0;
    @endphp

    <div class="space-y-6">
        <!-- Tarjetas de Resumen KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Hembras Evaluadas</p>
                <p class="text-2xl font-black text-ganaderasoft-azul mt-1">{{ $totalAnimales }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Producción Acumulada de la Finca</p>
                <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">{{ number_format((float)$prodTotalFinca, 2) }} Lts</p>
            </div>
        </div>

        <!-- Listado Detallado por Vaca -->
        <div class="space-y-6">
            @forelse($animales as $an)
                @if(is_array($an))
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm no-break bg-white">
                        <!-- Cabecera de la Vaca -->
                        <div class="bg-gray-50/90 px-4 py-3 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="flex items-center space-x-3">
                                <span class="px-2.5 py-1 bg-ganaderasoft-azul text-white font-black text-xs rounded-lg">
                                    {{ $an['codigo'] ?? 'S/C' }}
                                </span>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-sm">{{ $an['nombre'] ?? 'Sin Nombre' }}</h3>
                                    <p class="text-xs text-gray-500">{{ $an['categoria'] ?? 'Vaca' }} • Rebaño: {{ $an['rebano_nombre'] ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="text-xs text-gray-500 font-medium">Producción Vitalicia:</span>
                                <span class="text-sm font-black text-ganaderasoft-azul ml-1">{{ number_format((float)($an['produccion_vitalicia'] ?? 0), 2) }} Lts</span>
                            </div>
                        </div>

                        <!-- Tabla de Lactancias de la Vaca -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 text-gray-600 uppercase font-bold text-[10px] border-b border-gray-100">
                                        <th class="py-2 px-3"># Lactancia</th>
                                        <th class="py-2 px-3">Inicio</th>
                                        <th class="py-2 px-3">Fin</th>
                                        <th class="py-2 px-3">Estado</th>
                                        <th class="py-2 px-3 text-right">Días</th>
                                        <th class="py-2 px-3 text-right">P244 (Lts)</th>
                                        <th class="py-2 px-3 text-right">P270 (Lts)</th>
                                        <th class="py-2 px-3 text-right">P305 (Lts)</th>
                                        <th class="py-2 px-3 text-right">Prod. Total (Lts)</th>
                                        <th class="py-2 px-3 text-center">Pesajes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                    @php
                                        $lactancias = is_array($an['lactancias'] ?? null) ? $an['lactancias'] : [];
                                    @endphp
                                    @forelse($lactancias as $lact)
                                        @if(is_array($lact))
                                            <tr class="hover:bg-gray-50/30">
                                                <td class="py-2 px-3 font-bold text-gray-800">Lactancia {{ $lact['num_lactancia'] ?? 1 }}</td>
                                                <td class="py-2 px-3">{{ $lact['fecha_inicio'] ?? '-' }}</td>
                                                <td class="py-2 px-3">{{ $lact['fecha_fin'] ?? 'En curso' }}</td>
                                                <td class="py-2 px-3">
                                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold {{ ($lact['estado'] ?? '') === 'Secada' ? 'bg-gray-100 text-gray-700' : 'bg-green-100 text-green-800' }}">
                                                        {{ $lact['estado'] ?? 'En curso' }}
                                                    </span>
                                                </td>
                                                <td class="py-2 px-3 text-right font-medium">{{ $lact['dias_lactancia'] ?? 0 }} d</td>
                                                <td class="py-2 px-3 text-right font-medium text-gray-800">
                                                    {{ isset($lact['p244']) && $lact['p244'] !== null ? number_format((float)$lact['p244'], 2) : '-' }}
                                                </td>
                                                <td class="py-2 px-3 text-right font-medium text-gray-800">
                                                    {{ isset($lact['p270']) && $lact['p270'] !== null ? number_format((float)$lact['p270'], 2) : '-' }}
                                                </td>
                                                <td class="py-2 px-3 text-right font-medium text-gray-800">
                                                    {{ isset($lact['p305']) && $lact['p305'] !== null ? number_format((float)$lact['p305'], 2) : '-' }}
                                                </td>
                                                <td class="py-2 px-3 text-right font-bold text-ganaderasoft-azul">
                                                    {{ number_format((float)($lact['produccion_total'] ?? 0), 2) }}
                                                </td>
                                                <td class="py-2 px-3 text-center text-gray-500">
                                                    {{ $lact['total_pesajes'] ?? 0 }}
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="10" class="py-3 text-center text-gray-400 italic">
                                                Sin registros de lactancia para este animal.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @empty
                <div class="py-12 text-center text-gray-400 italic bg-gray-50 rounded-2xl border border-gray-100">
                    No se encontraron hembras con registros de producción para los filtros seleccionados.
                </div>
            @endforelse
        </div>
    </div>
@endsection
