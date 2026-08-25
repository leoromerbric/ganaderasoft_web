@extends('reportes.base', [
    'titulo' => 'Reporte de pesaje de leche',
    'subtitulo' => 'Monitoreo de pesajes diarios, promedios de lactancia y rendimiento por ordeño',
    'icon' => '🥛',
    'routeAction' => route('reportes.pesaje-leche')
])

@section('report_content')
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

    <!-- Tabla Principal de Pesajes Consolidados -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Pesajes consolidados por lote</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Fecha pesaje</th>
                        <th class="py-2.5 px-3">Rebaño / lote</th>
                        <th class="py-2.5 px-3">Ordeño mañana</th>
                        <th class="py-2.5 px-3">Ordeño tarde</th>
                        <th class="py-2.5 px-3 text-right">Total día</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">14/08/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">Lote alta producción</td>
                        <td class="py-2.5 px-3 text-gray-700">420.5 lts</td>
                        <td class="py-2.5 px-3 text-gray-700">340.0 lts</td>
                        <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul">760.5 lts</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">13/08/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">Lote alta producción</td>
                        <td class="py-2.5 px-3 text-gray-700">415.0 lts</td>
                        <td class="py-2.5 px-3 text-gray-700">338.5 lts</td>
                        <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul">753.5 lts</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">12/08/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">Lote media producción</td>
                        <td class="py-2.5 px-3 text-gray-700">210.0 lts</td>
                        <td class="py-2.5 px-3 text-gray-700">175.0 lts</td>
                        <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul">385.0 lts</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">11/08/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">Lote media producción</td>
                        <td class="py-2.5 px-3 text-gray-700">205.5 lts</td>
                        <td class="py-2.5 px-3 text-gray-700">170.0 lts</td>
                        <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul">375.5 lts</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">10/08/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">Lote alta producción</td>
                        <td class="py-2.5 px-3 text-gray-700">418.0 lts</td>
                        <td class="py-2.5 px-3 text-gray-700">335.0 lts</td>
                        <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul">753.0 lts</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">09/08/2026</td>
                        <td class="py-2.5 px-3 text-gray-600">Lote media producción</td>
                        <td class="py-2.5 px-3 text-gray-700">212.0 lts</td>
                        <td class="py-2.5 px-3 text-gray-700">172.0 lts</td>
                        <td class="py-2.5 px-3 text-right font-black text-ganaderasoft-azul">384.0 lts</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla Secundaria de Rendimiento Individual -->
    <div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Rendimiento individual por vaca</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/90 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Arete / animal</th>
                        <th class="py-2.5 px-3">Lactancia</th>
                        <th class="py-2.5 px-3">Días en ordeño</th>
                        <th class="py-2.5 px-3">Litros / día</th>
                        <th class="py-2.5 px-3 text-right">Variación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #104 (Mariposa)</td>
                        <td class="py-2.5 px-3 text-gray-600">3ra lactancia</td>
                        <td class="py-2.5 px-3 text-gray-600">120 días</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">22.4 lts</td>
                        <td class="py-2.5 px-3 text-right text-emerald-600 font-semibold">+1.2 lts</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #208 (Lucero)</td>
                        <td class="py-2.5 px-3 text-gray-600">2da lactancia</td>
                        <td class="py-2.5 px-3 text-gray-600">85 días</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">19.8 lts</td>
                        <td class="py-2.5 px-3 text-right text-emerald-600 font-semibold">+0.5 lts</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #115 (Campana)</td>
                        <td class="py-2.5 px-3 text-gray-600">4ta lactancia</td>
                        <td class="py-2.5 px-3 text-gray-600">210 días</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">16.5 lts</td>
                        <td class="py-2.5 px-3 text-right text-amber-600 font-semibold">-0.8 lts</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-3 font-semibold text-gray-800">Vaca #302 (Canela)</td>
                        <td class="py-2.5 px-3 text-gray-600">1ra lactancia</td>
                        <td class="py-2.5 px-3 text-gray-600">65 días</td>
                        <td class="py-2.5 px-3 font-bold text-ganaderasoft-azul">18.0 lts</td>
                        <td class="py-2.5 px-3 text-right text-emerald-600 font-semibold">+0.3 lts</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gráfica Visual de Tendencia Semanal de Ordeño -->
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tendencia de producción diaria (últimos 7 días)</h3>
                <p class="text-[11px] text-gray-500">Comportamiento del volumen total en litros</p>
            </div>
            <span class="text-xs font-bold text-ganaderasoft-azul bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg">
                Promedio: 1,485 Lts/día
            </span>
        </div>
        
        <!-- Barras de Rendimiento Visual con colores forzados para impresión -->
        <div class="grid grid-cols-7 gap-2 pt-4 items-end h-36 border-b border-gray-200 pb-2">
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">1,390L</span>
                <div class="w-full rounded-t-md" style="height: 70%; background-color: #bfdbfe;"></div>
                <span class="text-[10px] text-gray-500 font-medium">08/08</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">1,410L</span>
                <div class="w-full rounded-t-md" style="height: 74%; background-color: #bfdbfe;"></div>
                <span class="text-[10px] text-gray-500 font-medium">09/08</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">1,435L</span>
                <div class="w-full rounded-t-md" style="height: 78%; background-color: #93c5fd;"></div>
                <span class="text-[10px] text-gray-500 font-medium">10/08</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">1,460L</span>
                <div class="w-full rounded-t-md" style="height: 84%; background-color: #60a5fa;"></div>
                <span class="text-[10px] text-gray-500 font-medium">11/08</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">1,475L</span>
                <div class="w-full rounded-t-md" style="height: 88%; background-color: #60a5fa;"></div>
                <span class="text-[10px] text-gray-500 font-medium">12/08</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-gray-700">1,480L</span>
                <div class="w-full rounded-t-md" style="height: 92%; background-color: #2563eb;"></div>
                <span class="text-[10px] text-gray-500 font-medium">13/08</span>
            </div>
            <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                <span class="text-[10px] font-bold text-ganaderasoft-azul">1,485L</span>
                <div class="w-full rounded-t-md shadow-sm" style="height: 96%; background-color: #1d4ed8;"></div>
                <span class="text-[10px] font-bold text-ganaderasoft-azul">14/08</span>
            </div>
        </div>
    </div>

    <!-- Notas y Observaciones Técnicas -->
    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Notas y observaciones técnicas</h4>
        <p class="text-xs text-gray-600 leading-relaxed">
            La curva de lactancia del lote de alta producción muestra estabilidad favorable con una eficiencia promedio de 18.2 Lts/vaca/día. Se recomienda mantener el suplemento proteico actual durante el próximo período de evaluación.
        </p>
    </div>
@endsection
