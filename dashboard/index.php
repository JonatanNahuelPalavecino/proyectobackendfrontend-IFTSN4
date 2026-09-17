<?php
    require_once __DIR__ . "/../config/functions.php";
    require_once __DIR__ . "/../config/db.php";

    $usuario = getUser();

    if (!$usuario) {
        notify('Debés iniciar sesión para acceder.', 'error');
        redirect('/login.php');
    }

    $titulo = 'Dashboard | Reservá tu aula';
    
    //Variables de consultas Usuario
    $cantReservasUser = getCantReservActivas($pdo, $usuario['id']);
    $cantAllReservasUser = getAllCantReserv($pdo, $usuario['id']);

    var_dump($reservas);

    // var_dump($cantReservasUser);

?>

<?php 
    require __DIR__ . '/../components/header.php';
 ?>


<main class="dashboard">
    <div class="dashboard-welcome">
            <?php if ($usuario['rol'] === 'admin'): ?>
                <div>
                    <h1>Bienvenido Administrador , <?= htmlspecialchars(ucfirst($usuario['nombre'])) ?>!</h1>
                    <p>Eres un administrador. Aquí puedes gestionar usuarios, aulas y reservas.</p>    
                </div>
                <div>
                    <a href="<?= BASE_URL?>/dashboard/aulas/" class="btn reservar">+ Nueva Aula</a>
                </div>

            <?php else: ?>
                
                <div>
                    <h1>Bienvenido Profesor, <?=htmlspecialchars(ucfirst($usuario['nombre']))?>!</h1>
                    <p>Eres un usuario regular. Aquí puedes ver tus reservas y realizar nuevas reservas de aulas.</p>
                </div>
                <div>
                    <a href="<?=BASE_URL?>/dashboard/aulas/crear-reserva.php" class="btn reservar">Reservar aula</a>
                </div>
                
            <?php endif; ?> 
    </div>
    <?php if($usuario["rol"] =="admin"):?>
        <section class= "dashboard-cards">
            <article class="card-info">
                <span>0</span>
                <small>Aulas Totales</small>
                
            </article>
                
            <article class="card-info">
                <span>0</span>
                <small>Profesores Registrados</small>
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