<?php
require_once __DIR__ . "/../../../config/functions.php";
require_once __DIR__ . "/../../../config/validations.php";
require_once __DIR__ . "/../../../config/db.php";
require_once __DIR__ . "/../../../config/sendMailNotify.php";
require_once __DIR__ . "/../../../config/email/templates/message.php";

$usuario = getUser();

if (!$usuario || $usuario['rol'] !== 'user') {
    notify('No tenés permisos para crear reservas de carros.', 'error');
    redirect('/dashboard/index.php');
}

// Variables de la URL (Vista)
$carroId = $_GET['carro'] ?? null;
$fechaSeleccionada = $_GET['fecha'] ?? null;
$mes = $_GET['mes'] ?? date('Y-m');

// Traer todos los carros para las tarjetas
$carros = getCarrosDisponibles($pdo);

$carroSeleccionado = null;
$diasCalendario = [];
$diaDisponible = false;

if ($carroId) {
    $carroSeleccionado = getCarroReservable($pdo, $carroId); 
}

if ($carroSeleccionado) {
    if (!isset($carroSeleccionado['dia_desde'])) {
        $carroSeleccionado['dia_desde'] = 1; 
    }
    if (!isset($carroSeleccionado['dia_hasta'])) {
        $carroSeleccionado['dia_hasta'] = 5; 
    }

    $diasCalendario = crearDiasCalendarioCarros($pdo, $carroSeleccionado, $mes);
}

if ($carroSeleccionado && $fechaSeleccionada) {
    $diaDisponible = isCarrosDisponiblesDia($pdo, $carroSeleccionado['id'], $fechaSeleccionada);
}

$titulo = 'Reservar Carro Móvil | IFTS N° 4';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = trim($_POST['cart_id']);
    $fecha = trim($_POST['fecha']);
    $comentario = trim($_POST['comentario']);
    $user_id = $usuario['id'];

    $sqlItemsCarro = "SELECT computers.id, computers.nombre, numero_serie, carts.nombre AS pertenece_a_carro FROM carts JOIN computers ON carts.id = computers.cart_id WHERE carts.id = ?";
    $consulta = $pdo->prepare($sqlItemsCarro);
    $consulta->execute([$cart_id]);
    $tieneComputadoras = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $error = validateInputsCreateOrEditReservationsCarros($cart_id, $fecha, $comentario, $tieneComputadoras, $pdo);

    if ($error) {
        notify($error, "error");
    } else {
        try {
            if (empty($tieneComputadoras)) {
                notify("El carro no tiene computadoras asignadas", "error");
                redirect("/dashboard/reservas/carros/crear-reserva.php?carro={$cart_id}&fecha={$fecha}#confirmacion");
            }

            $idReservaDeCarro = crearReservaCarro($pdo, $user_id, $cart_id, $fecha, $comentario);

            foreach ($tieneComputadoras as $computadora) {
                $sqlInsertComputadoras = "INSERT INTO cart_delivery_items (cart_reservation_id, nombre_computadora, numero_serie) VALUES (?, ?, ?)";
                $registrarComputadoras = $pdo->prepare($sqlInsertComputadoras);
                $registrarComputadoras->execute([$idReservaDeCarro, $computadora["nombre"], $computadora["numero_serie"]]);
            }

            if ($idReservaDeCarro) {
                sendMailNotify(['nombre' => NAME_SISTEMA, 'email' => MAIL_USERNAME], 'Gestión de Reservas', notifyOperationReservCarroAdmin(['title' => '¡Nueva reserva!', 'subtitle' => 'Datos:'], $usuario['nombre'], $tieneComputadoras[0]['pertenece_a_carro'], $fecha, $comentario));
                sendMailNotify(['nombre' => $usuario['nombre'], 'email' => $usuario['email']], 'Sistema IFTS', notifyOperationReservCarroProf(['title' => '¡Reserva exitosa!', 'subtitle' => 'Datos:'], $tieneComputadoras[0]['pertenece_a_carro'], $fecha, $comentario));
                
                notify("Reserva confirmada con éxito para todo el día", "success");
                redirect('/dashboard/reservas/carros/ver-reservas.php');
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                notify("¡Error! Este carro ya se encuentra reservado para esta fecha.", "error");
            } else {
                notify("Error inesperado en la base de datos.", "error");
            }
        }
    }
}
?>

<?php require __DIR__ . '/../../../components/header.php'; ?>

<main class="reserva-carro">
    <section style ="padding: 20px 10px;">
        <article class="vr-containerInfo reserva-carro-title">
            <span class="vr-emojiAula">🛒</span>
            <div>
                <h2 class="vr-title">Reserva de Carros</h2>
                <p class="vr-description">Seleccioná un carro y elegí el día en el calendario.</p>
            </div>
            <span class="vr-divisor"></span>
            <span class="vr-logo">PROFESOR</span>
        </article>
    </section>

    <!-- TARJETAS DE CARROS -->
    <section class="dashboard-reservas">
        <?php if (count($carros) === 0): ?>
            <article class="no-results no-results-carts">
                <p class="text-sinResultsCarts">No hay carros habilitados por el momento.</p>
            </article>
        <?php else: ?>
            <div class="dashboard-reservas-container">
                <article class="db-reservas-header">
                    <h2 class="db-reservas-title">Selecciona un carro</h2>
                </article>

                <div class="db-reservas-cards">
                    <?php foreach ($carros as $carro): ?>
                        <article class="db-reservas-item <?= $carro['total_computadoras'] == 0 ? 'disabled' : '' ?>">
                            <div class="db-reserva-iconCart">
                                <span class="vr-emojiCart">🛒</span>
                            </div>
                            <div class="db-reservas-body">
                                <div class="db-reserva-inline">
                                    <h3 class="db-reserva-subtitle"><?= htmlspecialchars($carro['nombre']) ?></h3>
                                    <span class="db-reserva-chipActiva" style="background-color: var(--secondary-color-background); color: var(--secondary-color);">
                                        <?= $carro['total_computadoras'] == 0 ? "Sin computadoras" : $carro['total_computadoras'] . " unidades" ?>
                                    </span>
                                </div>
                                <div>
                                    <?php if ($carro['total_computadoras'] > 0): ?>
                                        <a class="btn btn-gestion-carts" href="?carro=<?= $carro['id']; ?>&mes=<?= date('Y-m'); ?>#calendario">
                                            Ver disponibilidad
                                        </a>
                                    <?php else: ?>
                                        <p style="color: var(--danger-color); font-size: 0.9em; font-weight: bold;">Inhabilitado</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- CALENDARIO INDEPENDIENTE PARA CARROS -->
    <?php if ($carroSeleccionado): ?>
        <section id="calendario">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/TP-integrador-Lopez-Luna_Palavecino/components/calendarioCarro.php"; ?>
        </section>
    <?php endif; ?>

    <!-- FORMULARIO DE CONFIRMACIÓN -->
    <?php if ($carroSeleccionado && $fechaSeleccionada): ?>
        <section class="dashboard-reservas" id="confirmacion">
            <article class="dashboard-reservas-container">
                <h2 class="vr-title">Reserva del <?php echo formatDate(htmlspecialchars($fechaSeleccionada)); ?></h2>
    
                <?php if (!$diaDisponible): ?>
                    <div style="background: #ffebee; padding: 15px; border-radius: 8px; border-left: 4px solid var(--danger-color); margin-top: 15px;">
                        <p style="color: var(--danger-color); margin: 0; font-weight: bold;">Este carro ya se encuentra reservado para esta fecha. Por favor, selecciona otro día en el calendario.</p>
                    </div>
                <?php else: ?>
                    <article class="horario-contenedor" style="background: var(--secondary-color-background); border-left: 4px solid var(--secondary-color); border-radius: 8px;">
                        <h3 class="horario-titulo" style="color: var(--secondary-color);">¡Día disponible para reserva completa!</h3>
                        
                        <form method="POST" style="margin-top: 15px;">
                            <input type="hidden" name="cart_id" value="<?php echo $carroSeleccionado['id']; ?>">
                            <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fechaSeleccionada); ?>">
    
                            <div class="reserva-carro-group" style="margin-bottom: 15px;">
                                <label class="reserva-carro-label" for="comentario" style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--navy);">Comentario (Requerido):</label>
                                <textarea class="reserva-carro-textarea auth-input" name="comentario" rows="3" style="width: 100%; padding: 10px;" placeholder="Ej: Necesito el carro en el aula 5 para la clase de bases de datos..." required></textarea>
                            </div>
    
                            <button class="btn btn-gestion-carts" type="submit" style="width: 100%; padding: 12px; font-size: 1.1em;">Confirmar Reserva del Día</button>
                        </form>
                    </article>
                <?php endif; ?>
            </article>
        </section>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../../../components/footer.php'; ?>