@extends('layouts.authenticated')

@section('title', 'Animales')

@section('content')
    <div>
        <!-- Page Title and Actions -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de Animales</h2>
                <p class="text-gray-600 mt-1">Administra los animales del sistema</p>
            </div>
            <a href="{{ route('animales.create') }}" 
               class="px-6 py-3 bg-ganaderasoft-verde text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                + Nuevo
            </a>
        </div>

        <!-- Success/Error Messages -->
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

        <!-- Filter by Herd -->
        @if(count($rebanos) > 0)
        <div class="mb-6 flex items-center space-x-3">
            <label for="rebano-filter" class="text-sm font-medium text-gray-700">Filtrar por Rebaño:</label>
            <select id="rebano-filter" onchange="filterByRebano(this.value)" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                <option value="">Todos los Rebaños</option>
                @foreach($rebanos as $rebano)
                    <option value="{{ $rebano['id_Rebano'] }}" {{ $rebanoId == $rebano['id_Rebano'] ? 'selected' : '' }}>
                        {{ $rebano['Nombre'] }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Animals List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if(count($animales) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rebaño</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Nacimiento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($animales as $animal)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $animal['codigo_animal'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $animal['Nombre'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 py-1 rounded {{ $animal['Sexo'] === 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $animal['Sexo'] === 'M' ? 'Macho' : 'Hembra' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $animal['rebano']['Nombre'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ isset($animal['fecha_nacimiento']) ? date('d/m/Y', strtotime($animal['fecha_nacimiento'])) : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if(isset($animal['archivado']) && $animal['archivado'])
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Archivado
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('animales.show', $animal['id_Animal']) }}" 
                                               class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">
                                                Ver
                                            </a>
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('animales.edit', $animal['id_Animal']) }}" 
                                               class="text-ganaderasoft-verde hover:text-green-700">
                                                Editar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">🐄</div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay animales registrados</h3>
                    <p class="text-gray-500 mb-6">Comienza agregando tu primer animal al sistema</p>
                    <a href="{{ route('animales.create') }}" 
                       class="inline-block px-6 py-3 bg-ganaderasoft-verde text-white rounded-lg hover:bg-opacity-90 transition-all duration-200">
                        + Nuevo
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        function filterByRebano(rebanoId) {
            const url = new URL(window.location.href);
            if (rebanoId) {
                url.searchParams.set('id_rebano', rebanoId);
            } else {
                url.searchParams.delete('id_rebano');
            }
            window.location.href = url.toString();
        }
    </script>
@endsection
