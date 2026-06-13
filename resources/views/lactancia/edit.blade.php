@extends('layouts.authenticated')

@section('title', 'Editar Lactancia')

@section('content')
<div>
    <div class="mb-8 flex items-center">
        <a href="{{ route('lactancia.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">Editar Período de Lactancia #{{ $lactancia['lactancia_id'] }}</h2>
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
        <form method="POST" action="{{ route('lactancia.update', $lactancia['lactancia_id']) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Animal</label>
                @php
                    $animalNombre = data_get($lactancia, 'animal.Nombre')
                        ?? ('Animal #'.(data_get($lactancia, 'lactancia_etapa_anid') ?? 'N/A'));
                    $etapaActual = data_get($lactancia, 'animal.etapa_actual.etapa.etapa_nombre')
                        ?? data_get($lactancia, 'animal.etapaActual.etapa.etapa_nombre')
                        ?? data_get($lactancia, 'animal.etapa_actual.etapa.Nombre')
                        ?? data_get($lactancia, 'animal.etapaActual.etapa.Nombre');
                @endphp
                <input type="text" readonly value="{{ $animalNombre }}" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-lg text-gray-600">
                <input type="hidden" name="lactancia_etapa_anid" value="{{ old('lactancia_etapa_anid', data_get($lactancia, 'lactancia_etapa_anid')) }}">
                <input type="hidden" name="lactancia_etapa_etid" value="{{ old('lactancia_etapa_etid', data_get($lactancia, 'lactancia_etapa_etid')) }}">
                <p class="mt-2 text-sm text-gray-500">Etapa actual: {{ $etapaActual ?? 'No disponible' }}</p>
            </div>

            <div>
                <label for="lactancia_fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio</label>
                <input type="date" name="lactancia_fecha_inicio" id="lactancia_fecha_inicio"
                       value="{{ old('lactancia_fecha_inicio', data_get($lactancia, 'lactancia_fecha_inicio')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste @error('lactancia_fecha_inicio') border-red-500 @enderror">
                @error('lactancia_fecha_inicio')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="Lactancia_fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Fin (Opcional)</label>
                <input type="date" name="Lactancia_fecha_fin" id="Lactancia_fecha_fin"
                       value="{{ old('Lactancia_fecha_fin', data_get($lactancia, 'Lactancia_fecha_fin')) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste @error('Lactancia_fecha_fin') border-red-500 @enderror">
                @error('Lactancia_fecha_fin')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="lactancia_secado" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Secado (Opcional)</label>
                <input type="date" name="lactancia_secado" id="lactancia_secado"
                       value="{{ old('lactancia_secado', data_get($lactancia, 'lactancia_secado')) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste @error('lactancia_secado') border-red-500 @enderror">
                @error('lactancia_secado')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('lactancia.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancelar</a>
                <button type="submit" class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
