@extends('layouts.authenticated')

@section('title', 'Detalle de Vacunación')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div class="flex items-center">
        <a href="{{ route('vacunacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">💉 Vacunación #{{ data_get($vacunacion, 'vacunacion_id') }}</h2>
    </div>
</div>

<div class="rounded-xl bg-white p-6 shadow-md">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div><p class="text-sm text-gray-500">Vacuna</p><p class="text-gray-900">{{ data_get($vacunacion, 'vacuna.vacuna_nombre') ?? ('Vacuna #'.data_get($vacunacion, 'vacunacion_vacuna_id')) }}</p></div>
        <div><p class="text-sm text-gray-500">Rebaño</p><p class="text-gray-900">{{ data_get($vacunacion, 'rebano.Nombre') ?? ('#'.data_get($vacunacion, 'vacunacion_rebano_id')) }}</p></div>
        <div><p class="text-sm text-gray-500">Fecha de vacunación</p><p class="text-gray-900">{{ data_get($vacunacion, 'vacunacion_fecha') }}</p></div>
        <div><p class="text-sm text-gray-500">Costo individual de la dosis</p><p class="text-gray-900">{{ number_format((float) data_get($vacunacion, 'vacunacion_costo_dosis', 0), 2, ',', '.') }}</p></div>
        <div><p class="text-sm text-gray-500">Total animales</p><p class="text-gray-900">{{ data_get($vacunacion, 'animales_count', data_get($vacunacion, 'vacunacion_total_animales', 0)) }}</p></div>
        <div><p class="text-sm text-gray-500">Monto total</p><p class="text-gray-900">{{ number_format((float) data_get($vacunacion, 'vacunacion_monto_total', 0), 2, ',', '.') }}</p></div>
        <div class="md:col-span-2"><p class="text-sm text-gray-500">Observación</p><p class="text-gray-900">{{ data_get($vacunacion, 'vacunacion_observacion') ?: '-' }}</p></div>
    </div>
</div>

<div class="mt-6 rounded-xl bg-white p-6 shadow-md">
    <h3 class="mb-4 text-lg font-semibold text-ganaderasoft-negro">Animales incluidos</h3>
    @php $items = data_get($vacunacion, 'animales', []); @endphp
    @if(empty($items))
        <p class="text-gray-500">No hay animales asociados.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID Animal</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nombre</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Código</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($items as $row)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ data_get($row, 'va_animal_id') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ data_get($row, 'animal.Nombre', '-') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ data_get($row, 'animal.codigo_animal', '-') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
