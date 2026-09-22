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
        $sql = "SELECT pc.id, pc.nombre, pc.numero_serie, pc.created_at, ca.nombre AS carro
                FROM computers pc
                INNER JOIN carts ca 
                on pc.cart_id = ca.id 
                ORDER BY pc.id ASC";

        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
        $sql = "SELECT r.fecha, r.hora_inicio, r.hora_fin, c.nombre as aula, c.capacidad 
                FROM classrooms c
                INNER JOIN reservations r on r.classroom_id = c.id
                where r.user_id = :id AND r.fecha >= CURDATE() 
                ORDER BY 
                r.fecha ASC, r.hora_inicio ASC";
        $consulta = $conn->prepare($sql);
        $consulta->execute([':id' => $id ]);
        return $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllClassroomsAndSchedules ($conn) {
        $sqlConsultaAulas = $conn->query("SELECT * FROM classrooms JOIN classroom_schedules ON classrooms.id = classroom_schedules.classroom_id ORDER BY classrooms.id");
        $aulas = $sqlConsultaAulas->fetchAll();
        return $aulas;
    }

    function getClassroomAndSchedule ($conn, $id) {
        $sql = "SELECT * FROM classrooms JOIN classroom_schedules ON classrooms.id = classroom_schedules.classroom_id WHERE classrooms.id = ? ORDER BY classrooms.id";
        $consulta = $conn->prepare($sql);
        $consulta->execute([$id]);
        $aula = $consulta->fetch();
        return $aula;
    }

    function getReservationsByMonth($conn, $classroomId, $month) {
        $inicio = $month . '-01';
        $fin = date('Y-m-t', strtotime($inicio));
        $sql = "SELECT fecha, hora_inicio, hora_fin FROM reservations
                WHERE classroom_id = :classroom_id AND fecha BETWEEN :inicio AND :fin
                ORDER BY fecha, hora_inicio";
        $consulta = $conn->prepare($sql);
        $consulta->execute(['classroom_id' => $classroomId, 'inicio' => $inicio, 'fin' => $fin]);
        $reservas = [];
        foreach ($consulta->fetchAll(PDO::FETCH_ASSOC) as $reserva) {
            $reservas[$reserva['fecha']][] = $reserva;
        }
        return $reservas;
    }

    function getAvailableRanges($schedule, $reservations = []) {
        $rangos = [['inicio' => substr($schedule['hora_inicio'], 0, 5), 'fin' => substr($schedule['hora_fin'], 0, 5)]];
        foreach ($reservations as $reserva) {
            $ocupadoInicio = substr($reserva['hora_inicio'], 0, 5);
            $ocupadoFin = substr($reserva['hora_fin'], 0, 5);
            $nuevos = [];
            foreach ($rangos as $rango) {
                if ($ocupadoFin <= $rango['inicio'] || $ocupadoInicio >= $rango['fin']) {
                    $nuevos[] = $rango;
                    continue;
                }
                if ($rango['inicio'] < $ocupadoInicio) $nuevos[] = ['inicio' => $rango['inicio'], 'fin' => $ocupadoInicio];
                if ($ocupadoFin < $rango['fin']) $nuevos[] = ['inicio' => $ocupadoFin, 'fin' => $rango['fin']];
            }
            $rangos = $nuevos;
        }
        return array_values(array_filter($rangos, fn($rango) => $rango['inicio'] < $rango['fin']));
    }

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
