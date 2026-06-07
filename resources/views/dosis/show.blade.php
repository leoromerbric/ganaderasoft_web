@extends('layouts.authenticated')

@section('title', 'Detalle de Dosis')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('dosis.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🧪 Dosis #{{ $dosis['dosis_id'] ?? '' }}</h2>
    </div>
    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white"><h3 class="text-lg font-semibold">Detalle</h3></div>
        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">
            <div><p class="text-sm text-gray-500">Vacuna</p><p class="text-gray-900">{{ data_get($dosis, 'vacuna.vacuna_nombre') ?? ('Vacuna #'.data_get($dosis, 'dosis_vacuna_id')) }}</p></div>
            <div><p class="text-sm text-gray-500">Casa comercial</p><p class="text-gray-900">{{ data_get($dosis, 'casa_comercial.laboratorio') ?? data_get($dosis, 'casaComercial.laboratorio') ?? ('Casa #'.data_get($dosis, 'dosis_casa_id')) }}</p></div>
            <div><p class="text-sm text-gray-500">Frecuencia</p><p class="text-gray-900">{{ $dosis['dosis_frecuencia'] ?? '-' }}</p></div>
            <div><p class="text-sm text-gray-500">Costo</p><p class="text-gray-900">{{ $dosis['dosis_costo'] ?? '-' }}</p></div>
            <div><p class="text-sm text-gray-500">Costo frasco</p><p class="text-gray-900">{{ $dosis['dosis_costo_frasco'] ?? '-' }}</p></div>
            <div><p class="text-sm text-gray-500">Vigencia</p><p class="text-gray-900">{{ $dosis['dosis_fecha_uso_ini'] ?? '-' }}{{ isset($dosis['dosis_fecha_uso_fin']) ? ' a '.$dosis['dosis_fecha_uso_fin'] : '' }}</p></div>
        </div>
    </div>
</div>
@endsection