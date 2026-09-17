<?php

    // if (session_status() === PHP_SESSION_NONE) {
    //     session_start();
    // }

    //--- CONSULTAS DASHBOARD USUARIO ---
    function getCantReservActivas($pdo, $id){
        $sql = "SELECT COUNT(*) AS Total FROM reservations WHERE user_id = :id AND fecha >= CURDATE()";
        $consulta = $pdo->prepare($sql);
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado['Total'];
    }

    function getAllCantReserv($pdo, $id){
        $sql = "SELECT COUNT(*) AS Total FROM reservations WHERE user_id = :id";
        $consulta = $pdo->prepare($sql);
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado['Total'];
    }

    function getReservasUser($pdo, $id){
        $sql = "SELECT r.fecha, r.hora_inicio, r.hora_fin, c.nombre as aula, c.capacidad 
                FROM classrooms c
                INNER JOIN reservations r on r.classroom_id = c.id
                where r.user_id = :id AND r.fecha >= CURDATE() 
                ORDER BY 
                r.fecha ASC, r.hora_inicio ASC";
        $consulta = $pdo->prepare($sql);
        $consulta->execute([':id' => $id ]);
        return $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


    function notify($message, $type = 'success') {
        $_SESSION['notification'] = ['message' => $message, 'type' => $type];
    }

    function isLoggedIn() {
        return isset($_SESSION['usuario']);
    };

    function redirect($location) {
        header("Location: " . BASE_URL . $location);
        exit();
    }

    function getUser() {
        return $_SESSION['usuario'] ?? null;
    }

    function validateInputsRegister ($nombre, $email, $password) {
        if ($nombre == "" || $email == "" || $password == "") {
            return "Todos los campos son obligatorios.";
        }

        if (strlen($nombre) < 3) {
            return "El nombre debe tener al menos 3 caracteres.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "El correo electrónico no es válido.";
        }

        if (strlen($password) < 6) {
            return "La contraseña debe tener al menos 6 caracteres.";
        }


        return null;
    }

    function validateInputsLogin ($email, $password) {
        if ($email == "" || $password == "") {
            return "Todos los campos son obligatorios.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "El correo electrónico no es válido.";
        }

        if (strlen($password) < 6) {
            return "La contraseña debe tener al menos 6 caracteres.";
        }

        return null;
    }

?>
