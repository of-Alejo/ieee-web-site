function tet() {
    timerTet();
    const icons = [
        { id: "icon-3d-1", shape: "star" }, // Estrella 3D
        { id: "icon-3d-2", shape: "pyramid" },
        { id: "icon-3d-3", shape: "sphere" },
        { id: "icon-3d-4", shape: "torus" },
        { id: "icon-3d-5", shape: "dodecahedron" },
        { id: "icon-3d-6", shape: "torusKnot" },
    ];

    icons.forEach(icon => {
        const container = document.getElementById(icon.id);
        if (container) {
            create3DIcon(container, icon.shape);
        }
    });
}

function create3DIcon(container, shape) {
    const width = isMobile ? 170 : 150; // Tamaño del contenedor
    const height = isMobile ? 170 : 150;

    // Crear la escena
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(80, width / height, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true }); // Activar antialiasing
    renderer.setSize(width, height);
    renderer.setPixelRatio(window.devicePixelRatio * 1.5); // Mejorar la calidad del renderizado
    container.appendChild(renderer.domElement);

    // Crear geometría
    let geometry;
    if (shape === "star") {
        geometry = createStarGeometry3D(); // Estrella 3D personalizada
    } else if (shape === "pyramid") {
        geometry = new THREE.ConeGeometry(0.8, 1.2, 8); // Pirámide con más segmentos
    } else if (shape === "sphere") {
        geometry = new THREE.SphereGeometry(0.8, 64, 64); // Esfera con más segmentos
    } else if (shape === "torus") {
        geometry = new THREE.TorusGeometry(0.8, 0.2, 16, 100); // Toro (anillo)
    } else if (shape === "dodecahedron") {
        geometry = new THREE.DodecahedronGeometry(0.8); // Dodecaedro
    } else if (shape === "torusKnot") {
        const radius =  2.0;  
        const tubeRadius =  1.0;  
        const radialSegments = 10;  
        const tubularSegments =  36;  
        const p =  2;  
        const q =  3; 
        geometry = new THREE.TorusKnotGeometry(0.5, 0.2, 100, 100 );; // Tetraedro
    }

    // Cambiar el material para incluir caras visibles
    const material = new THREE.MeshStandardMaterial({
        color: 0x6d0801, // Color red
        metalness: 0.7, // Efecto metálico
        roughness: 0.2, // Superficie más suave
        flatShading: true,
    });

    const mesh = new THREE.Mesh(geometry, material);
    scene.add(mesh);

    // Añadir luces para mejorar la visibilidad
    const light1 = new THREE.PointLight(0xd5ba65, 2.5, 100);
    light1.position.set(2, 2, 5);
    scene.add(light1);

    const light2 = new THREE.AmbientLight(0xd5ba65, 0.8); // Luz ambiental para suavizar sombras
    scene.add(light2);

    camera.position.z = 2; // Ajustar la posición de la cámara para acomodar el tamaño

    // Animar la geometría
    function animate() {
        requestAnimationFrame(animate);
        mesh.rotation.x += 0.01;
        mesh.rotation.y += 0.01;
        renderer.render(scene, camera);
    }
    animate();
}

// Función para crear una estrella 3D personalizada
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

    // Extruir la forma para convertirla en 3D
    const extrudeSettings = {
        depth: 0.5, // Profundidad de la extrusión
        bevelEnabled: true, // Habilitar biselado
        bevelThickness: 0.1, // Grosor del bisel
        bevelSize: 0.1, // Tamaño del bisel
        bevelSegments: 5, // Segmentos del bisel
    };

    return new THREE.ExtrudeGeometry(shape, extrudeSettings);
}

function timerTet(){
    // Fecha objetivo: 6 de noviembre de 2025, 00:00:00
    const eventDay = new Date('2025-11-06T08:00:00').getTime();
    const elem = document.getElementById('counter-time-tet');

    setInterval(() => {
        const now = new Date().getTime();
        const rest = eventDay - now;

        if (rest <= 0) {
        elem.innerText = "¡El TET 2025 comenzó!";
        return;
        }

        const days = Math.floor(rest / (1000 * 60 * 60 * 24));
        const hours = Math.floor((rest % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((rest % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((rest % (1000 * 60)) / 1000);

        elem.innerText = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }, 1000);
}