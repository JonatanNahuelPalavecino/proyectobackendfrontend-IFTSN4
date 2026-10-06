<?php

    if (!isset($titulo)) {
        $titulo = "SIRAUCA - Sistema de Reserva de Aulas y Carros del IFTS N° 4";
    }

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caacupe+One&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/style.css">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/assets/images/ifts_logo.png" type="image/png">
    <script src="<?php echo BASE_URL; ?>/assets/js/aside.js" defer></script>
    <title><?php echo $titulo; ?></title>
</head>

<body>
    <header class="header">
        <section class="logo">
            <img
                class="logo-img"
                src="<?= BASE_URL ?>/assets/images/ifts_logo.png"
                alt="Logo"
            >
            <article class="logo-container">
                <h4 class="logo-title">SIRAUCA</h4>
                <h4 class="logo-subtitle">Sistema Integral de Reserva de Aulas y Carros</h4>
            </article>
        </section>
    </header>

    <?php include __DIR__ . "/aside.php"; ?>
