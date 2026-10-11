<?php 
    //============================================
    //=========== ADMIN NOTIFICACIONES ===========
    //============================================

    //Notificaciones de registro de roles
    function notifyRegister(array $operacion, string $nombre, string $email): string
    {
        $title = $operacion['title'];
        $subtitle = $operacion['subtitle'];
        return "
            <h3><strong>$title</strong></h3>
            <p><strong><u>$subtitle</u></strong></p>
            <p><strong>Nombre: </strong>$nombre<p/>
            <p><strong>Email: </strong>$email<p/>
        ";    
    }
    //Notificaciones de creacion, edicion y eliminacion de notebooks
    function notifyOperationNotebookAdmin(array $operacion, string $nombre, string $numeroSerie, ?string $id_carro, ?string $nombreCarro): string
    {
        $title = $operacion['title'];
        $subtitle = $operacion['subtitle'];
        return "
            <h3><strong>$title</strong></h3>
            <p><strong><u>$subtitle</u></strong></p>
            <p><strong>Nombre:</strong> $nombre</p>
            <p><strong>Numero de Serie:</strong> $numeroSerie</p>
            <p><strong>Id Carro: </strong>" . ($id_carro ?? 'Sin asignar') . "</p>
            <p><strong>Nombre del Carro: </strong>" . ($nombreCarro ?? 'Sin asignar') . "</p>
        ";
    }

    // Notificaciones de creacion, edicion y eliminacion de reservas de aulas
    function notifyOperationReservAulaAdmin(array $operacion,string $profesor, string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        $title = $operacion['title'];
        $subtitle = $operacion['subtitle'];
        return "
            <h3><strong>$title</strong></h3>
            <p><strong><u>$subtitle</u></strong></p>
            <p><strong>Nombre del reservante:</strong> $profesor</p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }

    // Notificaciones de creacion, edicion y eliminacion de reservas de carros
    function notifyOperationReservCarroAdmin(array $operacion, string $profesor, string $nombreCarro, string $fecha, string $comentario): string
    {
        $title = $operacion['title'];
        $subtitle = $operacion['subtitle'];
        return "
            <h3><strong>$title</strong></h3>
            <p><strong><u>$subtitle</u></strong></p>
            <p><strong>Nombre del reservante:</strong> $profesor</p>
            <p><strong>Carro:</strong> $nombreCarro</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Comentario:</strong> $comentario</p>
        ";
    }

    //===============================================
    //=========== PROFESOR NOTIFICACIONES ===========
    //===============================================
    
    // Notificaciones a Profesores de creacion, edicion y eliminacion de reservas de aulas
    function notifyOperationReservAulaProf(array $operacion, string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        $title = $operacion['title'];
        $subtitle = $operacion['subtitle'];
        return "
            <h3><strong>$title</strong></h3>
            <p><strong><u>$subtitle</u></strong></p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }
    
    
    // Notificaciones a Profesores de creacion, edicion y eliminacion de reservas de carros
    function notifyOperationReservCarroProf(array $operacion, string $nombreCarro, string $fecha, string $comentario): string
    {
        $title = $operacion['title'];
        $subtitle = $operacion['subtitle'];
        return "
            <h3><strong>$title</strong></h3>
            <p><strong><u>$subtitle</u></strong></p>
            <p><strong>Carro:</strong> $nombreCarro</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Comentario:</strong> $comentario</p>
        ";
    }

?>