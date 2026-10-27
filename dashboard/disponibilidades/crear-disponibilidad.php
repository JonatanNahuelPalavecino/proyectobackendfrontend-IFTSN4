<?php

    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";
    require_once __DIR__ . "/../../config/sendMailNotify.php";
    require_once __DIR__ . "/../../config/email/templates/message.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para crear la disponibilidad de aulas.', 'error');
        redirect('/dashboard/');
    }

    $titulo = "Crear Disponibilidad de Aula | Reservá tu aula";

    $id = $_GET['id'] ?? '';

    if (!is_numeric($id)) {
        notify('ID de aula invalido.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    }

    $sql = 'SELECT * FROM classrooms WHERE id = ?';
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
                $sql = 'INSERT INTO classroom_schedules (classroom_id, dia_desde, dia_hasta, hora_inicio, hora_fin) VALUES (?, ?, ?, ?, ?)' ;
                $crearDisponibilidad = $pdo->prepare($sql);
                $crearDisponibilidad->execute([$id, $dia_desde, $dia_hasta, $hora_inicio, $hora_fin]);


                sendMailNotify(
                    ['nombre' => NAME_SISTEMA, 'email' => MAIL_USERNAME],
                    "Gestion de Disponibilidad de Aulas",
                    notifyOperationDisponibilidadAulaAdmin(
                        ['title' => 'Nueva disponibilidad de aula creada', 'subtitle' => 'Los datos de la nueva     disponibilidad de aula son:'],
                        $aula['nombre'],
                        $aula['capacidad'],
                        getDayString($dia_desde),
                        getDayString($dia_hasta),
                        $hora_inicio,
                        $hora_fin
                    )

                );
    
                notify("Creacion de disponibilidad exitoso.", 'success');
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
    <div class="form-header">
        <span class="form-crudChip form-chipAulas">Crear Disponibilidad</span>
        <p class="dh-welcome-label">Seleccione los datos requeridos para la disponibilidad</p>
    </div>

    <form class="create-form" method="post">
        <div class="form-group">
            <label for="id">ID Aula</label>
            <input style="cursor: not-allowed;" type="text" name="id" id="id" readonly value="<?php echo $aula['id'] ?>">
        </div>
        <div class="form-group">
            <label for="nombre">Nombre Aula</label>
            <input style="cursor: not-allowed;" type="text" name="nombre" id="nombre" value="<?php echo $aula['nombre'] ?>">
        </div>
        <div class="form-group">
            <label for="capacidad">Dia <span style="font-style: italic;">(Inicio)</span>:</label>
            <select id="dia_desde" name="dia_desde" required>
                <option value="">Seleccionar</option>
                <?php for ($dia = 1; $dia <= 7; $dia++) { ?>
                    <option value="<?php echo $dia; ?>">
                        <?php echo getDayString($dia); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="created_at">Dia <span style="font-style: italic;">(Fin)</span>:</label>
            <select id="dia_hasta" name="dia_hasta" required>
                <option value="">Seleccionar</option>
                <?php for ($dia = 1; $dia <= 7; $dia++) { ?>
                    <option value="<?php echo $dia; ?>">
                        <?php echo getDayString($dia); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="hora_inicio">Horario <span style="font-style: italic;">(Inicio)</span>:</label>
            <input type="time" name="hora_inicio" id="hora_inicio" required>
        </div>
        <div class="form-group">
            <label for="hora_fin">Horario <span style="font-style: italic;">(Fin)</span>:</label>
            <input type="time" name="hora_fin" id="hora_fin" required>
        </div>

        <div class="form-actions">
            <button class="btn-action" type="submit">Crear Disponibilidad</button>
        </div>
    </form>
</main>

<?php
    include __DIR__ . "/../../components/footer.php";
?>