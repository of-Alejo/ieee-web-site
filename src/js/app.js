// Variables globales
const baseUrl = window.location.origin;
const isMobile = window.innerWidth <= 768;

// Páginas permitidas, añadir nuevas rutas aquí
const routes = {
    index: '/',
    eventos: '/eventos',
    tet: '/tet',
    anuario: '/anuario',
    galeria: '/galeria',
    organizacion: '/organizacion',
    e404: '/404',
    e500: '/500'
};

// Función principal (main), se ejecuta siempre
document.addEventListener("DOMContentLoaded", () => {
    const currentPath = window.location.pathname;
    const isAllowedRoute = Object.values(routes).includes(currentPath);

    // Verifica si la URL actual está dentro de las rutas permitidas
    if (isAllowedRoute) {
        activateFunctions(); // Activa funciones específicas según la página

        (isMobile) ? menuBurger() : null;
        initializeParticles();
        if (window.location.pathname == "/tet")
        tet();
    } 
});

function activateFunctions() {
    const pathActions = {
        //[routes.index]: initIndexApp
    };
    
    if (pathActions[window.location.pathname]) {
        pathActions[window.location.pathname]();
    }
}

