<?php

    /**
     * @var array $aulaSeleccionada
     * @var array $diasCalendario
     * @var string $mes
     * @var int|string $aulaId
     * @var bool $modoEdicion
     * @var int|string|null $reservaId
     */

    $modoEdicion = $modoEdicion ?? false;
    $reservaId = $reservaId ?? null;
    $rutaCalendario = $modoEdicion
        ? '/dashboard/reservas/aulas/editar-reserva.php'
        : '/dashboard/reservas/aulas/crear-reserva.php';
    $parametroAula = $modoEdicion
        ? 'id=' . (int) $reservaId
        : 'aula=' . (int) $aulaId;

    $primerDiaMes = $mes . '-01';
    $diaSemanaPrimerDia = (int) date('N', strtotime($primerDiaMes));
    $mesAnterior = date('Y-m', strtotime($primerDiaMes . ' -1 month'));
    $mesSiguiente = date('Y-m', strtotime($primerDiaMes . ' +1 month'));

    $nombresMeses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];

    $numeroMes = (int) date('n', strtotime($primerDiaMes));
    $anio = date('Y', strtotime($primerDiaMes));
?>

<section class="reservation-calendar" id="calendario">
    <h2>Calendario de <?= htmlspecialchars($aulaSeleccionada['nombre']) ?></h2>

    <div class="calendar-navigation">
        <?php if ($mesAnterior >= date('Y-m')): ?>
            <a href="<?= BASE_URL . $rutaCalendario ?>?<?= $parametroAula ?>&mes=<?= $mesAnterior ?>#calendario">
                ← Mes anterior
            </a>
        <?php else: ?>
            <span></span>
        <?php endif; ?>

        <strong><?= $nombresMeses[$numeroMes] ?> <?= $anio ?></strong>

        <a href="<?= BASE_URL . $rutaCalendario ?>?<?= $parametroAula ?>&mes=<?= $mesSiguiente ?>#calendario">
            Mes siguiente →
        </a>
    </div>

    <table class="calendar-table">
        <thead>
            <tr>
                <th>Lun</th>
                <th>Mar</th>
                <th>Mié</th>
                <th>Jue</th>
                <th>Vie</th>
                <th>Sáb</th>
                <th>Dom</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <?php for ($i = 1; $i < $diaSemanaPrimerDia; $i++): ?>
                    <td class="calendar-day empty"></td>
                <?php endfor; ?>

                <?php foreach ($diasCalendario as $indice => $dia): ?>
                    <td class="calendar-day <?= $dia['disponible'] ? 'available' : 'disabled' ?>">
                        <?php if ($dia['disponible']): ?>
                            <a href="<?= BASE_URL . $rutaCalendario ?>?<?= $parametroAula ?>&fecha=<?= urlencode($dia['fecha']) ?>&mes=<?= urlencode($mes) ?>#horarios">
                                <strong><?= (int) $dia['numero'] ?></strong>
                                <small>Disponible</small>
                            </a>
                        <?php else: ?>
                            <span><?= (int) $dia['numero'] ?></span>
                            <small>
                                <?php if ($dia['completo']): ?>
                                    Completo
                                <?php elseif ($dia['es_pasado']): ?>
                                    No disponible
                                <?php elseif (!$dia['habilitado']): ?>
                                    No habilitado
                                <?php endif; ?>
                            </small>
                        <?php endif; ?>
                    </td>

                    <?php if (($indice + $diaSemanaPrimerDia) % 7 === 0 && $indice < count($diasCalendario) - 1): ?>
                        </tr><tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php
                    $celdasUsadas = ($diaSemanaPrimerDia - 1 + count($diasCalendario)) % 7;
                    $celdasRestantes = $celdasUsadas === 0 ? 0 : 7 - $celdasUsadas;
                ?>

                <?php for ($i = 0; $i < $celdasRestantes; $i++): ?>
                    <td class="calendar-day empty"></td>
                <?php endfor; ?>
            </tr>
        </tbody>
    </table>
</section>
