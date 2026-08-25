@extends('reportes.base', [
    'titulo' => 'Reporte general de finca',
    'subtitulo' => 'Resumen ejecutivo consolidado de inventario ganadero, fincas y personal',
    'icon' => '📊',
    'routeAction' => route('reportes.general')
])

@section('report_content')
    <!-- Tarjetas de Resumen KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de animales</p>
            <p class="text-2xl font-black text-ganaderasoft-azul mt-1">128</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rebaños activos</p>
            <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">8</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Personal registrado</p>
            <p class="text-2xl font-black text-gray-800 mt-1">14</p>
        </div>
    </div>

    <!-- Tabla de Inventario de Fincas -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Inventario ganadero por finca y categoría</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Finca / lote</th>
                        <th class="py-2.5 px-3">Categoría</th>
                        <th class="py-2.5 px-3">Cant. Animales</th>
                        <th class="py-2.5 px-3">Estado nutricional</th>
                        <th class="py-2.5 px-3 text-right">Observación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Finca El Paraíso</td>
                        <td class="py-2.5 px-3 text-gray-600">Vacas en ordeño</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">45</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Excelente</span></td>
                        <td class="py-2.5 px-3 text-right text-gray-500 text-xs">Sin novedad sanitaria</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Finca Santa María</td>
                        <td class="py-2.5 px-3 text-gray-600">Novillas de levante</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">32</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Bueno</span></td>
                        <td class="py-2.5 px-3 text-right text-gray-500 text-xs">Plan vacunal al día</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Finca La Esperanza</td>
                        <td class="py-2.5 px-3 text-gray-600">Toros reproductores</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">6</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Óptimo</span></td>
                        <td class="py-2.5 px-3 text-right text-gray-500 text-xs">Evaluación andrológica OK</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Finca Buena Vista</td>
                        <td class="py-2.5 px-3 text-gray-600">Terneros destetados</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">25</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Bueno</span></td>
                        <td class="py-2.5 px-3 text-right text-gray-500 text-xs">Desparasitación vigente</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Finca San José</td>
                        <td class="py-2.5 px-3 text-gray-600">Vacas horras</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">20</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Óptimo</span></td>
                        <td class="py-2.5 px-3 text-right text-gray-500 text-xs">Suplementación mineral</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de Distribución de Personal -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Distribución de personal en campo</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Nombre del trabajador</th>
                        <th class="py-2.5 px-3">Cargo / rol</th>
                        <th class="py-2.5 px-3">Finca asignada</th>
                        <th class="py-2.5 px-3">Teléfono</th>
                        <th class="py-2.5 px-3 text-right">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Carlos Mendoza</td>
                        <td class="py-2.5 px-3 text-gray-600">Administrador de finca</td>
                        <td class="py-2.5 px-3 text-gray-600">Finca El Paraíso</td>
                        <td class="py-2.5 px-3 text-gray-500">0414-1234567</td>
                        <td class="py-2.5 px-3 text-right"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Activo</span></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Luis Hernández</td>
                        <td class="py-2.5 px-3 text-gray-600">Médico veterinario</td>
                        <td class="py-2.5 px-3 text-gray-600">Finca Santa María</td>
                        <td class="py-2.5 px-3 text-gray-500">0412-9876543</td>
                        <td class="py-2.5 px-3 text-right"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Activo</span></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">José Gregorio Pérez</td>
                        <td class="py-2.5 px-3 text-gray-600">Ordeñador principal</td>
                        <td class="py-2.5 px-3 text-gray-600">Finca El Paraíso</td>
                        <td class="py-2.5 px-3 text-gray-500">0424-5551122</td>
                        <td class="py-2.5 px-3 text-right"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Activo</span></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Manuel Rivas</td>
                        <td class="py-2.5 px-3 text-gray-600">Vaquero de campo</td>
                        <td class="py-2.5 px-3 text-gray-600">Finca La Esperanza</td>
                        <td class="py-2.5 px-3 text-gray-500">0416-4443322</td>
                        <td class="py-2.5 px-3 text-right"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Activo</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cuadro de Notas y Resumen -->
    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Dictamen y estado general</h4>
        <p class="text-xs text-gray-600 leading-relaxed">
            Las unidades de producción registran un balance positivo con disponibilidad de forraje y control zoosanitario al 100%. No se reportan incidencias epidemiológicas en los lotes evaluados.
        </p>
    </div>
@endsection
