@extends(session('selected_finca') ? 'layouts.finca' : 'layouts.authenticated')

@section('title', 'Personal')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de Personal</h2>
            <p class="text-gray-600 mt-1">Personal registrado en las fincas</p>
        </div>

        @if(isset($error))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="text-sm">{{ $error }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Filter by Finca -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-end space-x-4">
                <div class="flex-1">
                    <label for="finca_select" class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Finca
                    </label>
                    <select 
                        id="finca_select" 
                        onchange="filterByFinca(this.value)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste">
                        <option value="">Seleccione una finca...</option>
                        @foreach($fincas as $finca)
                            <option value="{{ $finca['id_Finca'] }}" {{ $idFinca == $finca['id_Finca'] ? 'selected' : '' }}>
                                {{ $finca['Nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Personal List -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-ganaderasoft-negro">Personal de Finca</h3>
                    <a 
                        href="{{ route('personal.create') }}"
                        class="bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center space-x-2 shadow-sm">
                        <span>Nuevo</span>
                    </a>
                </div>
            </div>

            <div class="p-6">
                @if(count($personal) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Finca</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($personal as $persona)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $persona['Cedula'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">👤</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $persona['Nombre'] }} {{ $persona['Apellido'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($persona['Tipo_Trabajador'] === 'Veterinario') bg-blue-100 text-blue-800
                                                @elseif($persona['Tipo_Trabajador'] === 'Tecnico') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $persona['Tipo_Trabajador'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $persona['finca']['Nombre'] ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ $persona['Telefono'] }}</div>
                                            <div class="text-xs">{{ $persona['Correo'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-ganaderasoft-celeste hover:text-blue-700 mr-3">Ver</button>
                                            <a 
                                                href="{{ route('personal.edit', $persona['id_Tecnico']) }}"
                                                class="text-ganaderasoft-verde hover:text-green-700">
                                                Editar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(isset($pagination) && $pagination['total'] > 0)
                        <div class="mt-6 flex justify-between items-center text-sm text-gray-600">
                            <p>Mostrando {{ count($personal) }} de {{ $pagination['total'] }} registros</p>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 transition-colors duration-200" 
                                    {{ $pagination['current_page'] <= 1 ? 'disabled' : '' }}>
                                    Anterior
                                </button>
                                <span class="px-3 py-1">Página {{ $pagination['current_page'] }} de {{ $pagination['last_page'] }}</span>
                                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 transition-colors duration-200"
                                    {{ $pagination['current_page'] >= $pagination['last_page'] ? 'disabled' : '' }}>
                                    Siguiente
                                </button>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <span class="text-6xl mb-4 block">👥</span>
                        <p class="text-gray-500 text-lg">No hay personal registrado</p>
                        @if(!isset($idFinca))
                            <p class="text-gray-400 text-sm mt-2">Seleccione una finca para ver su personal</p>
                        @else
                            <p class="text-gray-400 text-sm mt-2">No hay personal registrado en esta finca</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function filterByFinca(fincaId) {
            const url = new URL(window.location.href);
            if (fincaId) {
                url.searchParams.set('id_finca', fincaId);
            } else {
                url.searchParams.delete('id_finca');
            }
            window.location.href = url.toString();
        }
    </script>
@endsection
