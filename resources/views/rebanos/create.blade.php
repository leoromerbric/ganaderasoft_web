@extends('layouts.authenticated')

@section('title', 'Crear rebaño')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Header & Breadcrumb -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Crear nuevo rebaño</h1>
                <p class="text-sm text-gray-500 mt-1">Registre un grupo o lote de ganado</p>
            </div>
            <a href="{{ route('rebanos.index') }}"
                class="inline-flex items-center text-sm text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-medium transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a rebaños
            </a>
        </div>

        <!-- Error Alert -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm" role="alert">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">⚠️</span>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm">
                <ul class="list-disc ml-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-6">
            <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-xl">
                    🐄
                </div>
                <div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro">Información del rebaño</h3>
                    <p class="text-xs text-gray-500">Seleccione la finca y nombre del rebaño</p>
                </div>
            </div>

            <form method="POST" action="{{ route('rebanos.store') }}" class="space-y-6">
                @csrf

                <!-- Selector de Finca -->
                <div>
                    <label for="finca_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Finca destino <span class="text-red-500">*</span>
                    </label>
                    <select name="finca_id" id="finca_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Seleccione una finca...</option>
                        @foreach($fincas as $finca)
                            @php
                                $fId = $finca['id'] ?? null;
                                $fNombre = $finca['nombre'] ?? ('Finca #' . $fId);
                                $isSelected = (string) old('finca_id', request()->query('finca_id')) === (string) $fId;
                            @endphp
                            <option value="{{ $fId }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $fNombre }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Selecciona la finca a la cual pertenecerá este rebaño</p>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Nombre del rebaño <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" id="nombre" required value="{{ old('nombre') }}"
                        placeholder="Ej: Rebaño vacas lecheras, rebaño norte, lote engorde"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-1">Nombre distintivo para identificar y agrupar los animales</p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('rebanos.index') }}"
                        class="px-6 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-ganaderasoft-verde-oscuro text-white text-sm font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        Guardar rebaño
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection