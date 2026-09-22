<?php
require_once __DIR__ . "/../../config/functions.php";
require_once __DIR__ . "/../../config/validations.php";
require_once __DIR__ . "/../../config/db.php";

$usuario = getUser();

if (!$usuario || $usuario['rol'] !== 'user') {
    notify('No tenés permisos para crear reservas de aulas.', 'error');
    redirect('/dashboard/');
}

$aulas = getAllClassroomsAndSchedules($pdo);
$aulaId = filter_input(INPUT_GET, 'aula', FILTER_VALIDATE_INT) ?: null;
$fechaSeleccionada = $_GET['fecha'] ?? '';
$mes = $_GET['mes'] ?? date('Y-m');
$aulaSeleccionada = $aulaId ? getClassroomAndSchedule($pdo, $aulaId) : null;

if (!preg_match('/^\d{4}-\d{2}$/', $mes) || !validDateFormat($mes . '-01')) {
    $mes = date('Y-m');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aulaIdPost = filter_input(INPUT_POST, 'classroom_id', FILTER_VALIDATE_INT);
    $fecha = $_POST['fecha'] ?? '';
    $horaInicio = $_POST['hora_inicio'] ?? '';
    $horaFin = $_POST['hora_fin'] ?? '';
    $regla = $aulaIdPost ? getClassroomAndSchedule($pdo, $aulaIdPost) : null;

    if (!$regla || !validDateFormat($fecha) || !preg_match('/^\d{2}:\d{2}$/', $horaInicio) || !preg_match('/^\d{2}:\d{2}$/', $horaFin)) {
        notify('Los datos de la reserva no son válidos.', 'error');
    } elseif ($fecha < date('Y-m-d')) {
        notify('No podés reservar una fecha anterior a hoy.', 'error');
    } elseif (!dateIsAllowed($fecha, $regla)) {
        notify('Ese día no está habilitado para esta aula.', 'error');
    } else {
        $reservas = getReservationsByMonth($pdo, $aulaIdPost, substr($fecha, 0, 7));
        $rangosLibres = getAvailableRanges($regla, $reservas[$fecha] ?? []);

        if (!rangeIsAvailable($rangosLibres, $horaInicio, $horaFin)) {
            notify('Ese horario ya está ocupado o está fuera de la disponibilidad.', 'error');
        } else {
            // Se vuelve a comprobar el solapamiento justo antes del INSERT.
            // Esto evita aceptar una reserva creada por otra persona mientras
            // el usuario tenía abierto el calendario.
            $sql = "SELECT id FROM reservations
                    WHERE classroom_id = :classroom_id
                    AND fecha = :fecha
                    AND hora_inicio < :hora_fin
                    AND hora_fin > :hora_inicio";
            $consulta = $pdo->prepare($sql);
            $consulta->execute([
                'classroom_id' => $aulaIdPost,
                'fecha' => $fecha,
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin
            ]);

            if ($consulta->fetch()) {
                notify('Ese horario acaba de ser reservado por otra persona.', 'error');
            } else {
                $sql = "INSERT INTO reservations
                        (user_id, classroom_id, fecha, hora_inicio, hora_fin)
                        VALUES (:user_id, :classroom_id, :fecha, :hora_inicio, :hora_fin)";
                $consulta = $pdo->prepare($sql);
                $consulta->execute([
                    'user_id' => $usuario['id'],
                    'classroom_id' => $aulaIdPost,
                    'fecha' => $fecha,
                    'hora_inicio' => $horaInicio,
                    'hora_fin' => $horaFin
                ]);
                notify('Reserva creada correctamente.', 'success');
            }
        }
    }

    $mesRedireccion = validDateFormat($fecha) ? substr($fecha, 0, 7) : date('Y-m');
    redirect('/dashboard/aulas/crear-reserva.php?aula=' . (int) $aulaIdPost . '&fecha=' . urlencode($fecha) . '&mes=' . $mesRedireccion);
}

// Se consulta una sola vez el mes elegido y se reutiliza para todos los días.
$reservasPorFecha = $aulaSeleccionada
    ? getReservationsByMonth($pdo, $aulaSeleccionada['id'], $mes)
    : [];

$fechaValida = $aulaSeleccionada
    && validDateFormat($fechaSeleccionada)
    && $fechaSeleccionada >= date('Y-m-d')
    && dateIsAllowed($fechaSeleccionada, $aulaSeleccionada);

$rangosFechaSeleccionada = $fechaValida
    ? getAvailableRanges($aulaSeleccionada, $reservasPorFecha[$fechaSeleccionada] ?? [])
    : [];

$primerDiaMes = $mes . '-01';
$cantidadDias = (int) date('t', strtotime($primerDiaMes));
$diaSemanaPrimerDia = (int) date('N', strtotime($primerDiaMes));
$mesAnterior = date('Y-m', strtotime($primerDiaMes . ' -1 month'));
$mesSiguiente = date('Y-m', strtotime($primerDiaMes . ' +1 month'));
$numeroMes = (int) date('n', strtotime($primerDiaMes));
$anio = (int) date('Y', strtotime($primerDiaMes));
$nombresMeses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];

$titulo = 'Reservar aula | Reservá tu aula';
include __DIR__ . "/../../components/header.php";
?>

<main class="reservation-page">
    <h1>Reservar un aula</h1>

    <section class="reservation-classrooms">
        <?php foreach ($aulas as $aula): ?>
            <article>
                <h2><?= htmlspecialchars($aula['nombre']) ?></h2>
                <p>Capacidad: <?= (int) $aula['capacidad'] ?></p>
                <p>Disponible de <?= getDayString($aula['dia_desde']) ?> a <?= getDayString($aula['dia_hasta']) ?></p>
                <p><?= substr($aula['hora_inicio'], 0, 5) ?> a <?= substr($aula['hora_fin'], 0, 5) ?></p>
                <a href="<?= BASE_URL ?>/dashboard/aulas/crear-reserva.php?aula=<?= (int) $aula['id'] ?>&mes=<?= $mes ?>">Consultar disponibilidad</a>
            </article>
        <?php endforeach; ?>
    </section>

    <?php if ($aulaSeleccionada): ?>
        <section class="reservation-calendar">
            <h2>Calendario de <?= htmlspecialchars($aulaSeleccionada['nombre']) ?></h2>
            <div class="calendar-navigation">
                <?php if ($mesAnterior >= date('Y-m')): ?>
                    <a href="<?= BASE_URL ?>/dashboard/aulas/crear-reserva.php?aula=<?= (int) $aulaId ?>&mes=<?= $mesAnterior ?>">← Mes anterior</a>
                <?php else: ?><span></span><?php endif; ?>
                <strong><?= $nombresMeses[$numeroMes] ?> <?= $anio ?></strong>
                <a href="<?= BASE_URL ?>/dashboard/aulas/crear-reserva.php?aula=<?= (int) $aulaId ?>&mes=<?= $mesSiguiente ?>">Mes siguiente →</a>
            </div>

            <table class="calendar-table">
                <thead><tr><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th></tr></thead>
                <tbody><tr>
                    <?php for ($vacio = 1; $vacio < $diaSemanaPrimerDia; $vacio++): ?>
                        <td class="calendar-day empty"></td>
                    <?php endfor; ?>
                    <?php for ($dia = 1; $dia <= $cantidadDias; $dia++): ?>
                        <?php
                        $fecha = sprintf('%04d-%02d-%02d', $anio, $numeroMes, $dia);
                        $esPasado = $fecha < date('Y-m-d');
                        $habilitado = !$esPasado && dateIsAllowed($fecha, $aulaSeleccionada);
                        $rangos = $habilitado ? getAvailableRanges($aulaSeleccionada, $reservasPorFecha[$fecha] ?? []) : [];
                        $disponible = count($rangos) > 0;
                        ?>
                        <td class="calendar-day <?= $disponible ? 'available' : 'disabled' ?>">
                            <?php if ($disponible): ?>
                                <a href="<?= BASE_URL ?>/dashboard/aulas/crear-reserva.php?aula=<?= (int) $aulaId ?>&fecha=<?= $fecha ?>&mes=<?= $mes ?>">
                                    <span><?= $dia ?></span><small>Disponible</small>
                                </a>
                            <?php else: ?>
                                <span><?= $dia ?></span>
                                <small><?= $esPasado ? 'No disponible' : ($habilitado ? 'Completo' : 'No habilitado') ?></small>
                            <?php endif; ?>
                        </td>
                        <?php if (($dia + $diaSemanaPrimerDia - 1) % 7 === 0 && $dia < $cantidadDias): ?></tr><tr><?php endif; ?>
                    <?php endfor; ?>
                </tr></tbody>
            </table>
        </section>
    <?php endif; ?>

    <?php if ($fechaValida): ?>
        <section class="reservation-slots">
            <h2>Horarios disponibles del <?= htmlspecialchars($fechaSeleccionada) ?></h2>
            <?php foreach ($rangosFechaSeleccionada as $rango): ?>
                <form method="post">
                    <input type="hidden" name="classroom_id" value="<?= (int) $aulaSeleccionada['id'] ?>">
                    <input type="hidden" name="fecha" value="<?= htmlspecialchars($fechaSeleccionada) ?>">
                    <label>Desde <input type="time" name="hora_inicio" min="<?= $rango['inicio'] ?>" max="<?= $rango['fin'] ?>" step="1800" value="<?= $rango['inicio'] ?>" required></label>
                    <label>Hasta <input type="time" name="hora_fin" min="<?= $rango['inicio'] ?>" max="<?= $rango['fin'] ?>" step="1800" value="<?= $rango['fin'] ?>" required></label>
                    <button type="submit">Reservar</button>
                </form>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<?php include __DIR__ . "/../../components/footer.php"; ?>
