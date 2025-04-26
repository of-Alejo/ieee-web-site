function initializeParticles() {
    const isMobile = window.innerWidth <= 768; // Detectar si es móvil

    particlesJS("particles-js", {
        particles: {
            number: {
                value: isMobile ? 16 : 60,
                density: { enable: false, value_area: 946.0439595093181 }
            },
            color: { value: "#ffffff" },
            shape: {
                type: "circle",
                stroke: { width: 1, color: "#000000" },
                polygon: { nb_sides: 5 },
                image: { src: "img/github.svg", width: 100, height: 100 }
            },
            opacity: {
                value: 0.3232316861656837,
                random: true,
                anim: { enable: false, speed: 1, opacity_min: 0.1, sync: false }
            },
            size: {
                value: 3.9418498312888257,
                random: true,
                anim: { enable: true, speed: 2, size_min: 0.1, sync: true }
            },
            line_linked: {
                enable: true,
                distance: isMobile ? 140 : 200,
                color: "#ffffff",
                opacity: .5,
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
                attract: { enable: true, rotateX: 9000, rotateY: 9000 }
            }
        },
        interactivity: {
            detect_on: "canvas",
            events: {
                onhover: { enable: true, mode: "grab" },
                onclick: { enable: true, mode: "push" },
                resize: true
            },
            modes: {
                grab: { distance: isMobile ? 120 : 180, line_linked: { opacity: 1 } },
                push: { particles_nb: isMobile ? 1 : 2 },
                repulse: { distance: 200, duration: 0.4 },
            }
        },
        retina_detect: true
    });
}

// Inicializar partículas al cargar la página
document.addEventListener("DOMContentLoaded", initializeParticles);