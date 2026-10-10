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
    <section class="dashboard-welcome">
        <?php if ($usuario['rol'] === 'admin'): ?>

            <section>
                <h1>Bienvenido <?= htmlspecialchars(ucwords($usuario['nombre'])) ?> al Sistema!</h1>
                <p>Aquí podes gestionar aulas, carros, pc's y sus reservas.</p>
            </section>

            <section>
                <a href="<?= BASE_URL ?>/dashboard/aulas/ver-aulas.php" class="btn reservar">Gestionar Aulas</a>
                <a href="<?= BASE_URL ?>/dashboard/notebooks/ver-notebooks.php" class="btn reservar">Gestionar Pcs</a>
                <a href="<?= BASE_URL ?>/dashboard/carros/ver-carros.php" class="btn reservar">Gestionar Carros</a>
            </section>

        <?php else: ?>

            <section>
                <h1>Bienvenido Profesor, <?= htmlspecialchars(ucwords($usuario['nombre'])) ?>!</h1>
                <p>Aquí puedes ver tus reservas y realizar nuevas reservas de aulas o carros.</p>
            </section>
            <section>
                <a href="<?= BASE_URL ?>/dashboard/reservas/aulas/crear-reserva.php" class="btn reservar">Reservar aula</a>
                <a href="<?= BASE_URL ?>/dashboard/reservas/carros/crear-reserva.php" class="btn reservar">Reservar Carros</a>
            </section>

        <?php endif; ?>
    </section>

    <?php if ($usuario["rol"] == "admin"): ?>
        <section class="dashboard-cards">
            <article class="card-info">
                <span><?= $totalAulas ?></span>
                <small>Aulas Totales</small>
            </article>

            <article class="card-info">
                <span><?= $totalUsers ?></span>
                <small>Profesores Registrados</small>
            </article>

            <article class="card-info">
                <span><?= $totalNotebooks ?></span>
                <small>Notebooks Totales</small>
            </article>

            <article class="card-info">
                <span><?= $totalCarts ?></span>
                <small>Carros Totales</small>
            </article>

            <article class="card-info">
                <span><?= $totalReservasAulasHoy ?></span>
                <small>Reservas de Aulas hoy</small>
            </article>

            <article class="card-info">
                <span><?= $totalReservasCarrosHoy ?></span>
                <small>Reservas Carros hoy</small>
            </article>

        </section>
    <?php else: ?>
        <section class="dashboard-cards">
            <article class="card-info">
                <span><?= $totalReservasAulasActivas ?></span>
                <small>Reservas de Aulas activas</small>
            </article>

            <article class="card-info">
                <span><?= $totalReservasAulas ?></span>
                <small>Total de Reservas de Aulas</small>
            </article>

            <article class="card-info">
                <span><?= $totalReservasCarrosActivas ?></span>
                <small>Reservas de Carros activas</small>
            </article>

            <article class="card-info">
                <span><?= $totalReservasCarros ?></span>
                <small>Total de Reservas de Carros</small>
            </article>
        </section>
    <?php endif; ?>

    <?php if ($usuario["rol"] == "admin"): ?>
        <section class="dashboard-reservas">
            <h2>Reservas de Aulas para hoy</h2>
            <?php if (!empty($detalleReservasAulasHoy)): ?>
                <a href="<?= BASE_URL ?>/config/excel/descargar-reservas-aulas-hoy.php" target="_blank">Descargar las reservas de aulas del día de hoy en Excel</a>
                <section>
                    <?php foreach ($detalleReservasAulasHoy as $reserva): ?>
                        <article>
                            <h3>Aula: <?= htmlspecialchars($reserva['aula']) ?></h3>
                            <p>Profesor: <?= htmlspecialchars($reserva['usuario']) ?> </p>
                            <p>Hora: <?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</p>
                        </article>
                    <?php endforeach ?>
                </section>
            <?php else: ?>
                <p>No hay reservas de aulas para hoy.</p>
            <?php endif; ?>
        </section>

        <section class="dashboard-reservas">
            <h2>Reservas de Carros para hoy</h2>
            <?php if (!empty($detalleReservasCarrosHoy)): ?>
                <a href="<?= BASE_URL ?>/config/excel/descargar-reservas-carros-hoy.php" target="_blank">Descargar reservas de carros del día de hoy en Excel</a>
                <section>
                    <?php foreach ($detalleReservasCarrosHoy as $reserva): ?>
                        <article>
                            <h3>Carro: <?= htmlspecialchars($reserva['carro']) ?></h3>
                            <p>Profesor: <?= htmlspecialchars($reserva['usuario']) ?> </p>
                            <p>Comentario: <?= htmlspecialchars($reserva['comentario']) ?></p>
                        </article>
                    <?php endforeach ?>
                </section>
            <?php else: ?>
                <p>No hay reservas de carros para hoy.</p>
                <?php endif; ?>
        </section>

    <?php else: ?>
        
        <section class="dashboard-reservas">
            <h2>Próximas Reservas de Aulas</h2>
            <?php if (!empty($detalleReservasAulasFuturas)): ?>
                <section>
                    <?php foreach ($detalleReservasAulasFuturas as $reserva): ?>
                        <article>
                            <h3>Aula: <?= htmlspecialchars($reserva['aula']) ?></h3>
                            <p>Fecha: <?= formatDate($reserva['fecha']) ?> </p>
                            <p>Hora: <?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</p>
                        </article>
                    <?php endforeach ?>
                </section>
            <?php else: ?>
                <p>No tenes reservas de aulas próximamente.</p>
            <?php endif; ?>
        </section>

        <section class="dashboard-reservas">
            <h2>Próximas Reservas de Carros</h2>
            <?php if (!empty($detalleReservasCarrosFuturas)): ?>
                <section>
                    <?php foreach ($detalleReservasCarrosFuturas as $reserva): ?>
                        <article>
                            <h3>Carro: <?= htmlspecialchars($reserva['carro']) ?></h3>
                            <p>Fecha: <?= formatDate(htmlspecialchars($reserva['fecha'])) ?> </p>
                            <p>Comentario: <?= htmlspecialchars($reserva['comentario']) ?></p>
                        </article>
                    <?php endforeach ?>
                </section>
            <?php else: ?>
                <p>No tenes reservas de carros próximamente.</p>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/../components/footer.php';
?>