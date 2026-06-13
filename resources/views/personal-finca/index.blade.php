@extends('layouts.authenticated')

@section('title', 'Personal de Finca')

@section('content')
    <div>
        <!-- Page Title and Actions -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-ganaderasoft-negro">Personal de Finca</h2>
                <p class="text-gray-600 mt-1">Gestiona el personal asignado a cada finca</p>
            </div>
            <a href="{{ route('personal-finca.create') }}"
               class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                + Nuevo Personal
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Finca</label>
                    <select id="filtroFinca"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todas las fincas</option>
                        @foreach($fincas as $finca)
                            <option value="{{ $finca['id_Finca'] }}" {{ $fincaId == $finca['id_Finca'] ? 'selected' : '' }}>
                                {{ $finca['Nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Trabajador</label>
                    <select id="filtroTipo"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Todos los tipos</option>
                        <option value="Tecnico">Tecnico</option>
                        <option value="Veterinario">Veterinario</option>
                        <option value="Operario">Operario</option>
                        <option value="Vigilante">Vigilante</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Administrador">Administrador</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar por Nombre</label>
                    <input type="text" id="filtroNombre" placeholder="Escriba el nombre..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                </div>
                <div>
                    <button onclick="limpiarFiltros()"
                            class="w-full px-6 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                        Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadisticas -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-azul">{{ $estadisticas['total_personal'] }}</div>
                    <div class="text-sm text-gray-600">Total Personal</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-verde">{{ $estadisticas['por_tipo']['Veterinario'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Veterinarios</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-ganaderasoft-celeste">{{ $estadisticas['por_tipo']['Tecnico'] ?? ($estadisticas['por_tipo']['T&eacute;cnico'] ?? 0) }}</div>
                    <div class="text-sm text-gray-600">Tecnicos</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold" style="color:#E07B39;">{{ $estadisticas['por_tipo']['Operario'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Operarios</div>
                </div>
            </div>
        </div>

        <!-- Tabla de Personal -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if(empty($personalFinca))
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">&#x1F465;</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay personal registrado</h3>
                    <p class="text-gray-500 mb-4">Comienza agregando el personal de tus fincas</p>
                    <a href="{{ route('personal-finca.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md">
                        + Registrar Primer Personal
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cedula</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Finca</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tablaPersonal">
                            @foreach($personalFinca as $persona)
                                <tr class="hover:bg-gray-50 transition-colors registro-personal"
                                    data-finca="{{ $persona['id_Finca'] ?? '' }}"
                                    data-tipo="{{ strtolower($persona['Tipo_Trabajador'] ?? '') }}"
                                    data-nombre="{{ strtolower(($persona['Nombre'] ?? '').' '.($persona['Apellido'] ?? '')) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-ganaderasoft-celeste flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                                {{ strtoupper(substr($persona['Nombre'] ?? 'P', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $persona['Nombre'] }} {{ $persona['Apellido'] }}</div>
                                                <div class="text-xs text-gray-500">{{ $persona['Correo'] ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $persona['Cedula'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $persona['Telefono'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $tipo = $persona['Tipo_Trabajador'] ?? ''; @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $tipo === 'Veterinario' ? 'bg-green-100 text-green-800' : ($tipo === 'Tecnico' || $tipo === 'T&eacute;cnico' ? 'bg-blue-100 text-blue-800' : ($tipo === 'Supervisor' ? 'bg-purple-100 text-purple-800' : ($tipo === 'Administrador' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800'))) }}">
                                            {{ $tipo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $persona['finca']['Nombre'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('personal-finca.show', $persona['id_Tecnico']) }}"
                                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">Ver</a>
                                            <a href="{{ route('personal-finca.edit', $persona['id_Tecnico']) }}"
                                               class="text-ganaderasoft-verde hover:text-ganaderasoft-verde-oscuro">Editar</a>
                                            <form action="{{ route('personal-finca.destroy', $persona['id_Tecnico']) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Confirma eliminar este personal?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
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
            const finca  = document.getElementById('filtroFinca').value;
            const tipo   = document.getElementById('filtroTipo').value.toLowerCase();
            const nombre = document.getElementById('filtroNombre').value.toLowerCase();
            document.querySelectorAll('.registro-personal').forEach(function (row) {
                const ok = (!finca  || row.dataset.finca  === finca)
                        && (!tipo   || row.dataset.tipo.includes(tipo))
                        && (!nombre || row.dataset.nombre.includes(nombre));
                row.style.display = ok ? '' : 'none';
            });
        }

        function limpiarFiltros() {
            document.getElementById('filtroFinca').value  = '';
            document.getElementById('filtroTipo').value   = '';
            document.getElementById('filtroNombre').value = '';
            document.querySelectorAll('.registro-personal').forEach(r => r.style.display = '');
        }
    </script>
@endsection