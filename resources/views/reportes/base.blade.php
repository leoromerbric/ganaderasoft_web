@extends('layouts.authenticated')

@section('title', ($titulo ?? 'Reporte') . ' - GanaderaSoft')

@section('content')
<style>
    /* 1. Estilos de pantalla: Contenedor Escritorio y Hojas Tamaño Carta */
    .letter-preview-desk {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 2.5rem 1rem;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .page-wrapper {
        width: 100%;
        max-width: 215.9mm;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .print-sheet {
        width: 100%;
        max-width: 215.9mm; /* Ancho estándar Tamaño Carta (8.5 in) */
        min-height: 279.4mm; /* Alto estándar Tamaño Carta (11 in) */
        background-color: #ffffff;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        border-radius: 2px;
        padding: 14mm 16mm; /* Margen cómodo y amplio para tablas y gráficos */
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    @media print {
        /* Forzar renderizado exacto de colores de fondo, gráficos y barras en la impresión/PDF */
        *, *::before, *::after {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* 2. Configuración de página física a tamaño carta con márgenes estándar de documento */
        @page {
            size: letter portrait !important;
            margin: 12mm 14mm 12mm 14mm !important;
        }

        /* 3. Ocultar la interfaz de usuario de la web */
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

        .print-container, .letter-preview-desk {
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            width: 100% !important;
            display: block !important;
        }

        .page-wrapper {
            max-width: 100% !important;
            width: 100% !important;
            min-height: 251mm !important;
            height: 251mm !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            page-break-after: always !important;
            break-after: page !important;
            box-sizing: border-box !important;
            overflow: visible !important;
        }

        .page-wrapper:last-child {
            page-break-after: avoid !important;
            break-after: avoid !important;
        }

        /* 4. Hoja carta limpia sin sombras que ocupa el 100% del alto */
        .print-sheet {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100% !important;
            min-height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            box-sizing: border-box !important;
        }

        .sheet-content-top {
            flex: 1 1 auto !important;
            display: flex !important;
            flex-direction: column !important;
        }

        /* Evitar cortar filas de tablas, gráficos, tarjetas y notas a la mitad entre páginas */
        tr, td, th, canvas, svg, .chart-container, .no-break, .rounded-xl, .rounded-2xl, .p-4, .p-5, .p-6 {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* Repetir automáticamente el encabezado de las tablas al saltar de página */
        thead {
            display: table-header-group !important;
        }

        /* 5. Pie de página fijo al final horizontal de cada hoja con holgura vertical */
        .print-footer {
            margin-top: auto !important;
            padding-top: 8px !important;
            padding-bottom: 8px !important;
            padding-left: 4px !important;
            padding-right: 4px !important;
            border-top: 1px solid #cbd5e1 !important;
            background-color: transparent !important;
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            font-size: 11px !important;
            line-height: 1.6 !important;
            color: #64748b !important;
            box-sizing: border-box !important;
            overflow: visible !important;
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
                <button type="button" onclick="descargarReportePdf()" class="w-full md:w-auto px-5 py-4 bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-bold text-sm rounded-xl shadow-sm transition-all flex items-center justify-center space-x-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Descargar PDF / imprimir</span>
                </button>
            </div>
        </div>

        <!-- Integrated Filter Bar Line -->
        <div class="border-t border-gray-100 pt-4">
            <form method="GET" action="{{ $routeAction ?? '#' }}" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    @if($mostrarFiltroFinca ?? true)
                        <!-- Selector de Finca -->
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider shrink-0">Finca:</span>
                            <select name="finca_id" class="w-full sm:w-auto min-w-[220px] px-3.5 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all bg-white font-medium text-gray-700 cursor-pointer">
                                <option value="" {{ empty($fincaId) ? 'selected' : '' }}>Todas las fincas</option>
                                @php
                                    $listaFincas = is_array($fincasDisponibles ?? null) ? $fincasDisponibles : (is_array($fincas ?? null) ? $fincas : []);
                                @endphp
                                @foreach($listaFincas as $f)
                                    @php
                                        $fId = is_array($f) ? ($f['id'] ?? ($f['finca_id'] ?? null)) : (is_object($f) ? ($f->id ?? null) : null);
                                        $fNom = is_array($f) ? ($f['nombre'] ?? ($f['nombre_finca'] ?? null)) : (is_object($f) ? ($f->nombre ?? null) : null);
                                    @endphp
                                    @if($fId)
                                        <option value="{{ $fId }}" {{ ($fincaId == $fId) ? 'selected' : '' }}>
                                            {{ $fNom ?? ('Finca #' . $fId) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <span class="hidden sm:inline-block text-gray-300">|</span>
                    @endif

                    <!-- Selector de Período -->
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider shrink-0">Período:</span>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <input type="date" name="fecha_inicio" value="{{ $fechaInicioInput ?? '' }}" placeholder="Desde el inicio" title="Fecha inicio (vacío para histórico completo)" class="w-full sm:w-auto px-3.5 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all">
                            <span class="text-gray-400 text-sm font-medium">a</span>
                            <input type="date" name="fecha_fin" value="{{ $fechaFinInput ?? date('Y-m-d') }}" class="w-full sm:w-auto px-3.5 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full lg:w-auto justify-end">
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold text-sm rounded-xl transition-all shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
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

    <!-- Contenedor fuente original invisible -->
    <div id="reporteRawContent" class="hidden">
        @yield('report_content')
    </div>

    <!-- Previsualización de Hojas Tamaño Carta (Generadas automáticamente) -->
    <div id="reportePagesDesk" class="letter-preview-desk space-y-8">
        <!-- Renderizado dinámico de hojas carta -->
    </div>
</div>

@push('scripts')
<script>
    function renderizarHojasReporte() {
        const rawContainer = document.getElementById('reporteRawContent');
        const deskContainer = document.getElementById('reportePagesDesk');
        if (!rawContainer || !deskContainer) return;

        deskContainer.innerHTML = '';

        const titulo = @json($titulo ?? 'Reporte oficial');
        const fincaNombre = @json($fincaNombre ?? 'Todas las fincas');
        const fechaEmision = @json($fechaEmision ?? date('d/m/Y h:i A'));
        const fechaInicio = @json($fechaInicio ?? null);
        const fechaFin = @json($fechaFin ?? date('d/m/Y'));
        const periodoTexto = fechaInicio ? `${fechaInicio} - ${fechaFin}` : `Histórico consolidado (hasta ${fechaFin})`;
        const logoUrl = @json(asset('images/logo.png'));

        // Encabezado de Página 1 (Oficial completo)
        const headerPagina1 = `
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b-2 border-ganaderasoft-azul pb-5 mb-5 shrink-0">
                <div class="flex items-center space-x-4">
                    <img src="${logoUrl}" alt="Logo" class="w-12 h-12 object-contain">
                    <div>
                        <h2 class="text-2xl font-black text-ganaderasoft-negro tracking-tight">GanaderaSoft</h2>
                        <p class="text-xs font-bold text-ganaderasoft-azul uppercase tracking-wider">Sistema de gestión ganadera</p>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <span class="inline-block px-3 py-1 bg-ganaderasoft-azul/10 text-ganaderasoft-azul rounded-lg text-xs font-bold uppercase tracking-wider mb-1">
                        ${titulo}
                    </span>
                    <p class="text-xs text-gray-700 font-bold">Finca: ${fincaNombre}</p>
                    <p class="text-xs text-gray-500 font-medium">Fecha de emisión: ${fechaEmision}</p>
                    <p class="text-xs text-gray-500 font-medium">Período: ${periodoTexto}</p>
                </div>
            </div>
        `;

        // Encabezado de Páginas 2+ (Continuación compacto)
        const headerPaginaSiguiente = `
            <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-5 shrink-0">
                <div class="flex items-center space-x-3">
                    <img src="${logoUrl}" alt="Logo" class="w-8 h-8 object-contain">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 leading-none">GanaderaSoft</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">${titulo} — ${fincaNombre} (continuación)</p>
                    </div>
                </div>
                <span class="text-xs text-gray-500 font-medium">Período: ${periodoTexto}</span>
            </div>
        `;

        const maxSheetContentHeight = 740; // Altura útil en px con márgenes amplios
        const paginas = [];

        function crearNuevaHoja(numPagina) {
            const wrapper = document.createElement('div');
            wrapper.className = 'page-wrapper';

            const badgeBar = document.createElement('div');
            badgeBar.className = 'no-print w-full flex items-center justify-between pb-2 px-1 text-xs text-gray-500 font-medium';
            badgeBar.innerHTML = `
                <span class="flex items-center gap-1.5 font-semibold text-gray-700">
                    <svg class="w-4 h-4 text-ganaderasoft-azul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="page-badge-text">Página ${numPagina}</span>
                </span>
                <span class="text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-0.5 rounded-md">
                    Tamaño carta (8.5" × 11")
                </span>
            `;

            const sheet = document.createElement('div');
            sheet.className = 'print-sheet';

            const topSection = document.createElement('div');
            topSection.className = 'sheet-content-top flex-1';
            topSection.innerHTML = numPagina === 1 ? headerPagina1 : headerPaginaSiguiente;

            const bodySlot = document.createElement('div');
            bodySlot.className = 'sheet-body space-y-5';
            topSection.appendChild(bodySlot);

            const footer = document.createElement('div');
            footer.className = 'mt-auto pt-3 pb-2 border-t border-gray-200 flex flex-row items-center justify-between text-xs text-gray-500 print-footer shrink-0 w-full px-2';
            footer.innerHTML = `
                <span class="text-left font-medium text-gray-500 tracking-normal">© ${new Date().getFullYear()} GanaderaSoft. Documento generado oficialmente.</span>
                <span class="page-footer-num text-right font-bold text-gray-600">Página ${numPagina}</span>
            `;

            sheet.appendChild(topSection);
            sheet.appendChild(footer);
            wrapper.appendChild(badgeBar);
            wrapper.appendChild(sheet);

            deskContainer.appendChild(wrapper);

            return {
                wrapper: wrapper,
                sheet: sheet,
                body: bodySlot,
                footerNum: footer.querySelector('.page-footer-num'),
                badgeText: badgeBar.querySelector('.page-badge-text')
            };
        }

        let hojaActual = crearNuevaHoja(1);
        paginas.push(hojaActual);

        // Elementos y bloques directos del reporte
        const elementos = Array.from(rawContainer.children);

        for (let el of elementos) {
            const tabla = el.tagName === 'TABLE' ? el : el.querySelector('table');

            if (tabla) {
                const thead = tabla.querySelector('thead');
                const tbody = tabla.querySelector('tbody');
                const filas = tbody ? Array.from(tbody.querySelectorAll('tr')) : [];

                let tablaClone = el.cloneNode(true);
                let tablaInterna = tablaClone.tagName === 'TABLE' ? tablaClone : tablaClone.querySelector('table');
                let tbodyInterno = tablaInterna.querySelector('tbody');
                if (tbodyInterno) tbodyInterno.innerHTML = '';

                hojaActual.body.appendChild(tablaClone);

                for (let fila of filas) {
                    const filaClone = fila.cloneNode(true);
                    tbodyInterno.appendChild(filaClone);

                    if (hojaActual.body.offsetHeight > maxSheetContentHeight && tbodyInterno.children.length > 1) {
                        tbodyInterno.removeChild(filaClone);

                        hojaActual = crearNuevaHoja(paginas.length + 1);
                        paginas.push(hojaActual);

                        tablaClone = el.cloneNode(true);
                        tablaInterna = tablaClone.tagName === 'TABLE' ? tablaClone : tablaClone.querySelector('table');
                        tbodyInterno = tablaInterna.querySelector('tbody');
                        if (tbodyInterno) tbodyInterno.innerHTML = '';
                        tbodyInterno.appendChild(filaClone);
                        hojaActual.body.appendChild(tablaClone);
                    }
                }
            } else {
                // Para tarjetas, notas, gráficos o resúmenes KPI
                const clone = el.cloneNode(true);

                // Si contiene canvas original, copiar su contenido gráfico
                const originalCanvases = el.querySelectorAll('canvas');
                const clonedCanvases = clone.querySelectorAll('canvas');
                if (el.tagName === 'CANVAS' && clone.tagName === 'CANVAS') {
                    const ctx = clone.getContext('2d');
                    ctx.drawImage(el, 0, 0);
                } else if (originalCanvases.length > 0) {
                    originalCanvases.forEach((origCanvas, i) => {
                        const targetCanvas = clonedCanvases[i];
                        if (targetCanvas) {
                            const ctx = targetCanvas.getContext('2d');
                            ctx.drawImage(origCanvas, 0, 0);
                        }
                    });
                }

                hojaActual.body.appendChild(clone);

                // Si excede la hoja y ya hay contenido previo, mover el bloque entero a la nueva hoja
                if (hojaActual.body.offsetHeight > maxSheetContentHeight && hojaActual.body.children.length > 1) {
                    hojaActual.body.removeChild(clone);

                    hojaActual = crearNuevaHoja(paginas.length + 1);
                    paginas.push(hojaActual);

                    hojaActual.body.appendChild(clone);
                }
            }
        }

        const totalHojas = paginas.length;
        paginas.forEach((pag, idx) => {
            const n = idx + 1;
            if (pag.badgeText) pag.badgeText.textContent = `Página ${n} de ${totalHojas}`;
            if (pag.footerNum) pag.footerNum.textContent = `Página ${n} de ${totalHojas}`;
        });

        // Disparar evento para scripts complementarios (como gráficos que requieran re-inicializar)
        window.dispatchEvent(new CustomEvent('ganaderasoft:reporte-paginado', { detail: { totalPaginas: totalHojas } }));
    }

    function descargarReportePdf() {
        const titulo = @json($titulo ?? 'reporte');
        const tituloLimpio = titulo.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        const fechaHoy = @json(date('d-m-Y'));
        const nombreArchivo = `${tituloLimpio || 'reporte'}-${fechaHoy}`;

        const tituloOriginal = document.title;
        document.title = nombreArchivo;
        window.print();

        setTimeout(function () {
            document.title = tituloOriginal;
        }, 2000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', renderizarHojasReporte);
    } else {
        renderizarHojasReporte();
    }
</script>
@endpush
@endsection
