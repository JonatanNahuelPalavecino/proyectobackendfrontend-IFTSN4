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

$carros = getCarrosDisponibles($pdo);

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
            // Intentamos hacer la reserva si el carro tiene computadoras
            if (empty($tieneComputadoras)) {
                notify("El carro no tiene computadoras asignadas", "error");
                redirect('/dashboard/reservas/carros/crear-reserva.php');
            }

            $idReservaDeCarro = crearReservaCarro($pdo, $user_id, $cart_id, $fecha, $comentario);

            foreach ($tieneComputadoras as $computadora) {
                $sqlInsertComputadoras = "INSERT INTO cart_delivery_items (cart_reservation_id, nombre_computadora, numero_serie) VALUES (?, ?, ?)";
                $registrarComputadorasDeLaReservaDelCarro = $pdo->prepare($sqlInsertComputadoras);
                $registrarComputadorasDeLaReservaDelCarro->execute([$idReservaDeCarro, $computadora["nombre"], $computadora["numero_serie"]]);
            }

            // Camino Feliz: Todo salió bien
            if ($idReservaDeCarro) {

                // Notificacion Admin
                sendMailNotify(
                    ['nombre' => NAME_SISTEMA, 'email' => MAIL_USERNAME],
                    'Gestión de Reservas de Carro',
                    notifyOperationReservCarroAdmin(
                        ['title' => '¡Se registro una nueva reserva de carro en el Sistema!', 'subtitle' => 'Los datos de la reserva de carro son:'],
                        $usuario['nombre'],
                        $tieneComputadoras[0]['pertenece_a_carro'],
                        $fecha,
                        $comentario
                    )
                );

                // Notificacion Profesor
                sendMailNotify(
                    ['nombre' => $usuario['nombre'], 'email' => $usuario['email']],
                    'Sistema de Reservas',
                    notifyOperationReservCarroProf(
                        ['title' => '¡Reserva de carro exitosa!', 'subtitle' => 'Los datos de tu reserva son:'],
                        $tieneComputadoras[0]['pertenece_a_carro'],
                        $fecha,
                        $comentario
                    )
                );
                notify("Reserva confirmada con éxito", "success");
                redirect('/dashboard/reservas/carros/ver-reservas.php');
            } else {
                // Falló la función pero no hubo error grave de Base de Datos
                notify("Hubo un problema al procesar la reserva del carro.", "error");
            }
        } catch (\PDOException $e) {
            // Camino Triste: La base de datos rechazó la operación (Candado de duplicado)
            if ($e->getCode() == 23000) {
                notify("¡Error! Este carro ya se encuentra reservado para esa fecha. Por favor elige otro día u otro carro", "error");

                // Redirección inmediata para que salte la alerta al instante
                redirect('/dashboard/reservas/carros/crear-reserva.php');
            } else {
                // Por si ocurre otro error de base de datos distinto al 23000
                notify("Ocurrió un error inesperado al conectar con la base de datos.", "error");
                var_dump($e);
            }
        }
    }
}
$titulo = 'Reservar Carro Móvil | IFTS N° 4';
?>

<?php require __DIR__ . '/../../../components/header.php'; ?>

<main class="reserva-carro">
    <article class="vr-containerInfo reserva-carro-title">
        <span class="vr-emojiAula">🛒</span>

        <div>
            <h2 class="vr-title">Reserva de Carros</h2>
            <p class="vr-description">Aca podras reservar el carro que necesites</p>
        </div>

        <span class="vr-divisor"></span>

        <span class="vr-logo">PROFESOR</span>

    </article>

    <form method="POST" class="reserva-carro-form" style="max-width: 600px; margin-top: 20px;">
        <h2 class="db-reservas-title">Selecciona un carro</h2>
        <div class="reserva-carro-group">
            <label class="reserva-carro-label" for="cart_id">Seleccionar:</label>
            <select class="reserva-carro-input" name="cart_id" id="cart_id" required>
                <option value="">Elegi el carro</option>
                <?php foreach ($carros as $carro): ?>
                    <option 
                        value="<?= htmlspecialchars($carro['id']) ?>"
                        <?= $carro['total_computadoras'] == 0 ? "disabled" : "" ?>
                    >
                        <?= htmlspecialchars($carro['nombre']) ?> - 
                        <?= $carro['total_computadoras'] == 0 ? "Sin computadoras asignadas" : "Total de computadoras: " . $carro['total_computadoras'] ."un." ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="reserva-carro-group">
            <label class="reserva-carro-label" for="fecha">Fecha de la reserva:</label>
            <input class="reserva-carro-input" type="date" name="fecha" id="fecha" class="auth-input" min="<?= date('Y-m-d') ?>" required>
        </div>


        <div class="reserva-carro-group">
            <label class="reserva-carro-label" for="comentario">Comentario <i>(Config. necesaria):</i></label>
            <textarea class="reserva-carro-textarea" name="comentario" id="comentario" class="auth-input" rows="3" placeholder="Ej: Necesito que todas tengan AutoCAD instalado..." required></textarea>
        </div>

        <button class="btn reserva-carro-button" type="submit" class="auth-button">Confirmar reserva</button>

    </form>
</main>

<?php require __DIR__ . '/../../../components/footer.php'; ?>