@extends('layouts.authenticated')

@section('title', 'Registrar personal de finca')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-2xl shadow-xs border border-teal-100 shrink-0">
                👥
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Registrar personal de finca
                </h1>
                <p class="text-gray-500 text-sm mt-1">Complete la información del trabajador y su asignación a la unidad de producción</p>
            </div>
        </div>
        <div>
            <a href="{{ route('personal-finca.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm space-y-1">
            <div class="flex items-center space-x-2 font-bold mb-1">
                <span class="text-lg">⚠️</span>
                <p class="text-sm">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario Principal -->
    <form method="POST" action="{{ route('personal-finca.store') }}" id="formCreatePersonal" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card 1: Finca y Cargo -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>🏡</span> Asignación de finca y cargo
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Finca Destino -->
                        <div>
                            <label for="finca_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Finca de asignación <span class="text-red-500">*</span>
                            </label>
                            <select name="finca_id" id="finca_id" required
                                    class="w-full px-4 py-3 border @error('finca_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar finca --</option>
                                @foreach($fincas as $finca)
                                    @php
                                        $fId = $finca['id'] ?? null;
                                        $fNombre = $finca['nombre'] ?? ('Finca #' . $fId);
                                        $fTipo = $finca['explotacion_tipo'] ?? 'General';
                                        $isSelected = (string) old('finca_id', request()->query('finca_id')) === (string) $fId;
                                    @endphp
                                    <option value="{{ $fId }}" data-nombre="{{ $fNombre }}" data-tipo="{{ $fTipo }}" {{ $isSelected ? 'selected' : '' }}>
                                        🏡 {{ $fNombre }} ({{ $fTipo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('finca_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            <p class="text-[11px] text-gray-400 mt-1">Finca en la cual laborará.</p>
                        </div>

                        <!-- Tipo de Trabajador / Cargo -->
                        <div>
                            <label for="tipo_trabajador_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Cargo o rol de trabajo <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo_trabajador_id" id="tipo_trabajador_id" required
                                    class="w-full px-4 py-3 border @error('tipo_trabajador_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                                <option value="">-- Seleccionar cargo --</option>
                                @foreach($tiposTrabajador as $tipo)
                                    @php
                                        $tId = $tipo['id'] ?? null;
                                        $tNombre = $tipo['nombre'] ?? '';
                                        $isSelected = (string) old('tipo_trabajador_id') === (string) $tId;
                                    @endphp
                                    <option value="{{ $tId }}" data-nombre="{{ $tNombre }}" {{ $isSelected ? 'selected' : '' }}>
                                        💼 {{ $tNombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_trabajador_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            <p class="text-[11px] text-gray-400 mt-1">Función principal.</p>
                        </div>

                        <!-- Fecha de Ingreso -->
                        <div>
                            <label for="fecha_ingreso" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de ingreso
                            </label>
                            <input type="date" name="fecha_ingreso" id="fecha_ingreso" 
                                   value="{{ old('fecha_ingreso', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border @error('fecha_ingreso') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium text-gray-700">
                            @error('fecha_ingreso')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                            <p class="text-[11px] text-gray-400 mt-1">Fecha de inicio de labores.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Datos Personales y Contacto -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>👤</span> Datos personales y de contacto
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Cédula -->
                        <div>
                            <label for="cedula" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Cédula / Identificación <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="cedula" id="cedula" required 
                                   value="{{ old('cedula') }}" maxlength="20"
                                   placeholder="Ej: V12345678"
                                   class="w-full px-4 py-3 border @error('cedula') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-mono uppercase">
                            @error('cedula')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" required 
                                   value="{{ old('nombre') }}" maxlength="25"
                                   placeholder="Ej: Carlos"
                                   class="w-full px-4 py-3 border @error('nombre') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label for="apellido" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Apellido <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="apellido" id="apellido" required 
                                   value="{{ old('apellido') }}" maxlength="25"
                                   placeholder="Ej: Mendoza"
                                   class="w-full px-4 py-3 border @error('apellido') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('apellido')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Teléfono de contacto <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="telefono" id="telefono" required 
                                   value="{{ old('telefono') }}" maxlength="15"
                                   placeholder="Ej: 04141234567"
                                   class="w-full px-4 py-3 border @error('telefono') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('telefono')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div>
                            <label for="correo" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Correo electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="correo" id="correo" required 
                                   value="{{ old('correo') }}" maxlength="40"
                                   placeholder="Ej: empleado@ganaderasoft.com"
                                   class="w-full px-4 py-3 border @error('correo') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium">
                            @error('correo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label for="fecha_nacimiento" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Fecha de nacimiento
                            </label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento') }}"
                                   class="w-full px-4 py-3 border @error('fecha_nacimiento') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all bg-white font-medium text-gray-700">
                            @error('fecha_nacimiento')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <!-- Card 1: Resumen y Acciones -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen de la ficha
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Avatar e Identificación -->
                        <div class="p-4 bg-teal-50/70 border border-teal-100 rounded-2xl flex items-center space-x-3">
                            <div id="previewAvatar" class="w-12 h-12 rounded-xl bg-white border border-teal-200 text-teal-700 font-bold flex items-center justify-center text-xl shadow-xs shrink-0">
                                P
                            </div>
                            <div class="overflow-hidden">
                                <p id="previewNombreCompleto" class="text-base font-bold text-gray-900 truncate">Nuevo personal</p>
                                <p id="previewCedula" class="text-xs text-gray-500 font-mono">Cédula: #---</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Finca asignada:</span>
                                <span id="previewFincaNombre" class="font-bold text-gray-900 text-right truncate">No especificada</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Cargo / Rol:</span>
                                <span id="previewCargoNombre" class="font-bold text-purple-700 text-right">No seleccionado</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Estado inicial:</span>
                                <span class="font-bold text-emerald-700 text-right">🟢 Activo</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-gray-500">Teléfono:</span>
                                <span id="previewTelefono" class="font-semibold text-gray-800 text-right truncate">---</span>
                            </div>
                        </div>

                        <!-- Action Buttons en el Sidebar -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                💾 Guardar personal
                            </button>
                            <a href="{{ route('personal-finca.index') }}"
                               class="w-full py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm flex items-center justify-center">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fincaSelect = document.getElementById('finca_id');
    const tipoSelect = document.getElementById('tipo_trabajador_id');
    const cedulaInput = document.getElementById('cedula');
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const telefonoInput = document.getElementById('telefono');

    const previewAvatar = document.getElementById('previewAvatar');
    const previewNombreCompleto = document.getElementById('previewNombreCompleto');
    const previewCedula = document.getElementById('previewCedula');
    const previewFincaNombre = document.getElementById('previewFincaNombre');
    const previewCargoNombre = document.getElementById('previewCargoNombre');
    const previewTelefono = document.getElementById('previewTelefono');

    function updatePreview() {
        const nom = (nombreInput ? nombreInput.value : '').trim();
        const ape = (apellidoInput ? apellidoInput.value : '').trim();
        const full = [nom, ape].filter(Boolean).join(' ');

        if (previewNombreCompleto) {
            previewNombreCompleto.textContent = full || 'Nuevo personal';
        }

        if (previewAvatar) {
            previewAvatar.textContent = (nom || ape || 'P').charAt(0).toUpperCase();
        }

        const ced = (cedulaInput ? cedulaInput.value : '').trim();
        if (previewCedula) {
            previewCedula.textContent = ced ? ('Cédula: #' + ced.toUpperCase()) : 'Cédula: #---';
        }

        const tel = (telefonoInput ? telefonoInput.value : '').trim();
        if (previewTelefono) {
            previewTelefono.textContent = tel || '---';
        }

        const optFinca = fincaSelect ? fincaSelect.options[fincaSelect.selectedIndex] : null;
        if (optFinca && optFinca.value) {
            const fNom = optFinca.dataset.nombre || optFinca.textContent.replace(/🏡|\(.*\)/g, '').trim();
            if (previewFincaNombre) previewFincaNombre.textContent = fNom;
        } else {
            if (previewFincaNombre) previewFincaNombre.textContent = 'No especificada';
        }

        const optTipo = tipoSelect ? tipoSelect.options[tipoSelect.selectedIndex] : null;
        if (optTipo && optTipo.value) {
            const tNom = optTipo.dataset.nombre || optTipo.textContent.replace(/💼/g, '').trim();
            if (previewCargoNombre) previewCargoNombre.textContent = tNom;
        } else {
            if (previewCargoNombre) previewCargoNombre.textContent = 'No seleccionado';
        }
    }

    fincaSelect?.addEventListener('change', updatePreview);
    tipoSelect?.addEventListener('change', updatePreview);
    cedulaInput?.addEventListener('input', updatePreview);
    nombreInput?.addEventListener('input', updatePreview);
    apellidoInput?.addEventListener('input', updatePreview);
    telefonoInput?.addEventListener('input', updatePreview);

    updatePreview();
});
</script>
@endsection