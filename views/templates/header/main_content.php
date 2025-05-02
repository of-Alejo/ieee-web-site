<!-- Variar el contenido según url -->
<section aria-label="Contenido principal del encabezado" aria-labelledby="header-content">
    <!-- Principales -->
    <?php if ($_SERVER['REQUEST_URI'] === '/') : ?>
        <article>
            <h1 id="header-content">RAMA IEEE<span>Unicauca</span></h1>
            <p>El IEEE es una organización sin ánimo de lucro y es considerada la mayor asociación del mundo para el desarrollo tecnológico.</p>
            <a class="btn" href="https://www.instagram.com/direct/t/101507081252221" title="Contáctanos en Instagram">Contáctanos</a>
        </article>
    <?php elseif ($_SERVER['REQUEST_URI'] === '/eventos') : ?>
        <article>
            <h1 id="header-content">Eventos<span>Unicauca</span></h1>
            <p>¿Qué hacemos? Entérate de todos los eventos que realizamos y que no te puedes perder.</p>
            <a class="btn" href="/eventos#events" title="Ver eventos">¡Quiero saber más!</a>
        </article>
    <?php elseif ($_SERVER['REQUEST_URI'] === '/tet') : ?>
        <article>
            <h1 id="header-content">TET 2025<span>Evento Insignia</span></h1>
            <p>¿Quieres saber más sobre la décima edición del Seminario de Tecnologías Emergentes de Telecomunicaciones?</p>
            <a class="btn" href="/tet#tet-2025" title="Ver más información sobre TET 2025">¡Quiero saber más!</a>
        </article>
    <?php elseif ($_SERVER['REQUEST_URI'] === '/anuario') : ?>
        <article>
            <h1 id="header-content">Anuario<span>Unicauca</span></h1>
            <p>¿Quieres saber más sobre el anuario de la Rama IEEE Unicauca?</p>
            <a class="btn" href="/anuario#timeline" title="Ver anuario">¡Ver línea de tiempo!</a>
        </article>
    <?php elseif ($_SERVER['REQUEST_URI'] === '/galeria') : ?>
        <article>
            <h1 id="header-content">Galería<span>IEEE</span></h1>
            <p>¿Quieres ver las fotos de nuestros eventos? <br>¡No te pierdas la oportunidad de revivir esos momentos especiales!</p>
            <a class="btn" href="/galeria#galery" title="Ir a la sección de Galería">¡Quiero ver!</a>
        </article>
    <?php elseif ($_SERVER['REQUEST_URI'] === '/organizacion') : ?>
        <article>
            <h1 id="header-content">Organización<span>IEEE</span></h1>
            <p>¿Quieres saber más sobre la organización y estructura de los miembros de la Rama IEEE Unicauca? <br>¡Conoce a nuestro equipo!</p>
            <a class="btn" href="/" title="Ver más información sobre la organización">¡Quiero saber más!</a>
        </article>
    <?php elseif ($_SERVER['REQUEST_URI'] === '/404') : ?>
        <article>
            <h1 id="header-content">ERROR<span>404</span></h1>
            <p>La página que buscas no existe o ha sido eliminada. <br>Puedes volver a la página de inicio haciendo clic en el logo de la Rama IEEE Unicauca.</p>
        </article>
    <?php elseif ($_SERVER['REQUEST_URI'] === '/500') : ?>
        <article>
            <h1 id="header-content">ERROR<span>500</span></h1>
            <p>Error interno del servidor. <br>Por favor, inténtalo más tarde o vuelve a la página de inicio haciendo clic en el logo de la Rama IEEE Unicauca.</p>
        </article>
    <?php endif; ?>

    <!-- Redes sociales -->
    <aside aria-label="Redes sociales de la Rama IEEE Unicauca" aria-labelledby="social-media">
        <h2 id="social-media">Síguenos en nuestras redes sociales</h2>
        <ul>
            <li>
                <a href="https://www.linkedin.com/in/ieeeunicauca/" target="_blank" aria-label="Visítanos en LinkedIn" title="LinkedIn">
                    <i class="fa-brands fa-linkedin"></i>
                </a>
            </li>
            <li>
                <a href="https://web.facebook.com/IEEEUnicauca" target="_blank" aria-label="Visítanos en Facebook" title="Facebook">
                    <i class="fa-brands fa-square-facebook"></i>
                </a>
            </li>
            <li>
                <a href="mailto:ramaieee@unicauca.edu.co" target="_blank" aria-label="Envíanos un correo" title="Correo electrónico">
                    <i class="fa-solid fa-envelope"></i>
                </a>
            </li>
            <li>
                <a href="https://www.instagram.com/ieeeunicauca/" target="_blank" aria-label="Visítanos en Instagram" title="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </li>
            <li>
                <a href="https://www.tiktok.com/@ieeeunicauca" target="_blank" aria-label="Visítanos en TikTok" title="TikTok">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
            </li>
        </ul>
    </aside>
</section>