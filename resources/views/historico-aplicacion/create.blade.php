@extends('layouts.authenticated')

@section('title', 'Nuevo Histórico de Aplicación')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('historico-aplicacion.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">📋 Nuevo Histórico de Aplicación</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos de la Aplicación</h3>
        </div>
        <form action="{{ route('historico-aplicacion.store') }}" method="POST" class="p-6">
            @csrf
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vacuna <span class="text-red-500">*</span></label>
                    <select name="ha_vacuna_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione una vacuna</option>
                        @foreach($vacunas as $vacuna)
                            <option value="{{ $vacuna['vacuna_id'] }}" {{ old('ha_vacuna_id') == $vacuna['vacuna_id'] ? 'selected' : '' }}>
                                {{ $vacuna['vacuna_nombre'] ?? 'Vacuna #'.$vacuna['vacuna_id'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('ha_vacuna_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Casa Comercial</label>
                    <select name="ha_casa_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">-- Sin casa comercial --</option>
                        @foreach($casas as $casa)
                            <option value="{{ $casa['casa_id'] }}" {{ old('ha_casa_id') == $casa['casa_id'] ? 'selected' : '' }}>
                                {{ $casa['laboratorio'] ?? '' }} - {{ $casa['marca_comercial'] ?? '' }} (#{{ $casa['casa_id'] }})
                            </option>
                        @endforeach
                    </select>
                    @error('ha_casa_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                    <div class="mb-2 text-sm">
                        <a href="{{ route('dosis.create') }}" class="text-ganaderasoft-azul hover:underline">Agregar nueva dosis</a>
                    </div>
                    <select name="ha_dosis_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">-- Sin dosis --</option>
                        @foreach($dosis as $d)
                            <option value="{{ $d['dosis_id'] }}" {{ old('ha_dosis_id') == $d['dosis_id'] ? 'selected' : '' }}>
                                {{ $d['vacuna']['vacuna_nombre'] ?? '' }} - {{ $d['dosis_frecuencia'] ?? '' }} (#{{ $d['dosis_id'] }})
                            </option>
                        @endforeach
                    </select>
                    @if(count($dosis) === 0)
                        <p class="mt-2 text-sm text-gray-500">No hay dosis registradas. Use el enlace anterior para agregar una.</p>
                    @endif
                    @error('ha_dosis_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Animal</label>
                    <select name="ha_animal_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">-- Seleccione un animal --</option>
                        @foreach($animales as $animal)
                            <option value="{{ $animal['id_Animal'] }}" {{ old('ha_animal_id') == $animal['id_Animal'] ? 'selected' : '' }}>
                                {{ $animal['Nombre'] ?? ('Animal #'.$animal['id_Animal']) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Si selecciona dosis, este campo es opcional y la API puede expandir al grupo objetivo.</p>
                    @error('ha_animal_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inyección <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha_inyeccion" required value="{{ old('fecha_inyeccion', date('Y-m-d')) }}"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                    @error('fecha_inyeccion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                    <textarea name="observacion" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">{{ old('observacion') }}</textarea>
                    @error('observacion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('historico-aplicacion.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                    💾 Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
