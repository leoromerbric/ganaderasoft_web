@extends('reportes.base', [
    'titulo' => 'Reporte de pesaje de leche',
    'subtitulo' => 'Monitoreo de pesajes diarios, promedios de lactancia y rendimiento por ordeño',
    'icon' => '🥛',
    'routeAction' => route('reportes.pesaje-leche')
])

@section('report_content')
    <!-- Documento: Reporte de pesaje de leche -->
    <div class="space-y-6">
        <!-- Tarjetas de Resumen KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Producción total ordeño</p>
                <p class="text-2xl font-black text-ganaderasoft-azul mt-1">1,485.0 Lts</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Promedio diario / vaca</p>
                <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">18.2 Lts</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Vacas en ordeño</p>
                <p class="text-2xl font-black text-gray-800 mt-1">45</p>
            </div>
        </div>

        <!-- Tabla Resumen de Pesaje de Leche -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/80 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Fecha pesaje</th>
                        <th class="py-3 px-4">Rebaño / lote</th>
                        <th class="py-3 px-4">Ordeño mañana (lts)</th>
                        <th class="py-3 px-4">Ordeño tarde (lts)</th>
                        <th class="py-3 px-4 text-right">Total día (lts)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-800">14/08/2026</td>
                        <td class="py-3 px-4 text-gray-600">Lote alta producción</td>
                        <td class="py-3 px-4 font-semibold text-gray-700">420.5</td>
                        <td class="py-3 px-4 font-semibold text-gray-700">340.0</td>
                        <td class="py-3 px-4 text-right font-black text-ganaderasoft-azul">760.5 Lts</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-800">13/08/2026</td>
                        <td class="py-3 px-4 text-gray-600">Lote alta producción</td>
                        <td class="py-3 px-4 font-semibold text-gray-700">415.0</td>
                        <td class="py-3 px-4 font-semibold text-gray-700">338.5</td>
                        <td class="py-3 px-4 text-right font-black text-ganaderasoft-azul">753.5 Lts</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-800">12/08/2026</td>
                        <td class="py-3 px-4 text-gray-600">Lote media producción</td>
                        <td class="py-3 px-4 font-semibold text-gray-700">210.0</td>
                        <td class="py-3 px-4 font-semibold text-gray-700">175.0</td>
                        <td class="py-3 px-4 text-right font-black text-ganaderasoft-azul">385.0 Lts</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
