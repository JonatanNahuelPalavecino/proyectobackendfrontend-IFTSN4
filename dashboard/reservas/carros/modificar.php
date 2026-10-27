<?php

    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/validations.php";
    require_once __DIR__ . "/../../../config/db.php";

    $usuario = getUser();

    if ($usuario["rol"] !== "user") {
        notify('No tenés permisos para editar reservas.', 'error');
        redirect('/dashboard/');
    }

    $reservaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$reservaId) {
        notify('La reserva indicada no es válida.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    }

    $reservaAEditar = getReservaCarroById($pdo, $reservaId, $usuario['id']);
    
    if (!$reservaAEditar) {
        notify('No existe la reserva.', 'error');
        redirect('/dashboard/reservas/carros/ver-reservas.php');
    }

    if ($reservaAEditar['estado'] == "entregado") {
        notify('La reserva ya fue entregada. No podes eliminarla.', 'error');
        redirect('/dashboard/reservas/carros/ver-reservas.php');
    } elseif ($reservaAEditar['estado'] == "devuelto") {
        notify('La reserva ya fue devuelta. No podes eliminarla.', 'error');
        redirect('/dashboard/reservas/carros/ver-reservas.php');
    }

    $carros = getCarrosDisponibles($pdo);
    $titulo = 'Modifica tu reserva de Carro | SIRAUCA - Sistema de Reserva de Aulas y Carros';

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $cart_id = trim($_POST['cart_id']);
        $fecha = trim($_POST['fecha']);
        $comentario = trim($_POST['comentario']);
        $user_id = $usuario['id'];

        $tieneComputadoras = getComputadorasenCarrosById($pdo, $cart_id);

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

                //RECORDAR!: Un carro tiene notebooks asignadas. Por ende, si alguien reserva un carro, reserva las notebooks por tema historial
                //Con esa premisa, si en la edicion del carro, el profesor cambia de carro, se deben cambiar las notebooks reservadas
                if ($cart_id != $reservaAEditar['cart_id']) {
                    //"son diferentes carros. se deben eliminar las notebooks reservadas pertenecientes al carro anterior y agregar las nuevas notebooks del nuevo carro seleccionado";

                    $computadorasAEliminar = getComputadorasReservadasById($pdo, $reservaAEditar['id']);

                    eliminarRegistroDeNotebookDeCarroReservado($pdo, $computadorasAEliminar, $reservaAEditar['id']);

                    guardarRegistroDeNotebookDeCarroReservado($pdo, $tieneComputadoras, $reservaAEditar['id']);

                } 

                $sql ="UPDATE cart_reservations SET cart_id = ?, fecha = ?, comentario = ? WHERE id = ? AND user_id = ?";
                $editarReserva = $pdo->prepare($sql);
                $editarReserva->execute([$cart_id, $fecha, $comentario, $reservaAEditar['id'], $user_id]);

                notify("Reserva modificada con éxito", "success");
                redirect('/dashboard/reservas/carros/ver-reservas.php');

            } catch (\PDOException $e) {
                notify("Ocurrió un error inesperado al conectar con la base de datos.", "error");
                var_dump($e);
            }
        }
    }

?>

<?php require __DIR__ . '/../../../components/header.php'; ?>

<main class="reserva-carro">
    <article class="vr-containerInfo reserva-carro-title">
        <span class="vr-emojiAula">🛒</span>

        <div>
            <h2 class="vr-title">Edicion de reserva de Carros</h2>
            <p class="vr-description">Aca podras editar la reserva del carros</p>
        </div>

        <span class="vr-divisor"></span>

        <span class="vr-logo">PROFESOR</span>

    </article>

    <form method="POST" class="reserva-carro-form" style="max-width: 600px; margin-top: 20px;">
        <h2 class="db-reservas-title">Edita el dato necesario</h2>
        <div class="reserva-carro-group">
            <label class="reserva-carro-label" for="cart_id">Seleccionar:</label>
            <select class="reserva-carro-input" name="cart_id" id="cart_id" required>
                <option value="">Elegi el carro</option>
                <?php foreach ($carros as $carro): ?>
                    <option
                        value="<?= htmlspecialchars($carro['id']) ?>"
                        <?= $carro['total_computadoras'] == 0 ? "disabled" : "" ?>
                        <?= $carro['id'] == $reservaAEditar['cart_id'] ? "selected" : "" ?>
                    >
                        <?= htmlspecialchars($carro['nombre']) ?> -
                        <?= $carro['total_computadoras'] == 0 ? "Sin computadoras asignadas" : "Total de computadoras: " . $carro['total_computadoras'] . "un." ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="reserva-carro-group">
            <label class="reserva-carro-label" for="fecha">Fecha de la reserva:</label>
            <input class="reserva-carro-input" type="date" name="fecha" id="fecha" class="auth-input" min="<?= date('Y-m-d') ?>" value="<?= $reservaAEditar['fecha'] ?>" required>
        </div>


        <div class="reserva-carro-group">
            <label class="reserva-carro-label" for="comentario">Comentario <i>(Config. necesaria):</i></label>
            <textarea class="reserva-carro-textarea" name="comentario" id="comentario" class="auth-input" rows="3" placeholder="Ej: Necesito que todas tengan AutoCAD instalado..." required><?= $reservaAEditar['comentario'] ?></textarea>
        </div>

        <button class="btn reserva-carro-button" type="submit" class="auth-button">Modificar</button>

    </form>
</main>

<?php require __DIR__ . '/../../../components/footer.php'; ?>