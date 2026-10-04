<?php
    require_once __DIR__ . "/../config/functions.php";
    require_once __DIR__ . "/../config/db.php";

    $usuario = getUser();

    if (!$usuario) {
        notify('Debés iniciar sesión para acceder.', 'error');
        redirect('/login.php');
    }

    $titulo = 'Dashboard | Reservá tu aula';
    
    if ($usuario['rol'] === "admin") {

        $totalAulas = getTotalAulas($pdo);
        $totalUsers = getTotalUsers($pdo);
        $totalCarts = getTotalCarts($pdo);
        $totalNotebooks = getTotalNotebooks($pdo);
        $totalReservasAulasHoy = getTotalReservasAulasToday($pdo);
        $totalReservasCarrosHoy = getTotalReservasCarrosToday($pdo);

        $detalleReservasAulasHoy = getDetalleReservasAulasToday($pdo);
        $detalleReservasCarrosHoy = getDetalleReservasCarrosToday($pdo);

    } else {
        $totalReservasAulasActivas = getTotalReservasAulasActivas($pdo, $usuario['id']);
        $totalReservasAulas = getTotalReservasAulas($pdo, $usuario['id']);
        $totalReservasCarrosActivas = getTotalReservasCarrosActivas($pdo, $usuario['id']); 
        $totalReservasCarros = getTotalReservasCarros($pdo, $usuario['id']);

        $detalleReservasAulasFuturas = getDetalleReservasAulasFuturas($pdo, $usuario['id']);
        $detalleReservasCarrosFuturas = getDetalleReservasCarrosFuturas($pdo, $usuario['id']);

    }

?>

<?php 
    require __DIR__ . '/../components/header.php';
 ?>


<main class="dashboard">
    <section class="dashboard-welcome">
            <?php if ($usuario['rol'] === 'admin'): ?>

                <div>
                    <h1>Bienvenido <?= htmlspecialchars(ucwords($usuario['nombre'])) ?> al Sistema!</h1>
                    <p>Eres un administrador. Aquí puedes gestionar usuarios, aulas y reservas.</p>    
                </div>
                
                <div>
                    <a href="<?= BASE_URL?>/dashboard/aulas/ver-aulas.php" class="btn reservar">Gestionar Aulas</a>
                </div>

                <div>
                    <a href="<?= BASE_URL?>/dashboard/notebooks/ver-notebooks.php" class="btn reservar">Gestionar Pcs</a>
                </div>
                <div>
                    <a href="<?= BASE_URL?>/dashboard/carros/ver-carros.php" class="btn reservar">Gestionar Carros</a>
                </div>

            <?php else: ?>
                
                <div>
                    <h1>Bienvenido Profesor, <?=htmlspecialchars(ucwords($usuario['nombre']))?>!</h1>
                    <p>Eres un usuario regular. Aquí puedes ver tus reservas y realizar nuevas reservas de aulas.</p>
                </div>
                <div>
                    <a href="<?=BASE_URL?>/dashboard/reservas/aulas/crear-reserva.php" class="btn reservar">Reservar aula</a>
                </div>
                <div>
                    <a href="<?= BASE_URL?>/dashboard/reservas/carros/crear-reserva.php" class="btn reservar">Reservar Carros</a>
                </div>
                
            <?php endif; ?> 
    </section>
    
    <?php if($usuario["rol"] =="admin"):?>
        <section class= "dashboard-cards">
            <article class="card-info">
                <span><?= $totalAulas ?></span>
                <small>Aulas Totales</small>
            </article>
                
            <article class="card-info">
                <span><?= $totalUsers?></span>
                <small>Profesores Registrados</small>
            </article>
            
            <article class="card-info">
                <span><?= $totalNotebooks ?></span>
                <small>Notebooks Totales</small>
            </article>
            
            <article class="card-info">
                <span><?= $totalCarts?></span>
                <small>Carros Totales</small>
            </article>
            
            <article class="card-info">
                <span><?= $totalReservasAulasHoy?></span>
                <small>Reservas de Aulas hoy</small>
            </article>
            
            <article class="card-info">
                <span><?= $totalReservasCarrosHoy?></span>
                <small>Reservas Carros hoy</small>
            </article>

        </section>
    <?php else: ?>  
        <section class= "dashboard-cards">
            <article class="card-info">
                <span class="<?php echo ($totalReservasAulasActivas>0) ? "cant-green": "cant-black";?>"><?= $totalReservasAulasActivas?></span>
                <small>Reservas de Aulas activas</small>
            </article>
            
            <article class="card-info">
                <span><?= $totalReservasAulas?></span>
                <small>Total de Reservas de Aulas</small>
            </article>

            <article class="card-info">
                <span><?= $totalReservasCarrosActivas?></span>
                <small>Reservas de Carros activas</small>
            </article>
            
            <article class="card-info">
                <span><?= $totalReservasCarros?></span>
                <small>Total de Reservas de Carros</small>
            </article>
        </section>
    <?php endif;?>
        
    <?php if($usuario["rol"] =="admin"):?>
        <?php if (!empty($detalleReservasAulasHoy)): ?>
            <section class="dashboard-reservas">
                <h2>Reservas de Aulas para hoy</h2>
                <a href="<?= BASE_URL ?>/config/excel/descargar-reservas-aulas-hoy.php" target="_blank">Descargar Reporte</a>
                <?php foreach ($detalleReservasAulasHoy as $reserva): ?>
                    <article>
                        <h3>Aula: <?= htmlspecialchars($reserva['aula']) ?></h3>
                        <p>Profesor: <?= htmlspecialchars($reserva['usuario']) ?> </p>
                        <p>Hora: <?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</p>
                    </article>
                <?php endforeach ?>
            </section>
        <?php endif;?>

        <?php if (!empty($detalleReservasCarrosHoy)): ?>
            <section class="dashboard-reservas">
                <h2>Reservas de Carros para hoy</h2>
                <a href="<?= BASE_URL ?>/config/excel/descargar-reservas-carros-hoy.php" target="_blank">Descargar Reporte</a>
                <?php foreach ($detalleReservasCarrosHoy as $reserva): ?>
                    <article>
                        <h3>Carro: <?= htmlspecialchars($reserva['carro']) ?></h3>
                        <p>Profesor: <?= htmlspecialchars($reserva['usuario']) ?> </p>
                        <p>Comentario: <?= htmlspecialchars($reserva['comentario']) ?></p>
                    </article>
                <?php endforeach ?>
            </section>
        <?php endif;?>
    <?php else: ?>  
        <?php if (!empty($detalleReservasAulasFuturas)): ?>
            <section class="dashboard-reservas">
                <h2>Próximas Reservas de Aulas</h2>
                <?php foreach ($detalleReservasAulasFuturas as $reserva): ?>
                    <article>
                        <h3>Aula: <?= htmlspecialchars($reserva['aula']) ?></h3>
                        <p>Fecha: <?= htmlspecialchars($reserva['fecha']) ?> </p>
                        <p>Hora: <?= htmlspecialchars($reserva['hora_inicio']) ?>hs - <?= htmlspecialchars($reserva['hora_fin']) ?>hs</p>
                    </article>
                <?php endforeach ?>
            </section>
        <?php endif;?>

        <?php if (!empty($detalleReservasCarrosFuturas)): ?>
            <section class="dashboard-reservas">
                <h2>Próximas Reservas de Carros</h2>
                <?php foreach ($detalleReservasCarrosFuturas as $reserva): ?>
                    <article>
                        <h3>Carro: <?= htmlspecialchars($reserva['carro']) ?></h3>
                        <p>Fecha: <?= htmlspecialchars($reserva['fecha']) ?> </p>
                        <p>Comentario: <?= htmlspecialchars($reserva['comentario']) ?></p>
                    </article>
                <?php endforeach ?>
            </section>
        <?php endif;?>
    <?php endif;?>
</main>

<?php 
    require __DIR__ . '/../components/footer.php';
 ?>