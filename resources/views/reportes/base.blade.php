@extends('layouts.authenticated')

@section('title', ($titulo ?? 'Reporte') . ' - GanaderaSoft')

@section('content')
<style>
    /* 1. Estilos de pantalla: Contenedor Escritorio y Hojas Tamaño Carta con Scroll Horizontal en móvil */
    .letter-preview-desk {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.5rem 0.5rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        display: block;
    }

    .desk-inner-scroller {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 215.9mm;
        min-width: 215.9mm;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .page-wrapper {
        width: 215.9mm;
        min-width: 215.9mm;
        max-width: 215.9mm;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-sizing: border-box;
    }

    .print-sheet {
        width: 215.9mm;
        min-width: 215.9mm;
        max-width: 215.9mm;
        min-height: 279.4mm;
        background-color: #ffffff;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        border-radius: 2px;
        padding: 14mm 16mm;
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
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }

        /* Desactivar cualquier barra de desplazamiento en la impresión */
        ::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        .overflow-x-auto, .overflow-y-auto, .overflow-auto, .sheet-body, .print-sheet, .page-wrapper {
            overflow: visible !important;
            overflow-x: visible !important;
            overflow-y: visible !important;
        }

        /* 2. Configuración de página física a tamaño carta con márgenes estándar */
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

        .print-container, .letter-preview-desk, .desk-inner-scroller {
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            width: 100% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            display: block !important;
        }

        .page-wrapper {
            max-width: 100% !important;
            min-width: 0 !important;
            width: 100% !important;
            min-height: 246mm !important;
            height: 246mm !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            page-break-after: always !important;
            break-after: page !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
        }

        .page-wrapper:last-child {
            page-break-after: avoid !important;
            break-after: avoid !important;
        }

        /* 4. Hoja carta limpia sin sombras */
        .print-sheet {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            height: 100% !important;
            min-height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
        }

        .sheet-content-top {
            flex: 1 1 auto !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
        }

        .sheet-body {
            display: flex !important;
            flex-direction: column !important;
            gap: 1rem !important;
        }

        .sheet-body > * {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
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

        /* 5. Pie de página fijado en la base de cada hoja impresa */
        .print-footer {
            margin-top: auto !important;
            padding-top: 8px !important;
            padding-bottom: 2px !important;
            padding-left: 4px !important;
            padding-right: 4px !important;
            border-top: 1px solid #cbd5e1 !important;
            background-color: transparent !important;
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            font-size: 10.5px !important;
            line-height: 1.4 !important;
            color: #64748b !important;
            box-sizing: border-box !important;
            flex-shrink: 0 !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            page-break-after: avoid !important;
            break-after: avoid !important;
        }
    }
</style>

<div class="space-y-8 print-container">
    <!-- Header & Filter Unified Panel (no-print) -->
    <div class="no-print bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
        <!-- Top Row: Title, Subtitle & Print Action -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start sm:items-center space-x-3 sm:space-x-4 pl-9 sm:pl-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul flex items-center justify-center text-xl sm:text-2xl shrink-0 mt-0.5 sm:mt-0">
                    {{ $icon ?? '📊' }}
                </div>
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-ganaderasoft-negro leading-tight">{{ $titulo ?? 'Reporte' }}</h1>
                    <p class="text-gray-500 text-xs sm:text-sm mt-0.5">{{ $subtitulo ?? 'Generación y consulta de documentos e informes del sistema' }}</p>
                </div>
            </div>
            <div class="w-full sm:w-auto shrink-0">
                <button type="button" onclick="descargarReportePdf()" class="w-full sm:w-auto px-5 py-3 bg-ganaderasoft-azul hover:bg-opacity-90 text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm transition-all flex items-center justify-center space-x-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Descargar PDF / imprimir</span>
                </button>
            </div>
        </div>

        <!-- Integrated Filter Bar Line -->
        <div class="border-t border-gray-100 pt-4">
            <form method="GET" action="{{ $routeAction ?? '#' }}" class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-stretch sm:items-end gap-3.5 flex-1 w-full">
                    @if($mostrarFiltroFinca ?? true)
                        <!-- Selector de Finca -->
                        <div class="col-span-1 sm:col-span-2 lg:flex-1 lg:min-w-[200px] lg:max-w-xs flex flex-col">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Finca</label>
                            <select name="finca_id" class="w-full h-10 px-3.5 border border-gray-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all bg-white font-medium text-gray-700 cursor-pointer shadow-2xs">
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
                    @endif

                    <!-- Fecha Desde -->
                    <div class="col-span-1 lg:flex-1 lg:min-w-[150px] lg:max-w-xs flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ $fechaInicioInput ?? '' }}" placeholder="Desde el inicio" title="Fecha inicio (vacío para histórico completo)" class="w-full h-10 px-3 border border-gray-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all bg-white shadow-2xs">
                    </div>

                    <!-- Fecha Hasta -->
                    <div class="col-span-1 lg:flex-1 lg:min-w-[150px] lg:max-w-xs flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Fecha fin</label>
                        <input type="date" name="fecha_fin" value="{{ $fechaFinInput ?? date('Y-m-d') }}" class="w-full h-10 px-3 border border-gray-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-all bg-white shadow-2xs">
                    </div>
                </div>

                <!-- Botones de Acción (Ocupan el 100% proporcional en móvil/apilado y auto en escritorio) -->
                <div class="grid grid-cols-3 sm:flex items-center gap-2.5 h-10 shrink-0 w-full lg:w-auto justify-end">
                    <button type="submit" class="col-span-2 sm:flex-initial h-10 px-5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-sm flex items-center justify-center space-x-1.5 cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Generar reporte</span>
                    </button>
                    <a href="{{ $routeAction ?? '#' }}" class="col-span-1 sm:flex-initial h-10 px-4 bg-gray-100 text-gray-700 font-semibold text-xs sm:text-sm rounded-xl hover:bg-gray-200 transition-all flex items-center justify-center whitespace-nowrap">
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

    <!-- Previsualización de Hojas Tamaño Carta (Generadas automáticamente con scroll horizontal en móvil) -->
    <div class="letter-preview-desk">
        <div class="lg:hidden text-center text-xs text-gray-500 font-semibold pb-3 flex items-center justify-center gap-1.5 no-print">
            <svg class="w-4 h-4 text-ganaderasoft-azul animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <span>Desliza horizontalmente para navegar por el documento</span>
        </div>
        <div id="reportePagesDesk" class="desk-inner-scroller space-y-8">
            <!-- Renderizado dinámico de hojas carta -->
        </div>
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

        function excedeCapacidadHoja(hoja, numPagina) {
            // Medición dinámica basada en la capacidad física de la hoja Letter (279.4mm ≈ 1056px)
            // Descontamos: paddings (106px), cabecera, pie de página y margen de seguridad
            const headerEl = hoja.sheet.querySelector('.sheet-content-top > div:first-child');
            const footerEl = hoja.sheet.querySelector('.print-footer');
            
            const headerH = headerEl ? headerEl.offsetHeight : (numPagina === 1 ? 140 : 60);
            const footerH = footerEl ? footerEl.offsetHeight : 45;
            const paddingSheet = 106; // 14mm superior + 14mm inferior
            const sheetTotalH = hoja.sheet.offsetHeight || 1056;

            // Altura útil real disponible para el cuerpo dejando margen de seguridad respecto al pie
            const maxBodyHeight = sheetTotalH - paddingSheet - headerH - footerH - 45;
            
            return hoja.body.offsetHeight > maxBodyHeight;
        }

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
            bodySlot.className = 'sheet-body space-y-4';
            topSection.appendChild(bodySlot);

            const footer = document.createElement('div');
            footer.className = 'mt-auto pt-3 pb-1 border-t border-gray-200 flex flex-row items-center justify-between text-xs text-gray-500 print-footer shrink-0 w-full px-1';
            footer.innerHTML = `
                <span class="text-left font-medium text-gray-500 tracking-normal pl-0.5">© ${new Date().getFullYear()} GanaderaSoft. Documento generado oficialmente.</span>
                <span class="page-footer-num text-right font-bold text-gray-600 pr-0.5">Página ${numPagina}</span>
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

                    if (excedeCapacidadHoja(hojaActual, paginas.length) && tbodyInterno.children.length > 1) {
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
                if (excedeCapacidadHoja(hojaActual, paginas.length) && hojaActual.body.children.length > 1) {
                    hojaActual.body.removeChild(clone);

                    hojaActual = crearNuevaHoja(paginas.length + 1);
                    paginas.push(hojaActual);

                    hojaActual.body.appendChild(clone);
                }
            }
        }

        // Limpiar hojas vacías si alguna quedara sin contenido útil
        for (let i = paginas.length - 1; i > 0; i--) {
            const pag = paginas[i];
            const filasTabla = pag.body.querySelectorAll('tbody tr').length;
            const tieneOtros = pag.body.children.length > 0 && !pag.body.querySelector('table');
            if (filasTabla === 0 && !tieneOtros && pag.body.innerText.trim().length === 0) {
                if (pag.wrapper && pag.wrapper.parentNode) {
                    pag.wrapper.parentNode.removeChild(pag.wrapper);
                }
                paginas.splice(i, 1);
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
