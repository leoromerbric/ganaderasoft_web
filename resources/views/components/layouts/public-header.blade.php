@props([
    'showHomeLink' => false,
])

<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 sm:h-20">
            <!-- Brand Logo & Name -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2 sm:space-x-3 group">
                <div class="bg-white p-1 sm:p-2 rounded-xl sm:rounded-2xl shadow-xs border border-gray-100 group-hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/logo.png') }}" alt="GanaderaSoft Logo" class="w-7 h-7 sm:w-10 sm:h-10 object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-base sm:text-2xl font-black tracking-tight text-ganaderasoft-negro leading-tight">
                        Ganadera<span class="text-ganaderasoft-azul">Soft</span>
                    </span>
                    <span class="text-[10px] sm:text-xs text-gray-500 font-medium leading-tight">
                        Facultad de agronomía
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation Links (Opcional) -->
            @if(isset($slot) && !empty(trim($slot)))
                <nav class="hidden lg:flex items-center space-x-6 xl:space-x-8 text-sm font-semibold text-gray-600">
                    {{ $slot }}
                </nav>
            @endif

            <!-- Auth Action Buttons (Público) -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                @if($showHomeLink)
                    <a href="{{ url('/') }}" class="hidden sm:inline-flex items-center text-xs sm:text-sm font-semibold text-gray-600 hover:text-ganaderasoft-azul transition-colors">
                        Inicio
                    </a>
                @endif

                <a href="{{ route('login') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-2.5 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-ganaderasoft-celeste via-[#4aa9d6] to-ganaderasoft-azul rounded-xl shadow-md shadow-ganaderasoft-celeste/30 hover:shadow-lg hover:scale-105 transition-all whitespace-nowrap">
                    Iniciar sesión
                </a>
            </div>
        </div>
    </div>
</header>
