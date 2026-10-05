<?php 



    function notifyProfesor($nombre, $email)
    {
            return $mensaje = "
                    <h3><strong>¡Registro exitoso!<strong></h3>
                    <p>Tus datos son:</p>
                    <p><strong>Nombre: </strong>$nombre<p/>
                    <p><strong>Email: </strong>$email<p/>
                ";

            
    }
    function notifyAdmin($nombre, $email)
    {
        return $mensaje = "<h3>Se registro un nuevo profesor: <strong>$nombre ($email)</strong> </h3>";
            
    }



?>