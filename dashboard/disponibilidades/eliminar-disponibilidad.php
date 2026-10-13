<?php

    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";
    require_once __DIR__ . "/../../config/sendMailNotify.php";
    require_once __DIR__ . "/../../config/email/templates/message.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para eliminar la disponibilidad de aulas.', 'error');
        redirect('/dashboard/');
    }


    $id = $_GET['id'] ?? '';

    if (!is_numeric($id)) {
        notify('ID de aula invalido.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    }

    $sql = 'SELECT * FROM classroom_schedules WHERE classroom_id = ?';
    $consulta = $pdo->prepare($sql);
    $consulta->execute([$id]);
    $disponibilidad = $consulta->fetch();

    if (!$disponibilidad) {
        notify('No existe disponibilidad en esta aula.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    };

    try {
        $sqlDelete = 'DELETE FROM classroom_schedules WHERE classroom_id = ?';
        $borrarDisponiblidad = $pdo->prepare($sqlDelete);
        $borrarDisponiblidad->execute([$id]);

        sendMailNotify(
            ['nombre' => 'Sistema de Reservas', 'email' => 'antruxxi@gmail.com'],
            "Gestion de disponibilidad de aulas",
            notifyOperationDisponibilidadAulaAdmin(
                ['title' => 'Disponibilidad de aula eliminada', 'subtitle' => 'Los datos de la disponibilidad de aula eliminada eran:'],
                getClassroomById($pdo, $id)['nombre'],
                getClassroomById($pdo, $id)['capacidad'],
                getDayString($disponibilidad['dia_desde']),
                getDayString($disponibilidad['dia_hasta']),
                $disponibilidad['hora_inicio'],
                $disponibilidad['hora_fin']
                )
            );
        
        notify("Disponiblidad de Aula Eliminada.", 'success');
        redirect('/dashboard/aulas/ver-aulas.php');
    } catch (Exception $error) {
        die('Error de conexión a la base de datos: ' . $error->getMessage());
    }
?>