<?php
    function validateInputsCreateorEditNotebooks($nombreNotebook, $numeroSerie, $conn, $idNotebook=null){
        
        if($nombreNotebook == "" || $numeroSerie == ""){
            return "Todos los campos son obligatorios";
        }

        if(strlen($nombreNotebook) < 8){
            return "El nombre de la notebook debe contener al menos 8 caracteres";
        }

        if(!preg_match('/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9-]+$/', $numeroSerie)){
            return "El numero de serie debe contener letras y numeros";
        }

        if (strlen($numeroSerie) < 5){
            return "El numero de serie debe contener minimo 5 caracteres";
        }

        //Al crear por primera vez
        if ($idNotebook === null){
            $sql = "SELECT id FROM computers WHERE numero_serie = :numero_serie";
            $numVerify = $conn->prepare($sql);
            $numVerify->execute([':numero_serie' => $numeroSerie]);
        }else{
            //Al editarlo
            $sql = "SELECT id FROM computers WHERE numero_serie = :numero_serie AND id != :id_notebook";
            $numVerify = $conn->prepare($sql);
            $numVerify->execute([':numero_serie' => $numeroSerie,':id_notebook' => $idNotebook]);
        }
            
        if($numVerify->fetch()){
            return "El numero de serie ya está registrado";
        }
        
        return null;
    }





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