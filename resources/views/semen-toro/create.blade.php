@extends('layouts.authenticated')

@section('title', 'Registrar semen de toro')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🧬
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Registrar semen de toro
                </h1>
                <p class="text-gray-500 text-sm mt-1">Ingreso de pajuelas y muestras de toros donantes al banco genético</p>
            </div>
        </div>
        <div>
            <a href="{{ route('semen-toro.index') }}" 
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
    <form method="POST" action="{{ route('semen-toro.store') }}" id="formSemenToro" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card 1: Selección del Toro Donante -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐂</span> Selección del toro donante
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

                    <!-- Selector Principal del Toro Donante -->
                    <div>
                        <label for="semen_animal_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                            Toro donante <span class="text-red-500">*</span>
                        </label>
                        <select name="animal_id" id="semen_animal_id" required
                                class="w-full px-4 py-3 border @error('animal_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                            <option value="">-- Seleccionar toro donante --</option>
                            @foreach($toros as $toro)
                                @php
                                    $aId = $toro['id'] ?? $toro['id_Animal'] ?? '';
                                    $aNombre = $toro['Nombre'] ?? $toro['nombre'] ?? ('Toro #'.$aId);
                                    $aCodigo = $toro['codigo_animal'] ?? $toro['Codigo'] ?? '';
                                    $aRaza = data_get($toro, 'composicion_raza.nombre') ?? data_get($toro, 'composicionRaza.nombre') ?? data_get($toro, 'raza.Nombre') ?? data_get($toro, 'raza.nombre') ?? '';

                                    $rId = (string) (data_get($toro, 'rebano.id') ?? data_get($toro, 'rebano.id_Rebano') ?? data_get($toro, 'rebano_id') ?? ($toro['id_Rebano'] ?? ''));
                                    $rNombre = data_get($toro, 'rebano.nombre') ?? data_get($toro, 'rebano.Nombre') ?? '';
                                    $fId = (string) (data_get($toro, 'rebano.finca.id') ?? data_get($toro, 'rebano.finca.id_Finca') ?? data_get($toro, 'rebano.finca_id') ?? data_get($toro, 'finca_id') ?? ($toro['rebano']['id_Finca'] ?? ''));
                                    $fNombre = data_get($toro, 'rebano.finca.nombre') ?? data_get($toro, 'rebano.finca.Nombre') ?? ($fId ? 'Finca #'.$fId : '');
                                @endphp
                                <option value="{{ $aId }}"
                                        data-nombre="{{ $aNombre }}"
                                        data-codigo="{{ $aCodigo }}"
                                        data-raza="{{ $aRaza }}"
                                        data-rebano-id="{{ $rId }}"
                                        data-rebano-nombre="{{ $rNombre }}"
                                        data-finca-id="{{ $fId }}"
                                        data-finca-nombre="{{ $fNombre }}"
                                        {{ (string)old('animal_id', $presetAnimalId ?? request('animal_id')) === (string)$aId ? 'selected' : '' }}>
                                    {{ $aNombre }} {{ $aCodigo ? '(#'.$aCodigo.')' : '' }} {{ $aRaza ? '• '.$aRaza : '' }} {{ $rNombre ? '• '.$rNombre : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('animal_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Card 2: Parámetros del Lote de Semen -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🧬</span> Parámetros del lote de pajuelas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Estado / Disponibilidad -->
                        <div>
                            <label for="estado" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Disponibilidad en banco <span class="text-red-500">*</span>
                            </label>
                            <select name="estado" id="estado"
                                    class="w-full px-4 py-3 border @error('estado') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="1" {{ old('estado', '1') === '1' ? 'selected' : '' }}>🟢 Disponible / Activo en banco</option>
                                <option value="0" {{ old('estado') === '0' ? 'selected' : '' }}>⚪ Agotado / Inactivo</option>
                            </select>
                            @error('estado')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha de Colecta / Ingreso -->
                        <div>
                            <label for="fecha" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de colecta / ingreso al banco
                            </label>
                            <input type="date" name="fecha" id="fecha"
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
                            <span>📋</span> Resumen del lote de semen
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-2xl shadow-xs shrink-0">
                                🐂
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
                                <span class="text-gray-500">Raza:</span>
                                <span id="previewRaza" class="font-bold text-blue-700 text-right">No especificada</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Disponibilidad:</span>
                                <span id="previewEstado" class="font-bold text-emerald-700 text-right">Disponible / Activo</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Fecha de colecta:</span>
                                <span id="previewFecha" class="font-bold text-gray-900 text-right">Hoy</span>
                            </div>
                        </div>

                        <!-- Action Buttons en el Sidebar -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar registro de semen
                            </button>
                            <a href="{{ route('semen-toro.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Protocolos y Conservación de Semen -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span>💡</span> Manejo y conservación criogénica
                    </h4>
                    
                    <div class="p-3.5 bg-cyan-50 rounded-xl border border-cyan-200 text-xs text-cyan-900 space-y-1.5 leading-relaxed">
                        <strong class="block font-bold">Conservación de pajuelas:</strong>
                        <p>• Conservar en termo criogénico con nitrógeno líquido a <strong>-196°C</strong>.</p>
                        <p>• Descongelar en baño maría a <strong>35°C - 37°C durante 40 segundos</strong> antes de la inseminación.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const aSelect = document.getElementById('semen_animal_id');
    const fHelper = document.getElementById('helper_finca');
    const rHelper = document.getElementById('helper_rebano');
    const estadoSelect = document.getElementById('estado');
    const fechaInput = document.getElementById('fecha');

    // Sidebar previews
    const previewNombre = document.getElementById('previewNombre');
    const previewCodigo = document.getElementById('previewCodigo');
    const previewUbicacion = document.getElementById('previewUbicacion');
    const previewRaza = document.getElementById('previewRaza');
    const previewEstado = document.getElementById('previewEstado');
    const previewFecha = document.getElementById('previewFecha');

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

    function filtrarToros() {
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
            return;
        }

        const parts = val.split('-');
        if (parts.length === 3) {
            const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            if (previewFecha) previewFecha.textContent = ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
        }
    }

    function updateAllPreviews() {
        const selectedOpt = aSelect ? aSelect.options[aSelect.selectedIndex] : null;
        if (selectedOpt && selectedOpt.value) {
            if (previewNombre) previewNombre.textContent = selectedOpt.dataset.nombre || 'Toro donante';
            if (previewCodigo) previewCodigo.textContent = selectedOpt.dataset.codigo ? ('Código: #' + selectedOpt.dataset.codigo) : ('ID: #' + selectedOpt.value);

            const ubicParts = [selectedOpt.dataset.fincaNombre, selectedOpt.dataset.rebanoNombre].filter(Boolean);
            if (previewUbicacion) previewUbicacion.textContent = ubicParts.length ? ubicParts.join(' • ') : 'Asignado';

            if (previewRaza) previewRaza.textContent = selectedOpt.dataset.raza || 'No especificada';
        } else {
            if (previewNombre) previewNombre.textContent = 'Sin seleccionar';
            if (previewCodigo) previewCodigo.textContent = 'Código: #---';
            if (previewUbicacion) previewUbicacion.textContent = 'No especificada';
            if (previewRaza) previewRaza.textContent = 'No especificada';
        }

        // Estado
        const eVal = estadoSelect ? estadoSelect.value : '1';
        if (previewEstado) {
            if (eVal === '1') {
                previewEstado.textContent = 'Disponible / Activo';
                previewEstado.className = 'font-bold text-emerald-700 text-right';
            } else {
                previewEstado.textContent = 'Agotado / Inactivo';
                previewEstado.className = 'font-bold text-gray-500 text-right';
            }
        }

        calculateDates();
    }

    if (fHelper) {
        fHelper.addEventListener('change', () => {
            filtrarRebanos();
            filtrarToros();
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
            filtrarToros();
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

    if (estadoSelect) estadoSelect.addEventListener('change', updateAllPreviews);
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
