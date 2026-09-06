<?php 

    define('BASE_URL', '/TP-integrador-Lopez-Luna_Palavecino');

    $host = "127.0.0.1";
    $username = "root";
    $password = "";
    $db = 'sistema_reserva_aulas';
    $puerto = "3306";

    $conn = new mysqli($host, $username, $password, $db, $puerto);

    if ($conn->connect_errno) {
        echo "La conexion fallo";
    }

?> 