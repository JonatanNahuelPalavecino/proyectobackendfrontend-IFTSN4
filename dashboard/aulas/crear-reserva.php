<?php
    require_once __DIR__ . "/../config/functions.php";
    require_once __DIR__ . "/../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'user') {
        notify('No tenés permisos para crear reservas de aulas.', 'error');
        redirect('/dashboard/');
    }
    
    //ACA PUEDE ENTRAR SOLO USER, YA QUE EL ADMIN NO PUEDE CREAR RESERVAS DE AULAS

?>