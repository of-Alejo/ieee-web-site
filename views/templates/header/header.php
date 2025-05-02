<!-- Menu hamburguesa -->
<button type="button" id="burgerMenu" title="Abrir menú principal" aria-expanded="false" aria-controls="menu" aria-label="Abrir menú principal">
    <i class="fa-solid fa-bars" aria-hidden="true"></i>
</button>

<header class="header">
    <!-- Partículas -->
    <div id="particles-js"></div> <!-- stats - count particles -->

    <!-- Nav -->
    <?php include_once __DIR__ . '/nav.php'; ?>

    <!-- Contenido Principal -->
    <?php include_once __DIR__ . '/main_content.php'; ?>
</header>