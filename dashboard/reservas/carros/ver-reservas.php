<?php
    require_once __DIR__ . "/../../../config/db.php";
    require_once __DIR__ . "/../../../config/functions.php";

    $usuario = getUser();

    // Guardia de seguridad: Debe estar logueado
    if (!$usuario) {
        notify("Debes iniciar sesión para acceder.", "error");
        redirect("/login.php");
    }

    if ($usuario['rol'] === "admin") {
        $titulo = "Total de Reservas | Reserva tu carro";
        $reservas_admin = getReservasCarrosAdmin($pdo);
    } else {
        $titulo = "Mis reservas | Reserva tu carro";
        $reservas_user = getDetalleReservasCarrosByUser($pdo, $usuario['id']);
    }

    if (isset($_GET['success'])) {
        notify($_GET['success'], 'success');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $accion = $_POST['accion'] ?? null;
        $reservaId = $_POST['reserva_id'] ?? null;

        if ($usuario['rol'] === 'admin' && $accion === 'devolver' && $reservaId) {

            $consulta = $pdo->prepare("UPDATE cart_reservations SET estado = 'devuelto', devuelto_at = NOW() WHERE id = :id AND estado = 'entregado'");
            $consulta->execute(['id' => $reservaId]);

            notify("El carro fue marcado como devuelto.", "success");
            redirect("/dashboard/reservas/carros/ver-reservas.php");
        }
    }

?>

<?php require_once __DIR__ . "/../../../components/header.php"; ?>

<main>
    <section class="verReservas-header">
        <?php if ($usuario['rol'] === 'admin'): ?>
            <article class="vr-containerInfo">

                <span class="container-smallEmoji container-smallCarts">
                    <svg class="bi bi-cart3 small-emoji" xmlns="http://www.w3.org/2000/svg" fill="currentColor"  viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                </span>

                <div>
                    <h2 class="vr-title">Reservas de Carros</h2>
                    <p class="vr-description">Todas las reservas de carros del sistema</p>
                </div>

                <span class="vr-divisor"></span>

                <span class="vr-logo">SISTEMA</span>
            </article>
        <?php else: ?>
            <article class="vr-containerInfo">
                <span class="vr-emojiCart">🛒</span>
                <div>
                    <h2 class="vr-title">Mis reservas de Carros</h1>
                    <p class="vr-description">Aquí podrás ver tus reservas de carros activas.</p>
                </div>
                
                <span class="vr-divisor"></span>

                <div>
                    <span class="vr-logo">PROFESOR</span>
                </div>
            </article>
        <?php endif; ?>
    </section>


    <?php if ($usuario['rol'] === 'admin'): ?>
        <section class="verReservas-container">
            <?php if (!empty($reservas_admin)): ?>
                <article class="vr-tabla-card">
                    <table class="vr-table">
                        <thead>
                            <tr>
                                <th>Carro</th>
                                <th>Reservante</th>
                                <th>Fecha</th>
                                <th class="col-centro">Estado</th>
                                <th>Comentarios</th>
                                <th class= "col-centro">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($reservas_admin as $reserva): ?>
                                <?php 
                                    $reserva_entregado= $reserva['estado'] === "entregado";
                                    $reserva_reservado = $reserva['estado'] === "reservado";    
                                    $reserva_devuelto = $reserva['estado'] === "devuelto";    
                                ?>  
                                <tr class="<?= ($reserva_entregado) ? "vr-fila-pendiente": (($reserva_reservado) ? 'vr-fila-activa': "vr-fila-finalizada") ?>"> 
                                    <td><?= htmlspecialchars($reserva['carro']) ?></td>
                                    <td><?= htmlspecialchars(ucwords($reserva['nombre'])) ?></td>
                                    <td><?= htmlspecialchars(formatDate($reserva['fecha'])) ?></td>
                                    <td class="col-centro">
                                        <!-- Faltaria implementar el color devuelto -->
                                        <span class="verReservas-status <?= ($reserva_entregado) ? "vr-chipPendiente": (($reserva_reservado) ? "vr-chipActivo" : "vr-chipFinalizado") ?>"><?= htmlspecialchars(ucfirst($reserva['estado'])) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($reserva['comentario']) ?></td>

                                    <td class="verReservas-acciones col-centro">
                                        <?php if ($reserva['estado'] === 'reservado'): ?>
                                            <button
                                                type="button"
                                                class="btn entregar-carro"
                                                data-id="<?= $reserva['id'] ?>"
                                                data-entregar-url="<?= BASE_URL ?>/dashboard/reservas/carros/estados/entregar-carro.php"
                                                data-remito-url="<?= BASE_URL ?>/config/pdf/remito-carro.php"
                                                data-volver-url="<?= BASE_URL ?>/dashboard/reservas/carros/ver-reservas.php">
                                                Entregar
                                            </button>
                                        <?php elseif ($reserva['estado'] === 'entregado'): ?>
                                            <form method="post">

                                                <input
                                                    type="hidden"
                                                    name="reserva_id"
                                                    value="<?= $reserva['id'] ?>">

                                                <button
                                                    class="btn btn-devolver"
                                                    type="submit"
                                                    name="accion"
                                                    value="devolver">
                                                    Devolver
                                                </button>

                                            </form>
                                        <?php else: ?>
                                            <button class ="btn btn-devolver" disabled>Devolver</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </article>
            <?php else: ?>
                <article class="no-results no-results-carts">
                    <p class="text-sinResultsCarts">No hay reservas de carros en el sistema.</p>
                </article>
            <?php endif; ?>

        </section>
        <script src="<?= BASE_URL ?>/assets/js/entregar-carro.js" defer></script>

    <?php else: ?>
        <section class="verReservas-container">
            <?php if (!empty($reservas_user)): ?>
                <article class="vr-tabla-card">
                    <table class="vr-table">
                        <thead>
                            <tr>
                                <th>Carro</th>
                                <th>Fecha</th>
                                <th class="col-centro">Estado</th>
                                <th>Comentarios</th>
                                <th class="col-centro">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($reservas_user as $reserva): ?>
                                <?php 
                                    $reserva_entregado= $reserva['estado'] === "entregado";
                                    $reserva_reservado = $reserva['estado'] === "reservado";    
                                    $reserva_devuelto = $reserva['estado'] === "devuelto";    
                                ?>  
                                <tr class="<?= ($reserva_entregado) ? "vr-fila-pendiente": (($reserva_reservado) ? 'vr-fila-activa': "vr-fila-finalizada") ?>">
                                    <td><?= htmlspecialchars($reserva['carro']) ?></td>
                                    <td><?= htmlspecialchars(date("d/m/Y", strtotime($reserva['fecha']))) ?></td>
    
                                    <td class="col-centro">
                                        <!-- Faltaria implementar el color devuelto -->
                                        <span class="verReservas-status <?= ($reserva_entregado) ? "vr-chipPendiente": (($reserva_reservado) ? "vr-chipActivo" : "vr-chipFinalizado") ?>"><?= htmlspecialchars(ucfirst($reserva['estado'])) ?></span>
                                    </td>

                                    <td><?= htmlspecialchars($reserva['comentario']) ?></td>

                                    <td class="col-centro">
                                        <div class="verReservas-acciones ">

                                            <?php if ($reserva_reservado): ?>
                                                <a class="btn btn-edit" href="<?= BASE_URL ?>/dashboard/reservas/carros/modificar.php?id=<?= $reserva['id'] ?>">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                    </svg>
                                                
                                                </a>
    
                                                <button class="btn btn-delete" type="button" popovertarget="eliminar-reserva-<?= $reserva['id'] ?>">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                                    </svg>
                                                
                                                </button>
                                            <?php else:?>
                                                <button class="btn btn-edit disabled">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                    </svg>
                                                
                                                </button>
                                                
                                                <button class="btn btn-delete disabled">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                                    </svg>
                                                
                                                </button>
                                                
                                            <?php endif ;?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </article>
            <?php else: ?>
                <article class="no-results no-results-carts">
                    <p class="text-sinResultsCarts">No tienes reservas de carros activas.</p>
                    <a class="btn btn-gestion-carts" href="<?= BASE_URL ?>/dashboard/reservas/carros/crear-reserva.php">Ir a reservar</a>
                </article>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>

<?php if ($usuario['rol'] === 'user' && !empty($reservas_user)): ?>
    <?php foreach ($reservas_user as $reserva): ?>
        <section id="eliminar-reserva-<?= $reserva['id'] ?>" popover class="popover-eliminar">
            <section class="popover-eliminar-content">
                <h2>Eliminar Reserva de Carro</h2>
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