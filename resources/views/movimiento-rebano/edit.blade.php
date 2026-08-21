@extends('layouts.authenticated')

@section('title', 'Editar movimiento de rebaño')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                🔄
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Editar movimiento #{{ $movimiento['id'] ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Actualiza las notas y observaciones adicionales del traslado</p>
            </div>
        </div>
        <div>
            <a href="{{ route('movimiento-rebano.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm">
            <p class="text-sm font-bold mb-1">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm pl-2 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form & Content Layout -->
    <form action="{{ route('movimiento-rebano.update', $movimiento['id']) }}" method="POST">
        @csrf 
        @method('PUT')
        
        <input type="hidden" name="rebano_destino" value="{{ old('rebano_destino', $movimiento['rebano_destino'] ?? '') }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Información de Lectura y Edición de Comentarios (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Resumen Inmutable de Ubicaciones -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>🚚</span> Ubicaciones del traslado
                        </h3>
                        <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-600 rounded-full border border-gray-200 inline-flex items-center gap-1">
                            🔒 Inmutables por trazabilidad
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Origen Card -->
                        <div class="p-5 bg-amber-50/60 border border-amber-100 rounded-2xl space-y-3">
                            <p class="text-xs font-bold text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span>🏡</span> Ubicación de origen
                            </p>
                            <div>
                                <span class="text-xs text-gray-500">Rebaño:</span>
                                <p class="text-base font-bold text-gray-900">
                                    🐄 {{ data_get($movimiento, 'rebano_origen.nombre') ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Finca:</span>
                                <p class="text-sm font-semibold text-gray-700">
                                    🏡 {{ data_get($movimiento, 'finca_origen.nombre') ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Destino Card -->
                        <div class="p-5 bg-blue-50/60 border border-blue-100 rounded-2xl space-y-3">
                            <p class="text-xs font-bold text-blue-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span>🎯</span> Ubicación de destino
                            </p>
                            <div>
                                <span class="text-xs text-blue-600">Rebaño:</span>
                                <p class="text-base font-bold text-ganaderasoft-azul">
                                    🐄 {{ data_get($movimiento, 'rebano_destino_rel.nombre') ?? $movimiento['rebano_destino'] ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-xs text-blue-600">Finca:</span>
                                <p class="text-sm font-semibold text-blue-900">
                                    🏡 {{ data_get($movimiento, 'finca_destino.nombre') ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campo Editable de Comentarios -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2 border-b border-gray-100 pb-3">
                        <span>💬</span> Observaciones del registro
                    </h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Comentarios u observaciones</label>
                        <textarea name="comentario" rows="3" maxlength="40"
                                  placeholder="Escriba algún comentario o nota importante sobre la transferencia..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">{{ old('comentario', $movimiento['comentario'] ?? '') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Máximo 40 caracteres.</p>
                        @error('comentario')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Panel Lateral de Guardado (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>⚙️</span> Guardar cambios
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="text-xs text-gray-500 space-y-2 border-b border-gray-100 pb-4">
                            <div class="flex justify-between">
                                <span>ID registro:</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $movimiento['id'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fecha registro:</span>
                                <span class="font-semibold text-gray-800">{{ isset($movimiento['created_at']) ? date('d/m/Y H:i', strtotime($movimiento['created_at'])) : 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar cambios
                            </button>

                            <a href="{{ route('movimiento-rebano.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
