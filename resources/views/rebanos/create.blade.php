@extends('layouts.authenticated')

@section('title', 'Crear nuevo rebaño')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🐄
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Crear nuevo rebaño
                </h1>
                <p class="text-gray-500 text-sm mt-1">Registre un nuevo grupo, lote o categoría de animales para una finca</p>
            </div>
        </div>
        <div>
            <a href="{{ route('rebanos.index') }}" 
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
    <form method="POST" action="{{ route('rebanos.store') }}" id="formCreateRebano" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Columna Izquierda: 2 Cajas Independientes (2 Tercios) -->
            <div class="lg:col-span-2 flex flex-col gap-6">
                
                <!-- Card 1: Información Principal del Rebaño -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📋</span> Datos del rebaño
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Finca Destino -->
                        <div>
                            <label for="finca_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Finca de ubicación <span class="text-red-500">*</span>
                            </label>
                            <select name="finca_id" id="finca_id" required
                                    class="w-full px-4 py-3 border @error('finca_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar finca --</option>
                                @foreach($fincas as $finca)
                                    @php
                                        $fId = $finca['id'] ?? null;
                                        $fNombre = $finca['nombre'] ?? ('Finca #' . $fId);
                                        $fTipo = $finca['explotacion_tipo'] ?? 'General';
                                        $isSelected = (string) old('finca_id', request()->query('finca_id')) === (string) $fId;
                                    @endphp
                                    <option value="{{ $fId }}" data-nombre="{{ $fNombre }}" data-tipo="{{ $fTipo }}" {{ $isSelected ? 'selected' : '' }}>
                                        🏡 {{ $fNombre }} ({{ $fTipo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('finca_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            <p class="text-[11px] text-gray-400 mt-1">Finca a la cual pertenecerá la agrupación de animales.</p>
                        </div>

                        <!-- Nombre del Rebaño -->
                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Nombre del rebaño o lote <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" required 
                                   value="{{ old('nombre') }}" maxlength="100"
                                   placeholder="Ej: Vacas en ordeño, mautas norte, lote de ceba #1..."
                                   class="w-full px-4 py-3 border @error('nombre') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            <p class="text-[11px] text-gray-400 mt-1">Nombre distintivo para identificar y organizar el ganado.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Buenas Prácticas de Agrupación Ganadera (Alineada con borde inferior) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex-1 flex flex-col justify-between space-y-4">
                    <h4 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <span>💡</span> Criterios recomendados para la creación de rebaños
                    </h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs flex-1 items-stretch">
                        <div class="p-4 bg-emerald-50/70 border border-emerald-100 rounded-xl space-y-1 flex flex-col justify-between">
                            <div>
                                <span class="font-bold text-emerald-900 block mb-1">🥛 Etapa productiva:</span>
                                <p class="text-emerald-800 leading-relaxed">Agrupa animales según su estado de lactancia, secado o engorde para optimizar la ración nutricional.</p>
                            </div>
                        </div>

                        <div class="p-4 bg-blue-50/70 border border-blue-100 rounded-xl space-y-1 flex flex-col justify-between">
                            <div>
                                <span class="font-bold text-blue-900 block mb-1">⚖️ Peso y edad:</span>
                                <p class="text-blue-800 leading-relaxed">Mantén lotes homogéneos en tamaño para reducir la dominancia social y competencia en comederos.</p>
                            </div>
                        </div>

                        <div class="p-4 bg-purple-50/70 border border-purple-100 rounded-xl space-y-1 flex flex-col justify-between">
                            <div>
                                <span class="font-bold text-purple-900 block mb-1">🧬 Sanidad y origen:</span>
                                <p class="text-purple-800 leading-relaxed">Separa animales recién ingresados en rebaños de cuarentena antes de integrarlos al lote general.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen del rebaño
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-2xl shadow-xs shrink-0">
                                🐄
                            </div>
                            <div class="overflow-hidden">
                                <p id="previewNombre" class="text-base font-bold text-gray-900 truncate">Nuevo rebaño</p>
                                <p id="previewFinca" class="text-xs text-gray-500 font-medium truncate">Sin finca seleccionada</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Finca destino:</span>
                                <span id="previewFincaNombre" class="font-bold text-gray-900 text-right truncate">No especificada</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Tipo explotación:</span>
                                <span id="previewExplotacion" class="font-bold text-blue-700 text-right">General</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Estado inicial:</span>
                                <span class="font-bold text-emerald-700 text-right">Activo (0 animales)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons en el fondo de la columna derecha -->
                <div class="p-6 pt-0 space-y-3">
                    <button type="submit"
                            class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                        💾 Guardar rebaño
                    </button>
                    <a href="{{ route('rebanos.index') }}"
                       class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fincaSelect = document.getElementById('finca_id');
    const nombreInput = document.getElementById('nombre');

    const previewNombre = document.getElementById('previewNombre');
    const previewFinca = document.getElementById('previewFinca');
    const previewFincaNombre = document.getElementById('previewFincaNombre');
    const previewExplotacion = document.getElementById('previewExplotacion');

    function updatePreview() {
        const nom = (nombreInput ? nombreInput.value : '').trim();
        if (previewNombre) {
            previewNombre.textContent = nom || 'Nuevo rebaño';
        }

        const opt = fincaSelect ? fincaSelect.options[fincaSelect.selectedIndex] : null;
        if (opt && opt.value) {
            const fNom = opt.dataset.nombre || opt.textContent.replace(/🏡|\(.*\)/g, '').trim();
            const fTipo = opt.dataset.tipo || 'General';

            if (previewFinca) previewFinca.textContent = '🏡 ' + fNom;
            if (previewFincaNombre) previewFincaNombre.textContent = fNom;
            if (previewExplotacion) previewExplotacion.textContent = fTipo;
        } else {
            if (previewFinca) previewFinca.textContent = 'Sin finca seleccionada';
            if (previewFincaNombre) previewFincaNombre.textContent = 'No especificada';
            if (previewExplotacion) previewExplotacion.textContent = 'General';
        }
    }

    fincaSelect?.addEventListener('change', updatePreview);
    nombreInput?.addEventListener('input', updatePreview);

    updatePreview();
});
</script>
@endsection