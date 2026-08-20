@extends('layouts.authenticated')

@section('title', 'Editar vacunación')

@section('content')
@php
    $id = $vacunacion['id'] ?? null;
    $animal = $vacunacion['animal'] ?? [];
    $animalId = $vacunacion['animal_id'] ?? data_get($animal, 'id');
    $animalNombre = data_get($animal, 'nombre') ?? ('Animal #'.$animalId);
    $animalCodigo = data_get($animal, 'codigo_animal') ?? 'S/C';
    $animalSexo = data_get($animal, 'sexo');
    $rebanoNombre = data_get($animal, 'rebano.nombre') ?? ('Rebaño #'.data_get($animal, 'rebano_id', ''));
    $fincaNombre = data_get($animal, 'rebano.finca.nombre') ?? '';

    $fechaValor = $vacunacion['fecha'] ?? null;
    $fechaValor = $fechaValor ? \Illuminate\Support\Carbon::parse($fechaValor)->format('Y-m-d') : date('Y-m-d');
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-verde/15 text-ganaderasoft-verde-oscuro flex items-center justify-center font-bold text-2xl shadow-sm border border-ganaderasoft-verde/20">
                💉
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar vacunación #{{ $id }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Animal: <span class="font-bold text-gray-800">{{ $animalCodigo }} - {{ $animalNombre }}</span>
                </p>
            </div>
        </div>
        <div>
            <a href="{{ route('vacunacion.index') }}" 
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
            <div class="flex items-center space-x-3">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('vacunacion.update', $id) }}" novalidate class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Animal Asociado (Informativo) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🐮</span> Animal receptor de la vacuna
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200/70">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre / Código</p>
                            <p class="text-base font-bold text-gray-900">{{ $animalCodigo }} - {{ $animalNombre }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Sexo</p>
                            <p class="text-base font-medium text-gray-800">{{ $animalSexo === 'H' ? 'Hembra' : ($animalSexo === 'M' ? 'Macho' : '—') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ubicación</p>
                            <p class="text-base font-medium text-gray-800">{{ $rebanoNombre }} @if($fincaNombre) ({{ $fincaNombre }}) @endif</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Datos de la Vacunación -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🧪</span> Detalles del biológico y aplicación
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="vacuna_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Vacuna utilizada <span class="text-red-500">*</span>
                            </label>
                            <select id="vacuna_id" name="vacuna_id" required
                                    class="w-full px-4 py-3 border @error('vacuna_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                @foreach($vacunas as $vacuna)
                                    @php
                                        $vacId = $vacuna['id'] ?? $vacuna['vacuna_id'] ?? '';
                                        $vacNombre = $vacuna['nombre'] ?? $vacuna['vacuna_nombre'] ?? 'Vacuna';
                                        $selected = old('vacuna_id', $vacunacion['vacuna_id'] ?? '') == $vacId;
                                    @endphp
                                    <option value="{{ $vacId }}" {{ $selected ? 'selected' : '' }}>
                                        {{ $vacNombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vacuna_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="fecha" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de aplicación <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="fecha" name="fecha" value="{{ old('fecha', $fechaValor) }}" required
                                   class="w-full px-4 py-3 border @error('fecha') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('fecha')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="dosis" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Dosis (ml)
                            </label>
                            <input type="number" step="0.01" min="0" id="dosis" name="dosis" value="{{ old('dosis', $vacunacion['dosis'] ?? '') }}" placeholder="Ej: 2.50"
                                   class="w-full px-4 py-3 border @error('dosis') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('dosis')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="costo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Costo individual ($)
                            </label>
                            <input type="number" step="0.01" min="0" id="costo" name="costo" value="{{ old('costo', $vacunacion['costo'] ?? '0.00') }}" placeholder="0.00"
                                   class="w-full px-4 py-3 border @error('costo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('costo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="lote" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Número de lote
                            </label>
                            <input type="text" id="lote" name="lote" value="{{ old('lote', $vacunacion['lote'] ?? '') }}" placeholder="Ej: LOTE-2026-X"
                                   class="w-full px-4 py-3 border @error('lote') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('lote')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 3: Observaciones -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>📝</span> Observaciones o notas sanitarias
                    </h3>
                    <textarea name="observacion" rows="3" placeholder="Detalles de la aplicación, reacciones..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('observacion', $vacunacion['observacion'] ?? '') }}</textarea>
                </div>
            </div>

            <!-- Columna Derecha: Acciones (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6 sticky top-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>⚙️</span> Guardar cambios
                    </h3>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200/70 text-sm text-gray-600 space-y-2">
                        <p class="font-semibold text-gray-800">Actualización individual</p>
                        <p class="text-xs text-gray-500">Los cambios afectarán únicamente el registro sanitario de este animal.</p>
                    </div>

                    <div class="space-y-3">
                        <button type="submit"
                                class="w-full py-3.5 px-6 bg-ganaderasoft-verde-oscuro text-white font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Actualizar vacunación
                        </button>
                        <a href="{{ route('vacunacion.index') }}"
                           class="w-full py-3 px-6 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
