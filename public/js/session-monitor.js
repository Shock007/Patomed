/**
 * Monitor de Sesión para Patomed
 * Cierra la sesión automáticamente al cerrar la pestaña
 */

(function() {
    'use strict';

    // Detectar cierre de pestaña/navegador
    window.addEventListener('beforeunload', function(event) {
        // Enviar solicitud síncrona para cerrar sesión
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/logout-auto', false); // false = síncrono
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
        
        try {
            xhr.send(JSON.stringify({ auto_logout: true }));
        } catch (e) {
            console.error('Error al cerrar sesión:', e);
        }
    });

    // Alternativa con sendBeacon (más moderno y confiable)
    window.addEventListener('unload', function(event) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (navigator.sendBeacon) {
            const formData = new FormData();
            formData.append('_token', token);
            formData.append('auto_logout', 'true');
            
            navigator.sendBeacon('/logout-auto', formData);
        }
    });

    // Detectar inactividad (opcional, para mayor seguridad)
    let inactivityTimer;
    const INACTIVITY_LIMIT = 5 * 60 * 1000; // 5 minutos

    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(function() {
            // Redirigir al login por inactividad
            window.location.href = '/logout?reason=inactivity';
        }, INACTIVITY_LIMIT);
    }

    // Escuchar eventos de actividad del usuario
    ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(function(event) {
        document.addEventListener(event, resetInactivityTimer, true);
    });

    // Iniciar el temporizador
    resetInactivityTimer();

    // Limpiar al descargar la página
    window.addEventListener('beforeunload', function() {
        clearTimeout(inactivityTimer);
    });

    console.log('Monitor de sesión Patomed activado');
})();