@extends('layouts.authenticated')

@section('title', 'Editar Personal de Finca - GanaderaSoft')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('personal-finca.index') }}" 
               class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-ganaderasoft-negro">
                👥 Editar Personal: {{ $personal['Nombre'] }} {{ $personal['Apellido'] }}
            </h1>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-md">
            <!-- Header del formulario -->
            <div class="bg-ganaderasoft-verde text-white px-6 py-4 rounded-t-lg">
                <h2 class="text-lg font-semibold">Modificar Información del Personal</h2>
                <p class="text-sm opacity-90 mt-1">Actualice los datos del personal de la finca</p>
            </div>

            <form action="{{ route('personal-finca.update', $personal['id_Tecnico']) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Mensajes de error generales -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">Hay errores en el formulario:</p>
                                <ul class="ml-6 list-disc">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información Personal -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-ganaderasoft-negro border-b pb-2">Datos Personales</h3>

                        <!-- Cédula -->
                        <div>
                            <label for="Cedula" class="block text-sm font-medium text-gray-700 mb-1">
                                Cédula de Identidad <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Cedula" id="Cedula" 
                                   value="{{ old('Cedula', $personal['Cedula']) }}"
                                   class="form-input w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Cedula') border-red-500 @enderror"
                                   placeholder="Ej: 12345678"
                                   maxlength="12"
                                   pattern="[0-9]{7,12}"
                                   required>
                            @error('Cedula')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Solo números, entre 7 y 12 dígitos</p>
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label for="Nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Nombre" id="Nombre" 
                                   value="{{ old('Nombre', $personal['Nombre']) }}"
                                   class="form-input w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Nombre') border-red-500 @enderror"
                                   placeholder="Ej: Juan Carlos"
                                   maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+"
                                   required>
                            @error('Nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Solo letras y espacios (máximo 50 caracteres)</p>
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label for="Apellido" class="block text-sm font-medium text-gray-700 mb-1">
                                Apellido <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Apellido" id="Apellido" 
                                   value="{{ old('Apellido', $personal['Apellido']) }}"
                                   class="form-input w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Apellido') border-red-500 @enderror"
                                   placeholder="Ej: García Pérez"
                                   maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+"
                                   required>
                            @error('Apellido')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Solo letras y espacios (máximo 50 caracteres)</p>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label for="Fecha_Nacimiento" class="block text-sm font-medium text-gray-700 mb-1">
                                Fecha de Nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="Fecha_Nacimiento" id="Fecha_Nacimiento" 
                                   value="{{ old('Fecha_Nacimiento', $personal['Fecha_Nacimiento']) }}"
                                   class="form-input w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Fecha_Nacimiento') border-red-500 @enderror"
                                   min="1940-01-01"
                                   max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                   required>
                            @error('Fecha_Nacimiento')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Debe ser mayor de 18 años</p>
                        </div>
                    </div>

                    <!-- Información de Contacto y Trabajo -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-ganaderasoft-negro border-b pb-2">Contacto y Trabajo</h3>

                        <!-- Teléfono -->
                        <div>
                            <label for="Telefono" class="block text-sm font-medium text-gray-700 mb-1">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="Telefono" id="Telefono" 
                                   value="{{ old('Telefono', $personal['Telefono']) }}"
                                   class="form-input w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Telefono') border-red-500 @enderror"
                                   placeholder="Ej: 04161234567"
                                   pattern="[0-9]{10,12}"
                                   maxlength="12"
                                   required>
                            @error('Telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">10-12 dígitos, formato venezolano (sin espacios)</p>
                        </div>

                        <!-- Correo -->
                        <div>
                            <label for="Correo" class="block text-sm font-medium text-gray-700 mb-1">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="Correo" id="Correo" 
                                   value="{{ old('Correo', $personal['Correo']) }}"
                                   class="form-input w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Correo') border-red-500 @enderror"
                                   placeholder="ejemplo@correo.com"
                                   maxlength="100"
                                   required>
                            @error('Correo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo de Trabajador -->
                        <div>
                            <label for="Tipo_Trabajador" class="block text-sm font-medium text-gray-700 mb-1">
                                Tipo de Trabajador <span class="text-red-500">*</span>
                            </label>
                            <select name="Tipo_Trabajador" id="Tipo_Trabajador" 
                                    class="form-select w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Tipo_Trabajador') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccione un tipo</option>
                                <option value="Técnico" {{ (old('Tipo_Trabajador', $personal['Tipo_Trabajador']) == 'Técnico') ? 'selected' : '' }}>🔧 Técnico</option>
                                <option value="Veterinario" {{ (old('Tipo_Trabajador', $personal['Tipo_Trabajador']) == 'Veterinario') ? 'selected' : '' }}>🏥 Veterinario</option>
                                <option value="Operario" {{ (old('Tipo_Trabajador', $personal['Tipo_Trabajador']) == 'Operario') ? 'selected' : '' }}>👷 Operario</option>
                                <option value="Vigilante" {{ (old('Tipo_Trabajador', $personal['Tipo_Trabajador']) == 'Vigilante') ? 'selected' : '' }}>🛡️ Vigilante</option>
                                <option value="Supervisor" {{ (old('Tipo_Trabajador', $personal['Tipo_Trabajador']) == 'Supervisor') ? 'selected' : '' }}>👨‍💼 Supervisor</option>
                                <option value="Administrador" {{ (old('Tipo_Trabajador', $personal['Tipo_Trabajador']) == 'Administrador') ? 'selected' : '' }}>👤 Administrador</option>
                            </select>
                            @error('Tipo_Trabajador')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Finca -->
                        <div>
                            <label for="id_Finca" class="block text-sm font-medium text-gray-700 mb-1">
                                Finca <span class="text-red-500">*</span>
                            </label>
                            <select name="id_Finca" id="id_Finca" 
                                    class="form-select w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('id_Finca') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccione una finca</option>
                                @foreach($fincas as $finca)
                                    <option value="{{ $finca['id_Finca'] }}" 
                                            {{ (old('id_Finca', $personal['id_Finca']) == $finca['id_Finca']) ? 'selected' : '' }}>
                                        {{ $finca['Nombre'] }} - {{ $finca['Municipio'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_Finca')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fecha de Ingreso -->
                        <div>
                            <label for="Fecha_Ingreso" class="block text-sm font-medium text-gray-700 mb-1">
                                Fecha de Ingreso <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="Fecha_Ingreso" id="Fecha_Ingreso" 
                                   value="{{ old('Fecha_Ingreso', $personal['Fecha_Ingreso']) }}"
                                   class="form-input w-full border-gray-300 rounded-md focus:border-ganaderasoft-celeste focus:ring-ganaderasoft-celeste @error('Fecha_Ingreso') border-red-500 @enderror"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('Fecha_Ingreso')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">No puede ser una fecha futura</p>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">📋 Información del Registro</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
                        <div>
                            <span class="font-medium">ID del Personal:</span> {{ $personal['id_Tecnico'] }}
                        </div>
                        <div>
                            <span class="font-medium">Registrado el:</span> 
                            {{ date('d/m/Y', strtotime($personal['created_at'] ?? date('Y-m-d'))) }}
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('personal-finca.show', $personal['id_Tecnico']) }}" 
                       class="text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80 font-medium">
                        👁️ Ver Detalles
                    </a>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('personal-finca.index') }}" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-ganaderasoft-verde text-white rounded-md hover:bg-ganaderasoft-verde/90 focus:outline-none focus:ring-2 focus:ring-ganaderasoft-verde transition-colors">
                            ✏️ Actualizar Personal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para validaciones en tiempo real -->
    <script>
        // Validación de cédula en tiempo real
        document.getElementById('Cedula').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Solo números
            e.target.value = value;
        });

        // Validación de teléfono en tiempo real
        document.getElementById('Telefono').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Solo números
            e.target.value = value;
        });

        // Validación de nombres (solo letras)
        function validarSoloLetras(e) {
            let value = e.target.value.replace(/[^a-zA-ZáéíóúñÁÉÍÓÚÑ\s]/g, '');
            e.target.value = value;
        }

        document.getElementById('Nombre').addEventListener('input', validarSoloLetras);
        document.getElementById('Apellido').addEventListener('input', validarSoloLetras);

        // Validar edad mínima en fecha de nacimiento
        document.getElementById('Fecha_Nacimiento').addEventListener('change', function(e) {
            const fechaNacimiento = new Date(e.target.value);
            const fechaActual = new Date();
            const edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
            
            if (edad < 18) {
                alert('El personal debe ser mayor de 18 años.');
                e.target.value = '{{ $personal['Fecha_Nacimiento'] }}';
            }
        });

        // Prevenir envío del formulario con datos inválidos
        document.querySelector('form').addEventListener('submit', function(e) {
            const cedula = document.getElementById('Cedula').value;
            const telefono = document.getElementById('Telefono').value;
            
            if (cedula.length < 7 || cedula.length > 12) {
                alert('La cédula debe tener entre 7 y 12 dígitos.');
                e.preventDefault();
                return;
            }
            
            if (telefono.length < 10 || telefono.length > 12) {
                alert('El teléfono debe tener entre 10 y 12 dígitos.');
                e.preventDefault();
                return;
            }
        });

        // Confirmación antes de enviar cambios
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('¿Está seguro de que desea actualizar la información de este personal?')) {
                e.preventDefault();
            }
        });
    </script>
@endsection