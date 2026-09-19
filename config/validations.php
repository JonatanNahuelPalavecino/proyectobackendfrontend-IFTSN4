<?php

    function validateInputsCreateorEditClassroom ($nombre_aula, $capacidad) {
        if ($nombre_aula == "" || $capacidad == "") {
            return "Todos los campos son obligatorios.";
        }

        if (strlen($nombre_aula) < 5) {
            return "El nombre del aula debe tener al menos 6 caracteres.";
        }

        
        if ($capacidad <= 0 || !is_numeric($capacidad)) {
            return "La capacidad del aula debe ser mayor a cero.";
        }

        return null;    
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