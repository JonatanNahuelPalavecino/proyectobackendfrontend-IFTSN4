<?php
    require_once __DIR__ . "/../config/functions.php";
    require_once __DIR__ . "/../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para eliminar notebooks.', 'error');
        redirect('/dashboard/');
    }
    
    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE ELIMINAR NOTEBOOKS

?>