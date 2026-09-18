<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para administrar aulas.', 'error');
        redirect('/dashboard/');
    }

    $titulo = "Administrar Aulas | Reservá tu aula";
    
    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE ADMINISTRAR AULAS

    $consulta = $pdo->query('SELECT * FROM classrooms ORDER BY id');
    $aulas = $consulta->fetchAll();

?>

<?php
    include __DIR__ . "/../../components/header.php";
?>

<main>
    <h1>Administrar Aulas</h1>

    <?php if (count($aulas) === 0): ?>
        <p>No hay aulas disponibles</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID Aula</th>
                    <th>Nombre</th>
                    <th>Capacidad</th>
                    <th colspan="2">Ultima vez Modificado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aulas as $aula): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($aula['id']) ?></td>
                        <td><?php echo htmlspecialchars($aula['nombre']) ?></td>
                        <td><?php echo htmlspecialchars($aula['capacidad']) ?></td>
                        <td><?php echo htmlspecialchars($aula['created_at']) ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/dashboard/aulas/editar-aula.php?id=<?php echo $aula['id']; ?>">EDITAR</a>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/dashboard/aulas/eliminar-aula.php?id=<?php echo $aula['id']; ?>">ELIMINAR</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php
    include __DIR__ . "/../../components/footer.php";
?>