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

$reservasTotalesCarrosHoy = getTotalReservasCarrosToday($pdo);

$nombreArchivo = "reservas-carros-hoy.xls";

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");

header(
    'Content-Disposition: attachment; filename="' .
    $nombreArchivo .
    '"'
);

header("Pragma: no-cache");
header("Expires: 0");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <table>

        <thead>
            <tr>
                <th>Usuario</th>
                <th>Carro</th>
                <th>Fecha</th>
                <th>Comentario</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($reservasTotalesCarrosHoy as $reserva): ?>

                <tr>
                    <td><?= htmlspecialchars($reserva['usuario'] ?? '') ?></td>
                    <td><?= htmlspecialchars($reserva['carro'] ?? '') ?></td>
                    <td><?= htmlspecialchars($reserva['fecha'] ?? '') ?></td>
                    <td><?= htmlspecialchars($reserva['comentario'] ?? '') ?></td>
                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

</body>
</html>
