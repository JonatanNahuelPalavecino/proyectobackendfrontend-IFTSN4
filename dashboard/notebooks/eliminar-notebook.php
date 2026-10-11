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

    var_dump($idNotebook);

    if(!is_numeric($idNotebook)){
        notify("ID de notebook inválid", "error");
        redirect("/dashboard/notebooks/ver-notebooks.php");
    }

    $notebook = getNotebookById($pdo, $idNotebook);

    if(!$notebook){
        notify("La notebook no existe");
        redirect("/dashboard/notebooks/ver-notebooks.php");
    }

    try {
        $sql = "DELETE FROM computers WHERE id = ?";
        $borrarNotebook = $pdo->prepare($sql);
        $borrarNotebook->execute([$idNotebook]);

        $cart = getCartById($pdo, $notebook['cart_id']);
        //Notificacion admin
        sendMailNotify(
            ['nombre' => 'Sistema de Reservas', 'email' => 'antruxxi@gmail.com'],
            'Se ha eliminado una notebook',
            notifyOperationNotebookAdmin(
                ['title' => '¡Se eliminó una notebook en el Sistema!', 'subtitle' => 'Los datos de la notebook eran:'],
                $notebook['nombre'], 
                $notebook['numero_serie'], 
                $notebook['cart_id'], 
                $cart ? $cart['nombre'] : null
            )
        );

        notify("Eliminación de notebook con éxito");
        redirect("/dashboard/notebooks/ver-notebooks.php");

    }catch(Exception $e){
        die("Error de consulta en la base de datos" . $e->getMessage());
    }







    
    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE ELIMINAR NOTEBOOKS

?>