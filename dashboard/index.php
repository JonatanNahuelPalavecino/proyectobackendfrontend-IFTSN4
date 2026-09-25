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

    } else {
        //TOTAL DE RESERVAS ACTIVAS QUE HIZO EL USUARIO
        $cantReservasUser = getCantReservActivas($pdo, $usuario['id']);

        //TOTAL DE RESERVAS QUE HIZO EL USUARIO
        $cantAllReservasUser = getAllCantReserv($pdo, $usuario['id']);

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
                <!-- Falta definir ruta de carros -->
                <div>
                    <a href="<?= BASE_URL?>/dashboard/carros/ver-reservas.php" class="btn reservar">Gestionar Carros</a>
                </div>

                <div>
                    <a href="<?= BASE_URL?>/dashboard/aulas/crear-aula.php" class="btn reservar">+ Aula</a>
                </div>
                
                <div>
                    <a href="<?= BASE_URL?>/dashboard/notebooks/crear.php" class="btn reservar">+ Pcs</a>
                </div>
                
                <div>
                    <a href="<?= BASE_URL?>/dashboard/carros/crear.php" class="btn reservar">+ Carro</a>
                </div>

            <?php else: ?>
                
                <div>
                    <h1>Bienvenido Profesor, <?=htmlspecialchars(ucwords($usuario['nombre']))?>!</h1>
                    <p>Eres un usuario regular. Aquí puedes ver tus reservas y realizar nuevas reservas de aulas.</p>
                </div>
                <div>
                    <a href="<?=BASE_URL?>/dashboard/aulas/crear-reserva.php" class="btn reservar">Reservar aula</a>
                </div>
                <div>
                    <a href="<?= BASE_URL?>/dashboard/carros/ver-reservas.php" class="btn reservar">Gestionar Carros</a>
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
                <span>0</span>
                <small>Reservas hoy</small>
            </article>

        </section>
    <?php else: ?>  
        <section class= "dashboard-cards">
            <article class="card-info">
                <span class="<?php echo ($cantReservasUser>0) ? "cant-green": "cant-black";?>"><?= $cantReservasUser?></span>
                <small>Reservas activas</small>
            </article>
            
            <article class="card-info">
                <span><?= $cantAllReservasUser?></span>
                <small>Total de Reservas</small>
            </article>
                
            <article class="card-info">
                <span class="cant-black">0</span>
                <small>Aulas disponibles</small>
            </article>

        </section>
    <?php endif;?>
        
    <section class="dashboard-section">

    
    </section>
</main>

<?php 
    require __DIR__ . '/../components/footer.php';
 ?>