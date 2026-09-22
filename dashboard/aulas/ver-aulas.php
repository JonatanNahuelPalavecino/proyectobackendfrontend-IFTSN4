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

    $consulta = $pdo->query('SELECT `classrooms`.id, nombre, capacidad, `classrooms`.created_at, dia_desde, dia_hasta, hora_inicio, hora_fin FROM classrooms LEFT JOIN classroom_schedules ON classroom_schedules.classroom_id = classrooms.id ORDER BY `classrooms`.id');
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
                    <th>Disponible desde el dia</th>
                    <th>Hasta el dia</th>
                    <th>Disponible desde el horario</th>
                    <th>Hasta el horario</th>
                    <th colspan="2">Ultima modificacion del aula</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aulas as $aula): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($aula['id']) ?></td>
                        <td><?php echo htmlspecialchars($aula['nombre']) ?></td>
                        <td><?php echo htmlspecialchars($aula['capacidad']) ?></td>
                        <td><?php echo htmlspecialchars( getDayString(intval($aula['dia_desde']))) ?></td>
                        <td><?php echo htmlspecialchars( getDayString(intval($aula['dia_hasta']))) ?></td>
                        <td><?php echo htmlspecialchars($aula['hora_inicio'] ?? "No Seteado") ?></td>
                        <td><?php echo htmlspecialchars($aula['hora_fin']  ?? "No Seteado") ?></td>
                        <td><?php echo htmlspecialchars($aula['created_at']) ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/dashboard/aulas/editar-aula.php?id=<?php echo $aula['id']; ?>">EDITAR AULA</a>
                        </td>
                        <?php if (!$aula['dia_desde'] || !$aula['dia_hasta'] || !$aula['hora_inicio'] || !$aula['hora_fin']): ?>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/dashboard/disponibilidades/crear-disponibilidad.php?id=<?php echo $aula['id']; ?>">CONFIGURAR DISPONIBILIDAD</a>
                            </td>    
                        <?php else: ?>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/dashboard/disponibilidades/editar-disponibilidad.php?id=<?php echo $aula['id']; ?>">EDITAR DISPONIBILIDAD</a>
                            </td>
                        <?php endif; ?>
                        <td>
                            <button popovertarget="eliminar-aula-<?php echo $aula['id'] ?>">ELIMINAR AULA</button>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php foreach ($aulas as $aula): ?>
    <section id="eliminar-aula-<?php echo $aula['id'] ?>" popover>
        <p>¿estas seguro que queres eliminar el <?php echo $aula['nombre'] ?> del sistema?</p>
        <a href="<?php echo BASE_URL; ?>/dashboard/aulas/eliminar-aula.php?id=<?php echo $aula['id']; ?>">Eliminar</a>
        <button type="button" popovertarget="eliminar-aula-<?php echo $aula['id'] ?>" popovertargetaction="hide">Cancelar</button>
    </section>
<?php endforeach ?>

<?php
    include __DIR__ . "/../../components/footer.php";
?>