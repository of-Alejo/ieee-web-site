<footer class="footer">
    <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%">
        <path d="M-2.54,64.64 C303.89,145.56 219.24,53.80 499.72,149.52 L499.15,151.48 L0.00,150.00 Z" style="stroke: none; fill: #123f72"></path>
    </svg>
    <div class="footer-img">
        <picture>
            <source srcset="/build/img/img_2022/pages/home/footer.webp" type="image/webp">
            <img loading="lazy" width="200" height="300" src="/build/img/img_2022/pages/home/footer.png" alt="Imagen footer">
        </picture>
    </div>
    <div class="footer-info">
        <div class="footer-info-initial">
            <div><h3>Ready to get started?</h3></div>
            <div>
                <a class="btn" href="https://www.instagram.com/direct/t/101507081252221">Contáctanos</a>
            </div>
        </div>
        <div class="footer-info-tools">
            <div>
                <h4>RAMA IEEE</h4>
                <a href="/Nosotros">Nosotros</a>
                <a href="/Misión">Misión</a>
                <a href="/Visión">Visión</a>
                <a href="/Informe anual">Informe anual</a>
            </div>
            <div>
                <h4>EVENTOS</h4>
                <a href="/tet2022">TET 2022</a>
                <a href="/tet2025">TET 2025</a>
            </div>
            <div>
                <h4>ORGANIZACIÓN</h4>
                <a href="/">Junta Directiva</a>
                <a href="/Misión">Comités</a>
            </div>
        </div>
    </div>
    <div class="footer-final">
        <div>
            <p>&copy; <?php
                $initial = 2022;
                $actual = date("Y");
                echo $initial . (($initial != $actual) ? "-  {$actual}" : '') . ' IEEE - Todos los derechos reservados'; ?>
            </p>
        </div>
        <div>
            <ul>
                <li><a href="https://www.linkedin.com/in/ieeeunicauca/">
                    <i class="fa-brands fa-linkedin" style="color: #ffffff;"></i>
                </a></li>
                <li><a href="https://web.facebook.com/IEEEUnicauca">
                    <i class="fa-brands fa-square-facebook" style="color: #ffffff;"></i>
                </a></li>
                <li><a href="mailto:ramaieee@unicauca.edu.co">
                    <i class="fa-solid fa-envelope" style="color: #ffffff;"></i>
                </a></li>
                <li><a href="https://www.instagram.com/ieeeunicauca/">
                    <i class="fa-brands fa-instagram" style="color: #ffffff;"></i>
                </a></li>
                <li><a href="https://www.tiktok.com/@ieeeunicauca">
                    <i class="fa-brands fa-tiktok" style="color: #ffffff;"></i>
                </a></li>
            </ul>
        </div>
    </div>
</footer>