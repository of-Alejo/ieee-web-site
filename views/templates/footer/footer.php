<footer class="footer">
    <div>
        <a href="/terminos-condiciones" target="_blank" aria-label="Términos y Condiciones" title="Términos y Condiciones" rel="terms">Términos y Condiciones</a>
        <a href="/politica-privacidad" target="_blank" aria-label="Política de Privacidad" title="Política de Privacidad" rel="privacy">Política de Privacidad</a>
    </div>
    <div>
        <p>&copy; <?php 
            $initial = 2025; // Año de inicio del contenido o del sitio web
            $actual = date("Y");
            echo $initial . (($initial != $actual) ? "- . {$actual}" : ''); ?> 
            
        </p>
    </div>
</footer>