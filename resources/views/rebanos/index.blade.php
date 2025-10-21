@extends(session('selected_finca') ? 'layouts.finca' : 'layouts.authenticated')

@section('title', 'Rebaños')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de Rebaños</h2>
            <p class="text-gray-600 mt-1">Lista de rebaños registrados en el sistema</p>
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

        <!-- Rebaños List -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-ganaderasoft-negro">Lista de Rebaños</h3>
                    <a 
                        href="{{ route('rebanos.create') }}"
                        class="bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center space-x-2 shadow-sm">
                        <span>Nuevo</span>
                    </a>
                </div>
            </div>

            <div class="p-6">
                @if(count($rebanos) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($rebanos as $rebano)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-ganaderasoft-negro mb-1">{{ $rebano['Nombre'] }}</h4>
                                        <p class="text-sm text-gray-600">
                                            Finca: {{ $rebano['finca']['Nombre'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <span class="text-3xl">🐄</span>
                                </div>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24">ID:</span>
                                        <span class="font-medium text-gray-900">{{ $rebano['id_Rebano'] }}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24">Animales:</span>
                                        <span class="font-medium text-gray-900">{{ count($rebano['animales'] ?? []) }}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24">Tipo:</span>
                                        <span class="font-medium text-gray-900">{{ $rebano['finca']['Explotacion_Tipo'] ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                @if(isset($rebano['animales']) && count($rebano['animales']) > 0)
                                    <div class="border-t border-gray-200 pt-3 mb-3">
                                        <p class="text-xs font-semibold text-gray-600 mb-2">Animales en el rebaño:</p>
                                        <div class="space-y-1">
                                            @foreach(array_slice($rebano['animales'], 0, 3) as $animal)
                                                <div class="text-xs text-gray-700 flex items-center">
                                                    <span class="mr-2">•</span>
                                                    <span>{{ $animal['Nombre'] }} ({{ $animal['codigo_animal'] }})</span>
                                                </div>
                                            @endforeach
                                            @if(count($rebano['animales']) > 3)
                                                <p class="text-xs text-gray-500">+ {{ count($rebano['animales']) - 3 }} más</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="flex space-x-2">
                                    <a 
                                        href="{{ route('animales.index', ['id_rebano' => $rebano['id_Rebano']]) }}"
                                        class="flex-1 bg-ganaderasoft-celeste hover:bg-blue-500 text-white px-3 py-2 rounded text-sm font-medium transition-colors duration-200 text-center">
                                        Ver Animales
                                    </a>
                                    <a 
                                        href="{{ route('rebanos.edit', $rebano['id_Rebano']) }}"
                                        class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center">
                                        ✏️
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(isset($pagination) && $pagination['total'] > 0)
                        <div class="mt-6 flex justify-between items-center text-sm text-gray-600">
                            <p>Mostrando {{ count($rebanos) }} de {{ $pagination['total'] }} rebaños</p>
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
                        <span class="text-6xl mb-4 block">🐄</span>
                        <p class="text-gray-500 text-lg">No hay rebaños registrados</p>
                        <p class="text-gray-400 text-sm mt-2">Comience agregando un nuevo rebaño</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
