@extends('layouts.authenticated')

@section('title', 'Detalle de semen de toro')

@section('content')
@php
    $id = $semen['id'] ?? null;
    $toroId = $semen['animal_id'] ?? data_get($semen, 'toro.id') ?? '';
    $toroNombre = data_get($semen, 'toro.Nombre') ?? data_get($semen, 'toro.nombre') ?? ('Toro #'.$toroId);
    $toroCodigo = data_get($semen, 'toro.codigo_animal') ?? data_get($semen, 'toro.Codigo') ?? '';
    $toroRaza = data_get($semen, 'toro.composicion_raza.nombre') ?? data_get($semen, 'toro.composicionRaza.nombre') ?? data_get($semen, 'toro.raza.Nombre') ?? data_get($semen, 'toro.raza.nombre') ?? '';

    $fincaNombre = data_get($semen, 'toro.rebano.finca.Nombre') ?? data_get($semen, 'toro.rebano.finca.nombre') ?? '';
    $rebanoNombre = data_get($semen, 'toro.rebano.Nombre') ?? data_get($semen, 'toro.rebano.nombre') ?? '';

    $estado = (bool)($semen['estado'] ?? false);
    $fecha = $semen['fecha'] ?? null;
    $servicios = $semen['servicios'] ?? [];
    $serviciosCount = count($servicios);

    $createdAt = $semen['created_at'] ?? null;
    $updatedAt = $semen['updated_at'] ?? null;
@endphp

<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                🧬
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Semen de toro #{{ $id }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">Detalle de la pajuela, toro donante y servicios vinculados</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($id)
                <a href="{{ route('semen-toro.edit', $id) }}" 
                   class="px-6 py-3 bg-ganaderasoft-azul text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar semen
                </a>
            @endif
            <a href="{{ route('semen-toro.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver listado
            </a>
        </div>
    </div>

    <!-- Main Content Layout (2 Columnas: 2/3 Principal, 1/3 Lateral) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Columna Izquierda: Información Principal (2 Tercios) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card 1: Toro Donante -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                    <span>🐂</span> Toro donante
                </h3>

                <div class="p-5 bg-gray-50/90 border border-gray-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-700 border border-blue-100 font-bold flex items-center justify-center text-3xl shadow-xs shrink-0">
                            🐂
                        </div>
                        <div>
                            <p class="text-xl font-bold text-gray-900">{{ $toroNombre }}</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                @if($toroCodigo)
                                    <span class="text-xs font-mono text-gray-600 bg-white px-2.5 py-0.5 rounded-md border border-gray-200 font-semibold">
                                        #{{ $toroCodigo }}
                                    </span>
                                @endif
                                @if($toroRaza)
                                    <span class="text-xs font-bold text-blue-800 bg-blue-50 px-2.5 py-0.5 rounded-md border border-blue-200">
                                        {{ $toroRaza }}
                                    </span>
                                @endif
                                @if($fincaNombre)
                                    <span class="text-xs font-semibold text-gray-700 bg-white px-2.5 py-0.5 rounded-md border border-gray-200">
                                        🏡 {{ $fincaNombre }}
                                    </span>
                                @endif
                                @if($rebanoNombre)
                                    <span class="text-xs font-semibold text-gray-700 bg-white px-2.5 py-0.5 rounded-md border border-gray-200">
                                        {{ $rebanoNombre }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($toroId)
                        <div>
                            <a href="{{ route('animales.show', $toroId) }}"
                               class="px-5 py-2.5 bg-white hover:bg-gray-100 border border-gray-300 text-gray-800 font-semibold rounded-xl text-sm inline-flex items-center gap-2 transition-all shadow-xs hover:shadow-sm">
                                <svg class="w-4 h-4 text-ganaderasoft-azul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver ficha del toro
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 2: Datos del Lote de Semen -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>🧬</span> Información del lote de pajuelas
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Disponibilidad -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Disponibilidad en banco</p>
                        @if($estado)
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Disponible / Activo en banco
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-bold bg-gray-50 text-gray-600 border border-gray-200">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                Agotado / Inactivo
                            </span>
                        @endif
                    </div>

                    <!-- Fecha de Colecta -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Fecha de colecta / ingreso</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">
                            {{ $fecha ? date('d/m/Y', strtotime($fecha)) : 'No especificada' }}
                        </p>
                    </div>

                    <!-- Servicios Realizados -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Servicios de IA aplicados</p>
                        <p class="text-sm font-bold text-purple-700">
                            {{ $serviciosCount }} {{ $serviciosCount === 1 ? 'servicio registrado' : 'servicios registrados' }}
                        </p>
                    </div>

                    <!-- Código de Registro / Pajuela -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Identificador de pajuela</p>
                        <p class="text-sm font-bold font-mono text-cyan-800">
                            Pajuela #{{ $id }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 3: Historial de Servicios Reproductivos -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>📋</span> Inseminaciones vinculadas a esta pajuela
                    </h3>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg">
                        Total: {{ $serviciosCount }}
                    </span>
                </div>

                @if($serviciosCount > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 text-sm">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Hembra inseminada</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Fecha de servicio</th>
                                    <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($servicios as $serv)
                                    @php
                                        $srvId = $serv['id'] ?? $serv['id_Servicio_Animal'] ?? null;
                                        $hNom = data_get($serv, 'animal.nombre') ?? data_get($serv, 'animal.Nombre') ?? ('Hembra #'.data_get($serv, 'animal_id'));
                                        $hCod = data_get($serv, 'animal.codigo_animal') ?? data_get($serv, 'animal.Codigo');
                                        $srvFecha = $serv['fecha'] ?? null;
                                    @endphp
                                    <tr class="hover:bg-gray-50/60">
                                        <td class="px-4 py-3 font-mono font-bold text-gray-700">#{{ $srvId }}</td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold text-gray-900">{{ $hNom }}</span>
                                            @if($hCod)
                                                <span class="text-xs text-gray-500 font-mono ml-1">(#{{ $hCod }})</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $srvFecha ? date('d/m/Y', strtotime($srvFecha)) : 'S/F' }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            @if($srvId)
                                                <a href="{{ route('servicio-animal.show', $srvId) }}" 
                                                   class="text-xs font-semibold text-ganaderasoft-azul hover:underline">
                                                    Ver servicio ➔
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 bg-gray-50 rounded-xl text-center space-y-1">
                        <p class="text-sm font-semibold text-gray-700">No se registran servicios de inseminación con este lote aún</p>
                        <p class="text-xs text-gray-400">Cuando realices una inseminación artificial seleccionando este toro, aparecerá registrada aquí.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas de Resumen y Metadatos (1 Tercio) -->
        <div class="space-y-6">
            <!-- Banco Genético Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>🧪</span> Banco genético
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="p-4 bg-teal-50 rounded-2xl border border-teal-100 space-y-1">
                        <span class="text-xs font-semibold text-teal-700 uppercase tracking-wider block">Estado criogénico</span>
                        <p class="text-base font-bold text-teal-950">
                            {{ $estado ? 'Disponible para IA' : 'Lote agotado / Inactivo' }}
                        </p>
                        <p class="text-[11px] text-teal-700 leading-tight">
                            Apto para protocolos de sincronización a celo detectado y tiempo fijo (IATF).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Registro del Sistema Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-100 text-gray-800 px-6 py-4">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <span>⚙️</span> Registro del sistema
                    </h3>
                </div>
                <div class="p-6 space-y-3 text-xs text-gray-600">
                    <div class="flex justify-between items-center py-1 border-b border-gray-50">
                        <span class="text-gray-500">ID de registro:</span>
                        <span class="font-bold text-gray-900 font-mono">#{{ $id }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-gray-50">
                        <span class="text-gray-500">Fecha de registro:</span>
                        <span class="font-bold text-gray-900">
                            {{ $createdAt ? date('d/m/Y H:i', strtotime($createdAt)) : 'No disponible' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-500">Última modificación:</span>
                        <span class="font-bold text-gray-900">
                            {{ $updatedAt ? date('d/m/Y H:i', strtotime($updatedAt)) : 'No disponible' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
