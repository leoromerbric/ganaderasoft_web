@extends('layouts.authenticated')

@section('title', 'Editar tratamiento')

@section('content')
@php
    $id = $tratamiento['id'] ?? $tratamiento['tratamiento_id'] ?? null;
    $diagId = $tratamiento['diagnostico_id'] ?? $tratamiento['tratamiento_diagnostico_id'] ?? null;
    $fechaIni = $tratamiento['fecha_ini'] ?? $tratamiento['tratamiento_fecha_ini'] ?? null;
    $fechaFin = $tratamiento['fecha_fin'] ?? $tratamiento['tratamiento_fecha_fin'] ?? null;
    $plan = $tratamiento['plan'] ?? $tratamiento['tratamiento_plan'] ?? '';
@endphp
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('tratamiento.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">💊 Editar tratamiento #{{ $id }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Modificar datos</h3>
        </div>
        <form action="{{ route('tratamiento.update', $id) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico</label>
                    <select name="diagnostico_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('diagnostico_id') border-red-500 @enderror">
                        <option value="">-- Sin diagnóstico --</option>
                        @foreach($diagnosticos as $diagnostico)
                            @php
                                $dId = $diagnostico['id'] ?? $diagnostico['diagnostico_id'] ?? '';
                                $dTipo = $diagnostico['tipo'] ?? $diagnostico['diagnostico_tipo'] ?? '';
                                $dFecha = $diagnostico['fecha'] ?? $diagnostico['diagnostico_fecha'] ?? null;
                                $aNombre = data_get($diagnostico, 'animal.Nombre') ?? '';
                            @endphp
                            <option value="{{ $dId }}" {{ old('diagnostico_id', $diagId) == $dId ? 'selected' : '' }}>
                                {{ $aNombre }} - {{ $dTipo }}
                                ({{ $dFecha ? date('d/m/Y', strtotime($dFecha)) : '' }})
                                #{{ $dId }}
                            </option>
                        @endforeach
                    </select>
                    @error('diagnostico_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plan de tratamiento</label>
                    <input type="text" name="plan" maxlength="255"
                           value="{{ old('plan', $plan) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('plan') border-red-500 @enderror">
                    @error('plan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de inicio <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha_ini" required
                           value="{{ old('fecha_ini', $fechaIni ? \Carbon\Carbon::parse($fechaIni)->format('Y-m-d') : date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('fecha_ini') border-red-500 @enderror">
                    @error('fecha_ini')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de fin <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha_fin" required
                           value="{{ old('fecha_fin', $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format('Y-m-d') : '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('fecha_fin') border-red-500 @enderror">
                    @error('fecha_fin')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('tratamiento.index') }}"
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
