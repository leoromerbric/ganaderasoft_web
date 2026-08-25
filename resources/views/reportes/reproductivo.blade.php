@extends('reportes.base', [
    'titulo' => 'Reporte reproductivo',
    'subtitulo' => 'Análisis integral de celos, servicios, gestaciones, palpaciones y partos',
    'icon' => '🍼',
    'routeAction' => route('reportes.reproductivo')
])

@section('report_content')
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

    <!-- Tabla de Servicios y Diagnósticos -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Servicios y diagnósticos de palpación</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Arete / animal</th>
                        <th class="py-2.5 px-3">Último servicio</th>
                        <th class="py-2.5 px-3">Tipo de servicio</th>
                        <th class="py-2.5 px-3">Diagnóstico palpación</th>
                        <th class="py-2.5 px-3 text-right">Fecha prob. parto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #104 (Mariposa)</td>
                        <td class="py-2.5 px-3 text-gray-600">12/05/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">IA (Semen Holstein)</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Gestante (3m)</span></td>
                        <td class="py-2.5 px-3 text-right font-bold text-ganaderasoft-azul">18/02/2027</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #208 (Lucero)</td>
                        <td class="py-2.5 px-3 text-gray-600">28/06/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">Monta natural</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Gestante (2m)</span></td>
                        <td class="py-2.5 px-3 text-right font-bold text-ganaderasoft-azul">04/04/2027</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Novilla #312 (Estrella)</td>
                        <td class="py-2.5 px-3 text-gray-600">10/07/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">IA (Semen Jersey)</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-md">Pendiente palpación</span></td>
                        <td class="py-2.5 px-3 text-right text-gray-500 text-xs">Por confirmar</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #109 (Paloma)</td>
                        <td class="py-2.5 px-3 text-gray-600">15/07/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">IA (Semen Gyr)</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-md">Pendiente palpación</span></td>
                        <td class="py-2.5 px-3 text-right text-gray-500 text-xs">Por confirmar</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #215 (Canela)</td>
                        <td class="py-2.5 px-3 text-gray-600">20/07/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">Monta natural</td>
                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Gestante (1m)</span></td>
                        <td class="py-2.5 px-3 text-right font-bold text-ganaderasoft-azul">26/04/2027</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de Calendario de Partos y Secados -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Calendario programado de partos y secados</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Arete / animal</th>
                        <th class="py-2.5 px-3">Días de gestación</th>
                        <th class="py-2.5 px-3">Fecha secado</th>
                        <th class="py-2.5 px-3">Fecha probable parto</th>
                        <th class="py-2.5 px-3 text-right">Prioridad</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #055 (Princesa)</td>
                        <td class="py-2.5 px-3 text-gray-600">265 días</td>
                        <td class="py-2.5 px-3 text-gray-600">01/08/2026</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">15/09/2026</td>
                        <td class="py-2.5 px-3 text-right"><span class="px-2 py-0.5 text-xs font-bold bg-red-100 text-red-800 rounded-md">Inminente</span></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #089 (Margarita)</td>
                        <td class="py-2.5 px-3 text-gray-600">250 días</td>
                        <td class="py-2.5 px-3 text-gray-600">15/08/2026</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">30/09/2026</td>
                        <td class="py-2.5 px-3 text-right"><span class="px-2 py-0.5 text-xs font-bold bg-amber-100 text-amber-800 rounded-md">Atención</span></td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #104 (Mariposa)</td>
                        <td class="py-2.5 px-3 text-gray-600">95 días</td>
                        <td class="py-2.5 px-3 text-gray-600">18/12/2026</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">18/02/2027</td>
                        <td class="py-2.5 px-3 text-right"><span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-800 rounded-md">Normal</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recomendaciones Reproductivas -->
    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Recomendaciones del plan reproductivo</h4>
        <p class="text-xs text-gray-600 leading-relaxed">
            Programar jornada de palpación para las novillas pendientes de confirmación. Trasladar al potrero de maternidad a los animales con más de 260 días de gestación para monitoreo preparto continuo.
        </p>
    </div>
@endsection
