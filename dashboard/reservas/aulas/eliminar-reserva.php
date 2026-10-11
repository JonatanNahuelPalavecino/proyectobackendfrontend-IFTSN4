<?php

    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/validations.php";
    require_once __DIR__ . "/../../../config/db.php";
    require_once __DIR__ . "/../../../config/sendMailNotify.php";
    require_once __DIR__ . "/../../../config/email/templates/message.php";

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

    if ($reserva['fecha'] < date('Y-m-d')) {
    notify(
        "No podés eliminar una reserva que ya fue cumplida.",
        "error"
    );

    redirect("/dashboard/reservas/aulas/");
}

    try {
        $sqlDelete = 'DELETE FROM reservations WHERE id = ?';
        $borrarReserva = $pdo->prepare($sqlDelete);
        $borrarReserva->execute([$id]);

        // Notificacion Administrador
        sendMailNotify(
            ['nombre' => 'Sistema de Reservas', 'email' => 'antruxxi@gmail.com'],
            'Reserva Eliminada',
            notifyReservAulaAdminDelete(
                $usuario['nombre'],
                getAulaReservable($pdo, $reserva['classroom_id'])['nombre'],
                $reserva['fecha'],
                $reserva['hora_inicio'],
                $reserva['hora_fin']
            )
        );

        // Notificacion Profesor
        sendMailNotify(
            ['nombre' => $usuario['nombre'], 'email' => $usuario['email']],
            'Sistema Reservas IFTS N°4',
            notifyReservAulaProfDelete(
                getAulaReservable($pdo, $reserva['classroom_id'])['nombre'],
                $reserva['fecha'],
                $reserva['hora_inicio'],
                $reserva['hora_fin']
            )
        );

        notify("Reserva Eliminada.", 'success');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    } catch (Exception $error) {
        die('Error de conexión a la base de datos: ' . $error->getMessage());
    };

?>