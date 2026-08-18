@extends('layouts.authenticated')

@section('title', 'Detalle de raza - ' . ($item['nombre'] ?? ''))

@section('content')
<div class="space-y-8">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            @php
                $nom = $item['nombre'] ?? 'R';
                $inicial = strtoupper(substr($nom, 0, 1));
                if (empty($inicial)) $inicial = '#';
                $isPublic = empty($item['finca_id']);
            @endphp
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-2xl shadow-sm border border-blue-100 uppercase">
                {{ $inicial }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    {{ $item['nombre'] ?? 'Sin nombre' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    <span>Siglas: <strong class="font-medium text-gray-800">{{ $item['siglas'] ?? 'N/A' }}</strong></span>
                    <span class="text-gray-300">•</span>
                    <span>ID: <strong class="font-mono font-medium text-gray-800">#{{ $item['id'] }}</strong></span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if(!$isPublic)
                <a href="{{ route($catalog['slug'].'.edit', $item['id']) }}" 
                   class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar raza
                </a>
            @endif
            <a href="{{ route($catalog['slug'].'.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Información Principal (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card 1: Identificación y Clasificación -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>🏷️</span> Identificación y clasificación
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre de la raza</p>
                        <p class="text-lg font-bold text-gray-900">{{ $item['nombre'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Siglas</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">{{ $item['siglas'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo de animal</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $tipoAnimalNombre ?? $item['tipo_animal']['nombre'] ?? $item['tipoAnimal']['nombre'] ?? 'No especificado' }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo de raza</p>
                        <p class="text-lg font-bold text-gray-900">{{ $item['tipo_raza'] ?? 'No especificado' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Propósito</p>
                        <p class="text-lg font-bold text-gray-900">{{ $item['proposito'] ?? 'No especificado' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Proporción</p>
                        <p class="text-lg font-bold text-gray-900">{{ $item['proporcion_raza'] ?? 'No especificada' }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Características Zootécnicas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro mb-6 flex items-center gap-2">
                    <span>🧬</span> Características zootécnicas
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pelaje típico</p>
                        <p class="text-base font-medium text-gray-900">{{ $item['pelaje'] ?? 'No especificado' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Origen geográfico</p>
                        <p class="text-base font-medium text-gray-900">{{ $item['origen'] ?? 'No especificado' }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Características especiales</p>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-gray-800 text-sm leading-relaxed">
                            {{ $item['caracteristica_especial'] ?? 'Sin características especiales registradas.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas de Estado y Sistema (1 Tercio) -->
        <div class="space-y-6">
            <!-- Visibilidad y Pertenencia Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="{{ $isPublic ? 'bg-blue-600' : 'bg-ganaderasoft-verde-oscuro' }} text-white px-6 py-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <span>{{ $isPublic ? '🌐' : '🔒' }}</span> Visibilidad y pertenencia
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo de visibilidad</label>
                        @if($isPublic)
                            <span class="inline-flex px-3 py-1 text-sm font-bold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                                🌐 Raza pública (Global)
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                🔒 Raza privada (Finca)
                            </span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Finca asignada</label>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $fincaNombre ?? $item['finca']['nombre'] ?? ($isPublic ? 'Disponible para todas las fincas' : 'Finca #' . ($item['finca_id'] ?? '')) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Registro del Sistema Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Identificador único</label>
                        <p class="text-sm font-semibold text-gray-900 font-mono">
                            ID #{{ $item['id'] }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de registro</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ !empty($item['created_at']) ? date('d/m/Y H:i', strtotime($item['created_at'])) : 'No registrada' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Última actualización</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ !empty($item['updated_at']) ? date('d/m/Y H:i', strtotime($item['updated_at'])) : 'No registrada' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
