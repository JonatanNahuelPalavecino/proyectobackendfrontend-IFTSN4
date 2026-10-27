<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    // Guardia de seguridad estricta
    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para eliminar carros.', 'error');
        redirect('/dashboard/index.php');
    }

    $id = $_GET['id'] ?? '';

    if (!is_numeric($id)) {
        notify("ID de carro invalido", "error");
        redirect("/dashboard/carros/ver-carros.php");
    }


    $sql = "SELECT carts.id, nombre, descripcion, cart_reservations.estado FROM carts LEFT JOIN cart_reservations ON cart_reservations.id = (SELECT MAX(cr.id) FROM cart_reservations AS cr WHERE cr.cart_id = carts.id) WHERE carts.id =?";
    $consulta = $pdo->prepare($sql);
    $consulta->execute([$id]);
    $carro = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$carro) {
        notify("El carro no existe.", "error");
        redirect('/dashboard/carros/ver-carros.php');
    }

    if ($carro['estado'] == "reservado") {
        notify("El carro esta reservado y no puede eliminarse.", "error");
        redirect('/dashboard/carros/ver-carros.php');
    }

    if ($carro['estado'] == "entregado") {
        notify("El carro esta entregado y no puede eliminarse.", "error");
        redirect('/dashboard/carros/ver-carros.php');
    }

    try {
        // Operación DELETE en la base de datos
        $sqlDelete = "DELETE FROM carts WHERE id = ?";
        $stmt = $pdo->prepare($sqlDelete);
        $stmt->execute([$id]);

        notify("Carro eliminado exitosamente.", "success");
    } catch (Exception $e) {
        // Si falla (ej: el carro tiene reservas atadas a él y la base de datos bloquea el borrado por integridad)
        notify("Error al eliminar el carro. Verifica que no tenga reservas activas.", "error");
    }

    // Sin importar qué pase, redireccionamos de vuelta a la tabla
    redirect("/dashboard/carros/ver-carros.php");
?>
