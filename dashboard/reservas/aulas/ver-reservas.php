<?php
    require_once __DIR__ . "/../../../config/db.php";
    require_once __DIR__ . "/../../../config/functions.php";

    $usuario = getUser();

    if(!$usuario){
        notify("Debés iniciar sesion para acceder.", "error");
        redirect("/login.php");
    }

    if($usuario['rol'] === "admin") {
        $titulo = "Total de Reservas | Reservá tu aula";

        //FUNCION QUE TRAE TODAS LAS RESERVAS SIN DIFERENCIAR PROFESOR
    } else {
        $titulo = "Mis Reservas | Reservá tu aula";
    
        //Consultas
        $reservas = getReservasUser($pdo, $usuario['id']);
    }


    //ACA PUEDE ENTRAR ADMIN Y USER, SOLO CAMBIA LO QUE VE CADA UNO
    //ADMIN VE EL TOTAL DE RESERVAS DE AULAS SIN DIFRERENCIAR POR USUARIO, Y EL USER SOLO VE SUS RESERVAS DE AULAS
?>

<?php
    require_once __DIR__ . "/../../../components/header.php";   
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
                                <a href="<?= BASE_URL ?>/dashboard/reservas/aulas/editar-reserva.php?id=<?= $reserva['id'] ?>">Editar</a>
                            <button popovertarget="eliminar-reserva-<?php echo $reserva['id'] ?>">Eliminar</button>
                            </div>

                        </article>
                    <?php endforeach?>

                <?php else: ?>
                    <article>
                        <p>No tienes reservas activas</p>
                        <a href="<?= BASE_URL ?>/dashboard/reservas/aulas/crear-reserva.php">Ir a Reservar</a>
                    </article>
                <?php endif ?>
                
            </div>

        <?php endif ?>
    </section>
</main>

<?php foreach ($reservas as $reserva): ?>
    <section id="eliminar-reserva-<?php echo $reserva['id'] ?>" popover>
        <p>¿estas seguro que queres eliminar del sistema la reserva del <?php echo $reserva['aula'] ?> - Dia: <?php echo $reserva['fecha'] ?> - Horario Inicio: <?php echo $reserva['hora_inicio'] ?> - Horario Fin: <?php echo $reserva['hora_fin'] ?>?</p>
        <a href="<?php echo BASE_URL; ?>/dashboard/reservas/aulas/eliminar-reserva.php?id=<?php echo $reserva['id']; ?>">Eliminar</a>
        <button type="button" popovertarget="eliminar-reserva-<?php echo $reserva['id'] ?>" popovertargetaction="hide">Cancelar</button>
    </section>
<?php endforeach ?>

<?php 
    require_once __DIR__ . "/../../../components/footer.php";

?>


