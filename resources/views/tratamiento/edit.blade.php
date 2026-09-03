@extends('layouts.authenticated')

@section('title', 'Editar tratamiento médico')

@section('content')
@php
    $id = $tratamiento['id'] ?? $tratamiento['tratamiento_id'] ?? null;
    $diagId = $tratamiento['diagnostico_id'] ?? $tratamiento['tratamiento_diagnostico_id'] ?? null;
    $fechaIni = $tratamiento['fecha_ini'] ?? $tratamiento['tratamiento_fecha_ini'] ?? null;
    $fechaFin = $tratamiento['fecha_fin'] ?? $tratamiento['tratamiento_fecha_fin'] ?? null;
    $plan = $tratamiento['plan'] ?? $tratamiento['tratamiento_plan'] ?? '';
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center font-bold text-2xl shadow-xs">
                💊
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar tratamiento #{{ $id ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Modifica las prescripciones terapéuticas y el cronograma de aplicación</p>
            </div>
        </div>
        <div>
            <a href="{{ route('tratamiento.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Error Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('tratamiento.update', $id) }}" novalidate class="space-y-6" id="tratamientoEditForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card 1: Vinculación con Diagnóstico -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="border-b border-gray-100 pb-3">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>📋</span> Diagnóstico clínico y ejemplar receptor
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Filtra por finca o rebaño para reasignar el diagnóstico clínico correspondiente</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Filtro Finca Helper -->
                        <div>
                            <label for="helper_finca" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Filtrar por finca <span class="text-xs font-normal text-gray-400 normal-case">(opcional)</span>
                            </label>
                            <select id="helper_finca"
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Todas las fincas</option>
                                @foreach($fincas as $finca)
                                    @php
                                        $fId = $finca['id'] ?? $finca['id_Finca'] ?? '';
                                        $fNombre = $finca['nombre'] ?? $finca['Nombre'] ?? ('Finca #'.$fId);
                                    @endphp
                                    @if($fId)
                                        <option value="{{ $fId }}">{{ $fNombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro Rebaño Helper -->
                        <div>
                            <label for="helper_rebano" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Filtrar por rebaño <span class="text-xs font-normal text-gray-400 normal-case">(opcional)</span>
                            </label>
                            <select id="helper_rebano"
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Todos los rebaños</option>
                                @foreach($rebanos as $rebano)
                                    @php
                                        $rId = $rebano['id'] ?? $rebano['id_Rebano'] ?? '';
                                        $rNombre = $rebano['nombre'] ?? $rebano['Nombre'] ?? ('Rebaño #'.$rId);
                                        $rFincaId = $rebano['finca_id'] ?? $rebano['id_Finca'] ?? '';
                                    @endphp
                                    @if($rId)
                                        <option value="{{ $rId }}" data-finca-id="{{ $rFincaId }}">{{ $rNombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Selector Principal de Diagnóstico -->
                        <div class="md:col-span-2">
                            <label for="diagnostico_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Diagnóstico clínico base <span class="text-xs font-normal text-gray-400 normal-case">(opcional)</span>
                            </label>
                            <select name="diagnostico_id" id="diagnostico_id"
                                    class="w-full px-4 py-3 border @error('diagnostico_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white">
                                <option value="">-- Sin diagnóstico específico vinculado --</option>
                                @foreach($diagnosticos as $diag)
                                    @php
                                        $dId = $diag['id'] ?? $diag['diagnostico_id'] ?? '';
                                        $dTipo = $diag['tipo'] ?? $diag['diagnostico_tipo'] ?? 'Diagnóstico';
                                        $dFecha = $diag['fecha'] ?? $diag['diagnostico_fecha'] ?? null;
                                        $dDesc = $diag['descripcion'] ?? $diag['diagnostico_descripcion'] ?? '';
                                        
                                        $anId = $diag['animal_id'] ?? $diag['fk_etapa_animal_anid'] ?? data_get($diag, 'etapa_animal.animal_id') ?? data_get($diag, 'animal.id') ?? data_get($diag, 'animal.id_Animal') ?? data_get($diag, 'etapa_animal.animal.id') ?? '';
                                        $anNombre = data_get($diag, 'etapa_animal.animal.nombre') ?? data_get($diag, 'etapa_animal.animal.Nombre') ?? data_get($diag, 'animal.Nombre') ?? data_get($diag, 'animal.nombre') ?? ($anId ? 'Animal #'.$anId : 'Ejemplar');
                                        $anCodigo = data_get($diag, 'etapa_animal.animal.codigo_animal') ?? data_get($diag, 'animal.codigo_animal') ?? '';
                                        $anSexo = data_get($diag, 'etapa_animal.animal.sexo') ?? data_get($diag, 'etapa_animal.animal.Sexo') ?? data_get($diag, 'animal.sexo') ?? 'H';
                                        
                                        $rId = (string) (data_get($diag, 'etapa_animal.animal.rebano.id') ?? data_get($diag, 'etapa_animal.animal.rebano.id_Rebano') ?? data_get($diag, 'etapa_animal.animal.rebano_id') ?? data_get($diag, 'animal.rebano.id') ?? data_get($diag, 'animal.rebano.id_Rebano') ?? data_get($diag, 'animal.rebano_id') ?? data_get($diag, 'rebano_id') ?? '');
                                        $rNombre = data_get($diag, 'etapa_animal.animal.rebano.nombre') ?? data_get($diag, 'etapa_animal.animal.rebano.Nombre') ?? data_get($diag, 'animal.rebano.Nombre') ?? data_get($diag, 'animal.rebano.nombre') ?? ($rId ? 'Rebaño #'.$rId : '');
                                        
                                        $fId = (string) (data_get($diag, 'etapa_animal.animal.rebano.finca.id') ?? data_get($diag, 'etapa_animal.animal.rebano.finca.id_Finca') ?? data_get($diag, 'etapa_animal.animal.rebano.finca_id') ?? data_get($diag, 'animal.rebano.finca_id') ?? data_get($diag, 'animal.rebano.finca.id') ?? data_get($diag, 'finca_id') ?? '');
                                        $fNombre = data_get($diag, 'etapa_animal.animal.rebano.finca.nombre') ?? data_get($diag, 'etapa_animal.animal.rebano.finca.Nombre') ?? data_get($diag, 'animal.rebano.finca.Nombre') ?? data_get($diag, 'animal.rebano.finca.nombre') ?? ($fId ? 'Finca #'.$fId : '');
                                    @endphp
                                    <option value="{{ $dId }}"
                                            data-animal-id="{{ $anId }}"
                                            data-animal-nombre="{{ $anNombre }}"
                                            data-animal-codigo="{{ $anCodigo }}"
                                            data-animal-sexo="{{ $anSexo }}"
                                            data-diag-tipo="{{ $dTipo }}"
                                            data-diag-fecha="{{ $dFecha ? date('d/m/Y', strtotime($dFecha)) : '' }}"
                                            data-diag-desc="{{ $dDesc }}"
                                            data-rebano-id="{{ $rId }}"
                                            data-rebano-nombre="{{ $rNombre }}"
                                            data-finca-id="{{ $fId }}"
                                            data-finca-nombre="{{ $fNombre }}"
                                            {{ (string)old('diagnostico_id', $diagId) === (string)$dId ? 'selected' : '' }}>
                                        {{ $anNombre }} {{ $anCodigo ? '(#'.$anCodigo.')' : '' }} • {{ $dTipo }} ({{ $dFecha ? date('d/m/Y', strtotime($dFecha)) : 'S/F' }}) #{{ $dId }}
                                    </option>
                                @endforeach
                            </select>
                            @error('diagnostico_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Caja dinámica con información del diagnóstico seleccionado -->
                    <div id="diagnosticoPreviewBox" class="hidden p-4 sm:p-5 bg-gradient-to-br from-purple-50/90 to-purple-50/40 border border-purple-200/90 rounded-2xl space-y-3.5 shadow-xs">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-purple-200/70 pb-2.5">
                            <span class="text-xs sm:text-sm font-bold text-purple-950 uppercase tracking-wider flex items-center gap-1.5">
                                <span>🩺</span> Detalle del diagnóstico seleccionado
                            </span>
                            <span id="previewDiagBadge" class="text-xs font-bold px-3 py-0.5 rounded-full bg-purple-100 text-purple-800 border border-purple-200 shadow-xs self-start sm:self-auto">
                                Tipo
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-0.5">Ejemplar</span>
                                <p id="previewAnimalTexto" class="text-sm sm:text-base font-bold text-gray-900">—</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-0.5">Ubicación</span>
                                <p id="previewUbicacionTexto" class="text-sm sm:text-base font-bold text-gray-900">—</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-0.5">Fecha de evaluación</span>
                                <p id="previewFechaDiagTexto" class="text-sm sm:text-base font-bold text-gray-900">—</p>
                            </div>
                        </div>
                        <div id="previewDescContainer" class="hidden p-3 bg-white/80 rounded-xl border border-purple-100 space-y-0.5">
                            <span class="text-xs font-semibold text-purple-900 uppercase tracking-wider block">Descripción clínica</span>
                            <p id="previewDescTexto" class="text-xs sm:text-sm text-gray-800 leading-relaxed font-medium"></p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Plan Terapéutico y Prescripción -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="border-b border-gray-100 pb-3">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>💊</span> Esquema terapéutico y prescripción
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Define los medicamentos, posología y el cronograma de aplicación</p>
                    </div>

                    <div class="space-y-6">
                        <!-- Plan de tratamiento -->
                        <div>
                            <label for="plan" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Plan de tratamiento / Posología
                            </label>
                            <textarea name="plan" id="plan" rows="3" maxlength="255"
                                      placeholder="Ej: Administrar 10 ml de antibiótico intramuscular cada 24 horas por 5 días. Reposo y observación..."
                                      class="w-full px-4 py-3 border @error('plan') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('plan', $plan) }}</textarea>
                            <p class="text-[11px] text-gray-400 mt-1">Máximo 255 caracteres.</p>
                            @error('plan')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fechas del tratamiento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="fecha_ini" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                    Fecha de inicio <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_ini" id="fecha_ini" required
                                       value="{{ old('fecha_ini', $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format('Y-m-d') : date('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border @error('fecha_ini') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                @error('fecha_ini')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="fecha_fin" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                    Fecha estimada de finalización <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_fin" id="fecha_fin" required
                                       value="{{ old('fecha_fin', $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format('Y-m-d') : '') }}"
                                       class="w-full px-4 py-3 border @error('fecha_fin') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                @error('fecha_fin')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen y Guardado (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6 sticky top-24">
                    <div class="border-b border-gray-100 pb-3">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>📋</span> Resumen de la terapia
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-purple-50/70 rounded-xl border border-purple-100 text-center space-y-1">
                            <span class="text-xs font-semibold text-purple-900 uppercase tracking-wider block">Duración programada</span>
                            <p id="resumenDuracionTexto" class="text-2xl font-extrabold text-purple-800">
                                0 días
                            </p>
                            <p id="resumenFechasTexto" class="text-[11px] text-purple-600">Selecciona las fechas de inicio y fin</p>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-xs space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-500">ID Tratamiento:</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Ejemplar:</span>
                                <span id="resumenAnimalLabel" class="font-bold text-gray-900">No seleccionado</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Diagnóstico:</span>
                                <span id="resumenDiagLabel" class="font-semibold text-purple-700">Sin vincular</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 space-y-3">
                        <button type="submit"
                                class="w-full py-3.5 px-6 bg-ganaderasoft-verde-oscuro text-white font-bold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Actualizar tratamiento
                        </button>
                        <a href="{{ route('tratamiento.index') }}"
                           class="w-full py-3 px-6 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const helperFinca    = document.getElementById('helper_finca');
    const helperRebano   = document.getElementById('helper_rebano');
    const selectDiag     = document.getElementById('diagnostico_id');
    const fechaIniInput  = document.getElementById('fecha_ini');
    const fechaFinInput  = document.getElementById('fecha_fin');

    const previewBox     = document.getElementById('diagnosticoPreviewBox');
    const previewBadge   = document.getElementById('previewDiagBadge');
    const previewAnimal  = document.getElementById('previewAnimalTexto');
    const previewUbic    = document.getElementById('previewUbicacionTexto');
    const previewFecha   = document.getElementById('previewFechaDiagTexto');
    const previewDescCont= document.getElementById('previewDescContainer');
    const previewDesc    = document.getElementById('previewDescTexto');

    const resumenDuracion= document.getElementById('resumenDuracionTexto');
    const resumenFechas  = document.getElementById('resumenFechasTexto');
    const resumenAnimal  = document.getElementById('resumenAnimalLabel');
    const resumenDiag    = document.getElementById('resumenDiagLabel');

    // Lista original de opciones de diagnóstico
    const opcionesDiagOriginales = Array.from(selectDiag.options).map(opt => ({
        value: opt.value,
        text: opt.textContent,
        fincaId: opt.dataset.fincaId || '',
        fincaNombre: opt.dataset.fincaNombre || '',
        rebanoId: opt.dataset.rebanoId || '',
        rebanoNombre: opt.dataset.rebanoNombre || '',
        animalNombre: opt.dataset.animalNombre || '',
        animalCodigo: opt.dataset.animalCodigo || '',
        animalSexo: opt.dataset.animalSexo || '',
        diagTipo: opt.dataset.diagTipo || '',
        diagFecha: opt.dataset.diagFecha || '',
        diagDesc: opt.dataset.diagDesc || ''
    }));

    // Lista original de rebaños
    const listaRebanosOriginales = Array.from(helperRebano.options)
        .filter(opt => !!opt.value)
        .map(opt => ({
            value: opt.value,
            text: opt.textContent,
            fincaId: opt.dataset.fincaId || ''
        }));

    function repopularRebanosPorFinca(fincaId) {
        const valActual = helperRebano.value;
        helperRebano.innerHTML = '<option value="">Todos los rebaños</option>';

        listaRebanosOriginales
            .filter(r => !fincaId || r.fincaId === fincaId)
            .sort((a, b) => a.text.localeCompare(b.text))
            .forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.value;
                opt.textContent = r.text;
                opt.dataset.fincaId = r.fincaId;
                if (r.value === valActual) opt.selected = true;
                helperRebano.appendChild(opt);
            });
    }

    function repopularDiagnosticos() {
        const fVal = helperFinca.value;
        const rVal = helperRebano.value;
        const currentSelected = selectDiag.value;

        selectDiag.innerHTML = '<option value="">-- Sin diagnóstico específico vinculado --</option>';

        opcionesDiagOriginales.forEach(d => {
            if (!d.value) return;
            const matchFinca = !fVal || d.fincaId === fVal;
            const matchRebano = !rVal || d.rebanoId === rVal;

            if (matchFinca && matchRebano) {
                const opt = document.createElement('option');
                opt.value = d.value;
                opt.textContent = d.text;
                opt.dataset.fincaId = d.fincaId;
                opt.dataset.fincaNombre = d.fincaNombre;
                opt.dataset.rebanoId = d.rebanoId;
                opt.dataset.rebanoNombre = d.rebanoNombre;
                opt.dataset.animalNombre = d.animalNombre;
                opt.dataset.animalCodigo = d.animalCodigo;
                opt.dataset.animalSexo = d.animalSexo;
                opt.dataset.diagTipo = d.diagTipo;
                opt.dataset.diagFecha = d.diagFecha;
                opt.dataset.diagDesc = d.diagDesc;
                if (d.value === currentSelected) opt.selected = true;
                selectDiag.appendChild(opt);
            }
        });

        actualizarPreviewDiagnostico();
    }

    function actualizarPreviewDiagnostico() {
        const selectedOpt = selectDiag.options[selectDiag.selectedIndex];
        if (!selectedOpt || !selectedOpt.value) {
            previewBox.classList.add('hidden');
            resumenAnimal.textContent = 'No seleccionado';
            resumenDiag.textContent = 'Sin vincular';
            return;
        }

        const d = selectedOpt.dataset;
        previewBox.classList.remove('hidden');
        previewBadge.textContent = d.diagTipo || 'Diagnóstico';
        previewAnimal.textContent = `${d.animalNombre || 'Ejemplar'} ${d.animalCodigo ? '(#' + d.animalCodigo + ')' : ''}`;
        previewUbic.textContent = `${d.fincaNombre || 'Finca'} • ${d.rebanoNombre || 'Rebaño'}`;
        previewFecha.textContent = d.diagFecha || 'No especificada';

        if (d.diagDesc && d.diagDesc.trim()) {
            previewDescCont.classList.remove('hidden');
            previewDesc.textContent = d.diagDesc;
        } else {
            previewDescCont.classList.add('hidden');
        }

        resumenAnimal.textContent = d.animalNombre || 'Ejemplar';
        resumenDiag.textContent = d.diagTipo || 'Vinculado';
    }

    function actualizarDuracion() {
        const ini = fechaIniInput.value;
        const fin = fechaFinInput.value;

        if (!ini || !fin) {
            resumenDuracion.textContent = '0 días';
            resumenFechas.textContent = 'Selecciona las fechas de inicio y fin';
            return;
        }

        const dateIni = new Date(ini + 'T00:00:00');
        const dateFin = new Date(fin + 'T00:00:00');

        const diffTime = dateFin - dateIni;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

        if (diffDays > 0) {
            resumenDuracion.textContent = `${diffDays} ${diffDays === 1 ? 'día' : 'días'}`;
            resumenFechas.textContent = `${ini.split('-').reverse().join('/')} al ${fin.split('-').reverse().join('/')}`;
            resumenDuracion.classList.remove('text-red-600');
            resumenDuracion.classList.add('text-purple-800');
        } else {
            resumenDuracion.textContent = 'Fecha inválida';
            resumenFechas.textContent = 'La fecha fin debe ser igual o posterior a inicio';
            resumenDuracion.classList.add('text-red-600');
            resumenDuracion.classList.remove('text-purple-800');
        }
    }

    // Inicializar helpers con el diagnóstico preseleccionado
    const selectedInitially = opcionesDiagOriginales.find(d => d.value === '{{ $diagId }}');
    if (selectedInitially) {
        if (selectedInitially.fincaId) {
            helperFinca.value = selectedInitially.fincaId;
            repopularRebanosPorFinca(selectedInitially.fincaId);
        }
        if (selectedInitially.rebanoId) {
            helperRebano.value = selectedInitially.rebanoId;
        }
    }

    // Eventos
    helperFinca.addEventListener('change', function () {
        repopularRebanosPorFinca(this.value);
        repopularDiagnosticos();
    });

    helperRebano.addEventListener('change', function () {
        const rebVal = this.value;
        if (rebVal) {
            const rebInfo = listaRebanosOriginales.find(r => r.value === rebVal);
            if (rebInfo && rebInfo.fincaId && helperFinca.value !== rebInfo.fincaId) {
                helperFinca.value = rebInfo.fincaId;
                repopularRebanosPorFinca(rebInfo.fincaId);
                helperRebano.value = rebVal;
            }
        }
        repopularDiagnosticos();
    });

    selectDiag.addEventListener('change', function () {
        const selectedOpt = selectDiag.options[selectDiag.selectedIndex];
        if (selectedOpt && selectedOpt.value) {
            const fId = selectedOpt.dataset.fincaId;
            const rId = selectedOpt.dataset.rebanoId;
            if (fId && helperFinca.value !== fId) {
                helperFinca.value = fId;
                repopularRebanosPorFinca(fId);
            }
            if (rId) {
                helperRebano.value = rId;
            }
        }
        actualizarPreviewDiagnostico();
    });

    fechaIniInput.addEventListener('input', actualizarDuracion);
    fechaFinInput.addEventListener('input', actualizarDuracion);

    // Inicialización
    actualizarPreviewDiagnostico();
    actualizarDuracion();
});
</script>
@endpush
@endsection
