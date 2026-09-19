@extends('layouts.authenticated')

@section('title', 'Nuevo diagnóstico')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold shadow-xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Registrar nuevo diagnóstico
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registra la evaluación clínica, diagnóstico y etapa productiva del animal</p>
            </div>
        </div>
        <div>
            <a href="{{ route('diagnostico.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Errors -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm space-y-2">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-sm font-bold">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 pl-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('diagnostico.store') }}" novalidate class="space-y-6" id="formCreateDiagnostico">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Selección de Animal y Etapa -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="border-b border-gray-100 pb-3 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-ganaderasoft-negro">
                                Selección de animal y etapa clínica
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Filtra por finca o rebaño para localizar al animal y cargar su etapa productiva</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Filtro Finca (Helper) -->
                        <div>
                            <label for="helper_finca" class="block text-sm font-semibold text-gray-700 mb-2">
                                Filtrar por finca <span class="text-xs font-normal text-gray-400 normal-case">(opcional)</span>
                            </label>
                            <select id="helper_finca"
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Todas las fincas</option>
                            </select>
                        </div>

                        <!-- Filtro Rebaño (Helper) -->
                        <div>
                            <label for="helper_rebano" class="block text-sm font-semibold text-gray-700 mb-2">
                                Filtrar por rebaño <span class="text-xs font-normal text-gray-400 normal-case">(opcional)</span>
                            </label>
                            <select id="helper_rebano"
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Todos los rebaños</option>
                            </select>
                        </div>

                        <!-- Selector de Animal Principal -->
                        <div>
                            <label for="animal_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Animal evaluado <span class="text-red-500">*</span>
                            </label>
                            <select name="animal_id" id="animal_id" required
                                    class="w-full px-4 py-3 border @error('animal_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Seleccione un ejemplar</option>
                                @foreach($animales as $animal)
                                    @php
                                        $aId = $animal['id'] ?? $animal['id_Animal'] ?? '';
                                        $aNombre = $animal['Nombre'] ?? $animal['nombre'] ?? ('Animal #'.$aId);
                                        $aCodigo = $animal['codigo_animal'] ?? $animal['Codigo'] ?? '';
                                        $rebanoId = $animal['rebano']['id_Rebano'] ?? ($animal['rebano']['id'] ?? ($animal['id_Rebano'] ?? ''));
                                        $rebanoNombre = $animal['rebano']['Nombre'] ?? ($animal['rebano']['nombre'] ?? ($rebanoId ? 'Rebaño #'.$rebanoId : ''));
                                        $fincaId = $animal['rebano']['finca']['id_Finca'] ?? ($animal['rebano']['finca']['id'] ?? ($animal['rebano']['id_Finca'] ?? ($animal['finca_id'] ?? '')));
                                        $fincaNombre = $animal['rebano']['finca']['Nombre'] ?? ($animal['rebano']['finca']['nombre'] ?? ($fincaId ? 'Finca #'.$fincaId : ''));
                                        $sexoVal = $animal['sexo'] ?? $animal['Sexo'] ?? 'H';

                                        $etapaId = (string)(data_get($animal, 'etapa_actual.etapa.id') ?? data_get($animal, 'etapa_actual.etapa.id_Etapa') ?? data_get($animal, 'etapa_actual.etapa_id') ?? data_get($animal, 'etapa_actual.id') ?? data_get($animal, 'etapa.id') ?? data_get($animal, 'etapa_id') ?? '');
                                        $etapaNombre = data_get($animal, 'etapa_actual.etapa.nombre') 
                                            ?? data_get($animal, 'etapa_actual.etapa.Nombre') 
                                            ?? data_get($animal, 'etapa_actual.nombre') 
                                            ?? data_get($animal, 'etapa_actual.etapa_nombre') 
                                            ?? data_get($animal, 'etapa.nombre') 
                                            ?? ($etapaId ? 'Etapa #'.$etapaId : '');
                                    @endphp
                                    <option value="{{ $aId }}"
                                            data-nombre="{{ $aNombre }}"
                                            data-codigo="{{ $aCodigo }}"
                                            data-sexo="{{ $sexoVal }}"
                                            data-rebano-id="{{ $rebanoId }}"
                                            data-rebano-nombre="{{ $rebanoNombre }}"
                                            data-finca-id="{{ $fincaId }}"
                                            data-finca-nombre="{{ $fincaNombre }}"
                                            data-etapa-id="{{ $etapaId }}"
                                            data-etapa-nombre="{{ $etapaNombre }}"
                                            {{ (string)old('animal_id', request('animal_id')) === (string)$aId ? 'selected' : '' }}>
                                        {{ $aNombre }} {{ $aCodigo ? '(#'.$aCodigo.')' : '(#' . $aId . ')' }} {{ $rebanoNombre ? '• ' . $rebanoNombre : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('animal_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Etapa Actual del Animal -->
                        <div>
                            <label for="diagnostico_etapa_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Etapa productiva / clínica <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="etapa_id" id="diagnostico_etapa_id" required
                                        class="w-full px-4 py-3 border @error('etapa_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                    <option value="">Seleccione o autodetectar al elegir animal...</option>
                                    @foreach($etapas as $etapa)
                                        @php
                                            $eId = $etapa['id'] ?? $etapa['id_Etapa'] ?? '';
                                            $eNom = $etapa['nombre'] ?? $etapa['Nombre'] ?? ('Etapa #'.$eId);
                                        @endphp
                                        <option value="{{ $eId }}" {{ (string)old('etapa_id') === (string)$eId ? 'selected' : '' }}>
                                            {{ $eNom }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="etapaLoading" class="hidden absolute right-8 top-3.5 text-ganaderasoft-celeste animate-spin pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('etapa_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-400 mt-1">Se detecta automáticamente al elegir el ejemplar o puede seleccionarse manualmente.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Información del Diagnóstico -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="border-b border-gray-100 pb-3 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ganaderasoft-negro">
                            Información médica del diagnóstico
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tipo" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tipo de diagnóstico <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="tipo" name="tipo" value="{{ old('tipo') }}" maxlength="30" required
                                   placeholder="Ej: Mastitis, Cojera, Neumonía..."
                                   class="w-full px-4 py-3 border @error('tipo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('tipo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-2">
                                Fecha de evaluación <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                                   class="w-full px-4 py-3 border @error('fecha') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('fecha')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">
                                Descripción y observaciones clínicas
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="4"
                                      placeholder="Describe los síntomas identificados, evolución clínica, severidad o indicaciones médicas..."
                                      class="w-full px-4 py-3 border @error('descripcion') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('descripcion') }}</textarea>
                            @error('descripcion')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="bg-gray-50/80 border-b border-gray-100 px-6 py-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-900">Resumen del diagnóstico</h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Animal Avatar e Identificación -->
                        <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-2xl flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-white border border-blue-200 text-blue-700 font-bold flex items-center justify-center shadow-xs shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="overflow-hidden">
                                <p id="previewAnimalNombre" class="text-base font-bold text-gray-900 truncate">Ningún animal</p>
                                <p id="previewAnimalId" class="text-xs text-gray-500 font-mono">ID: #---</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-sm text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center">
                                <span>Finca:</span>
                                <span id="previewFinca" class="font-bold text-gray-900 truncate max-w-[150px]">No especificada</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Rebaño:</span>
                                <span id="previewRebano" class="font-bold text-gray-900 truncate max-w-[150px]">No especificado</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Etapa:</span>
                                <span id="previewEtapa" class="font-bold text-gray-900 truncate max-w-[150px]">Pendiente</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Tipo:</span>
                                <span id="previewTipo" class="font-bold text-ganaderasoft-verde-oscuro truncate max-w-[150px]">No especificado</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Fecha:</span>
                                <span id="previewFecha" class="font-bold text-gray-900">{{ date('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Observaciones:</span>
                                <span id="previewDescripcionEstado" class="font-medium text-gray-400">Sin observaciones</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Guardar diagnóstico
                            </button>
                            <a href="{{ route('diagnostico.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const helperFinca = document.getElementById('helper_finca');
    const helperRebano = document.getElementById('helper_rebano');
    const animalSelect = document.getElementById('animal_id');
    const etapaSelect = document.getElementById('diagnostico_etapa_id');
    const etapaLoading = document.getElementById('etapaLoading');
    const tipoInput = document.getElementById('tipo');
    const fechaInput = document.getElementById('fecha');
    const descripcionInput = document.getElementById('descripcion');

    const previewAnimalNombre = document.getElementById('previewAnimalNombre');
    const previewAnimalId = document.getElementById('previewAnimalId');
    const previewFinca = document.getElementById('previewFinca');
    const previewRebano = document.getElementById('previewRebano');
    const previewEtapa = document.getElementById('previewEtapa');
    const previewTipo = document.getElementById('previewTipo');
    const previewFecha = document.getElementById('previewFecha');
    const previewDescripcionEstado = document.getElementById('previewDescripcionEstado');

    const endpointTemplate = '{{ route('lactancia.animal.etapa', ['id' => '__ID__']) }}';

    // Poblar los filtros rápidos de Finca y Rebaño desde las opciones de animales
    const animalOptions = Array.from(animalSelect.options).filter(o => o.value !== '');
    const fincasMap = {};
    const rebanosMap = {};

    animalOptions.forEach(opt => {
        const fId = opt.dataset.fincaId;
        const fNom = opt.dataset.fincaNombre;
        const rId = opt.dataset.rebanoId;
        const rNom = opt.dataset.rebanoNombre;

        if (fId && !fincasMap[fId]) {
            fincasMap[fId] = fNom || ('Finca #' + fId);
        }
        if (rId && !rebanosMap[rId]) {
            rebanosMap[rId] = {
                nombre: rNom || ('Rebaño #' + rId),
                fincaId: fId || ''
            };
        }
    });

    Object.keys(fincasMap).sort((a, b) => fincasMap[a].localeCompare(fincasMap[b])).forEach(fId => {
        const opt = document.createElement('option');
        opt.value = fId;
        opt.textContent = fincasMap[fId];
        helperFinca.appendChild(opt);
    });

    Object.keys(rebanosMap).sort((a, b) => rebanosMap[a].nombre.localeCompare(rebanosMap[b].nombre)).forEach(rId => {
        const opt = document.createElement('option');
        opt.value = rId;
        opt.textContent = rebanosMap[rId].nombre;
        opt.dataset.fincaId = rebanosMap[rId].fincaId;
        helperRebano.appendChild(opt);
    });

    // Relación Finca <-> Rebaño en el Helper
    helperFinca.addEventListener('change', function () {
        const fVal = this.value;
        Array.from(helperRebano.options).forEach(opt => {
            if (!opt.value) return;
            const match = !fVal || opt.dataset.fincaId === fVal;
            opt.hidden = !match;
            opt.disabled = !match;
        });

        if (helperRebano.value) {
            const curOpt = helperRebano.querySelector(`option[value="${helperRebano.value}"]`);
            if (curOpt && curOpt.hidden) helperRebano.value = '';
        }

        filtrarAnimales();
    });

    helperRebano.addEventListener('change', function () {
        const rVal = this.value;
        if (rVal && rebanosMap[rVal]?.fincaId) {
            const fId = rebanosMap[rVal].fincaId;
            if (helperFinca.value !== fId) {
                helperFinca.value = fId;
                Array.from(helperRebano.options).forEach(opt => {
                    if (!opt.value) return;
                    const match = opt.dataset.fincaId === fId;
                    opt.hidden = !match;
                    opt.disabled = !match;
                });
            }
        }
        filtrarAnimales();
    });

    function filtrarAnimales() {
        const fVal = helperFinca.value;
        const rVal = helperRebano.value;

        animalOptions.forEach(opt => {
            const matchFinca = !fVal || opt.dataset.fincaId === fVal;
            const matchRebano = !rVal || opt.dataset.rebanoId === rVal;
            const match = matchFinca && matchRebano;
            opt.hidden = !match;
            opt.disabled = !match;
        });

        if (animalSelect.value) {
            const curOpt = animalSelect.querySelector(`option[value="${animalSelect.value}"]`);
            if (curOpt && curOpt.hidden) {
                animalSelect.value = '';
                updateStage();
            }
        }
    }

    async function updateStage() {
        if (!animalSelect.value) {
            etapaSelect.value = '';
            updatePreview();
            return;
        }

        const selectedOpt = animalSelect.options[animalSelect.selectedIndex];
        const datasetEtapaId = selectedOpt ? selectedOpt.dataset.etapaId : '';

        // 1. Si la etapa viene en el dataset del animal, asignarla directamente
        if (datasetEtapaId && etapaSelect.querySelector(`option[value="${datasetEtapaId}"]`)) {
            etapaSelect.value = datasetEtapaId;
            updatePreview();
            return;
        }

        // 2. Fallback por AJAX si no viene en el dataset
        if (etapaLoading) etapaLoading.classList.remove('hidden');

        try {
            const response = await fetch(endpointTemplate.replace('__ID__', animalSelect.value), {
                headers: { Accept: 'application/json' }
            });
            const payload = await response.json();
            const animal = payload?.data?.animal || payload?.data || {};
            const etapaActual = payload?.data?.etapa_actual || payload?.data?.etapaActual || animal?.etapa_actual || animal?.etapaActual || null;
            const etapa = etapaActual?.etapa || etapaActual;
            const etapaId = etapa?.id || etapa?.etapa_id || etapaActual?.etan_etapa_id || etapaActual?.etanEtapaId || '';

            if (etapaId && etapaSelect.querySelector(`option[value="${etapaId}"]`)) {
                etapaSelect.value = etapaId;
                if (selectedOpt) {
                    selectedOpt.dataset.etapaId = etapaId;
                }
            }
        } catch (error) {
            console.warn('No se pudo autodetectar la etapa vía AJAX:', error);
        } finally {
            if (etapaLoading) etapaLoading.classList.add('hidden');
            updatePreview();
        }
    }

    function updatePreview() {
        const selectedOpt = animalSelect.options[animalSelect.selectedIndex];
        if (animalSelect.value && selectedOpt) {
            const nom = selectedOpt.dataset.nombre || selectedOpt.textContent.trim();

            previewAnimalNombre.textContent = nom;
            previewAnimalId.textContent = 'ID: #' + animalSelect.value;
            previewFinca.textContent = selectedOpt.dataset.fincaNombre || 'No especificada';
            previewRebano.textContent = selectedOpt.dataset.rebanoNombre || 'No especificado';
        } else {
            previewAnimalNombre.textContent = 'Ningún animal';
            previewAnimalId.textContent = 'ID: #---';
            previewFinca.textContent = 'No especificada';
            previewRebano.textContent = 'No especificado';
        }

        const etapaSelectedOpt = etapaSelect.options[etapaSelect.selectedIndex];
        previewEtapa.textContent = (etapaSelect.value && etapaSelectedOpt) ? etapaSelectedOpt.textContent.trim() : 'Pendiente';
        previewTipo.textContent = tipoInput.value.trim() || 'No especificado';

        if (fechaInput.value) {
            const parts = fechaInput.value.split('-');
            if (parts.length === 3) {
                previewFecha.textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
            } else {
                previewFecha.textContent = fechaInput.value;
            }
        } else {
            previewFecha.textContent = 'No seleccionada';
        }

        const descLen = descripcionInput.value.trim().length;
        if (descLen > 0) {
            previewDescripcionEstado.textContent = `${descLen} caracteres`;
            previewDescripcionEstado.className = 'font-medium text-emerald-600';
        } else {
            previewDescripcionEstado.textContent = 'Sin observaciones';
            previewDescripcionEstado.className = 'font-medium text-gray-400';
        }
    }

    animalSelect.addEventListener('change', function () {
        const selectedOpt = this.options[this.selectedIndex];
        if (selectedOpt && selectedOpt.value) {
            const fId = selectedOpt.dataset.fincaId;
            const rId = selectedOpt.dataset.rebanoId;
            if (fId && helperFinca.value !== fId) {
                helperFinca.value = fId;
                Array.from(helperRebano.options).forEach(opt => {
                    if (!opt.value) return;
                    const match = opt.dataset.fincaId === fId;
                    opt.hidden = !match;
                    opt.disabled = !match;
                });
            }
            if (rId && helperRebano.value !== rId) {
                helperRebano.value = rId;
            }
        }
        updateStage();
    });

    etapaSelect.addEventListener('change', updatePreview);
    tipoInput.addEventListener('input', updatePreview);
    fechaInput.addEventListener('change', updatePreview);
    descripcionInput.addEventListener('input', updatePreview);

    if (animalSelect.value) {
        updateStage();
    } else {
        updatePreview();
    }
});
</script>
@endsection

