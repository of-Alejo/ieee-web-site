<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-language" content="es">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="language" content="es">

    <!-- Indexación en Google -->
    <meta name="robots" content="index, follow">

    <!-- Información -->
    <title>IEEE Unicauca <?php echo $title; ?></title>
    <meta name="title" content="<?php echo $metaTitle; ?>">
    <meta name="description" content="<?php echo $descriptionSite; ?>">
    <meta name="author" content="UNICAUCA"> <meta name="publisher" content="DAVID ALEJANDRO ORTEGA FLÓREZ">
    <meta name="geo.position" content="2.446339294065221, -76.59841050197932">

    <!-- Tema color -->
    <meta name="theme-color" content="#124072">

    <!-- Favicon -->
    <link rel="icon" href="/build/img/logos/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/build/img/logos/favicon.ico" type="image/x-icon">

    <!-- Copyright -->
    <meta name="copyright" content="2025">

    <!-- Sitio Principal -->
    <link rel="canonical" href="http://ieee.unicauca.edu.co/">

    <!-- Redes Sociales -->
    <!-- Para Facebook -->
    <meta property="fb:pages" content="IEEEUnicauca">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:description" content="<?php echo $descriptionSite; ?>">
    <meta property="og:site_name" content="Rama IEEE Unicauca">
    <meta property="og:image" content="http://ieee.unicauca.edu.co/build/img/logos/logo_ext.png">
    <meta property="og:image:width" content="821">
    <meta property="og:url" content="http://ieee.unicauca.edu.co/">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="http://ieee.unicauca.edu.co/">
    <meta name="twitter:title" content="<?php echo $title; ?>">
    <meta name="twitter:description" content="<?php echo $descriptionSite; ?>">
    <meta name="twitter:image" content="http://ieee.unicauca.edu.co/build/img/logos/logo_ext.png">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
    <link rel="preload" href="/build/css/app.css" as="style">
    <link rel="stylesheet" href="/build/css/app.css">

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0/particles.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
    <script rel="preload" src="build/js/main.js" as="script" defer></script>
</head>

<body>
    <?php include_once __DIR__ .'/templates/header/header.php'; ?>
    <?php echo $content; ?>
    <?php include_once __DIR__ .'/templates/footer/footer.php'; ?>
</body>

</html>