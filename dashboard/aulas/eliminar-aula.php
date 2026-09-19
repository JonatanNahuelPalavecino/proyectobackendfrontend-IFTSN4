<?php

    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para eliminar aulas.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    }

    $id = $_GET['id'] ?? '';

    if (!is_numeric($id)) {
        notify('ID de aula invalido.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    }

    $sql = 'SELECT * FROM classrooms WHERE id = ?';
    $consulta = $pdo->prepare($sql);
    $consulta->execute([$id]);
    $aula = $consulta->fetch();

    if (!$aula) {
        notify('El aula no existe.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    };  

    try {
        $sqlDelete = 'DELETE FROM classrooms WHERE id = ?';
        $borrarAula = $pdo->prepare($sqlDelete);
        $borrarAula->execute([$id]);
    
        notify("Aula Eliminada.", 'success');
        redirect('/dashboard/aulas/ver-aulas.php');
    } catch (Exception $error) {
        die('Error de conexión a la base de datos: ' . $error->getMessage());
    }

?>