@extends('layouts.public')

@section('title', 'GanaderaSoft - Control integral de la producción bovina y rebaños')

@section('header-links')
    <a href="#caracteristicas" class="whitespace-nowrap hover:text-ganaderasoft-azul transition-colors">Características</a>
    <a href="#modulos" class="whitespace-nowrap hover:text-ganaderasoft-azul transition-colors">Módulos</a>
    <a href="#app-movil" class="whitespace-nowrap hover:text-ganaderasoft-azul transition-colors">App móvil</a>
    <a href="#creditos" class="whitespace-nowrap hover:text-ganaderasoft-azul transition-colors">Créditos</a>
    <a href="#organizacion" class="whitespace-nowrap hover:text-ganaderasoft-azul transition-colors">Instituciones</a>
@endsection

@section('main-content')
    <!-- Hero Section -->
    <section id="caracteristicas" class="scroll-mt-16 sm:scroll-mt-20 relative overflow-hidden pt-4 pb-8 sm:pt-12 sm:pb-20 lg:pt-20 lg:pb-28 bg-gradient-to-b from-white via-slate-50 to-gray-50">
        <!-- Abstract Background Grid -->
        <div class="absolute inset-0 pointer-events-none opacity-20">
            <svg class="w-full h-full" width="100%" height="100%" fill="none">
                <defs>
                    <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#007B92" stroke-width="0.75" stroke-dasharray="2,2"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern)" />
            </svg>
        </div>

        <div class="absolute -top-24 -left-24 w-96 h-96 bg-ganaderasoft-celeste/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -right-24 w-96 h-96 bg-ganaderasoft-azul/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-12 items-center">
                
                <!-- Hero Content Left -->
                <div class="lg:col-span-7 space-y-4 sm:space-y-6 lg:space-y-8 text-center lg:text-left">
                    
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-2xl sm:rounded-full bg-ganaderasoft-celeste/10 border border-ganaderasoft-celeste/30 text-ganaderasoft-azul text-xs sm:text-sm font-bold shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-ganaderasoft-azul shrink-0"></span>
                        <span class="leading-snug">Proyecto de investigación UCV - FONACIT</span>
                    </div>

                    <h1 class="text-2xl min-[480px]:text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-extrabold text-ganaderasoft-negro tracking-tight leading-tight">
                        Control integral de la <span class="bg-clip-text text-transparent bg-gradient-to-r from-ganaderasoft-azul to-ganaderasoft-celeste">producción bovina</span> y rebaños.
                    </h1>

                    <p class="text-sm sm:text-base lg:text-lg text-gray-600 max-w-2xl mx-auto lg:mx-0 font-normal leading-relaxed">
                        Plataforma académica y técnica para la gestión centralizada de rebaños, registros de producción lechera, seguimiento genealógico y jornadas sanitarias, desarrollada por la Facultad de Ciencias en conjunto con la Facultad de Agronomía (UCV).
                    </p>

                    <!-- Hero Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-1 sm:pt-2 max-w-sm sm:max-w-none mx-auto lg:mx-0 w-full">
                        <a href="{{ route('login') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 sm:px-8 sm:py-3.5 text-sm sm:text-base font-bold text-white bg-gradient-to-r from-ganaderasoft-celeste via-[#007B92] to-ganaderasoft-azul rounded-xl sm:rounded-2xl shadow-lg shadow-ganaderasoft-azul/20 hover:shadow-xl hover:scale-105 transition-all duration-300">
                            Ingresar al sistema
                        </a>

                        <a href="#app-movil" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 sm:px-7 sm:py-3.5 text-sm sm:text-base font-semibold text-ganaderasoft-azul bg-white border border-gray-200 rounded-xl sm:rounded-2xl shadow-xs hover:bg-gray-50 hover:border-ganaderasoft-celeste transition-all duration-300">
                            Descargar app android
                        </a>
                    </div>

                    <!-- Quick Highlights metrics -->
                    <div class="grid grid-cols-3 gap-2 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-200/80 max-w-lg mx-auto lg:mx-0">
                        <div>
                            <p class="text-base sm:text-2xl font-bold text-ganaderasoft-azul">100%</p>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Trazabilidad bovina</p>
                        </div>
                        <div>
                            <p class="text-base sm:text-2xl font-bold text-ganaderasoft-azul">Centralizado</p>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Producción lechera</p>
                        </div>
                        <div>
                            <p class="text-base sm:text-2xl font-bold text-ganaderasoft-azul">API gateway</p>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Soporte institucional</p>
                        </div>
                    </div>

                </div>

                <!-- Hero Graphic Card Right -->
                <div class="lg:col-span-5">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        
                        <!-- Floating Glass Card -->
                        <div class="bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-5 sm:p-7 lg:p-6 xl:p-8 shadow-2xl border border-white/60 space-y-4 sm:space-y-5 lg:space-y-4 xl:space-y-6 relative z-10">
                            
                            <div class="flex items-center justify-between pb-3.5 sm:pb-4 border-b border-gray-100 gap-2">
                                <div class="flex items-center space-x-3 sm:space-x-3.5 min-w-0">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 xl:w-12 xl:h-12 rounded-xl sm:rounded-2xl bg-ganaderasoft-celeste/20 flex items-center justify-center text-ganaderasoft-azul shrink-0">
                                        <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5 xl:w-6 xl:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-gray-900 text-sm sm:text-base xl:text-lg leading-tight">Resumen ganadero</h4>
                                        <p class="text-xs sm:text-xs xl:text-sm text-gray-500 leading-tight">Módulos de registro</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-xs xl:text-sm font-bold rounded-full bg-emerald-100 text-emerald-700 shrink-0">Activo</span>
                            </div>

                            <!-- Stat Item 1 -->
                            <div class="bg-slate-50 rounded-xl sm:rounded-2xl p-3 sm:p-4 xl:p-4.5 flex items-center justify-between gap-2 border border-gray-100">
                                <div class="flex items-center space-x-3 sm:space-x-3.5 min-w-0">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 xl:w-11 xl:h-11 rounded-lg sm:rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm sm:text-base shrink-0">
                                        🐄
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] sm:text-xs font-semibold text-gray-500 leading-tight">Rebaño total</p>
                                        <p class="text-xs sm:text-sm xl:text-base font-extrabold text-gray-900 leading-tight">Control de vacunos</p>
                                    </div>
                                </div>
                                <span class="text-xs xl:text-sm font-bold text-ganaderasoft-azul bg-ganaderasoft-celeste/20 px-2.5 py-1 xl:px-3.5 xl:py-1.5 rounded-lg sm:rounded-xl shrink-0 whitespace-nowrap">Registrados</span>
                            </div>

                            <!-- Stat Item 2 -->
                            <div class="bg-slate-50 rounded-xl sm:rounded-2xl p-3 sm:p-4 xl:p-4.5 flex items-center justify-between gap-2 border border-gray-100">
                                <div class="flex items-center space-x-3 sm:space-x-3.5 min-w-0">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 xl:w-11 xl:h-11 rounded-lg sm:rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-sm sm:text-base shrink-0">
                                        🥛
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] sm:text-xs font-semibold text-gray-500 leading-tight">Producción lechera</p>
                                        <p class="text-xs sm:text-sm xl:text-base font-extrabold text-gray-900 leading-tight">Litros e historial</p>
                                    </div>
                                </div>
                                <span class="text-xs xl:text-sm font-bold text-amber-700 bg-amber-100 px-2.5 py-1 xl:px-3.5 xl:py-1.5 rounded-lg sm:rounded-xl shrink-0 whitespace-nowrap">Registrado</span>
                            </div>

                            <!-- Stat Item 3 -->
                            <div class="bg-slate-50 rounded-xl sm:rounded-2xl p-3 sm:p-4 xl:p-4.5 flex items-center justify-between gap-2 border border-gray-100">
                                <div class="flex items-center space-x-3 sm:space-x-3.5 min-w-0">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 xl:w-11 xl:h-11 rounded-lg sm:rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm sm:text-base shrink-0">
                                        💉
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] sm:text-xs font-semibold text-gray-500 leading-tight">Jornadas sanitarias</p>
                                        <p class="text-xs sm:text-sm xl:text-base font-extrabold text-gray-900 leading-tight">Vacunación y salud</p>
                                    </div>
                                </div>
                                <span class="text-xs xl:text-sm font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 xl:px-3.5 xl:py-1.5 rounded-lg sm:rounded-xl shrink-0 whitespace-nowrap">Al día</span>
                            </div>

                            <a href="{{ route('login') }}" class="block text-center py-2.5 sm:py-3 xl:py-3.5 text-xs sm:text-sm xl:text-base font-bold text-ganaderasoft-azul hover:text-ganaderasoft-negro transition">
                                Explorar panel administrativo →
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Main Modules Section -->
    <section id="modulos" class="scroll-mt-16 sm:scroll-mt-20 py-8 sm:py-20 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto space-y-2.5 sm:space-y-4 mb-8 sm:mb-16">
                <h2 class="text-xs font-bold uppercase tracking-wider text-ganaderasoft-azul">Módulos del sistema</h2>
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ganaderasoft-negro tracking-tight">
                    Funcionalidades para la gestión e investigación ganadera
                </h3>
                <p class="text-gray-600 text-xs sm:text-base max-w-2xl mx-auto">
                    Herramientas diseñadas para docentes, investigadores, estudiantes y personal de campo en el control de rebaños.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                
                <!-- Card 1 -->
                <div class="bg-slate-50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 lg:p-8 border border-gray-100 hover:border-ganaderasoft-celeste/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul flex items-center justify-center mb-3 sm:mb-5 group-hover:bg-ganaderasoft-azul group-hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h2m0 0h2m-4 0v-4m0 4h4m-4-4l-4 4m4-4l4 4" />
                        </svg>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-1.5 sm:mb-2">Gestión de fincas y rebaños</h4>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                        Administración centralizada de unidades de producción, lotes ganaderos y asignación de personal.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-slate-50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 lg:p-8 border border-gray-100 hover:border-ganaderasoft-celeste/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul flex items-center justify-center mb-3 sm:mb-5 group-hover:bg-ganaderasoft-azul group-hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-1.5 sm:mb-2">Control de animales y etapas</h4>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                        Registro de vacunos con historial de peso, cambios de etapa de vida (becerros, novillas, vacas) y estado sanitario.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-slate-50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 lg:p-8 border border-gray-100 hover:border-ganaderasoft-celeste/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul flex items-center justify-center mb-3 sm:mb-5 group-hover:bg-ganaderasoft-azul group-hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-1.5 sm:mb-2">Producción lechera y lactancias</h4>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                        Registro de producción en litros, períodos de lactancia, ordeños y métricas de rendimiento por vaca o lote.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="bg-slate-50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 lg:p-8 border border-gray-100 hover:border-ganaderasoft-celeste/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul flex items-center justify-center mb-3 sm:mb-5 group-hover:bg-ganaderasoft-azul group-hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.4.382l-1.056.845a2 2 0 00-.744 1.564V20a2 2 0 002 2h15.2a2 2 0 002-2v-1.636a2 2 0 00-.744-1.564l-1.056-.845z" />
                        </svg>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-1.5 sm:mb-2">Salud y vacunación</h4>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                        Planificación de jornadas sanitarias, registro de dosis de vacunas, tratamientos y relación con casas comerciales.
                    </p>
                </div>

                <!-- Card 5 -->
                <div class="bg-slate-50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 lg:p-8 border border-gray-100 hover:border-ganaderasoft-celeste/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul flex items-center justify-center mb-3 sm:mb-5 group-hover:bg-ganaderasoft-azul group-hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-1.5 sm:mb-2">Árbol genealógico</h4>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                        Seguimiento del linaje genético (padre y madre) para la trazabilidad y selección zootécnica.
                    </p>
                </div>

                <!-- Card 6 -->
                <div class="bg-slate-50 rounded-2xl sm:rounded-3xl p-5 sm:p-6 lg:p-8 border border-gray-100 hover:border-ganaderasoft-celeste/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul flex items-center justify-center mb-3 sm:mb-5 group-hover:bg-ganaderasoft-azul group-hover:text-white transition-colors duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-1.5 sm:mb-2">Reportes y exportación</h4>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                        Generación de reportes institucionales en PDF y excel para análisis técnico e investigación agrícola.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- Mobile App Banner -->
    <section id="app-movil" class="scroll-mt-16 sm:scroll-mt-20 py-8 sm:py-16 bg-gradient-to-r from-ganaderasoft-azul via-[#006f85] to-ganaderasoft-celeste text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-5 sm:gap-8">
                
                <div class="space-y-2.5 sm:space-y-4 max-w-2xl text-center md:text-left">
                    <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white text-[10px] sm:text-xs font-bold tracking-wide uppercase">Aplicación móvil</span>
                    <h3 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight">
                        Registro de datos en campo con la aplicación android
                    </h3>
                    <p class="text-white/80 text-xs sm:text-base leading-relaxed">
                        Facilita la toma de datos directamente en el potrero para eventos sanitarios, pesaje y control de ordeño.
                    </p>
                </div>

                <div class="shrink-0 w-full sm:w-auto text-center max-w-sm sm:max-w-none mx-auto md:mx-0">
                    <a href="https://drive.google.com/file/d/19g-CpAm9VyXjgKSWMgS8L8zHcl66gvdg/view?usp=drive_link" 
                       target="_blank"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 sm:px-8 sm:py-3.5 bg-white text-ganaderasoft-azul rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base shadow-lg hover:bg-slate-100 hover:scale-105 transition-all duration-300">
                        Descargar apk android
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Credits & Research Team Section -->
    <section id="creditos" class="scroll-mt-16 sm:scroll-mt-20 py-10 sm:py-20 bg-gradient-to-b from-slate-50 via-white to-slate-50 border-t border-gray-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-3 mb-10 sm:mb-16">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-ganaderasoft-azul/10 border border-ganaderasoft-azul/20 text-ganaderasoft-azul text-xs font-bold uppercase tracking-wider">
                    <span>🎓</span>
                    <span>Proyecto de investigación</span>
                </div>
                <h3 class="text-2xl sm:text-4xl font-extrabold text-ganaderasoft-negro tracking-tight">
                    Créditos y equipo de investigación
                </h3>
                <p class="text-gray-600 text-xs sm:text-base leading-relaxed">
                    El sistema web para la gestión de los datos productivos en la ganadería vacuna, <strong class="text-ganaderasoft-azul font-bold">GanaderaSoft</strong>, es un proyecto de investigación desarrollado en la <strong>Universidad Central de Venezuela (UCV)</strong> por las Facultades de Ciencias y Agronomía para sistematizar y automatizar los procesos de producción bovina. Cuenta con el financiamiento del <strong>FONACIT (MINCYT)</strong>.
                </p>
            </div>

            <!-- Two Column Cards: Researchers & Developers -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-10">
                
                <!-- Card 1: Investigadores Principales -->
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-ganaderasoft-celeste/15 text-ganaderasoft-azul flex items-center justify-center font-bold text-xl sm:text-2xl shrink-0">
                            🔬
                        </div>
                        <div>
                            <h4 class="text-base sm:text-xl font-bold text-gray-900">Investigadores principales</h4>
                            <p class="text-xs text-gray-500">Dirección y coordinación científica</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex flex-col min-[500px]:flex-row min-[500px]:items-center justify-between gap-2 min-[500px]:gap-3 p-3 sm:p-3.5 rounded-xl bg-slate-50 border border-gray-100 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                                    YH
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">Profa. Yosly Hernández Bieliukas</p>
                                    <p class="text-[10px] sm:text-xs text-gray-500 leading-snug">Investigadora principal</p>
                                </div>
                            </div>
                            <div class="pl-11 min-[500px]:pl-0 shrink-0">
                                <span class="inline-block text-[10px] sm:text-xs font-semibold px-2.5 py-0.5 sm:py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap">
                                    Ciencias UCV
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col min-[500px]:flex-row min-[500px]:items-center justify-between gap-2 min-[500px]:gap-3 p-3 sm:p-3.5 rounded-xl bg-slate-50 border border-gray-100 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">
                                    MF
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">Profa. Marina Fuentes</p>
                                    <p class="text-[10px] sm:text-xs text-gray-500 leading-snug">Investigadora principal</p>
                                </div>
                            </div>
                            <div class="pl-11 min-[500px]:pl-0 shrink-0">
                                <span class="inline-block text-[10px] sm:text-xs font-semibold px-2.5 py-0.5 sm:py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                    Agronomía UCV
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col min-[500px]:flex-row min-[500px]:items-center justify-between gap-2 min-[500px]:gap-3 p-3 sm:p-3.5 rounded-xl bg-slate-50 border border-gray-100 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">
                                    DV
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">Prof. Daniel Vargas</p>
                                    <p class="text-[10px] sm:text-xs text-gray-500 leading-snug">Investigador principal</p>
                                </div>
                            </div>
                            <div class="pl-11 min-[500px]:pl-0 shrink-0">
                                <span class="inline-block text-[10px] sm:text-xs font-semibold px-2.5 py-0.5 sm:py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                    Agronomía UCV
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col min-[500px]:flex-row min-[500px]:items-center justify-between gap-2 min-[500px]:gap-3 p-3 sm:p-3.5 rounded-xl bg-slate-50 border border-gray-100 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                                    CL
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">Prof. Carlos Leal</p>
                                    <p class="text-[10px] sm:text-xs text-gray-500 leading-snug">Investigador principal</p>
                                </div>
                            </div>
                            <div class="pl-11 min-[500px]:pl-0 shrink-0">
                                <span class="inline-block text-[10px] sm:text-xs font-semibold px-2.5 py-0.5 sm:py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap">
                                    Ciencias UCV
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Equipo de Desarrollo -->
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-ganaderasoft-azul/10 text-ganaderasoft-azul flex items-center justify-center font-bold text-xl sm:text-2xl shrink-0">
                            💻
                        </div>
                        <div>
                            <h4 class="text-base sm:text-xl font-bold text-gray-900">Equipo de desarrollo</h4>
                            <p class="text-xs text-gray-500">Diseño, arquitectura e implementación</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex flex-col min-[500px]:flex-row min-[500px]:items-center justify-between gap-2 min-[500px]:gap-3 p-3 sm:p-3.5 rounded-xl bg-slate-50 border border-gray-100 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center shrink-0">
                                    EG
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">Edwyn Guzmán</p>
                                    <p class="text-[10px] sm:text-xs text-gray-500 leading-snug">Desarrollador de software</p>
                                </div>
                            </div>
                            <div class="pl-11 min-[500px]:pl-0 shrink-0">
                                <span class="inline-block text-[10px] sm:text-xs font-semibold px-2.5 py-0.5 sm:py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200 whitespace-nowrap">
                                    Computación, Ciencias UCV
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col min-[500px]:flex-row min-[500px]:items-center justify-between gap-2 min-[500px]:gap-3 p-3.5 rounded-xl bg-slate-50 border border-gray-100 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center shrink-0">
                                    MC
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">Miguel Ciavato</p>
                                    <p class="text-[10px] sm:text-xs text-gray-500 leading-snug">Desarrollador de software</p>
                                </div>
                            </div>
                            <div class="pl-11 min-[500px]:pl-0 shrink-0">
                                <span class="inline-block text-[10px] sm:text-xs font-semibold px-2.5 py-0.5 sm:py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200 whitespace-nowrap">
                                    Computación, Ciencias UCV
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col min-[500px]:flex-row min-[500px]:items-center justify-between gap-2 min-[500px]:gap-3 p-3.5 rounded-xl bg-slate-50 border border-gray-100 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center shrink-0">
                                    DP
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">Diego Perdomo</p>
                                    <p class="text-[10px] sm:text-xs text-gray-500 leading-snug">Desarrollador de software</p>
                                </div>
                            </div>
                            <div class="pl-11 min-[500px]:pl-0 shrink-0">
                                <span class="inline-block text-[10px] sm:text-xs font-semibold px-2.5 py-0.5 sm:py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200 whitespace-nowrap">
                                    Computación, Ciencias UCV
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Funding Banner -->
            <div class="bg-gradient-to-r from-ganaderasoft-azul/10 via-ganaderasoft-celeste/10 to-ganaderasoft-azul/10 rounded-2xl p-4 sm:p-6 border border-ganaderasoft-celeste/20 text-center max-w-4xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6">
                <span class="text-2xl">🏛️</span>
                <p class="text-xs sm:text-sm text-gray-700 font-medium leading-relaxed">
                    Proyecto financiado por el <strong class="text-ganaderasoft-azul font-bold">Fondo Nacional de Ciencia, Tecnología e Innovación (FONACIT)</strong> adscrito al <strong>MINCYT</strong>, en convenio interfacultades con la <strong>Universidad Central de Venezuela</strong>.
                </p>
            </div>

        </div>
    </section>

    <!-- Participating Organizations / Allies Section (Directamente arriba del footer) -->
    <section id="organizacion" class="scroll-mt-16 sm:scroll-mt-20 py-8 sm:py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-5 sm:mb-8">Instituciones y facultades participantes</h4>
            <div class="flex justify-center items-center">
                <img src="{{ asset('images/logos_participantes.png') }}" alt="Logos Participantes" class="max-w-full h-auto max-h-14 sm:max-h-24 object-contain">
            </div>
        </div>
    </section>
@endsection
