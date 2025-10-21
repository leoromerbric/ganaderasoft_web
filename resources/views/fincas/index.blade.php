@extends('layouts.authenticated')

@section('title', 'Fincas')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de Fincas</h2>
            <p class="text-gray-600 mt-1">Lista de fincas registradas en el sistema</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(isset($error))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="text-sm">{{ $error }}</p>
            </div>
        @endif

        <!-- Fincas List -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-ganaderasoft-negro">Lista de Fincas</h3>
                    <a href="{{ route('fincas.create') }}" class="bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center space-x-2 shadow-sm">
                        <span>Nueva</span>
                    </a>
                </div>
            </div>

            <div class="p-6">
                @if(count($fincas) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($fincas as $finca)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-ganaderasoft-negro mb-1">{{ $finca['Nombre'] }}</h4>
                                        <p class="text-sm text-gray-600">{{ $finca['Explotacion_Tipo'] }}</p>
                                    </div>
                                    <span class="text-3xl">🏡</span>
                                </div>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24">Propietario:</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $finca['propietario']['Nombre'] ?? '' }} {{ $finca['propietario']['Apellido'] ?? '' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24">Teléfono:</span>
                                        <span class="font-medium text-gray-900">{{ $finca['propietario']['Telefono'] ?? 'N/A' }}</span>
                                    </div>
                                    @if(isset($finca['terreno']) && $finca['terreno'])
                                        <div class="flex items-center text-sm">
                                            <span class="text-gray-500 w-24">Superficie:</span>
                                            <span class="font-medium text-gray-900">{{ $finca['terreno']['Superficie'] ?? 'N/A' }} ha</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ route('fincas.dashboard', $finca['id_Finca']) }}" class="flex-1 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white px-3 py-2 rounded text-sm font-medium transition-colors duration-200 text-center shadow-sm">
                                        Ir a Finca
                                    </a>
                                    <a href="{{ route('fincas.edit', $finca['id_Finca']) }}" class="px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        ✏️
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(isset($pagination) && $pagination['total'] > 0)
                        <div class="mt-6 flex justify-between items-center text-sm text-gray-600">
                            <p>Mostrando {{ count($fincas) }} de {{ $pagination['total'] }} fincas</p>
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
                        <span class="text-6xl mb-4 block">🏡</span>
                        <p class="text-gray-500 text-lg">No hay fincas registradas</p>
                        <p class="text-gray-400 text-sm mt-2">Comience agregando una nueva finca</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
