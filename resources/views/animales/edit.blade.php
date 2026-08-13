@extends('layouts.authenticated')

@section('title', 'Editar Animal')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Editar Animal</h2>
            <p class="text-gray-600 mt-1">Actualiza la información del animal: {{ $animal['nombre'] }}</p>
        </div>

        <!-- Error Messages -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <p class="font-medium mb-2">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="{{ route('animales.update', $animal['id']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Rebaño -->
                    <div>
                        <label for="rebano_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Rebaño <span class="text-red-500">*</span>
                        </label>
                        <select id="rebano_id" name="rebano_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione un rebaño</option>
                            @foreach($rebanos as $rebano)
                                <option value="{{ $rebano['id'] }}" 
                                    {{ (old('rebano_id', $animal['rebano_id']) == $rebano['id']) ? 'selected' : '' }}>
                                    {{ $rebano['nombre'] }} - {{ $rebano['finca']['nombre'] ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('rebano_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $animal['nombre']) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent"
                               placeholder="Ej: Vaca Lechera #1">
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Código -->
                    <div>
                        <label for="codigo_animal" class="block text-sm font-medium text-gray-700 mb-2">
                            Código <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="codigo_animal" name="codigo_animal" value="{{ old('codigo_animal', $animal['codigo_animal']) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent"
                               placeholder="Ej: ANIMAL-001">
                        @error('codigo_animal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- sexo -->
                    <div>
                        <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">
                            sexo <span class="text-red-500">*</span>
                        </label>
                        <select id="sexo" name="sexo" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione el sexo</option>
                            <option value="M" {{ old('sexo', $animal['sexo']) == 'M' ? 'selected' : '' }}>Macho</option>
                            <option value="H" {{ old('sexo', $animal['sexo']) == 'H' ? 'selected' : '' }}>Hembra</option>
                        </select>
                        @error('sexo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Nacimiento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                               value="{{ old('fecha_nacimiento', isset($animal['fecha_nacimiento']) ? date('Y-m-d', strtotime($animal['fecha_nacimiento'])) : '') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                        @error('fecha_nacimiento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- procedencia -->
                    <div>
                        <label for="procedencia" class="block text-sm font-medium text-gray-700 mb-2">
                            procedencia <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="procedencia" name="procedencia" value="{{ old('procedencia', $animal['procedencia']) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent"
                               placeholder="Ej: Local, Importado">
                        @error('procedencia')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Raza -->
                    <div>
                        <label for="composicion_raza_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Raza <span class="text-red-500">*</span>
                        </label>
                        <select id="composicion_raza_id" name="composicion_raza_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione una raza</option>
                            @foreach($razas as $raza)
                                <option value="{{ $raza['id'] }}" 
                                    {{ old('composicion_raza_id', $animal['composicion_raza_id']) == $raza['id'] ? 'selected' : '' }}>
                                    {{ $raza['nombre'] }} ({{ $raza['Siglas'] ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('composicion_raza_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>



                    <!-- Archivado -->
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="archivado" value="1" 
                                   {{ old('archivado', $animal['archivado'] ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-ganaderasoft-celeste border-gray-300 rounded focus:ring-ganaderasoft-celeste">
                            <span class="ml-2 text-sm font-medium text-gray-700">Archivar animal</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('animales.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
