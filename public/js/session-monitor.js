/**
 * Monitor de Sesión para Patomed
 * Cierra la sesión automáticamente al cerrar la pestaña/navegador
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

    console.log('✓ Monitor de sesión Patomed activado');
    console.log('✓ La sesión se cerrará al cerrar el navegador');
})();