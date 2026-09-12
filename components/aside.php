<?php

$user = getUser();
?>


<aside class="side-menu" id="side-menu">
    <button
        class="menu-toggle"
        type="button"
        aria-label="Abrir menú"
        aria-expanded="false"
        aria-controls="side-menu">
        <span></span>
        <span></span>
        <span></span>
    </button>


    <nav class="side-nav" aria-label="Navegación principal">

        <a class="side-nav-link" href="<?= BASE_URL ?>/index.php" aria-label="Inicio" title="Inicio">
            <span class="side-nav-icon" aria-hidden="true">⌂</span>
            <span class="side-nav-text">
                <strong>Inicio</strong>
                <small>Página principal</small>
            </span>
        </a>

        <?php if ($user): ?>

            <a class="side-nav-link" href="<?= BASE_URL ?>/dashboard.php" aria-label="Dashboard" title="Dashboard">
                <span class="side-nav-icon" aria-hidden="true">▦</span>
                <span class="side-nav-text">
                    <strong>Dashboard</strong>
                    <small>Administrar el sistema</small>
                </span>
            </a>


            <?php if ($user["rol"] === "admin"): ?>

                <a class="side-nav-link" href="<?= BASE_URL ?>/ver-reservas.php" aria-label="Ver reservas" title="Ver reservas">
                    <span class="side-nav-icon" aria-hidden="true">▣</span>
                    <span class="side-nav-text">
                        <strong>Ver reservas</strong>
                        <small>Consultar todas las reservas</small>
                    </span>
                </a>

            <?php else: ?>

                <a class="side-nav-link" href="<?= BASE_URL ?>/reservar-aula.php" aria-label="Reservar aula" title="Reservar aula">
                    <span class="side-nav-icon" aria-hidden="true">＋</span>
                    <span class="side-nav-text">
                        <strong>Reservar aula</strong>
                        <small>Crear una nueva reserva</small>
                    </span>
                </a>

                <a class="side-nav-link" href="<?= BASE_URL ?>/mis-reservas.php" aria-label="Mis reservas" title="Mis reservas">
                    <span class="side-nav-icon" aria-hidden="true">▤</span>
                    <span class="side-nav-text">
                        <strong>Mis reservas</strong>
                        <small>Consultar tus reservas</small>
                    </span>
                </a>

            <?php endif; ?>

            <a class="side-nav-link" href="<?= BASE_URL ?>/logout.php" aria-label="Cerrar sesión" title="Cerrar sesión">
                <span class="side-nav-icon" aria-hidden="true">↪</span>
                <span class="side-nav-text">
                    <strong>Cerrar sesión</strong>
                    <small>Salir de tu cuenta</small>
                </span>
            </a>

        <?php else: ?>

            <a class="side-nav-link" href="<?= BASE_URL ?>/como-funciona.php" aria-label="¿Cómo funciona?" title="¿Cómo funciona?">
                <span class="side-nav-icon" aria-hidden="true">?</span>
                <span class="side-nav-text">
                    <strong>¿Cómo funciona?</strong>
                    <small>Información del sistema</small>
                </span>
            </a>

            <a class="side-nav-link" href="<?= BASE_URL ?>/login.php" aria-label="Iniciar sesión" title="Iniciar sesión">
                <span class="side-nav-icon" aria-hidden="true">→</span>
                <span class="side-nav-text">
                    <strong>Iniciar sesión</strong>
                    <small>Ingresar a tu cuenta</small>
                </span>
            </a>

        <?php endif; ?>

    </nav>
</aside>