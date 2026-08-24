@extends('layouts.authenticated')

@section('title', 'Registrar reproducción animal')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🔬
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Registrar reproducción animal
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra eventos reproductivos y ginecológicos para el control de fertilidad</p>
            </div>
        </div>
        <div>
            <a href="{{ route('reproduccion-animal.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm space-y-1">
            <div class="flex items-center space-x-2 font-bold mb-1">
                <span class="text-lg">⚠️</span>
                <p class="text-sm">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario Principal -->
    <form method="POST" action="{{ route('reproduccion-animal.store') }}" id="formReproduccionAnimal" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 flex flex-col space-y-6">
                
                <!-- Card 1: Selección de la Hembra Receptora -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐄</span> Selección de la hembra
                    </h3>

                    <!-- Filtros Auxiliares Finca y Rebaño -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50/80 rounded-2xl border border-gray-200/80">
                        <div>
                            <label for="helper_finca" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Filtrar por finca
                            </label>
                            <select id="helper_finca"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="">Todas las fincas</option>
                                @foreach($fincas as $finca)
                                    @php
                                        $fId = $finca['id'] ?? $finca['id_Finca'] ?? '';
                                        $fNombre = $finca['nombre'] ?? $finca['Nombre'] ?? ('Finca #'.$fId);
                                    @endphp
                                    <option value="{{ $fId }}">{{ $fNombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="helper_rebano" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Filtrar por rebaño
                            </label>
                            <select id="helper_rebano"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="">Todos los rebaños</option>
                                @foreach($rebanos as $rebano)
                                    @php
                                        $rId = $rebano['id'] ?? $rebano['id_Rebano'] ?? '';
                                        $rNombre = $rebano['nombre'] ?? $rebano['Nombre'] ?? ('Rebaño #'.$rId);
                                        $rFincaId = $rebano['finca_id'] ?? $rebano['id_Finca'] ?? data_get($rebano, 'finca.id') ?? '';
                                    @endphp
                                    <option value="{{ $rId }}" data-finca-id="{{ $rFincaId }}">{{ $rNombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Selector Principal del Animal -->
                    <div>
                        <label for="repro_animal_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Hembra para el registro <span class="text-red-500">*</span>
                        </label>
                        <select name="animal_id" id="repro_animal_id" required
                                class="w-full px-4 py-3 border @error('animal_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                            <option value="">-- Seleccionar hembra receptora --</option>
                            @foreach($animales as $animal)
                                @php
                                    $aId = $animal['id'] ?? $animal['id_Animal'] ?? '';
                                    $aNombre = $animal['Nombre'] ?? $animal['nombre'] ?? ('Animal #'.$aId);
                                    $aCodigo = $animal['codigo_animal'] ?? $animal['Codigo'] ?? '';
                                    
                                    $etapaId = (string)(data_get($animal, 'etapa_actual.etapa.id') ?? data_get($animal, 'etapa_actual.etapa.id_Etapa') ?? data_get($animal, 'etapa_actual.etapa_id') ?? data_get($animal, 'etapa.id') ?? '');
                                    $etapaNombre = data_get($animal, 'etapa_actual.etapa.nombre') 
                                        ?? data_get($animal, 'etapa_actual.etapa.Nombre') 
                                        ?? data_get($animal, 'etapa_actual.nombre') 
                                        ?? data_get($animal, 'etapa_actual.etapa_nombre') 
                                        ?? data_get($animal, 'etapa.nombre') 
                                        ?? 'En producción';

                                    $rId = (string) (data_get($animal, 'rebano.id') ?? data_get($animal, 'rebano.id_Rebano') ?? data_get($animal, 'rebano_id') ?? ($animal['id_Rebano'] ?? ''));
                                    $rNombre = data_get($animal, 'rebano.nombre') ?? data_get($animal, 'rebano.Nombre') ?? '';
                                    $fId = (string) (data_get($animal, 'rebano.finca.id') ?? data_get($animal, 'rebano.finca.id_Finca') ?? data_get($animal, 'rebano.finca_id') ?? data_get($animal, 'finca_id') ?? ($animal['rebano']['id_Finca'] ?? ''));
                                    $fNombre = data_get($animal, 'rebano.finca.nombre') ?? data_get($animal, 'rebano.finca.Nombre') ?? ($fId ? 'Finca #'.$fId : '');
                                @endphp
                                <option value="{{ $aId }}"
                                        data-nombre="{{ $aNombre }}"
                                        data-codigo="{{ $aCodigo }}"
                                        data-etapa-id="{{ $etapaId }}"
                                        data-etapa-nombre="{{ $etapaNombre }}"
                                        data-rebano-id="{{ $rId }}"
                                        data-rebano-nombre="{{ $rNombre }}"
                                        data-finca-id="{{ $fId }}"
                                        data-finca-nombre="{{ $fNombre }}"
                                        {{ (string)old('animal_id', $presetAnimalId ?? request('animal_id')) === (string)$aId ? 'selected' : '' }}>
                                    {{ $aNombre }} {{ $aCodigo ? '(#'.$aCodigo.')' : '' }} {{ $rNombre ? '• '.$rNombre : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('animal_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Card 2: Parámetros del Evento Reproductivo -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col flex-1 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🔬</span> Parámetros del evento reproductivo
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Etapa Actual (Auto-asignada) -->
                        <div>
                            <label for="repro_etapa_texto" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Etapa productiva <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="repro_etapa_texto" readonly
                                   placeholder="Se completará al seleccionar la hembra"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-700 font-medium focus:outline-none">
                            <input type="hidden" name="etapa_id" id="repro_etapa_etid" value="{{ old('etapa_id') }}">
                            @error('etapa_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Tipo de Reproducción -->
                        <div>
                            <label for="tipo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Tipo de evento reproductivo <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo" id="tipo" required
                                    class="w-full px-4 py-3 border @error('tipo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="IA" {{ old('tipo', 'IA') == 'IA' ? 'selected' : '' }}>🧬 Inseminación Artificial (IA)</option>
                                <option value="Natural" {{ old('tipo') == 'Natural' ? 'selected' : '' }}>🐂 Monta Natural</option>
                                <option value="Parto" {{ old('tipo') == 'Parto' ? 'selected' : '' }}>🐣 Parto</option>
                                <option value="Aborto" {{ old('tipo') == 'Aborto' ? 'selected' : '' }}>⚠️ Aborto</option>
                                <option value="Palpación" {{ old('tipo') == 'Palpación' ? 'selected' : '' }}>🩺 Palpación / Diagnóstico</option>
                            </select>
                            @error('tipo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha del Evento -->
                        <div class="md:col-span-2">
                            <label for="fecha" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha del evento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha" id="fecha" required 
                                   value="{{ old('fecha', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border @error('fecha') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('fecha')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="flex-1 flex flex-col pt-2">
                        <label for="observacion" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Observaciones clínicas y notas reproductivas <span class="text-xs font-normal text-gray-400 normal-case">(opcional)</span>
                        </label>
                        <textarea name="observacion" id="observacion" rows="6" maxlength="100"
                                  placeholder="ej. Buena respuesta al servicio, revisión ginecológica favorable..."
                                  class="w-full flex-1 min-h-[160px] px-4 py-3 border @error('observacion') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white leading-relaxed">{{ old('observacion') }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            @error('observacion')
                                <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                            @else
                                <span></span>
                            @enderror
                            <span class="text-[11px] text-gray-400">Máx. 100 caracteres</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <!-- Card 1: Resumen y Acciones -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen del evento
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-2xl shadow-xs shrink-0">
                                🐄
                            </div>
                            <div class="overflow-hidden">
                                <p id="previewNombre" class="text-base font-bold text-gray-900 truncate">Sin seleccionar</p>
                                <p id="previewCodigo" class="text-xs text-gray-500 font-mono">Código: #---</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Ubicación:</span>
                                <span id="previewUbicacion" class="font-bold text-gray-900 text-right truncate">No especificada</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Etapa actual:</span>
                                <span id="previewEtapa" class="font-bold text-pink-700 text-right">No seleccionada</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Tipo de evento:</span>
                                <span id="previewTipo" class="font-bold text-blue-700 text-right">Inseminación (IA)</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Fecha del evento:</span>
                                <span id="previewFecha" class="font-bold text-gray-900 text-right">Hoy</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Retorno celo (+21d):</span>
                                <span id="previewProximoCelo" class="font-bold text-purple-700 font-mono text-right">—</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Chequeo preñez (+45d):</span>
                                <span id="previewPalpacion" class="font-bold text-emerald-700 font-mono text-right">—</span>
                            </div>
                        </div>

                        <!-- Mensaje Proyección -->
                        <div class="p-3.5 bg-purple-50/70 border border-purple-100 rounded-xl space-y-1 text-xs text-purple-900 leading-relaxed">
                            <strong class="block font-bold">Proyección ginecológica:</strong>
                            <p>Supervisar retorno al celo a los <strong>+21 días</strong> post-evento. Programar palpación o ecografía a los <strong>+45 a +60 días</strong>.</p>
                        </div>

                        <!-- Action Buttons en el Sidebar -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar registro reproductivo
                            </button>
                            <a href="{{ route('reproduccion-animal.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Guía y Buenas Prácticas Reproductivas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>💡</span> Buenas prácticas reproductivas
                    </h4>
                    
                    <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 space-y-1 leading-relaxed">
                        <strong class="block font-bold">Monitoreo del ciclo estral:</strong>
                        <p>• El ciclo estral bovino promedio es de <strong>21 días</strong> (rango 18-24 días).</p>
                        <p>• Registra cualquier manifestación de celo o monta para asegurar la trazabilidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const aSelect = document.getElementById('repro_animal_id');
    const fHelper = document.getElementById('helper_finca');
    const rHelper = document.getElementById('helper_rebano');
    const tipoSelect = document.getElementById('tipo');
    const fechaInput = document.getElementById('fecha');
    const etapaIdHidden = document.getElementById('repro_etapa_etid');
    const etapaTextoInput = document.getElementById('repro_etapa_texto');

    // Sidebar previews
    const previewNombre = document.getElementById('previewNombre');
    const previewCodigo = document.getElementById('previewCodigo');
    const previewUbicacion = document.getElementById('previewUbicacion');
    const previewEtapa = document.getElementById('previewEtapa');
    const previewTipo = document.getElementById('previewTipo');
    const previewFecha = document.getElementById('previewFecha');
    const previewProximoCelo = document.getElementById('previewProximoCelo');
    const previewPalpacion = document.getElementById('previewPalpacion');

    const etapasCatalog = {
        @foreach($etapas as $e)
            '{{ $e['id'] ?? $e['id_Etapa'] ?? '' }}': '{{ $e['nombre'] ?? $e['Nombre'] ?? '' }}',
        @endforeach
    };

    function filtrarRebanos() {
        if (!fHelper || !rHelper) return;
        const selectedFinca = fHelper.value;

        Array.from(rHelper.options).forEach(opt => {
            if (!opt.value) return;
            const optFinca = opt.dataset.fincaId || '';
            if (!selectedFinca) {
                opt.hidden = false;
            } else {
                opt.hidden = (optFinca !== selectedFinca);
            }
        });

        if (rHelper.selectedOptions[0] && rHelper.selectedOptions[0].hidden) {
            rHelper.value = '';
        }
    }

    function filtrarAnimales() {
        const sf = fHelper ? fHelper.value : '';
        const sr = rHelper ? rHelper.value : '';

        if (!aSelect) return;

        Array.from(aSelect.options).forEach(opt => {
            if (!opt.value) return;
            const anFid = opt.dataset.fincaId || '';
            const anRid = opt.dataset.rebanoId || '';

            let visible = true;
            if (sf && anFid !== sf) visible = false;
            if (sr && anRid !== sr) visible = false;

            opt.hidden = !visible;
        });

        if (aSelect.selectedOptions[0] && aSelect.selectedOptions[0].hidden) {
            aSelect.value = '';
        }

        updateAllPreviews();
    }

    function calculateDates() {
        const val = fechaInput ? fechaInput.value : '';
        if (!val) {
            if (previewFecha) previewFecha.textContent = 'Hoy';
            if (previewProximoCelo) previewProximoCelo.textContent = '—';
            if (previewPalpacion) previewPalpacion.textContent = '—';
            return;
        }

        const parts = val.split('-');
        if (parts.length === 3) {
            const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            if (previewFecha) previewFecha.textContent = ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();

            // +21 days (estrus repeat)
            const nextCelo = new Date(d);
            nextCelo.setDate(nextCelo.getDate() + 21);
            if (previewProximoCelo) previewProximoCelo.textContent = ('0' + nextCelo.getDate()).slice(-2) + '/' + ('0' + (nextCelo.getMonth() + 1)).slice(-2) + '/' + nextCelo.getFullYear();

            // +45 days (palpation / pregnancy check)
            const nextPalp = new Date(d);
            nextPalp.setDate(nextPalp.getDate() + 45);
            if (previewPalpacion) previewPalpacion.textContent = ('0' + nextPalp.getDate()).slice(-2) + '/' + ('0' + (nextPalp.getMonth() + 1)).slice(-2) + '/' + nextPalp.getFullYear();
        }
    }

    function updateAllPreviews() {
        const selectedOpt = aSelect ? aSelect.options[aSelect.selectedIndex] : null;
        if (selectedOpt && selectedOpt.value) {
            if (previewNombre) previewNombre.textContent = selectedOpt.dataset.nombre || 'Hembra seleccionada';
            if (previewCodigo) previewCodigo.textContent = selectedOpt.dataset.codigo ? ('Código: #' + selectedOpt.dataset.codigo) : ('ID: #' + selectedOpt.value);

            const ubicParts = [selectedOpt.dataset.fincaNombre, selectedOpt.dataset.rebanoNombre].filter(Boolean);
            if (previewUbicacion) previewUbicacion.textContent = ubicParts.length ? ubicParts.join(' • ') : 'Asignada';

            const etapaId = selectedOpt.dataset.etapaId || '';
            const etapaNombre = selectedOpt.dataset.etapaNombre 
                || (etapaId && etapasCatalog[etapaId]) 
                || (etapaId ? `Etapa #${etapaId}` : 'En producción');

            if (previewEtapa) previewEtapa.textContent = etapaNombre;
            if (etapaIdHidden) etapaIdHidden.value = etapaId;
            if (etapaTextoInput) etapaTextoInput.value = etapaNombre;

            // Fallback fetch de etapa si no viene en dataset
            if (!etapaId && aSelect.value) {
                fetch('{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}'.replace('__ID__', aSelect.value), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(payload => {
                    const etapaNode = payload?.data?.etapa_actual || payload?.data?.etapa || payload?.data;
                    const fetchedId = etapaNode?.id || etapaNode?.etapa_id || '';
                    const fetchedNombre = etapaNode?.nombre 
                        || etapaNode?.etapa_nombre 
                        || (fetchedId && etapasCatalog[fetchedId]) 
                        || '';
                    if (fetchedId) {
                        selectedOpt.dataset.etapaId = fetchedId;
                        if (etapaIdHidden) etapaIdHidden.value = fetchedId;
                    }
                    if (fetchedNombre) {
                        selectedOpt.dataset.etapaNombre = fetchedNombre;
                        if (previewEtapa) previewEtapa.textContent = fetchedNombre;
                        if (etapaTextoInput) etapaTextoInput.value = fetchedNombre;
                    }
                })
                .catch(() => {});
            }
        } else {
            if (previewNombre) previewNombre.textContent = 'Sin seleccionar';
            if (previewCodigo) previewCodigo.textContent = 'Código: #---';
            if (previewUbicacion) previewUbicacion.textContent = 'No especificada';
            if (previewEtapa) previewEtapa.textContent = 'No seleccionada';
            if (etapaIdHidden) etapaIdHidden.value = '';
            if (etapaTextoInput) etapaTextoInput.value = '';
        }

        // Tipo
        const tVal = tipoSelect ? tipoSelect.value : '';
        if (previewTipo) {
            if (tVal === 'IA') {
                previewTipo.textContent = 'Inseminación (IA)';
                previewTipo.className = 'font-bold text-blue-700 text-right';
            } else if (tVal === 'Natural') {
                previewTipo.textContent = 'Monta Natural';
                previewTipo.className = 'font-bold text-emerald-700 text-right';
            } else if (tVal === 'Parto') {
                previewTipo.textContent = 'Parto';
                previewTipo.className = 'font-bold text-purple-700 text-right';
            } else if (tVal === 'Aborto') {
                previewTipo.textContent = 'Aborto';
                previewTipo.className = 'font-bold text-red-700 text-right';
            } else {
                previewTipo.textContent = tVal || 'Evento';
                previewTipo.className = 'font-bold text-gray-700 text-right';
            }
        }

        calculateDates();
    }

    if (fHelper) {
        fHelper.addEventListener('change', () => {
            filtrarRebanos();
            filtrarAnimales();
        });
    }

    if (rHelper) {
        rHelper.addEventListener('change', () => {
            const selVal = rHelper.value;
            if (selVal && fHelper) {
                const opt = rHelper.selectedOptions[0];
                const fid = opt ? opt.dataset.fincaId : '';
                if (fid && fHelper.value !== fid) {
                    fHelper.value = fid;
                    filtrarRebanos();
                    rHelper.value = selVal;
                }
            }
            filtrarAnimales();
        });
    }

    if (aSelect) {
        aSelect.addEventListener('change', function () {
            const selectedOpt = aSelect.options[aSelect.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                if (selectedOpt.dataset.fincaId && fHelper && fHelper.value !== selectedOpt.dataset.fincaId) {
                    fHelper.value = selectedOpt.dataset.fincaId;
                    filtrarRebanos();
                }
                if (selectedOpt.dataset.rebanoId && rHelper && rHelper.value !== selectedOpt.dataset.rebanoId) {
                    rHelper.value = selectedOpt.dataset.rebanoId;
                }
            }
            updateAllPreviews();
        });
    }

    if (tipoSelect) tipoSelect.addEventListener('change', updateAllPreviews);
    if (fechaInput) fechaInput.addEventListener('input', calculateDates);

    // Initial setup
    filtrarRebanos();
    if (aSelect && aSelect.value) {
        const s = Array.from(aSelect.options).find(o => o.value === aSelect.value);
        if (s) {
            if (s.dataset.fincaId && fHelper) {
                fHelper.value = s.dataset.fincaId;
                filtrarRebanos();
            }
            if (s.dataset.rebanoId && rHelper) {
                rHelper.value = s.dataset.rebanoId;
            }
        }
    }
    updateAllPreviews();
});
</script>
@endsection
