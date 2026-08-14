@extends('layouts.authenticated')

@section('title', 'Gestión de Rebaños')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de Rebaños</h1>
            <p class="text-gray-500 text-sm mt-1">Administración de agrupaciones y lotes de ganado (API V2)</p>
        </div>
        <a href="{{ route('rebanos.create') }}"
           class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul text-white font-semibold rounded-xl hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Rebaño
        </a>
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
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Filtrar por Finca</label>
                <select id="filtroFinca"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        @php
                            $fId = $finca['id'] ?? null;
                            $fNombre = $finca['nombre'] ?? ('Finca #'.$fId);
                        @endphp
                        <option value="{{ $fId }}" {{ (string)$fincaId === (string)$fId ? 'selected' : '' }}>
                            {{ $fNombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nombre del Rebaño</label>
                <input type="text" id="filtroNombre" value="{{ $nombre }}" placeholder="Buscar por nombre..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <button onclick="limpiarFiltros()"
                        class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Summary KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Rebaños</p>
                <p id="statTotal" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $estadisticas['total'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                🐄
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Animales Asociados</p>
                <p id="statAnimales" class="text-3xl font-extrabold text-ganaderasoft-verde-oscuro">{{ $estadisticas['totalAnimales'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-verde/20 flex items-center justify-center text-2xl">
                📋
            </div>
        </div>
    </div>

    <!-- Grid / Cards List -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        @if(count($rebanos) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gridRebanos">
                @foreach($rebanos as $rebano)
                    @php
                        $rebanoId = $rebano['id'] ?? null;
                        $rebanoNombre = $rebano['nombre'] ?? 'Rebaño';
                        $fincaObj = $rebano['finca'] ?? null;
                        $fincaNombre = $fincaObj['nombre'] ?? ('Finca #'.($rebano['finca_id'] ?? 'N/A'));
                        $fincaTipo = $fincaObj['explotacion_tipo'] ?? '-';
                        $fincaIdAttr = $rebano['finca_id'] ?? ($fincaObj['id'] ?? '');
                        $animalesCount = count($rebano['animales'] ?? []);
                    @endphp
                    <div class="group border border-gray-200 hover:border-ganaderasoft-celeste rounded-2xl p-6 hover:shadow-lg transition-all duration-200 flex flex-col justify-between fila-rebano"
                         data-finca="{{ $fincaIdAttr }}"
                         data-nombre="{{ strtolower($rebanoNombre) }}">
                        <div>
                            <!-- Header with icon -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1 pr-2">
                                    <h3 class="text-xl font-bold text-ganaderasoft-negro group-hover:text-ganaderasoft-azul transition-colors">
                                        {{ $rebanoNombre }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                                        <span class="mr-1">🏡</span> {{ $fincaNombre }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
                                    🐄
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-2 py-3 border-t border-b border-gray-100 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500">ID Rebaño:</span>
                                    <span class="font-bold text-gray-900">#{{ $rebanoId }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500">Tipo Explotación:</span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-ganaderasoft-celeste/10 text-ganaderasoft-azul font-semibold">
                                        {{ $fincaTipo }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500">Total Animales:</span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-green-50 text-green-700 font-bold badge-animales">
                                        {{ $animalesCount }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2 mt-6 pt-2">
                            <a href="{{ route('animales.index', ['rebano_id' => $rebanoId]) }}"
                               class="flex-1 px-4 py-2.5 bg-ganaderasoft-celeste/15 hover:bg-ganaderasoft-celeste text-ganaderasoft-azul hover:text-white rounded-xl text-xs font-bold text-center transition-all duration-200">
                                Ver Animales
                            </a>
                            <a href="{{ route('rebanos.edit', $rebanoId) }}"
                               class="px-3 py-2.5 border border-gray-200 hover:border-gray-300 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition-colors">
                                ✏️
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                    🐄
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay rebaños registrados</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza agregando un nuevo rebaño a la finca</p>
                <a href="{{ route('rebanos.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul text-white font-semibold rounded-xl hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste transition-all duration-200 shadow-md">
                    + Nuevo Rebaño
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('filtroFinca').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroNombre').addEventListener('input', aplicarFiltros);

    function aplicarFiltros() {
        const finca  = document.getElementById('filtroFinca').value;
        const nombre = document.getElementById('filtroNombre').value.toLowerCase();

        let total = 0, totalAnimales = 0;

        document.querySelectorAll('.fila-rebano').forEach(function (row) {
            const ok = (!finca  || row.dataset.finca === finca)
                    && (!nombre || row.dataset.nombre.includes(nombre));
            row.style.display = ok ? '' : 'none';
            if (ok) {
                total++;
                const badge = row.querySelector('.badge-animales');
                if (badge) totalAnimales += parseInt(badge.textContent.trim()) || 0;
            }
        });

        document.getElementById('statTotal').textContent    = total;
        document.getElementById('statAnimales').textContent = totalAnimales;
    }

    function limpiarFiltros() {
        document.getElementById('filtroFinca').value  = '';
        document.getElementById('filtroNombre').value = '';
        document.querySelectorAll('.fila-rebano').forEach(r => r.style.display = '');
        aplicarFiltros();
    }
</script>
@endsection
