@extends('layouts.authenticated')

@section('title', ($titulo ?? 'Reporte') . ' - GanaderaSoft')

@section('content')
<style>
    @media print {
        /* 1. Configuración de página: A4 horizontal (landscape) para tablas anchas sin recorte */
        @page {
            margin: 8mm 10mm 12mm 10mm !important;
            size: {{ $pageSize ?? 'A4 landscape' }};
        }

        /* 2. Ocultar la interfaz de usuario de la web y navegación */
        header, nav, aside, #sidebar, #sidebar-toggle-wrapper, .no-print {
            display: none !important;
        }
        
        html, body, main, #main-content {
            background-color: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            height: auto !important;
            overflow: visible !important;
        }

        .print-container {
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* 3. Hoja A4 limpia con espacio para el footer fijo */
        .print-sheet {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            min-height: 0 !important;
            display: block !important;
            padding-bottom: 12mm !important;
        }

        /* 4. ELIMINAR SCROLLBARS Y CONTENEDORES DE RECORTE */
        .overflow-x-auto,
        .overflow-y-auto,
        .overflow-hidden,
        .overflow-auto,
        div[class*="overflow-"] {
            overflow: visible !important;
            overflow-x: visible !important;
            overflow-y: visible !important;
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* 5. Ajuste automático de tablas para impresión en toda la página */
        table {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-size: 7.5pt !important;
        }

        th, td {
            padding: 3px 5px !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }

        th {
            background-color: #f3f4f6 !important;
            color: #111827 !important;
            font-weight: 700 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* 6. Evitar cortar filas de tablas a la mitad entre páginas */
        tr, .no-break {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* 7. Repetir automáticamente el encabezado de las tablas al saltar de página */
        thead {
            display: table-header-group !important;
        }

        /* 8. Repetir el footer al final de CADA PÁGINA del informe */
        .print-footer {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            margin-top: 0 !important;
            padding-top: 0.4rem !important;
            border-top: 1px solid #e5e7eb !important;
            background-color: #ffffff !important;
            font-size: 7pt !important;
        }
    }
</style>

<div class="space-y-8 print-container">
    <!-- Header & Filter Unified Panel (no-print) -->
    <div class="no-print bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-5">
        <!-- Top Row: Title, Subtitle & Print Action -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul flex items-center justify-center text-2xl shrink-0">
                    {{ $icon ?? '📊' }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-ganaderasoft-negro">{{ $titulo ?? 'Reporte' }}</h1>
                    <p class="text-gray-500 text-sm mt-0.5">{{ $subtitulo ?? 'Generación y consulta de documentos e informes del sistema' }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <button onclick="window.print()" class="w-full md:w-auto px-5 py-4 bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-bold text-sm rounded-xl shadow-sm transition-all flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Descargar PDF / Imprimir</span>
                </button>
            </div>
        </div>

        <!-- Integrated Filter Bar Line -->
        <div class="border-t border-gray-100 pt-4">
            <form method="GET" action="{{ $routeAction ?? '#' }}" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    @if(!empty($fincas) && is_array($fincas) && count($fincas) > 0)
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider shrink-0">Finca:</span>
                            <select name="finca_id" onchange="this.form.submit()" class="px-3.5 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all bg-white font-semibold text-gray-800 cursor-pointer">
                                @foreach($fincas as $f)
                                    @if(is_array($f) && isset($f['id'], $f['nombre']))
                                        <option value="{{ $f['id'] }}" {{ ($fincaId ?? null) == $f['id'] ? 'selected' : '' }}>
                                            {{ $f['nombre'] }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider shrink-0">Período:</span>
                        <input type="date" name="fecha_inicio" value="{{ $filters['fecha_inicio'] ?? '' }}" class="px-3.5 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all">
                        <span class="text-gray-400 text-sm font-medium">a</span>
                        <input type="date" name="fecha_fin" value="{{ $filters['fecha_fin'] ?? '' }}" class="px-3.5 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all">
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full lg:w-auto justify-end">
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold text-sm rounded-xl transition-all shadow-sm flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Generar reporte</span>
                    </button>
                    <a href="{{ $routeAction ?? '#' }}" class="w-full sm:w-auto text-center px-4 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-all">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Previsualización del Documento (Hoja Imprimible/PDF) -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 sm:p-6 md:p-8 max-w-7xl mx-auto print-sheet">
        <div>
            <!-- Header Oficial del Documento -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b-2 border-ganaderasoft-azul pb-5 mb-5">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                    <div>
                        <h2 class="text-2xl font-black text-ganaderasoft-negro tracking-tight">GanaderaSoft</h2>
                        <p class="text-xs font-bold text-ganaderasoft-azul uppercase tracking-wider">Sistema de gestión ganadera</p>
                        @if(!empty($reporte['finca']['nombre']))
                            <p class="text-sm font-bold text-ganaderasoft-azul mt-0.5">Finca: {{ $reporte['finca']['nombre'] }}</p>
                        @endif
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <span class="inline-block px-3 py-1 bg-ganaderasoft-azul/10 text-ganaderasoft-azul rounded-lg text-xs font-bold uppercase tracking-wider mb-1">
                        {{ $titulo ?? 'Reporte oficial' }}
                    </span>
                    <p class="text-xs text-gray-500 font-medium">Fecha de emisión: {{ $fechaEmision ?? date('d/m/Y h:i A') }}</p>
                    @if(!empty($filters['fecha_inicio']) || !empty($filters['fecha_fin']))
                        <p class="text-xs text-gray-500 font-medium">
                            Período: {{ $filters['fecha_inicio'] ?? 'Inicio' }} al {{ $filters['fecha_fin'] ?? 'Hoy' }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Document Body Slot -->
            @yield('report_content')
        </div>

        <!-- Footer del Documento (Repetido en cada página al imprimir) -->
        <div class="mt-8 pt-4 border-t border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between text-xs text-gray-400 print-footer">
            <p>© {{ date('Y') }} GanaderaSoft. Documento generado oficialmente.</p>
            <p>Documento oficial del sistema</p>
        </div>
    </div>
</div>
@endsection
