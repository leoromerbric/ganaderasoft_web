<!-- Modal de Confirmación Genérico -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-40 transition-opacity backdrop-blur-sm" onclick="closeConfirmModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 pointer-events-auto">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div id="modalIconContainer" class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Icon injected by JS -->
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-bold leading-6 text-gray-900" id="modalTitle">Título</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modalMessage">Mensaje</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="btnConfirmAction" class="inline-flex w-full justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto transition-colors">Confirmar</button>
                    <button type="button" onclick="closeConfirmModal()" id="btnCancelAction" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Generic Modal Logic
    let formToSubmit = null;
    let onConfirmCallback = null;

    window.openGenericConfirmModal = function(options) {
        const modal = document.getElementById('confirmModal');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');
        const iconContainer = document.getElementById('modalIconContainer');
        const btnConfirm = document.getElementById('btnConfirmAction');
        const btnCancel = document.getElementById('btnCancelAction');

        // Reset state
        formToSubmit = null;
        onConfirmCallback = null;

        if (options.formId) {
            formToSubmit = document.getElementById(options.formId);
        }
        if (options.onConfirm && typeof options.onConfirm === 'function') {
            onConfirmCallback = options.onConfirm;
        }

        title.textContent = options.title || 'Confirmar acción';
        message.textContent = options.message || '¿Estás seguro de que deseas continuar?';
        btnConfirm.textContent = options.confirmText || 'Confirmar';
        btnCancel.textContent = options.cancelText || 'Cancelar';

        // Configure styles based on intent
        if (options.intent === 'danger') {
            iconContainer.className = 'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10 text-red-600';
            iconContainer.innerHTML = options.icon || '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
            btnConfirm.className = 'inline-flex w-full justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors';
        } else if (options.intent === 'success') {
            iconContainer.className = 'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10 text-emerald-600';
            iconContainer.innerHTML = options.icon || '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            btnConfirm.className = 'inline-flex w-full justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto transition-colors';
        } else {
            iconContainer.className = 'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10 text-blue-600';
            iconContainer.innerHTML = options.icon || '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>';
            btnConfirm.className = 'inline-flex w-full justify-center rounded-xl bg-[#2b3543] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-opacity-90 sm:ml-3 sm:w-auto transition-colors';
        }

        modal.classList.remove('hidden');
    }

    window.closeConfirmModal = function() {
        document.getElementById('confirmModal').classList.add('hidden');
        formToSubmit = null;
        onConfirmCallback = null;
    }

    // Bind event listener just once on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('btnConfirmAction');
        if (btn && !btn.hasAttribute('data-initialized')) {
            btn.setAttribute('data-initialized', 'true');
            btn.addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                } else if (onConfirmCallback) {
                    onConfirmCallback();
                    closeConfirmModal();
                }
            });
        }
    });
</script>
