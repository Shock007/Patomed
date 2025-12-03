/**
 * Monitor de Sesión para Patomed
 * Sistema de timeout por inactividad de 2 minutos
 */

(function() {
    'use strict';

    // Configuración
    const INACTIVITY_TIMEOUT = 120000; // 2 minutos en milisegundos
    const CHECK_INTERVAL = 5000; // Verificar cada 5 segundos
    const WARNING_TIME = 30000; // Mostrar advertencia 30 segundos antes

    let lastActivityTime = Date.now();
    let inactivityTimer = null;
    let warningTimer = null;
    let warningModal = null;

    // **Eventos que se consideran "actividad del usuario"**
    const activityEvents = [
        'mousedown',
        'mousemove',
        'keypress',
        'scroll',
        'touchstart',
        'click'
    ];

    /**
     * Actualizar el timestamp de última actividad
     */
    function updateActivity() {
        lastActivityTime = Date.now();
        console.log('✓ Actividad detectada - Timer reseteado');
        
        // Limpiar advertencia si existe
        if (warningModal) {
            warningModal.hide();
            warningModal = null;
        }
    }

    /**
     * Mostrar advertencia de cierre inminente
     */
    function showWarning() {
        // Crear modal de advertencia si no existe
        if (!document.getElementById('sessionWarningModal')) {
            const modalHTML = `
                <div class="modal fade" id="sessionWarningModal" tabindex="-1" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Sesión por Expirar
                                </h5>
                            </div>
                            <div class="modal-body text-center">
                                <p class="mb-3">Su sesión se cerrará en <strong id="countdown">30</strong> segundos por inactividad.</p>
                                <p class="text-muted small">Mueva el mouse o presione una tecla para mantener la sesión activa.</p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-primary" onclick="location.reload()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Mantener Sesión Activa
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        // Mostrar el modal
        const modalElement = document.getElementById('sessionWarningModal');
        warningModal = new bootstrap.Modal(modalElement);
        warningModal.show();

        // Countdown de 30 segundos
        let countdown = 30;
        const countdownElement = document.getElementById('countdown');
        
        const countdownInterval = setInterval(() => {
            countdown--;
            if (countdownElement) {
                countdownElement.textContent = countdown;
            }
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }

    /**
     * Cerrar sesión por inactividad
     */
    function logoutDueToInactivity() {
        console.warn('⏰ Sesión cerrada por inactividad');
        
        // Redirigir al logout
        window.location.href = '/login?timeout=1';
    }

    /**
     * Verificar tiempo de inactividad
     */
    function checkInactivity() {
        const currentTime = Date.now();
        const inactiveTime = currentTime - lastActivityTime;

        console.log(`⏱ Tiempo inactivo: ${Math.floor(inactiveTime / 1000)} segundos`);

        // Si faltan 30 segundos para el timeout, mostrar advertencia
        if (inactiveTime >= (INACTIVITY_TIMEOUT - WARNING_TIME) && !warningModal) {
            showWarning();
        }

        // Si se superó el tiempo de inactividad, cerrar sesión
        if (inactiveTime >= INACTIVITY_TIMEOUT) {
            logoutDueToInactivity();
        }
    }

    /**
     * Inicializar el sistema de monitoreo
     */
    function init() {
        console.log('✓ Sistema de monitoreo de sesión iniciado');
        console.log(`⏱ Timeout de inactividad: ${INACTIVITY_TIMEOUT / 1000} segundos (${INACTIVITY_TIMEOUT / 60000} minutos)`);

        // Registrar eventos de actividad
        activityEvents.forEach(event => {
            document.addEventListener(event, updateActivity, true);
        });

        // Verificar inactividad periódicamente
        setInterval(checkInactivity, CHECK_INTERVAL);

        // Detectar si viene de un timeout
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('timeout') === '1') {
            const alert = document.createElement('div');
            alert.className = 'alert alert-warning alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="bi bi-clock-history me-2"></i>
                Su sesión anterior se cerró por inactividad (2 minutos sin uso)
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            // Auto-ocultar después de 5 segundos
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
    }

    // **ELIMINAR CÓDIGO DE CIERRE AL CAMBIAR PESTAÑAS**
    // Ya no usamos beforeunload ni unload

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();