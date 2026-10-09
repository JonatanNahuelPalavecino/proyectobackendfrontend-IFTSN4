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

    function notifyReservAulaAdmin($profesor, $nombreAula, $fecha, $hora_inicio, $hora_fin)
    {
        return $message = "
            <h3><strong>¡Se registro una nueva reserva en el Sistema!</strong></h3>
            <p><strong>Datos de la reseva:</strong></p>
            <p>Nombre del reservante: $profesor</p>
            <p>Nombre Aula: $nombreAula</p>
            <p>Fecha Reserva: $fecha</p>
            <p>Horario: $hora_inicio hs a $hora_fin hs</p>
        ";
    }



    function notifyReservAulaProf($nombreAula, $fecha, $hora_inicio, $hora_fin)
    {
        return $mensaje = "
            <h3><strong>¡Reserva de aula exitoso!</strong></h3>
            <p><strong>Los datos de tu reserva son: </strong></p>
            <p>Nombre Aula: $nombreAula:</p>
            <p>Fecha Reserva: $fecha</p>
            <p>Horario: $hora_inicio hs a $hora_fin hs</p>
        ";
    }



?>