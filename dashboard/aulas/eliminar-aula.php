<?php

    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";
    require_once __DIR__ . "/../../config/sendMailNotify.php";
    require_once __DIR__ . "/../../config/email/templates/message.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para eliminar aulas.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    }

    $id = $_GET['id'] ?? '';

    if (!is_numeric($id)) {
        notify('ID de aula invalido.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    }

     $sql = "SELECT classrooms.*, CASE WHEN reservations.fecha >= CURDATE() THEN 'reservada' ELSE 'disponible' END AS estado FROM classrooms LEFT JOIN reservations ON reservations.id = (SELECT MAX(r.id) FROM reservations r WHERE r.classroom_id = classrooms.id) WHERE classrooms.id = ?";
    $consulta = $pdo->prepare($sql);
    $consulta->execute([$id]);
    $aula = $consulta->fetch();

    if (!$aula) {
        notify('El aula no existe.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    };  

    if ($aula['estado'] === 'reservada') {
        notify('El aula esta reservada y no puede ser eliminada.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    };

    try {
        $sqlDelete = 'DELETE FROM classrooms WHERE id = ?';
        $borrarAula = $pdo->prepare($sqlDelete);
        $borrarAula->execute([$id]);

        sendMailNotify(
                ['nombre' => NAME_SISTEMA, 'email' => MAIL_USERNAME],
                "Gestión de aulas",
                notifyOperationAulaAdmin(
                    ['title' => '¡Se eliminó un aula en el Sistema!', 'subtitle' => 'Los datos del aula eliminada eran:'],
                    $aula['nombre'],
                    $aula['capacidad']
                    )
                );
    
        notify("Aula Eliminada.", 'success');
        redirect('/dashboard/aulas/ver-aulas.php');
    } catch (Exception $error) {
        die('Error de conexión a la base de datos: ' . $error->getMessage());
    }

?>