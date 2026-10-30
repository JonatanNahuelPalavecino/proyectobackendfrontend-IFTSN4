<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/db.php";
    require_once __DIR__ . "/../../config/sendMailNotify.php";
    require_once __DIR__ . "/../../config/email/templates/message.php";


    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para eliminar notebooks.', 'error');
        redirect('/dashboard/');
    }

    $idNotebook = $_GET['id'];

    if(!is_numeric($idNotebook)){
        notify("ID de notebook inválid", "error");
        redirect("/dashboard/notebooks/ver-notebooks.php");
    }

    $notebook = getNotebookById($pdo, $idNotebook);

    if(!$notebook){
        notify("La notebook no existe");
        redirect("/dashboard/notebooks/ver-notebooks.php");
    }


    if ($notebook['cart_id']) {
        $cart = getCartById($pdo, $notebook['cart_id']);
    
        if ($cart['estado'] == "reservado") {
            notify("No podes eliminar esta notebook porque esta reservado su carro a la que esta asignado");
            redirect('/dashboard/notebooks/ver-notebooks.php');
        }
    
        if ($cart['estado'] == "entregado") {
            notify("No podes eliminar esta notebook porque esta entregado su carro a la que esta asignado");
            redirect('/dashboard/notebooks/ver-notebooks.php');
        }
    }

    try {

        $sql = "DELETE FROM computers WHERE id = ?";
        $borrarNotebook = $pdo->prepare($sql);
        $borrarNotebook->execute([$idNotebook]);

        notify("Eliminación de notebook con éxito");
        redirect("/dashboard/notebooks/ver-notebooks.php");

    }catch(Exception $e){
        die("Error de consulta en la base de datos" . $e->getMessage());
    }

?>