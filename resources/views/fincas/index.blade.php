@extends('layouts.authenticated')

@section('title', 'Gestión de Fincas')

@section('content')
    <div class="space-y-8">
        <!-- Header section -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de Fincas</h1>
                <p class="text-gray-500 text-sm mt-1">Administración de fincas y unidades de producción ganadera</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('fincas.importar') }}"
                   class="px-5 py-3 border border-ganaderasoft-verde-oscuro text-ganaderasoft-verde-oscuro hover:bg-ganaderasoft-verde-oscuro hover:text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md inline-flex items-center justify-center font-medium">
                    📥 Importar CSV / TXT
                </a>
                <a href="{{ route('fincas.create') }}"
                   class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center font-medium">
                    + Nueva Finca
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div
                class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">✅</span>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div
                class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">⚠️</span>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Filters Bar -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar por
                        Nombre</label>
                    <input type="text" id="filtroNombre" value="{{ $nombre }}" placeholder="Ej: Finca San José..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipo de
                        Explotación</label>
                    <select id="filtroTipo"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los tipos</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo }}" {{ $tipoFiltro === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex space-x-2">
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
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total de Fincas</p>
                    <p id="statTotal" class="text-3xl font-extrabold text-ganaderasoft-azul">{{ count($fincas) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                    🏡
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Superficie Total Registrada
                    </p>
                    <p id="statSuperficie" class="text-3xl font-extrabold text-ganaderasoft-verde-oscuro">
                        @php
                            $totalSuperficie = array_sum(array_map(function ($f) {
                                return (float) ($f['terreno']['superficie'] ?? 0);
                            }, $fincas));
                        @endphp
                        {{ number_format($totalSuperficie, 1, ',', '.') }} ha
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-ganaderasoft-verde/20 flex items-center justify-center text-2xl">
                    📐
                </div>
            </div>
        </div>

        <!-- Fincas List Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            @if(count($fincas) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Finca</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Tipo Explotación</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Propietario / Persona</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Superficie</th>
                                <th
                                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaFincas">
                            @foreach($fincas as $finca)
                                @php
                                    $fincaId = $finca['id'] ?? null;
                                    $nombreFinca = $finca['nombre'] ?? 'Sin Nombre';
                                    $tipoExp = $finca['explotacion_tipo'] ?? '-';
                                    $superficie = (float) ($finca['terreno']['superficie'] ?? 0);

                                    // Formateo de propietario V2
                                    $propObj = $finca['propietario'] ?? null;
                                    $persona = $propObj['persona'] ?? null;
                                    $nombreProp = $persona ? trim(($persona['nombre'] ?? '') . ' ' . ($persona['apellido'] ?? '')) : '-';
                                    $telefonoProp = $persona['telefono'] ?? '-';
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors fila-finca"
                                    data-nombre="{{ strtolower($nombreFinca) }}" data-tipo="{{ $tipoExp }}"
                                    data-superficie="{{ $superficie }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-ganaderasoft-azul font-bold text-lg">
                                                🏡
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $nombreFinca }}</p>
                                                <p class="text-xs text-gray-400">ID: #{{ $fincaId }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-ganaderasoft-celeste/15 text-ganaderasoft-azul border border-ganaderasoft-celeste/30">
                                            {{ $tipoExp }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="font-medium text-gray-900">{{ $nombreProp ?: '-' }}</p>
                                        <p class="text-xs text-gray-500">📞 {{ $telefonoProp }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                        {{ $superficie > 0 ? number_format($superficie, 1, ',', '.') . ' ha' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <a href="{{ route('fincas.edit', $fincaId) }}"
                                            class="text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-semibold transition-colors inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div
                        class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                        🏡
                    </div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay fincas registradas</h3>
                    <p class="text-gray-500 text-sm mb-6">Comienza registrando la primera finca de tu propiedad</p>
                    <a href="{{ route('fincas.create') }}"
                        class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        + Nueva Finca
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('filtroNombre').addEventListener('input', aplicarFiltros);
        document.getElementById('filtroTipo').addEventListener('change', aplicarFiltros);

        function aplicarFiltros() {
            const nombre = document.getElementById('filtroNombre').value.toLowerCase();
            const tipo = document.getElementById('filtroTipo').value;

            let total = 0, superficie = 0;

            document.querySelectorAll('.fila-finca').forEach(function (row) {
                const ok = (!nombre || row.dataset.nombre.includes(nombre))
                    && (!tipo || row.dataset.tipo === tipo);
                row.style.display = ok ? '' : 'none';
                if (ok) {
                    total++;
                    superficie += parseFloat(row.dataset.superficie) || 0;
                }
            });

            document.getElementById('statTotal').textContent = total;
            document.getElementById('statSuperficie').textContent = superficie.toLocaleString('es', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' ha';
        }

        function limpiarFiltros() {
            document.getElementById('filtroNombre').value = '';
            document.getElementById('filtroTipo').value = '';
            document.querySelectorAll('.fila-finca').forEach(r => r.style.display = '');
            aplicarFiltros();
        }
    </script>
@endsection