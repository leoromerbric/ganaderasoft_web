@extends('layouts.authenticated')

@section('title', 'Detalle de finca')

@section('content')
@php
    $fincaId = $finca['id'] ?? $finca['id_Finca'] ?? null;
    $nombreFinca = $finca['nombre'] ?? $finca['Nombre'] ?? 'Sin Nombre';
    $tipoExp = $finca['explotacion_tipo'] ?? $finca['Explotacion_Tipo'] ?? 'General';
    $terreno = $finca['terreno'] ?? [];
    
    $superficie = (float) ($terreno['superficie'] ?? $terreno['Superficie'] ?? 0);
    $relieve = $terreno['relieve'] ?? $terreno['Relieve'] ?? null;
    $textura = $terreno['suelo_textura'] ?? $terreno['Suelo_Textura'] ?? null;
    $ph = $terreno['ph_suelo'] ?? $terreno['ph_Suelo'] ?? null;
    $precipitacion = $terreno['precipitacion'] ?? $terreno['Precipitacion'] ?? null;
    $viento = $terreno['velocidad_viento'] ?? $terreno['Velocidad_Viento'] ?? null;
    
    $tempAnual = $terreno['temp_anual'] ?? $terreno['Temp_Anual'] ?? null;
    $tempMin = $terreno['temp_min'] ?? $terreno['Temp_Min'] ?? null;
    $tempMax = $terreno['temp_max'] ?? $terreno['Temp_Max'] ?? null;
    $radiacion = $terreno['radiacion'] ?? $terreno['Radiacion'] ?? null;
    
    $fuenteAgua = $terreno['fuente_agua'] ?? $terreno['Fuente_Agua'] ?? null;
    $riego = $terreno['riego_metodo'] ?? $terreno['Riego_Metodo'] ?? null;

    // Propietario V2
    $propObj = $finca['propietario'] ?? null;
    $persona = $propObj['persona'] ?? null;
    $nombreProp = $persona ? trim(($persona['nombre'] ?? '') . ' ' . ($persona['apellido'] ?? '')) : null;
    $telefonoProp = $persona['telefono'] ?? null;
    $correoProp = $persona['correo'] ?? null;

    $createdAt = $finca['created_at'] ?? null;
    $updatedAt = $finca['updated_at'] ?? null;
    $inicial = strtoupper(substr($nombreFinca ?: 'F', 0, 1));
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-700 border border-teal-100 flex items-center justify-center font-bold text-2xl shadow-xs shrink-0">
                {{ $inicial ?: '🏡' }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    {{ $nombreFinca }}
                </h1>
                <p class="text-gray-500 text-sm mt-0.5 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                        💼 {{ $tipoExp }}
                    </span>
                    @if($superficie > 0)
                        <span>•</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            📐 {{ number_format($superficie, 1, ',', '.') }} ha
                        </span>
                    @endif
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($fincaId)
                <a href="{{ route('fincas.edit', $fincaId) }}" 
                   class="px-7 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar finca
                </a>
            @endif
            <a href="{{ route('fincas.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center justify-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center space-x-2">
            <span class="text-lg">✅</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Columna Izquierda: Detalles de la Finca (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card 1: Información General y Propietario -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🏡</span> Información general y modelo productivo
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Nombre de la finca</span>
                        <p class="text-base font-bold text-gray-900">{{ $nombreFinca }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Tipo de explotación</span>
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-100 mt-0.5">
                            💼 {{ $tipoExp }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Propietario / Responsable</span>
                        <p class="text-base font-bold text-gray-900">{{ $nombreProp ?: 'No registrado' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Teléfono de contacto</span>
                        <p class="text-base font-bold text-gray-900">{{ $telefonoProp && $telefonoProp !== '-' ? $telefonoProp : 'No registrado' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Correo electrónico</span>
                        <p class="text-base font-bold text-gray-900">{{ $correoProp ?: 'No registrado' }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Características del Terreno y Suelo -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🌱</span> Características del terreno y suelo
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Superficie total</span>
                        <p class="text-xl font-black text-gray-900">
                            {{ $superficie > 0 ? number_format($superficie, 1, ',', '.') . ' ha' : 'No especificada' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Tipo de relieve</span>
                        <p class="text-base font-bold text-gray-900">{{ $relieve ?: 'No especificado' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Textura del suelo</span>
                        <p class="text-base font-bold text-gray-900">{{ $textura ?: 'No especificada' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">pH del suelo</span>
                        <p class="text-base font-bold text-gray-900">{{ $ph ?: 'No especificado' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Precipitación anual</span>
                        <p class="text-base font-bold text-gray-900">{{ $precipitacion ? $precipitacion . ' mm' : 'No registrada' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Velocidad del viento</span>
                        <p class="text-base font-bold text-gray-900">{{ $viento ? $viento . ' km/h' : 'No registrada' }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 3: Recursos Hídricos y Clima -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>💧</span> Recursos hídricos y clima
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Fuente de agua principal</span>
                        <p class="text-base font-bold text-gray-900">{{ $fuenteAgua ?: 'No especificada' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Método de riego</span>
                        <p class="text-base font-bold text-gray-900">{{ $riego ?: 'No especificado' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Temperatura promedio</span>
                        <p class="text-base font-bold text-gray-900">{{ $tempAnual ? $tempAnual . ' °C' : 'No registrada' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Temperatura mínima</span>
                        <p class="text-base font-bold text-gray-900">{{ $tempMin ? $tempMin . ' °C' : 'No registrada' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Temperatura máxima</span>
                        <p class="text-base font-bold text-gray-900">{{ $tempMax ? $tempMax . ' °C' : 'No registrada' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Radiación solar</span>
                        <p class="text-base font-bold text-gray-900">{{ $radiacion ?: 'No registrada' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Módulos Asociados y Registro del Sistema (1 Tercio) -->
        <div class="space-y-6">
            
            <!-- Card 1: Accesos Rápidos de la Finca -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>📊</span> Gestión y módulos asociados
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Rebaños -->
                    <a href="{{ route('rebanos.index', ['finca_id' => $fincaId]) }}"
                       class="w-full py-3.5 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-semibold rounded-xl text-sm flex items-center justify-between px-4 transition-all shadow-2xs">
                        <span class="flex items-center gap-2">
                            <span>🐄</span> Ver rebaños de la finca
                        </span>
                        <span>→</span>
                    </a>

                    <!-- Personal -->
                    <a href="{{ route('personal-finca.index', ['finca_id' => $fincaId]) }}"
                       class="w-full py-3.5 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-semibold rounded-xl text-sm flex items-center justify-between px-4 transition-all shadow-2xs">
                        <span class="flex items-center gap-2">
                            <span>👥</span> Ver personal asignado
                        </span>
                        <span>→</span>
                    </a>

                    <!-- Animales -->
                    <a href="{{ route('animales.index', ['finca_id' => $fincaId]) }}"
                       class="w-full py-3.5 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-semibold rounded-xl text-sm flex items-center justify-between px-4 transition-all shadow-2xs">
                        <span class="flex items-center gap-2">
                            <span>🏷️</span> Ver inventario de animales
                        </span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Card 2: Registro del Sistema -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Identificador único</span>
                        <p class="text-sm font-bold text-gray-900 font-mono">
                            ID #{{ $fincaId ?? 'N/A' }}
                        </p>
                    </div>

                    @if($createdAt)
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Fecha de creación</span>
                            <p class="text-sm font-bold text-gray-900">
                                {{ date('d/m/Y H:i', strtotime($createdAt)) }}
                            </p>
                        </div>
                    @endif

                    @if($updatedAt)
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Última actualización</span>
                            <p class="text-sm font-bold text-gray-900">
                                {{ date('d/m/Y H:i', strtotime($updatedAt)) }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
