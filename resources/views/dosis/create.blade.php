@extends('layouts.authenticated')

@section('title', 'Nueva Dosis')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('dosis.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🧪 Nueva Dosis</h2>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white"><h3 class="text-lg font-semibold">Plantilla de dosis y objetivo</h3></div>
        <form action="{{ route('dosis.store') }}" method="POST" class="p-6">
            @csrf
            @if($errors->any())
                <div class="mb-6 rounded border-l-4 border-red-500 bg-red-50 p-4 text-red-800"><ul class="ml-4 list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Vacuna <span class="text-red-500">*</span></label>
                    <select name="dosis_vacuna_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione una vacuna</option>
                        @foreach($vacunas as $vacuna)
                            <option value="{{ $vacuna['vacuna_id'] ?? '' }}" {{ old('dosis_vacuna_id') == ($vacuna['vacuna_id'] ?? '') ? 'selected' : '' }}>{{ $vacuna['vacuna_nombre'] ?? 'Vacuna' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Casa comercial <span class="text-red-500">*</span></label>
                    <select name="dosis_casa_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione una casa comercial</option>
                        @foreach($casas as $casa)
                            <option value="{{ $casa['casa_id'] ?? '' }}" {{ old('dosis_casa_id') == ($casa['casa_id'] ?? '') ? 'selected' : '' }}>{{ ($casa['laboratorio'] ?? 'Casa').' - '.($casa['marca_comercial'] ?? '') }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Objetivo <span class="text-red-500">*</span></label>
                    <select name="dosis_objetivo_tipo" id="dosis_objetivo_tipo" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="animal" {{ old('dosis_objetivo_tipo', 'animal') === 'animal' ? 'selected' : '' }}>Animal individual</option>
                        <option value="rebano" {{ old('dosis_objetivo_tipo') === 'rebano' ? 'selected' : '' }}>Rebaño completo</option>
                        <option value="subgrupo" {{ old('dosis_objetivo_tipo') === 'subgrupo' ? 'selected' : '' }}>Subgrupo de un rebaño</option>
                    </select>
                </div>

                <div id="campo_animal">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Animal objetivo</label>
                    <select name="dosis_objetivo_animal_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione un animal</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] ?? '' }}" {{ old('dosis_objetivo_animal_id') == ($animal['id_Animal'] ?? '') ? 'selected' : '' }}>{{ $animal['Nombre'] ?? ('Animal #'.($animal['id_Animal'] ?? '')) }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="campo_rebano" class="hidden">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Rebaño objetivo</label>
                    <select name="dosis_objetivo_rebano_id" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione un rebaño</option>
                        @foreach($rebanos as $rebano)
                            <option value="{{ $rebano['id_Rebano'] ?? '' }}" {{ old('dosis_objetivo_rebano_id') == ($rebano['id_Rebano'] ?? '') ? 'selected' : '' }}>
                                {{ $rebano['Nombre'] ?? ('Rebaño #'.($rebano['id_Rebano'] ?? '')) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="campo_subgrupo" class="hidden md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Filtros del subgrupo</label>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4 rounded-lg bg-gray-50 p-4">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Sexo</label>
                            <select name="dosis_objetivo_filtros[sexo]" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                <option value="">Todos</option>
                                <option value="H" {{ old('dosis_objetivo_filtros.sexo') === 'H' ? 'selected' : '' }}>Hembra</option>
                                <option value="M" {{ old('dosis_objetivo_filtros.sexo') === 'M' ? 'selected' : '' }}>Macho</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Edad mínima (días)</label>
                            <input type="number" min="0" name="dosis_objetivo_filtros[edad_min_dias]" value="{{ old('dosis_objetivo_filtros.edad_min_dias') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Edad máxima (días)</label>
                            <input type="number" min="0" name="dosis_objetivo_filtros[edad_max_dias]" value="{{ old('dosis_objetivo_filtros.edad_max_dias') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Etapa ID</label>
                            <input type="number" min="1" name="dosis_objetivo_filtros[etapa_id]" value="{{ old('dosis_objetivo_filtros.etapa_id') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Frecuencia (días) <span class="text-red-500">*</span></label>
                    <input type="number" min="1" name="dosis_frecuencia" value="{{ old('dosis_frecuencia', 1) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Costo</label>
                    <input type="number" step="0.01" min="0" name="dosis_costo" value="{{ old('dosis_costo') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Costo frasco</label>
                    <input type="number" step="0.01" min="0" name="dosis_costo_frasco" value="{{ old('dosis_costo_frasco') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Fecha uso inicial <span class="text-red-500">*</span></label>
                    <input type="date" name="dosis_fecha_uso_ini" value="{{ old('dosis_fecha_uso_ini', date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Fecha uso final</label>
                    <input type="date" name="dosis_fecha_uso_fin" value="{{ old('dosis_fecha_uso_fin') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Observación</label>
                    <textarea name="dosis_observacion" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">{{ old('dosis_observacion') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
                <a href="{{ route('dosis.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="rounded-lg bg-ganaderasoft-verde px-6 py-2 text-white transition-colors hover:bg-ganaderasoft-verde/80">💾 Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const objetivo = document.getElementById('dosis_objetivo_tipo');
    const campoAnimal = document.getElementById('campo_animal');
    const campoRebano = document.getElementById('campo_rebano');
    const campoSubgrupo = document.getElementById('campo_subgrupo');

    function refreshObjectiveFields() {
        const value = objetivo.value;
        campoAnimal.classList.toggle('hidden', value !== 'animal');
        campoRebano.classList.toggle('hidden', value === 'animal');
        campoSubgrupo.classList.toggle('hidden', value !== 'subgrupo');
    }

    objetivo.addEventListener('change', refreshObjectiveFields);
    refreshObjectiveFields();
});
</script>
@endsection
