@extends('layouts.authenticated')

@section('title', 'Editar Dosis')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('dosis.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🧪 Editar Dosis #{{ $dosis['dosis_id'] ?? '' }}</h2>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white"><h3 class="text-lg font-semibold">Modificar dosis</h3></div>
        <form action="{{ route('dosis.update', $dosis['dosis_id']) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="mb-6 grid grid-cols-1 gap-6 rounded-lg bg-gray-50 p-4 md:grid-cols-2">
                <div><p class="text-sm text-gray-500">Vacuna</p><p class="text-gray-900">{{ data_get($dosis, 'vacuna.vacuna_nombre') ?? ('Vacuna #'.data_get($dosis, 'dosis_vacuna_id')) }}</p></div>
                <div><p class="text-sm text-gray-500">Casa comercial</p><p class="text-gray-900">{{ data_get($dosis, 'casa_comercial.laboratorio') ?? data_get($dosis, 'casaComercial.laboratorio') ?? ('Casa #'.data_get($dosis, 'dosis_casa_id')) }}</p></div>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div><label class="mb-1 block text-sm font-medium text-gray-700">Frecuencia</label><input type="number" min="1" name="dosis_frecuencia" value="{{ old('dosis_frecuencia', $dosis['dosis_frecuencia'] ?? 1) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste"></div>
                <div><label class="mb-1 block text-sm font-medium text-gray-700">Costo</label><input type="number" step="0.01" min="0" name="dosis_costo" value="{{ old('dosis_costo', $dosis['dosis_costo'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste"></div>
                <div><label class="mb-1 block text-sm font-medium text-gray-700">Costo frasco</label><input type="number" step="0.01" min="0" name="dosis_costo_frasco" value="{{ old('dosis_costo_frasco', $dosis['dosis_costo_frasco'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste"></div>
                <div><label class="mb-1 block text-sm font-medium text-gray-700">Fecha uso inicial</label><input type="date" name="dosis_fecha_uso_ini" value="{{ old('dosis_fecha_uso_ini', $dosis['dosis_fecha_uso_ini'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste"></div>
                <div><label class="mb-1 block text-sm font-medium text-gray-700">Fecha uso final</label><input type="date" name="dosis_fecha_uso_fin" value="{{ old('dosis_fecha_uso_fin', $dosis['dosis_fecha_uso_fin'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste"></div>
            </div>
            <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6"><a href="{{ route('dosis.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a><button type="submit" class="rounded-lg bg-ganaderasoft-verde px-6 py-2 text-white transition-colors hover:bg-ganaderasoft-verde/80">💾 Actualizar</button></div>
        </form>
    </div>
</div>
@endsection