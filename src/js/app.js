// Variables globales
const baseUrl = window.location.origin;
let registerPhoneInputInt;

// Páginas permitidas
const routes = {
    index: '/'
};
// Función principal (main), se ejecuta siempre
document.addEventListener("DOMContentLoaded", () => {
    const currentPath = window.location.pathname;
    
    const isAllowedRoute = Object.values(routes).includes(currentPath);

    // Verifica si la URL actual está dentro de las rutas permitidas
    // Estas funciones se encuentran en la carpeta functions
    if (isAllowedRoute) {
        activateFunctions();
    } 
});

function activateFunctions() {
    // Activa funcionalidades específicas según la página actual
    const pathActions = {
        [routes.index]: initIndexApp,
        [routes.search]: initSearch,
        [routes.blog]: initBlog
    };
    
    if (pathActions[window.location.pathname]) {
        pathActions[window.location.pathname]();
    }
}

