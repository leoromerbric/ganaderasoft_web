@extends('layouts.authenticated')

@section('title', 'Crear ' . $catalog['name'])

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-2xl">
                📋
            </div>
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    Crear registro
                </h1>
                <p class="text-gray-500 text-sm mt-1">Nuevo registro en {{ $catalog['name'] }}</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.'.$catalog['slug'].'.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-lg">⚠️</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.'.$catalog['slug'].'.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2 Tercios) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card: Información del registro -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h3 class="text-xl font-bold text-ganaderasoft-negro border-b border-gray-100 pb-3 flex items-center gap-2">
                        <span>📝</span> Datos del registro
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nombre de la vacuna <span class="text-red-500">*</span></label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('nombre')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="descripcion" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Descripción <span class="text-red-500">*</span></label>
                            <input type="text" id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                            @error('descripcion')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="activa" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Estado (Activa)</label>
                            <select id="activa" name="activa" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all">
                                <option value="1" {{ old('activa', '1') == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('activa') == '0' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('activa')<p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de Ficha en Vivo (1 Tercio) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-slate-100 border-b border-slate-200 text-slate-800 px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📋</span> Resumen
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Preview Icono e Identificación -->
                        <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-2xl flex items-center space-x-3">
                            <div id="previewIcono" class="w-12 h-12 rounded-xl bg-white border border-blue-200 text-blue-700 font-bold flex items-center justify-center text-2xl shadow-xs uppercase">
                                #
                            </div>
                            <div>
                                <p id="previewNombre" class="text-base font-bold text-gray-900">Sin registro</p>
                                <p class="text-xs text-gray-500">Nuevo registro</p>
                            </div>
                        </div>

                        <!-- Mini Stats Preview -->
                        <div class="space-y-3 text-xs text-gray-600 border-b border-gray-100 pb-4">

                            <div class="flex justify-between">
                                <span>Descripción:</span>
                                <span id="previewDescripcion" class="font-bold text-gray-900">N/A</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estado (Activa):</span>
                                <span id="previewActiva" class="font-bold text-gray-900">N/A</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                    class="w-full py-3.5 bg-ganaderasoft-verde-oscuro hover:bg-opacity-90 text-white font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                💾 Guardar
                            </button>
                            <a href="{{ route('admin.'.$catalog['slug'].'.index') }}"
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
    const nombreInput = document.getElementById('nombre');
    const previewNombre = document.getElementById('previewNombre');
    const descripcionInput = document.getElementById('descripcion');
    const previewDescripcion = document.getElementById('previewDescripcion');
    const activaInput = document.getElementById('activa');
    const previewActiva = document.getElementById('previewActiva');
    
    const previewNombre = document.getElementById('previewNombre');
    const previewIcono  = document.getElementById('previewIcono');

    function updatePreview() {

        const nombreVal = nombreInput?.value.trim() || '';
        if (previewNombre) previewNombre.textContent = nombreVal || 'Sin registro';
        if (previewIcono) previewIcono.textContent = nombreVal ? nombreVal.charAt(0).toUpperCase() : '#';

        const descripcionVal = descripcionInput?.value.trim();
        if (previewDescripcion) previewDescripcion.textContent = descripcionVal || 'N/A';

        const activaVal = activaInput?.value;
        if (previewActiva) previewActiva.textContent = activaVal == '1' ? 'Activo' : 'Inactivo';

    }

    nombreInput?.addEventListener('input', updatePreview);
    nombreInput?.addEventListener('change', updatePreview);
    descripcionInput?.addEventListener('input', updatePreview);
    descripcionInput?.addEventListener('change', updatePreview);
    activaInput?.addEventListener('input', updatePreview);
    activaInput?.addEventListener('change', updatePreview);
    
    updatePreview();
});
</script>
@endsection