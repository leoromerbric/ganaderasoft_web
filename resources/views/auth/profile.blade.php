@extends('layouts.authenticated')

@section('title', 'Mi perfil')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-ganaderasoft-negro">Mi perfil</h1>
                <p class="text-sm text-gray-500 mt-1">Información general y detalles de la cuenta de usuario</p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center text-sm text-ganaderasoft-azul hover:text-ganaderasoft-celeste font-medium transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al dashboard
            </a>
        </div>

        <!-- Main Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header Banner -->
            <div
                class="h-32 bg-gradient-to-r from-ganaderasoft-celeste via-ganaderasoft-azul to-ganaderasoft-negro relative">
                <div class="absolute -bottom-10 left-8">
                    <div
                        class="w-24 h-24 rounded-2xl bg-white p-1 shadow-lg flex items-center justify-center border-2 border-white">
                        <div
                            class="w-full h-full rounded-xl bg-gradient-to-br from-ganaderasoft-celeste to-ganaderasoft-azul flex items-center justify-center text-white text-3xl font-bold">
                            {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identity Section -->
            <div
                class="pt-14 pb-6 px-8 flex flex-col md:flex-row justify-between md:items-center gap-4 border-b border-gray-100">
                <div>
                    <div class="flex items-center space-x-3">
                        <h2 class="text-2xl font-bold text-ganaderasoft-negro">{{ $user['name'] ?? 'Usuario' }}</h2>
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full {{ ($user['status'] ?? 'activo') === 'activo' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                            {{ ucfirst($user['status'] ?? 'activo') }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ $user['email'] ?? 'No especificado' }}
                    </p>
                </div>

                <!-- Roles Badges -->
                <div class="flex flex-wrap gap-2">
                    @if(!empty($user['roles']))
                        @foreach($user['roles'] as $role)
                            <span
                                class="px-3 py-1.5 text-xs font-medium bg-ganaderasoft-celeste/15 text-ganaderasoft-azul border border-ganaderasoft-celeste/30 rounded-lg flex items-center space-x-1">
                                <span class="w-2 h-2 rounded-full bg-ganaderasoft-azul"></span>
                                <span>{{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                            </span>
                        @endforeach
                    @else
                        <span class="px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-lg">
                            {{ $user['type_user'] ?? 'Usuario' }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Details Grid -->
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Information Box: Cuenta -->
                <div class="bg-gray-50/70 p-5 rounded-xl border border-gray-100 space-y-4">
                    <h3 class="text-sm font-semibold text-ganaderasoft-azul uppercase tracking-wider flex items-center">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Datos de la Cuenta</span>
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-1 border-b border-gray-200/60">
                            <span class="text-gray-500">ID usuario:</span>
                            <span class="font-medium text-gray-900">#{{ $user['id'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-200/60">
                            <span class="text-gray-500">Roles:</span>
                            <span class="font-medium text-gray-900">
                                @if(!empty($user['roles']))
                                    {{ implode(', ', array_map(fn($r) => ucfirst(str_replace('_', ' ', $r)), $user['roles'])) }}
                                @else
                                    {{ $user['type_user'] ?? 'Usuario' }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-200/60">
                            <span class="text-gray-500">Fecha de registro:</span>
                            <span class="font-medium text-gray-900">
                                {{ isset($user['created_at']) ? \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y H:i') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Information Box: Persona -->
                <div class="bg-gray-50/70 p-5 rounded-xl border border-gray-100 space-y-4">
                    <h3 class="text-sm font-semibold text-ganaderasoft-azul uppercase tracking-wider flex items-center">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3 3 0 00-3 3b2 2 0 002 2h6a2 2 0 002-2 3 3 0 00-3-3H9z" />
                        </svg>
                        <span>Datos Personales</span>
                    </h3>
                    <div class="space-y-3 text-sm">
                        @if(!empty($user['persona']))
                            @php
                                $persona = $user['persona'];
                            @endphp
                            <div class="flex justify-between py-1 border-b border-gray-200/60">
                                <span class="text-gray-500">Cédula:</span>
                                <span class="font-medium text-gray-900">{{ $persona['cedula'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200/60">
                                <span class="text-gray-500">Nombre completo:</span>
                                <span
                                    class="font-medium text-gray-900">{{ trim(($persona['nombre'] ?? '') . ' ' . ($persona['apellido'] ?? '')) ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200/60">
                                <span class="text-gray-500">Teléfono:</span>
                                <span class="font-medium text-gray-900">{{ $persona['telefono'] ?? 'No registrado' }}</span>
                            </div>
                        @else
                            <p class="text-xs text-gray-500 italic py-4 text-center">
                                Sin datos personales asociados directamente en la base de datos V2.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection