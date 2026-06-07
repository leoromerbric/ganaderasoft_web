@extends('layouts.authenticated')

@section('title', 'Editar Movimiento de Rebaño')

@section('content')
<div>
    <div class="mb-6 flex items-center">
        <a href="{{ route('movimiento-rebano.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-ganaderasoft-negro">🔄 Editar Movimiento #{{ $movimiento['id_Movimiento'] }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="bg-ganaderasoft-celeste text-white px-6 py-4 rounded-t-xl">
            <h3 class="text-lg font-semibold">Modificar Datos</h3>
        </div>
        <form action="{{ route('movimiento-rebano.update', $movimiento['id_Movimiento']) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                    <ul class="list-disc ml-4">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <!-- Información de solo lectura -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-500">Finca Origen</p>
                    <p class="text-base text-ganaderasoft-negro mt-1">
                        {{ $movimiento['fincaOrigen']['Nombre'] ?? (isset($movimiento['id_Finca']) ? 'Finca #'.$movimiento['id_Finca'] : '-') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Rebaño Origen</p>
                    <p class="text-base text-ganaderasoft-negro mt-1">
                        {{ $movimiento['rebanoOrigen']['Nombre'] ?? (isset($movimiento['id_Rebano']) ? 'Rebaño #'.$movimiento['id_Rebano'] : '-') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Finca Destino</p>
                    <p class="text-base text-ganaderasoft-negro mt-1">
                        {{ $movimiento['fincaDestino']['Nombre'] ?? (isset($movimiento['id_Finca_Destino']) ? 'Finca #'.$movimiento['id_Finca_Destino'] : '-') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Rebaño Destino</p>
                    <p class="text-base text-ganaderasoft-negro mt-1">
                        {{ $movimiento['rebanoDestino']['Nombre'] ?? (isset($movimiento['id_Rebano_Destino']) ? 'Rebaño #'.$movimiento['id_Rebano_Destino'] : '-') }}
                    </p>
                </div>
            </div>
            <p class="text-xs text-gray-400 -mt-2 mb-6">El origen y destino no se pueden modificar. Solo se pueden actualizar los campos siguientes.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Rebaño Destino</label>
                    <input type="text" name="Rebano_Destino" maxlength="30"
                           value="{{ old('Rebano_Destino', $movimiento['Rebano_Destino'] ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('Rebano_Destino') border-red-500 @enderror">
                    @error('Rebano_Destino')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                    <input type="text" name="Comentario" maxlength="40"
                           value="{{ old('Comentario', $movimiento['Comentario'] ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-ganaderasoft-celeste @error('Comentario') border-red-500 @enderror">
                    @error('Comentario')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('movimiento-rebano.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                        class="px-6 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
                    💾 Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
