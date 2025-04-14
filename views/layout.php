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
    <title>IEEE - <?php echo $title; ?></title>
    <meta name="title" content="<?php echo $metaTitle; ?>">
    <meta name="description" content="<?php echo $descriptionSite; ?>">
    <meta name="author" content="UNICAUCA">
    <meta name="keywords" content="">
    <meta name="publisher" content="DAVID ALEJANDRO ORTEGA FLÓREZ">
    <meta name="geo.position" content="">

    <!-- Tema color -->
    <meta name="theme-color" content="">

    <!-- Favicon -->
    <link rel="icon" href="/build/img/logo/logo.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/build/img/logo/logo.ico" type="image/x-icon">

    <!-- Copyright -->
    <meta name="copyright" content="20245">

    <!-- Sitio Principal -->
    <link rel="canonical" href="">


    <!-- Redes Sociales -->
    <!-- Para Facebook -->
    <!-- Etiquetas de Facebook Open Graph -->

    <!-- Etiquetas para Twitter Card -->

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;700;900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="preload" href="/build/css/app.css" as="style">
    <link rel="stylesheet" href="/build/css/app.css">

    <!-- JS -->
    <!-- DOMPurify (Sanitizar) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.6/purify.min.js" integrity="sha512-jB0TkTBeQC9ZSkBqDhdmfTv1qdfbWpGE72yJ/01Srq6hEzZIz2xkz1e57p9ai7IeHMwEG7HpzG6NdptChif5Pg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Teléfono internacional -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.min.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js" defer></script>
    <!-- Código principal JS -->
    <script rel="preload" src="build/js/main.js" as="script" defer></script>
</head>

<body>
    <?php include_once __DIR__ .'/templates/header/header.php'; ?>
    <?php echo $content; ?>
</body>

</html>