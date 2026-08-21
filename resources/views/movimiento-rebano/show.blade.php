@extends('layouts.authenticated')

@section('title', 'Detalle movimiento de rebaño')

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
                    Movimiento #{{ $movimiento['id'] ?? 'N/A' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Origen: <span class="font-bold text-gray-800">{{ data_get($movimiento, 'rebano_origen.nombre', '-') }}</span> 
                    → Destino: <span class="font-bold text-ganaderasoft-azul">{{ data_get($movimiento, 'rebano_destino_rel.nombre') ?? $movimiento['rebano_destino'] ?? '-' }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('movimiento-rebano.edit', $movimiento['id']) }}" 
               class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar movimiento
            </a>
            <a href="{{ route('movimiento-rebano.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">✅</span>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Origen y Destino -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>🏡</span> Ubicaciones del traslado
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Origen del movimiento</p>
                        <div class="space-y-2">
                            <div>
                                <span class="text-xs text-gray-500">Rebaño:</span>
                                <p class="text-base font-bold text-gray-900">
                                    🐄 {{ data_get($movimiento, 'rebano_origen.nombre') ?? ($mapaRebanos[$movimiento['rebano_id'] ?? ''] ?? '-') }}
                                </p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Finca:</span>
                                <p class="text-sm font-semibold text-gray-700">
                                    🏡 {{ data_get($movimiento, 'finca_origen.nombre') ?? ($mapaFincas[$movimiento['finca_id'] ?? ''] ?? '-') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50/60 rounded-2xl border border-blue-100">
                        <p class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Destino del movimiento</p>
                        <div class="space-y-2">
                            <div>
                                <span class="text-xs text-blue-600">Rebaño:</span>
                                <p class="text-base font-bold text-ganaderasoft-azul">
                                    🐄 {{ data_get($movimiento, 'rebano_destino_rel.nombre') ?? ($mapaRebanos[$movimiento['rebano_destino_id'] ?? ''] ?? ($movimiento['rebano_destino'] ?? '-')) }}
                                </p>
                            </div>
                            <div>
                                <span class="text-xs text-blue-600">Finca:</span>
                                <p class="text-sm font-semibold text-blue-900">
                                    🏡 {{ data_get($movimiento, 'finca_destino.nombre') ?? ($mapaFincas[$movimiento['finca_destino_id'] ?? ''] ?? '-') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Animales Movidos -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                @php
                    $animales = $movimiento['animales'] ?? [];
                    $totalAnimales = count($animales);
                @endphp
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>🐄</span> Animales movidos <span class="text-sm px-3 py-1 bg-blue-50 text-blue-700 rounded-full border border-blue-200">Total: {{ $totalAnimales }}</span>
                </h3>

                @if($totalAnimales > 0)
                    <div class="overflow-x-auto border border-gray-100 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre del animal</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Código identificador</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 text-sm">
                                @foreach($animales as $item)
                                @php
                                    $animObj = data_get($item, 'animal');
                                    $animId = data_get($animObj, 'id') ?? data_get($item, 'animal_id') ?? data_get($item, 'id');
                                    $animNombre = data_get($animObj, 'nombre') ?? data_get($item, 'nombre') ?? 'Animal sin nombre';
                                    $animCodigo = data_get($animObj, 'codigo_animal') ?? data_get($item, 'codigo_animal') ?? 'N/A';
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-gray-900">
                                        #{{ $animId }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                        🐄 {{ $animNombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-600">
                                        #{{ $animCodigo }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($animId)
                                            <a href="{{ route('animales.show', $animId) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                               title="Ver expediente del animal">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-400 text-sm font-medium py-2">Sin registro explícito de animales transferidos.</p>
                @endif
            </div>

            <!-- Observaciones y Comentarios -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-4 flex items-center gap-2">
                    <span>💬</span> Comentarios u observaciones
                </h3>
                @if(!empty($movimiento['comentario']))
                    <div class="p-4 bg-gray-50 border-l-4 border-ganaderasoft-celeste rounded-xl text-gray-700 text-sm leading-relaxed">
                        {{ $movimiento['comentario'] }}
                    </div>
                @else
                    <p class="text-gray-400 text-sm font-medium py-2">Sin observaciones registradas para este traslado.</p>
                @endif
            </div>
        </div>

        <!-- Columna Derecha: Información del Sistema -->
        <div class="space-y-6">
            <!-- Registro del Sistema Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">ID del movimiento</label>
                        <p class="text-sm font-bold font-mono text-gray-900">
                            #{{ $movimiento['id'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de registro</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($movimiento['created_at']) ? date('d/m/Y H:i', strtotime($movimiento['created_at'])) : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Última modificación</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($movimiento['updated_at']) ? date('d/m/Y H:i', strtotime($movimiento['updated_at'])) : 'N/A' }}
                        </p>
                    </div>
                    @if(isset($movimiento['created_at']))
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Antigüedad del registro</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($movimiento['created_at'])->diffForHumans() }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
