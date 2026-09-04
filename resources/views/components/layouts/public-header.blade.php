<header class="relative z-50 bg-white border-b border-gray-100 shadow-xs">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 sm:h-20">
            <!-- Logotipo y nombre del sistema -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2 sm:space-x-3 group min-w-0 shrink-0">
                <div class="bg-white p-1 sm:p-2 rounded-xl sm:rounded-2xl shadow-xs border border-gray-100 group-hover:scale-105 transition-transform duration-200 shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="GanaderaSoft Logo" class="w-7 h-7 sm:w-10 sm:h-10 object-contain">
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-base sm:text-xl xl:text-2xl font-black tracking-tight text-ganaderasoft-negro leading-tight truncate">
                        Ganadera<span class="text-ganaderasoft-azul">Soft</span>
                    </span>
                    <span class="text-[10px] sm:text-xs text-gray-500 font-medium leading-tight truncate">
                        <span class="hidden md:inline">Facultad de Ciencias / Facultad de Agronomía</span>
                        <span class="md:hidden">Ciencias / Agronomía &bull; UCV</span>
                    </span>
                </div>
            </a>
            <!-- Navegación y acciones -->
            @if(isset($slot) && !empty(trim($slot)))
                <!-- Enlaces de navegación en escritorio (>= xl) -->
                <nav class="hidden xl:flex items-center space-x-6 2xl:space-x-8 text-sm font-semibold text-gray-600 [&>a]:whitespace-nowrap">
                    {{ $slot }}
                </nav>

                <!-- Botón de iniciar sesión en escritorio (>= xl) -->
                <div class="hidden xl:flex items-center space-x-3 sm:space-x-4 shrink-0">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-2.5 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-ganaderasoft-celeste via-[#4aa9d6] to-ganaderasoft-azul rounded-xl shadow-md shadow-ganaderasoft-celeste/30 hover:shadow-lg hover:scale-105 transition-all whitespace-nowrap">
                        Iniciar sesión
                    </a>
                </div>

                <!-- Botón de menú hamburguesa móvil (< xl) -->
                <div class="flex items-center xl:hidden">
                    <button type="button" 
                            id="mobile-menu-btn"
                            class="p-2 rounded-xl text-gray-600 hover:text-ganaderasoft-azul hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-ganaderasoft-celeste/40"
                            aria-label="Abrir menú"
                            aria-expanded="false">
                        <!-- Ícono hamburguesa (3 líneas) -->
                        <svg id="hamburger-icon" class="w-6 h-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Ícono cerrar (X) -->
                        <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Menú desplegable móvil (solo si hay enlaces en el slot) -->
    @if(isset($slot) && !empty(trim($slot)))
        <div id="mobile-menu" class="hidden xl:hidden border-t border-gray-100 bg-white/95 backdrop-blur-lg px-4 pt-3 pb-5 shadow-lg transition-all duration-300">
            <div class="flex flex-col space-y-2 text-sm font-semibold text-gray-700">
                <div class="mobile-slot-links flex flex-col space-y-1 [&>a]:block [&>a]:px-3 [&>a]:py-2.5 [&>a]:rounded-lg [&>a]:hover:bg-slate-50 [&>a]:hover:text-ganaderasoft-azul [&>a]:transition-colors">
                    {{ $slot }}
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <a href="{{ route('login') }}" 
                       class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-ganaderasoft-celeste via-[#4aa9d6] to-ganaderasoft-azul rounded-xl shadow-md shadow-ganaderasoft-celeste/30 hover:shadow-lg transition-all">
                        Iniciar sesión
                    </a>
                </div>
            </div>
        </div>
    @endif
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');

        if (!btn || !menu) return;

        function toggleMenu() {
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', !isExpanded);
            menu.classList.toggle('hidden');
            if (hamburgerIcon && closeIcon) {
                hamburgerIcon.classList.toggle('hidden');
                hamburgerIcon.classList.toggle('block');
                closeIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('block');
            }
        }

        function closeMenu() {
            btn.setAttribute('aria-expanded', 'false');
            menu.classList.add('hidden');
            if (hamburgerIcon && closeIcon) {
                hamburgerIcon.classList.remove('hidden');
                hamburgerIcon.classList.add('block');
                closeIcon.classList.add('hidden');
                closeIcon.classList.remove('block');
            }
        }

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMenu();
        });

        // Cerrar al hacer clic en cualquier enlace del menú móvil
        menu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                closeMenu();
            });
        });

        // Cerrar al hacer clic fuera del menú
        document.addEventListener('click', function(e) {
            if (!menu.contains(e.target) && !btn.contains(e.target) && !menu.classList.contains('hidden')) {
                closeMenu();
            }
        });

        // Cerrar con la tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
                closeMenu();
            }
        });
    });
</script>
