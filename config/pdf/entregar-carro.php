<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../functions.php";

header("Content-Type: application/json; charset=UTF-8");


$usuario = getUser();


if (!$usuario || $usuario['rol'] !== 'admin') {

    echo json_encode([
        "ok" => false,
        "mensaje" => "No tenés permisos."
    ]);

    exit;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        "ok" => false,
        "mensaje" => "Método no permitido."
    ]);

    exit;
}


$reservaId = $_POST['id'] ?? null;


if (!$reservaId) {

    echo json_encode([
        "ok" => false,
        "mensaje" => "Reserva inválida."
    ]);

    exit;
}


$consulta = $pdo->prepare(
    "SELECT
        id,
        estado

    FROM cart_reservations

    WHERE id = :id"
);

$consulta->execute([
    'id' => $reservaId
]);

$reserva = $consulta->fetch();


if (!$reserva) {

    echo json_encode([
        "ok" => false,
        "mensaje" => "La reserva no existe."
    ]);

    exit;
}


if ($reserva['estado'] !== 'reservado') {

    echo json_encode([
        "ok" => false,
        "mensaje" => "La reserva ya fue entregada."
    ]);

    exit;
}


$actualizar = $pdo->prepare(
    "UPDATE cart_reservations

    SET
        estado = 'entregado',
        entregado_at = NOW()

    WHERE id = :id
    AND estado = 'reservado'"
);

$actualizar->execute([
    'id' => $reservaId
]);


echo json_encode([
    "ok" => true
]);

exit;