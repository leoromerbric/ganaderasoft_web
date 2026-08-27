@extends('reportes.base', [
    'titulo' => 'Reporte general de finca',
    'subtitulo' => 'Resumen ejecutivo consolidado de inventario ganadero, fincas y personal',
    'icon' => '📊',
    'routeAction' => route('reportes.general'),
    'mostrarFiltroFinca' => false
])

@section('report_content')
    @php
        $kpis = is_array($reporte['kpis'] ?? null) ? $reporte['kpis'] : ($reporte['resumen'] ?? []);
        $items = is_array($reporte['items'] ?? null) ? $reporte['items'] : [];
        $fincasReporte = is_array($reporte['fincas'] ?? null) ? $reporte['fincas'] : [];
        $rebanos = is_array($reporte['rebanos'] ?? null) ? $reporte['rebanos'] : [];
        $animales = is_array($reporte['animales'] ?? null) ? $reporte['animales'] : [];

        $totalAnimales = $kpis['total_animales'] ?? count($animales);
        $totalRebanos = $kpis['rebanos_activos'] ?? ($kpis['total_rebanos'] ?? count($rebanos));
        $totalPersonal = $kpis['personal_registrado'] ?? ($kpis['total_personal'] ?? 0);
    @endphp

    <!-- Tarjetas de Resumen KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de animales</p>
            <p class="text-2xl font-black text-ganaderasoft-azul mt-1">{{ $totalAnimales }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rebaños activos</p>
            <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">{{ $totalRebanos }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Personal registrado</p>
            <p class="text-2xl font-black text-gray-800 mt-1">{{ $totalPersonal }}</p>
        </div>
    </div>

    <!-- Tabla de Inventario de Fincas y Categorías -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Inventario ganadero por unidad de producción y categoría</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Finca / Lote</th>
                        <th class="py-2.5 px-3">Categoría</th>
                        <th class="py-2.5 px-3 text-center">Cant. Animales</th>
                        <th class="py-2.5 px-3">Estado de salud</th>
                        <th class="py-2.5 px-3 text-right">Composición</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                        @if(is_array($item))
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $item['finca_nombre'] ?? 'Finca' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $item['categoria'] ?? 'General' }}</td>
                                <td class="py-2.5 px-3 text-center font-bold text-ganaderasoft-azul">{{ $item['cantidad_animales'] ?? 0 }}</td>
                                <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">{{ $item['estado_nutricional'] ?? 'Sano' }}</span></td>
                                <td class="py-2.5 px-3 text-right text-gray-600 text-xs font-medium">{{ $item['observacion'] ?? '-' }}</td>
                            </tr>
                        @endif
                    @empty
                        @forelse($fincas as $finca)
                            @if(is_array($finca))
                                <tr>
                                    <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $finca['nombre'] ?? 'Finca' }}</td>
                                    <td class="py-2.5 px-3 text-gray-600">Rebaños: {{ $finca['cantidad_rebanos'] ?? 0 }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-ganaderasoft-azul">{{ $finca['cantidad_animales'] ?? 0 }}</td>
                                    <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Óptimo</span></td>
                                    <td class="py-2.5 px-3 text-right text-gray-500 text-xs">Personal: {{ $finca['cantidad_personal'] ?? 0 }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400 italic">No se encontraron registros de inventario ganadero.</td>
                            </tr>
                        @endforelse
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla Detallada de Animales / Distribución -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Distribución y registro de animales</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Código / Nombre</th>
                        <th class="py-2.5 px-3">Sexo</th>
                        <th class="py-2.5 px-3">Categoría</th>
                        <th class="py-2.5 px-3">Rebaño</th>
                        <th class="py-2.5 px-3">Raza</th>
                        <th class="py-2.5 px-3 text-right">Último Peso</th>
                        <th class="py-2.5 px-3 text-right">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($animales as $an)
                        @if(is_array($an))
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-gray-800">
                                    {{ $an['codigo'] ?? '-' }} <span class="text-xs text-gray-500 font-normal">({{ $an['nombre'] ?? 'Sin nombre' }})</span>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold {{ in_array($an['sexo'] ?? '', ['M', 'MACHO', 'Macho']) ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $an['sexo'] ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $an['categoria'] ?? 'S/C' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $an['rebano_nombre'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $an['raza'] ?? 'S/R' }}</td>
                                <td class="py-2.5 px-3 text-right font-bold text-ganaderasoft-azul">
                                    {{ !empty($an['ultimo_peso']) ? number_format((float)$an['ultimo_peso'], 1) . ' kg' : '-' }}
                                </td>
                                <td class="py-2.5 px-3 text-right">
                                    <span class="px-2 py-0.5 text-xs font-bold {{ ($an['archivado'] ?? false) ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-800' }} rounded-md">
                                        {{ ($an['archivado'] ?? false) ? 'Archivado' : 'Activo' }}
                                    </span>
                                </td>
                            </tr>
                        @endif
                    @empty
                        @forelse($rebanos as $r)
                            @if(is_array($r))
                                <tr>
                                    <td class="py-2.5 px-3 font-semibold text-gray-800">{{ $r['nombre'] ?? 'Rebaño' }}</td>
                                    <td class="py-2.5 px-3 text-gray-500">-</td>
                                    <td class="py-2.5 px-3 text-gray-600">Lote activo</td>
                                    <td class="py-2.5 px-3 text-gray-600">{{ $r['nombre'] ?? '-' }}</td>
                                    <td class="py-2.5 px-3 text-gray-500">-</td>
                                    <td class="py-2.5 px-3 text-right font-bold text-ganaderasoft-azul">{{ $r['cantidad_animales'] ?? 0 }} animales</td>
                                    <td class="py-2.5 px-3 text-right"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Activo</span></td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-400 italic">No se encontraron animales registrados.</td>
                            </tr>
                        @endforelse
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
