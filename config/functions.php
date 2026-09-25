<?php

    // if (session_status() === PHP_SESSION_NONE) {
    //     session_start();
    // }

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





    //------------------------------- FUNCIONES DEL SISTEMA -------------------------------
    // 
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
