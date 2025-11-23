<!-- Menu hamburguesa -->
<button type="button" id="burgerMenu" title="Abrir menú principal" aria-expanded="false" aria-controls="menu" aria-label="Abrir menú principal">
    <svg aria-hidden="true" style="width: 1.5em; height: 1.5em;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor">
        <path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/>
    </svg>
</button>

<header class="header">
    <!-- Partículas -->
    <div id="particles-js"></div> <!-- stats - count particles -->

    <!-- Nav -->
    <?php include_once __DIR__ . '/nav.php'; ?>

    <!-- Contenido Principal -->
    <?php include_once __DIR__ . '/main_content.php'; ?>
</header>