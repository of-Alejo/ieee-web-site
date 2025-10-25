/**
 * Inicializa el sistema de partículas del header
 * Solo se ejecuta si particles.js está cargado
 */
function initializeParticles() {
    // Verificar que particlesJS esté disponible
    if (typeof particlesJS === 'undefined') {
        console.warn('particles.js no está cargado');
        return;
    }

    const particlesContainer = document.getElementById('particles-js');
    if (!particlesContainer) {
        console.warn('Contenedor #particles-js no encontrado');
        return;
    }

    // Configuración optimizada según dispositivo
    const particleConfig = {
        particles: {
            number: {
                value: isMobile ? 12 : 40,
                density: { 
                    enable: false, 
                    value_area: 946 
                }
            },
            color: { 
                value: "#ffffff" 
            },
            shape: {
                type: "circle",
                stroke: { 
                    width: 1, 
                    color: "#000000" 
                }
            },
            opacity: {
                value: 0.38,
                random: true,
                anim: { 
                    enable: false, 
                    speed: 1, 
                    opacity_min: 0.1, 
                    sync: false 
                }
            },
            size: {
                value: isMobile ? 3 : 4,
                random: true,
                anim: { 
                    enable: true, 
                    speed: 2, 
                    size_min: 0.1, 
                    sync: true 
                }
            },
            line_linked: {
                enable: true,
                distance: isMobile ? 120 : 160,
                color: "#ffffff",
                opacity: 0.5,
                width: 2
            },
            move: {
                enable: true,
                speed: isMobile ? 2 : 3,
                direction: "right",
                random: true,
                straight: true,
                out_mode: "out",
                bounce: true,
                attract: { 
                    enable: true, 
                    rotateX: 9000, 
                    rotateY: 9000 
                }
            }
        },
        interactivity: {
            detect_on: "canvas",
            events: {
                onhover: { 
                    enable: !isMobile, // Desactivar hover en móviles
                    mode: "grab" 
                },
                onclick: { 
                    enable: true, 
                    mode: "push" 
                },
                resize: true
            },
            modes: {
                grab: { 
                    distance: isMobile ? 120 : 200, 
                    line_linked: { opacity: 1 } 
                },
                push: { 
                    particles_nb: isMobile ? 1 : 2 
                },
                repulse: { 
                    distance: 200, 
                    duration: 0.4 
                }
            }
        },
        retina_detect: true
    };

    // Inicializar particles.js
    particlesJS("particles-js", particleConfig);
}
