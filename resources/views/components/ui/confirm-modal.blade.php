<!-- Modal de Confirmación Genérico -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <!-- Fondo oscuro (Backdrop) -->
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-xs transition-opacity" onclick="closeConfirmModal()"></div>

    <!-- Contenedor del diálogo centrado -->
    <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 pointer-events-auto">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <!-- Icono dinámico -->
                        <div id="modalIconContainer" class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"></div>

                        <!-- Título y Mensaje -->
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Confirmar acción</h3>
                            <div class="mt-2">
                                <p id="modalMessage" class="text-sm text-gray-500">¿Estás seguro de que deseas continuar?</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                    <button type="button" id="btnConfirmAction" class="inline-flex w-full justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-sm sm:w-auto transition-colors cursor-pointer">
                        Confirmar
                    </button>
                    <button type="button" onclick="closeConfirmModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors cursor-pointer">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables de referencia para el formulario o callback
    let modalFormulario = null;
    let modalCallback = null;

    /**
     * Abre el modal de confirmación con las opciones enviadas
     */
    window.openGenericConfirmModal = function (opciones = {}) {
        const modal = document.getElementById('confirmModal');
        const titulo = document.getElementById('modalTitle');
        const mensaje = document.getElementById('modalMessage');
        const contenedorIcono = document.getElementById('modalIconContainer');
        const btnConfirmar = document.getElementById('btnConfirmAction');

        if (!modal) return;

        // Guardar referencia de lo que se ejecutará al confirmar
        modalFormulario = opciones.formId ? document.getElementById(opciones.formId) : null;
        modalCallback = (typeof opciones.onConfirm === 'function') ? opciones.onConfirm : null;

        // Asignar textos
        titulo.textContent = opciones.title || 'Confirmar acción';
        mensaje.textContent = opciones.message || '¿Estás seguro de que deseas continuar?';
        btnConfirmar.textContent = opciones.confirmText || 'Confirmar';

        // Restaurar estado habilitado del botón confirmar
        btnConfirmar.disabled = false;
        btnConfirmar.classList.remove('opacity-75', 'cursor-not-allowed', 'pointer-events-none');

        // Aplicar estilos según el tipo de acción (danger, success, default)
        if (opciones.intent === 'danger') {
            contenedorIcono.className = 'mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 sm:mx-0 sm:h-10 sm:w-10';
            contenedorIcono.innerHTML = opciones.icon || '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
            btnConfirmar.className = 'inline-flex w-full justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors cursor-pointer';
        } else if (opciones.intent === 'success') {
            contenedorIcono.className = 'mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 sm:mx-0 sm:h-10 sm:w-10';
            contenedorIcono.innerHTML = opciones.icon || '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            btnConfirmar.className = 'inline-flex w-full justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:w-auto transition-colors cursor-pointer';
        } else {
            contenedorIcono.className = 'mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 sm:mx-0 sm:h-10 sm:w-10';
            contenedorIcono.innerHTML = opciones.icon || '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>';
            btnConfirmar.className = 'inline-flex w-full justify-center rounded-xl bg-[#2b3543] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-opacity-90 sm:w-auto transition-colors cursor-pointer';
        }

        modal.classList.remove('hidden');
    };

    /**
     * Cierra el modal y limpia el estado
     */
    window.closeConfirmModal = function () {
        const modal = document.getElementById('confirmModal');
        const btnConfirmar = document.getElementById('btnConfirmAction');

        if (modal) modal.classList.add('hidden');
        if (btnConfirmar) {
            btnConfirmar.disabled = false;
            btnConfirmar.classList.remove('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
        }

        modalFormulario = null;
        modalCallback = null;
    };

    // Vincular el evento de clic una sola vez
    document.addEventListener('DOMContentLoaded', function () {
        const btnConfirmar = document.getElementById('btnConfirmAction');
        if (!btnConfirmar) return;

        btnConfirmar.addEventListener('click', function () {
            // Evitar envíos dobles si el botón ya fue presionado
            if (btnConfirmar.disabled) return;

            btnConfirmar.disabled = true;
            btnConfirmar.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');

            if (modalFormulario) {
                modalFormulario.submit();
            } else if (modalCallback) {
                modalCallback();
                closeConfirmModal();
            }
        });
    });
</script>

