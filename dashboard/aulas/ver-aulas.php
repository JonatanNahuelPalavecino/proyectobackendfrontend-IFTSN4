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

    $consulta = $pdo->query("SELECT classrooms.id, classrooms.nombre, classrooms.capacidad, classrooms.created_at, classroom_schedules.dia_desde, classroom_schedules.dia_hasta, classroom_schedules.hora_inicio, classroom_schedules.hora_fin, CASE WHEN EXISTS (SELECT 1 FROM reservations WHERE reservations.classroom_id = classrooms.id AND reservations.fecha >= CURDATE()) THEN 'reservada' ELSE 'disponible' END AS estado_reserva FROM classrooms LEFT JOIN classroom_schedules ON classroom_schedules.classroom_id = classrooms.id ORDER BY classrooms.id");
    $aulas = $consulta->fetchAll();

?>

<?php
    include __DIR__ . "/../../components/header.php";
?>

<main>
    <section class= "verReservas-header">
        <div class="vr-containerInfo">

            <span class="container-smallEmoji container-smallAulas">
                <svg class="bi bi-building small-emoji" xmlns="http://www.w3.org/2000/svg" fill="currentColor"  viewBox="0 0 16 16">
                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>
                </svg>
            </span>

            <article>
                <h2 class="vr-title">Gestión de Aulas</h2>
                <p class="vr-description">Puedes ver y gestionar todas las aulas del sistema</p>
            </article>

            <span class="vr-divisor"></span>

            <span class="vr-logo">ADMINISTRADOR</span>

            <article class="dh-actions">
                <a href="<?= BASE_URL ?>/dashboard/aulas/crear-aula.php" class="btn btn-gestion-aulas">Nueva Aula</a>
            </article>
        </div>
    </section>

    <section class="verReservas-container" >
        <div class="vr-tabla-card">
            <?php if (count($aulas) === 0): ?>
                <article class="no-results no-results-aulas">
                    <p class="text-sinResultsAulas">No hay aulas disponibles</p>
                </article>
            <?php else: ?>
                <table class="vr-table">
                    <thead>
                        <tr>
                            <th class="col-centro">ID Aula</th>
                            <th>Nombre</th>
                            <th class="col-centro">Capacidad</th>
                            <th>Día (inicio)</th>
                            <th>Día (fin)</th>
                            <th>Hora (inicio)</th>
                            <th>Hora (fin)</th>
                            <th class="col-centro">Modificacion</th>
                            <th class="col-centro">Acciones</th>
                            <th class="col-centro">Disponibilidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aulas as $aula): ?>
                            <tr class="vr-description">
                                <td class="col-centro"><?php echo htmlspecialchars($aula['id']) ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($aula['nombre'])) ?></td>
                                <td class="col-centro"><?php echo htmlspecialchars($aula['capacidad']) ?></td>
                                <td><?php echo htmlspecialchars(getDayString(intval($aula['dia_desde']))) ?></td>
                                <td><?php echo htmlspecialchars(getDayString(intval($aula['dia_hasta']))) ?></td>
                                <td><?php echo htmlspecialchars($aula['hora_inicio'] ?? "No Seteado") ?></td>
                                <td><?php echo htmlspecialchars($aula['hora_fin']  ?? "No Seteado") ?></td>
                                <td class="col-centro"><?php echo htmlspecialchars($aula['created_at']) ?></td>

                                <td class="col-centro">
                                    <div class="verReservas-acciones">
                                    <?php if ($aula['estado_reserva'] === "reservada"): ?>
                                        <span class="btn btn-edit disabled">
    
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                            </svg>
    
                                        </span>
                                        
                                        <span class="btn btn-delete disabled">
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                            </svg>
                                            
                                        </span>
                                    <?php else: ?>
                                        <a class="btn btn-edit" href="<?php echo BASE_URL; ?>/dashboard/aulas/editar-aula.php?id=<?php echo $aula['id']; ?>">
    
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                            </svg>
    
                                        </a>
                                        
                                        <button class="btn btn-delete" popovertarget="eliminar-aula-<?php echo $aula['id'] ?>">
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                            </svg>
                                            
                                        </button>
                                    <?php endif ?>
                                    </div>
                                    
                                </td>
                                
                                <td class="col-centro">
                                    <div class="verReservas-acciones">
                                        <?php if (!$aula['dia_desde'] || !$aula['dia_hasta'] || !$aula['hora_inicio'] || !$aula['hora_fin']): ?>
                                            <?php if ($aula['estado_reserva'] === "reservada"): ?>
                                                <span class="btn btn-setting disabled" href="<?php echo BASE_URL; ?>/dashboard/disponibilidades/crear-disponibilidad.php?id=<?php echo $aula['id']; ?>">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                                                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                                                    </svg>
    
                                                </span>
                                            <?php else: ?>
                                                <a class="btn btn-setting" href="<?php echo BASE_URL; ?>/dashboard/disponibilidades/crear-disponibilidad.php?id=<?php echo $aula['id']; ?>">
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                                                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                                                    </svg>
    
                                                </a>
                                            <?php endif ?>
                                        <?php else: ?>
                                            <?php if ($aula['estado_reserva'] === "reservada"): ?>
                                                <span class="btn btn-edit disabled" href="<?php echo BASE_URL; ?>/dashboard/disponibilidades/editar-disponibilidad.php?id=<?php echo $aula['id']; ?>">
                
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                    </svg>
                                                </span>
                                            <?php else: ?>
                                                <a class="btn btn-edit" href="<?php echo BASE_URL; ?>/dashboard/disponibilidades/editar-disponibilidad.php?id=<?php echo $aula['id']; ?>">
                
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                    </svg>
                                                </a>
                                            <?php endif ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
            
                    </tbody>
                </table>
            <?php endif; ?>
            </div>
    </section>
</main>

<?php foreach ($aulas as $aula): ?>
    <section id="eliminar-aula-<?php echo $aula['id'] ?>" popover class="popover-eliminar">
        <section class="popover-eliminar-content">
            <h2>Eliminar Aula</h2>
            <p>
                ¿Estas seguro que queres eliminar el aula?
            </p>

            <section class="popover-eliminar-info">
                <p>
                    <strong>ID Aula: </strong>
                    <?= htmlspecialchars($aula['id']); ?>
                </p>

                <p>
                    <strong>Nombre Aula: </strong>
                    <?= htmlspecialchars($aula['nombre']); ?>
                </p>
                <p>
                    <strong>Capacidad: </strong>
                    <?= htmlspecialchars($aula['capacidad']); ?>
                </p>    
                
                <p>
                    <strong>Creado: </strong>
                    <?= htmlspecialchars($aula['created_at']); ?>
                </p> 
            </section>

            <section class="popover-eliminar-actions">
                <a class="popover-confirmar" href="<?php echo BASE_URL; ?>/dashboard/aulas/eliminar-aula.php?id=<?php echo $aula['id']; ?>">Eliminar</a>
                <button class="popover-cancelar" type="button" popovertarget="eliminar-aula-<?php echo $aula['id'] ?>" popovertargetaction="hide">Cancelar</button>
            </section>

        </section>        
    </section>
<?php endforeach ?>

<?php
    include __DIR__ . "/../../components/footer.php";
?>