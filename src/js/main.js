// Variables globales
const baseUrl = window.location.origin;
const isMobile = window.innerWidth <= 768;

// Páginas permitidas
const routes = {
    index: '/',
    eventos: '/eventos',
    tet: '/tet',
    tet_ingreso: '/tet/ingreso',
    anuario: '/anuario',
    galeria: '/galeria',
    organizacion: '/organizacion',
    organizacion: '/capitulos',
    e404: '/404',
    e500: '/500'
};

// Inicialización principal
document.addEventListener("DOMContentLoaded", function() {
    const currentPath = window.location.pathname;
    const isAllowedRoute = Object.values(routes).includes(currentPath);
    
    if (!isAllowedRoute) return;
    
    // Funciones globales
    if (isMobile) menuBurger();
    
    // Inicializar particles solo si la función y librería existen
    if (typeof initializeParticles === 'function' && typeof particlesJS !== 'undefined') {
        // Usar requestIdleCallback para ejecutar cuando el navegador esté libre
        if ('requestIdleCallback' in window) {
            requestIdleCallback(initializeParticles);
        } else {
            setTimeout(initializeParticles, 100);
        }
    }

    // Activar funciones específicas por página
    activateFunctions();
});

// Activar funciones específicas según ruta
function activateFunctions() {
    const pathActions = {
        '/tet': tet,
        '/tet/ingreso': tet_ingreso
    };
    
    const action = pathActions[window.location.pathname];
    
    if (action && typeof action === 'function') {
        action();
    }
}