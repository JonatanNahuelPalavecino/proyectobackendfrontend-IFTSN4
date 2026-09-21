<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para modificar notebooks.', 'error');
        redirect('/dashboard/');
    }
    
    $idNotebook = $_GET['id'];

    echo "Modificar" . $idNotebook;
    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE MODIFICAR NOTEBOOKS

?>