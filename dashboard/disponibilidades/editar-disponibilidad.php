<?php

    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para editar la disponibilidad de aulas.', 'error');
        redirect('/dashboard/');
    }

    $titulo = "Editar Disponibilidad de Aula | Reservá tu aula";

    $id = $_GET['id'] ?? '';

    if (!is_numeric($id)) {
        notify('ID de aula invalido.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    }

    $sql = 'SELECT * FROM classrooms JOIN classroom_schedules ON  classrooms.id = classroom_schedules.classroom_id WHERE classrooms.id = ?';
    $consulta = $pdo->prepare($sql);
    $consulta->execute([$id]);
    $aula = $consulta->fetch();

    if (!$aula) {
        notify('El aula no existe.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    };

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $dia_desde = htmlentities(addslashes(intval($_POST['dia_desde'])));
        $dia_hasta = htmlentities(addslashes(intval($_POST['dia_hasta'])));
        $hora_inicio = htmlentities(addslashes($_POST['hora_inicio']));
        $hora_fin = htmlentities(addslashes($_POST['hora_fin'])); 

        $error = validateIptusToCreateOrEditSchedules($dia_desde, $dia_hasta, $hora_inicio, $hora_fin);

        if ($error) {
            notify($error, "error");
        } else {
            try {
                $sql = 'UPDATE classroom_schedules SET dia_desde = ?, dia_hasta = ?, hora_inicio = ?, hora_fin = ? WHERE classroom_id = ?';
                $crearDisponibilidad = $pdo->prepare($sql);
                $crearDisponibilidad->execute([$dia_desde, $dia_hasta, $hora_inicio, $hora_fin, $aula['classroom_id']]);
    
                notify("Edicion de disponibilidad exitoso.", 'success');
                redirect('/dashboard/aulas/ver-aulas.php');
            } catch (Exception $error) {
                die('Error de conexión a la base de datos: ' . $error->getMessage());
            }
        }
    }
?>

<?php
    include __DIR__ . "/../../components/header.php";
?>

<main class="form-page">
    <article class="form-header">
        <h1>Editar Disponibilidad de <?php echo $aula['nombre'] ?></h1>
        <p>Modifica la disponibilidad del aula <?php echo $aula['nombre'] ?></p>
    </article>

    <form class="create-form" method="post">
        <div class="form-group">
            <label for="id">ID Aula</label>
            <input style="cursor: not-allowed;" type="text" name="id" id="id" readonly value="<?php echo $aula['classroom_id'] ?>">
        </div>
        <div class="form-group">
            <label for="nombre">Nombre Aula</label>
            <input style="cursor: not-allowed;" type="text" name="nombre" id="nombre" value="<?php echo $aula['nombre'] ?>">
        </div>
        <div class="form-group">
            <label for="capacidad">Desde Dia:</label>
            <select id="dia_desde" name="dia_desde" required>
                <?php for ($dia = 1; $dia <= 7; $dia++) { ?>
                    <option value="<?php echo $dia; ?>" <?php if ($dia === $aula['dia_desde']) {echo "selected";} ?> >
                        <?php echo getDayString($dia); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="created_at">Hasta dia:</label>
            <select id="dia_hasta" name="dia_hasta" required>
                <?php for ($dia = 1; $dia <= 7; $dia++) { ?>
                    <option value="<?php echo $dia; ?>" <?php if ($dia === $aula['dia_hasta']) {echo "selected";} ?> >
                        <?php echo getDayString($dia); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="hora_inicio">Desde horario:</label>
            <input type="time" name="hora_inicio" id="hora_inicio" value="<?php echo $aula['hora_inicio'] ?>" required>
        </div>
        <div class="form-group">
            <label for="hora_fin">Hasta horario:</label>
            <input type="time" name="hora_fin" id="hora_fin" value="<?php echo $aula['hora_fin'] ?>" required>
        </div>
        <div class="form-actions">
            <button class="btn-action" type="submit">Editar</button>
            <button class="btn-" popovertarget="eliminar-disponibilidad" type="button">Eliminar</button>
            <a class="btn-back" href ="../aulas/ver-aulas.php">Volver</a>
        </div>
    </form>
</main>

<section id="eliminar-disponibilidad" popover>
    <p>¿estas seguro que queres eliminar la disponibilidad del <?php echo $aula['nombre'] ?> del sistema?</p>
    <a href="<?php echo BASE_URL; ?>/dashboard/disponibilidades/eliminar-disponibilidad.php?id=<?php echo $aula['classroom_id']; ?>">Eliminar</a>
    <button type="button" popovertarget="eliminar-disponibilidad" popovertargetaction="hide">Cancelar</button>
</section>

<?php
    include __DIR__ . "/../../components/footer.php";
?>