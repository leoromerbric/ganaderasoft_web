@extends('layouts.authenticated')

@section('title', 'Importación masiva de animales')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl shadow-xs">
                📥
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Importación masiva de animales
                </h1>
                <p class="text-gray-500 text-sm mt-1">Cargue lotes de ganado a rebaños a partir de archivos delimitados (.csv o .txt)</p>
            </div>
        </div>
        <div>
            <a href="{{ route('animales.index') }}"
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-xl">✅</span>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-5 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm space-y-3">
            <div class="flex items-center space-x-3">
                <span class="text-xl">⚠️</span>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
            @if(session('import_errors') && is_array(session('import_errors')) && count(session('import_errors')) > 0)
                <div class="mt-3 pl-8 text-xs space-y-1.5 border-t border-red-200 pt-3">
                    <p class="font-semibold text-red-900 mb-1">Detalles de los errores detectados en el archivo:</p>
                    <ul class="list-disc list-inside space-y-1 text-red-700 max-h-48 overflow-y-auto pr-2">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm">
            <p class="text-sm font-bold mb-1">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container con Grid 2/3 + 1/3 de Usuarios -->
    <form action="{{ route('animales.importar.procesar') }}" method="POST" enctype="multipart/form-data" id="formImportar" novalidate>
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Columna Izquierda: Formulario y Guía (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card 1: Configuración de Carga -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📋</span> Configuración de archivo
                    </h3>

                    <div class="space-y-6">
                        <!-- Finca de Destino -->
                        <div>
                            <label for="finca_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Finca de destino <span class="text-red-500">*</span>
                            </label>
                            <select id="finca_id" name="finca_id" required
                                    class="w-full px-4 py-3 border @error('finca_id') border-red-500 ring-2 ring-red-100 bg-red-50/30 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="">Seleccione una finca...</option>
                                @foreach($fincas as $finca)
                                    @php
                                        $fId = $finca['id'] ?? null;
                                        $fNombre = $finca['nombre'] ?? ('Finca #' . $fId);
                                        $selected = (string)old('finca_id', $idFinca) === (string)$fId;
                                    @endphp
                                    <option value="{{ $fId }}" {{ $selected ? 'selected' : '' }}>
                                        {{ $fNombre }} (ID: #{{ $fId }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1.5">Todos los rebaños y animales importados se asignarán a esta finca.</p>
                            @error('finca_id')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Drag & Drop File Zone -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Archivo delimitado (.csv o .txt) <span class="text-red-500">*</span>
                            </label>
                            <div id="dropZone"
                                 class="border-2 border-dashed border-gray-300 hover:border-ganaderasoft-celeste rounded-2xl p-8 text-center bg-gray-50/50 hover:bg-ganaderasoft-celeste/5 transition-all cursor-pointer">
                                <input type="file" name="archivo" id="archivo" accept=".csv,.txt,text/plain,text/csv" required class="hidden">
                                
                                <div id="dropPrompt" class="space-y-3">
                                    <div class="w-16 h-16 mx-auto rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center text-3xl shadow-xs">
                                        📄
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">
                                            Haz clic para seleccionar o arrastra y suelta tu archivo aquí
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Formatos permitidos: .csv o .txt delimitados por coma (,) o punto y coma (;) &bull; Máximo 10MB
                                        </p>
                                    </div>
                                </div>

                                <div id="fileSelectedInfo" class="hidden space-y-2">
                                    <div class="inline-flex items-center space-x-2 px-4 py-2 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-sm font-semibold shadow-xs">
                                        <span>📄</span>
                                        <span id="fileName">archivo.csv</span>
                                        <span id="fileSize" class="text-xs text-emerald-600 font-normal"></span>
                                    </div>
                                    <p class="text-xs text-gray-500">Haz clic aquí si deseas cambiar el archivo seleccionado</p>
                                </div>
                            </div>
                            @error('archivo')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Especificaciones del Archivo -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>💡</span> Especificaciones y estructura de columnas
                    </h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        El archivo debe incluir una primera fila de encabezados exactos. El sistema detectará automáticamente si las columnas están delimitadas por comas (,) o puntos y coma (;).
                    </p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-xs border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-gray-500 font-semibold uppercase tracking-wider">
                                <tr>
                                    <th class="p-3.5">Columna</th>
                                    <th class="p-3.5">Obligatorio</th>
                                    <th class="p-3.5">Descripción / Valores</th>
                                    <th class="p-3.5">Ejemplo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 text-gray-600">
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-3.5 font-mono font-bold text-gray-900">codigo_animal</td>
                                    <td class="p-3.5 text-amber-600 font-bold">Recomendado</td>
                                    <td class="p-3.5">Código único identificador.</td>
                                    <td class="p-3.5 font-mono text-gray-500">BOV-001</td>
                                </tr>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-3.5 font-mono font-bold text-gray-900">nombre</td>
                                    <td class="p-3.5 text-amber-600 font-bold">Recomendado</td>
                                    <td class="p-3.5">Nombre o alias del ejemplar.</td>
                                    <td class="p-3.5 font-mono text-gray-500">Mariposa</td>
                                </tr>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-3.5 font-mono font-bold text-gray-900">sexo</td>
                                    <td class="p-3.5 text-red-600 font-bold">Sí</td>
                                    <td class="p-3.5">Sexo: <span class="font-semibold text-gray-800">M</span> (Macho) o <span class="font-semibold text-gray-800">H</span> (Hembra).</td>
                                    <td class="p-3.5 font-mono text-gray-500">H</td>
                                </tr>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-3.5 font-mono font-bold text-gray-900">fecha_nacimiento</td>
                                    <td class="p-3.5 text-red-600 font-bold">Sí</td>
                                    <td class="p-3.5">Formato <span class="font-semibold text-gray-800">YYYY-MM-DD</span> o <span class="font-semibold text-gray-800">DD/MM/YYYY</span>.</td>
                                    <td class="p-3.5 font-mono text-gray-500">2023-03-15</td>
                                </tr>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-3.5 font-mono font-bold text-gray-900">procedencia</td>
                                    <td class="p-3.5 text-gray-400">Opcional</td>
                                    <td class="p-3.5">Origen del ejemplar.</td>
                                    <td class="p-3.5 font-mono text-gray-500">Nacido en finca</td>
                                </tr>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-3.5 font-mono font-bold text-gray-900">rebano</td>
                                    <td class="p-3.5 text-gray-400">Opcional</td>
                                    <td class="p-3.5">Nombre del rebaño (crea si no existe).</td>
                                    <td class="p-3.5 font-mono text-gray-500">Lote Ordeño A</td>
                                </tr>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-3.5 font-mono font-bold text-gray-900">raza</td>
                                    <td class="p-3.5 text-gray-400">Opcional</td>
                                    <td class="p-3.5">Nombre o siglas de la raza.</td>
                                    <td class="p-3.5 font-mono text-gray-500">Carora</td>
                                </tr>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-3.5 font-mono font-bold text-gray-900">estado_salud</td>
                                    <td class="p-3.5 text-gray-400">Opcional</td>
                                    <td class="p-3.5">Estado inicial (por defecto Sano).</td>
                                    <td class="p-3.5 font-mono text-gray-500">Sano</td>
                                </tr>
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-3.5 font-mono font-bold text-gray-900">peso</td>
                                    <td class="p-3.5 text-gray-400">Opcional</td>
                                    <td class="p-3.5">Peso inicial en kg (numérico).</td>
                                    <td class="p-3.5 font-mono text-gray-500">420.5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Panel Lateral y Acciones (1 Tercio) -->
            <div class="space-y-6">
                
                <!-- Card 1: Plantillas Descargables -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>📥</span> Descargar plantillas
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Descarga un archivo base con los encabezados y ejemplos preconfigurados listos para rellenar:
                        </p>
                        <div class="space-y-2 pt-1">
                            <a href="{{ route('animales.plantilla', ['delimitador' => 'coma']) }}"
                               class="w-full px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-xl text-xs flex items-center justify-between border border-blue-100 transition-colors">
                                <span>📄 Plantilla CSV (por comas)</span>
                                <span>⬇️</span>
                            </a>
                            <a href="{{ route('animales.plantilla', ['delimitador' => 'punto_coma']) }}"
                               class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs flex items-center justify-between border border-slate-200 transition-colors">
                                <span>📄 Plantilla CSV (por punto y coma)</span>
                                <span>⬇️</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Resumen y Botones de Acción -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center gap-2">
                            <span>⚙️</span> Procesar importación
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Finca destino:</span>
                                <span id="previewFinca" class="font-bold text-gray-900 truncate max-w-[150px] text-right">No seleccionada</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Archivo:</span>
                                <span id="previewArchivo" class="font-semibold text-gray-900 truncate max-w-[150px] text-right">Sin archivo</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2 cursor-pointer">
                                <span>+</span> Importar animales
                            </button>
                            <a href="{{ route('animales.index') }}"
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
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('archivo');
    const dropPrompt = document.getElementById('dropPrompt');
    const fileSelectedInfo = document.getElementById('fileSelectedInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const previewFinca = document.getElementById('previewFinca');
    const previewArchivo = document.getElementById('previewArchivo');
    const fincaSelect = document.getElementById('finca_id');

    function updateFincaPreview() {
        if (fincaSelect.value && fincaSelect.selectedIndex >= 0) {
            previewFinca.textContent = fincaSelect.options[fincaSelect.selectedIndex].text.split('(')[0].trim();
        } else {
            previewFinca.textContent = 'No seleccionada';
        }
    }

    fincaSelect.addEventListener('change', updateFincaPreview);
    updateFincaPreview();

    dropZone.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            mostrarArchivo(fileInput.files[0]);
        }
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-ganaderasoft-celeste', 'bg-ganaderasoft-celeste/10');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-ganaderasoft-celeste', 'bg-ganaderasoft-celeste/10');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-ganaderasoft-celeste', 'bg-ganaderasoft-celeste/10');

        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            mostrarArchivo(e.dataTransfer.files[0]);
        }
    });

    function mostrarArchivo(file) {
        fileName.textContent = file.name;
        previewArchivo.textContent = file.name;
        const sizeKB = (file.size / 1024).toFixed(1);
        fileSize.textContent = `(${sizeKB} KB)`;
        dropPrompt.classList.add('hidden');
        fileSelectedInfo.classList.remove('hidden');
    }
</script>
@endsection
