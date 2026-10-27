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
                
                <span class="container-smallEmoji container-smallAulas">
                    <svg class="bi bi-building small-emoji" xmlns="http://www.w3.org/2000/svg" fill="currentColor"  viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>
                    </svg>
                </span>

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

                <span class="container-smallEmoji container-smallAulas">
                    <svg class="bi bi-building small-emoji" xmlns="http://www.w3.org/2000/svg" fill="currentColor"  viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>
                    </svg>
                </span>

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
                                <tr class="<?= $fila_cumplida ? 'vr-fila-finalizada' : 'vr-fila-activa' ?>">
                                    <td class="vr-td-fuerte"><?= htmlspecialchars(ucwords($reserva['usuario'])) ?></td>
                                    <td><?= htmlspecialchars($reserva['aula']) ?></td>
                                    <td><?= formatDate(htmlspecialchars($reserva['fecha'])) ?></td>
                                    <td><?= htmlspecialchars($reserva['hora_inicio']) ?> hs - <?= htmlspecialchars($reserva['hora_fin']) ?> hs</td>
                                    <td class="col-centro"><?= htmlspecialchars($reserva['capacidad']) ?></td>
                                    <td class="col-centro">
                                        <span class="verReservas-status <?= $fila_cumplida ? 'vr-chipFinalizado' : 'vr-chipActivo' ?>">
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
                                <tr class="<?= $fila_cumplida ? 'vr-fila-finalizada' : 'vr-fila-activa' ?>">
                                    <td><?= htmlspecialchars($reserva['aula']) ?></td>
                                    <td><?= formatDate(htmlspecialchars($reserva['fecha'])) ?></td>
                                    <td><?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</td>
                                    <td class="col-centro"><?= htmlspecialchars($reserva['capacidad']) ?></td>
                                    <td class="col-centro">
                                        <span class="verReservas-status <?= $fila_cumplida ? 'vr-chipFinalizado' : 'vr-chipActivo' ?>">
                                            <?= $fila_cumplida ? 'Cumplida' : 'Activa' ?>
                                        </span>
                                    </td>
                                    <td class="col-centro" >
                                        <div class="verReservas-acciones">
                                            
                                            <?php if ($fila_cumplida): ?>
                                                <button class="btn btn-edit disabled">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                    </svg>
                                                </button>
                                                
                                                <span class="btn btn-delete disabled">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                                    </svg>
                                                </span>
                                            <?php else: ?>
                                                <a
                                                    class="btn btn-edit"
                                                    href="<?= BASE_URL ?>/dashboard/reservas/aulas/editar-reserva.php?id=<?= $reserva['id'] ?>">

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                    </svg>

                                                </a>

                                                <button class="btn btn-delete" popovertarget="eliminar-reserva-<?php echo $reserva['id'] ?>">
                                                
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                                    </svg>

                                                </button>
                                            <?php endif ?>

                                        </div>
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
                    <h2>Eliminar reserva de aula</h2>
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