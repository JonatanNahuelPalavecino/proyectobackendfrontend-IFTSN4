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


<main>
    <section class="verReservas-header">
        <?php if ($usuario['rol'] === 'admin'): ?>
            <article class="vr-containerInfo">
                <span class="vr-emojiAula">🏛️</span>

                <div>
                    <h2 class ="vr-title">Reservas de Aulas</h2>
                    <p class="vr-description">Todas las reservas de aulas del sistema</p>
                </div>
                
                <span class="vr-divisor"></span>
                
                <span class="vr-logo">ADMINISTRADOR</span>
                

                <div class= "dh-actions">
                    <?php if (!empty($reservasTotales)): ?>
                        <a href="<?= BASE_URL ?>/config/excel/exportar-reservas-aulas.php" class="btn btn-excel">
                            ⬇ Descargar Excel
                        </a>
                    <?php endif ?>
                </div>
            </article>
        <?php else: ?>
            <article class="vr-containerInfo">
                <span class="vr-emojiAula">🏛️</span>

                <div>
                    <h2 class="vr-title">Mis Reserva de Aulas</h2>
                    <p class="vr-description">Aca podras ver tus reservas de aulas</p>
                </div>
                
                <span class="vr-divisor"></span>

                <span class="vr-logo">PROFESOR</span>
                
            </article>
        <?php endif?>
    </section>

    <?php if ($usuario['rol'] === 'admin'): ?>
            <!-- ADMIN -->
        <section class="verReservas-container">
            <?php if (!empty($reservasTotales)): ?>
                <div class="vr-tabla-card">
                    <table class="vr-table">
                        <thead>
                            <tr>
                                <th>Reservante</th>
                                <th>Nombre aula</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th class="col-centro">Capacidad</th>
                                <th class="col-centro">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservasTotales as $reserva): ?>
                                <?php $fila_cumplida = $reserva['fecha'] < date('Y-m-d'); ?> 
                                <tr class="<?= $fila_cumplida ? 'vr-fila-cumplida' : 'vr-fila-activa' ?>">
                                    <td class="vr-td-fuerte"><?= htmlspecialchars(ucwords($reserva['usuario'])) ?></td>
                                    <td><?= htmlspecialchars($reserva['aula']) ?></td>
                                    <td><?= formatDate(htmlspecialchars($reserva['fecha'])) ?></td>
                                    <td><?= htmlspecialchars($reserva['hora_inicio']) ?> hs - <?= htmlspecialchars($reserva['hora_fin']) ?> hs</td>
                                    <td class="col-centro"><?= htmlspecialchars($reserva['capacidad']) ?></td>
                                    <td class="col-centro">
                                        <span class="verReservas-status <?= $fila_cumplida ? 'confirmed' : 'pending' ?>">
                                            <?= $fila_cumplida ? 'Cumplida' : 'Activa' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <article class="no-results no-results-aulas">
                    <p class="text-sinResultsAulas" >No existen reservas de aulas aún</p>
                </article>
            <?php endif ?>
        </section>

    <?php else: ?>
        <section class="verReservas-container">
            <?php if (!empty($reservasPropias)): ?>
                <article class="vr-tabla-card">
                    <table class="vr-table">
                        <thead>
                            <tr>
                                <th>Nombre aula</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th class="col-centro">Capacidad</th>
                                <th class="col-centro">Estado</th>
                                <th class="col-centro">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservasPropias as $reserva): ?>
                                <?php $fila_cumplida = $reserva['fecha'] < date('Y-m-d'); ?>
                                <tr class="<?= $fila_cumplida ? 'vr-fila-cumplida' : 'vr-fila-activa' ?>">
                                    <td class="vr-td-fuerte"><?= htmlspecialchars($reserva['aula']) ?></td>
                                    <td><?= formatDate(htmlspecialchars($reserva['fecha'])) ?></td>
                                    <td><?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</td>
                                    <td class="col-centro"><?= htmlspecialchars($reserva['capacidad']) ?></td>
                                    <td class="col-centro">
                                        <span class="verReservas-status <?= $fila_cumplida ? 'confirmed' : 'pending' ?>">
                                            <?= $fila_cumplida ? 'Cumplida' : 'Activa' ?>
                                        </span>
                                    </td>
                                    <td class="col-centro" class="verReservas-acciones">
                                        <?php if ($fila_cumplida): ?>
                                            <span class="btn btn-edit disabled">Editar</span>
                                            <span class="btn btn-delete disabled">Eliminar</span>
                                        <?php else: ?>
                                            <a
                                                class="btn btn-edit"
                                                href="<?= BASE_URL ?>/dashboard/reservas/aulas/editar-reserva.php?id=<?= $reserva['id'] ?>">
                                                Editar
                                            </a>
                                            <button class="btn btn-delete" popovertarget="eliminar-reserva-<?php echo $reserva['id'] ?>">Eliminar</button>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </article>
            <?php else: ?>
                <article class="no-results no-results-aulas">
                    <p class="text-sinResultsAulas">No hay reservas de aulas registradas</p>
                    <a class="btn btn-gestion-aulas" href="<?= BASE_URL ?>/dashboard/reservas/aulas/crear-reserva.php">Ir a Reservar</a>
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