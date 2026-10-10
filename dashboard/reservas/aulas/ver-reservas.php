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


<main class="verReservas">
    <?php if ($usuario['rol'] === 'admin'): ?>
        <h1>🏛️ Reservas de aulas del Sistema</h1>
        <p>Todas las reservas aulas del sistema</p>
        <?php if (!empty($reservasTotales)): ?>
            <a href="<?= BASE_URL ?>/config/excel/exportar-reservas-aulas.php" class="exportar-excel">Exportar a Excel</a>
        <?php endif ?>
        <section class="verReservas-container">

            <?php if (!empty($reservasTotales)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Quien reserva</th>
                            <th>Nombre aula</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservasTotales as $reserva): ?>
                            <tr style="color: <?= $reserva['fecha'] < date('Y-m-d') ? "grey" : "black" ?>;">
                                <td><?= htmlspecialchars($reserva['usuario']) ?></td>
                                <td><?= htmlspecialchars($reserva['aula']) ?></td>
                                <td><?= formatDate(htmlspecialchars($reserva['fecha'])) ?></td>
                                <td><?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</td>
                                <td><?= htmlspecialchars($reserva['capacidad']) ?></td>
                                <td><span class="verReservas-status <?= $reserva['fecha'] < date('Y-m-d') ? 'confirmed' : 'pending' ?>"><?= $reserva['fecha'] < date('Y-m-d') ? 'Cumplida' : 'Activa' ?></span></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php else: ?>
                <article>
                    <p>No existen reservas activas</p>
                    <a href="<?= BASE_URL ?>/dashboard/">Volver</a>
                </article>
            <?php endif ?>

        </section>


    <?php else: ?>
        <h1>🏛️ Mis Reservas de aulas</h1>
        <p>Aca podras ver tus reservas de aulas</p>
        <section class="verReservas-container">

            <?php if (!empty($reservasPropias)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre aula</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservasPropias as $reserva): ?>
                            <tr style="color: <?= $reserva['fecha'] < date('Y-m-d') ? "grey" : "black" ?>;">
                                <td><?= htmlspecialchars($reserva['aula']) ?></td>
                                <td><?= formatDate(htmlspecialchars($reserva['fecha'])) ?></td>
                                <td><?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</td>
                                <td><?= htmlspecialchars($reserva['capacidad']) ?></td>
                                <td><span class="verReservas-status <?= $reserva['fecha'] < date('Y-m-d') ? 'confirmed' : 'pending' ?>"><?= $reserva['fecha'] < date('Y-m-d') ? 'Cumplida' : 'Activa' ?></span></td>
                                <td class="verReservas-acciones">
                                    <?php if ($reserva['fecha'] < date('Y-m-d')): ?>
                                        <span
                                            class="verReservas-editar disabled">
                                            Editar
                                        </span>
                                        <span class="verReservas-eliminar disabled">Eliminar</span>
                                    <?php else: ?>
                                        <a
                                            class="verReservas-editar"
                                            href="<?= BASE_URL ?>/dashboard/reservas/aulas/editar-reserva.php?id=<?= $reserva['id'] ?>">
                                            Editar
                                        </a>
                                        <button class="verReservas-eliminar" popovertarget="eliminar-reserva-<?php echo $reserva['id'] ?>">Eliminar</button>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

            <?php else: ?>
                <article>
                    <p>No tienes reservas activas</p>
                    <a href="<?= BASE_URL ?>/dashboard/reservas/aulas/crear-reserva.php">Ir a Reservar</a>
                </article>
            <?php endif ?>

        </section>

    <?php endif ?>
</main>

<?php if ($usuario['rol'] === 'user'): ?>
    <?php foreach ($reservasPropias as $reserva): ?>
        <section
            id="eliminar-reserva-<?php echo $reserva['id'] ?>" class="popover-eliminar"
            popover>
            <section class="popover-eliminar-content">
                    <h2>Eliminar reserva</h2>
                    <p>
                        ¿Estás seguro de que querés eliminar la reserva de
                        <strong><?= htmlspecialchars($reserva['aula']) ?></strong>?
                    </p>
                    <section class="popover-eliminar-info">
                        <p>
                            <strong>Fecha:</strong>
                            <?= formatDate($reserva['fecha']) ?>
                        </p>

                        <p>
                            <strong>Horario:</strong>
                            <?= htmlspecialchars($reserva['hora_inicio']) ?>hs -
                            <?= htmlspecialchars($reserva['hora_fin']) ?>hs
                        </p>
                    </section>
                    <section class="popover-eliminar-actions">
                        <a
                            class="popover-confirmar"
                            href="<?php echo BASE_URL; ?>/dashboard/reservas/aulas/eliminar-reserva.php?id=<?php echo $reserva['id']; ?>">
                            Eliminar
                        </a>
                        <button
                            type="button"
                            class="popover-cancelar"
                            popovertarget="eliminar-reserva-<?php echo $reserva['id'] ?>"
                            popovertargetaction="hide">
                            Cancelar
                        </button>
                    </section>
            </section>
        </section>
    <?php endforeach ?>
<?php endif ?>

<?php
require_once __DIR__ . "/../../../components/footer.php";

?>