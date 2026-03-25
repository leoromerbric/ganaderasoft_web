@extends('layouts.authenticated')

@section('title', 'Detalles del Cambio - GanaderaSoft')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('cambios-animal.index') }}" 
                   class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">
                    📝 Cambio #{{ $cambio['id_Cambio'] }} - {{ $cambio['Etapa_Cambio'] }}
                </h1>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">¡Éxito! </strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Panel Principal de Información -->
            <div class="lg:col-span-2">
                <!-- Información del Cambio -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-ganaderasoft-celeste text-white px-6 py-4">
                        <h2 class="text-lg font-semibold flex items-center">
                            <span class="mr-2">📊</span>
                            Información del Cambio
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Fecha del Cambio</label>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ date('d/m/Y', strtotime($cambio['Fecha_Cambio'])) }}
                                        <span class="text-sm text-gray-500">
                                            ({{ \Carbon\Carbon::parse($cambio['Fecha_Cambio'])->diffForHumans() }})
                                        </span>
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Etapa Alcanzada</label>
                                    <span class="inline-flex px-3 py-1 text-lg font-semibold rounded-full
                                        @if(strtolower($cambio['Etapa_Cambio']) === 'becerro' || strtolower($cambio['Etapa_Cambio']) === 'becerra') bg-yellow-100 text-yellow-800
                                        @elseif(strtolower($cambio['Etapa_Cambio']) === 'juvenil') bg-blue-100 text-blue-800
                                        @elseif(strtolower($cambio['Etapa_Cambio']) === 'adulto' || strtolower($cambio['Etapa_Cambio']) === 'adulta') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $cambio['Etapa_Cambio'] }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Animal ID</label>
                                    <p class="text-lg text-gray-900">
                                        <span class="bg-ganaderasoft-celeste text-white px-3 py-1 rounded-full text-sm">
                                            🐄 Animal #{{ $cambio['cambios_etapa_anid'] }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">ID de Etapa</label>
                                    <p class="text-lg text-gray-900">Etapa #{{ $cambio['cambios_etapa_etid'] }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Registro Creado</label>
                                    <p class="text-lg text-gray-900">
                                        {{ date('d/m/Y H:i', strtotime($cambio['created_at'])) }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Última Actualización</label>
                                    <p class="text-lg text-gray-900">
                                        {{ date('d/m/Y H:i', strtotime($cambio['updated_at'])) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medidas Físicas -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-ganaderasoft-verde text-white px-6 py-4">
                        <h2 class="text-lg font-semibold">📏 Medidas Físicas</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="text-center p-6 bg-blue-50 rounded-lg">
                                <div class="text-3xl mb-2">⚖️</div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Peso</h3>
                                <p class="text-2xl font-bold text-blue-600">
                                    @if($cambio['Peso'])
                                        {{ number_format($cambio['Peso'], 1) }} kg
                                    @else
                                        <span class="text-gray-400 text-lg">No registrado</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="text-center p-6 bg-green-50 rounded-lg">
                                <div class="text-3xl mb-2">📐</div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Altura</h3>
                                <p class="text-2xl font-bold text-green-600">
                                    @if($cambio['Altura'])
                                        {{ number_format($cambio['Altura'], 1) }} cm
                                    @else
                                        <span class="text-gray-400 text-lg">No registrado</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        @if(!$cambio['Peso'] && !$cambio['Altura'])
                            <div class="text-center py-8">
                                <div class="text-4xl mb-2">📊</div>
                                <p class="text-gray-500">No se registraron medidas físicas en este cambio</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Comentarios -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-ganaderasoft-azul text-white px-6 py-4">
                        <h2 class="text-lg font-semibold">💬 Comentarios y Observaciones</h2>
                    </div>
                    <div class="p-6">
                        @if($cambio['Comentario'])
                            <div class="bg-gray-50 border-l-4 border-ganaderasoft-celeste p-4 rounded">
                                <p class="text-gray-700 leading-relaxed">{{ $cambio['Comentario'] }}</p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-2">💭</div>
                                <p class="text-gray-500">No se registraron comentarios para este cambio</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="lg:col-span-1">
                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-700">⚡ Acciones Rápidas</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('cambios-animal.create') }}" 
                           class="w-full bg-ganaderasoft-verde text-white px-4 py-2 rounded-md hover:bg-ganaderasoft-verde/90 transition-colors flex items-center justify-center">
                            ➕ Nuevo Cambio
                        </a>
                        
                        <a href="{{ route('cambios-animal.index') }}" 
                           class="w-full bg-ganaderasoft-celeste text-white px-4 py-2 rounded-md hover:bg-ganaderasoft-celeste/90 transition-colors flex items-center justify-center">
                            📋 Ver Lista Completa
                        </a>
                        
                        <a href="{{ route('cambios-animal.index', ['animal_id' => $cambio['cambios_etapa_anid']]) }}" 
                           class="w-full bg-ganaderasoft-azul text-white px-4 py-2 rounded-md hover:bg-ganaderasoft-azul/90 transition-colors flex items-center justify-center">
                            🐄 Cambios del Animal
                        </a>
                    </div>
                </div>

                <!-- Resumen del Cambio -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-700">📈 Resumen</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">ID Cambio</span>
                            <span class="font-semibold text-ganaderasoft-azul">{{ $cambio['id_Cambio'] }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Animal</span>
                            <span class="font-semibold text-ganaderasoft-azul">#{{ $cambio['cambios_etapa_anid'] }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Etapa ID</span>
                            <span class="font-semibold text-ganaderasoft-azul">#{{ $cambio['cambios_etapa_etid'] }}</span>
                        </div>
                        
                        @if($cambio['Peso'])
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Peso registrado</span>
                                <span class="font-semibold text-ganaderasoft-verde">Sí</span>
                            </div>
                        @endif
                        
                        @if($cambio['Altura'])
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Altura registrada</span>
                                <span class="font-semibold text-ganaderasoft-verde">Sí</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Con comentarios</span>
                            <span class="font-semibold {{ $cambio['Comentario'] ? 'text-ganaderasoft-verde' : 'text-gray-400' }}">
                                {{ $cambio['Comentario'] ? 'Sí' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Información del Sistema -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-700">🔧 Información del Sistema</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Fecha de Registro</label>
                            <p class="text-sm text-gray-900">
                                {{ date('d/m/Y H:i:s', strtotime($cambio['created_at'])) }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Última Modificación</label>
                            <p class="text-sm text-gray-900">
                                {{ date('d/m/Y H:i:s', strtotime($cambio['updated_at'])) }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Tiempo Transcurrido</label>
                            <p class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($cambio['Fecha_Cambio'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection