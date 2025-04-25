<!-- Menu hamburguesa -->
<button type="button" id="burgerMenu" title="Abrir menú principal" aria-expanded="false">
    <i class="fa-solid fa-bars" aria-hidden="true"></i>
</button>

<header class="header">
    <nav aria-label="Navegación principal del sitio">
        <a href="/" aria-label="Ir a la sección NOSOTROS" title="NOSOTROS">NOSOTROS</a>
        <a href="/eventos" aria-label="Ir a la sección EVENTOS" title="EVENTOS">EVENTOS</a>
        <a href="/tet" aria-label="Ir a la sección TET 2025" title="TET 2025">TET 2025</a>
        <a href="/" aria-label="Inicio">
            <picture>
                <source srcset="/build/img/logos/logo.webp" type="image/webp">
                <img loading="lazy" width="150" height="150" src="/build/img/logos/logo.png" alt="Logo de la Rama IEEE Unicauca">
            </picture>
        </a>
        <a href="/yearbook" aria-label="Ir a la sección ANUARIO" title="ANUARIO">ANUARIO</a>
        <a href="/galery" aria-label="Ir a la sección GALERÍA" title="GALERÍA">GALERÍA</a>
        <a href="/organization" aria-label="Ir a la sección ORGANIZACIÓN" title="ORGANIZACIÓN">ORGANIZACIÓN</a>
    </nav>

    <section aria-label="Contenido principal del encabezado" aria-labelledby="header-content">
        <?php if ($_SERVER['REQUEST_URI'] == '/') : ?>
            <article>
                <h1 id="header-content">RAMA IEEE<span>Unicauca</span></h1>
                <p>El IEEE es una organización sin ánimo de lucro y es considerada la mayor asociación del mundo para el desarrollo tecnológico.</p>
                <a class="btn" href="https://www.instagram.com/direct/t/101507081252221" title="Contáctanos en Instagram">Contáctanos</a>
            </article>
        <?php endif; ?>
        <?php if ($_SERVER['REQUEST_URI'] == '/eventos') : ?>
            <article>
                <h1 id="header-content">Eventos<span>Unicauca</span></h1>
                <p>¿Qué hacemos? Entérate de todos los eventos que realizamos y que no te puedes perder.</p>
                <a class="btn" href="/eventos#timeline" title="Ver eventos">¡Quiero ver!</a>
            </article>
        <?php endif; ?>
        <?php if ($_SERVER['REQUEST_URI'] == '/tet') : ?>
            <article>
                <h1 id="header-content">TET 2025<span>Evento Insignia</span></h1>
                <p>¿Quieres saber más sobre la décima edición del Seminario de Tecnologías Emergentes de Telecomunicaciones?</p>
                <a class="btn" href="/tet#timeline" title="Ver más información sobre TET 2025">¡Quiero saber más!</a>
            </article>
        <?php endif; ?>
        <aside aria-label="Redes sociales de la Rama IEEE Unicauca" aria-labelledby="social-media">
            <h2 id="social-media">Síguenos en nuestras redes sociales</h2>
            <ul>
                <li>
                    <a href="https://www.linkedin.com/in/ieeeunicauca/" aria-label="Visítanos en LinkedIn" title="LinkedIn">
                        <i class="fa-brands fa-linkedin"></i>
                    </a>
                </li>
                <li>
                    <a href="https://web.facebook.com/IEEEUnicauca" aria-label="Visítanos en Facebook" title="Facebook">
                        <i class="fa-brands fa-square-facebook"></i>
                    </a>
                </li>
                <li>
                    <a href="mailto:ramaieee@unicauca.edu.co" aria-label="Envíanos un correo" title="Correo electrónico">
                        <i class="fa-solid fa-envelope"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/ieeeunicauca/" aria-label="Visítanos en Instagram" title="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.tiktok.com/@ieeeunicauca" aria-label="Visítanos en TikTok" title="TikTok">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                </li>
            </ul>
        </aside>
    </section>
</header>