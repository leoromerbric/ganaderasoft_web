@extends('layouts.authenticated')

@section('title', 'Importación Masiva de Animales')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header & Breadcrumb -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Importación Masiva de Animales</h1>
                <p class="text-gray-500 text-sm mt-1">Cargue lotes de ganado a rebaños a partir de archivos delimitados (.csv o .txt)</p>
            </div>
            <a href="{{ route('animales.index') }}"
               class="inline-flex items-center text-sm text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-medium transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a Animales
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-xl">✅</span>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-5 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-2xl shadow-sm space-y-3">
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
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-8">
            <form action="{{ route('animales.importar.procesar') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="formImportar">
                @csrf

                <!-- Finca de Destino -->
                <div>
                    <label for="finca_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Finca de Destino <span class="text-red-500">*</span>
                    </label>
                    <select id="finca_id" name="finca_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
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
                    <p class="text-xs text-gray-500 mt-1">Todos los rebaños y animales importados se asignarán a esta finca.</p>
                </div>

                <!-- Drag & Drop File Zone -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Archivo Delimitado (.csv o .txt) <span class="text-red-500">*</span>
                    </label>
                    <div id="dropZone"
                         class="border-2 border-dashed border-gray-300 hover:border-ganaderasoft-verde-oscuro rounded-2xl p-8 text-center bg-gray-50/50 hover:bg-green-50/30 transition-all cursor-pointer">
                        <input type="file" name="archivo" id="archivo" accept=".csv,.txt,text/plain,text/csv" required class="hidden">
                        
                        <div id="dropPrompt" class="space-y-3">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-ganaderasoft-celeste/15 flex items-center justify-center text-3xl">
                                📄
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">
                                    Haz clic para seleccionar o arrastra y suelta tu archivo aquí
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Formatos permitidos: .csv, .txt con delimitador por comas (,) o punto y coma (;) • Máximo 10MB
                                </p>
                            </div>
                        </div>

                        <div id="fileSelectedInfo" class="hidden space-y-2">
                            <div class="inline-flex items-center space-x-2 px-4 py-2 bg-green-100 text-green-800 rounded-xl text-sm font-medium">
                                <span>📄</span>
                                <span id="fileName">archivo.csv</span>
                                <span id="fileSize" class="text-xs text-green-600 font-normal"></span>
                            </div>
                            <p class="text-xs text-gray-500">Haz clic para cambiar el archivo</p>
                        </div>
                    </div>
                </div>

                <!-- Actions & Template download -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-6 border-t border-gray-100">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500">Plantillas de ejemplo:</span>
                        <a href="{{ route('animales.plantilla', ['delimitador' => 'coma']) }}"
                           class="text-xs font-semibold text-ganaderasoft-azul hover:text-ganaderasoft-celeste hover:underline flex items-center">
                            📥 CSV (Coma)
                        </a>
                        <span class="text-gray-300">•</span>
                        <a href="{{ route('animales.plantilla', ['delimitador' => 'punto_coma']) }}"
                           class="text-xs font-semibold text-ganaderasoft-azul hover:text-ganaderasoft-celeste hover:underline flex items-center">
                            📥 CSV (Punto y coma)
                        </a>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('animales.index') }}"
                           class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-7 py-3 bg-ganaderasoft-verde-oscuro text-white text-sm font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center">
                            + Importar Animales
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Guide / Specifications Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-4">
            <h3 class="text-base font-bold text-ganaderasoft-negro flex items-center">
                <span class="mr-2">💡</span> Especificaciones del Archivo
            </h3>
            <p class="text-xs text-gray-600 leading-relaxed">
                El archivo debe incluir una primera fila de encabezados. El sistema detectará automáticamente si las columnas están separadas por coma (,) o por punto y coma (;).
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-gray-100 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-700 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="p-3 border-b border-gray-100">Columna</th>
                            <th class="p-3 border-b border-gray-100">Obligatorio</th>
                            <th class="p-3 border-b border-gray-100">Descripción / Valores Permitidos</th>
                            <th class="p-3 border-b border-gray-100">Ejemplo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-600">
                        <tr>
                            <td class="p-3 font-mono font-bold text-gray-800">codigo_animal</td>
                            <td class="p-3 text-red-500 font-medium">Recomendado</td>
                            <td class="p-3">Código identificador único para el animal.</td>
                            <td class="p-3 font-mono text-gray-500">AN-001</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-mono font-bold text-gray-800">nombre</td>
                            <td class="p-3 text-red-500 font-medium">Recomendado</td>
                            <td class="p-3">Nombre o alias asignado al animal.</td>
                            <td class="p-3 font-mono text-gray-500">Vaca Mariposa</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-mono font-bold text-gray-800">sexo</td>
                            <td class="p-3 text-red-600 font-bold">Sí</td>
                            <td class="p-3">Sexo biológico: <span class="font-semibold text-gray-700">M</span> (Macho) o <span class="font-semibold text-gray-700">H</span> (Hembra).</td>
                            <td class="p-3 font-mono text-gray-500">H</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-mono font-bold text-gray-800">fecha_nacimiento</td>
                            <td class="p-3 text-red-600 font-bold">Sí</td>
                            <td class="p-3">Fecha de nacimiento en formato <span class="font-semibold text-gray-700">YYYY-MM-DD</span> o <span class="font-semibold text-gray-700">DD/MM/YYYY</span>.</td>
                            <td class="p-3 font-mono text-gray-500">2023-03-15</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-mono font-bold text-gray-800">procedencia</td>
                            <td class="p-3 text-gray-400">Opcional</td>
                            <td class="p-3">Origen del animal (por defecto "Importación Masiva").</td>
                            <td class="p-3 font-mono text-gray-500">Local</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-mono font-bold text-gray-800">rebano</td>
                            <td class="p-3 text-gray-400">Opcional</td>
                            <td class="p-3">Nombre del rebaño. Si no existe en la finca, se creará automáticamente.</td>
                            <td class="p-3 font-mono text-gray-500">Lote Produccion A</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-mono font-bold text-gray-800">raza</td>
                            <td class="p-3 text-gray-400">Opcional</td>
                            <td class="p-3">Nombre o siglas de la raza en catálogo (ej. Holstein, Brahman, Carora).</td>
                            <td class="p-3 font-mono text-gray-500">Holstein</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-mono font-bold text-gray-800">estado_salud</td>
                            <td class="p-3 text-gray-400">Opcional</td>
                            <td class="p-3">Estado de salud inicial (por defecto "Sano").</td>
                            <td class="p-3 font-mono text-gray-500">Sano</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-mono font-bold text-gray-800">peso</td>
                            <td class="p-3 text-gray-400">Opcional</td>
                            <td class="p-3">Peso corporal inicial en kilogramos (numérico).</td>
                            <td class="p-3 font-mono text-gray-500">420.5</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('archivo');
        const dropPrompt = document.getElementById('dropPrompt');
        const fileSelectedInfo = document.getElementById('fileSelectedInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');

        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                mostrarArchivo(fileInput.files[0]);
            }
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-ganaderasoft-verde-oscuro', 'bg-green-50/50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-ganaderasoft-verde-oscuro', 'bg-green-50/50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-ganaderasoft-verde-oscuro', 'bg-green-50/50');

            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                mostrarArchivo(e.dataTransfer.files[0]);
            }
        });

        function mostrarArchivo(file) {
            fileName.textContent = file.name;
            const sizeKB = (file.size / 1024).toFixed(1);
            fileSize.textContent = `(${sizeKB} KB)`;
            dropPrompt.classList.add('hidden');
            fileSelectedInfo.classList.remove('hidden');
        }
    </script>
@endsection
