<?php
    require_once __DIR__ . "/../../config/db.php";
    require_once __DIR__ . "/../../config/functions.php";

    $usuario = getUser();

    // Guardia de seguridad: Debe estar logueado
    if (!$usuario){
        notify("Debes iniciar sesión para acceder.", "error");
        redirect("/login.php");
    }

    $titulo = "Mis reservas || Reserva tu carro";

    // Vista de Usuario: Trae solo las reservas activas del profesor logueado
    $reservas_user = getReservasCarrosActivas($pdo, $usuario['id']);
    
    // Vista de Admin: Trae TODAS las reservas del sistema (usando tu nueva función)
    $reservas_admin = getReservasCarrosAdmin($pdo);

?>

<?php require_once __DIR__ . "/../../components/header.php"; ?>

<main class="mis-reservas">
    <section>
        
        <?php if ($usuario['rol'] === 'admin'): ?>
            <!-- VISTA DEL ADMINISTRADOR -->
            <div class="reservas-header">
                <h1>📊 Reservas del Sistema</h1>
                <p>Todas las reservas de carros del sistema.</p>
            </div> 
            
           <div class="reservas-grid">
    <?php if (!empty($reservas_admin)): ?>
        <table class="table-reservas" border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr>
                    <th style="padding: 10px; background-color: #f4f4f4;">Carro</th>
                    <th style="padding: 10px; background-color: #f4f4f4;">Fecha</th>
                    <th style="padding: 10px; background-color: #f4f4f4;">Estado</th>
                    <th style="padding: 10px; background-color: #f4f4f4;">Comentarios</th>
                    <th style="padding: 10px; background-color: #f4f4f4;"> Editar </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas_admin as $reserva): ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($reserva['carro']) ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars(date("d/m/Y", strtotime($reserva['fecha']))) ?></td>
                        
                        <!-- Coloreado dinámico del estado según la base de datos -->
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <span style="padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 0.9em;
                                <?= $reserva['estado'] === 'reservado' ? 'background-color: #fff3cd; color: #856404;' : 
                                   ($reserva['estado'] === 'entregado' ? 'background-color: #d4edda; color: #155724;' : 
                                   'background-color: #e2e3e5; color: #383d41;') ?>">
                                <?= htmlspecialchars(ucfirst($reserva['estado'])) ?>
                            </span>
                        </td>
                        
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($reserva['comentario']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay reservas de carros en el sistema.</p>
    <?php endif; ?>
</div>

        <?php else: ?>
            <!-- VISTA DEL USUARIO REGULAR (PROFESOR) -->
            <div>
                <h1>💻 Mis reservas || Carros </h1>
                <p>Aquí podrás ver tus reservas de carros activas.</p>
            </div>

            <div class="reservas-grid">
    <?php if(!empty($reservas_user)): ?>
        <table class="table-reservas" border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr>
                    <th style="padding: 10px; background-color: #f4f4f4;">Carro</th>
                    <th style="padding: 10px; background-color: #f4f4f4;">Fecha</th>
                    <th style="padding: 10px; background-color: #f4f4f4;">Estado</th>
                    <th style="padding: 10px; background-color: #f4f4f4;">Comentarios</th>
                    <th style="padding: 10px; background-color: #f4f4f4;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservas_user as $reserva): ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($reserva['carro']) ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars(date("d/m/Y", strtotime($reserva['fecha']))) ?></td>
                        
                        <!-- Coloreado dinámico del estado para el profesor -->
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <span style="padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 0.9em;
                                <?= $reserva['estado'] === 'reservado' ? 'background-color: #fff3cd; color: #856404;' : 
                                   ($reserva['estado'] === 'entregado' ? 'background-color: #d4edda; color: #155724;' : 
                                   'background-color: #e2e3e5; color: #383d41;') ?>">
                                <?= htmlspecialchars(ucfirst($reserva['estado'])) ?>
                            </span>
                        </td>
                        
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($reserva['comentario']) ?></td>
                        
                        <!-- Columna exclusiva del profesor para gestionar sus reservas -->
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <a href="<?= BASE_URL ?>/dashboard/carros/modificar.php?id=<?= $reserva['id'] ?>" class="btn" style="margin-right: 5px;">Editar</a>
                            <button type="button" popovertarget="eliminar-reserva-<?= $reserva['id'] ?>" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <article class="empty-state" style="text-align: center; padding: 20px; background-color: #f9f9f9; border: 1px dashed #ccc;">
            <p>No tienes reservas de carros activas.</p>
            <a href="<?= BASE_URL ?>/dashboard/carros/crear-reserva.php" class="btn">Ir a Reservar</a>
        </article>
    <?php endif; ?>
</div>
            
        <?php endif; ?>

    </section>

    <!-- Componente Popover (Solo para Profesores) -->
    <?php if ($usuario['rol'] === 'user' && !empty($reservas_user)): ?>
        <?php foreach ($reservas_user as $reserva): ?>
            <section id="eliminar-reserva-<?= $reserva['id'] ?>" popover>
                <p>¿Estás seguro que quieres cancelar tu reserva del <strong><?= htmlspecialchars($reserva['carro']) ?></strong> para el día <?= htmlspecialchars($reserva['fecha']) ?>?</p>
                <a href="<?= BASE_URL ?>/dashboard/carros/eliminar.php?id=<?= $reserva['id'] ?>">Sí, Cancelar Reserva</a>
                <button type="button" popovertarget="eliminar-reserva-<?= $reserva['id'] ?>" popovertargetaction="hide">No, Volver</button>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . "/../../components/footer.php"; ?>