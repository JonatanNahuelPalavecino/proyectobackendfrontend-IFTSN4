<?php

    // if (session_status() === PHP_SESSION_NONE) {
    //     session_start();
    // }

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
