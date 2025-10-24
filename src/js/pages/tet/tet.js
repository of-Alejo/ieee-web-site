/**
 * Funciones específicas para la página TET
 * Maneja el contador regresivo y los iconos 3D con Three.js
 */

function tet() {
    // Iniciar contador solo si existe el elemento
    const counterElement = document.getElementById('counter-time-tet');
    if (counterElement) {
        timerTet();
    }

    // Configuración de iconos 3D
    const icons = [
        { id: "icon-3d-1", shape: "star" },
        { id: "icon-3d-2", shape: "pyramid" },
        { id: "icon-3d-3", shape: "sphere" },
        { id: "icon-3d-4", shape: "torus" },
        { id: "icon-3d-5", shape: "dodecahedron" },
        { id: "icon-3d-6", shape: "torusKnot" }
    ];

    // Crear iconos solo si Three.js está disponible
    if (typeof THREE !== 'undefined') {
        icons.forEach(function(icon) {
            const container = document.getElementById(icon.id);
            if (container) {
                create3DIcon(container, icon.shape);
            }
        });
    }
}

/**
 * Crea un ícono 3D usando Three.js
 * @param {HTMLElement} container - Contenedor DOM
 * @param {string} shape - Tipo de geometría
 */
function create3DIcon(container, shape) {
    const width = isMobile ? 170 : 150;
    const height = isMobile ? 170 : 150;

    // Configuración de escena
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(80, width / height, 0.1, 1000);
    
    // Renderer optimizado
    const renderer = new THREE.WebGLRenderer({ 
        alpha: true, 
        antialias: !isMobile, // Desactivar antialiasing en móviles para rendimiento
        powerPreference: "high-performance"
    });
    
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2)); // Limitar a 2x para rendimiento
    container.appendChild(renderer.domElement);

    // Seleccionar geometría según shape
    const geometry = getGeometry(shape);
    
    // Material optimizado
    const material = new THREE.MeshStandardMaterial({
        color: 0x6d0801,
        metalness: 0.7,
        roughness: 0.2,
        flatShading: true
    });

    const mesh = new THREE.Mesh(geometry, material);
    scene.add(mesh);

    // Iluminación optimizada
    const light1 = new THREE.PointLight(0xd5ba65, 2.5, 100);
    light1.position.set(2, 2, 5);
    scene.add(light1);

    const light2 = new THREE.AmbientLight(0xd5ba65, 0.8);
    scene.add(light2);

    camera.position.z = 2;

    // Animación con requestAnimationFrame optimizado
    let animationId;
    function animate() {
        animationId = requestAnimationFrame(animate);
        mesh.rotation.x += 0.01;
        mesh.rotation.y += 0.01;
        renderer.render(scene, camera);
    }
    animate();

    // Limpiar recursos cuando el elemento ya no sea visible (opcional)
    container.addEventListener('DOMNodeRemovedFromDocument', function() {
        cancelAnimationFrame(animationId);
        geometry.dispose();
        material.dispose();
        renderer.dispose();
    });
}

/**
 * Retorna la geometría según el tipo de forma
 * @param {string} shape - Tipo de geometría
 * @returns {THREE.Geometry}
 */
function getGeometry(shape) {
    const geometries = {
        star: createStarGeometry3D(),
        pyramid: new THREE.ConeGeometry(0.8, 1.2, 8),
        sphere: new THREE.SphereGeometry(0.8, isMobile ? 32 : 64, isMobile ? 32 : 64),
        torus: new THREE.TorusGeometry(0.8, 0.2, 16, 100),
        dodecahedron: new THREE.DodecahedronGeometry(0.8),
        torusKnot: new THREE.TorusKnotGeometry(0.5, 0.2, 100, 16)
    };
    
    return geometries[shape] || new THREE.BoxGeometry(1, 1, 1);
}

/**
 * Crea una geometría de estrella 3D personalizada
 * @returns {THREE.ExtrudeGeometry}
 */
function createStarGeometry3D() {
    const shape = new THREE.Shape();
    const outerRadius = 0.8;
    const innerRadius = 0.4;
    const points = 5;

    for (let i = 0; i < points * 2; i++) {
        const angle = (i / (points * 2)) * Math.PI * 2;
        const radius = i % 2 === 0 ? outerRadius : innerRadius;
        const x = Math.cos(angle) * radius;
        const y = Math.sin(angle) * radius;
        
        if (i === 0) {
            shape.moveTo(x, y);
        } else {
            shape.lineTo(x, y);
        }
    }
    shape.closePath();

    const extrudeSettings = {
        depth: 0.5,
        bevelEnabled: true,
        bevelThickness: 0.1,
        bevelSize: 0.1,
        bevelSegments: 5
    };

    return new THREE.ExtrudeGeometry(shape, extrudeSettings);
}

/**
 * Contador regresivo para el evento TET 2025
 */
function timerTet() {
    const eventDay = new Date('2025-11-06T08:00:00').getTime();
    const elem = document.getElementById('counter-time-tet');
    
    if (!elem) return;

    // Función de actualización
    function updateCounter() {
        const now = new Date().getTime();
        const rest = eventDay - now;

        if (rest <= 0) {
            elem.innerText = "¡El TET 2025 comenzó!";
            clearInterval(intervalId);
            return;
        }

        const days = Math.floor(rest / (1000 * 60 * 60 * 24));
        const hours = Math.floor((rest % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((rest % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((rest % (1000 * 60)) / 1000);

        elem.innerText = days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
    }

    // Ejecutar inmediatamente y luego cada segundo
    updateCounter();
    const intervalId = setInterval(updateCounter, 1000);
}
