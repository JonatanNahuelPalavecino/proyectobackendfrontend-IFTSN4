<?php
    require_once __DIR__ . "/../../../config/db.php";
    require_once __DIR__ . "/../../../config/functions.php";
    
    $usuario = getUser();

    // Guardia de seguridad: Debe estar logueado
    if (!$usuario){
        notify("Debes iniciar sesión para acceder.", "error");
        redirect("/login.php");
    }

    if($usuario['rol'] === "admin") {

        $titulo = "Total de Reservas | Reserva tu carro";

        // Vista de Admin: Trae TODAS las reservas del sistema (usando tu nueva función)
        $reservas_admin = getReservasCarrosAdmin($pdo);
    } else {

        $titulo = "Mis reservas | Reserva tu carro";
    
        // Vista de Usuario: Trae solo las reservas activas del profesor logueado
        $reservas_user = getReservasCarrosActivas($pdo, $usuario['id']);
    }
?>

<?php require_once __DIR__ . "/../../../components/header.php"; ?>

    <main >
        <section class="verReservas">

            <?php if ($usuario['rol'] === 'admin'): ?>
                <!-- VISTA DEL ADMINISTRADOR -->
                <article class="verReservas-header">

                    <span class="verReservas-emoji">📊</span>

                    <div>
                        <h1> Reservas de carros</h1>
                        <p>Todas las reservas de carros del sistema.</p>
                    </div>

                    <span class="verReservas-divisor"></span>

                    <div>
                        <span class="verReservas-logo">SISTEMA</span>
                    </div>
                    
                </article>

                <section class="verReservas-container">
                    <?php if (!empty($reservas_admin)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Carro</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Comentarios</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservas_admin as $reserva): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($reserva['carro']) ?></td>
                                        <td><?= htmlspecialchars(date("d/m/Y", strtotime($reserva['fecha']))) ?></td>

                                        <td>
                                            <span style="<?= $reserva['estado'] === 'reservado' ? 'background-color: #fff3cd; color: #856404;' :
                                                ($reserva['estado'] === 'entregado' ? 'background-color: #d4edda; color: #155724;' :
                                                'background-color: #e2e3e5; color: #383d41;') ?>">
                                                <?= htmlspecialchars(ucfirst($reserva['estado'])) ?>
                                            </span>
                                        </td>

                                        <td><?= htmlspecialchars($reserva['comentario']) ?></td>

                                        <td class="verReservas-acciones">
                                            <a class="verReservas-editar" href="modificar.php?id=<?= $reserva['id'] ?>">Editar</a>

                                            <button class="verReservas-eliminar" href="eliminar.php?id=<?= $reserva['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este carro permanentemente?');">Eliminar</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-results">No hay reservas de carros en el sistema.</p>
                    <?php endif; ?>
                    <a href="../../index.php">Volver</a>
                </section>

            <?php else: ?>
                <article class="verReservas-header">

                    <span class="verReservas-emoji">🛒</span>

                    <div>
                        <h1>Mis reservas de Carros</h1>
                        <p>Aquí podrás ver tus reservas de carros activas.</p>
                    </div>

                    <span class="verReservas-divisor"></span>

                    <div>
                        <span class="verReservas-logo">USUARIO</span>
                    </div>

                </article>

                <section class="verReservas-container">
                    <?php if (!empty($reservas_user)): ?>
                        <table >
                            <thead>
                                <tr>
                                    <th>Carro</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Comentarios</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservas_user as $reserva): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($reserva['carro']) ?></td>
                                        <td><?= htmlspecialchars(date("d/m/Y", strtotime($reserva['fecha']))) ?></td>

                                        <td>
                                            <span style="<?= $reserva['estado'] === 'reservado' ? 'background-color: #fff3cd; color: #856404;' :
                                                ($reserva['estado'] === 'entregado' ? 'background-color: #d4edda; color: #155724;' :
                                                'background-color: #e2e3e5; color: #383d41;') ?>">
                                                <?= htmlspecialchars(ucfirst($reserva['estado'])) ?>
                                            </span>
                                        </td>

                                        <td><?= htmlspecialchars($reserva['comentario']) ?></td>

                                        <td class="verReservas-acciones">
                                            <a class="verReservas-editar" href="<?= BASE_URL ?>/dashboard/reservas/carros/modificar.php?id=<?= $reserva['id'] ?>">Editar</a>
                                            <button class="verReservas-eliminar" type="button" popovertarget="eliminar-reserva-<?= $reserva['id'] ?>">Eliminar</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-results">No tienes reservas de carros activas.</p>
                    <?php endif; ?>
                    
                </section>

            <?php endif; ?>

        </section>
    </main>

    <?php if ($usuario['rol'] === 'user' && !empty($reservas_user)): ?>
        <?php foreach ($reservas_user as $reserva): ?>
            <section id="eliminar-reserva-<?= $reserva['id'] ?>" popover class="popover-eliminar">
                <section class="popover-eliminar-content">
                    <h2>Eliminar Reserva</h2>
                    <p>
                        ¿Estás seguro que quieres eliminar tu reserva?
                    </p>
                    <section class="popover-eliminar-info">
                        <p>
                            <strong>Carro:</strong>
                            <?= htmlspecialchars($reserva['carro']) ?>
                        </p>
                        <p>
                            <strong>Fecha:</strong>
                            <?= htmlspecialchars(date("d/m/Y", strtotime($reserva['fecha']))) ?>
                        </p>
                        <p>
                            <strong>Estado:</strong>
                            <?= htmlspecialchars(ucfirst($reserva['estado'])) ?>
                        </p>

                    </section>
                    <section class="popover-eliminar-actions">
                        <a 
                            class="popover-confirmar"
                            href="<?= BASE_URL ?>/dashboard/reservas/carros/eliminar.php?id=<?= $reserva['id'] ?>">
                            Eliminar
                        </a>
                        <button 
                            type="button" 
                            class="popover-cancelar"
                            popovertarget="eliminar-reserva-<?= $reserva['id'] ?>" 
                            popovertargetaction="hide">
                            Cancelar
                        </button>
                    </section>

                </section>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>


<?php include __DIR__ . "/../../../components/footer.php"; ?>
