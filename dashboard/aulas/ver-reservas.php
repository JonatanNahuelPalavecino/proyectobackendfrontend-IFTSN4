<?php
    require_once __DIR__ . "/../../config/db.php";
    require_once __DIR__ . "/../../config/functions.php";

    $usuario = getUser();

    if(!$usuario){
        notify("Debés iniciar sesion para acceder.", "error");
        redirect("/login.php");
    }

    $titulo = "Mis Reservas | Reservá tu aula";

    //Consultas
    $reservas = getReservasUser($pdo, $usuario['id']);
    var_dump($reservas);


    //ACA PUEDE ENTRAR ADMIN Y USER, SOLO CAMBIA LO QUE VE CADA UNO
    //ADMIN VE EL TOTAL DE RESERVAS DE AULAS SIN DIFRERENCIAR POR USUARIO, Y EL USER SOLO VE SUS RESERVAS DE AULAS
?>

<?php
    require_once __DIR__ . "/../../components/header.php";   
?>


<main class="mis-reservas">
    <section>
        <?php if($usuario['rol'] === 'admin'): ?>
            <div class ="reservas-header">
                <h1>Reservas del Sistema</h1>
                <p>Todas las reservas del sistema</p>
            </div>



        <?php else:?>
            <div>
                <h1>Mis Reservas</h1>
                <p>Aca podras ver tus reservas activas</p>
            </div>
            <!-- VISTA DE COMO SE VERIA LAS RESERVAS CON LA CONSULTA -->
            <div>
                <?php if(!empty($reservas)):?>
                    <?php foreach($reservas as $reserva): ?>
                        <article>
                            <h3>Nombre aula: <?= htmlspecialchars($reserva['aula'])?></h3>
                            <p>Fecha: <?=htmlspecialchars($reserva['fecha']) ?> </p>
                            <p>
                                Hora: <?=htmlspecialchars($reserva['hora_inicio'])?>hs - <?=htmlspecialchars($reserva['hora_fin']) ?>hs
                            </p>
                            <p>Capacidad: <?=htmlspecialchars($reserva['capacidad']) ?></p>
                            <div>
                                <button>Editar</button>
                                <button>Borrar</button>
                            </div>
                        </article>
                    <?php endforeach?>

                <?php else: ?>
                    <article>
                        <p>No tienes reservas activas</p>
                        <a href="/">Ir a Reservar</a>
                    </article>
                <?php endif ?>
                
            </div>

        <?php endif ?>
    </section>




</main>

<?php 
    require_once __DIR__ . "/../../components/footer.php";

?>