@extends('layouts.authenticated')

@section('title', 'Nuevo Tratamiento')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('tratamiento.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">💊 Nuevo Tratamiento</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos del Tratamiento</h3>
        </div>
        <form action="{{ route('tratamiento.store') }}" method="POST" class="p-6">
            @csrf
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
                    <select name="tratamiento_diagnostico_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('tratamiento_diagnostico_id') border-red-500 @enderror">
                        <option value="">-- Sin diagnóstico --</option>
                        @foreach($diagnosticos as $diagnostico)
                            <option value="{{ $diagnostico['diagnostico_id'] }}" {{ old('tratamiento_diagnostico_id') == $diagnostico['diagnostico_id'] ? 'selected' : '' }}>
                                {{ $diagnostico['animal']['Nombre'] ?? '' }} - {{ $diagnostico['diagnostico_tipo'] ?? '' }}
                                ({{ isset($diagnostico['diagnostico_fecha']) ? date('d/m/Y', strtotime($diagnostico['diagnostico_fecha'])) : '' }})
                                #{{ $diagnostico['diagnostico_id'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('tratamiento_diagnostico_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plan de Tratamiento</label>
                    <input type="text" name="tratamiento_plan" value="{{ old('tratamiento_plan') }}" maxlength="255"
                           placeholder="Descripción del plan de tratamiento..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('tratamiento_plan') border-red-500 @enderror">
                    @error('tratamiento_plan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio <span class="text-red-500">*</span></label>
                    <input type="date" name="tratamiento_fecha_ini" required value="{{ old('tratamiento_fecha_ini', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('tratamiento_fecha_ini') border-red-500 @enderror">
                    @error('tratamiento_fecha_ini')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin <span class="text-red-500">*</span></label>
                    <input type="date" name="tratamiento_fecha_fin" required value="{{ old('tratamiento_fecha_fin') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('tratamiento_fecha_fin') border-red-500 @enderror">
                    @error('tratamiento_fecha_fin')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('tratamiento.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    💾 Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
