@extends('reportes.base', [
    'titulo' => 'Reporte general de finca',
    'subtitulo' => 'Consolidado de animales, categorías, edades, pesos y genealogía',
    'icon' => '📊',
    'routeAction' => route('reportes.general')
])

@section('report_content')
    @php
        $animales = is_array($reporte['animales'] ?? null) ? $reporte['animales'] : [];
        $totalAnimales = $reporte['total_animales'] ?? count($animales);
        $activos = collect($animales)->filter(fn($a) => is_array($a) && !($a['archivado'] ?? false))->count();
        $archivados = collect($animales)->filter(fn($a) => is_array($a) && ($a['archivado'] ?? false))->count();
    @endphp

    <div class="space-y-6">
        <!-- Tarjetas de Resumen KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de Animales</p>
                <p class="text-2xl font-black text-ganaderasoft-azul mt-1">{{ $totalAnimales }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Animales Activos</p>
                <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">{{ $activos }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Animales Archivados</p>
                <p class="text-2xl font-black text-gray-600 mt-1">{{ $archivados }}</p>
            </div>
        </div>

        <!-- Tabla de Animales -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-200 bg-gray-50 text-gray-600 uppercase font-bold tracking-wider text-[11px]">
                        <th class="py-2.5 px-3">Código</th>
                        <th class="py-2.5 px-3">Nombre</th>
                        <th class="py-2.5 px-3">Sexo</th>
                        <th class="py-2.5 px-3">Categoría</th>
                        <th class="py-2.5 px-3">Rebaño</th>
                        <th class="py-2.5 px-3">Edad</th>
                        <th class="py-2.5 px-3">Raza</th>
                        <th class="py-2.5 px-3">Peso Ingreso</th>
                        <th class="py-2.5 px-3">Penúlt. Peso</th>
                        <th class="py-2.5 px-3">Último Peso</th>
                        <th class="py-2.5 px-3">Padre</th>
                        <th class="py-2.5 px-3">Madre</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($animales as $an)
                        @if(is_array($an))
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-2.5 px-3 font-bold text-gray-900">{{ $an['codigo'] ?? ($an['id'] ?? '-') }}</td>
                                <td class="py-2.5 px-3 font-semibold">{{ $an['nombre'] ?? '-' }}</td>
                                <td class="py-2.5 px-3">
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold {{ in_array($an['sexo'] ?? '', ['M', 'MACHO', 'Macho']) ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $an['sexo'] ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3">{{ $an['categoria'] ?? 'S/C' }}</td>
                                <td class="py-2.5 px-3">{{ $an['rebano_nombre'] ?? '-' }}</td>
                                <td class="py-2.5 px-3">
                                    <div>{{ !empty($an['edad_meses']) ? $an['edad_meses'] . ' m' : ($an['edad_formateada'] ?? '-') }}</div>
                                    @if(!empty($an['fecha_nacimiento']))
                                        <div class="text-[10px] text-gray-400">{{ $an['fecha_nacimiento'] }}</div>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3">{{ $an['raza'] ?? 'S/R' }}</td>
                                <td class="py-2.5 px-3">
                                    @if(!empty($an['peso_ingreso']))
                                        <div class="font-semibold">{{ number_format($an['peso_ingreso'], 1) }} kg</div>
                                        <div class="text-[10px] text-gray-400">{{ $an['fecha_ingreso'] ?? '' }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3">
                                    @if(!empty($an['penultimo_peso']))
                                        <div class="font-semibold">{{ number_format($an['penultimo_peso'], 1) }} kg</div>
                                        <div class="text-[10px] text-gray-400">{{ $an['fecha_penultimo_peso'] ?? '' }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3">
                                    @if(!empty($an['ultimo_peso']))
                                        <div class="font-bold text-ganaderasoft-azul">{{ number_format($an['ultimo_peso'], 1) }} kg</div>
                                        <div class="text-[10px] text-gray-400">{{ $an['fecha_ultimo_peso'] ?? '' }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $an['padre_codigo'] ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $an['madre_codigo'] ?? '-' }}</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="12" class="py-8 text-center text-gray-400 italic">
                                No se encontraron animales registrados para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
