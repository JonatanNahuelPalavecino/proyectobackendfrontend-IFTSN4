<?php
    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/validations.php";
    require_once __DIR__ . "/../../../config/db.php";

    $usuario = getUser();

    if (!$usuario || !in_array($usuario['rol'], ['user', 'admin'], true)) {
        notify('No tenés permisos para editar reservas.', 'error');
        redirect('/dashboard/');
    }

    $reservaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$reservaId) {
        notify('La reserva indicada no es válida.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    }

    $reserva = getReservaById($pdo, $reservaId);

    if (!$reserva) {
        notify('La reserva no existe.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    }

    if ($usuario['rol'] !== 'admin' && (int) $reserva['user_id'] !== (int) $usuario['id']) {
        notify('No tenés permisos para editar esta reserva.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    }

    $aulaId = (int) $reserva['classroom_id'];
    $mes = $_GET['mes'] ?? substr($reserva['fecha'], 0, 7);
    $fechaSeleccionada = $_GET['fecha'] ?? $reserva['fecha'];
    $horaInicioSeleccionada = $reserva['hora_inicio'];
    $horaFinSeleccionada = $reserva['hora_fin'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reservaId = filter_input(INPUT_POST, 'reservation_id', FILTER_VALIDATE_INT);
        $reserva = getReservaById($pdo, $reservaId);

        if (!$reserva || ($usuario['rol'] !== 'admin' && (int) $reserva['user_id'] !== (int) $usuario['id'])) {
            notify('No tenés permisos para editar esta reserva.', 'error');
            redirect('/dashboard/reservas/aulas/ver-reservas.php');
        }

        $aulaId = (int) $reserva['classroom_id'];
        $fechaSeleccionada = $_POST['fecha'] ?? null;
        $horaInicioSeleccionada = $_POST['hora_inicio'] ?? null;
        $horaFinSeleccionada = $_POST['hora_fin'] ?? null;

        $error = validateInputsCreateOrEditReservations(
            $pdo,
            $aulaId,
            $fechaSeleccionada,
            $horaInicioSeleccionada,
            $horaFinSeleccionada,
            $reservaId
        );

        if ($error) {
            notify($error, 'error');
        } else {
            try {
                $sql = 'UPDATE reservations
                        SET fecha = :fecha,
                            hora_inicio = :hora_inicio,
                            hora_fin = :hora_fin
                        WHERE id = :id_reserva';

                $editarReserva = $pdo->prepare($sql);
                $editarReserva->execute([
                    'fecha' => $fechaSeleccionada,
                    'hora_inicio' => $horaInicioSeleccionada,
                    'hora_fin' => $horaFinSeleccionada,
                    'id_reserva' => $reservaId
                ]);

                notify('Reserva modificada correctamente.', 'success');
                redirect('/dashboard/reservas/aulas/ver-reservas.php');
            } catch (PDOException $error) {
                notify('No se pudo modificar la reserva.', 'error');
            }
        }

        $mes = substr($fechaSeleccionada, 0, 7);
    }

    $aulaSeleccionada = getAulaReservable($pdo, $aulaId);

    if (!$aulaSeleccionada) {
        notify('El aula de la reserva no existe o no tiene disponibilidad.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    }

    $diasCalendario = crearDiasCalendario($pdo, $aulaSeleccionada, $mes, $reservaId);
    $rangosLibres = getRangosLibres($pdo, $aulaSeleccionada, $fechaSeleccionada, $reservaId);
    $modoEdicion = true;
    $titulo = 'Editar Reserva | Reservá tu aula';
?>

<?php include __DIR__ . "/../../../components/header.php"; ?>

<main>
    <h1>Editar reserva</h1>

    <section>
        <h2>Aula: <?= htmlspecialchars($aulaSeleccionada['nombre']) ?></h2>
        <p>Elegí otra fecha o modificá el horario de tu reserva.</p>
    </section>

    <?php include __DIR__ . "/../../../components/calendario.php"; ?>

    <section id="horarios">
        <h2>Horarios disponibles del <?= htmlspecialchars($fechaSeleccionada) ?></h2>

        <?php if (count($rangosLibres) === 0): ?>
            <p>No quedan horarios libres para esta fecha.</p>
        <?php else: ?>
            <form method="post">
                <input type="hidden" name="reservation_id" value="<?= (int) $reservaId ?>">
                <input type="hidden" name="fecha" value="<?= htmlspecialchars($fechaSeleccionada) ?>">

                <label for="hora_inicio">Desde</label>
                <select id="hora_inicio" name="hora_inicio" required>
                    <?php foreach ($rangosLibres as $rango): ?>
                        <?php for ($minutos = horaAMinutos($rango['inicio']); $minutos < horaAMinutos($rango['fin']); $minutos += 60): ?>
                            <?php $hora = minutosAHora($minutos); ?>
                            <option value="<?= $hora ?>" <?= $hora === $horaInicioSeleccionada ? 'selected' : '' ?>>
                                <?= $hora ?>
                            </option>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                </select>

                <label for="hora_fin">Hasta</label>
                <select id="hora_fin" name="hora_fin" required>
                    <?php foreach ($rangosLibres as $rango): ?>
                        <?php for ($minutos = horaAMinutos($rango['inicio']) + 60; $minutos <= horaAMinutos($rango['fin']); $minutos += 60): ?>
                            <?php $hora = minutosAHora($minutos); ?>
                            <option value="<?= $hora ?>" <?= $hora === $horaFinSeleccionada ? 'selected' : '' ?>>
                                <?= $hora ?>
                            </option>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Guardar cambios</button>
            </form>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . "/../../../components/footer.php"; ?>
