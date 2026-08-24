@extends('layouts.authenticated')

@section('title', 'Registrar palpación animal')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🩺
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Registrar palpación animal
                </h1>
                <p class="text-gray-500 text-sm mt-1">Diagnóstico de preñez, tacto rectal y evaluaciones ginecológicas</p>
            </div>
        </div>
        <div>
            <a href="{{ route('palpacion.index') }}" 
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
    <form method="POST" action="{{ route('palpacion.store') }}" id="formPalpacionAnimal" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card 1: Selección de la Hembra Evaluada -->
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
                        <label for="palp_animal_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Hembra a evaluar <span class="text-red-500">*</span>
                        </label>
                        <select name="animal_id" id="palp_animal_id" required
                                class="w-full px-4 py-3 border @error('animal_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                            <option value="">-- Seleccionar hembra para palpación --</option>
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

                <!-- Card 2: Parámetros del Diagnóstico Ginecológico -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🩺</span> Parámetros del diagnóstico clínico
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Etapa Actual (Auto-asignada) -->
                        <div>
                            <label for="palp_etapa_texto" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Etapa productiva <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="palp_etapa_texto" readonly
                                   placeholder="Se completará al seleccionar la hembra"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-700 font-medium focus:outline-none">
                            <input type="hidden" name="etapa_id" id="palp_etapa_etid" value="{{ old('etapa_id') }}">
                            @error('etapa_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Tipo de Palpación / Diagnóstico -->
                        <div>
                            <label for="tipo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Resultado / Diagnóstico <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo" id="tipo" required
                                    class="w-full px-4 py-3 border @error('tipo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="Preñez" {{ old('tipo', 'Preñez') == 'Preñez' ? 'selected' : '' }}>🤰 Preñada / Gestante</option>
                                <option value="Vacía" {{ old('tipo') == 'Vacía' ? 'selected' : '' }}>⭕ Vacía / Abierta</option>
                                <option value="Revision" {{ old('tipo') == 'Revision' ? 'selected' : '' }}>🩺 Revisión ginecológica</option>
                                <option value="Ecografía" {{ old('tipo') == 'Ecografía' ? 'selected' : '' }}>🔬 Ecografía</option>
                            </select>
                            @error('tipo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Técnico / Veterinario Responsable -->
                        <div>
                            <label for="tecnico_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Veterinario / Técnico evaluador <span class="text-xs font-normal text-gray-400 normal-case">(opcional)</span>
                            </label>
                            <select name="tecnico_id" id="tecnico_id"
                                    class="w-full px-4 py-3 border @error('tecnico_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="">-- Sin técnico asignado --</option>
                                @foreach($personal as $persona)
                                    @php
                                        $pId = data_get($persona, 'id') ?? data_get($persona, 'id_Tecnico') ?? data_get($persona, 'id_Personal') ?? data_get($persona, 'personal.id');
                                        $pNom = trim((data_get($persona, 'persona.nombre') ?? data_get($persona, 'Nombre') ?? data_get($persona, 'nombre') ?? data_get($persona, 'personal.Nombre') ?? '') . ' ' . (data_get($persona, 'persona.apellido') ?? data_get($persona, 'Apellido') ?? data_get($persona, 'apellido') ?? data_get($persona, 'personal.Apellido') ?? ''));
                                        $pCargo = data_get($persona, 'tipo_trabajador.nombre') ?? data_get($persona, 'tipoTrabajador.nombre') ?? data_get($persona, 'Tipo_Trabajador') ?? 'Veterinario';
                                        $pFincaId = (string)(data_get($persona, 'finca_id') ?? data_get($persona, 'id_Finca') ?? '');
                                    @endphp
                                    @continue(!$pId)
                                    <option value="{{ $pId }}" data-nombre="{{ $pNom }}" data-finca-id="{{ $pFincaId }}" {{ old('tecnico_id') == $pId ? 'selected' : '' }}>
                                        👨‍⚕️ {{ $pNom ?: ('Personal #'.$pId) }} ({{ $pCargo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tecnico_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha de la Palpación -->
                        <div>
                            <label for="fecha" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de la evaluación <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha" id="fecha" required 
                                   value="{{ old('fecha', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border @error('fecha') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('fecha')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
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
                            <span>📋</span> Resumen del diagnóstico
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
                                <span class="text-gray-500">Diagnóstico:</span>
                                <span id="previewTipo" class="font-bold text-emerald-700 text-right">Preñada / Gestante</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Evaluador:</span>
                                <span id="previewTecnico" class="font-bold text-gray-900 text-right truncate">Sin asignar</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Fecha evaluación:</span>
                                <span id="previewFecha" class="font-bold text-gray-900 text-right">Hoy</span>
                            </div>
                            <div class="flex justify-between items-center gap-2" id="boxPartoEstimado">
                                <span class="text-gray-500">Parto est. (+283d):</span>
                                <span id="previewParto" class="font-bold text-emerald-700 font-mono text-right">—</span>
                            </div>
                            <div class="flex justify-between items-center gap-2" id="boxSecadoEstimado">
                                <span class="text-gray-500">Secado est. (+220d):</span>
                                <span id="previewSecado" class="font-bold text-purple-700 font-mono text-right">—</span>
                            </div>
                        </div>

                        <!-- Mensaje Proyección -->
                        <div class="p-3.5 bg-emerald-50/70 border border-emerald-100 rounded-xl space-y-1 text-xs text-emerald-900 leading-relaxed" id="infoGestacionBox">
                            <strong class="block font-bold">Diagnóstico favorable de preñez:</strong>
                            <p>Se proyecta el período de gestación estándar de <strong>283 días</strong>. Programar secado a los <strong>7 meses de gestación</strong>.</p>
                        </div>

                        <!-- Action Buttons en el Sidebar -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar registro de palpación
                            </button>
                            <a href="{{ route('palpacion.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Guía Diagnóstica y Protocolos Reproductivos -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>💡</span> Protocolos de diagnóstico reproductivo
                    </h4>
                    
                    <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 space-y-1 leading-relaxed">
                        <strong class="block font-bold">Ventanas de diagnóstico:</strong>
                        <p>• <strong>Ecografía temprana:</strong> Detectable a partir del <strong>día 28 - 35</strong> post-servicio.</p>
                        <p>• <strong>Palpación rectal:</strong> Certera a partir del <strong>día 45 - 60</strong> post-servicio.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const aSelect = document.getElementById('palp_animal_id');
    const fHelper = document.getElementById('helper_finca');
    const rHelper = document.getElementById('helper_rebano');
    const tipoSelect = document.getElementById('tipo');
    const tecnicoSelect = document.getElementById('tecnico_id');
    const fechaInput = document.getElementById('fecha');
    const etapaIdHidden = document.getElementById('palp_etapa_etid');
    const etapaTextoInput = document.getElementById('palp_etapa_texto');

    // Sidebar previews
    const previewNombre = document.getElementById('previewNombre');
    const previewCodigo = document.getElementById('previewCodigo');
    const previewUbicacion = document.getElementById('previewUbicacion');
    const previewEtapa = document.getElementById('previewEtapa');
    const previewTipo = document.getElementById('previewTipo');
    const previewTecnico = document.getElementById('previewTecnico');
    const previewFecha = document.getElementById('previewFecha');
    const previewParto = document.getElementById('previewParto');
    const previewSecado = document.getElementById('previewSecado');
    const boxParto = document.getElementById('boxPartoEstimado');
    const boxSecado = document.getElementById('boxSecadoEstimado');
    const infoBox = document.getElementById('infoGestacionBox');

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
        const tVal = (tipoSelect ? tipoSelect.value : '').toLowerCase();
        const isPrenada = tVal.includes('preñ') || tVal.includes('gestan') || tVal.includes('positiv');

        if (!val) {
            if (previewFecha) previewFecha.textContent = 'Hoy';
            if (previewParto) previewParto.textContent = '—';
            if (previewSecado) previewSecado.textContent = '—';
            return;
        }

        const parts = val.split('-');
        if (parts.length === 3) {
            const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            if (previewFecha) previewFecha.textContent = ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();

            if (isPrenada) {
                // Parto promedio bovino (+283 días)
                const partoDate = new Date(d);
                partoDate.setDate(partoDate.getDate() + 283);
                if (previewParto) previewParto.textContent = ('0' + partoDate.getDate()).slice(-2) + '/' + ('0' + (partoDate.getMonth() + 1)).slice(-2) + '/' + partoDate.getFullYear();

                // Secado estimado (+220 días)
                const secadoDate = new Date(d);
                secadoDate.setDate(secadoDate.getDate() + 220);
                if (previewSecado) previewSecado.textContent = ('0' + secadoDate.getDate()).slice(-2) + '/' + ('0' + (secadoDate.getMonth() + 1)).slice(-2) + '/' + secadoDate.getFullYear();

                if (boxParto) boxParto.style.display = 'flex';
                if (boxSecado) boxSecado.style.display = 'flex';
                if (infoBox) infoBox.style.display = 'block';
            } else {
                if (previewParto) previewParto.textContent = 'N/A (No gestante)';
                if (previewSecado) previewSecado.textContent = 'N/A';
                if (boxParto) boxParto.style.display = 'none';
                if (boxSecado) boxSecado.style.display = 'none';
                if (infoBox) infoBox.style.display = 'none';
            }
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

        // Tipo / Diagnóstico
        const tVal = tipoSelect ? tipoSelect.value : '';
        if (previewTipo) {
            if (tVal.toLowerCase().includes('preñ') || tVal.toLowerCase().includes('gestan')) {
                previewTipo.textContent = 'Preñada / Gestante';
                previewTipo.className = 'font-bold text-emerald-700 text-right';
            } else if (tVal.toLowerCase().includes('vac')) {
                previewTipo.textContent = 'Vacía / Abierta';
                previewTipo.className = 'font-bold text-amber-600 text-right';
            } else if (tVal.toLowerCase().includes('eco')) {
                previewTipo.textContent = 'Ecografía';
                previewTipo.className = 'font-bold text-purple-700 text-right';
            } else {
                previewTipo.textContent = tVal || 'Revisión';
                previewTipo.className = 'font-bold text-blue-700 text-right';
            }
        }

        // Técnico
        const tecOpt = tecnicoSelect ? tecnicoSelect.options[tecnicoSelect.selectedIndex] : null;
        if (previewTecnico) {
            previewTecnico.textContent = (tecOpt && tecOpt.value) ? (tecOpt.dataset.nombre || tecOpt.textContent.replace(/👨‍⚕️|\(.*\)/g, '').trim()) : 'Sin asignar';
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
    if (tecnicoSelect) tecnicoSelect.addEventListener('change', updateAllPreviews);
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
