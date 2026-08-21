@extends('layouts.authenticated')

@section('title', 'Editar servicio animal')

@section('content')
@php
    $id = $servicio['id'] ?? $servicio['servicio_id'] ?? null;
    $animalId = $servicio['animal_id'] ?? $servicio['servicio_id_Animal'] ?? data_get($servicio, 'etapa_animal.animal_id');
    $animalNombre = data_get($servicio, 'animal.Nombre') ?? ('Animal #'.$animalId);
    $semenId = $servicio['semen_id'] ?? $servicio['servicio_semen_id'] ?? null;
    $tecnicoId = $servicio['tecnico_id'] ?? $servicio['servicio_id_Tecnico'] ?? null;
    $celoId = $servicio['celo_id'] ?? $servicio['servicio_celo_id'] ?? null;
    $tipo = $servicio['tipo'] ?? $servicio['servicio_tipo'] ?? '';
    $fechaRaw = old('fecha', $servicio['fecha'] ?? $servicio['servicio_fecha'] ?? null);
    $fechaValue = '';
    if (!empty($fechaRaw)) {
        try {
            $fechaValue = \Carbon\Carbon::parse($fechaRaw)->format('Y-m-d');
        } catch (\Exception $e) {
            $fechaValue = '';
        }
    }
    $observacion = $servicio['observacion'] ?? $servicio['servicio_observacion'] ?? '';
    $borderClass = fn (string $field) => $errors->has($field) ? 'border-red-500' : 'border-gray-300';
@endphp
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('servicio-animal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🐂 Editar servicio animal #{{ $id }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Modificar datos</h3>
        </div>
        <form action="{{ route('servicio-animal.update', $id) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Animal</label>
                    <input type="text" readonly
                           value="{{ $animalNombre }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semen / toro</label>
                    <select name="semen_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('semen_id') }}">
                        <option value="">-- Sin semen --</option>
                        @foreach($semenToros as $semen)
                            @php
                                $semId = $semen['id'] ?? $semen['semen_id'] ?? '';
                                $toroNombre = data_get($semen, 'toro.Nombre') ?? $semen['descripcion'] ?? $semen['codigo'] ?? ('Semen #'.$semId);
                            @endphp
                            <option value="{{ $semId }}" {{ old('semen_id', $semenId) == $semId ? 'selected' : '' }}>
                                {{ $toroNombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('semen_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Técnico</label>
                    <select name="tecnico_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('tecnico_id') }}">
                        <option value="">-- Sin técnico --</option>
                        @foreach($personal as $persona)
                            @php
                                $personalId = data_get($persona, 'id') ?? data_get($persona, 'id_Tecnico') ?? data_get($persona, 'id_Personal') ?? data_get($persona, 'personal.id') ?? data_get($persona, 'personal.id_Tecnico') ?? data_get($persona, 'personal.id_Personal');
                                $personalNombre = trim((data_get($persona, 'Nombre') ?? data_get($persona, 'personal.Nombre') ?? '') . ' ' . (data_get($persona, 'Apellido') ?? data_get($persona, 'personal.Apellido') ?? ''));
                            @endphp
                            @continue(!$personalId)
                            <option value="{{ $personalId }}" {{ old('tecnico_id', $tecnicoId) == $personalId ? 'selected' : '' }}>
                                {{ $personalNombre !== '' ? $personalNombre : 'Personal #'.$personalId }}
                            </option>
                        @endforeach
                    </select>
                    @error('tecnico_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registro de celo</label>
                    <select name="celo_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('celo_id') }}">
                        <option value="">-- Sin registro de celo --</option>
                        @foreach($registrosCelo as $celo)
                            @php
                                $celId = $celo['id'] ?? $celo['celo_id'] ?? '';
                                $celoFecha = $celo['fecha'] ?? $celo['celo_fecha'] ?? null;
                                $animalNombre = data_get($celo, 'animal.Nombre') ?? '';
                            @endphp
                            <option value="{{ $celId }}" {{ old('celo_id', $celoId) == $celId ? 'selected' : '' }}>
                                {{ $animalNombre }} - {{ $celoFecha ? date('d/m/Y', strtotime($celoFecha)) : '' }} (#{{ $celId }})
                            </option>
                        @endforeach
                    </select>
                    @error('celo_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de servicio</label>
                    <input type="text" name="tipo" maxlength="11"
                           value="{{ old('tipo', $tipo) }}"
                           placeholder="Ej: Ia, natural..."
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('tipo') }}">
                    @error('tipo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha del servicio</label>
                    <input type="date" name="fecha"
                           value="{{ $fechaValue }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('fecha') }}">
                    @error('fecha')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                    <input type="text" name="observacion" maxlength="100"
                           value="{{ old('observacion', $observacion) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('servicio-animal.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
