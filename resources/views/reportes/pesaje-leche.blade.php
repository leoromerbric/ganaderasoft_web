@extends('reportes.base', [
    'titulo' => 'Reporte de pesajes de leche',
    'subtitulo' => 'Monitoreo cronológico de pesajes individuales de leche por animal y lote',
    'icon' => '🥛',
    'routeAction' => route('reportes.pesaje-leche')
])

@section('report_content')
    @php
        $resumen = is_array($reporte['resumen'] ?? null) ? $reporte['resumen'] : [];
        $pesajes = is_array($reporte['pesajes'] ?? null) ? $reporte['pesajes'] : [];
        $totalPesajes = $resumen['total_pesajes'] ?? count($pesajes);
        $totalProduccion = $resumen['total_produccion'] ?? 0.0;
        $promedioPesaje = $resumen['promedio_pesaje'] ?? 0.0;
    @endphp

    <div class="space-y-6">
        <!-- Tarjetas de Resumen KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de Pesajes</p>
                <p class="text-2xl font-black text-ganaderasoft-azul mt-1">{{ $totalPesajes }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Producción Acumulada</p>
                <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">{{ number_format((float)$totalProduccion, 2) }} Lts/Kg</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Promedio por Pesaje</p>
                <p class="text-2xl font-black text-gray-800 mt-1">{{ number_format((float)$promedioPesaje, 2) }} Lts/Kg</p>
            </div>
        </div>

        <!-- Tabla Resumen de Pesaje de Leche -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-200 bg-gray-50 text-gray-600 uppercase font-bold tracking-wider text-[11px]">
                        <th class="py-2.5 px-3">Fecha Pesaje</th>
                        <th class="py-2.5 px-3">Código Animal</th>
                        <th class="py-2.5 px-3">Nombre</th>
                        <th class="py-2.5 px-3">Rebaño / Lote</th>
                        <th class="py-2.5 px-3">Categoría</th>
                        <th class="py-2.5 px-3">Estatus</th>
                        <th class="py-2.5 px-3 text-right">Pesaje Total (Lts/Kg)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($pesajes as $ps)
                        @if(is_array($ps))
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-2.5 px-3 font-semibold text-gray-900">{{ $ps['fecha_evento'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">{{ $ps['codigo'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 font-medium">{{ $ps['nombre'] ?? '-' }}</td>
                                <td class="py-2.5 px-3">{{ $ps['lote'] ?? '-' }}</td>
                                <td class="py-2.5 px-3">{{ $ps['categoria'] ?? 'Lactancia' }}</td>
                                <td class="py-2.5 px-3">
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold {{ ($ps['estatus'] ?? '') === 'Archivado' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-800' }}">
                                        {{ $ps['estatus'] ?? 'Activo' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul text-sm">
                                    {{ number_format((float)($ps['peso_total'] ?? 0), 2) }}
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 italic">
                                No se encontraron registros de pesajes de leche para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
