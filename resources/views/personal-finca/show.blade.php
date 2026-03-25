@extends('layouts.authenticated')

@section('title', 'Detalles del Personal - GanaderaSoft')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('personal-finca.index') }}" 
                   class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">
                    👥 {{ $personal['Nombre'] }} {{ $personal['Apellido'] }}
                </h1>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('personal-finca.edit', $personal['id_Tecnico']) }}" 
                   class="bg-ganaderasoft-verde text-white px-4 py-2 rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                    ✏️ Editar
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">¡Éxito! </strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Panel Principal de Información -->
            <div class="lg:col-span-2">
                <!-- Información Personal -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-ganaderasoft-celeste text-white px-6 py-4">
                        <h2 class="text-lg font-semibold flex items-center">
                            <span class="mr-2">
                                @if($personal['Tipo_Trabajador'] == 'Veterinario')
                                    🏥
                                @elseif($personal['Tipo_Trabajador'] == 'Técnico')
                                    🔧
                                @elseif($personal['Tipo_Trabajador'] == 'Operario')
                                    👷
                                @elseif($personal['Tipo_Trabajador'] == 'Vigilante')
                                    🛡️
                                @elseif($personal['Tipo_Trabajador'] == 'Supervisor')
                                    👨‍💼
                                @else
                                    👤
                                @endif
                            </span>
                            Información Personal
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Nombre Completo</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $personal['Nombre'] }} {{ $personal['Apellido'] }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Cédula de Identidad</label>
                                    <p class="text-lg text-gray-900">{{ number_format($personal['Cedula'], 0, ',', '.') }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Fecha de Nacimiento</label>
                                    <p class="text-lg text-gray-900">
                                        {{ date('d/m/Y', strtotime($personal['Fecha_Nacimiento'])) }}
                                        <span class="text-sm text-gray-500">
                                            ({{ \Carbon\Carbon::parse($personal['Fecha_Nacimiento'])->age }} años)
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Tipo de Trabajador</label>
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                        @if($personal['Tipo_Trabajador'] == 'Veterinario') bg-green-100 text-green-800
                                        @elseif($personal['Tipo_Trabajador'] == 'Técnico') bg-blue-100 text-blue-800
                                        @elseif($personal['Tipo_Trabajador'] == 'Supervisor') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $personal['Tipo_Trabajador'] }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Fecha de Ingreso</label>
                                    <p class="text-lg text-gray-900">
                                        {{ date('d/m/Y', strtotime($personal['Fecha_Ingreso'])) }}
                                        <span class="text-sm text-gray-500">
                                            ({{ \Carbon\Carbon::parse($personal['Fecha_Ingreso'])->diffForHumans() }})
                                        </span>
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Tiempo en la Empresa</label>
                                    <p class="text-lg text-gray-900">
                                        {{ \Carbon\Carbon::parse($personal['Fecha_Ingreso'])->diffInDays(now()) }} días
                                        <span class="text-sm text-gray-500">
                                            (~{{ round(\Carbon\Carbon::parse($personal['Fecha_Ingreso'])->diffInMonths(now())) }} meses)
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-ganaderasoft-verde text-white px-6 py-4">
                        <h2 class="text-lg font-semibold">📞 Información de Contacto</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Teléfono</label>
                                <div class="flex items-center">
                                    <span class="text-lg text-gray-900">{{ $personal['Telefono'] }}</span>
                                    <a href="tel:{{ $personal['Telefono'] }}" 
                                       class="ml-3 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                                        📞 Llamar
                                    </a>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Correo Electrónico</label>
                                <div class="flex items-center">
                                    <span class="text-lg text-gray-900">{{ $personal['Correo'] }}</span>
                                    <a href="mailto:{{ $personal['Correo'] }}" 
                                       class="ml-3 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                                        ✉️ Email
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de la Finca -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-ganaderasoft-azul text-white px-6 py-4">
                        <h2 class="text-lg font-semibold">🏡 Información de la Finca</h2>
                    </div>
                    <div class="p-6">
                        @if(isset($personal['finca']))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Nombre de la Finca</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $personal['finca']['Nombre'] }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Ubicación</label>
                                    <p class="text-lg text-gray-900">{{ $personal['finca']['Municipio'] }}, {{ $personal['finca']['Estado'] }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Hectáreas</label>
                                    <p class="text-lg text-gray-900">{{ number_format($personal['finca']['Hectareas'], 2) }} ha</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Tipo de Explotación</label>
                                    <p class="text-lg text-gray-900">{{ $personal['finca']['id_Tipo_Explotacion'] }}</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-2">🏡</div>
                                <p class="text-gray-500">No se encontró información de la finca</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="lg:col-span-1">
                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-700">⚡ Acciones Rápidas</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('personal-finca.edit', $personal['id_Tecnico']) }}" 
                           class="w-full bg-ganaderasoft-verde text-white px-4 py-2 rounded-md hover:bg-ganaderasoft-verde/90 transition-colors flex items-center justify-center">
                            ✏️ Editar Información
                        </a>
                        
                        <a href="{{ route('personal-finca.index') }}" 
                           class="w-full bg-ganaderasoft-celeste text-white px-4 py-2 rounded-md hover:bg-ganaderasoft-celeste/90 transition-colors flex items-center justify-center">
                            📋 Ver Lista Completa
                        </a>
                        
                        <form action="{{ route('personal-finca.destroy', $personal['id_Tecnico']) }}" method="POST" 
                              onsubmit="return confirm('¿Está seguro de eliminar a este personal? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors flex items-center justify-center">
                                🗑️ Eliminar Personal
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Estadísticas del Personal -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-700">📊 Estadísticas</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Días trabajados</span>
                            <span class="font-semibold text-ganaderasoft-azul">
                                {{ \Carbon\Carbon::parse($personal['Fecha_Ingreso'])->diffInDays(now()) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Meses de experiencia</span>
                            <span class="font-semibold text-ganaderasoft-azul">
                                {{ round(\Carbon\Carbon::parse($personal['Fecha_Ingreso'])->diffInMonths(now())) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Edad actual</span>
                            <span class="font-semibold text-ganaderasoft-azul">
                                {{ \Carbon\Carbon::parse($personal['Fecha_Nacimiento'])->age }} años
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Información del Registro -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-700">📝 Información del Registro</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500">ID del Personal</label>
                            <p class="text-sm text-gray-900">{{ $personal['id_Tecnico'] }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Registrado el</label>
                            <p class="text-sm text-gray-900">
                                {{ date('d/m/Y H:i', strtotime($personal['created_at'] ?? date('Y-m-d H:i:s'))) }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Última actualización</label>
                            <p class="text-sm text-gray-900">
                                {{ date('d/m/Y H:i', strtotime($personal['updated_at'] ?? date('Y-m-d H:i:s'))) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection