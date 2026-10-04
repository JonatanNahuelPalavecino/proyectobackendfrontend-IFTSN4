<?php

    require_once __DIR__ . "/../db.php";
    require_once __DIR__ . "/../functions.php";

    $usuario = getUser();


    if (!$usuario || $usuario['rol'] !== 'admin') {

        notify(
            "No tenés permisos para exportar el total de reservas de aulas de hoy.",
            "error"
        );

        redirect("/dashboard/");
    }


    $reservasTotalesAulasHoy = getDetalleReservasAulasToday($pdo);
    $nombreArchivo = "reservas-aulas-hoy.csv";

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
            "Aula",
            "Fecha",
            "Hora Inicio",
            "Hora Fin",
            "Capacidad"
        ],
        ";"
    );

    foreach ($reservasTotalesAulasHoy as $reserva) {

        fputcsv(
            $archivo,
            [
                $reserva['usuario'],
                $reserva['aula'],
                $reserva['fecha'],
                $reserva['hora_inicio'],
                $reserva['hora_fin'],
                $reserva['capacidad']
            ],
            ";"
        );
    }

    fclose($archivo);

    exit;

?>