@extends('layouts.authenticated')

@section('title', 'Gestión de animales')

@section('content')
<div class="space-y-8">
    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de animales</h1>
            <p class="text-gray-500 text-sm mt-1">Administración del inventario de ganado, genealogía y registro por rebaños</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('animales.importar', ['finca_id' => $idFinca]) }}"
               class="px-5 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-xs inline-flex items-center justify-center gap-2 text-sm">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Importar CSV / TXT
            </a>
            <a href="{{ route('animales.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
                + Nuevo animal
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
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar</label>
                <input type="text" id="filtroNombre" value="{{ $nombre }}" placeholder="Nombre o código..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                <select id="filtroFinca"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todas las fincas</option>
                    @foreach($fincas as $finca)
                        <option value="{{ $finca['id'] }}" {{ $idFinca == $finca['id'] ? 'selected' : '' }}>
                            {{ $finca['nombre'] ?? 'Finca #'.$finca['id'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Rebaño</label>
                <select id="filtroRebano"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los rebaños</option>
                    @foreach($rebanos as $rebano)
                        <option value="{{ $rebano['id'] }}"
                                data-finca="{{ $rebano['finca_id'] ?? '' }}"
                                {{ $idRebano == $rebano['id'] ? 'selected' : '' }}>
                            {{ $rebano['nombre'] ?? 'Rebaño #'.$rebano['id'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Sexo</label>
                <select id="filtroSexo"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los sexos</option>
                    <option value="M" {{ $sexo === 'M' ? 'selected' : '' }}>Macho (♂)</option>
                    <option value="H" {{ $sexo === 'H' ? 'selected' : '' }}>Hembra (♀)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado</label>
                <select id="filtroArchivado" onchange="cambiarFiltroArchivado(this.value)"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="activos" {{ ($archivado ?? 'activos') === 'activos' ? 'selected' : '' }}>Solo activos</option>
                    <option value="archivados" {{ ($archivado ?? '') === 'archivados' ? 'selected' : '' }}>Solo archivados</option>
                    <option value="todos" {{ ($archivado ?? '') === 'todos' ? 'selected' : '' }}>Todos los animales</option>
                </select>
            </div>
            <div>
                <a href="{{ route('animales.index') }}"
                   class="w-full px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center h-[42px]">
                    Limpiar filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Animales -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($animales) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Animal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sexo</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rebaño</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nacimiento / edad</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaAnimales">
                        @foreach($animales as $animal)
                            @php
                                $rebanoId   = $animal['rebano']['id'] ?? ($animal['rebano_id'] ?? '');
                                $fincaId    = $animal['rebano']['finca_id'] ?? ($mapaRebanoFinca[$rebanoId] ?? '');
                                $isMacho    = ($animal['sexo'] ?? '') === 'M';
                                $isArchivado = !empty($animal['archivado']);
                                $inicial    = strtoupper(substr($animal['nombre'] ?? 'A', 0, 1));
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors fila-animal {{ $isArchivado ? 'bg-gray-50/50' : '' }}"
                                data-rebano="{{ $rebanoId }}"
                                data-finca="{{ $fincaId }}"
                                data-sexo="{{ $animal['sexo'] ?? '' }}"
                                data-nombre="{{ strtolower(($animal['nombre'] ?? '').' '.($animal['codigo_animal'] ?? '')) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 shrink-0 rounded-xl {{ $isMacho ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-lg">
                                            {{ $isMacho ? '🐂' : '🐄' }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="font-bold text-gray-900 truncate">{{ $animal['nombre'] ?? 'Sin Nombre' }}</p>
                                            <p class="text-xs text-gray-400">ID: #{{ $animal['id'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-semibold text-gray-800">
                                    {{ $animal['codigo_animal'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $isMacho ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200' }}">
                                        {{ $isMacho ? 'Macho' : 'Hembra' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                                    {{ $animal['rebano']['nombre'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    <p class="font-medium text-gray-800">{{ isset($animal['fecha_nacimiento']) ? date('d/m/Y', strtotime($animal['fecha_nacimiento'])) : 'N/A' }}</p>
                                    @if(!empty($animal['edad_formateada']))
                                        <p class="text-xs text-gray-400">{{ $animal['edad_formateada'] }}</p>
                                    @elseif(!empty($animal['fecha_nacimiento']))
                                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($animal['fecha_nacimiento'])->diffForHumans(null, true) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isArchivado)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 border border-red-200">Archivado</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Activo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <div class="flex justify-center space-x-2">
                                        <!-- Botón de Ver Detalles -->
                                        <a href="{{ route('animales.show', $animal['id']) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-celeste/10 text-ganaderasoft-celeste hover:bg-ganaderasoft-celeste hover:text-white transition-colors"
                                           title="Ver detalle">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Botón de Árbol Genealógico -->
                                        <a href="{{ route('arbol-gen.show', $animal['id']) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors"
                                           title="Árbol genealógico">
                                            <span class="text-xs">🌳</span>
                                        </a>
                                        
                                        <!-- Botón de Editar -->
                                        <a href="{{ route('animales.edit', $animal['id']) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-ganaderasoft-azul/10 text-ganaderasoft-azul hover:bg-ganaderasoft-azul hover:text-white transition-colors"
                                           title="Editar animal">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Botón de Restaurar / Archivar con Modal -->
                                        @if($isArchivado)
                                            <form action="{{ route('animales.restore', $animal['id']) }}" method="POST" class="inline-block" id="form-restore-{{ $animal['id'] }}">
                                                @csrf
                                                <button type="button" onclick="openGenericConfirmModal({
                                                    formId: 'form-restore-{{ $animal['id'] }}',
                                                    intent: 'success',
                                                    title: 'Restaurar animal',
                                                    message: '¿Estás seguro de que deseas reactivar este animal en el inventario activo?',
                                                    confirmText: 'Sí, restaurar'
                                                })"
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-colors"
                                                   title="Restaurar animal">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay animales registrados</h3>
                <p class="text-gray-500 text-sm mb-6">Prueba cambiando los filtros o agrega un nuevo ejemplar al inventario.</p>
                <a href="{{ route('animales.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    + Nuevo animal
                </a>
            </div>
        @endif
    </div>
</div>

<x-ui.confirm-modal />

<script>
    const mapaRebanoFinca = @json($mapaRebanoFinca);

    // Cascada finca → rebano
    document.getElementById('filtroFinca').addEventListener('change', function () {
        const fincaId = this.value;
        const rebanoSel = document.getElementById('filtroRebano');
        Array.from(rebanoSel.options).forEach(opt => {
            if (!opt.value) { opt.style.display = ''; return; }
            opt.style.display = (!fincaId || opt.dataset.finca === fincaId) ? '' : 'none';
        });
        if (rebanoSel.value && rebanoSel.options[rebanoSel.selectedIndex].style.display === 'none') {
            rebanoSel.value = '';
        }
        aplicarFiltros();
    });

    document.getElementById('filtroRebano').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroSexo').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroNombre').addEventListener('input', aplicarFiltros);

    function aplicarFiltros() {
        const finca  = document.getElementById('filtroFinca').value;
        const rebano = document.getElementById('filtroRebano').value;
        const sexo   = document.getElementById('filtroSexo').value;
        const nombre = document.getElementById('filtroNombre').value.toLowerCase();

        document.querySelectorAll('.fila-animal').forEach(function (row) {
            const ok = (!finca  || row.dataset.finca  === finca)
                    && (!rebano || row.dataset.rebano === rebano)
                    && (!sexo   || row.dataset.sexo   === sexo)
                    && (!nombre || row.dataset.nombre.includes(nombre));
            row.style.display = ok ? '' : 'none';
        });
    }

    function cambiarFiltroArchivado(val) {
        const url = new URL(window.location.href);
        if (val && val !== 'activos') {
            url.searchParams.set('archivado', val);
        } else {
            url.searchParams.delete('archivado');
        }
        window.location.href = url.toString();
    }

    @if($idFinca || $idRebano || $sexo || $nombre)
    document.addEventListener('DOMContentLoaded', function () {
        @if($idFinca)
        document.getElementById('filtroFinca').dispatchEvent(new Event('change'));
        @else
        aplicarFiltros();
        @endif
    });
    @endif
</script>
@endsection