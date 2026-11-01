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
            <span class="side-nav-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 2 7.5V14a1 1 0 0 0 1 1h3.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H13a1 1 0 0 0 1-1V7.5a.5.5 0 0 0 .354-.854zM13 14h-3v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H3V7.707l5-5 5 5z" />
                </svg>
            </span>
            <span class="side-nav-text">
                <strong>Inicio</strong>
                <small>Página principal</small>
            </span>
        </a>

        <a class="side-nav-link" href="<?= BASE_URL ?>/como-funciona.php" aria-label="¿Cómo funciona?" title="¿Cómo funciona?">
            <span class="side-nav-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                </svg>
            </span>
            <span class="side-nav-text">
                <strong>¿Cómo funciona?</strong>
                <small>Información del sistema</small>
            </span>
        </a>

        <?php if ($user): ?>

            <a class="side-nav-link" href="<?= BASE_URL ?>/dashboard/index.php" aria-label="Dashboard" title="Dashboard">
                <span class="side-nav-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
                        <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM9 2.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zM10.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zM2.5 10a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM9 10.5A1.5 1.5 0 0 1 10.5 9h3A1.5 1.5 0 0 1 15 10.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zM10.5 10a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z" />
                    </svg>
                </span>
                <span class="side-nav-text">
                    <strong>Dashboard</strong>
                    <small>Administrar el sistema</small>
                </span>
            </a>


            <?php if ($user["rol"] === "admin"): ?>

                <a class="side-nav-link" href="<?= BASE_URL ?>/dashboard/reservas/aulas/ver-reservas.php" aria-label="Ver reservas" title="Ver reservas">
                    <span class="side-nav-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                            <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h.5A1.5 1.5 0 0 1 15 2.5v11a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 13.5v-11A1.5 1.5 0 0 1 2.5 1H3V.5a.5.5 0 0 1 .5-.5M2 4v9.5a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5V4z" />
                        </svg>
                    </span>
                    <span class="side-nav-text">
                        <strong>Ver reservas de Aulas</strong>
                        <small>Consultar todas las reservas de las aulas</small>
                    </span>
                </a>

                <a class="side-nav-link" href="<?= BASE_URL ?>/dashboard/reservas/carros/ver-reservas.php" aria-label="Ver reservas" title="Ver reservas">
                    <span class="side-nav-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                        </svg>
                    </span>
                    <span class="side-nav-text">
                        <strong>Ver reservas de Carros</strong>
                        <small>Consultar todas las reservas de los carros</small>
                    </span>
                </a>

            <?php else: ?>

                <a class="side-nav-link" href="<?= BASE_URL ?>/dashboard/reservas/aulas/ver-reservas.php" aria-label="Mis reservas" title="Mis reservas">
                    <span class="side-nav-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-calendar2-user" viewBox="0 0 16 16">
                            <path d="M6.5 7a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0" />
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm10-1c-.001-.246-.154-.986-.832-1.664C11.516 10.684 10.289 10 8 10s-3.516.684-4.168 1.336C3.154 12.014 3.001 12.754 3 13z" />
                            <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-11A1.5 1.5 0 0 0 13.5 1H13V.5a.5.5 0 0 0-1 0V1H4V.5a.5.5 0 0 0-1 0V1z" />
                        </svg>
                    </span>
                    <span class="side-nav-text">
                        <strong>Mis reservas de Aulas</strong>
                        <small>Consultar tus reservas</small>
                    </span>
                </a>

                <a class="side-nav-link" href="<?= BASE_URL ?>/dashboard/reservas/carros/ver-reservas.php" aria-label="Mis reservas" title="Mis reservas">
                    <span class="side-nav-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                        </svg>
                    </span>
                    <span class="side-nav-text">
                        <strong>Mis reservas de Carros</strong>
                        <small>Consultar tus reservas</small>
                    </span>
                </a>

            <?php endif; ?>

            <a class="side-nav-link" href="<?= BASE_URL ?>/logout.php" aria-label="Cerrar sesión" title="Cerrar sesión">
<span class="side-nav-icon" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 12 4.5v7a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5"/>
        <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H9.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
    </svg>
</span>
                <span class="side-nav-text">
                    <strong>Cerrar sesión</strong>
                    <small>Salir de tu cuenta</small>
                </span>
            </a>

        <?php else: ?>

            <a class="side-nav-link" href="<?= BASE_URL ?>/login.php" aria-label="Iniciar sesión" title="Iniciar sesión">
<span class="side-nav-icon" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 12 4.5v7a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5"/>
        <path fill-rule="evenodd" d="M13.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H4.5a.5.5 0 0 0 0 1h7.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
    </svg>
</span>
                <span class="side-nav-text">
                    <strong>Iniciar sesión</strong>
                    <small>Ingresar a tu cuenta</small>
                </span>
            </a>

        <?php endif; ?>

    </nav>
</aside>