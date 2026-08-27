@extends('reportes.base', [
    'titulo' => 'Reporte reproductivo consolidado',
    'subtitulo' => 'Historial cronológico de partos, servicios de monta e inseminación artificial',
    'icon' => '🍼',
    'routeAction' => route('reportes.reproductivo')
])

@section('report_content')
    @php
        $resumen = is_array($reporte['resumen'] ?? null) ? $reporte['resumen'] : [];
        $animales = is_array($reporte['animales'] ?? null) ? $reporte['animales'] : [];
        $totalAnimales = $resumen['total_animales'] ?? count($animales);
        $totalEventos = $resumen['total_eventos'] ?? 0;
        $totalPartos = $resumen['total_partos'] ?? 0;
        $totalServicios = $resumen['total_servicios'] ?? 0;
    @endphp

    <div class="space-y-6">
        <!-- Tarjetas de Resumen KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Animales Evaluados</p>
                <p class="text-2xl font-black text-gray-800 mt-1">{{ $totalAnimales }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Eventos</p>
                <p class="text-2xl font-black text-ganaderasoft-azul mt-1">{{ $totalEventos }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Partos</p>
                <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">{{ $totalPartos }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Servicios</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $totalServicios }}</p>
            </div>
        </div>

        <!-- Tabla Resumen Reproductivo por Animal -->
        <div class="space-y-6">
            @forelse($animales as $an)
                @if(is_array($an) && !empty($an['eventos']) && is_array($an['eventos']) && count($an['eventos']) > 0)
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm no-break bg-white">
                        <!-- Cabecera del Animal -->
                        <div class="bg-gray-50/90 px-4 py-3 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="flex items-center space-x-3">
                                <span class="px-2.5 py-1 bg-ganaderasoft-azul text-white font-black text-xs rounded-lg">
                                    {{ $an['codigo'] ?? 'S/C' }}
                                </span>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-sm">{{ $an['nombre'] ?? 'Sin Nombre' }}</h3>
                                    <p class="text-xs text-gray-500">{{ $an['categoria'] ?? 'Hembra' }} • Rebaño: {{ $an['rebano_nombre'] ?? '-' }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg">
                                    {{ count($an['eventos']) }} {{ count($an['eventos']) === 1 ? 'evento registrado' : 'eventos registrados' }}
                                </span>
                            </div>
                        </div>

                        <!-- Tabla de Eventos Reproductivos -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 text-gray-600 uppercase font-bold text-[10px] border-b border-gray-100">
                                        <th class="py-2 px-3">Origen</th>
                                        <th class="py-2 px-3">Tipo de Evento</th>
                                        <th class="py-2 px-3">Fecha</th>
                                        <th class="py-2 px-3">Detalle / Semen</th>
                                        <th class="py-2 px-3">Técnico / Responsable</th>
                                        <th class="py-2 px-3">Observación</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                    @foreach($an['eventos'] as $ev)
                                        @if(is_array($ev))
                                            <tr class="hover:bg-gray-50/30">
                                                <td class="py-2 px-3">
                                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold {{ ($ev['origen'] ?? '') === 'Parto' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ $ev['origen'] ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="py-2 px-3 font-semibold text-gray-800">{{ $ev['tipo'] ?? '-' }}</td>
                                                <td class="py-2 px-3 font-medium">{{ $ev['fecha'] ?? '-' }}</td>
                                                <td class="py-2 px-3">{{ $ev['semen'] ?? '-' }}</td>
                                                <td class="py-2 px-3">{{ $ev['tecnico'] ?? '-' }}</td>
                                                <td class="py-2 px-3 text-gray-500 text-[11px]">{{ $ev['observacion'] ?? '-' }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @empty
                <div class="py-12 text-center text-gray-400 italic bg-gray-50 rounded-2xl border border-gray-100">
                    No se encontraron registros reproductivos para los filtros seleccionados.
                </div>
            @endforelse
        </div>
    </div>
@endsection
