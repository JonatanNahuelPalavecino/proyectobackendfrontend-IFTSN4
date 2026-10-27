<?php
require_once __DIR__ . "/../../../config/functions.php";
require_once __DIR__ . "/../../../config/validations.php";
require_once __DIR__ . "/../../../config/db.php";
require_once __DIR__ . "/../../../config/sendMailNotify.php";
require_once __DIR__ . "/../../../config/email/templates/message.php";

$usuario = getUser();

if (!$usuario || $usuario['rol'] !== 'user') {
    notify('No tenés permisos para reservar aulas.', 'error');
    redirect('/dashboard/');
}

$aulaId = $_GET['aula'] ?? null;
$fechaSeleccionada = $_GET['fecha'] ?? null;
$mes = $_GET['mes'] ?? date('Y-m');

$aulas = getAulasReservables($pdo);

$aulaSeleccionada = null;
$diasCalendario = [];
$rangosLibres = [];

if ($aulaId) {
    $aulaSeleccionada = getAulaReservable($pdo, $aulaId);
}

if ($aulaSeleccionada) {
    $diasCalendario = crearDiasCalendario(
        $pdo,
        $aulaSeleccionada,
        $mes
    );
}

if ($aulaSeleccionada && $fechaSeleccionada) {
    $rangosLibres = getRangosLibres(
        $pdo,
        $aulaSeleccionada,
        $fechaSeleccionada
    );
}

$titulo = "Reserva de Aula | SIRAUCA - Sistema de Reserva de Aulas y Carros";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $aulaId = $_POST['classroom_id'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $horaInicio = $_POST['hora_inicio'] ?? null;
    $horaFin = $_POST['hora_fin'] ?? null;

    $error = validateInputsCreateOrEditReservations($pdo, $aulaId, $fecha, $horaInicio, $horaFin);

    if ($error) {
        notify($error, 'error');
    } else {
        try {
            $sql = 'INSERT INTO reservations (user_id, classroom_id, fecha, hora_inicio, hora_fin) VALUES (?, ?, ?, ?, ?)';
            $crearReserva = $pdo->prepare($sql);
            $crearReserva->execute([$usuario['id'], $aulaId, $fecha, $horaInicio, $horaFin]);

            //ADMIN
            sendMailNotify(
                ['nombre' => NAME_SISTEMA, 'email' => MAIL_USERNAME],
                "Gestion de Reservas de Aula",
                notifyOperationReservAulaAdmin(
                    ['title' => '¡Se registro una nueva reserva de aula!', 'subtitle' => 'Los datos de la reserva son:'],
                    $usuario['nombre'],
                    $aulaSeleccionada['nombre'],
                    $fecha,
                    $horaInicio,
                    $horaFin
                )
            );

            // PROFESOR
            sendMailNotify(
                ['nombre' => $usuario['nombre'], 'email' => $usuario['email']],
                "Sistema Reservas IFTS N°4",
                notifyOperationReservAulaProf(
                    ['title' => '¡Reserva de aula creada exitosamente!', 'subtitle' => 'Los datos de tu reserva son:'],
                    $aulaSeleccionada['nombre'],
                    $fecha,
                    $horaInicio,
                    $horaFin
                )
            );

            notify("Creacion de reserva exitoso.", 'success');
            redirect('/dashboard/');
        } catch (Exception $error) {
            die('Error de conexión a la base de datos: ' . $error->getMessage());
        }
    }
}

?>

<?php include __DIR__ . "/../../../components/header.php"; ?>

<main>
    <section style="padding: 20px 10px;">
        <article class="vr-containerInfo">
            <span class="vr-emojiAula">🏛️</span>

            <div>
                <h2 class="vr-title">Reserva de Aula</h2>
                <p class="vr-description">Aca podras reservar el aula que necesites</p>
            </div>

            <span class="vr-divisor"></span>

            <span class="vr-logo">PROFESOR</span>

        </article>

    </section>

    <section class="dashboard-reservas">

        <?php if (count($aulas) === 0): ?>
            <article class="no-results no-results-aulas">
                <p class="text-sinResultsAulas">No hay aulas con disponibilidad configurada.</p>
            </article>
        <?php else: ?>
            <div class="dashboard-reservas-container">
                <article class="db-reservas-header">
                    <h2 class="db-reservas-title">Selecciona un aula</h2>
                </article>

                <div class="db-reservas-cards">
                    <?php foreach ($aulas as $aula): ?>
                        <article class="db-reservas-item">
                            <div class="db-reserva-iconAula">
                                <svg class="db-reserva-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z" />
                                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z" />
                                </svg>
                            </div>

                            <div class="db-reservas-body">
                                <div class="db-reserva-inline">
                                    <h3 class="db-reserva-subtitle"><?= htmlspecialchars($aula['nombre']) ?></h3>
                                    <span class="db-reserva-chipActiva"> Capacidad: <?= htmlspecialchars($aula['capacidad']) ?> personas</span>
                                </div>
                                <div>
                                    <p class="db-reserva-fechaAula">Horario: <span><?php echo htmlspecialchars(substr($aula['hora_inicio'], 0, 5)); ?> hs - <?php echo htmlspecialchars(substr($aula['hora_fin'], 0, 5)); ?> hs</span></p>
                                </div>

                                <div>
                                    <a class="btn btn-gestion-carts" href="<?php echo BASE_URL; ?>/dashboard/reservas/aulas/crear-reserva.php?aula=<?php echo $aula['id']; ?>&mes=<?php echo date('Y-m'); ?>#calendario">
                                        Ver calendario
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>


    <?php if ($aulaSeleccionada): ?>
        <?php include __DIR__ . "/../../../components/calendario.php"; ?>
    <?php endif; ?>


    <?php if ($aulaSeleccionada && $fechaSeleccionada): ?>

        <section class="dashboard-reservas">

            <article class="dashboard-reservas-container" id="horarios">
                <h2 class="vr-title">
                    Horarios disponibles del
                    <?php echo formatDate(htmlspecialchars($fechaSeleccionada)); ?>
                </h2>

                <?php if (count($rangosLibres) === 0): ?>

                    <p>No quedan horarios libres para esta fecha.</p>

                <?php else: ?>

                    <?php foreach ($rangosLibres as $rango): ?>

                        <article class="horario-contenedor">
                            <h3 class="horario-titulo">
                                <?php echo htmlspecialchars($rango['inicio']); ?> hs
                                -
                                <?php echo htmlspecialchars($rango['fin']); ?>
                                hs
                            </h3>

                            <form class="horario-form" method="post">
                                <input
                                    type="hidden"
                                    name="classroom_id"
                                    value="<?php echo $aulaSeleccionada['id']; ?>">

                                <input
                                    type="hidden"
                                    name="fecha"
                                    value="<?php echo htmlspecialchars($fechaSeleccionada); ?>">

                                <label class="horario-label">Desde</label>

                                <input
                                    class="horario-input"
                                    type="time"
                                    name="hora_inicio"
                                    min="<?php echo htmlspecialchars($rango['inicio']); ?>"
                                    max="<?php echo htmlspecialchars($rango['fin']); ?>"
                                    step="3600"
                                    value="<?php echo htmlspecialchars($rango['inicio']); ?>"
                                    required>

                                <label class="horario-label">Hasta</label>

                                <input
                                    class="horario-input"
                                    type="time"
                                    name="hora_fin"
                                    min="<?php echo htmlspecialchars($rango['inicio']); ?>"
                                    max="<?php echo htmlspecialchars($rango['fin']); ?>"
                                    step="3600"
                                    value="<?php echo htmlspecialchars($rango['fin']); ?>"
                                    required>

                                <button class="horario-button" type="submit">
                                    Reservar
                                </button>
                            </form>
                        </article>

                    <?php endforeach; ?>

                <?php endif; ?>
            </article>
        </section>

    <?php endif; ?>
</main>

<?php include __DIR__ . "/../../../components/footer.php"; ?>