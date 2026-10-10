<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../functions.php";

$usuario = getUser();

if (!$usuario || $usuario['rol'] !== 'admin') {

    notify(
        "No tenés permisos para exportar el detalle de reservas de aulas de hoy.",
        "error"
    );

    redirect("/dashboard/");
}

$reservasTotalesAulas = getDetalleReservasAulas($pdo);

$nombreArchivo = "detalle-reservas-aulas.xls";

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
                <th>Aula</th>
                <th>Fecha</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Capacidad</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($reservasTotalesAulas as $reserva): ?>

                <tr>
                    <td><?= htmlspecialchars($reserva['usuario'] ?? '') ?></td>
                    <td><?= htmlspecialchars($reserva['aula'] ?? '') ?></td>
                    <td><?= htmlspecialchars($reserva['fecha'] ?? '') ?></td>
                    <td><?= htmlspecialchars($reserva['hora_inicio'] ?? '') ?></td>
                    <td><?= htmlspecialchars($reserva['hora_fin'] ?? '') ?></td>
                    <td><?= htmlspecialchars($reserva['capacidad'] ?? '') ?></td>
                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

</body>
</html>
