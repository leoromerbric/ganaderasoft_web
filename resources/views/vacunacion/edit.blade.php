@extends('layouts.authenticated')

@section('title', 'Editar Vacunación')

@section('content')
@php
    $selectedAnimalIds = collect(data_get($vacunacion, 'animales', []))->pluck('va_animal_id')->map(fn ($id) => (string) $id)->all();
@endphp

<div class="mb-8 flex items-center">
    <a href="{{ route('vacunacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
    </a>
    <div>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">💉 Editar Vacunación #{{ data_get($vacunacion, 'vacunacion_id') }}</h2>
    </div>
</div>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white"><h3 class="text-lg font-semibold">Actualizar registro de vacunación</h3></div>
    <form action="{{ route('vacunacion.update', data_get($vacunacion, 'vacunacion_id')) }}" method="POST" class="space-y-6 p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Vacuna *</label>
                <select name="vacunacion_vacuna_id" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    @foreach($vacunas as $vacuna)
                        <option value="{{ $vacuna['vacuna_id'] ?? '' }}" {{ old('vacunacion_vacuna_id', data_get($vacunacion, 'vacunacion_vacuna_id')) == ($vacuna['vacuna_id'] ?? '') ? 'selected' : '' }}>{{ $vacuna['vacuna_nombre'] ?? 'Vacuna' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Casa comercial</label>
                <select name="vacunacion_casa_id" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    <option value="">No especificar</option>
                    @foreach($casas as $casa)
                        <option value="{{ $casa['casa_id'] ?? '' }}" {{ old('vacunacion_casa_id', data_get($vacunacion, 'vacunacion_casa_id')) == ($casa['casa_id'] ?? '') ? 'selected' : '' }}>{{ ($casa['laboratorio'] ?? 'Casa').' - '.($casa['marca_comercial'] ?? '') }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Rebaño *</label>
                <select name="vacunacion_rebano_id" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    @foreach($rebanos as $rebano)
                        <option value="{{ $rebano['id_Rebano'] ?? '' }}" {{ old('vacunacion_rebano_id', data_get($vacunacion, 'vacunacion_rebano_id')) == ($rebano['id_Rebano'] ?? '') ? 'selected' : '' }}>{{ $rebano['Nombre'] ?? ('Rebaño #'.($rebano['id_Rebano'] ?? '')) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Modo de selección *</label>
                <select name="vacunacion_modo_seleccion" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    @php $modo = old('vacunacion_modo_seleccion', data_get($vacunacion, 'vacunacion_modo_seleccion', 'todos_rebano')); @endphp
                    <option value="todos_rebano" {{ $modo === 'todos_rebano' ? 'selected' : '' }}>Todos los animales del rebaño</option>
                    <option value="lista_animales" {{ $modo === 'lista_animales' ? 'selected' : '' }}>Lista manual de animales</option>
                    <option value="filtros" {{ $modo === 'filtros' ? 'selected' : '' }}>Filtros dentro del rebaño</option>
                </select>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Animales (múltiple para modo lista)</label>
            <select name="vacunacion_animal_ids[]" multiple size="8" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @foreach($animales as $animal)
                    @php $animalId = (string) ($animal['id_Animal'] ?? ''); @endphp
                    <option value="{{ $animalId }}" {{ in_array($animalId, old('vacunacion_animal_ids', $selectedAnimalIds), true) ? 'selected' : '' }}>
                        {{ $animal['Nombre'] ?? ('Animal #'.$animalId) }}
                    </option>
                @endforeach
            </select>
        </div>

        @php $filtros = old('vacunacion_filtros', data_get($vacunacion, 'vacunacion_filtros', [])); @endphp
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-600">Sexo</label>
                <select name="vacunacion_filtros[sexo]" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    <option value="">Todos</option>
                    <option value="H" {{ data_get($filtros, 'sexo') === 'H' ? 'selected' : '' }}>Hembra</option>
                    <option value="M" {{ data_get($filtros, 'sexo') === 'M' ? 'selected' : '' }}>Macho</option>
                </select>
            </div>
            <div><label class="mb-1 block text-xs font-medium text-gray-600">Nombre contiene</label><input type="text" name="vacunacion_filtros[nombre_like]" value="{{ data_get($filtros, 'nombre_like') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
            <div><label class="mb-1 block text-xs font-medium text-gray-600">Código contiene</label><input type="text" name="vacunacion_filtros[codigo_like]" value="{{ data_get($filtros, 'codigo_like') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
            <div><label class="mb-1 block text-xs font-medium text-gray-600">Etapa ID</label><input type="number" min="1" name="vacunacion_filtros[etapa_id]" value="{{ data_get($filtros, 'etapa_id') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
            <div><label class="mb-1 block text-xs font-medium text-gray-600">Edad mínima (días)</label><input type="number" min="0" name="vacunacion_filtros[edad_min_dias]" value="{{ data_get($filtros, 'edad_min_dias') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
            <div><label class="mb-1 block text-xs font-medium text-gray-600">Edad máxima (días)</label><input type="number" min="0" name="vacunacion_filtros[edad_max_dias]" value="{{ data_get($filtros, 'edad_max_dias') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Fecha de vacunación *</label>
                <input type="date" name="vacunacion_fecha" value="{{ old('vacunacion_fecha', data_get($vacunacion, 'vacunacion_fecha')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Costo por dosis *</label>
                <input type="number" step="0.01" min="0" name="vacunacion_costo_dosis" value="{{ old('vacunacion_costo_dosis', data_get($vacunacion, 'vacunacion_costo_dosis')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2">
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Observación</label>
            <textarea name="vacunacion_observacion" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2">{{ old('vacunacion_observacion', data_get($vacunacion, 'vacunacion_observacion')) }}</textarea>
        </div>

        <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
            <a href="{{ route('vacunacion.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="rounded-lg bg-ganaderasoft-verde px-6 py-2 text-white transition-colors hover:bg-ganaderasoft-verde/80">Actualizar</button>
        </div>
    </form>
</div>
@endsection
