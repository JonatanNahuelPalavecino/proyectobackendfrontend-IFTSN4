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
    <section>
        <div>
            <h1>Gestion de Notebooks</h1>
            <p>Puedes ver y gestionar todas las notebooks del sistema</p>
        </div>

        <div>
            <?php if($getAllNotebooks):?>
                <table border=2>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Numero_serie</th>
                            <th>Carro</th>
                            <th>Fecha_creacion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                <?php foreach($getAllNotebooks as $notebook): ?>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($notebook['id']);?></td>
                            <td><?php echo htmlspecialchars($notebook['nombre']);?></td>
                            <td><?php echo htmlspecialchars($notebook['numero_serie']);?></td>
                            <td><?php echo htmlspecialchars($notebook['carro']);?></td>
                            <td><?php echo htmlspecialchars($notebook['created_at']);?></td>
                            <td>
                                <a href="<?php echo BASE_URL ?>/dashboard/notebooks/editar-notebook.php?id=<?php echo $notebook['id'];?>">Editar</a>
                                <button popovertarget="eliminar-notebook-<?php echo $notebook['id'];?>">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach;?>
                </table>
            <?php else: ?>
                <p>No hay notebooks creadas</p>
            <?php endif;?>
            <a href="../index.php">Volver</a>
        </div>
    </section>
</main>

<?php foreach($getAllNotebooks as $notebook): ?>
    <section id="eliminar-notebook-<?php echo $notebook['id']; ?>" popover>
        <p>¿Estas seguro que quieres eliminar la notebook '<?php echo $notebook['nombre']; ?>' del sistema?</p>
        <a href="<?php echo BASE_URL;?>/dashboard/notebooks/eliminar-notebook.php?id=<?=$notebook['id'];?>">Eliminar</a>
        <button type="button" popovertarget="eliminar-notebook-<?php echo $notebook['id'];?>" popovertargetaction ="hide">Cancelar</button>
    </section>
<?php endforeach;?>

<?php 
    require __DIR__ . "/../../components/footer.php";
?>