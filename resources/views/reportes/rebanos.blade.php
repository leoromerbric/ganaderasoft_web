@extends('reportes.base', [
    'titulo' => 'Reporte de rebaños y lotes',
    'subtitulo' => 'Inventario consolidado de rebaños, distribución por sexo y estado de animales',
    'icon' => '🏷️',
    'routeAction' => route('reportes.rebanos')
])

@section('report_content')
    @php
        $rebanos = is_array($reporte['rebanos'] ?? null) ? $reporte['rebanos'] : [];
        $totalRebanos = $reporte['total_rebanos'] ?? count($rebanos);
        $totalAnimales = collect($rebanos)->filter(fn($r) => is_array($r))->sum('total_animales');
        $totalMachos = collect($rebanos)->filter(fn($r) => is_array($r))->sum('machos');
        $totalHembras = collect($rebanos)->filter(fn($r) => is_array($r))->sum('hembras');
    @endphp

    <div class="space-y-6">
        <!-- Tarjetas de Resumen KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Rebaños</p>
                <p class="text-2xl font-black text-gray-800 mt-1">{{ $totalRebanos }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Animales</p>
                <p class="text-2xl font-black text-ganaderasoft-azul mt-1">{{ $totalAnimales }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Machos</p>
                <p class="text-2xl font-black text-blue-700 mt-1">{{ $totalMachos }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Hembras</p>
                <p class="text-2xl font-black text-pink-700 mt-1">{{ $totalHembras }}</p>
            </div>
        </div>

        <!-- Tabla de Rebaños -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-200 bg-gray-50 text-gray-600 uppercase font-bold tracking-wider text-[11px]">
                        <th class="py-2.5 px-4">Nombre del Rebaño</th>
                        <th class="py-2.5 px-4">Estatus</th>
                        <th class="py-2.5 px-4 text-center">Machos</th>
                        <th class="py-2.5 px-4 text-center">Hembras</th>
                        <th class="py-2.5 px-4 text-center">Animales Activos</th>
                        <th class="py-2.5 px-4 text-center">Animales Archivados</th>
                        <th class="py-2.5 px-4 text-right">Total Animales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($rebanos as $r)
                        @if(is_array($r))
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3 px-4 font-bold text-gray-900 text-sm">{{ $r['nombre'] ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ ($r['archivado'] ?? false) ? 'bg-gray-100 text-gray-700' : 'bg-green-100 text-green-800' }}">
                                        {{ ($r['archivado'] ?? false) ? 'Archivado' : 'Activo' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center font-semibold text-blue-700">{{ $r['machos'] ?? 0 }}</td>
                                <td class="py-3 px-4 text-center font-semibold text-pink-700">{{ $r['hembras'] ?? 0 }}</td>
                                <td class="py-3 px-4 text-center font-semibold text-green-700">{{ $r['animales_activos'] ?? 0 }}</td>
                                <td class="py-3 px-4 text-center font-semibold text-gray-500">{{ $r['animales_archivados'] ?? 0 }}</td>
                                <td class="py-3 px-4 text-right font-black text-ganaderasoft-azul text-sm">
                                    {{ $r['total_animales'] ?? 0 }}
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 italic">
                                No se encontraron rebaños registrados para esta finca.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
