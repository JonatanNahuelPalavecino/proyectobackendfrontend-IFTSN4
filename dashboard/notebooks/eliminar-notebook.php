<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/db.php";

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

        notify("¡Notebook eliminada con exito!");
        redirect("/dashboard/notebooks/ver-notebooks.php");

    }catch(Exception $e){
        die("Error de consulta en la base de datos" . $e->getMessage());
    }







    
    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE ELIMINAR NOTEBOOKS

?>