@extends('layouts.authenticated')

@section('title', 'Gestión de Fincas')

@section('content')
<div>
    <!-- Cabecera -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de Fincas</h2>
            <p class="text-gray-600 mt-1">Administra las fincas del sistema</p>
        </div>
        <a href="{{ route('fincas.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
            + Nueva Finca
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex flex-nowrap gap-4 items-end">
            <div class="flex-1 min-w-0">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                <input type="text" id="filtroNombre" value="{{ $nombre }}" placeholder="Buscar por nombre..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
            </div>
            <div class="flex-1 min-w-0">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Explotación</label>
                <select id="filtroTipo"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                    <option value="">Todos los tipos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo }}" {{ $tipoFiltro === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-none">
                <button onclick="limpiarFiltros()"
                        class="px-6 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    Limpiar
                </button>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div id="statTotal" class="text-2xl font-bold text-ganaderasoft-azul">{{ count($fincas) }}</div>
                <div class="text-sm text-gray-600">Total Fincas</div>
            </div>
            <div class="text-center">
                <div id="statSuperficie" class="text-2xl font-bold text-ganaderasoft-verde">
                    {{ number_format(array_sum(array_map(fn($f) => (float)($f['terreno']['Superficie'] ?? 0), $fincas)), 1) }}
                </div>
                <div class="text-sm text-gray-600">Superficie Total (ha)</div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if(count($fincas) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propietario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Superficie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tablaFincas">
                        @foreach($fincas as $finca)
                            <tr class="hover:bg-gray-50 transition-colors fila-finca"
                                data-nombre="{{ strtolower($finca['Nombre'] ?? '') }}"
                                data-tipo="{{ $finca['Explotacion_Tipo'] ?? '' }}"
                                data-superficie="{{ (float)($finca['terreno']['Superficie'] ?? 0) }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $finca['Nombre'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $finca['Explotacion_Tipo'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ trim(($finca['propietario']['Nombre'] ?? '').' '.($finca['propietario']['Apellido'] ?? '')) ?: '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $finca['propietario']['Telefono'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ isset($finca['terreno']['Superficie']) ? $finca['terreno']['Superficie'].' ha' : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('fincas.edit', $finca['id_Finca']) }}"
                                       class="text-ganaderasoft-verde hover:text-ganaderasoft-verde-oscuro">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">🏡</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay fincas registradas</h3>
                <p class="text-gray-500 mb-4">Comienza agregando tu primera finca al sistema</p>
                <a href="{{ route('fincas.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md">
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
        const tipo   = document.getElementById('filtroTipo').value;

        let total = 0, superficie = 0;

        document.querySelectorAll('.fila-finca').forEach(function (row) {
            const ok = (!nombre || row.dataset.nombre.includes(nombre))
                    && (!tipo   || row.dataset.tipo === tipo);
            row.style.display = ok ? '' : 'none';
            if (ok) {
                total++;
                superficie += parseFloat(row.dataset.superficie) || 0;
            }
        });

        document.getElementById('statTotal').textContent      = total;
        document.getElementById('statSuperficie').textContent = superficie.toLocaleString('es', {minimumFractionDigits:1, maximumFractionDigits:1});
    }

    function limpiarFiltros() {
        document.getElementById('filtroNombre').value = '';
        document.getElementById('filtroTipo').value   = '';
        document.querySelectorAll('.fila-finca').forEach(r => r.style.display = '');
        aplicarFiltros();
    }
</script>
@endsection
