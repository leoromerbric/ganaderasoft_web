@extends('layouts.authenticated')

@section('title', 'Personal de Finca - GanaderaSoft')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">👥 Personal de Finca</h1>
            <a href="{{ route('personal-finca.create') }}" 
               class="bg-ganaderasoft-verde text-white px-4 py-2 rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                + Nuevo Personal
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">¡Éxito! </strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">Error: </strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-ganaderasoft-negro">🔍 Filtros de Búsqueda</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- Filtro por Finca -->
                <div>
                    <label for="filtroFinca" class="block text-sm font-medium text-gray-700 mb-1">Finca</label>
                    <select id="filtroFinca" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Todas las fincas</option>
                        @foreach($fincas as $finca)
                            <option value="{{ $finca['id_Finca'] }}" {{ $fincaId == $finca['id_Finca'] ? 'selected' : '' }}>
                                {{ $finca['Nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Tipo -->
                <div>
                    <label for="filtroTipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Trabajador</label>
                    <select id="filtroTipo" class="form-select w-full border-gray-300 rounded-md">
                        <option value="">Todos los tipos</option>
                        <option value="Técnico">Técnico</option>
                        <option value="Veterinario">Veterinario</option>
                        <option value="Operario">Operario</option>
                        <option value="Vigilante">Vigilante</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Administrador">Administrador</option>
                    </select>
                </div>

                <!-- Filtro por Nombre -->
                <div>
                    <label for="filtroNombre" class="block text-sm font-medium text-gray-700 mb-1">Buscar por Nombre</label>
                    <input type="text" id="filtroNombre" placeholder="Escriba el nombre..."
                           class="form-input w-full border-gray-300 rounded-md">
                </div>

                <!-- Botón Limpiar -->
                <div class="flex items-end">
                    <button onclick="limpiarFiltros()" 
                            class="w-full bg-gray-500 text-white px-3 py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">#</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Personal</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['total_personal'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">🏥</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Veterinarios</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['por_tipo']['Veterinario'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">🔧</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Técnicos</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['por_tipo']['Técnico'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">👷</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Operarios</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ $estadisticas['por_tipo']['Operario'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Personal -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-ganaderasoft-negro">Lista de Personal</h3>
            </div>

            @if(empty($personalFinca))
                <div class="p-8 text-center">
                    <div class="text-6xl mb-4">👥</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay personal registrado</h3>
                    <p class="text-gray-500 mb-4">Comienza agregando el personal de tus fincas</p>
                    <a href="{{ route('personal-finca.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-ganaderasoft-verde hover:bg-ganaderasoft-verde/80">
                        + Registrar Primer Personal
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="tablaPersonalFinca">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Finca</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($personalFinca as $persona)
                                <tr class="hover:bg-gray-50 registro-personal"
                                    data-finca="{{ $persona['id_Finca'] }}"
                                    data-tipo="{{ $persona['Tipo_Trabajador'] }}"
                                    data-nombre="{{ strtolower($persona['Nombre'] . ' ' . $persona['Apellido']) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <div class="w-10 h-10 bg-ganaderasoft-celeste rounded-full flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">
                                                        @if($persona['Tipo_Trabajador'] == 'Veterinario')
                                                            🏥
                                                        @elseif($persona['Tipo_Trabajador'] == 'Técnico')
                                                            🔧
                                                        @elseif($persona['Tipo_Trabajador'] == 'Operario')
                                                            👷
                                                        @elseif($persona['Tipo_Trabajador'] == 'Vigilante')
                                                            🛡️
                                                        @elseif($persona['Tipo_Trabajador'] == 'Supervisor')
                                                            👨‍💼
                                                        @else
                                                            👤
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $persona['Nombre'] }} {{ $persona['Apellido'] }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $persona['Correo'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $persona['Cedula'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $persona['Telefono'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($persona['Tipo_Trabajador'] == 'Veterinario') bg-green-100 text-green-800
                                            @elseif($persona['Tipo_Trabajador'] == 'Técnico') bg-blue-100 text-blue-800
                                            @elseif($persona['Tipo_Trabajador'] == 'Supervisor') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $persona['Tipo_Trabajador'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $persona['finca']['Nombre'] ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('personal-finca.show', $persona['id_Tecnico']) }}" 
                                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                                                Ver
                                            </a>
                                            <a href="{{ route('personal-finca.edit', $persona['id_Tecnico']) }}" 
                                               class="text-ganaderasoft-verde hover:text-ganaderasoft-verde/80">
                                                Editar
                                            </a>
                                            <form action="{{ route('personal-finca.destroy', $persona['id_Tecnico']) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('¿Está seguro de eliminar este personal?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
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
        function filtrarRegistros() {
            const filtroFinca = document.getElementById('filtroFinca').value;
            const filtroTipo = document.getElementById('filtroTipo').value.toLowerCase();
            const filtroNombre = document.getElementById('filtroNombre').value.toLowerCase();
            
            const registros = document.querySelectorAll('.registro-personal');
            
            registros.forEach(function(registro) {
                let mostrar = true;
                
                // Filtro por finca
                if (filtroFinca && registro.dataset.finca !== filtroFinca) {
                    mostrar = false;
                }
                
                // Filtro por tipo
                if (filtroTipo && !registro.dataset.tipo.toLowerCase().includes(filtroTipo)) {
                    mostrar = false;
                }
                
                // Filtro por nombre
                if (filtroNombre && !registro.dataset.nombre.includes(filtroNombre)) {
                    mostrar = false;
                }
                
                registro.style.display = mostrar ? '' : 'none';
            });
            
            // Update URL with finca filter
            if (filtroFinca) {
                const url = new URL(window.location);
                url.searchParams.set('id_finca', filtroFinca);
                window.history.pushState({}, '', url);
            } else {
                const url = new URL(window.location);
                url.searchParams.delete('id_finca');
                window.history.pushState({}, '', url);
            }
        }
        
        function limpiarFiltros() {
            document.getElementById('filtroFinca').value = '';
            document.getElementById('filtroTipo').value = '';
            document.getElementById('filtroNombre').value = '';
            
            const registros = document.querySelectorAll('.registro-personal');
            registros.forEach(function(registro) {
                registro.style.display = '';
            });
            
            // Clear URL parameters
            const url = new URL(window.location);
            url.searchParams.delete('id_finca');
            window.history.pushState({}, '', url);
        }
        
        // Agregar event listeners
        document.getElementById('filtroFinca').addEventListener('change', filtrarRegistros);
        document.getElementById('filtroTipo').addEventListener('change', filtrarRegistros);
        document.getElementById('filtroNombre').addEventListener('input', filtrarRegistros);
    </script>
@endsection