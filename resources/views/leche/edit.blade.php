@extends('layouts.authenticated')

@section('title', 'Editar Registro de Leche')

@section('content')
<div>
    <div class="mb-8 flex items-center">
        <a href="{{ route('leche.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">Editar Registro de Leche #{{ $registroLeche['leche_id'] }}</h2>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md p-8">
        <form method="POST" action="{{ route('leche.update', $registroLeche['leche_id']) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="leche_lactancia_id" class="block text-sm font-medium text-gray-700 mb-2">Período de Lactancia</label>
                <select name="leche_lactancia_id" id="leche_lactancia_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste @error('leche_lactancia_id') border-red-500 @enderror">
                    @foreach($lactancias as $lactancia)
                        @php
                            $fechaInicio = date('d/m/Y', strtotime($lactancia['lactancia_fecha_inicio']));
                            $fechaFin = $lactancia['Lactancia_fecha_fin'] ? date('d/m/Y', strtotime($lactancia['Lactancia_fecha_fin'])) : 'En curso';
                            $animalNombre = $lactancia['animal']['Nombre'] ?? ('Animal #'.($lactancia['lactancia_etapa_anid'] ?? 'N/A'));
                            $selected = old('leche_lactancia_id', $registroLeche['leche_lactancia_id']) == $lactancia['lactancia_id'];
                        @endphp
                        <option value="{{ $lactancia['lactancia_id'] }}" {{ $selected ? 'selected' : '' }}>
                            {{ $animalNombre }} - {{ $fechaInicio }} hasta {{ $fechaFin }}
                        </option>
                    @endforeach
                </select>
                @error('leche_lactancia_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="leche_fecha_pesaje" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Pesaje</label>
                <input type="date" name="leche_fecha_pesaje" id="leche_fecha_pesaje"
                       value="{{ old('leche_fecha_pesaje', $registroLeche['leche_fecha_pesaje']) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste @error('leche_fecha_pesaje') border-red-500 @enderror">
                @error('leche_fecha_pesaje')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="leche_pesaje_Total" class="block text-sm font-medium text-gray-700 mb-2">Cantidad de Leche (Litros)</label>
                <input type="number" name="leche_pesaje_Total" id="leche_pesaje_Total"
                       value="{{ old('leche_pesaje_Total', $registroLeche['leche_pesaje_Total']) }}"
                       step="0.01" min="0" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste @error('leche_pesaje_Total') border-red-500 @enderror">
                @error('leche_pesaje_Total')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('leche.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancelar</a>
                <button type="submit" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
