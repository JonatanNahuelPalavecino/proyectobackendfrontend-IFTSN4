<?php

    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para administrar la disponibilidad de aulas.', 'error');
        redirect('/dashboard/');
    }

    $titulo = "Crear Disponibilidad de Aula | Reservá tu aula";

    $id = $_GET['id'] ?? '';

    if (!is_numeric($id)) {
        notify('ID de aula invalido.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    }

    $sql = 'SELECT * FROM classrooms WHERE id = ?';
    $consulta = $pdo->prepare($sql);
    $consulta->execute([$id]);
    $aula = $consulta->fetch();

    if (!$aula) {
        notify('El aula no existe.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    };
?>

<?php
    include __DIR__ . "/../../components/header.php";
?>

<main>
    <h1>Crear Disponibilidad de <?php echo $aula['nombre'] ?></h1>

    <form method="post">
        <div>
            <label for="id">ID Aula</label>
            <input style="cursor: not-allowed;" type="text" name="id" id="id" readonly value="<?php echo $aula['id'] ?>">
        </div>
        <div>
            <label for="nombre">nombre Aula</label>
            <input style="cursor: not-allowed;" type="text" name="nombre" id="nombre" value="<?php echo $aula['nombre'] ?>">
        </div>
        <div>
            <label for="capacidad">Disponible desde Dia:</label>
            <select id="dia_desde" name="dia_desde" required>
                <option value="">Seleccionar</option>
                <?php for ($dia = 1; $dia <= 7; $dia++) { ?>
                    <option value="<?php echo $dia; ?>">
                        <?php echo getDayString($dia); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div>
            <label for="created_at">Hasta dia:</label>
            <select id="dia_hasta" name="dia_hasta" required>
                <option value="">Seleccionar</option>
                <?php for ($dia = 1; $dia <= 7; $dia++) { ?>
                    <option value="<?php echo $dia; ?>">
                        <?php echo getDayString($dia); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit">Crear Disponibilidad</button>
    </form>
</main>

<?php
    include __DIR__ . "/../../components/footer.php";
?>