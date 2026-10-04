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

$inventario_completo = getInventarioCarrosCompleto($pdo);
?>

<?php require_once __DIR__ . "/../../components/header.php"; ?>

<main class="dashboard">
    <section>
        <div class="reservas-header">
            <h1>📦 Inventario Total de Carros</h1>
            <p>Listado de todos los carros del sistema, incluyendo los disponibles.</p>
            <div>
                <a href="<?= BASE_URL ?>/dashboard/carros/crear.php" class="btn reservar">+ Carro</a>
            </div>
            <a href="<?php echo BASE_URL ?>/dashboard">
                ⬅ Volver al dashboard
            </a>
        </div>

        <?php if (!empty($inventario_completo)): ?>
            <table class="table-reservas" border="1">
                <thead>
                    <tr>
                        <th>Carro</th>
                        <th>Estado Actual</th>
                        <th>Última Fecha Reserva</th>
                        <th>Acciones de Inventario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventario_completo as $carro): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($carro['carro']) ?></strong>
                            </td>

                            <td>
                                <?php if (empty($carro['estado'])): ?>
                                    <span>
                                        Disponible (Sin reservas)
                                    </span>
                                <?php else: ?>
                                    <span style="padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 0.9em;
                                            <?= $carro['estado'] === 'reservado' ? 'background-color: #fff3cd; color: #856404;' : ($carro['estado'] === 'entregado' ? 'background-color: #d4edda; color: #155724;' :
                                                    'background-color: #e2e3e5; color: #383d41;') ?>">
                                        <?= htmlspecialchars(ucfirst($carro['estado'])) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= !empty($carro['fecha']) ? htmlspecialchars(date("d/m/Y", strtotime($carro['fecha']))) : '-' ?>
                            </td>

                            <td>
                                <a href="modificar.php?id=<?= $carro['id'] ?>">Editar Carro</a>
                                <a href="eliminar.php?id=<?= $carro['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este carro permanentemente de la base de datos?');">Eliminar Carro</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay carros registrados en la base de datos.</p>
        <?php endif; ?>

    </section>
</main>

<?php require_once __DIR__ . "/../../components/footer.php"; ?>