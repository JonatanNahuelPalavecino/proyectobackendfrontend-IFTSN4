<?php 
    //============================================
    //=========== ADMIN NOTIFICACIONES ===========
    //============================================
    function notifyAdmin(string $nombre, string $email): string
    {
        return "
            <h3>Se registro un nuevo profesor: <strong>$nombre ($email)</strong></h3>
        ";    
    }

    function notifyReservAulaAdmin(string $profesor, string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        return "
            <h3><strong>¡Se registro una nueva reserva en el Sistema!</strong></h3>
            <p><strong><u>Datos de la reseva:</u></strong></p>
            <p><strong>Nombre del reservante:</strong> $profesor</p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }

    function notifyReservAulaAdminEdit(string $profesor, string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        return "
            <h3><strong>¡Se modifico una reserva en el Sistema!</strong></h3>
            <p><strong><u>Datos de la reseva:</u></strong></p>
            <p><strong>Nombre del reservante:</strong> $profesor</p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }

    function notifyReservAulaAdminDelete(string $profesor, string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        return "
            <h3><strong>¡Se elimino una reserva en el Sistema!</strong></h3>
            <p><strong><u>Datos de la reseva:</u></strong></p>
            <p><strong>Nombre del reservante:</strong> $profesor</p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }

    //===============================================
    //=========== PROFESOR NOTIFICACIONES ===========
    //===============================================
    function notifyProfesor(string $nombre, string $email): string
    {   
        return "
            <h3><strong>¡Registro exitoso!<strong></h3>
            <p><strong><u>Tus datos son:</u></strong></p>
            <p><strong>Nombre: </strong>$nombre<p/>
            <p><strong>Email: </strong>$email<p/>
        ";        
    }
    
    function notifyReservAulaProf(string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        return "
            <h3><strong>¡Reserva de aula exitoso!</strong></h3>
            <p><strong><u>Los datos de tu reserva son: </u></strong></p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }
    
    function notifyReservAulaProfEdit(string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        return "
            <h3><strong>¡Reserva de aula modificada exitosamente!</strong></h3>
            <p><strong><u>Los datos de tu reserva son:</u></strong></p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }

    function notifyReservAulaProfDelete(string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        return "
            <h3><strong>¡Reserva de aula eliminada!</strong></h3>
            <p><strong><u>Los datos de tu reserva eran: </u></strong></p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }
    


?>