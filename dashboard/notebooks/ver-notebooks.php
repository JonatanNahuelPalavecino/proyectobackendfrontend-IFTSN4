<?php
require_once __DIR__ . "/../../config/functions.php";
require_once __DIR__ . "/../../config/db.php";

$usuario = getUser();

if (!$usuario || $usuario['rol'] !== 'admin') {
    notify('No tenés permisos para ver notebooks.', 'error');
    redirect('/dashboard/');
}

$titulo = "Gestion Notebooks | Reservá tu aula ";

//ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE VER NOTEBOOKS

$getAllNotebooks = getAllNotebooks($pdo);
?>

<?php
require __DIR__ . "/../../components/header.php";
?>

<main>
    <section class= "verReservas-header">
        <article class="vr-containerInfo">
            
            <span class="container-smallEmoji container-smallPcs">
                <svg class="bi bi-pc-display-horizontal small-emoji" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                    <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v7A1.5 1.5 0 0 0 1.5 10H6v1H1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5v-1h4.5A1.5 1.5 0 0 0 16 8.5v-7A1.5 1.5 0 0 0 14.5 0zm0 1h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5M12 12.5a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0m2 0a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0M1.5 12h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M1 14.25a.25.25 0 0 1 .25-.25h5.5a.25.25 0 1 1 0 .5h-5.5a.25.25 0 0 1-.25-.25"/>
                </svg>
            </span>
            
            <div>
                <h2 class="vr-title">Gestión de Notebooks</h2>
                <p class="vr-description">Puedes ver y gestionar todas las notebooks del sistema</p>
            </div>

            <span class="vr-divisor"></span>

            <span class="vr-logo">ADMINISTRADOR</span>

            <div class= "dh-actions">
                <a href="<?= BASE_URL ?>/dashboard/notebooks/crear.php" class="btn btn-gestion-pcs">Nueva Notebook</a>
            </div>
        </article>
    </section>
    
    <section class="verReservas-container">
        <div class="vr-tabla-card">
            <?php if ($getAllNotebooks): ?>
                <table class="vr-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Numero_serie</th>
                            <th class="col-centro">ID_Carro</th>
                            <th>Carro</th>
                            <th class="col-centro">Ultima_Modificacion</th>
                            <th class="col-centro">Acciones</th>
                        </tr>
                    </thead>
                    <?php foreach ($getAllNotebooks as $notebook): ?>
                        <tbody>
                            <tr class="vr-description">
                                <td><?php echo htmlspecialchars($notebook['id']); ?></td>
                                <td><?php echo htmlspecialchars($notebook['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($notebook['numero_serie']); ?></td>
                                <td class="col-centro"><?php echo $notebook['cart_id'] ?? 'Sin asignar'; ?></td>
                                <td><?php echo $notebook['carro'] ?? "Sin asignacion de carro"; ?></td>
                                <td class="col-centro"><?php echo htmlspecialchars($notebook['created_at']); ?></td>
                                <td class="col-centro" >
                                    <div class="verReservas-acciones">
                                        
                                        <a class="btn btn-edit" href="<?php echo BASE_URL ?>/dashboard/notebooks/editar-notebook.php?id=<?php echo $notebook['id']; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                            </svg>
                                        </a>
                                        
                                        <button class="btn btn-delete" popovertarget="eliminar-notebook-<?php echo $notebook['id']; ?>">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                            </svg>
                                            
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <article class="no-results no-results-pcs">
                    <p class="text-sinResultsPcs">No hay notebooks creadas</p>
                </article>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php foreach ($getAllNotebooks as $notebook): ?>
    <section id="eliminar-notebook-<?php echo $notebook['id']; ?>" popover class="popover-eliminar">
        <article class="popover-eliminar-content">
            <h2>Eliminar Carro</h2>
            <p>¿Estas seguro que quieres eliminar la notebook del sistema?</p>
            
            <div class="popover-eliminar-info">
                <p>
                    <strong>ID: </strong>
                    <?= htmlspecialchars($notebook['id']); ?>
                </p>

                <p>
                    <strong>Nombre Notebook: </strong>
                    <?= htmlspecialchars($notebook['nombre']); ?>
                </p>
                <p>
                    <strong>Numero de serie </strong>
                    <?= htmlspecialchars($notebook['numero_serie']); ?>
                </p>    
                
                <p>
                    <strong>Creado: </strong>
                    <?= htmlspecialchars($notebook['created_at']); ?>
                </p> 
                <p>
                    <strong>ID Carro vinculado: </strong>
                    <?php echo $notebook['cart_id'] ?? "Sin asignar"; ?>
                </p> 
                <p>
                    <strong>Nombre de carro vinculado: </strong>
                    <?php echo $notebook['carro'] ?? "Sin asignacion de carro"; ?>
                </p>  

            </div>


            <article class="popover-eliminar-actions">
                <a class="popover-confirmar" href="<?php echo BASE_URL; ?>/dashboard/notebooks/eliminar-notebook.php?id=<?= $notebook['id']; ?>">Eliminar</a>
                <button class="popover-cancelar" type="button" popovertarget="eliminar-notebook-<?php echo $notebook['id']; ?>" popovertargetaction="hide">Cancelar</button>
            </article>
        </article>

    </section>
<?php endforeach; ?>

<?php
require __DIR__ . "/../../components/footer.php";
?>