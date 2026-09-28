<?php
    require_once __DIR__ . "/../../../config/db.php";
    require_once __DIR__ . "/../../../config/functions.php";

    $usuario = getUser();

    // Guardia de seguridad: Solo el administrador puede ver el inventario total
    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify("No tienes permisos para ver el inventario.", "error");
        redirect("/dashboard/index.php");
    }

    $titulo = "Inventario de Carros || Sistema";

    // Llamamos a la nueva función (que crearemos en el paso 3)
    $inventario_completo = getInventarioCarrosCompleto($pdo);
?>

<?php require_once __DIR__ . "/../../../components/header.php"; ?>

<main class="dashboard">
    <section>
        <div class="reservas-header">
            <h1>📦 Inventario Total de Carros</h1>
            <p>Listado de todos los carros del sistema, incluyendo los disponibles.</p>
            <a href="ver-reservas.php" class="btn" style="display: inline-block; margin-top: 10px; background-color: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                ⬅ Volver a Reservas
            </a>
        </div>

        <div class="reservas-grid" style="margin-top: 20px;">
            <?php if (!empty($inventario_completo)): ?>
                <table class="table-reservas" border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr>
                            <th style="padding: 10px; background-color: #f4f4f4;">Carro</th>
                            <th style="padding: 10px; background-color: #f4f4f4;">Estado Actual</th>
                            <th style="padding: 10px; background-color: #f4f4f4;">Última Fecha Reserva</th>
                            <th style="padding: 10px; background-color: #f4f4f4;">Acciones de Inventario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventario_completo as $carro): ?>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                                    <strong><?= htmlspecialchars($carro['carro']) ?></strong>
                                </td>
                                
                                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                                    <?php if (empty($carro['estado'])): ?>
                                        <span style="padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 0.9em; background-color: #d1e7dd; color: #0f5132;">
                                            Disponible (Sin reservas)
                                        </span>
                                    <?php else: ?>
                                        <span style="padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 0.9em;
                                            <?= $carro['estado'] === 'reservado' ? 'background-color: #fff3cd; color: #856404;' : 
                                               ($carro['estado'] === 'entregado' ? 'background-color: #d4edda; color: #155724;' : 
                                               'background-color: #e2e3e5; color: #383d41;') ?>">
                                            <?= htmlspecialchars(ucfirst($carro['estado'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                                    <?= !empty($carro['fecha']) ? htmlspecialchars(date("d/m/Y", strtotime($carro['fecha']))) : '-' ?>
                                </td>

                                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                                    <!-- Estos botones apuntan al CRUD que ya construiste previamente -->
                                    <a href="modificar.php?id=<?= $carro['id'] ?>" style="background-color: #007bff; color: white; text-decoration: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px;">Editar Carro</a>
                                    <a href="eliminar.php?id=<?= $carro['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este carro permanentemente de la base de datos?');" style="background-color: #dc3545; color: white; text-decoration: none; padding: 5px 10px; border-radius: 3px;">Eliminar Carro</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay carros registrados en la base de datos.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once __DIR__ . "/../../../components/footer.php"; ?>