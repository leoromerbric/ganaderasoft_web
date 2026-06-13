@extends('layouts.authenticated')

@section('title', 'Editar Peso Corporal')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('peso-corporal.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">📊 Editar Peso Corporal #{{ $pesoCorporal['peso_id'] }}</h2>
    </div>

    <div class="rounded-xl bg-white shadow-md">
        <div class="rounded-t-xl bg-ganaderasoft-celeste px-6 py-4 text-white">
            <h3 class="text-lg font-semibold">Modificar Datos</h3>
        </div>
        <form action="{{ route('peso-corporal.update', $pesoCorporal['peso_id']) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            @if($errors->any())
                <div class="mb-6 rounded border-l-4 border-red-500 bg-red-50 p-4 text-red-800">
                    <ul class="ml-4 list-disc">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @php
                $borderClass = fn (string $field) => $errors->has($field) ? 'border-red-500' : 'border-gray-300';
            @endphp

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Animal</label>
                    <input type="text" readonly
                           value="{{ $pesoCorporal['animal']['Nombre'] ?? ('Animal #'.($pesoCorporal['peso_etapa_anid'] ?? '')) }}"
                           class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-600">
                    <input type="hidden" name="peso_etapa_anid" value="{{ old('peso_etapa_anid', $pesoCorporal['peso_etapa_anid'] ?? '') }}">
                    <input type="hidden" name="peso_etapa_etid" value="{{ old('peso_etapa_etid', $pesoCorporal['peso_etapa_etid'] ?? '') }}">
                    @error('peso_etapa_anid')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Fecha de Pesaje <span class="text-red-500">*</span></label>
                    @php $vFecha = old('Fecha_Peso') ?: (($r=$pesoCorporal['Fecha_Peso']??null) ? substr($r,0,10) : ''); @endphp
                    <input type="date" name="Fecha_Peso" required value="{{ $vFecha }}"
                           class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('Fecha_Peso') }}">
                    @error('Fecha_Peso')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Peso (kg) <span class="text-red-500">*</span></label>
                    <input type="number" name="Peso" required step="0.01" min="0.01" max="9999"
                           value="{{ old('Peso', $pesoCorporal['Peso'] ?? '') }}"
                           class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $borderClass('Peso') }}">
                    @error('Peso')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Comentario</label>
                    <input type="text" name="Comentario" maxlength="255"
                           value="{{ old('Comentario', $pesoCorporal['Comentario'] ?? '') }}"
                           placeholder="Observaciones del pesaje..."
                           class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste">
                    @error('Comentario')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
                <a href="{{ route('peso-corporal.index') }}"
                   class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
