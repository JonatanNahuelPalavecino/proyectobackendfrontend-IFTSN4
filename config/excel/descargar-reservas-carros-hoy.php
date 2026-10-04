<?php

    require_once __DIR__ . "/../db.php";
    require_once __DIR__ . "/../functions.php";

    $usuario = getUser();


    if (!$usuario || $usuario['rol'] !== 'admin') {

        notify(
            "No tenés permisos para exportar el total de reservas de carros de hoy.",
            "error"
        );

        redirect("/dashboard/");
    }


    $reservasTotalesCarrosHoy = getDetalleReservasCarrosToday($pdo);
    $nombreArchivo = "reservas-carros-hoy.csv";

    header("Content-Type: text/csv; charset=UTF-8");
    header('Content-Disposition: attachment; filename="' .
        $nombreArchivo .
        '"'
    );

    $archivo = fopen("php://output", "w");

    fwrite($archivo, "\xEF\xBB\xBF");

    fputcsv(
        $archivo,
        [
            "Usuario",
            "Carro",
            "Fecha",
            "Comentario"
        ],
        ";"
    );

    foreach ($reservasTotalesCarrosHoy as $reserva) {

        fputcsv(
            $archivo,
            [
                $reserva['usuario'],
                $reserva['carro'],
                $reserva['fecha'],
                $reserva['comentario']
            ],
            ";"
        );
    }

    fclose($archivo);

    exit;

?>