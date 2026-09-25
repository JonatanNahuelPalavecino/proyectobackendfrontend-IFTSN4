<?php
    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/validations.php";
    require_once __DIR__ . "/../../../config/db.php";

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

    $titulo = "Reservar Aula | Reservá tu aula";

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
    <h1>Reservar Aula</h1>

    <section>
        <h2>Elegí un aula</h2>

        <?php if (count($aulas) === 0): ?>
            <p>No hay aulas con disponibilidad configurada.</p>
        <?php else: ?>

            <?php foreach ($aulas as $aula): ?>
                <article>
                    <h3><?php echo htmlspecialchars($aula['nombre']); ?></h3>

                    <p>
                        Capacidad:
                        <?php echo htmlspecialchars($aula['capacidad']); ?>
                    </p>

                    <p>
                        Horario:
                        <?php echo htmlspecialchars(substr($aula['hora_inicio'], 0, 5)); ?>
                        -
                        <?php echo htmlspecialchars(substr($aula['hora_fin'], 0, 5)); ?>
                    </p>

                <a href="<?php echo BASE_URL; ?>/dashboard/reservas/aulas/crear-reserva.php?aula=<?php echo $aula['id']; ?>&mes=<?php echo date('Y-m'); ?>#calendario">
                        Ver calendario
                    </a>
                </article>
            <?php endforeach; ?>

        <?php endif; ?>
    </section>


    <?php if ($aulaSeleccionada): ?>
        <?php include __DIR__ . "/../../../components/calendario.php"; ?>
    <?php endif; ?>


    <?php if ($aulaSeleccionada && $fechaSeleccionada): ?>

        <section id="horarios">
            <h2>
                Horarios disponibles del
                <?php echo htmlspecialchars($fechaSeleccionada); ?>
            </h2>

            <?php if (count($rangosLibres) === 0): ?>

                <p>No quedan horarios libres para esta fecha.</p>

            <?php else: ?>

                <?php foreach ($rangosLibres as $rango): ?>

                    <article>
                        <h3>
                            <?php echo htmlspecialchars($rango['inicio']); ?>
                            -
                            <?php echo htmlspecialchars($rango['fin']); ?>
                        </h3>

                        <form method="post">
                            <input
                                type="hidden"
                                name="classroom_id"
                                value="<?php echo $aulaSeleccionada['id']; ?>"
                            >

                            <input
                                type="hidden"
                                name="fecha"
                                value="<?php echo htmlspecialchars($fechaSeleccionada); ?>"
                            >

                            <label>Desde</label>

                            <input
                                type="time"
                                name="hora_inicio"
                                min="<?php echo htmlspecialchars($rango['inicio']); ?>"
                                max="<?php echo htmlspecialchars($rango['fin']); ?>"
                                step="3600"
                                value="<?php echo htmlspecialchars($rango['inicio']); ?>"
                                required
                            >

                            <label>Hasta</label>

                            <input
                                type="time"
                                name="hora_fin"
                                min="<?php echo htmlspecialchars($rango['inicio']); ?>"
                                max="<?php echo htmlspecialchars($rango['fin']); ?>"
                                step="3600"
                                value="<?php echo htmlspecialchars($rango['fin']); ?>"
                                required
                            >

                            <button type="submit">
                                Reservar
                            </button>
                        </form>
                    </article>

                <?php endforeach; ?>

            <?php endif; ?>
        </section>

    <?php endif; ?>
</main>

<?php include __DIR__ . "/../../../components/footer.php"; ?>
