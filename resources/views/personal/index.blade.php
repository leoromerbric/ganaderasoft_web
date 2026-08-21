@extends('layouts.authenticated')

@section('title', 'Gestión de personal')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Gestión de personal</h1>
            <p class="text-gray-500 text-sm mt-1">Administración de trabajadores, encargados y técnicos</p>
        </div>
        <a href="{{ route('personal.create') }}"
           class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
            + Registrar personal
        </a>
    </div>

    <!-- Alert Messages -->
    @if(isset($error))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center space-x-2">
            <span class="text-lg">⚠️</span>
            <p class="text-sm font-medium">{{ $error }}</p>
        </div>
    @endif
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center space-x-2">
            <span class="text-lg">✅</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center space-x-2">
            <span class="text-lg">⚠️</span>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Finca Filter Dropdown -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="max-w-md">
            <label for="finca_select" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                Seleccionar finca
            </label>
            <select id="finca_select" onchange="filterByFinca(this.value)"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                <option value="">Seleccione una finca...</option>
                @foreach($fincas as $finca)
                    @php
                        $fId = $finca['id'] ?? null;
                        $fNombre = $finca['nombre'] ?? ('Finca #'.$fId);
                    @endphp
                    <option value="{{ $fId }}" {{ $idFinca == $fId ? 'selected' : '' }}>
                        {{ $fNombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Personal Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(count($personal) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empleado</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cédula</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Puesto / cargo</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Finca</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacto</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach($personal as $persona)
                            @php
                                $pId = $persona['id'] ?? null;
                                
                                // Data extraida V2
                                $personaSub = $persona['persona'] ?? null;
                                $nombreEmp = $personaSub ? trim(($personaSub['nombre'] ?? '').' '.($personaSub['apellido'] ?? '')) : 'Personal';
                                $cedulaEmp = $personaSub['cedula'] ?? '-';
                                $telefonoEmp = $personaSub['telefono'] ?? '-';
                                $correoEmp = $personaSub['correo'] ?? '-';

                                $tipoObj = $persona['tipo_trabajador'] ?? null;
                                $tipoNombre = $tipoObj['nombre'] ?? 'Trabajador';
                                
                                $fincaObj = $persona['finca'] ?? null;
                                $fincaNombre = $fincaObj['nombre'] ?? ('Finca #'.($persona['finca_id'] ?? 'N/A'));
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-ganaderasoft-celeste/15 flex items-center justify-center text-ganaderasoft-azul font-bold text-sm">
                                            👤
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
                                        @if(in_array(strtolower($tipoNombre), ['veterinario', 'médico'])) bg-blue-100 text-blue-800
                                        @elseif(in_array(strtolower($tipoNombre), ['tecnico', 'técnico'])) bg-green-100 text-green-800
                                        @elseif(in_array(strtolower($tipoNombre), ['administrador', 'supervisor'])) bg-purple-100 text-purple-800
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
                                    <a href="{{ route('personal.edit', $pId) }}"
                                       class="inline-flex items-center text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-semibold transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
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
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                    👥
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay personal registrado</h3>
                @if(!$idFinca)
                    <p class="text-gray-500 text-sm mb-6">Selecciona una finca en el desplegable superior para listar su equipo</p>
                @else
                    <p class="text-gray-500 text-sm mb-6">No hay empleados registrados en esta finca actualmente</p>
                @endif
                <a href="{{ route('personal.create') }}"
                   class="inline-block px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                    + Registrar empleado
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    function filterByFinca(fincaId) {
        const url = new URL(window.location.href);
        if (fincaId) {
            url.searchParams.set('finca_id', fincaId);
        } else {
            url.searchParams.delete('finca_id');
        }
        window.location.href = url.toString();
    }
</script>
@endsection
