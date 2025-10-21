@extends('layouts.authenticated')

@section('title', 'Detalle del Animal')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-ganaderasoft-negro">Detalle del Animal</h2>
                <p class="text-gray-600 mt-1">Información completa del animal</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('animales.edit', $animal['id_Animal']) }}" 
                   class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    Editar
                </a>
                <a href="{{ route('animales.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                    Volver
                </a>
            </div>
        </div>

        <!-- Animal Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info Card -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-6">Información General</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nombre</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $animal['Nombre'] }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Código</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $animal['codigo_animal'] }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Sexo</p>
                        <p class="text-lg font-semibold">
                            <span class="px-3 py-1 rounded {{ $animal['Sexo'] === 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $animal['Sexo'] === 'M' ? 'Macho' : 'Hembra' }}
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Fecha de Nacimiento</p>
                        <p class="text-lg font-semibold text-gray-900">{{ date('d/m/Y', strtotime($animal['fecha_nacimiento'])) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Procedencia</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $animal['Procedencia'] }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Estado</p>
                        <p class="text-lg font-semibold">
                            @if(isset($animal['archivado']) && $animal['archivado'])
                                <span class="px-3 py-1 rounded bg-gray-100 text-gray-800">Archivado</span>
                            @else
                                <span class="px-3 py-1 rounded bg-green-100 text-green-800">Activo</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Breed Info -->
                @if(isset($animal['composicion_raza']))
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-semibold text-ganaderasoft-negro mb-4">Información de Raza</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nombre</p>
                            <p class="font-semibold text-gray-900">{{ $animal['composicion_raza']['Nombre'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Siglas</p>
                            <p class="font-semibold text-gray-900">{{ $animal['composicion_raza']['Siglas'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Propósito</p>
                            <p class="font-semibold text-gray-900">{{ $animal['composicion_raza']['Proposito'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Origen</p>
                            <p class="font-semibold text-gray-900">{{ $animal['composicion_raza']['Origen'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Herd and Farm Info -->
                @if(isset($animal['rebano']))
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-semibold text-ganaderasoft-negro mb-4">Rebaño y Finca</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Rebaño</p>
                            <p class="font-semibold text-gray-900">{{ $animal['rebano']['Nombre'] }}</p>
                        </div>
                        @if(isset($animal['rebano']['finca']))
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Finca</p>
                            <p class="font-semibold text-gray-900">{{ $animal['rebano']['finca']['Nombre'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Status and Stage Card -->
            <div class="space-y-6">
                <!-- Current Health Status -->
                @if(isset($animal['estado_actual']) || (isset($animal['estados']) && count($animal['estados']) > 0))
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Estado de Salud Actual</h3>
                    @php
                        $estadoActual = $animal['estado_actual'] ?? (isset($animal['estados'][0]) ? $animal['estados'][0] : null);
                    @endphp
                    @if($estadoActual)
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Estado</p>
                                <p class="text-lg font-semibold text-ganaderasoft-verde">
                                    {{ $estadoActual['estado_salud']['estado_nombre'] ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Fecha Inicio</p>
                                <p class="font-semibold text-gray-900">
                                    {{ isset($estadoActual['esan_fecha_ini']) ? date('d/m/Y', strtotime($estadoActual['esan_fecha_ini'])) : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
                @endif

                <!-- Current Stage -->
                @if(isset($animal['etapa_actual']))
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Etapa Actual</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Etapa</p>
                            <p class="text-lg font-semibold text-ganaderasoft-celeste">
                                {{ $animal['etapa_actual']['etapa']['etapa_nombre'] ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Fecha Inicio</p>
                            <p class="font-semibold text-gray-900">
                                {{ isset($animal['etapa_actual']['etan_fecha_ini']) ? date('d/m/Y', strtotime($animal['etapa_actual']['etan_fecha_ini'])) : 'N/A' }}
                            </p>
                        </div>
                        @if(isset($animal['etapa_actual']['etapa']['etapa_edad_ini']) && isset($animal['etapa_actual']['etapa']['etapa_edad_fin']))
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Rango de Edad</p>
                            <p class="font-semibold text-gray-900">
                                {{ $animal['etapa_actual']['etapa']['etapa_edad_ini'] }} - {{ $animal['etapa_actual']['etapa']['etapa_edad_fin'] }} días
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Dates Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-ganaderasoft-negro mb-4">Fechas de Registro</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Creado</p>
                            <p class="font-semibold text-gray-900">
                                {{ isset($animal['created_at']) ? date('d/m/Y H:i', strtotime($animal['created_at'])) : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Última Actualización</p>
                            <p class="font-semibold text-gray-900">
                                {{ isset($animal['updated_at']) ? date('d/m/Y H:i', strtotime($animal['updated_at'])) : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
