<?php

    //------------------------------- CONSULTAS DASHBOARD ADMINISTRADOR -------------------------------

    function getTotalUsers($conn, $rol = "user") {
        $sql = 'SELECT COUNT(*) as Total FROM users WHERE rol = ?';
        $consulta = $conn->prepare($sql);
        $consulta->execute([$rol]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado['Total'];
    }

    function getTotalAulas($conn) {
        $sqlConsultaAulas = $conn->query("SELECT COUNT(*) as Total FROM classrooms");
        $totalAulas = $sqlConsultaAulas->fetch();
        return $totalAulas['Total'];
    }

    function getAllCarts($conn){
        return $conn->query("SELECT id, nombre FROM carts ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllNotebooks($conn){
        $sql = "SELECT pc.id, pc.nombre, pc.numero_serie, pc.created_at, pc.cart_id ,ca.nombre AS carro
                FROM computers pc
                LEFT JOIN carts ca 
                on pc.cart_id = ca.id 
                ORDER BY pc.id ASC";

        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTotalCarts($conn){
        $consulta = $conn->query("SELECT COUNT(*) as Total FROM carts");
        $totalCarts = $consulta->fetch();
        return $totalCarts['Total'];
    }

    function getTotalNotebooks($conn){
        $consulta = $conn->query("SELECT COUNT(*) as Total FROM computers");
        $totalNotebooks = $consulta->fetch();
        return $totalNotebooks['Total'];
    }

    function getNotebookById($conn, $id){
        $sql = "SELECT * FROM computers WHERE id = :id_notebook";
        $consulta = $conn->prepare($sql);
        $consulta->execute([":id_notebook" => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }

    function getReservasCarrosAdmin($conn){
        $sql = "SELECT r.id, r.fecha, r.comentario, r.estado, r.entregado_at, r.devuelto_at, c.nombre AS carro
                FROM carts c
                INNER JOIN cart_reservations r ON r.cart_id = c.id
                ORDER BY r.fecha DESC";

        $consulta = $conn->prepare($sql);
        $consulta->execute ();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);

    }




    //------------------------------- CONSULTAS DASHBOARD USUARIO -------------------------------
    function getCantReservActivas($conn, $id){
        $sql = "SELECT COUNT(*) AS Total FROM reservations WHERE user_id = :id AND fecha >= CURDATE()";
        $consulta = $conn->prepare($sql);
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado['Total'];
    }

    function getAllCantReserv($conn, $id){
        $sql = "SELECT COUNT(*) AS Total FROM reservations WHERE user_id = :id";
        $consulta = $conn->prepare($sql);
        $consulta->execute([':id' => $id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado['Total'];
    }

    function getReservasUser($conn, $id){
        $sql = "SELECT r.id, r.fecha, r.hora_inicio, r.hora_fin, c.nombre as aula, c.capacidad 
                FROM classrooms c
                INNER JOIN reservations r on r.classroom_id = c.id
                where r.user_id = :id AND r.fecha >= CURDATE() 
                ORDER BY 
                r.fecha ASC, r.hora_inicio ASC";
        $consulta = $conn->prepare($sql);
        $consulta->execute([':id' => $id ]);
        return $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

<<<<<<< HEAD
    function getReservasCarrosActivas($conn, $id) {
        $sql = "SELECT r.id, r.fecha, r.comentario, c.nombre AS carro 
                FROM carts c
                INNER JOIN cart_reservations r ON r.cart_id = c.id
                WHERE r.user_id = :id AND r.fecha >= CURDATE() 
                ORDER BY r.fecha ASC";
                
        $consulta = $conn->prepare($sql);
        $consulta->execute([':id' => $id]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    function getCarrosDisponibles ($conn){
        $sql = "SELECT id, nombre, capacidad FROM carts ORDER BY nombre ASC";
        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function crearReservaCarro ($conn, $user_id,$cart_id,$fecha,$comentario){
        $sql = "INSERT INTO cart_reservations (user_id,cart_id,fecha,comentario)
                VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);

        return $stmt->execute([$user_id, $cart_id, $fecha,$comentario]);

    }




=======
    function getAulasReservables($pdo){
        $consulta = $pdo->query(
            'SELECT
                classrooms.id,
                classrooms.nombre,
                classrooms.capacidad,
                classroom_schedules.dia_desde,
                classroom_schedules.dia_hasta,
                classroom_schedules.hora_inicio,
                classroom_schedules.hora_fin
            FROM classrooms
            INNER JOIN classroom_schedules
                ON classroom_schedules.classroom_id = classrooms.id
            ORDER BY classrooms.nombre'
        );

        return $consulta->fetchAll();
    }

    function getAulaReservable($pdo, $id){
        $consulta = $pdo->prepare(
            'SELECT
                classrooms.id,
                classrooms.nombre,
                classrooms.capacidad,
                classroom_schedules.dia_desde,
                classroom_schedules.dia_hasta,
                classroom_schedules.hora_inicio,
                classroom_schedules.hora_fin
            FROM classrooms
            INNER JOIN classroom_schedules
                ON classroom_schedules.classroom_id = classrooms.id
            WHERE classrooms.id = :id'
        );

        $consulta->execute([
            'id' => $id
        ]);

        return $consulta->fetch();
    }

    function getReservasDelDia($pdo, $aulaId, $fecha, $idReservaExcluir = null){
        $sql = 'SELECT id, hora_inicio, hora_fin
                FROM reservations
                WHERE classroom_id = :classroom_id
                AND fecha = :fecha';

        $parametros = [
            'classroom_id' => $aulaId,
            'fecha' => $fecha
        ];

        if ($idReservaExcluir !== null) {
            $sql .= ' AND id <> :id_reserva';
            $parametros['id_reserva'] = $idReservaExcluir;
        }

        $sql .= ' ORDER BY hora_inicio';

        $consulta = $pdo->prepare($sql);
        $consulta->execute($parametros);

        return $consulta->fetchAll();
    }

    function fechaPermitida($fecha, $aula){
        // date("N") devuelve 1 para lunes y 7 para domingo.
        $numeroDia = (int) date('N', strtotime($fecha));

        return (
            $numeroDia >= $aula['dia_desde']
            &&
            $numeroDia <= $aula['dia_hasta']
        );
    }

    function horaAMinutos($hora){
        $partes = explode(':', $hora);

        return ((int) $partes[0] * 60) + (int) $partes[1];
    }

    function minutosAHora($minutos){
        $horas = floor($minutos / 60);
        $minutosRestantes = $minutos % 60;

        return
            str_pad($horas, 2, '0', STR_PAD_LEFT)
            . ':'
            . str_pad($minutosRestantes, 2, '0', STR_PAD_LEFT);
    }

    function getRangosLibres($pdo, $aula, $fecha, $idReservaExcluir = null){
        if (!fechaPermitida($fecha, $aula)) {
            return [];
        }

        $inicioDisponible = horaAMinutos($aula['hora_inicio']);
        $finDisponible = horaAMinutos($aula['hora_fin']);

        // Si se reserva para hoy, no mostramos horarios que ya pasaron.
        if ($fecha === date('Y-m-d')) {
            $ahora = ((int) date('H') * 60) + (int) date('i');
            // Las reservas se realizan en bloques de una hora.
            $ahora = ceil($ahora / 60) * 60;

            if ($ahora > $inicioDisponible) {
                $inicioDisponible = $ahora;
            }
        }

        if ($inicioDisponible >= $finDisponible) {
            return [];
        }

        $reservas = getReservasDelDia($pdo, $aula['id'], $fecha, $idReservaExcluir);

        $rangosLibres = [];
        $cursor = $inicioDisponible;

        foreach ($reservas as $reserva) {
            $inicioReserva = horaAMinutos($reserva['hora_inicio']);
            $finReserva = horaAMinutos($reserva['hora_fin']);

            if ($finReserva <= $inicioDisponible || $inicioReserva >= $finDisponible) {
                continue;
            }

            $inicioReserva = max($inicioReserva, $inicioDisponible);
            $finReserva = min($finReserva, $finDisponible);

            if ($inicioReserva > $cursor) {
                $rangosLibres[] = [
                    'inicio' => minutosAHora($cursor),
                    'fin' => minutosAHora($inicioReserva)
                ];
            }

            if ($finReserva > $cursor) {
                $cursor = $finReserva;
            }
        }

        if ($cursor < $finDisponible) {
            $rangosLibres[] = [
                'inicio' => minutosAHora($cursor),
                'fin' => minutosAHora($finDisponible)
            ];
        }

        return $rangosLibres;
    }

    function horarioEstaLibre($rangosLibres, $horaInicio, $horaFin){
        $inicioElegido = horaAMinutos($horaInicio);
        $finElegido = horaAMinutos($horaFin);

        foreach ($rangosLibres as $rango) {
            $inicioRango = horaAMinutos($rango['inicio']);
            $finRango = horaAMinutos($rango['fin']);

            if ($inicioElegido >= $inicioRango && $finElegido <= $finRango) {
                return true;
            }
        }

        return false;
    }

    function existeSolapamiento($pdo, $aulaId, $fecha, $horaInicio, $horaFin, $idReservaExcluir = null){
        $sql = 'SELECT id
                FROM reservations
                WHERE classroom_id = :classroom_id
                AND fecha = :fecha
                AND hora_inicio < :hora_fin
                AND hora_fin > :hora_inicio';

        $parametros = [
            'classroom_id' => $aulaId,
            'fecha' => $fecha,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin
        ];

        if ($idReservaExcluir !== null) {
            $sql .= ' AND id <> :id_reserva';
            $parametros['id_reserva'] = $idReservaExcluir;
        }

        $sql .= ' LIMIT 1';

        $consulta = $pdo->prepare($sql);
        $consulta->execute($parametros);

        return $consulta->fetch() ? true : false;
    }

    function crearDiasCalendario($pdo, $aula, $mes, $idReservaExcluir = null){
        $primerDia = $mes . '-01';
        $cantidadDias = (int) date('t', strtotime($primerDia));

        $dias = [];

        for ($numero = 1; $numero <= $cantidadDias; $numero++) {
            $fecha = sprintf('%s-%02d', $mes, $numero);

            $esPasado = $fecha < date('Y-m-d');
            $estaHabilitado = fechaPermitida($fecha, $aula);

            $rangosLibres = [];

            if (!$esPasado && $estaHabilitado) {
                $rangosLibres = getRangosLibres($pdo, $aula, $fecha, $idReservaExcluir);
            }

            $dias[] = [
                'numero' => $numero,
                'fecha' => $fecha,
                'es_pasado' => $esPasado,
                'habilitado' => $estaHabilitado,
                'disponible' => !$esPasado && $estaHabilitado && count($rangosLibres) > 0,
                'completo' => !$esPasado && $estaHabilitado && count($rangosLibres) === 0
            ];
        }

        return $dias;
    }

    function getReservaById($pdo, $idReserva){
        $sql = 'SELECT
                    reservations.id,
                    reservations.user_id,
                    reservations.classroom_id,
                    reservations.fecha,
                    reservations.hora_inicio,
                    reservations.hora_fin,
                    classrooms.nombre AS aula,
                    classrooms.capacidad
                FROM reservations
                INNER JOIN classrooms
                    ON classrooms.id = reservations.classroom_id
                WHERE reservations.id = :id_reserva';

        $consulta = $pdo->prepare($sql);
        $consulta->execute(['id_reserva' => $idReserva]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
>>>>>>> develop

    //------------------------------- FUNCIONES DEL SISTEMA -------------------------------
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

    function getDayString ($number) {
        switch ($number) {
            case 1:
                return "Lunes";
            case 2:
                return "Martes";
            case 3:
                return "Miercoles";
            case 4:
                return "Jueves";
            case 5:
                return "Viernes";
            case 6:
                return "Sabado";
            case 7:
                return "Domingo";
            default:
                return "No seteado";
        }
    }
?>
