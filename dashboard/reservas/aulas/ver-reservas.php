<?php
require_once __DIR__ . "/../../../config/db.php";
require_once __DIR__ . "/../../../config/functions.php";

$usuario = getUser();

if (!$usuario) {
    notify("Debés iniciar sesion para acceder.", "error");
    redirect("/login.php");
}

if ($usuario['rol'] === "admin") {
    $titulo = "Total de Reservas | Reservá tu aula";
    $reservasTotales = getDetalleReservasAulas($pdo);
} else {
    $titulo = "Mis Reservas | Reservá tu aula";
    $reservasPropias = getDetalleReservasAulasByUser($pdo, $usuario['id']);
}

?>

<?php
require_once __DIR__ . "/../../../components/header.php";
?>


<main class="mis-reservas">
    <section>
        <?php if ($usuario['rol'] === 'admin'): ?>
            <div class="reservas-header">
                <h1>Reservas de aulas del Sistema</h1>
                <p>Todas las reservas aulas del sistema</p>
                <?php if (!empty($reservasTotales)): ?>
                    <a href="<?= BASE_URL ?>/config/excel/exportar-reservas-aulas.php" class="exportar-excel">Exportar a Excel</a>
                <?php endif ?>
            </div>
            <section>

                <?php if (!empty($reservasTotales)): ?>
                    <?php foreach ($reservasTotales as $reserva): ?>
                        <article>
                            <h3>Quien reserva: <?= htmlspecialchars($reserva['usuario']) ?></h3>
                            <h3>Nombre aula: <?= htmlspecialchars($reserva['aula']) ?></h3>
                            <p>Fecha: <?= htmlspecialchars($reserva['fecha']) ?> </p>
                            <p>
                                Hora: <?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs
                            </p>
                            <p>Capacidad: <?= htmlspecialchars($reserva['capacidad']) ?></p>
                        </article>
                    <?php endforeach ?>

                <?php else: ?>
                    <article>
                        <p>No existen reservas activas</p>
                        <a href="<?= BASE_URL ?>/dashboard/">Volver</a>
                    </article>
                <?php endif ?>

            </section>


        <?php else: ?>
            <section>
                <h1>Mis Reservas</h1>
                <p>Aca podras ver tus reservas activas</p>
            </section>
            <section>

                <?php if (!empty($reservasPropias)): ?>
                    <?php foreach ($reservasPropias as $reserva): ?>
                        <article>
                            <h3>Nombre aula: <?= htmlspecialchars($reserva['aula']) ?></h3>
                            <p>Fecha: <?= htmlspecialchars($reserva['fecha']) ?> </p>
                            <p>
                                Hora: <?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs
                            </p>
                            <p>Capacidad: <?= htmlspecialchars($reserva['capacidad']) ?></p>
                            <p>Estado: <?=  $reserva['fecha'] < date('Y-m-d') ? 'Cumplida' : 'Activa' ?></p>
                            <?php if ($reserva['fecha'] > date('Y-m-d')): ?>
                                <div>
                                    <a href="<?= BASE_URL ?>/dashboard/reservas/aulas/editar-reserva.php?id=<?= $reserva['id'] ?>">Editar</a>
                                    <button popovertarget="eliminar-reserva-<?php echo $reserva['id'] ?>">Eliminar</button>
                                </div>
                            <?php endif ?>
                        </article>
                    <?php endforeach ?>

                <?php else: ?>
                    <article>
                        <p>No tienes reservas activas</p>
                        <a href="<?= BASE_URL ?>/dashboard/reservas/aulas/crear-reserva.php">Ir a Reservar</a>
                    </article>
                <?php endif ?>

            </section>

        <?php endif ?>
    </section>
</main>

<?php if ($usuario['rol'] === 'user'): ?>
    <?php foreach ($reservasPropias as $reserva): ?>
        <section id="eliminar-reserva-<?php echo $reserva['id'] ?>" popover>
            <p>¿estas seguro que queres eliminar del sistema la reserva del <?php echo $reserva['aula'] ?> - Dia: <?php echo $reserva['fecha'] ?> - Horario Inicio: <?php echo $reserva['hora_inicio'] ?> - Horario Fin: <?php echo $reserva['hora_fin'] ?>?</p>
            <a href="<?php echo BASE_URL; ?>/dashboard/reservas/aulas/eliminar-reserva.php?id=<?php echo $reserva['id']; ?>">Eliminar</a>
            <button type="button" popovertarget="eliminar-reserva-<?php echo $reserva['id'] ?>" popovertargetaction="hide">Cancelar</button>
        </section>
    <?php endforeach ?>
<?php endif ?>

<?php
require_once __DIR__ . "/../../../components/footer.php";

?>