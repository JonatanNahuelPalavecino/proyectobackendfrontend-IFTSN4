<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../functions.php";
require_once __DIR__ . "/../tcpdf/tcpdf.php";


$usuario = getUser();


if (!$usuario || $usuario['rol'] !== 'admin') {
    exit("No tenés permisos.");
}


$reservaId = $_GET['id'] ?? null;


if (!$reservaId) {
    exit("Reserva inválida.");
}


$consulta = $pdo->prepare(
    "SELECT
        cart_reservations.id,
        cart_reservations.fecha,
        cart_reservations.estado,
        cart_reservations.comentario,

        users.nombre AS profesor,
        users.email,

        carts.id AS carro_id,
        carts.nombre AS carro

    FROM cart_reservations

    INNER JOIN users
        ON users.id = cart_reservations.user_id

    INNER JOIN carts
        ON carts.id = cart_reservations.cart_id

    WHERE cart_reservations.id = :id"
);

$consulta->execute([
    'id' => $reservaId
]);

$reserva = $consulta->fetch();


if (!$reserva) {
    exit("La reserva no existe.");
}


$consultaNotebooks = $pdo->prepare(
    "SELECT
        nombre_computadora,
        numero_serie

    FROM cart_delivery_items

    WHERE cart_reservation_id = :id

    ORDER BY id"
);

$consultaNotebooks->execute([
    'id' => $reservaId
]);

$notebooks = $consultaNotebooks->fetchAll();


$pdf = new TCPDF(
    'P',
    'mm',
    'A4',
    true,
    'UTF-8',
    false
);


$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetCreator('SIRAUCA');

$pdf->SetAuthor('Instituto');

$pdf->SetTitle(
    'Remito de entrega - Reserva #' . $reserva['id']
);

$pdf->SetMargins(
    15,
    15,
    15
);

$pdf->AddPage();


$html = '

<h1 style="text-align:center;">
    REMITO DE ENTREGA DE CARRO
</h1>

<hr>

<table cellpadding="5">

    <tr>

        <td width="50%">
            <strong>N° de reserva:</strong>
            ' . htmlspecialchars($reserva['id']) . '
        </td>

        <td width="50%">
            <strong>Fecha:</strong>
            ' . htmlspecialchars(formatDate($reserva['fecha'])) . '
        </td>

    </tr>

    <tr>

        <td width="50%">
            <strong>Profesor:</strong>
            ' . htmlspecialchars($reserva['profesor']) . '
        </td>

        <td width="50%">
            <strong>Carro:</strong>
            ' . htmlspecialchars($reserva['carro']) . '
        </td>

    </tr>

</table>


<br>


<h3>Aclaraciones</h3>

<p>
    ' . htmlspecialchars(
        $reserva['comentario'] ?: 'Sin aclaraciones'
    ) . '
</p>


<br>


<h3>Equipos entregados</h3>

<table
    border="1"
    cellpadding="5"
    width="100%"
>

    <thead>

        <tr style="font-weight:bold;">

            <th width="10%">
                #
            </th>

            <th width="45%">
                Equipo
            </th>

            <th width="45%">
                Número de serie
            </th>

        </tr>

    </thead>

    <tbody>
';


$numero = 1;


foreach ($notebooks as $notebook) {

    $html .= '

        <tr>

            <td width="10%">
                ' . $numero . '
            </td>

            <td width="45%">
                ' . htmlspecialchars(
                    $notebook['nombre_computadora']
                ) . '
            </td>

            <td width="45%">
                ' . htmlspecialchars(
                    $notebook['numero_serie']
                ) . '
            </td>

        </tr>
    ';

    $numero++;
}


$html .= '

    </tbody>

</table>


<br><br><br>


<table cellpadding="10">

    <tr>

        <td
            width="50%"
            align="center"
        >

            ______________________________

            <br>

            Firma Instituto

            <br>

            Aclaración

        </td>


        <td
            width="50%"
            align="center"
        >

            ______________________________

            <br>

            Firma Profesor

            <br>

            ' . htmlspecialchars($reserva['profesor']) . '

        </td>

    </tr>

</table>
';


$pdf->writeHTML(
    $html,
    true,
    false,
    true,
    false,
    ''
);


$pdf->Output(
    'remito-reserva-' . $reserva['id'] . '.pdf',
    'I'
);

exit;