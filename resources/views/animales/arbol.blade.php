@extends('layouts.authenticated')

@section('title', 'Árbol Genealógico — ' . ($arbol['animal']['nombre'] ?? 'Animal'))

@section('content')
<div class="space-y-8">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
          
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro flex items-center gap-2">
                    🌳 Árbol genealógico
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Historial genealógico de <span class="font-bold text-gray-800">{{ $arbol['animal']['nombre'] ?? 'Animal' }}</span> 
                    @if($arbol['animal']['codigo_animal'] ?? null)
                        <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-md font-mono">#{{ $arbol['animal']['codigo_animal'] }}</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button onclick="openModal('Padre')"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2">
                <span class="text-base">♂</span> + Asignar padre
            </button>
            <button onclick="openModal('Madre')"
                    class="px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-semibold text-sm transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center gap-2">
                <span class="text-base">♀</span> + Asignar madre
            </button>
            <a href="{{ route('animales.show', $id) }}"
               class="px-5 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ver detalle
            </a>
        </div>
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

    <!-- Árbol Visual Card -->
    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 overflow-x-auto">

        @php
            $abueloP  = $arbol['padre']['abuelo_paterno']  ?? null;
            $abuelaP  = $arbol['padre']['abuela_paterna']  ?? null;
            $abueloM  = $arbol['madre']['abuelo_materno']  ?? null;
            $abuelaM  = $arbol['madre']['abuela_materna']  ?? null;
            $padre    = $arbol['padre'] ?? null;
            $madre    = $arbol['madre'] ?? null;
            $animal   = $arbol['animal'] ?? [];
            $hijos    = $arbol['hijos'] ?? [];
        @endphp

        <div class="tree-container">

            <!-- Fila 1: Abuelos -->
            <div class="tree-row">
                <div class="tree-col">
                    @if($abueloP)
                        <div class="tree-card-parent blue relative">
                            <div class="tree-card-badge blue">Abuelo paterno</div>
                            <a href="{{ route('animales.show', $abueloP['id']) }}" class="block hover:opacity-80 transition-opacity">
                                <p class="font-bold text-gray-900 text-base leading-tight">{{ $abueloP['nombre'] }}</p>
                                @if($abueloP['codigo_animal'] ?? null)<p class="text-xs text-gray-500 mt-0.5">{{ $abueloP['codigo_animal'] }}</p>@endif
                            </a>
                        </div>
                    @else
                        <div class="tree-empty">Sin registro</div>
                    @endif
                </div>
                <div class="tree-col">
                    @if($abuelaP)
                        <div class="tree-card-parent pink relative">
                            <div class="tree-card-badge pink">Abuela paterna</div>
                            <a href="{{ route('animales.show', $abuelaP['id']) }}" class="block hover:opacity-80 transition-opacity">
                                <p class="font-bold text-gray-900 text-base leading-tight">{{ $abuelaP['nombre'] }}</p>
                                @if($abuelaP['codigo_animal'] ?? null)<p class="text-xs text-gray-500 mt-0.5">{{ $abuelaP['codigo_animal'] }}</p>@endif
                            </a>
                        </div>
                    @else
                        <div class="tree-empty">Sin registro</div>
                    @endif
                </div>
                <div class="tree-col">
                    @if($abueloM)
                        <div class="tree-card-parent blue relative">
                            <div class="tree-card-badge blue">Abuelo materno</div>
                            <a href="{{ route('animales.show', $abueloM['id']) }}" class="block hover:opacity-80 transition-opacity">
                                <p class="font-bold text-gray-900 text-base leading-tight">{{ $abueloM['nombre'] }}</p>
                                @if($abueloM['codigo_animal'] ?? null)<p class="text-xs text-gray-500 mt-0.5">{{ $abueloM['codigo_animal'] }}</p>@endif
                            </a>
                        </div>
                    @else
                        <div class="tree-empty">Sin registro</div>
                    @endif
                </div>
                <div class="tree-col">
                    @if($abuelaM)
                        <div class="tree-card-parent pink relative">
                            <div class="tree-card-badge pink">Abuela materna</div>
                            <a href="{{ route('animales.show', $abuelaM['id']) }}" class="block hover:opacity-80 transition-opacity">
                                <p class="font-bold text-gray-900 text-base leading-tight">{{ $abuelaM['nombre'] }}</p>
                                @if($abuelaM['codigo_animal'] ?? null)<p class="text-xs text-gray-500 mt-0.5">{{ $abuelaM['codigo_animal'] }}</p>@endif
                            </a>
                        </div>
                    @else
                        <div class="tree-empty">Sin registro</div>
                    @endif
                </div>
            </div>

            <!-- Conectores abuelos → padres -->
            <div class="tree-connectors-row">
                <div class="tree-connector-pair"></div>
                <div class="tree-connector-pair"></div>
            </div>

            <!-- Fila 2: Padre / Madre -->
            <div class="tree-row">
                <div class="tree-col-2">
                    @if($padre)
                        <div class="tree-card-parent blue relative">
                            <div class="tree-card-badge blue">Padre</div>
                            <a href="{{ route('animales.show', $padre['id']) }}" class="block hover:opacity-80 transition-opacity">
                                <p class="font-bold text-gray-900 text-lg leading-tight">{{ $padre['nombre'] }}</p>
                                @if($padre['codigo_animal'] ?? null)
                                    <p class="text-xs text-gray-500 mt-0.5 font-mono">#{{ $padre['codigo_animal'] }}</p>
                                @endif
                                @if($padre['fecha_nacimiento'] ?? null)
                                    <p class="text-xs text-gray-400 mt-1">Nac. {{ \Carbon\Carbon::parse($padre['fecha_nacimiento'])->format('d/m/Y') }}</p>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('arbol-gen.destroy', [$id, 'Padre']) }}"
                                  onsubmit="return confirm('¿Eliminar relación de Padre?')"
                                  class="mt-3">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700 font-semibold transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Quitar padre
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="tree-card-empty">
                            <p class="text-gray-500 font-medium text-sm mb-2">Sin padre registrado</p>
                            <button onclick="openModal('Padre')"
                                    class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl text-xs font-semibold transition-colors inline-flex items-center gap-1">
                                <span>♂</span> + Asignar padre
                            </button>
                        </div>
                    @endif
                </div>
                <div class="tree-col-2">
                    @if($madre)
                        <div class="tree-card-parent pink relative">
                            <div class="tree-card-badge pink">Madre</div>
                            <a href="{{ route('animales.show', $madre['id']) }}" class="block hover:opacity-80 transition-opacity">
                                <p class="font-bold text-gray-900 text-lg leading-tight">{{ $madre['nombre'] }}</p>
                                @if($madre['codigo_animal'] ?? null)
                                    <p class="text-xs text-gray-500 mt-0.5 font-mono">#{{ $madre['codigo_animal'] }}</p>
                                @endif
                                @if($madre['fecha_nacimiento'] ?? null)
                                    <p class="text-xs text-gray-400 mt-1">Nac. {{ \Carbon\Carbon::parse($madre['fecha_nacimiento'])->format('d/m/Y') }}</p>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('arbol-gen.destroy', [$id, 'Madre']) }}"
                                  onsubmit="return confirm('¿Eliminar relación de Madre?')"
                                  class="mt-3">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700 font-semibold transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Quitar madre
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="tree-card-empty">
                            <p class="text-gray-500 font-medium text-sm mb-2">Sin madre registrada</p>
                            <button onclick="openModal('Madre')"
                                    class="px-4 py-2 bg-pink-50 text-pink-600 hover:bg-pink-100 rounded-xl text-xs font-semibold transition-colors inline-flex items-center gap-1">
                                <span>♀</span> + Asignar madre
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Conector padres → animal -->
            <div class="tree-connectors-center"></div>

            <!-- Fila 3: Animal principal -->
            <div class="tree-row justify-center">
                <div class="tree-col-main">
                    <div class="tree-card-main">
                        <div class="tree-card-badge main">Animal principal</div>
                        <p class="font-extrabold text-2xl text-gray-900 leading-tight mb-1">{{ $animal['nombre'] ?? 'Animal' }}</p>
                        @if($animal['codigo_animal'] ?? null)
                            <p class="text-sm text-gray-500 font-mono">#{{ $animal['codigo_animal'] }}</p>
                        @endif
                        <div class="mt-3 flex items-center justify-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ ($animal['sexo'] ?? '') === 'M' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                {{ ($animal['sexo'] ?? '') === 'M' ? '♂ Macho' : '♀ Hembra' }}
                            </span>
                            @if($animal['fecha_nacimiento'] ?? null)
                                <span class="text-xs text-gray-500 font-medium">Nac. {{ \Carbon\Carbon::parse($animal['fecha_nacimiento'])->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conector animal → hijos -->
            @if(count($hijos) > 0)
            <div class="tree-connectors-center"></div>

            <!-- Fila 4: Hijos -->
            <div class="tree-row flex-wrap justify-center gap-4 mt-0">
                @foreach($hijos as $hijo)
                    <div style="min-width:160px; max-width:200px;">
                        <div class="tree-card-child">
                            <div class="tree-card-badge child">Hijo/a</div>
                            <a href="{{ route('animales.show', $hijo['id']) }}" class="block hover:opacity-80 transition-opacity">
                                <p class="font-bold text-gray-900 text-base leading-tight">{{ $hijo['nombre'] }}</p>
                                @if($hijo['codigo_animal'] ?? null)
                                    <p class="text-xs text-gray-500 font-mono mt-0.5">#{{ $hijo['codigo_animal'] }}</p>
                                @endif
                            </a>
                            <span class="mt-2 inline-block px-2 py-0.5 rounded-full text-xs font-semibold {{ ($hijo['sexo'] ?? '') === 'M' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                {{ ($hijo['sexo'] ?? '') === 'M' ? '♂ Macho' : '♀ Hembra' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <div class="mt-6 text-center text-sm text-gray-400 font-medium">Sin descendencia registrada</div>
            @endif

        </div>
    </div>
</div>

<!-- Modal: Asignar progenitor -->
<div id="modal-progenitor" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl p-6 mx-4 border border-gray-100">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-ganaderasoft-negro" id="modal-title">Asignar padre</h3>
            <button onclick="closeModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center text-2xl leading-none transition-colors">&Times;</button>
        </div>

        <form method="POST" action="{{ route('arbol-gen.store', $id) }}" id="form-progenitor">
            @csrf
            <input type="hidden" name="tipo" id="input-tipo" value="">

            <div class="mb-4">
                <label class="mb-2 block text-xs font-semibold text-gray-600 uppercase tracking-wider">Buscar animal</label>
                <input type="text" id="search-animal" placeholder="Escriba el nombre o código..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste focus:border-transparent transition-all"
                       autocomplete="off">
            </div>

            <div id="lista-disponibles" class="mb-4 max-h-56 overflow-y-auto rounded-xl border border-gray-200 divide-y divide-gray-100">
                <p class="p-4 text-center text-sm text-gray-400">Cargando...</p>
            </div>

            <input type="hidden" name="padre_id" id="input-padre-id" value="">
            <div id="selected-animal" class="mb-4 hidden rounded-xl bg-ganaderasoft-celeste/10 border border-ganaderasoft-celeste/30 p-3 text-sm text-ganaderasoft-azul font-semibold"></div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm">
                    Cancelar
                </button>
                <button type="submit" id="btn-guardar-prog"
                        class="px-6 py-2.5 bg-ganaderasoft-verde-oscuro text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200 shadow-md disabled:opacity-50 text-sm"
                        disabled>
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<style>
/* ── Tree layout ──────────────────────────────────────────────────── */
.tree-container { display: flex; flex-direction: column; align-items: center; gap: 0; min-width: 680px; }
.tree-row       { display: flex; gap: 20px; width: 100%; justify-content: center; }
.tree-col       { flex: 1; max-width: 200px; min-width: 130px; display: flex; justify-content: center; }
.tree-col-2     { flex: 2; max-width: 260px; min-width: 180px; display: flex; justify-content: center; }
.tree-col-main  { display: flex; justify-content: center; }

/* ── Connectors ───────────────────────────────────────────────────── */
.tree-connectors-row {
    display: flex; width: 100%; justify-content: center; gap: 20px; height: 44px; position: relative;
}
.tree-connector-pair {
    flex: 2; max-width: 420px; position: relative;
    border-top: 2px solid #9CA3AF; border-left: 2px solid #9CA3AF; border-right: 2px solid #9CA3AF;
    border-radius: 6px 6px 0 0; height: 26px; margin-top: 8px;
}
.tree-connector-pair::after {
    content: ''; position: absolute; bottom: -18px; left: 50%;
    transform: translateX(-50%); width: 2px; height: 18px; background: #9CA3AF;
}
.tree-connectors-center {
    width: 2px; height: 38px; background: #9CA3AF; margin: 4px auto;
}

/* ── Cards ────────────────────────────────────────────────────────── */
.tree-card-badge {
    position: absolute; top: -11px; left: 50%; transform: translateX(-50%);
    font-size: 0.68rem; font-weight: 700; padding: 2px 10px; border-radius: 99px; white-space: nowrap;
    letter-spacing: 0.05em; text-transform: uppercase; shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.tree-card-badge.blue  { background: #DBEAFE; color: #1E40AF; border: 1px solid #BFDBFE; }
.tree-card-badge.pink  { background: #FCE7F3; color: #9D174D; border: 1px solid #FBCFE8; }
.tree-card-badge.main  { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
.tree-card-badge.child { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }

.tree-empty {
    width: 100%; text-align: center; font-size: 0.75rem; color: #94A3B8; font-weight: 500;
    border: 2px dashed #E2E8F0; border-radius: 14px; padding: 16px 8px; background: #F8FAFC;
}

.tree-card-parent {
    position: relative; border-radius: 16px; padding: 22px 16px 14px;
    text-align: center; width: 100%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}
.tree-card-parent.blue { background: #F0F6FF; border: 2px solid #BFDBFE; }
.tree-card-parent.pink { background: #FDF2F8; border: 2px solid #FBCFE8; }

.tree-card-empty {
    position: relative; border-radius: 16px; padding: 22px 16px 14px;
    text-align: center; width: 100%;
    background: #F8FAFC; border: 2px dashed #CBD5E1;
}

.tree-card-main {
    position: relative; border-radius: 16px; padding: 22px 24px 14px;
    text-align: center; min-width: 240px;
    background: #ECFDF5;
    border: 2px solid #A7F3D0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}

.tree-card-child {
    position: relative; border-radius: 14px; padding: 18px 14px 12px;
    text-align: center; background: #F0F9FF; border: 2px solid #BAE6FD;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

@media (max-width: 768px) {
    .tree-col    { max-width: 130px; min-width: 100px; }
    .tree-col-2  { max-width: 180px; min-width: 140px; }
    .tree-card-parent { padding: 20px 10px 12px; }
    .tree-col-main { min-width: 200px; }
}
</style>

<script>
(function () {
    const modal      = document.getElementById('modal-progenitor');
    const modalTitle = document.getElementById('modal-title');
    const inputTipo  = document.getElementById('input-tipo');
    const inputId    = document.getElementById('input-padre-id');
    const lista      = document.getElementById('lista-disponibles');
    const selected   = document.getElementById('selected-animal');
    const btnGuardar = document.getElementById('btn-guardar-prog');
    const search     = document.getElementById('search-animal');
    const endpoint   = '{{ route('arbol-gen.disponibles', $id) }}';

    let allAnimales = [];

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    window.openModal = function (tipo) {
        inputTipo.value  = tipo;
        inputId.value    = '';
        selected.classList.add('hidden');
        btnGuardar.disabled = true;
        search.value = '';
        modalTitle.textContent = tipo === 'Padre' ? 'Asignar Padre (Macho)' : 'Asignar Madre (Hembra)';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        loadDisponibles(tipo);
    };

    window.closeModal = function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    async function loadDisponibles(tipo) {
        lista.innerHTML = '<p class="p-4 text-center text-sm text-gray-400">Cargando animales disponibles...</p>';
        try {
            const res  = await fetch(`${endpoint}?tipo=${tipo}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
            const json = await res.json();
            allAnimales = (json.success && Array.isArray(json.data)) ? json.data : [];
            renderLista(allAnimales);
        } catch (e) {
            lista.innerHTML = '<p class="p-4 text-center text-sm text-red-500">Error al cargar listado de animales.</p>';
        }
    }

    function renderLista(animales) {
        if (animales.length === 0) {
            lista.innerHTML = '<p class="p-4 text-center text-sm text-gray-400">No hay animales disponibles para asignar.</p>';
            return;
        }
        lista.innerHTML = animales.map(a => {
            const id = a.id;
            const nombre = a.nombre || 'Sin nombre';
            const codigo = a.codigo_animal || '';
            const sexo = a.sexo || '';
            const isMacho = sexo === 'M';

            return `
            <div class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-ganaderasoft-celeste/10 transition-colors"
                 data-id="${id}"
                 onclick="selectAnimal(${id}, '${escapeHtml(nombre)}', '${escapeHtml(codigo)}')">
                <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold ${isMacho ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700'}">
                    ${isMacho ? '♂' : '♀'}
                </span>
                <div>
                    <p class="text-sm font-bold text-gray-900">${escapeHtml(nombre)}</p>
                    ${codigo ? `<p class="text-xs font-mono text-gray-400">#${escapeHtml(codigo)}</p>` : ''}
                </div>
            </div>`;
        }).join('');
    }

    window.selectAnimal = function (id, nombre, codigo) {
        inputId.value = id;
        selected.textContent = `Seleccionado: ${nombre}${codigo ? ' (#' + codigo + ')' : ''}`;
        selected.classList.remove('hidden');
        btnGuardar.disabled = false;
        lista.querySelectorAll('[data-id]').forEach(el => {
            el.classList.toggle('bg-ganaderasoft-celeste/20', el.dataset.id == id);
        });
    };

    search.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        renderLista(allAnimales.filter(a =>
            (a.nombre || '').toLowerCase().includes(q) || (a.codigo_animal || '').toLowerCase().includes(q)
        ));
    });
})();
</script>
@endpush
