<?php
    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/validations.php";
    require_once __DIR__ . "/../../../config/db.php";
    require_once __DIR__ . "/../../../config/sendMailNotify.php";
    require_once __DIR__ . "/../../../config/email/templates/message.php";

    $usuario = getUser();

    if (!$usuario || !in_array($usuario['rol'], ['user', 'admin'], true)) {
        notify('No tenés permisos para editar reservas.', 'error');
        redirect('/dashboard/');
    }

    $reservaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reservaId = filter_input(INPUT_POST, 'reservation_id', FILTER_VALIDATE_INT);
    }

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

    if ($reserva['fecha'] < date('Y-m-d')) {
        notify("No podés editar una reserva que ya fue cumplida.", "error");
        redirect("/dashboard/reservas/aulas/ver-reservas.php");
    }

    $aulas = getAulasReservables($pdo);

    $aulaId = isset($_GET['aula'])
        ? (int) $_GET['aula']
        : (int) $reserva['classroom_id'];

    $cambioAula = isset($_GET['aula'])
        && (int) $_GET['aula'] !== (int) $reserva['classroom_id'];

    $mes = $_GET['mes'] ?? substr($reserva['fecha'], 0, 7);

    if (isset($_GET['fecha'])) {
        $fechaSeleccionada = $_GET['fecha'];
    } elseif ($cambioAula) {
        $fechaSeleccionada = null;
    } else {
        $fechaSeleccionada = $reserva['fecha'];
    }

    $horaInicioSeleccionada = $reserva['hora_inicio'];
    $horaFinSeleccionada = $reserva['hora_fin'];
    $modoEdicion = true;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reserva = getReservaById($pdo, $reservaId);

        if (!$reserva || ($usuario['rol'] !== 'admin' && (int) $reserva['user_id'] !== (int) $usuario['id'])) {
            notify('No tenés permisos para editar esta reserva.', 'error');
            redirect('/dashboard/reservas/aulas/ver-reservas.php');
        }

        $aulaId = filter_input(INPUT_POST, 'classroom_id', FILTER_VALIDATE_INT);
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
                        SET classroom_id = :classroom_id,
                            fecha = :fecha,
                            hora_inicio = :hora_inicio,
                            hora_fin = :hora_fin
                        WHERE id = :id_reserva';

                $editarReserva = $pdo->prepare($sql);

                $editarReserva->execute([
                    'classroom_id' => $aulaId,
                    'fecha' => $fechaSeleccionada,
                    'hora_inicio' => $horaInicioSeleccionada,
                    'hora_fin' => $horaFinSeleccionada,
                    'id_reserva' => $reservaId
                ]);

                $aulaActualizada = getAulaReservable($pdo, $aulaId);

                sendMailNotify(
                    ['nombre' => NAME_SISTEMA, 'email' => MAIL_USERNAME],
                    'Gestión de Reservas de Aula',
                    notifyOperationReservAulaAdmin(
                        [
                            'title' => '¡Se modificó una reserva de aula!',
                            'subtitle' => 'Los datos de la nueva reserva son:'
                        ],
                        $usuario['nombre'],
                        $aulaActualizada['nombre'],
                        $fechaSeleccionada,
                        $horaInicioSeleccionada,
                        $horaFinSeleccionada
                    )
                );

                sendMailNotify(
                    ['nombre' => $usuario['nombre'], 'email' => $usuario['email']],
                    'Sistema Reservas IFTS N°4',
                    notifyOperationReservAulaProf(
                        [
                            'title' => '¡Modificaste tu reserva!',
                            'subtitle' => 'Los datos de tu nueva reserva son:'
                        ],
                        $aulaActualizada['nombre'],
                        $fechaSeleccionada,
                        $horaInicioSeleccionada,
                        $horaFinSeleccionada
                    )
                );

                notify('Reserva modificada correctamente.', 'success');
                redirect('/dashboard/reservas/aulas/ver-reservas.php');

            } catch (PDOException $error) {
                notify('No se pudo modificar la reserva.', 'error');
            }
        }

        if ($fechaSeleccionada) {
            $mes = substr($fechaSeleccionada, 0, 7);
        }
    }

    $aulaSeleccionada = getAulaReservable($pdo, $aulaId);

    if (!$aulaSeleccionada) {
        notify('El aula seleccionada no existe o no tiene disponibilidad.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    }

    $diasCalendario = crearDiasCalendario(
        $pdo,
        $aulaSeleccionada,
        $mes,
        $reservaId
    );

    $rangosLibres = [];

    if ($fechaSeleccionada) {
        $rangosLibres = getRangosLibres(
            $pdo,
            $aulaSeleccionada,
            $fechaSeleccionada,
            $reservaId
        );
    }

    $titulo = 'Modifica tu reserva de Aula | SIRAUCA - Sistema de Reserva de Aulas y Carros';
?>

<?php include __DIR__ . "/../../../components/header.php"; ?>

<main>
    <section style="padding: 20px 10px;">
        <article class="vr-containerInfo">
            <span class="vr-emojiAula">🏛️</span>

            <div>
                <h2 class="vr-title">Edición de reserva de Aula</h2>
                <p class="vr-description">Acá podrás editar la reserva del aula que necesites</p>
            </div>

            <span class="vr-divisor"></span>

            <span class="vr-logo">PROFESOR</span>
        </article>
    </section>

    <section class="dashboard-reservas">
        <?php if (count($aulas) === 0): ?>

            <article class="no-results no-results-aulas">
                <p class="text-sinResultsAulas">
                    No hay aulas con disponibilidad configurada.
                </p>
            </article>

        <?php else: ?>

            <div class="dashboard-reservas-container">

                <article class="db-reservas-header">
                    <h2 class="db-reservas-title">Selecciona un aula</h2>
                </article>

                <div class="db-reservas-cards">

                    <?php foreach ($aulas as $aula): ?>

                        <?php
                            $aulaEsSeleccionada =
                                (int) $aula['id'] === (int) $aulaId;
                        ?>

                        <article class="db-reservas-item <?= $aulaEsSeleccionada ? 'aula-seleccionada' : '' ?>">

                            <div class="db-reserva-iconAula">
                                <svg
                                    class="db-reserva-icon"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor"
                                    viewBox="0 0 16 16"
                                >
                                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z" />
                                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z" />
                                </svg>
                            </div>

                            <div class="db-reservas-body">

                                <div class="db-reserva-inline">

                                    <h3 class="db-reserva-subtitle">
                                        <?= htmlspecialchars($aula['nombre']) ?>
                                    </h3>

                                    <span class="db-reserva-chipActiva">
                                        Capacidad:
                                        <?= htmlspecialchars($aula['capacidad']) ?>
                                        personas
                                    </span>

                                </div>

                                <div>
                                    <p class="db-reserva-fechaAula">
                                        Horario:
                                        <span>
                                            <?= htmlspecialchars(substr($aula['hora_inicio'], 0, 5)) ?> hs -
                                            <?= htmlspecialchars(substr($aula['hora_fin'], 0, 5)) ?> hs
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <a
                                        class="btn btn-gestion-carts"
                                        href="<?= BASE_URL ?>/dashboard/reservas/aulas/editar-reserva.php?id=<?= $reservaId ?>&aula=<?= $aula['id'] ?>&mes=<?= date('Y-m') ?>#calendario"
                                    >
                                        <?= $aulaEsSeleccionada ? 'Aula seleccionada' : 'Ver calendario' ?>
                                    </a>
                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php endif; ?>

    </section>

    <?php include __DIR__ . "/../../../components/calendario.php"; ?>

    <?php if ($fechaSeleccionada): ?>

        <section class="dashboard-reservas">

            <article class="dashboard-reservas-container" id="horarios">

                <article>
                    <h2 class="vr-title">
                        Horarios disponibles del
                        <?= htmlspecialchars(formatDate($fechaSeleccionada)) ?>
                    </h2>
                </article>

                <?php if (count($rangosLibres) === 0): ?>

                    <article class="no-results no-results-aulas">
                        <p class="text-sinResultsAulas">
                            No quedan horarios libres para esta fecha.
                        </p>
                    </article>

                <?php else: ?>

                    <article class="horario-contenedor">

                        <form class="horario-form" method="post">

                            <input
                                type="hidden"
                                name="reservation_id"
                                value="<?= (int) $reservaId ?>"
                            >

                            <input
                                type="hidden"
                                name="classroom_id"
                                value="<?= (int) $aulaId ?>"
                            >

                            <input
                                type="hidden"
                                name="fecha"
                                value="<?= htmlspecialchars($fechaSeleccionada) ?>"
                            >

                            <label for="hora_inicio">Desde</label>

                            <select
                                class="horario-input"
                                id="hora_inicio"
                                name="hora_inicio"
                                required
                            >

                                <?php foreach ($rangosLibres as $rango): ?>

                                    <?php for (
                                        $minutos = horaAMinutos($rango['inicio']);
                                        $minutos < horaAMinutos($rango['fin']);
                                        $minutos += 60
                                    ): ?>

                                        <?php $hora = minutosAHora($minutos); ?>

                                        <option
                                            value="<?= $hora ?>"
                                            <?= $hora === substr($horaInicioSeleccionada, 0, 5)
                                                ? 'selected'
                                                : ''
                                            ?>
                                        >
                                            <?= $hora ?>
                                        </option>

                                    <?php endfor; ?>

                                <?php endforeach; ?>

                            </select>

                            <label for="hora_fin">Hasta</label>

                            <select
                                class="horario-input"
                                id="hora_fin"
                                name="hora_fin"
                                required
                            >

                                <?php foreach ($rangosLibres as $rango): ?>

                                    <?php for (
                                        $minutos = horaAMinutos($rango['inicio']) + 60;
                                        $minutos <= horaAMinutos($rango['fin']);
                                        $minutos += 60
                                    ): ?>

                                        <?php $hora = minutosAHora($minutos); ?>

                                        <option
                                            value="<?= $hora ?>"
                                            <?= $hora === substr($horaFinSeleccionada, 0, 5)
                                                ? 'selected'
                                                : ''
                                            ?>
                                        >
                                            <?= $hora ?>
                                        </option>

                                    <?php endfor; ?>

                                <?php endforeach; ?>

                            </select>

                            <button class="btn btn-gestion-aulas" type="submit">
                                Modificar
                            </button>

                        </form>

                    </article>

                <?php endif; ?>

            </article>

        </section>

    <?php endif; ?>

</main>

<?php include __DIR__ . "/../../../components/footer.php"; ?>