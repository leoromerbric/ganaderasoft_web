import './bootstrap';
import Chart from 'chart.js/auto';

// Hacer Chart accesible globalmente para scripts inline
window.Chart = Chart;

// Protección global contra doble clic y envíos múltiples en formularios
function initFormSubmitProtection() {
    /**
     * Obtiene todos los botones de envío asociados a un formulario,
     * incluyendo aquellos ubicados fuera del tag <form> mediante el atributo form="formId".
     */
    function getSubmitButtons(form) {
        if (!form) return [];
        const internalButtons = Array.from(form.querySelectorAll('button[type="submit"], input[type="submit"]'));
        const externalButtons = form.id
            ? Array.from(document.querySelectorAll(`button[type="submit"][form="${form.id}"], input[type="submit"][form="${form.id}"]`))
            : [];
        return Array.from(new Set([...internalButtons, ...externalButtons]));
    }

    /**
     * Restablece el estado interactivo del formulario y sus botones.
     */
    function resetFormState(form) {
        if (!form) return;
        form.removeAttribute('data-submitting');

        const submitButtons = getSubmitButtons(form);
        submitButtons.forEach(btn => {
            const wasInitiallyDisabled = btn.getAttribute('data-original-disabled') === 'true';
            if (!wasInitiallyDisabled) {
                btn.disabled = false;
            }
            btn.classList.remove('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
            btn.removeAttribute('data-original-disabled');
        });
    }

    /**
     * Resuelve el formulario asociado a un botón de envío (incluso si está fuera del form).
     */
    function getAssociatedForm(btn) {
        if (!btn) return null;
        if (btn.form) return btn.form;
        const formId = btn.getAttribute('form');
        if (formId) return document.getElementById(formId);
        return btn.closest('form');
    }

    // 1. Interceptar clics repetidos en fase de captura para bloquear doble clic inmediato
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('button[type="submit"], input[type="submit"]');
        if (!btn) return;

        const form = getAssociatedForm(btn);
        if (form && form.getAttribute('data-submitting') === 'true') {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    }, true);

    // 2. Controlador en fase de burbujeo (Bubbling)
    // Se ejecuta DESPUÉS de handlers locales (como onsubmit="return confirm(...)")
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form || form.tagName !== 'FORM') return;

        // Si otro listener (ej. confirm() cancelado o validación personalizada) previno el envío, no bloquear
        if (e.defaultPrevented) {
            return;
        }

        // Si el formulario ya se está enviando, cancelar el evento redundante de inmediato
        if (form.getAttribute('data-submitting') === 'true') {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }

        // Respetar validación nativa HTML5 si novalidate no está presente
        if (!form.hasAttribute('novalidate') && form.checkValidity && !form.checkValidity()) {
            return;
        }

        // Marcar el formulario como enviándose
        form.setAttribute('data-submitting', 'true');

        const submitButtons = getSubmitButtons(form);
        submitButtons.forEach(btn => {
            btn.setAttribute('data-original-disabled', btn.disabled ? 'true' : 'false');
            btn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');

            // Deshabilitar botón de forma asíncrona para no interferir con la recolección de datos del formulario
            setTimeout(() => {
                btn.disabled = true;
            }, 0);
        });

        // Caso borde: Si el formulario abre en una nueva pestaña (target="_blank"),
        // restablecer estado tras 1 segundo para no dejar bloqueada la pestaña actual
        if (form.target === '_blank') {
            setTimeout(() => resetFormState(form), 1000);
            return;
        }

        // Temporizador de seguridad (60s para archivos/carga masiva, 10s para formularios estándar)
        const esCargaArchivos = form.enctype === 'multipart/form-data' || form.querySelector('input[type="file"]');
        const tiempoEspera = esCargaArchivos ? 60000 : 10000;

        setTimeout(() => {
            if (form.getAttribute('data-submitting') === 'true') {
                resetFormState(form);
            }
        }, tiempoEspera);
    }, false); // Fase de burbujeo para verificar e.defaultPrevented

    // 3. Restablecer estado si el formulario se limpia/resetea
    document.addEventListener('reset', function(e) {
        const form = e.target;
        if (form && form.tagName === 'FORM') {
            resetFormState(form);
        }
    });

    // 4. Restablecer formularios al volver atrás en el navegador (bfcache)
    window.addEventListener('pageshow', function() {
        document.querySelectorAll('form[data-submitting="true"]').forEach(resetFormState);
    });
}

// Funcionalidad modular para colapsar y recordar el estado de la barra lateral
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const mainContent = document.getElementById('main-content');

    if (!sidebar || !toggleBtn) return;

    // Restaurar estado guardado en localStorage
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed) {
        sidebar.classList.add('collapsed');
        mainContent?.classList.add('sidebar-collapsed');
    }

    // Alternar y persistir estado al hacer clic
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        mainContent?.classList.toggle('sidebar-collapsed');

        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    });
}

// Inicialización de módulos principales al cargar el DOM
document.addEventListener('DOMContentLoaded', function() {
    initFormSubmitProtection();
    initSidebar();
});



