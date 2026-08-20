@extends('layouts.authenticated')

@section('title', 'Personal de Finca')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Personal de Finca</h1>
                <p class="text-gray-500 text-sm mt-1">Gestión del personal asignado a las unidades de producción</p>
            </div>
            <a href="{{ route('personal-finca.create') }}"
                class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                + Registrar Personal
            </a>
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Finca</label>
                    <select id="filtroFinca"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todas las fincas</option>
                        @foreach($fincas as $finca)
                            @php
                                $fId = $finca['id'] ?? null;
                                $fNombre = $finca['nombre'] ?? ('Finca #' . $fId);
                            @endphp
                            <option value="{{ $fId }}" {{ $fincaId == $fId ? 'selected' : '' }}>
                                {{ $fNombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipo de
                        Trabajador</label>
                    <select id="filtroTipo"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                        <option value="">Todos los tipos</option>
                        @foreach($tiposTrabajador as $tipo)
                            @php
                                $tNombre = $tipo['nombre'] ?? '';
                            @endphp
                            <option value="{{ strtolower($tNombre) }}">{{ $tNombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar por
                        Nombre</label>
                    <input type="text" id="filtroNombre" placeholder="Escriba el nombre..."
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
        @php
            $tipoStyleMap = [
                'veterinario'   => ['icon' => '🏥', 'bg' => 'bg-green-100', 'text' => 'text-green-600'],
                'médico'        => ['icon' => '🏥', 'bg' => 'bg-green-100', 'text' => 'text-green-600'],
                'tecnico'       => ['icon' => '🔧', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                'técnico'       => ['icon' => '🔧', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                'operario'      => ['icon' => '👷', 'bg' => 'bg-amber-100', 'text' => 'text-amber-600'],
                'supervisor'    => ['icon' => '📋', 'bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                'administrador' => ['icon' => '💼', 'bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'],
                'vigilante'     => ['icon' => '🛡️', 'bg' => 'bg-rose-100', 'text' => 'text-rose-600'],
            ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Personal Card -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Personal</p>
                    <p class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $estadisticas['total_personal'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                    👥
                </div>
            </div>

            <!-- Dynamic KPI Cards from Backend Worker Types -->
            @foreach(array_slice($tiposTrabajador, 0, 3) as $tipo)
                @php
                    $nombreTipo = $tipo['nombre'] ?? '';
                    $key = strtolower($nombreTipo);
                    $style = $tipoStyleMap[$key] ?? ['icon' => '👤', 'bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
                    $count = $estadisticas['por_tipo'][$nombreTipo] ?? 0;
                @endphp
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 truncate" title="{{ $nombreTipo }}">
                            {{ $nombreTipo }}s
                        </p>
                        <p class="text-3xl font-extrabold {{ $style['text'] }}">{{ $count }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl {{ $style['bg'] }} flex items-center justify-center text-2xl">
                        {{ $style['icon'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Personal Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            @if(empty($personalFinca))
                <div class="p-12 text-center">
                    <div
                        class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                        👥
                    </div>
                    <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay personal registrado</h3>
                    <p class="text-gray-500 text-sm mb-6">Comienza registrando el personal asignado a tus fincas</p>
                    <a href="{{ route('personal-finca.create') }}"
                        class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        + Registrar Personal
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Empleado</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Cédula</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Cargo</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Finca</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Contacto</th>
                                <th
                                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaPersonal">
                            @foreach($personalFinca as $persona)
                                @php
                                    $pId = $persona['id'] ?? null;

                                    // Extraer V2 persona
                                    $personaSub = $persona['persona'] ?? null;
                                    $nombreEmp = $personaSub ? trim(($personaSub['nombre'] ?? '') . ' ' . ($personaSub['apellido'] ?? '')) : 'Personal';
                                    $cedulaEmp = $personaSub['cedula'] ?? '-';
                                    $telefonoEmp = $personaSub['telefono'] ?? '-';
                                    $correoEmp = $personaSub['correo'] ?? '-';

                                    $tipoObj = $persona['tipo_trabajador'] ?? null;
                                    $tipoNombre = $tipoObj['nombre'] ?? 'Trabajador';

                                    $fincaObj = $persona['finca'] ?? null;
                                    $fincaNombre = $fincaObj['nombre'] ?? ('Finca #' . ($persona['finca_id'] ?? 'N/A'));
                                    $fincaIdAttr = $persona['finca_id'] ?? '';
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors registro-personal" data-finca="{{ $fincaIdAttr }}"
                                    data-tipo="{{ strtolower($tipoNombre) }}" data-nombre="{{ strtolower($nombreEmp) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-ganaderasoft-celeste/15 flex items-center justify-center text-ganaderasoft-azul font-bold text-sm">
                                                {{ strtoupper(substr($nombreEmp ?: 'P', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $nombreEmp }}</p>
                                                <p class="text-xs text-gray-400">ID: #{{ $pId }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                        {{ $cedulaEmp }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                                    @if(in_array(strtolower($tipoNombre), ['veterinario', 'médico'])) bg-green-100 text-green-800
                                                    @elseif(in_array(strtolower($tipoNombre), ['tecnico', 'técnico'])) bg-blue-100 text-blue-800
                                                    @elseif(in_array(strtolower($tipoNombre), ['supervisor', 'administrador'])) bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                            {{ $tipoNombre }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                        {{ $fincaNombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        <p class="font-medium text-gray-900">📞 {{ $telefonoEmp }}</p>
                                        <p class="text-xs text-gray-400">✉️ {{ $correoEmp }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('personal-finca.show', $pId) }}"
                                                class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul font-semibold transition-colors">
                                                Ver
                                            </a>
                                            <a href="{{ route('personal-finca.edit', $pId) }}"
                                                class="text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-semibold transition-colors">
                                                Editar
                                            </a>
                                            <form action="{{ route('personal-finca.destroy', $pId) }}" method="POST" class="inline"
                                                onsubmit="return confirm('¿Confirma eliminar a este personal de finca?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 font-semibold transition-colors">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('filtroFinca').addEventListener('change', filtrarRegistros);
        document.getElementById('filtroTipo').addEventListener('change', filtrarRegistros);
        document.getElementById('filtroNombre').addEventListener('input', filtrarRegistros);

        function filtrarRegistros() {
            const finca = document.getElementById('filtroFinca').value;
            const tipo = document.getElementById('filtroTipo').value.toLowerCase();
            const nombre = document.getElementById('filtroNombre').value.toLowerCase();

            document.querySelectorAll('.registro-personal').forEach(function (row) {
                const ok = (!finca || row.dataset.finca === finca)
                    && (!tipo || row.dataset.tipo.includes(tipo))
                    && (!nombre || row.dataset.nombre.includes(nombre));
                row.style.display = ok ? '' : 'none';
            });
        }

        function limpiarFiltros() {
            document.getElementById('filtroFinca').value = '';
            document.getElementById('filtroTipo').value = '';
            document.getElementById('filtroNombre').value = '';
            document.querySelectorAll('.registro-personal').forEach(r => r.style.display = '');
        }
    </script>
@endsection