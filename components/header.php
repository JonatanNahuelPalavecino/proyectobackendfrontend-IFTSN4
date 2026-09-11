<?php

    if (!isset($titulo)) {
        $titulo = "Reservá tu aula";
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
    <script src="<?php echo BASE_URL; ?>/assets/js/aside.js" defer></script>
    <title><?php echo $titulo; ?></title>
</head>

<body>
    <header class="header">
        <div class="logo">
            <img
                class="logo-img"
                src="<?= BASE_URL ?>/assets/images/ifts_logo.png"
                alt="Logo"
            >

            <h4 class="logo-title">Reservá tu aula</h4>
        </div>
    </header>

    <?php include __DIR__ . "/aside.php"; ?>
