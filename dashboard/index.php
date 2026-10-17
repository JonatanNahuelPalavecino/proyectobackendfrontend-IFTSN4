<?php
require_once __DIR__ . "/../config/functions.php";
require_once __DIR__ . "/../config/db.php";

$usuario = getUser();

if (!$usuario) {
    notify('Debés iniciar sesión para acceder.', 'error');
    redirect('/login.php');
}

$titulo = 'Dashboard | SIRAUCA - Sistema de Reserva de Aulas y Carros del IFTS N° 4';

if ($usuario['rol'] === "admin") {

    $totalAulas = getTotalAulas($pdo);
    $totalUsers = getTotalUsers($pdo);
    $totalCarts = getTotalCarts($pdo);
    $totalNotebooks = getTotalNotebooks($pdo);
    $totalReservasAulasHoy = getTotalReservasAulasToday($pdo);
    $totalReservasCarrosHoy = getTotalReservasCarrosToday($pdo);

    $detalleReservasAulasHoy = getDetalleReservasAulasToday($pdo);
    $detalleReservasCarrosHoy = getDetalleReservasCarrosToday($pdo);
} else {
    $totalReservasAulasActivas = getTotalReservasAulasActivas($pdo, $usuario['id']);
    $totalReservasAulas = getTotalReservasAulas($pdo, $usuario['id']);
    $totalReservasCarrosActivas = getTotalReservasCarrosActivas($pdo, $usuario['id']);
    $totalReservasCarros = getTotalReservasCarros($pdo, $usuario['id']);

    $detalleReservasAulasFuturas = getDetalleReservasAulasFuturas($pdo, $usuario['id']);
    $detalleReservasCarrosFuturas = getDetalleReservasCarrosFuturas($pdo, $usuario['id']);
}

?>

<?php
require __DIR__ . '/../components/header.php';
?>


<main class="dashboard">
    <section class="dashboard-header">
        <!-- DASHBOARD HEADER ADMIN -->
        <?php if ($usuario['rol'] === 'admin'): ?>            
            <article class="dh-welcome">

                <article>
                    <span class="dh-panel">Panel de administracion</span>
                    <h2 class="dh-welcome-title">Bienvenido, <?= htmlspecialchars(ucwords($usuario['nombre'])) ?></h2>
                    <p class="dh-welcome-label">Gestioná aulas, carros, PCs y todas tus reservas desde acá</p>
                </article>
                
                <article class="dh-actions">
                    <a href="<?= BASE_URL ?>/dashboard/notebooks/ver-notebooks.php" class="btn reservar">Gestionar Pcs</a>
                
                </article>
            </article>

        <!-- DASHBOARD HEADER PROFESOR -->
        <?php else: ?>
            <article class="dh-welcome">

                <article>
                    <span class="dh-panel">Panel del Profesor</span>
                    <h2 class="dh-welcome-title">Hola, <?= htmlspecialchars(ucwords($usuario['nombre'])) ?></h2>
                    <p class="dh-welcome-label">Revisá tus reservas y realiza nuevas reservas de aulas o carros</p>
                </article>
                
                <article class="dh-actions">
                    <a href="<?= BASE_URL ?>/dashboard/reservas/aulas/crear-reserva.php" class="btn reservar">Gestionar Perfil</a>
                </article>

            </article>
        <?php endif; ?>

    </section>
    <!--DASHBOARD CARDS ADMIN -->
    <?php if ($usuario["rol"] == "admin"): ?>
        <section class="dashboard-stats">
            <article class="stat-card">
                <span class="stat-icon">🏫</span>
                <span class="stat-number"><?= $totalAulas ?></span>
                <p class="stat-label">Aulas Totales</p>
            </article>

            <article class="stat-card">
                <span class="stat-icon">👨‍🏫</span>
                <span class="stat-number"><?= $totalUsers ?></span>
                <p class="stat-label">Profesores Registrados</p>
            </article>

            <article class="stat-card">
                <span class="stat-icon">💻</span>
                <span class="stat-number"><?= $totalNotebooks ?></span>
                <p class="stat-label">Notebooks Totales</p>
            </article>

            <article class="stat-card">
                <span class="stat-icon">🛒</span>
                <span class="stat-number"><?= $totalCarts ?></span>
                <p class="stat-label">Carros Totales</p>
            </article>

            <article class="stat-card">
                <span class="stat-icon">🏫</span>
                <span class="stat-number"><?= $totalReservasAulasHoy ?></span>
                <p class="stat-label">Reservas de aulas hoy</p>
            </article>

            <article class="stat-card">
                <span class="stat-icon">🛒</span>
                <span class="stat-number"><?= $totalReservasCarrosHoy ?></span>
                <p class="stat-label">Reservas de carros hoy</p>
            </article>

        </section>

    <!-- DASHBOARD CARDS PROFESOR -->
    <?php else: ?>
        <section class="dashboard-stats">
            <article class="stat-card">
                <span class="stat-icon">🏫</span>
                <span class="stat-number"><?= $totalReservasAulasActivas ?></span>
                <p class="stat-label">Reservas de aulas activas</p>
            </article>

            <article class="stat-card">
                <span class="stat-icon">🏫</span>
                <span class="stat-number"><?= $totalReservasAulas ?></span>
                <p class="stat-label">Total de reservas de aulas</p>
            </article>

            <article class="stat-card">
                <span class="stat-icon">🛒</span>
                <span class="stat-number"><?= $totalReservasCarrosActivas?></span>
                <p class="stat-label">Reservas de carros activas</p>
            </article>

            <article class="stat-card">
                <span class="stat-icon">🛒</span>
                <span class="stat-number"><?= $totalReservasCarros ?></span>
                <p class="stat-label">Total de reservas de carros</p>
            </article>
        </section>
    <?php endif; ?>

    <!-- DASHBOARD RESERVAS ADMIN -->
    <?php if ($usuario["rol"] == "admin"): ?>
        <!-- Reservas de Aulas para hoy -->
        <section class="dashboard-reservas">
            <div class ="dashboard-reservas-container">
                <article class="db-reservas-header">
                    <h2 class="db-reservas-title">Reservas de aulas para hoy</h2>
                    <article class="dh-actions">
                        <?php if (!empty($detalleReservasAulasHoy)):?>
                            <a class="btn btn-excel" href="<?= BASE_URL ?>/config/excel/descargar-reservas-aulas-hoy.php" target="_blank">⬇️ Descargar Excel</a>
                        <?php endif;?>
                        <a href="<?= BASE_URL ?>/dashboard/aulas/ver-aulas.php" class="btn btn-gestion">Gestionar Aulas</a>
                    </article>
                </article>

            <?php if (!empty($detalleReservasAulasHoy)):?>
                
                <div class="db-reservas-cards">
                    <?php foreach($detalleReservasAulasHoy as $reserva):?>
                        <article class ="db-reservas-item">
                            <div class="db-reservas-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>
                                </svg>
                            </div>

                            <div class="db-reservas-body">
                                <div>
                                    <h3><?= htmlspecialchars($reserva['aula']) ?></h3>
                                    <span>Activa</span>
                                </div>
                                
                                <div>
                                    <span class="db-card-firstLetter"><?= htmlspecialchars(substr($reserva['usuario'],0,1));?></span>
                                    <p><?= htmlspecialchars($reserva['usuario']) ?></p>
                                </div>
                                
                                <div>
                                    <p><?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</p>
                                </div>
                            </div>

                            

                        </article>
                    <?php endforeach ?>
                </div>
            <?php else:?>
                <p class="no-results">No hay reservas de aulas para hoy.</p>
            <?php endif;?>

            </div>
        </section>

        <!-- Reservas de Carros para hoy -->
        <section class="dashboard-reservas">
            <div class ="dashboard-reservas-container">
                <article class="db-reservas-header">
                    <h2 class="db-reservas-title">Reservas de Carros para hoy</h2>
                    <article class="dh-actions">
                        <?php if (!empty($detalleReservasCarrosHoy)):?>
                            <a class="btn btn-excel" href="<?= BASE_URL ?>/config/excel/descargar-reservas-aulas-hoy.php" target="_blank">⬇️ Descargar Excel</a>
                        <?php endif;?>
                        <a href="<?= BASE_URL ?>/dashboard/carros/ver-carros.php" class="btn reservar">Gestionar Carros</a>
                    </article>
                </article>

            <?php if (!empty($detalleReservasCarrosHoy)):?>
                
                <div class="db-reservas-cards">
                    <?php foreach($detalleReservasCarrosHoy as $reserva):?>
                        <article class ="db-reservas-item">
                            <div class="db-reservas-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
                                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                </svg>
                            </div>
                            <div class="db-reservas-body">
                                <div class="">
                                    <h3><?= htmlspecialchars($reserva['carro']) ?></h3>
                                    <span>Activa</span>
                                </div>

                                <div>
                                    <span class="db-card-firstLetter"><?= htmlspecialchars(substr($reserva['usuario'],0,1));?></span>
                                    <p><?= htmlspecialchars($reserva['usuario']) ?></p>
                                </div>
                                
                                <div>
                                    <p><?= htmlspecialchars($reserva['comentario'])?></p>
                                </div>
                            </div>
                            

                        </article>
                    <?php endforeach ?>
                </div>
            <?php else:?>
                <p class="no-results">No hay reservas de aulas para hoy.</p>
            <?php endif;?>
            </div>
        </section>

    <!-- DASHBOARD RESERVAS AULAS PROXIMAS PROFESOR -->
    <?php else: ?>
        <section class="dashboard-reservas">
            <div class ="dashboard-reservas-container">
                <article class="db-reservas-header">
                    <h2 class="db-reservas-title">Próximas reservas de aulas</h2>
                    <article class="dh-actions">
                        <a href="<?= BASE_URL ?>/dashboard/aulas/ver-aulas.php" class="btn btn-gestion">Reservar Aula</a>
                    </article>
                </article>

            <?php if (!empty($detalleReservasAulasFuturas)):?>
                <!-- aca -->
                <div class="db-reservas-cards">
                    <?php foreach($detalleReservasAulasFuturas as $reserva):?>
                        <article class ="db-card-item">
                            <div class="">
                                <h3>Aula: <span><?= htmlspecialchars($reserva['aula']) ?></span></h3>
                                <span>Activa</span>
                            </div>
                            
                            <div>
                                <p>Fecha: <span><?= formatDate($reserva['fecha']) ?></span></p>
                            </div>

                            <div>
                                <p>Hora: <span><?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</span></p>
                            </div>
                        </article>
                    <?php endforeach ?>
                </div>
            <?php else:?>
                <p class="no-results">No tenes reservas de aulas próximas.</p>
            <?php endif;?>

            </div>
        </section>

        <!-- DASHBOARD RESERVAS CARROS PROXIMAS PROFESOR -->
        <section class="dashboard-reservas">
            <div class ="dashboard-reservas-container">
                <article class="db-reservas-header">
                    <h2 class="db-reservas-title">Próximas reservas de carro</h2>
                    <article class="dh-actions">
                        <a href="<?= BASE_URL ?>/dashboard/reservas/carros/crear-reserva.php">Reservar Carros</a>
                    </article>
                </article>

            <?php if(!empty($detalleReservasCarrosFuturas)):?>
                
                <div class="db-reservas-cards">
                    <?php foreach($detalleReservasCarrosFuturas as $reserva):?>
                        <article class ="db-card-item">
                            <div class="">
                                <h3>Carro: <span><?= htmlspecialchars($reserva['carro']) ?></span></h3>
                                <span>Activa</span>
                            </div>
                            
                            <div>
                                <p>Fecha: <span><?= formatDate(htmlspecialchars($reserva['fecha'])) ?> </span></p>
                            </div>

                            <div>
                                <p>Comentario : <span><?= htmlspecialchars($reserva['comentario']) ?></span></p>
                            </div>
                        </article>
                    <?php endforeach ?>
                </div>
            <?php else:?>
                <p class="no-results">No tenes reservas de carros próximas.</p>
            <?php endif;?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/../components/footer.php';
?>