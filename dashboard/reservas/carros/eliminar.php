<?php
    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/db.php";


    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para eliminar carros.', 'error');
        redirect('/dashboard/');
    }

?>
    
<?php
    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/db.php";

    $usuario = getUser();

    // Guardia de seguridad estricta
    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para eliminar carros.', 'error');
        redirect('/dashboard/index.php');
    }

    $id = $_GET['id'] ?? '';

    if (is_numeric($id)) {
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
    } else {
        notify("ID de carro inválido.", "error");
    }

    // Sin importar qué pase, redireccionamos de vuelta a la tabla
    redirect("/dashboard/reservas/carros/ver-reservas.php");
?>
?>