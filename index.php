<?php
    require_once __DIR__ . "/config/functions.php";
    require_once __DIR__ . "/config/db.php";

    if (isLoggedIn()) {
        return redirect("/dashboard.php");
    }

    redirect("/login.php");

    // REEMPLAZAR EL CODIGO DE ARRIBA POR RUTA. EL CONDICIONAMIENTO DE SI ESTA LOGUEADO O NO LO HACE EL HEADER
?>