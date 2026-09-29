<?php
   require_once __DIR__ . "/../../../config/db.php";
require_once __DIR__ . "/../../../config/functions.php";


    $usuario = getUser();

    // Guardia de seguridad: Debe estar logueado
    if (!$usuario){
        notify("Debes iniciar sesión para acceder.", "error");
        redirect("/login.php");
    }

    if($usuario['rol'] === "admin") {

        $titulo = "Total de Reservas | Reserva tu carro";

        // Vista de Admin: Trae TODAS las reservas del sistema (usando tu nueva función)
        $reservas_admin = getReservasCarrosAdmin($pdo);
    } else {

        $titulo = "Mis reservas | Reserva tu carro";
    
        // Vista de Usuario: Trae solo las reservas activas del profesor logueado
        $reservas_user = getReservasCarrosActivas($pdo, $usuario['id']);
    }
    

?>

<?php require_once __DIR__ . "/../../../components/header.php"; ?>

<main>
    <section>
        
        <?php if ($usuario['rol'] === 'admin'): ?>
            <!-- VISTA DEL ADMINISTRADOR -->
            <div>
                <h1>📊 Reservas de carros del Sistema</h1>
                <p>Todas las reservas de carros del sistema.</p>
            </div> 
            
           <div>
    <?php if (!empty($reservas_admin)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Carro</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Comentarios</th>
                    <th> Editar </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas_admin as $reserva): ?>
                    <tr>
                        <td><?= htmlspecialchars($reserva['carro']) ?></td>
                        <td><?= htmlspecialchars(date("d/m/Y", strtotime($reserva['fecha']))) ?></td>
                        
                        <td>
                            <span style="
                                <?= $reserva['estado'] === 'reservado' ? 'background-color: #fff3cd; color: #856404;' : 
                                ($reserva['estado'] === 'entregado' ? 'background-color: #d4edda; color: #155724;' : 
                                'background-color: #e2e3e5; color: #383d41;') ?>">
                                <?= htmlspecialchars(ucfirst($reserva['estado'])) ?>
                            </span>
                        </td>
                        
                        <td><?= htmlspecialchars($reserva['comentario']) ?></td>
                        

                        <td>
                            <a href="modificar.php?id=<?= $reserva['id'] ?>">Editar</a>
                            
                            <a href="eliminar.php?id=<?= $reserva['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este carro permanentemente?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay reservas de carros en el sistema.</p>
    <?php endif; ?>
</div>

        <?php else: ?>
            <div>
                <h1>💻 Mis reservas | Carros </h1>
                <p>Aquí podrás ver tus reservas de carros activas.</p>
            </div>

            <div>
    <?php if(!empty($reservas_user)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Carro</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Comentarios</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservas_user as $reserva): ?>
                    <tr>
                        <td><?= htmlspecialchars($reserva['carro']) ?></td>
                        <td><?= htmlspecialchars(date("d/m/Y", strtotime($reserva['fecha']))) ?></td>
                        
                    
                        <td>
                            <span style="
                                <?= $reserva['estado'] === 'reservado' ? 'background-color: #fff3cd; color: #856404;' : 
                                   ($reserva['estado'] === 'entregado' ? 'background-color: #d4edda; color: #155724;' : 
                                   'background-color: #e2e3e5; color: #383d41;') ?>">
                                <?= htmlspecialchars(ucfirst($reserva['estado'])) ?>
                            </span>
                        </td>
                        
                        <td><?= htmlspecialchars($reserva['comentario']) ?></td>
                        
                        
                        <td>
                            <a href="<?= BASE_URL ?>/dashboard/reservas/carros/modificar.php?id=<?= $reserva['id'] ?>">Editar</a>
                            <button type="button" popovertarget="eliminar-reserva-<?= $reserva['id'] ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <article>
            <p>No tienes reservas de carros activas.</p>
            <a href="<?= BASE_URL ?>/dashboard/reservas/carros/crear-reserva.php">Ir a Reservar</a>
        </article>
    <?php endif; ?>
</div>
            
        <?php endif; ?>

    </section>

    <?php if ($usuario['rol'] === 'user' && !empty($reservas_user)): ?>
        <?php foreach ($reservas_user as $reserva): ?>
            <section id="eliminar-reserva-<?= $reserva['id'] ?>" popover>
                <p>¿Estás seguro que quieres cancelar tu reserva del <strong><?= htmlspecialchars($reserva['carro']) ?></strong> para el día <?= htmlspecialchars($reserva['fecha']) ?>?</p>
                <a href="<?= BASE_URL ?>/dashboard/reservas/carros/eliminar.php?id=<?= $reserva['id'] ?>">Sí, Cancelar Reserva</a>
                <button type="button" popovertarget="eliminar-reserva-<?= $reserva['id'] ?>" popovertargetaction="hide">No, Volver</button>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

</main>

<?php include __DIR__ . "/../../../components/footer.php"; ?>
