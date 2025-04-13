<?php if($_SESSION['name'] == "Acceder"): ?>
    <!-- Mostrar solo para no administradores -->
    <button class="btnProfile" type="button" id="btnAccessLogin" aria-hidden="true">
        <i class="fa-solid fa-circle-user" aria-hidden="true"></i>
        <span>Acceder</span>
    </button>
<?php else: ?>
    <button class="btnProfile" type="button" id="btnProfile" title="Cerrar Sesión">
        <i class="fa-solid fa-circle-user" aria-hidden="true"></i>
        <span>Cerrar Sesión: <?php echo htmlspecialchars($_SESSION['name']); ?></span>
    </button>
<?php endif; ?>

<button class="closeMenu" type="button" id="closeMenu" title="Cerrar Menú">
    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
    <span>Volver</span>
</button>
