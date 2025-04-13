<!-- Menu hamburguesa -->
<button type="button" id="burgerMenu" title="Main Menu">
    <i class="fa-solid fa-bars fa-beat-fade" aria-hidden="true"></i>
</button>

<header class="header">
    <?php include_once __DIR__ . '/nav.php'; ?>
    <?php include_once __DIR__ . '/profile.php'; ?>
</header>

<?php if(getSubDomain() != '/admin'): ?>
    <!-- Social Media -->
    <?php include_once __DIR__ . '/../../extra/socialMedia/socialMedia.php'; ?>

    <!-- ChatBot -->
    <?php include_once __DIR__ . '/../../extra/chatBot/chatBot.php'; ?>

    <!-- Book Citas -->
    <?php include_once __DIR__ . '/../../extra/citas/citas.php'; ?>

<?php endif; ?>
