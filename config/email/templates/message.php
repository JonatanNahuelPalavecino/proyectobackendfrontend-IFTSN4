<?php 
    //============================================
    //=========== ADMIN NOTIFICACIONES ===========
    //============================================

    //Notificaciones cuando se registra un nuevo rol
    function notifyRegister(array $descripcion, string $nombre, string $email): string
    {
        return "
            <h3><strong>{$descripcion['title']}</strong></h3>
            <p><strong><u>{$descripcion['subtitle']}</u></strong></p>
            <p><strong>Nombre: </strong>$nombre<p/>
            <p><strong>Email: </strong>$email<p/>
        ";    
    }
    
    // =======================================================================================================================
    
    //Notificaciones cuando un administrador realiza una creacion, edicion y eliminacion de notebooks
    function notifyOperationNotebookAdmin(array $operacion, string $nombre, string $numeroSerie, ?string $id_carro, ?string $nombreCarro): string
    {
        return "
            <h3><strong>{$operacion['title']}</strong></h3>
            <p><strong><u>{$operacion['subtitle']}</u></strong></p>
            <p><strong>Nombre:</strong> $nombre</p>
            <p><strong>Numero de Serie:</strong> $numeroSerie</p>
            <p><strong>Id Carro: </strong>" . ($id_carro ?? 'Sin asignar') . "</p>
            <p><strong>Nombre del Carro: </strong>" . ($nombreCarro ?? 'Sin asignar') . "</p>
        ";
    }

    function notifyOperationAulaAdmin(array $operacion, string $nombre, string $capacidad):string
    {
        return "
            <h3><strong>{$operacion['title']}</strong></h3>
            <p><strong><u>{$operacion['subtitle']}</u></strong></p>
            <p><strong>Nombre:</strong> $nombre</p>
            <p><strong>Capacidad:</strong> $capacidad</p>
        ";
    }

    // Funcion carros pendiente de implementacion

    function notifyOperationCarroAdmin()
    {

    }



    function notifyOperationDisponibilidadAulaAdmin(array $operacion, string $nombre, string $capacidad, string $dia_desde, string $dia_hasta, string $hora_inicio, string $hora_fin ):string
    { 
        return "
            <h3><strong>{$operacion['title']}</strong></h3>
            <p><strong><u>{$operacion['subtitle']}</u></strong></p>
            <p><strong>Nombre:</strong> $nombre</p>
            <p><strong>Capacidad:</strong> $capacidad</p>
            <p><strong>Dia desde:</strong> $dia_desde</p>
            <p><strong>Dia hasta:</strong> $dia_hasta</p>
            <p><strong>Horario desde:</strong> $hora_inicio hs</p>
            <p><strong>Horario hasta:</strong> $hora_fin hs</p>
        ";
    }

    // =======================================================================================================================

    // Notificaciones cuando un profesor realiza una creacion, edicion y eliminacion de reservas de aulas
    function notifyOperationReservAulaAdmin(array $operacion,string $profesor, string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        return "
            <h3><strong>{$operacion['title']}</strong></h3>
            <p><strong><u>{$operacion['subtitle']}</u></strong></p>
            <p><strong>Nombre del reservante:</strong> $profesor</p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }

    // Notificaciones cuando un profesor realiza una creacion, edicion y eliminacion de reservas de carros
    function notifyOperationReservCarroAdmin(array $operacion, string $profesor, string $nombreCarro, string $fecha, string $comentario): string
    {
        return "
            <h3><strong>{$operacion['title']}</strong></h3>
            <p><strong><u>{$operacion['subtitle']}</u></strong></p>
            <p><strong>Nombre del reservante:</strong> $profesor</p>
            <p><strong>Carro:</strong> $nombreCarro</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Comentario:</strong> $comentario</p>
        ";
    }

    //===============================================
    //=========== PROFESOR NOTIFICACIONES ===========
    //===============================================
    
    // Notificaciones a Profesores cuando realizan una creacion, edicion y eliminacion de reservas de aulas
    function notifyOperationReservAulaProf(array $operacion, string $nombreAula, string $fecha, string $hora_inicio, string $hora_fin): string
    {
        return "
            <h3><strong>{$operacion['title']}</strong></h3>
            <p><strong><u>{$operacion['subtitle']}</u></strong></p>
            <p><strong>Aula:</strong> $nombreAula</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Horario:</strong> $hora_inicio hs a $hora_fin hs</p>
        ";
    }

    // Notificaciones a Profesores cuando realizan una creacion, edicion y eliminacion de reservas de carros
    function notifyOperationReservCarroProf(array $operacion, string $nombreCarro, string $fecha, string $comentario): string
    {
        return "
            <h3><strong>{$operacion['title']}</strong></h3>
            <p><strong><u>{$operacion['subtitle']}</u></strong></p>
            <p><strong>Carro:</strong> $nombreCarro</p>
            <p><strong>Fecha Reserva:</strong> $fecha</p>
            <p><strong>Comentario:</strong> $comentario</p>
        ";
    }
    
    
?>