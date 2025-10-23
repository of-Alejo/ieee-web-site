<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-language" content="es">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="language" content="es">

    <!-- Indexación en Google -->
    <meta name="robots" content="index, follow, max-image-preview:large">
    <meta name="googlebot" content="index, follow">

    <!-- Información -->
    <title>IEEE Unicauca <?php echo htmlspecialchars($title); ?></title>
    <meta name="title" content="<?php echo htmlspecialchars($metaTitle); ?>">
    <meta name="description" content="<?php echo htmlspecialchars($descriptionSite); ?>">
    <meta name="author" content="IEEE Student Branch Unicauca">
    <meta name="publisher" content="DAVID ALEJANDRO ORTEGA FLÓREZ">
    <meta name="keywords" content="IEEE, Unicauca, tecnología, telecomunicaciones, eventos académicos, TET 2025">
    <meta name="geo.position" content="2.446339294065221, -76.59841050197932">
    <meta name="geo.placename" content="Popayán, Cauca, Colombia">
    <meta name="geo.region" content="CO-19">

    <!-- Tema color -->
    <meta name="theme-color" content="#123f72">
    <meta name="msapplication-TileColor" content="#123f72">

    <!-- Favicon -->
    <link rel="icon" href="/build/img/logos/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/build/img/logos/favicon.ico" type="image/x-icon">

    <!-- Copyright -->
    <meta name="copyright" content="<?php echo date('Y'); ?> IEEE Student Branch Unicauca">

    <!-- Sitio Principal -->
    <link rel="canonical" href="http://www.ieee.unicauca.edu.co">

    <!-- Redes Sociales -->
    <!-- Para Facebook -->
    <meta property="fb:pages" content="IEEEUnicauca">
    <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($descriptionSite); ?>">
    <meta property="og:site_name" content="Rama IEEE Unicauca">
    <meta property="og:image" content="http://ieee.unicauca.edu.co/build/img/logos/logo_ext.png">
    <meta property="og:image:width" content="821">
    <meta property="og:image:height" content="463">
    <meta property="og:image:alt" content="Logo IEEE Unicauca">
    <meta property="og:url" content="http://ieee.unicauca.edu.co/">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_CO">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@ramitaIEEE">
    <meta name="twitter:url" content="http://ieee.unicauca.edu.co/">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($descriptionSite); ?>">
    <meta name="twitter:image" content="http://ieee.unicauca.edu.co/build/img/logos/logo_ext.png">
    <meta name="twitter:image:alt" content="Logo IEEE Unicauca">

    <!-- Preconexiones para rendimiento -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <?php
    $manifestPath = $_SERVER['DOCUMENT_ROOT'] . '/build/manifest.json';
    if (file_exists($manifestPath)) {
        echo "<!-- Manifest existe -->\n";
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $cssFile = '/build/' . $manifest['css'];
        $jsFile = '/build/' . $manifest['js'];
    } else {
        $cssFile = '/build/css/app.css';
        $jsFile = '/build/js/main.js';
    }
    ?>

    <!-- CSS -->
    <link rel="preload" href="<?php echo $cssFile; ?>" as="style">
    <link rel="stylesheet" href="<?php echo $cssFile; ?>">

    <!-- JS -->
    <link rel="preload" href="<?php echo $jsFile; ?>" as="script">
    <script src="<?php echo $jsFile; ?>" defer></script>

    
    <!-- Schema.org JSON-LD para SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "IEEE Student Branch Unicauca",
        "url": "http://www.ieee.unicauca.edu.co",
        "logo": "http://www.ieee.unicauca.edu.co/build/img/logos/logo_ext.png",
        "sameAs": [
            "https://www.facebook.com/IEEEUnicauca",
            "https://x.com/ramitaIEEE"
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "general"
        },
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Popayán",
            "addressRegion": "Cauca",
            "addressCountry": "CO"
        }
    }
    </script>

    <!-- Iconos -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0/particles.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
</head>

<body itemscope itemtype="https://schema.org/WebPage">
    <?php include_once __DIR__ . '/templates/header/header.php'; ?>
    <?php echo $content; ?>
    <?php include_once __DIR__ . '/templates/footer/footer.php'; ?>
</body>

</html>