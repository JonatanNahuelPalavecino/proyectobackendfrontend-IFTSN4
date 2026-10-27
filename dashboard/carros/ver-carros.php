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

<main class="dashboard">
    <section>
        <div class="reservas-header">
            <h1>📦 Inventario Total de Carros</h1>
            <a href="<?= BASE_URL ?>/dashboard/carros/crear.php" class="btn reservar">+ Carro</a>
        </div>

        <?php if (!empty($carros)): ?>
            <table class="table-reservas" border="1">
                <thead>
                    <tr>
                        <th>Carro</th>
                        <th>Descripcion</th>
                        <th>Capacidad</th>
                        <th>Computadoras Asignadas</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carros as $carro): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($carro['nombre']) ?></strong>
                            </td>

                            <td>
                                <?= htmlspecialchars($carro['descripcion']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($carro['capacidad']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($carro['computadorasAsignadas']) ?>
                            </td>

                            <td>
                                <?= !empty($carro['created_at']) ? htmlspecialchars(date("d/m/Y", strtotime($carro['created_at']))) : '-' ?>
                            </td>

                            <?php if ($carro["estado"] == "reservado" || $carro["estado"] == "entregado"): ?>
                                <td>
                                    <span>Editar</span>
                                    <span>Eliminar</span>
                                </td>
                            <?php else: ?>
                                <td>
                                    <a href="<?= BASE_URL ?>/dashboard/carros/modificar.php?id=<?= $carro['id'] ?>">Editar</a>
                                    <button popovertarget="eliminar-carro-<?php echo $carro['id'] ?>">Eliminar</button>
                                </td>
                            <?php endif ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay carros registrados en la base de datos.</p>
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
<?php endforeach ?>

<?php require_once __DIR__ . "/../../components/footer.php"; ?>