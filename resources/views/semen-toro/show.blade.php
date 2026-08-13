@extends('layouts.authenticated')

@section('title', 'Detalle Semen de Toro')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('semen-toro.index') }}" class="mr-4 text-ganaderasoft-celeste hover:text-ganaderasoft-celeste/80">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-ganaderasoft-negro">🧪 Semen #{{ $semen['id'] }}</h2>
        </div>
        <a href="{{ route('semen-toro.edit', $semen['id']) }}"
           class="px-4 py-2 bg-ganaderasoft-verde text-white rounded-lg hover:bg-ganaderasoft-verde/80 transition-colors">
            Editar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Toro</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ $semen['toro']['nombre'] ?? $semen['toro']['Nombre'] ?? ('Toro #'.($semen['animal_id'] ?? 'N/A')) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Estado</p>
                <div class="mt-1">
                    @if(isset($semen['estado']))
                        <span class="px-3 py-1 text-sm rounded-full {{ $semen['estado'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $semen['estado'] ? 'Activo' : 'Inactivo' }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wider">Fecha</p>
                <p class="text-lg font-semibold text-ganaderasoft-negro mt-1">
                    {{ isset($semen['fecha']) ? date('d/m/Y', strtotime($semen['fecha'])) : 'N/A' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
