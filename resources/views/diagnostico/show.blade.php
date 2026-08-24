@extends('layouts.authenticated')

@section('title', 'Detalle de diagnóstico')

@section('content')
@php
    $id = $diagnostico['id'] ?? $diagnostico['diagnostico_id'] ?? null;
    $animalId = $diagnostico['animal_id'] ?? $diagnostico['fk_etapa_animal_anid'] ?? data_get($diagnostico, 'etapa_animal.animal_id');
    $animalRefId = data_get($diagnostico, 'animal.id') ?? data_get($diagnostico, 'animal.id_Animal') ?? data_get($diagnostico, 'etapa_animal.animal.id') ?? $animalId;
    $animalNombre = data_get($diagnostico, 'animal.Nombre') ?? data_get($diagnostico, 'animal.nombre') ?? data_get($diagnostico, 'etapa_animal.animal.nombre') ?? data_get($diagnostico, 'etapa_animal.animal.Nombre') ?? ('Animal #'.$animalId);
    
    $etapaId = $diagnostico['etapa_id'] ?? $diagnostico['fk_etapa_animal_etid'] ?? data_get($diagnostico, 'etapa_animal.etapa_id');
    $etapaNombre = data_get($diagnostico, 'etapa_animal.etapa.nombre') ?? data_get($diagnostico, 'etapa_animal.etapa.etapa_nombre') ?? data_get($diagnostico, 'etapa.nombre') ?? data_get($diagnostico, 'etapa.etapa_nombre') ?? ($etapaId ? 'Etapa #'.$etapaId : 'Sin etapa');
    
    $tipo = $diagnostico['tipo'] ?? $diagnostico['diagnostico_tipo'] ?? 'General';
    $fecha = $diagnostico['fecha'] ?? $diagnostico['diagnostico_fecha'] ?? null;
    $descripcion = $diagnostico['descripcion'] ?? $diagnostico['diagnostico_descripcion'] ?? 'Sin observaciones registradas.';
    
    $sexoVal = data_get($diagnostico, 'animal.sexo') ?? data_get($diagnostico, 'animal.Sexo') ?? data_get($diagnostico, 'etapa_animal.animal.sexo') ?? data_get($diagnostico, 'etapa_animal.animal.Sexo') ?? 'H';
    $isMacho = in_array(strtoupper((string)$sexoVal), ['M', 'MACHO', 'MASCULINO']);
    
    $fincaNombre = data_get($diagnostico, 'animal.rebano.finca.Nombre') ?? data_get($diagnostico, 'animal.rebano.finca.nombre') ?? '';
    $rebanoNombre = data_get($diagnostico, 'animal.rebano.Nombre') ?? data_get($diagnostico, 'animal.rebano.nombre') ?? '';
    
    $tratamientos = $diagnostico['tratamientos'] ?? [];
@endphp

<div class="space-y-8">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl {{ $isMacho ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-2xl shadow-sm">
                {{ $isMacho ? '🐂' : '🐄' }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Diagnóstico clínico #{{ $id }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                    Animal evaluado: <span class="font-semibold text-gray-800">{{ $animalNombre }} (ID: #{{ $animalRefId }})</span>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('diagnostico.edit', $id) }}" 
               class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar diagnóstico
            </a>
            <a href="{{ route('diagnostico.index') }}" 
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
            <!-- Información del Animal y Etapa -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>📋</span> Información del animal y ubicación
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre del animal</p>
                        <p class="text-lg font-bold text-gray-900">{{ $animalNombre }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Identificador único</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">ID #{{ $animalRefId }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Sexo del animal</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $isMacho ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-pink-50 text-pink-700 border border-pink-200' }}">
                            {{ $isMacho ? '🐂 Macho' : '🐄 Hembra' }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Etapa clínica / productiva</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                            🏷️ {{ $etapaNombre }}
                        </span>
                    </div>

                    @if($fincaNombre)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Finca</p>
                        <p class="text-base font-semibold text-gray-900">{{ $fincaNombre }}</p>
                    </div>
                    @endif

                    @if($rebanoNombre)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rebaño</p>
                        <p class="text-base font-semibold text-gray-900">{{ $rebanoNombre }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detalle Clínico del Diagnóstico -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🩺</span> Evaluación y diagnóstico clínico
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo de diagnóstico</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            {{ $tipo }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de evaluación</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A' }}
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Descripción y observaciones clínicas</p>
                        <div class="p-4 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-800 leading-relaxed">{!! nl2br(e(trim($descripcion ?: 'Sin descripción u observaciones registradas.'))) !!}</div>
                    </div>
                </div>
            </div>

            <!-- Tratamientos Asociados -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold border border-purple-100">
                            💊
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-ganaderasoft-negro">
                                Tratamientos médicos asociados
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Planes terapéuticos, medicamentos y seguimiento clínico</p>
                        </div>
                    </div>
                    @if(count($tratamientos) > 0)
                        <a href="{{ route('tratamiento.create') }}" 
                           class="px-4 py-2.5 bg-ganaderasoft-verde-oscuro text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-xs inline-flex items-center gap-2 self-start sm:self-auto">
                            <span>+</span> Registrar tratamiento
                        </a>
                    @endif
                </div>

                @if(count($tratamientos) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan terapéutico</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha inicio</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha fin</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 text-sm">
                                @foreach($tratamientos as $tratamiento)
                                    @php
                                        $tId = $tratamiento['id'] ?? $tratamiento['tratamiento_id'] ?? null;
                                        $tPlan = $tratamiento['plan'] ?? $tratamiento['tratamiento_plan'] ?? 'Sin plan especificado';
                                        $tFechaIni = $tratamiento['fecha_ini'] ?? $tratamiento['tratamiento_fecha_ini'] ?? null;
                                        $tFechaFin = $tratamiento['fecha_fin'] ?? $tratamiento['tratamiento_fecha_fin'] ?? null;
                                    @endphp
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-gray-900">#{{ $tId }}</td>
                                        <td class="px-6 py-4 text-gray-800 font-medium max-w-xs truncate">{{ $tPlan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                            {{ $tFechaIni ? date('d/m/Y', strtotime($tFechaIni)) : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                            @if($tFechaFin)
                                                {{ date('d/m/Y', strtotime($tFechaFin)) }}
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    En curso
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <a href="{{ route('tratamiento.show', $tId) }}"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                               title="Ver tratamiento">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-3xl">
                            💊
                        </div>
                        <h4 class="text-base font-bold text-ganaderasoft-negro mb-1">Sin tratamientos vinculados</h4>
                        <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">Actualmente no se han formulado planes terapéuticos asociados a este diagnóstico.</p>
                        <a href="{{ route('tratamiento.create') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all shadow-sm text-sm">
                            <span>+</span> Registrar tratamiento
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas de Estado y Sistema (1 Tercio) -->
        <div class="space-y-6">
            <!-- Ficha de Estado Clínico Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-ganaderasoft-verde-oscuro text-white px-6 py-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <span>📋</span> Ficha del diagnóstico
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado de evaluación</label>
                        <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Evaluado / Registrado
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo diagnosticado</label>
                        <p class="text-sm font-bold text-gray-900">{{ $tipo }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Identificador de diagnóstico</label>
                        <p class="text-sm font-bold text-gray-900 font-mono">
                            ID #{{ $id }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Identificador del animal</label>
                        <p class="text-sm font-bold text-gray-900 font-mono">
                            Animal ID #{{ $animalRefId }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de registro</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($diagnostico['created_at']) ? date('d/m/Y H:i', strtotime($diagnostico['created_at'])) : ($fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Última actualización</label>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ isset($diagnostico['updated_at']) ? date('d/m/Y H:i', strtotime($diagnostico['updated_at'])) : 'Desconocida' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

