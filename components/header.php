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

    <aside class="side-menu" id="side-menu">
        <button
            class="menu-toggle"
            type="button"
            aria-label="Abrir menú"
            aria-expanded="false"
            aria-controls="side-menu"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="side-menu-content">
            <div class="side-menu-title">
                <span class="side-menu-symbol">IFTS</span>
                <h2>Menú</h2>
            </div>

            <nav class="side-nav" aria-label="Navegación principal">

                <a class="side-nav-link" href="<?= BASE_URL ?>/index.php">
                    <span class="side-nav-icon">⌂</span>
                    <span class="side-nav-text">
                        <strong>Inicio</strong>
                        <small>Página principal</small>
                    </span>
                </a>

                <?php if ($user): ?>

                    <?php if ($user["rol"] === "admin"): ?>

                        <a class="side-nav-link" href="<?= BASE_URL ?>/ver-reservas.php">
                            <span class="side-nav-icon">▣</span>
                            <span class="side-nav-text">
                                <strong>Ver reservas</strong>
                                <small>Consultar todas las reservas</small>
                            </span>
                        </a>

                        <a class="side-nav-link" href="<?= BASE_URL ?>/dashboard.php">
                            <span class="side-nav-icon">▦</span>
                            <span class="side-nav-text">
                                <strong>Dashboard</strong>
                                <small>Administrar el sistema</small>
                            </span>
                        </a>

                    <?php else: ?>

                        <a class="side-nav-link" href="<?= BASE_URL ?>/reservar-aula.php">
                            <span class="side-nav-icon">＋</span>
                            <span class="side-nav-text">
                                <strong>Reservar aula</strong>
                                <small>Crear una nueva reserva</small>
                            </span>
                        </a>

                        <a class="side-nav-link" href="<?= BASE_URL ?>/mis-reservas.php">
                            <span class="side-nav-icon">▤</span>
                            <span class="side-nav-text">
                                <strong>Mis reservas</strong>
                                <small>Consultar tus reservas</small>
                            </span>
                        </a>

                    <?php endif; ?>

                    <a class="side-nav-link" href="<?= BASE_URL ?>/logout.php">
                        <span class="side-nav-icon">↪</span>
                        <span class="side-nav-text">
                            <strong>Cerrar sesión</strong>
                            <small>Salir de tu cuenta</small>
                        </span>
                    </a>

                <?php else: ?>

                    <a class="side-nav-link" href="<?= BASE_URL ?>/como-funciona.php">
                        <span class="side-nav-icon">?</span>
                        <span class="side-nav-text">
                            <strong>¿Cómo funciona?</strong>
                            <small>Información del sistema</small>
                        </span>
                    </a>

                    <a class="side-nav-link" href="<?= BASE_URL ?>/login.php">
                        <span class="side-nav-icon">→</span>
                        <span class="side-nav-text">
                            <strong>Iniciar sesión</strong>
                            <small>Ingresar a tu cuenta</small>
                        </span>
                    </a>

                <?php endif; ?>

            </nav>
        </div>
    </aside>