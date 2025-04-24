<footer class="footer">
    <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%">
        <path d="M-2.54,64.64 C303.89,145.56 219.24,53.80 499.72,149.52 L499.15,151.48 L0.00,150.00 Z" style="stroke: none; fill: #123f72"></path>
    </svg>
    <div class="footer-img">
        <picture>
            <source srcset="/build/img/templates/footer/footer.webp" type="image/webp">
            <img loading="lazy" width="200" height="300" src="/build/img/templates/footer/footer.png" alt="Imagen decorativa del footer">
        </picture>
    </div>
    <div class="footer-info">
        <div class="footer-info-initial">
            <h3>¿Listo para empezar?</h3>
            <a class="btn" href="https://www.instagram.com/direct/t/101507081252221" title="Contáctanos en Instagram">Contáctanos</a>
        </div>
        <div class="footer-info-tools">
            <div>
                <h4>RAMA IEEE</h4>
                <a href="/Nosotros" title="Conoce más sobre nosotros">Nosotros</a>
                <a href="/Misión" title="Nuestra misión">Misión</a>
                <a href="/Visión" title="Nuestra visión">Visión</a>
                <a href="/Informe anual" title="Consulta nuestro informe anual">Informe anual</a>
            </div>
            <div>
                <h4>EVENTOS</h4>
                <a href="/tet2022" title="Detalles del TET 2022">TET 2022</a>
                <a href="/tet2025" title="Detalles del TET 2025">TET 2025</a>
            </div>
            <div>
                <h4>ORGANIZACIÓN</h4>
                <a href="/" title="Conoce a la Junta Directiva">Junta Directiva</a>
                <a href="/Misión" title="Conoce los comités">Comités</a>
            </div>
        </div>
    </div>
    <div class="footer-final">
        <p>&copy; <?php
            $initial = 2022;
            $actual = date("Y");
            echo $initial . (($initial != $actual) ? "-{$actual}" : '') . ' IEEE - Todos los derechos reservados'; ?>
        </p>
        <ul class="footer-social">
            <li>
                <a href="https://www.linkedin.com/in/ieeeunicauca/" aria-label="Visítanos en LinkedIn" title="LinkedIn" rel="noopener noreferrer" target="_blank">
                    <i class="fa-brands fa-linkedin"></i>
                </a>
            </li>
            <li>
                <a href="https://web.facebook.com/IEEEUnicauca" aria-label="Visítanos en Facebook" title="Facebook" rel="noopener noreferrer" target="_blank">
                    <i class="fa-brands fa-square-facebook"></i>
                </a>
            </li>
            <li>
                <a href="mailto:ramaieee@unicauca.edu.co" aria-label="Envíanos un correo" title="Correo electrónico">
                    <i class="fa-solid fa-envelope"></i>
                </a>
            </li>
            <li>
                <a href="https://www.instagram.com/ieeeunicauca/" aria-label="Visítanos en Instagram" title="Instagram" rel="noopener noreferrer" target="_blank">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </li>
            <li>
                <a href="https://www.tiktok.com/@ieeeunicauca" aria-label="Visítanos en TikTok" title="TikTok" rel="noopener noreferrer" target="_blank">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
            </li>
        </ul>
    </div>
</footer>