@extends('reportes.base', [
    'titulo' => 'Reporte reproductivo',
    'subtitulo' => 'Análisis integral de celos, servicios, gestaciones, palpaciones y partos',
    'icon' => '🍼',
    'routeAction' => route('reportes.reproductivo')
])

@section('report_content')
    <!-- Documento: Reporte reproductivo -->
    <div class="space-y-6">
        <!-- Tarjetas de Resumen KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tasa de concepción</p>
                <p class="text-2xl font-black text-ganaderasoft-azul mt-1">78.4%</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestaciones confirmadas</p>
                <p class="text-2xl font-black text-ganaderasoft-verde-oscuro mt-1">34</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Próximos partos (30 días)</p>
                <p class="text-2xl font-black text-gray-800 mt-1">9</p>
            </div>
        </div>

        <!-- Tabla Resumen Reproductivo -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/80 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Arete / animal</th>
                        <th class="py-3 px-4">Último servicio</th>
                        <th class="py-3 px-4">Tipo de servicio</th>
                        <th class="py-3 px-4">Diagnóstico palpación</th>
                        <th class="py-3 px-4 text-right">Fecha prob. Parto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-800">Vaca #104 (mariposa)</td>
                        <td class="py-3 px-4 text-gray-600">12/05/2026</td>
                        <td class="py-3 px-4 text-gray-600">Ia (semen holstein)</td>
                        <td class="py-3 px-4"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Gestante (3m)</span></td>
                        <td class="py-3 px-4 text-right font-bold text-ganaderasoft-azul">18/02/2027</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-800">Vaca #208 (lucero)</td>
                        <td class="py-3 px-4 text-gray-600">28/06/2026</td>
                        <td class="py-3 px-4 text-gray-600">Monta natural</td>
                        <td class="py-3 px-4"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Gestante (2m)</span></td>
                        <td class="py-3 px-4 text-right font-bold text-ganaderasoft-azul">04/04/2027</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-800">Novilla #312 (estrella)</td>
                        <td class="py-3 px-4 text-gray-600">10/07/2026</td>
                        <td class="py-3 px-4 text-gray-600">Ia (semen jersey)</td>
                        <td class="py-3 px-4"><span class="px-2 py-0.5 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-md">Pendiente palpación</span></td>
                        <td class="py-3 px-4 text-right text-gray-500 text-xs">Por confirmar</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
