<?php

if (!isset($titulo)) {
    $titulo = "Reservá tu aula";
}

$user = getUser();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caacupe+One&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/style.css">
    <title><?php echo $titulo; ?></title>
</head>

<body>
    <header class="header">
        <div class="logo">
            <img class="logo-img" src="<?php echo BASE_URL; ?>/assets/images/ifts_logo.png" alt="Logo">
            <h4 class="logo-title">Reservá tu aula</h4>
        </div>
        <nav class="nav">
            <a class="nav-link" href="index.php">Inicio</a>

            <?php if ($user): ?>
                <?php if ($user["rol"] === "admin"): ?>
                    <a class="nav-link" href="ver-reservas.php">Ver Reservas</a>
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                <?php else: ?>
                    <a class="nav-link" href="reservar-aula.php">Reservar Aula</a>
                    <a class="nav-link" href="mis-reservas.php">Mis Reservas</a>
                <?php endif; ?>
                <a class="nav-link" href="logout.php">Cerrar sesión</a>
            <?php else: ?>
                <a class="nav-link" href="como-funciona.php">¿Cómo funciona?</a>
                <a class="nav-link" href="login.php">Iniciar sesión</a>
            <?php endif; ?>

        </nav>
    </header>