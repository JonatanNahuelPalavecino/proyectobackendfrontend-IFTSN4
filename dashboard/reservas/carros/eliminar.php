<?php

    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/validations.php";
    require_once __DIR__ . "/../../../config/db.php";

    $usuario = getUser();

    if ($usuario["rol"] !== "user") {
        notify('No tenés permisos para editar reservas.', 'error');
        redirect('/dashboard/');
    }

    $reservaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$reservaId) {
        notify('La reserva indicada no es válida.', 'error');
        redirect('/dashboard/reservas/aulas/ver-reservas.php');
    }

    $reservaAEliminar = getReservaCarroById($pdo, $reservaId, $usuario['id']);

    if (!$reservaAEliminar) {
        notify('No existe la reserva.', 'error');
        redirect('/dashboard/reservas/carros/ver-reservas.php');
    }

    if ($reservaAEliminar['estado'] == "entregado") {
        notify('La reserva ya fue entregada. No podes eliminarla.', 'error');
        redirect('/dashboard/reservas/carros/ver-reservas.php');
    } elseif ($reservaAEliminar['estado'] == "devuelto") {
        notify('La reserva ya fue devuelta. No podes eliminarla.', 'error');
        redirect('/dashboard/reservas/carros/ver-reservas.php');
    }

    try {

        $sql = "DELETE FROM cart_reservations WHERE id = ?";
        $eliminarReserva = $pdo->prepare($sql);
        $eliminarReserva->execute([$reservaAEliminar['id']]);

        $computadorasAEliminar = getComputadorasReservadasById($pdo, $reservaAEliminar['id']);

        eliminarRegistroDeNotebookDeCarroReservado($pdo, $computadorasAEliminar, $reservaAEliminar['id']);

        notify("Reserva eliminada con éxito", "success");
        redirect('/dashboard/reservas/carros/ver-reservas.php');
    } catch (\PDOException $e) {
        notify("Ocurrió un error inesperado al conectar con la base de datos.", "error");
        var_dump($e);
    }

?>

