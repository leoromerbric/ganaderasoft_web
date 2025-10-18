@extends('layouts.authenticated')

@section('title', 'Crear Animal')

@section('content')
    <div>
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">Crear Nuevo Animal</h2>
            <p class="text-gray-600 mt-1">Registra un nuevo animal en el sistema</p>
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
            <form action="{{ route('animales.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Rebaño -->
                    <div>
                        <label for="id_Rebano" class="block text-sm font-medium text-gray-700 mb-2">
                            Rebaño <span class="text-red-500">*</span>
                        </label>
                        <select id="id_Rebano" name="id_Rebano" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione un rebaño</option>
                            @foreach($rebanos as $rebano)
                                <option value="{{ $rebano['id_Rebano'] }}" {{ old('id_Rebano') == $rebano['id_Rebano'] ? 'selected' : '' }}>
                                    {{ $rebano['Nombre'] }} - {{ $rebano['finca']['Nombre'] ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_Rebano')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="Nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="Nombre" name="Nombre" value="{{ old('Nombre') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent"
                               placeholder="Ej: Vaca Lechera #1">
                        @error('Nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Código -->
                    <div>
                        <label for="codigo_animal" class="block text-sm font-medium text-gray-700 mb-2">
                            Código <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="codigo_animal" name="codigo_animal" value="{{ old('codigo_animal') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent"
                               placeholder="Ej: ANIMAL-001">
                        @error('codigo_animal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sexo -->
                    <div>
                        <label for="Sexo" class="block text-sm font-medium text-gray-700 mb-2">
                            Sexo <span class="text-red-500">*</span>
                        </label>
                        <select id="Sexo" name="Sexo" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione el sexo</option>
                            <option value="M" {{ old('Sexo') == 'M' ? 'selected' : '' }}>Macho</option>
                            <option value="F" {{ old('Sexo') == 'F' ? 'selected' : '' }}>Hembra</option>
                        </select>
                        @error('Sexo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Nacimiento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                        @error('fecha_nacimiento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Procedencia -->
                    <div>
                        <label for="Procedencia" class="block text-sm font-medium text-gray-700 mb-2">
                            Procedencia <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="Procedencia" name="Procedencia" value="{{ old('Procedencia') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent"
                               placeholder="Ej: Local, Importado">
                        @error('Procedencia')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Raza -->
                    <div>
                        <label for="fk_composicion_raza" class="block text-sm font-medium text-gray-700 mb-2">
                            Raza <span class="text-red-500">*</span>
                        </label>
                        <select id="fk_composicion_raza" name="fk_composicion_raza" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione una raza</option>
                            @foreach($razas as $raza)
                                <option value="{{ $raza['id_Composicion'] }}" {{ old('fk_composicion_raza') == $raza['id_Composicion'] ? 'selected' : '' }}>
                                    {{ $raza['Nombre'] }} ({{ $raza['Siglas'] ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('fk_composicion_raza')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado de Salud Inicial -->
                    <div>
                        <label for="estado_inicial_estado_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado de Salud Inicial <span class="text-red-500">*</span>
                        </label>
                        <select id="estado_inicial_estado_id" name="estado_inicial[estado_id]" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione un estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado['estado_id'] }}" {{ old('estado_inicial.estado_id') == $estado['estado_id'] ? 'selected' : '' }}>
                                    {{ $estado['estado_nombre'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado_inicial.estado_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha Estado Inicial -->
                    <div>
                        <label for="estado_inicial_fecha_ini" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha Estado Inicial <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="estado_inicial_fecha_ini" name="estado_inicial[fecha_ini]" value="{{ old('estado_inicial.fecha_ini') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                        @error('estado_inicial.fecha_ini')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Etapa Inicial -->
                    <div>
                        <label for="etapa_inicial_etapa_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Etapa Inicial <span class="text-red-500">*</span>
                        </label>
                        <select id="etapa_inicial_etapa_id" name="etapa_inicial[etapa_id]" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                            <option value="">Seleccione una etapa</option>
                            @foreach($etapas as $etapa)
                                <option value="{{ $etapa['etapa_id'] }}" {{ old('etapa_inicial.etapa_id') == $etapa['etapa_id'] ? 'selected' : '' }}>
                                    {{ $etapa['etapa_nombre'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('etapa_inicial.etapa_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha Etapa Inicial -->
                    <div>
                        <label for="etapa_inicial_fecha_ini" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha Etapa Inicial <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="etapa_inicial_fecha_ini" name="etapa_inicial[fecha_ini]" value="{{ old('etapa_inicial.fecha_ini') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent">
                        @error('etapa_inicial.fecha_ini')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('animales.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        Crear Animal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
