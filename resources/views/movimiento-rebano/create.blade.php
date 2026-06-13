@extends('layouts.authenticated')

@section('title', 'Nuevo Movimiento de Rebaño')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('movimiento-rebano.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🔄 Nuevo Movimiento de Rebaño</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Datos del Movimiento</h3>
        </div>
        <form action="{{ route('movimiento-rebano.store') }}" method="POST" class="p-6" id="movimiento-form">
            @csrf
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Finca Origen <span class="text-red-500">*</span></label>
                            <select name="id_Finca" id="id_Finca" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('id_Finca') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione finca de origen</option>
                        @foreach($fincas as $finca)
                            <option value="{{ $finca['id_Finca'] }}" {{ old('id_Finca') == $finca['id_Finca'] ? 'selected' : '' }}>
                                {{ $finca['Nombre'] ?? 'Finca #'.$finca['id_Finca'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_Finca')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rebaño Origen <span class="text-red-500">*</span></label>
                            <select name="id_Rebano" id="id_Rebano" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('id_Rebano') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione rebaño de origen</option>
                        @foreach($rebanos as $rebano)
                            <option value="{{ $rebano['id_Rebano'] }}" {{ old('id_Rebano') == $rebano['id_Rebano'] ? 'selected' : '' }}
                                    data-finca="{{ $rebano['id_Finca'] ?? '' }}">
                                {{ $rebano['Nombre'] ?? 'Rebaño #'.$rebano['id_Rebano'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_Rebano')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Finca Destino <span class="text-red-500">*</span></label>
                            <select name="id_Finca_Destino" id="id_Finca_Destino" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('id_Finca_Destino') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione finca de destino</option>
                        @foreach($fincas as $finca)
                            <option value="{{ $finca['id_Finca'] }}" {{ old('id_Finca_Destino') == $finca['id_Finca'] ? 'selected' : '' }}>
                                {{ $finca['Nombre'] ?? 'Finca #'.$finca['id_Finca'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_Finca_Destino')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rebaño Destino <span class="text-red-500">*</span></label>
                            <select name="id_Rebano_Destino" id="id_Rebano_Destino" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('id_Rebano_Destino') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Seleccione rebaño de destino</option>
                        @foreach($rebanos as $rebano)
                            <option value="{{ $rebano['id_Rebano'] }}" {{ old('id_Rebano_Destino') == $rebano['id_Rebano'] ? 'selected' : '' }}
                                    data-finca="{{ $rebano['id_Finca'] ?? '' }}">
                                {{ $rebano['Nombre'] ?? 'Rebaño #'.$rebano['id_Rebano'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_Rebano_Destino')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Rebaño Destino</label>
                              <input type="text" name="Rebano_Destino" id="Rebano_Destino" value="{{ old('Rebano_Destino') }}" maxlength="30" readonly
                           placeholder="Nombre del rebaño destino..."
                                  class="w-full border rounded-lg px-4 py-2 bg-gray-50 text-gray-600 {{ $errors->has('Rebano_Destino') ? 'border-red-500' : 'border-gray-200' }}">
                    @error('Rebano_Destino')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                          <input type="text" name="Comentario" value="{{ old('Comentario') }}" maxlength="40"
                           placeholder="Comentario sobre el movimiento..."
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste {{ $errors->has('Comentario') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('Comentario')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Selección de animales -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-base font-semibold text-ganaderasoft-negro mb-3">Animales a Mover</h4>
                @if(count($animales) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-64 overflow-y-auto">
                        @foreach($animales as $animal)
                           <label class="animal-item flex items-center space-x-2 p-2 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer"
                               data-rebano="{{ data_get($animal, 'id_Rebano') ?? data_get($animal, 'rebano.id_Rebano') }}"
                               data-finca="{{ data_get($animal, 'rebano.id_Finca') ?? data_get($animal, 'rebano.finca.id_Finca') }}">
                            <input type="checkbox" name="animales[]" value="{{ $animal['id_Animal'] }}"
                                   {{ in_array($animal['id_Animal'], old('animales', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-ganaderasoft-celeste focus:ring-ganaderasoft-celeste">
                            <span class="text-sm text-gray-700">{{ $animal['Nombre'] ?? 'Animal #'.$animal['id_Animal'] }}</span>
                        </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No hay animales disponibles</p>
                @endif
                @error('animales')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                @error('animales.*')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('movimiento-rebano.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit" id="btn-guardar-movimiento"
                        class="px-6 py-3 bg-ganaderasoft-verde-oscuro text-white rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-60 disabled:cursor-not-allowed">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fincaOrigen = document.getElementById('id_Finca');
    const rebanoOrigen = document.getElementById('id_Rebano');
    const fincaDestino = document.getElementById('id_Finca_Destino');
    const rebanoDestino = document.getElementById('id_Rebano_Destino');
    const nombreRebanoDestino = document.getElementById('Rebano_Destino');
    const animales = Array.from(document.querySelectorAll('.animal-item'));

    function filterRebanos(select, fincaId, excludedRebanoId = null) {
        Array.from(select.options).forEach((option, index) => {
            if (index === 0) {
                option.hidden = false;
                return;
            }

            const belongs = !fincaId || option.dataset.finca === fincaId;
            const excluded = excludedRebanoId && option.value === excludedRebanoId;
            option.hidden = !belongs || excluded;
        });

        if (select.selectedOptions[0]?.hidden) {
            select.value = '';
        }
    }

    function syncDestinoNombre() {
        const option = rebanoDestino.options[rebanoDestino.selectedIndex];
        nombreRebanoDestino.value = option && option.value ? option.text.trim() : '';
    }

    function filterAnimales() {
        const rebanoId = rebanoOrigen.value;
        animales.forEach((item) => {
            const visible = !rebanoId || item.dataset.rebano === rebanoId;
            item.style.display = visible ? '' : 'none';
            if (!visible) {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
            }
        });
    }

    fincaOrigen.addEventListener('change', function () {
        if (fincaDestino.value === fincaOrigen.value) {
            fincaDestino.value = '';
        }
        filterRebanos(rebanoOrigen, fincaOrigen.value);
        filterRebanos(rebanoDestino, fincaDestino.value, rebanoOrigen.value);
        filterAnimales();
    });

    rebanoOrigen.addEventListener('change', function () {
        filterRebanos(rebanoDestino, fincaDestino.value, rebanoOrigen.value);
        if (rebanoDestino.value === rebanoOrigen.value) {
            rebanoDestino.value = '';
        }
        syncDestinoNombre();
        filterAnimales();
    });

    fincaDestino.addEventListener('change', function () {
        if (fincaDestino.value && fincaDestino.value === fincaOrigen.value) {
            fincaDestino.value = '';
        }
        filterRebanos(rebanoDestino, fincaDestino.value, rebanoOrigen.value);
        syncDestinoNombre();
    });

    rebanoDestino.addEventListener('change', syncDestinoNombre);

    filterRebanos(rebanoOrigen, fincaOrigen.value);
    filterRebanos(rebanoDestino, fincaDestino.value, rebanoOrigen.value);
    filterAnimales();
    syncDestinoNombre();

    const form = document.getElementById('movimiento-form');
    const submitBtn = document.getElementById('btn-guardar-movimiento');
    if (form && submitBtn) {
        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Guardando...';
        });
    }
});
</script>
@endsection
