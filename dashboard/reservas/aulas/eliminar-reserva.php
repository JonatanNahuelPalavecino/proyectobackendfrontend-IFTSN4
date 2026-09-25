<?php

    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/validations.php";
    require_once __DIR__ . "/../../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'user') {
        notify('No tenés permisos para eliminar una reserva.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    }

    $id = $_GET['id'] ?? '';

    if (!is_numeric($id)) {
        notify('ID de aula invalido.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    }

    $sql = 'SELECT * FROM reservations WHERE id = ?';
    $consulta = $pdo->prepare($sql);
    $consulta->execute([$id]);
    $reserva = $consulta->fetch();

    if (!$reserva) {
        notify('La reserva no existe.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    };  

    try {
        $sqlDelete = 'DELETE FROM reservations WHERE id = ?';
        $borrarReserva = $pdo->prepare($sqlDelete);
        $borrarReserva->execute([$id]);
    
        notify("Reserva Eliminada.", 'success');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    } catch (Exception $error) {
        die('Error de conexión a la base de datos: ' . $error->getMessage());
    }

?>