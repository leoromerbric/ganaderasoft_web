@extends('reportes.base', [
    'titulo' => 'Reporte general de finca',
    'subtitulo' => 'Resumen ejecutivo consolidado de inventario ganadero, fincas y personal',
    'icon' => '📊',
    'routeAction' => route('reportes.general')
])

@section('report_content')
    <!-- Documento: Reporte General -->
    <div class="space-y-6">
        <!-- Tarjetas de Resumen KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de Animales</p>
                <p class="text-2xl font-black text-ganaderasoft-azul mt-1">128</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rebaños Activos</p>
                <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">8</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Personal Registrado</p>
                <p class="text-2xl font-black text-gray-800 mt-1">14</p>
            </div>
        </div>

        <!-- Tabla Resumen del Documento -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/80 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Finca / Lote</th>
                        <th class="py-3 px-4">Categoría</th>
                        <th class="py-3 px-4">Cant. Animales</th>
                        <th class="py-3 px-4">Estado Nutricional</th>
                        <th class="py-3 px-4 text-right">Observación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-800">Finca El Paraíso</td>
                        <td class="py-3 px-4 text-gray-600">Vacas en Ordeño</td>
                        <td class="py-3 px-4 font-bold text-ganaderasoft-azul">45</td>
                        <td class="py-3 px-4"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Excelente</span></td>
                        <td class="py-3 px-4 text-right text-gray-500 text-xs">Sin novedad sanitaria</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-800">Finca Santa María</td>
                        <td class="py-3 px-4 text-gray-600">Novillas de Levande</td>
                        <td class="py-3 px-4 font-bold text-ganaderasoft-azul">32</td>
                        <td class="py-3 px-4"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Bueno</span></td>
                        <td class="py-3 px-4 text-right text-gray-500 text-xs">Plan vacunal al día</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-800">Finca La Esperanza</td>
                        <td class="py-3 px-4 text-gray-600">Toros Reproductores</td>
                        <td class="py-3 px-4 font-bold text-ganaderasoft-azul">6</td>
                        <td class="py-3 px-4"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Óptimo</span></td>
                        <td class="py-3 px-4 text-right text-gray-500 text-xs">Evaluación andrológica OK</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
