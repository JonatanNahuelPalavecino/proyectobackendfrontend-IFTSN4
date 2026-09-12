<?php
    require_once __DIR__ . "/config/functions.php";
    require_once __DIR__ . "/config/db.php";

    $_SESSION = [];

    session_regenerate_id(true);
    
    notify("Has cerrado sesión correctamente.", "success");
    redirect('/login.php');
?>