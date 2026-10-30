<?php
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../config/functions.php";

$usuario = getUser();

// Guardia de seguridad: Solo el administrador puede ver el inventario total
if (!$usuario || $usuario['rol'] !== 'admin') {
    notify("No tienes permisos para ver el inventario.", "error");
    redirect("/dashboard/index.php");
}

$titulo = "Inventario de Carros || Sistema";

$carros = getInventarioCarrosCompleto($pdo);

foreach ($carros as $indice => $carro) {
    $total = getTotalComputadorasenCarrosById($pdo, $carro['id']);
    $carros[$indice]['computadorasAsignadas'] = $total;
}

?>

<?php require_once __DIR__ . "/../../components/header.php"; ?>

<main>
    <section class="verReservas-header">
        <div class="vr-containerInfo">
            <span class="container-smallEmoji container-smallCarts">
                <svg class="bi bi-cart3 small-emoji" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                </svg>
            </span>

            <article>
                <h2 class="vr-title">Gestión de Carros</h2>
                <p class="vr-description">Puedes ver y gestionar todos los carros del sistema</p>
            </article>

            <span class="vr-divisor"></span>

            <span class="vr-logo">ADMINISTRADOR</span>

            <article class="dh-actions">
                <a href="<?= BASE_URL ?>/dashboard/carros/crear.php" class="btn btn-gestion-carts">Nuevo Carro</a>
            </article>
        </div>
    </section>

    <section class="verReservas-container">
        <?php if (!empty($carros)): ?>
            <article class="vr-tabla-card">
                <table class="vr-table">
                    <thead>
                        <tr>
                            <th>Carro</th>
                            <th>Descripcion</th>
                            <th class="col-centro">Capacidad</th>
                            <th class="col-centro">Computadoras Asignadas</th>
                            <th class="col-centro">Fecha de Creación</th>
                            <th class="col-centro">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carros as $carro): ?>
                            <tr class="vr-description">
                                <td>
                                    <?= htmlspecialchars($carro['nombre']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($carro['descripcion']) ?>
                                </td>

                                <td class="col-centro">
                                    <?= htmlspecialchars($carro['capacidad']) ?>
                                </td>

                                <td class="col-centro">
                                    <?= htmlspecialchars($carro['computadorasAsignadas']) ?>
                                </td>

                                <td class="col-centro">
                                    <?= !empty($carro['created_at']) ? htmlspecialchars(date("d/m/Y", strtotime($carro['created_at']))) : '-' ?>
                                </td>

                                <td class="col-centro">
                                    <div class="verReservas-acciones">
                                        <?php if ($carro["estado"] == "reservado" || $carro["estado"] == "entregado"): ?>
                                            <a class="btn btn-edit disabled">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                                </svg>

                                            </a>

                                            <button class="btn btn-delete disabled">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                </svg>

                                            </button>
                                        <?php else: ?>
                                            <a class="btn btn-edit" href="<?= BASE_URL ?>/dashboard/carros/modificar.php?id=<?= $carro['id'] ?>">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                                </svg>

                                            </a>

                                            <button class="btn btn-delete" popovertarget="eliminar-carro-<?php echo $carro['id'] ?>">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                </svg>

                                            </button>
                                        <?php endif ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </article>
        <?php else: ?>
            <article class="no-results no-results-carts">
                <p class="text-sinResultsCarts">No hay carros registrados en la base de datos.</p>
            </article>
        <?php endif; ?>

    </section>
</main>

<?php foreach ($carros as $carro): ?>
    <section id="eliminar-carro-<?php echo $carro['id'] ?>" popover class="popover-eliminar">
        <section class="popover-eliminar-content">
            <h2>Eliminar Carro</h2>
            <p>
                ¿Estas seguro que queres eliminar el carro?
            </p>

            <section class="popover-eliminar-info">
                <p>
                    <strong>ID Carro: </strong>
                    <?= htmlspecialchars($carro['id']); ?>
                </p>

                <p>
                    <strong>Nombre Carro: </strong>
                    <?= htmlspecialchars($carro['nombre']); ?>
                </p>
                <p>
                    <strong>Capacidad: </strong>
                    <?= htmlspecialchars($carro['capacidad']); ?>
                </p>

                <p>
                    <strong>Creado: </strong>
                    <?= htmlspecialchars($carro['created_at']); ?>
                </p>
            </section>

            <section class="popover-eliminar-actions">
                <a class="popover-confirmar" href="<?php echo BASE_URL; ?>/dashboard/carros/eliminar.php?id=<?php echo $carro['id']; ?>">Eliminar</a>
                <button class="popover-cancelar" type="button" popovertarget="eliminar-carro-<?php echo $carro['id'] ?>" popovertargetaction="hide">Cancelar</button>
            </section>

        </section>
    </section>
<?php endforeach; ?>

<?php require_once __DIR__ . "/../../components/footer.php"; ?>
