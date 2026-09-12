<?php

    session_start();

    define('BASE_URL', '/TP-integrador-Lopez-Luna_Palavecino');

    $host = "127.0.0.1";
    $username = "root";
    $password = "";
    $db = 'sistema_reserva_aulas';
    $puerto = "3306";
    $charset = 'utf8mb4';


    // DSN = Data Source Name.
    // Le indica a PDO qué motor, host, base de datos y codificación usar.
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$puerto";

    try {
        $pdo = new PDO($dsn, $username, $password);

    } catch (PDOException $error) {
        die('Error de conexión a la base de datos: ' . $error->getMessage());
    }
?>