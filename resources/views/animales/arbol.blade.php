@extends('layouts.authenticated')

@section('title', 'Árbol Genealógico — ' . ($arbol['animal']['Nombre'] ?? 'Animal'))

@section('content')
{{-- ── Encabezado ──────────────────────────────────────────────────────────── --}}
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('animales.show', $id) }}" class="text-ganaderasoft-celeste hover:text-ganaderasoft-azul">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🌳 Árbol Genealógico</h2>
            <p class="mt-1 text-gray-600">{{ $arbol['animal']['Nombre'] }} — {{ $arbol['animal']['codigo_animal'] ?? '' }}</p>
        </div>
    </div>
    <div class="flex gap-2">
        <button onclick="openModal('Padre')"
                class="px-4 py-2 bg-ganaderasoft-celeste text-white rounded-lg hover:bg-ganaderasoft-azul transition-colors text-sm">
            + Asignar Padre
        </button>
        <button onclick="openModal('Madre')"
                class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors text-sm">
            + Asignar Madre
        </button>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg border-l-4 border-green-500 bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 rounded-lg border-l-4 border-red-500 bg-red-50 p-4 text-red-800">{{ session('error') }}</div>
@endif

{{-- ── Árbol Visual ─────────────────────────────────────────────────────────── --}}
<div class="rounded-xl bg-white shadow-md p-8 overflow-x-auto">

    {{-- GENERACIÓN 2: Abuelos -------------------------------------------------- --}}
    @php
        $abueloP  = $arbol['padre']['abuelo_paterno']  ?? null;
        $abuelaP  = $arbol['padre']['abuela_paterna']  ?? null;
        $abueloM  = $arbol['madre']['abuelo_materno']  ?? null;
        $abuelaM  = $arbol['madre']['abuela_materna']  ?? null;
        $padre    = $arbol['padre'] ?? null;
        $madre    = $arbol['madre'] ?? null;
        $animal   = $arbol['animal'];
        $hijos    = $arbol['hijos'] ?? [];
        $idArbolPadre = $arbol['relaciones']['id_arbol_padre'] ?? null;
        $idArbolMadre = $arbol['relaciones']['id_arbol_madre'] ?? null;
    @endphp

    <div class="tree-container">

        {{-- Fila 1: Abuelos ──────────────────────────────────────────────── --}}
        <div class="tree-row">
            <div class="tree-col">
                @if($abueloP)
                    <x-arbol-card :animal="$abueloP" label="Abuelo paterno" color="blue" />
                @else
                    <div class="tree-empty">Sin registro</div>
                @endif
            </div>
            <div class="tree-col">
                @if($abuelaP)
                    <x-arbol-card :animal="$abuelaP" label="Abuela paterna" color="pink" />
                @else
                    <div class="tree-empty">Sin registro</div>
                @endif
            </div>
            <div class="tree-col">
                @if($abueloM)
                    <x-arbol-card :animal="$abueloM" label="Abuelo materno" color="blue" />
                @else
                    <div class="tree-empty">Sin registro</div>
                @endif
            </div>
            <div class="tree-col">
                @if($abuelaM)
                    <x-arbol-card :animal="$abuelaM" label="Abuela materna" color="pink" />
                @else
                    <div class="tree-empty">Sin registro</div>
                @endif
            </div>
        </div>

        {{-- Conectores abuelos → padres --}}
        <div class="tree-connectors-row">
            <div class="tree-connector-pair"></div>
            <div class="tree-connector-pair"></div>
        </div>

        {{-- Fila 2: Padre / Madre ─────────────────────────────────────────── --}}
        <div class="tree-row">
            <div class="tree-col-2">
                @if($padre)
                    <div class="tree-card-parent blue relative">
                        <div class="tree-card-badge blue">Padre</div>
                        <a href="{{ route('animales.show', $padre['id_Animal']) }}" class="block hover:opacity-80 transition-opacity">
                            <p class="font-bold text-gray-900 text-base leading-tight">{{ $padre['Nombre'] }}</p>
                            @if($padre['codigo_animal'] ?? null)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $padre['codigo_animal'] }}</p>
                            @endif
                            @if($padre['fecha_nacimiento'] ?? null)
                                <p class="text-xs text-gray-400 mt-1">Nac. {{ \Carbon\Carbon::parse($padre['fecha_nacimiento'])->format('d/m/Y') }}</p>
                            @endif
                        </a>
                        <form method="POST" action="{{ route('arbol-gen.destroy', [$id, 'Padre']) }}"
                              onsubmit="return confirm('¿Eliminar relación de Padre?')"
                              class="mt-2">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700">✕ Quitar</button>
                        </form>
                    </div>
                @else
                    <div class="tree-card-empty">
                        <p class="text-gray-400 text-sm">Sin padre registrado</p>
                        <button onclick="openModal('Padre')"
                                class="mt-2 text-xs text-ganaderasoft-celeste hover:underline">+ Asignar</button>
                    </div>
                @endif
            </div>
            <div class="tree-col-2">
                @if($madre)
                    <div class="tree-card-parent pink relative">
                        <div class="tree-card-badge pink">Madre</div>
                        <a href="{{ route('animales.show', $madre['id_Animal']) }}" class="block hover:opacity-80 transition-opacity">
                            <p class="font-bold text-gray-900 text-base leading-tight">{{ $madre['Nombre'] }}</p>
                            @if($madre['codigo_animal'] ?? null)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $madre['codigo_animal'] }}</p>
                            @endif
                            @if($madre['fecha_nacimiento'] ?? null)
                                <p class="text-xs text-gray-400 mt-1">Nac. {{ \Carbon\Carbon::parse($madre['fecha_nacimiento'])->format('d/m/Y') }}</p>
                            @endif
                        </a>
                        <form method="POST" action="{{ route('arbol-gen.destroy', [$id, 'Madre']) }}"
                              onsubmit="return confirm('¿Eliminar relación de Madre?')"
                              class="mt-2">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700">✕ Quitar</button>
                        </form>
                    </div>
                @else
                    <div class="tree-card-empty">
                        <p class="text-gray-400 text-sm">Sin madre registrada</p>
                        <button onclick="openModal('Madre')"
                                class="mt-2 text-xs text-pink-500 hover:underline">+ Asignar</button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Conector padres → animal --}}
        <div class="tree-connectors-center"></div>

        {{-- Fila 3: Animal principal ──────────────────────────────────────── --}}
        <div class="tree-row justify-center">
            <div class="tree-col-main">
                <div class="tree-card-main">
                    <div class="tree-card-badge main">Animal</div>
                    <p class="font-extrabold text-xl text-ganaderasoft-negro leading-tight">{{ $animal['Nombre'] }}</p>
                    @if($animal['codigo_animal'] ?? null)
                        <p class="text-sm text-gray-500 mt-0.5">{{ $animal['codigo_animal'] }}</p>
                    @endif
                    <div class="mt-2 flex items-center justify-center gap-2">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ ($animal['Sexo'] ?? '') === 'M' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                            {{ ($animal['Sexo'] ?? '') === 'M' ? '♂ Macho' : '♀ Hembra' }}
                        </span>
                        @if($animal['fecha_nacimiento'] ?? null)
                            <span class="text-xs text-gray-400">Nac. {{ \Carbon\Carbon::parse($animal['fecha_nacimiento'])->format('d/m/Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Conector animal → hijos --}}
        @if(count($hijos) > 0)
        <div class="tree-connectors-center"></div>

        {{-- Fila 4: Hijos ─────────────────────────────────────────────────── --}}
        <div class="tree-row flex-wrap justify-center gap-4 mt-0">
            @foreach($hijos as $hijo)
                <div style="min-width:140px; max-width:180px;">
                    <div class="tree-card-child">
                        <div class="tree-card-badge child">Hijo/a</div>
                        <a href="{{ route('animales.show', $hijo['id_Animal']) }}" class="block hover:opacity-80">
                            <p class="font-semibold text-gray-900 text-sm leading-tight">{{ $hijo['Nombre'] }}</p>
                            @if($hijo['codigo_animal'] ?? null)
                                <p class="text-xs text-gray-400">{{ $hijo['codigo_animal'] }}</p>
                            @endif
                        </a>
                        <span class="mt-1 inline-block px-1.5 py-0.5 rounded text-xs {{ ($hijo['Sexo'] ?? '') === 'M' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600' }}">
                            {{ ($hijo['Sexo'] ?? '') === 'M' ? '♂' : '♀' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="mt-4 text-center text-sm text-gray-400">Sin descendencia registrada</div>
        @endif

    </div>{{-- /tree-container --}}
</div>

{{-- ── Modal: Asignar progenitor ───────────────────────────────────────────── --}}
<div id="modal-progenitor" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl p-6 mx-4">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-ganaderasoft-negro" id="modal-title">Asignar Padre</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>

        <form method="POST" action="{{ route('arbol-gen.store', $id) }}" id="form-progenitor">
            @csrf
            <input type="hidden" name="tipo" id="input-tipo" value="">

            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-gray-700">Buscar animal</label>
                <input type="text" id="search-animal" placeholder="Escriba el nombre o código..."
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste"
                       autocomplete="off">
            </div>

            <div id="lista-disponibles" class="mb-4 max-h-56 overflow-y-auto rounded-lg border border-gray-200">
                <p class="p-4 text-center text-sm text-gray-400">Cargando...</p>
            </div>

            <input type="hidden" name="id_padre" id="input-id-padre" value="">
            <div id="selected-animal" class="mb-4 hidden rounded-lg bg-ganaderasoft-celeste/10 border border-ganaderasoft-celeste/30 p-3 text-sm text-ganaderasoft-azul"></div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit" id="btn-guardar-prog"
                        class="px-6 py-2 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md disabled:opacity-50"
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
.tree-container { display: flex; flex-direction: column; align-items: center; gap: 0; min-width: 600px; }
.tree-row       { display: flex; gap: 16px; width: 100%; justify-content: center; }
.tree-col       { flex: 1; max-width: 180px; min-width: 120px; display: flex; justify-content: center; }
.tree-col-2     { flex: 2; max-width: 220px; min-width: 160px; display: flex; justify-content: center; }
.tree-col-main  { display: flex; justify-content: center; }

/* ── Connectors ───────────────────────────────────────────────────── */
.tree-connectors-row {
    display: flex; width: 100%; justify-content: center; gap: 16px; height: 40px; position: relative;
}
.tree-connector-pair {
    flex: 2; max-width: 360px; position: relative;
    border-top: 2px solid #9CA3AF; border-left: 2px solid #9CA3AF; border-right: 2px solid #9CA3AF;
    border-radius: 4px 4px 0 0; height: 24px; margin-top: 8px;
}
.tree-connector-pair::after {
    content: ''; position: absolute; bottom: -16px; left: 50%;
    transform: translateX(-50%); width: 2px; height: 16px; background: #9CA3AF;
}
.tree-connectors-center {
    width: 2px; height: 36px; background: #9CA3AF; margin: 4px auto;
}

/* ── Cards ────────────────────────────────────────────────────────── */
.tree-card-badge {
    position: absolute; top: -10px; left: 50%; transform: translateX(-50%);
    font-size: 0.65rem; font-weight: 700; padding: 1px 8px; border-radius: 99px; white-space: nowrap;
    letter-spacing: 0.05em; text-transform: uppercase;
}
.tree-card-badge.blue  { background: #DBEAFE; color: #1D4ED8; }
.tree-card-badge.pink  { background: #FCE7F3; color: #BE185D; }
.tree-card-badge.main  { background: #B3D335; color: #3B4A0A; }
.tree-card-badge.child { background: #E0F2FE; color: #0369A1; }

.tree-empty {
    width: 100%; text-align: center; font-size: 0.7rem; color: #CBD5E1;
    border: 1.5px dashed #E2E8F0; border-radius: 10px; padding: 12px 4px;
}

.tree-card-parent {
    position: relative; border-radius: 12px; padding: 20px 16px 12px;
    text-align: center; width: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.tree-card-parent.blue { background: #EFF6FF; border: 2px solid #BFDBFE; }
.tree-card-parent.pink { background: #FDF2F8; border: 2px solid #FBCFE8; }

.tree-card-empty {
    position: relative; border-radius: 12px; padding: 20px 16px 12px;
    text-align: center; width: 100%;
    background: #F9FAFB; border: 2px dashed #E5E7EB;
}

.tree-card-main {
    position: relative; border-radius: 14px; padding: 22px 28px 18px;
    text-align: center; min-width: 200px;
    background: linear-gradient(135deg, #B3D335 0%, #7B9A1F 100%);
    color: white;
    box-shadow: 0 4px 20px rgba(107,142,35,0.35);
    border: 3px solid #B3D335;
}
.tree-card-main .tree-card-badge.main { background: white; color: #3B4A0A; }
.tree-card-main p { color: white !important; }
.tree-card-main .text-gray-500 { color: rgba(255,255,255,0.75) !important; }
.tree-card-main .text-gray-400 { color: rgba(255,255,255,0.6) !important; }

.tree-card-child {
    position: relative; border-radius: 10px; padding: 16px 12px 10px;
    text-align: center; background: #F0F9FF; border: 2px solid #BAE6FD;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}

/* Row for abuelos in narrower cells */
@media (max-width: 768px) {
    .tree-col    { max-width: 110px; min-width: 90px; }
    .tree-col-2  { max-width: 160px; min-width: 130px; }
    .tree-card-parent { padding: 18px 10px 10px; }
    .tree-col-main { min-width: 180px; }
}
</style>

<script>
(function () {
    const modal      = document.getElementById('modal-progenitor');
    const modalTitle = document.getElementById('modal-title');
    const inputTipo  = document.getElementById('input-tipo');
    const inputId    = document.getElementById('input-id-padre');
    const lista      = document.getElementById('lista-disponibles');
    const selected   = document.getElementById('selected-animal');
    const btnGuardar = document.getElementById('btn-guardar-prog');
    const search     = document.getElementById('search-animal');
    const endpoint   = '{{ route('arbol-gen.disponibles', $id) }}';

    let allAnimales = [];

    window.openModal = function (tipo) {
        inputTipo.value  = tipo;
        inputId.value    = '';
        selected.classList.add('hidden');
        btnGuardar.disabled = true;
        search.value = '';
        modalTitle.textContent = tipo === 'Padre' ? 'Asignar Padre' : 'Asignar Madre';
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
        lista.innerHTML = '<p class="p-4 text-center text-sm text-gray-400">Cargando...</p>';
        try {
            const res  = await fetch(`${endpoint}?tipo=${tipo}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
            const json = await res.json();
            allAnimales = (json.success && json.data) ? json.data : [];
            renderLista(allAnimales);
        } catch (e) {
            lista.innerHTML = '<p class="p-4 text-center text-sm text-red-500">Error al cargar.</p>';
        }
    }

    function renderLista(animales) {
        if (animales.length === 0) {
            lista.innerHTML = '<p class="p-4 text-center text-sm text-gray-400">No hay animales disponibles.</p>';
            return;
        }
        lista.innerHTML = animales.map(a => `
            <div class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-ganaderasoft-celeste/10 border-b border-gray-100 last:border-0 transition-colors"
                 data-id="${a.id_Animal}" data-nombre="${a.Nombre}" data-codigo="${a.codigo_animal || ''}"
                 onclick="selectAnimal(${a.id_Animal}, '${a.Nombre}', '${a.codigo_animal || ''}')">
                <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold ${a.Sexo === 'M' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700'}">
                    ${a.Sexo === 'M' ? '♂' : '♀'}
                </span>
                <div>
                    <p class="text-sm font-semibold text-gray-900">${a.Nombre}</p>
                    ${a.codigo_animal ? `<p class="text-xs text-gray-400">${a.codigo_animal}</p>` : ''}
                </div>
            </div>`).join('');
    }

    window.selectAnimal = function (id, nombre, codigo) {
        inputId.value = id;
        selected.textContent = `Seleccionado: ${nombre}${codigo ? ' (' + codigo + ')' : ''}`;
        selected.classList.remove('hidden');
        btnGuardar.disabled = false;
        // Highlight selected row
        lista.querySelectorAll('[data-id]').forEach(el => {
            el.classList.toggle('bg-ganaderasoft-celeste/20', el.dataset.id == id);
        });
    };

    search.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        renderLista(allAnimales.filter(a =>
            a.Nombre.toLowerCase().includes(q) || (a.codigo_animal || '').toLowerCase().includes(q)
        ));
    });
})();
</script>
@endpush
