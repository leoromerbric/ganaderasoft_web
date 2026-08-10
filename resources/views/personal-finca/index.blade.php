@extends('layouts.authenticated')

@section('title', 'Personal de Finca')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">Personal de Finca</h1>
            <p class="text-gray-500 text-sm mt-1">Gestión del personal asignado a las unidades de producción (API V2)</p>
        </div>
        <a href="{{ route('personal-finca.create') }}"
           class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul text-white font-semibold rounded-xl hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Personal
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">✅</span>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
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
                            $fId = $finca['id'] ?? $finca['id_Finca'] ?? null;
                            $fNombre = $finca['nombre'] ?? $finca['Nombre'] ?? ('Finca #'.$fId);
                        @endphp
                        <option value="{{ $fId }}" {{ $fincaId == $fId ? 'selected' : '' }}>
                            {{ $fNombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipo de Trabajador</label>
                <select id="filtroTipo"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                    <option value="">Todos los tipos</option>
                    <option value="tecnico">Técnico</option>
                    <option value="veterinario">Veterinario</option>
                    <option value="operario">Operario</option>
                    <option value="vigilante">Vigilante</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Buscar por Nombre</label>
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Personal</p>
                <p class="text-3xl font-extrabold text-ganaderasoft-azul">{{ $estadisticas['total_personal'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-2xl">
                👥
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Veterinarios</p>
                <p class="text-3xl font-extrabold text-green-600">{{ $estadisticas['por_tipo']['Veterinario'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-2xl">
                🏥
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Técnicos</p>
                <p class="text-3xl font-extrabold text-blue-600">
                    {{ $estadisticas['por_tipo']['Tecnico'] ?? ($estadisticas['por_tipo']['Técnico'] ?? 0) }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-2xl">
                🔧
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Operarios</p>
                <p class="text-3xl font-extrabold text-amber-600">{{ $estadisticas['por_tipo']['Operario'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-2xl">
                👷
            </div>
        </div>
    </div>

    <!-- Personal Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        @if(empty($personalFinca))
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-ganaderasoft-celeste/10 flex items-center justify-center text-4xl">
                    👥
                </div>
                <h3 class="text-lg font-bold text-ganaderasoft-negro mb-1">No hay personal registrado</h3>
                <p class="text-gray-500 text-sm mb-6">Comienza registrando el personal asignado a tus fincas</p>
                <a href="{{ route('personal-finca.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-ganaderasoft-celeste to-ganaderasoft-azul text-white font-semibold rounded-xl hover:from-ganaderasoft-azul hover:to-ganaderasoft-celeste transition-all duration-200 shadow-md">
                    + Registrar Personal
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empleado</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cédula</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cargo</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Finca</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacto</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tablaPersonal">
                        @foreach($personalFinca as $persona)
                            @php
                                $pId = $persona['id'] ?? $persona['id_Tecnico'] ?? null;
                                
                                // Extraer V2 persona
                                $personaSub = $persona['persona'] ?? null;
                                $nombreEmp = $personaSub ? trim(($personaSub['nombre'] ?? '').' '.($personaSub['apellido'] ?? '')) : trim(($persona['Nombre'] ?? '').' '.($persona['Apellido'] ?? ''));
                                $cedulaEmp = $personaSub['cedula'] ?? $persona['Cedula'] ?? '-';
                                $telefonoEmp = $personaSub['telefono'] ?? $persona['Telefono'] ?? '-';
                                $correoEmp = $personaSub['correo'] ?? $persona['Correo'] ?? '-';

                                $tipoObj = $persona['tipo_trabajador'] ?? null;
                                $tipoNombre = $tipoObj['nombre'] ?? $persona['Tipo_Trabajador'] ?? 'Trabajador';

                                $fincaObj = $persona['finca'] ?? null;
                                $fincaNombre = $fincaObj['nombre'] ?? $fincaObj['Nombre'] ?? ('Finca #'.($persona['finca_id'] ?? $persona['id_Finca'] ?? 'N/A'));
                                $fincaIdAttr = $persona['finca_id'] ?? $persona['id_Finca'] ?? '';
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors registro-personal"
                                data-finca="{{ $fincaIdAttr }}"
                                data-tipo="{{ strtolower($tipoNombre) }}"
                                data-nombre="{{ strtolower($nombreEmp) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-ganaderasoft-celeste/15 flex items-center justify-center text-ganaderasoft-azul font-bold text-sm">
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
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold transition-colors">
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