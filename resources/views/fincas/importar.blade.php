@extends('layouts.authenticated')

@section('title', 'Importación Masiva de Fincas')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header & Breadcrumb -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Importación Masiva de Fincas</h1>
                <p class="text-gray-500 text-sm mt-1">Cargue unidades de producción ganadera a partir de archivos delimitados (.csv o .txt)</p>
            </div>
            <a href="{{ route('fincas.index') }}"
               class="inline-flex items-center text-sm text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-medium transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a Fincas
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
            <form action="{{ route('fincas.importar.procesar') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="formImportar">
                @csrf

                @if(!empty($propietarioId))
                    <input type="hidden" name="propietario_id" value="{{ $propietarioId }}">
                @endif

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
                                🏡
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">
                                    Haga clic para seleccionar o arrastre su archivo aquí
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Formatos permitidos: .CSV, .TXT (delimitado por comas, punto y coma o tabulaciones). Tamaño máx: 10MB.
                                </p>
                            </div>
                        </div>

                        <div id="fileSelected" class="hidden space-y-2">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-green-100 text-green-700 flex items-center justify-center text-2xl">
                                📄
                            </div>
                            <p id="fileName" class="text-sm font-bold text-gray-800"></p>
                            <p id="fileSize" class="text-xs text-gray-500"></p>
                            <button type="button" id="btnRemoveFile" class="text-xs text-red-600 hover:underline font-semibold mt-2">
                                Cambiar archivo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('fincas.index') }}" class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-900 font-medium">
                        Cancelar
                    </a>
                    <button type="submit" id="btnSubmit"
                            class="px-8 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-xl font-semibold hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg inline-flex items-center space-x-2">
                        <span>🚀 Procesar Importación</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions & Template Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-ganaderasoft-negro flex items-center gap-2">
                        <span>ℹ️</span> Especificaciones de la Plantilla
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">El sistema detecta automáticamente encabezados y delimitadores (coma o punto y coma)</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('fincas.importar.plantilla', ['delimitador' => 'coma']) }}"
                       class="px-4 py-2 text-xs font-semibold text-ganaderasoft-verde-oscuro bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition-colors inline-flex items-center gap-1.5">
                        <span>📥</span> Descargar Plantilla (Comas)
                    </a>
                    <a href="{{ route('fincas.importar.plantilla', ['delimitador' => 'punto_coma']) }}"
                       class="px-4 py-2 text-xs font-semibold text-gray-700 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors inline-flex items-center gap-1.5">
                        <span>📥</span> Plantilla (Punto y coma)
                    </a>
                </div>
            </div>

            <!-- Columns Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 font-bold text-gray-700 uppercase">Columna</th>
                            <th class="px-4 py-3 font-bold text-gray-700 uppercase">Obligatorio</th>
                            <th class="px-4 py-3 font-bold text-gray-700 uppercase">Tipo / Valores Válidos</th>
                            <th class="px-4 py-3 font-bold text-gray-700 uppercase">Descripción / Sinónimos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-ganaderasoft-azul">nombre</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 bg-red-100 text-red-800 rounded font-semibold text-[10px]">REQUERIDO</span></td>
                            <td class="px-4 py-3 text-gray-600">Texto (máx. 25 caracteres)</td>
                            <td class="px-4 py-3 text-gray-500">Nombre de la finca (ej: <code>Hacienda Santa Ines</code>). Sinónimos: <code>nombre_finca</code>, <code>finca</code>.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-ganaderasoft-azul">explotacion_tipo</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 bg-red-100 text-red-800 rounded font-semibold text-[10px]">REQUERIDO</span></td>
                            <td class="px-4 py-3 text-gray-600"><code>Intensiva</code>, <code>Extensiva</code>, <code>Mixto</code>, <code>Lechero</code>, <code>Ceba</code></td>
                            <td class="px-4 py-3 text-gray-500">Sistema productivo principal. Sinónimos: <code>tipo_explotacion</code>, <code>explotacion</code>.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-gray-700">identificador_hierro</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded font-semibold text-[10px]">OPCIONAL</span></td>
                            <td class="px-4 py-3 text-gray-600">Texto (máx. 20 caracteres)</td>
                            <td class="px-4 py-3 text-gray-500">Identificador del hierro ganadero (ej: <code>HSI-01</code>). Crea automáticamente el registro de hierro.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-gray-700">superficie</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded font-semibold text-[10px]">OPCIONAL</span></td>
                            <td class="px-4 py-3 text-gray-600">Numérico (&ge; 0)</td>
                            <td class="px-4 py-3 text-gray-500">Superficie total en hectáreas (ej: <code>150.5</code>). Sinónimos: <code>hectareas</code>, <code>area</code>.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-gray-700">relieve</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded font-semibold text-[10px]">OPCIONAL</span></td>
                            <td class="px-4 py-3 text-gray-600"><code>Plano</code>, <code>Ondulado</code>, <code>Quebrado</code></td>
                            <td class="px-4 py-3 text-gray-500">Topografía predominante del terreno.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-gray-700">fuente_agua</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded font-semibold text-[10px]">OPCIONAL</span></td>
                            <td class="px-4 py-3 text-gray-600"><code>Rio</code>, <code>Pozo</code>, <code>Quebrada</code>, <code>Represa</code></td>
                            <td class="px-4 py-3 text-gray-500">Principal abastecimiento hídrico de la finca.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-gray-700">suelo_textura</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded font-semibold text-[10px]">OPCIONAL</span></td>
                            <td class="px-4 py-3 text-gray-600"><code>Arenoso</code>, <code>Arcilloso</code>, <code>Franco</code></td>
                            <td class="px-4 py-3 text-gray-500">Tipo textural del suelo.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes -->
            <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-xl text-xs text-amber-900 space-y-1.5">
                <p class="font-bold flex items-center gap-1.5">
                    <span>💡</span> Recomendaciones Importantes:
                </p>
                <ul class="list-disc list-inside space-y-0.5 pl-2">
                    <li>La primera fila puede contener los nombres de las columnas o comenzar directamente con los datos.</li>
                    <li>Si una línea contiene errores de formato, la transacción se revertirá por completo para evitar registros parciales corruptos.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Script de Drag & Drop y UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('archivo');
            const dropPrompt = document.getElementById('dropPrompt');
            const fileSelected = document.getElementById('fileSelected');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const btnRemoveFile = document.getElementById('btnRemoveFile');
            const formImportar = document.getElementById('formImportar');
            const btnSubmit = document.getElementById('btnSubmit');

            // Abrir selector al hacer click en la zona
            dropZone.addEventListener('click', (e) => {
                if (e.target !== btnRemoveFile) {
                    fileInput.click();
                }
            });

            // Drag & Drop visuales
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('border-ganaderasoft-verde-oscuro', 'bg-green-50/50');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('border-ganaderasoft-verde-oscuro', 'bg-green-50/50');
                });
            });

            // Soltar archivo
            dropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelection(files[0]);
                }
            });

            // Selección manual
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    handleFileSelection(fileInput.files[0]);
                }
            });

            function handleFileSelection(file) {
                fileName.textContent = file.name;
                fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
                dropPrompt.classList.add('hidden');
                fileSelected.classList.remove('hidden');
            }

            // Quitar archivo
            btnRemoveFile.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.value = '';
                dropPrompt.classList.remove('hidden');
                fileSelected.classList.add('hidden');
            });

            // Loading state al enviar
            formImportar.addEventListener('submit', () => {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-75', 'cursor-wait');
                btnSubmit.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Procesando Archivo...
                `;
            });
        });
    </script>
@endsection
