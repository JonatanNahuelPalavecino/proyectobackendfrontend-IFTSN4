<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para editar aulas.', 'error');
        redirect('/dashboard/aulas/ver-aulas.php');
    }

    $titulo = "Editar Aula | Reservá tu aula";

    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE EDITAR AULAS

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

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $nombre_aula = htmlentities(addslashes(trim($_POST['nombre'])));
        $capacidad = htmlentities(addslashes(intval($_POST['capacidad'])));

        $error = validateInputsCreateorEditClassroom($nombre_aula, $capacidad);

        if ($error) {
            notify($error, "error");
        } else {
            try {
                $sql = 'UPDATE classrooms SET nombre = ?, capacidad = ? WHERE id = ?';
                $editarAula = $pdo->prepare($sql);
                $editarAula->execute([$nombre_aula, $capacidad, $id]);

                notify("Edicion de aula exitoso.");
                redirect('/dashboard/aulas/ver-aulas.php');
            } catch (Exception $error) {
                die('Error de conexión a la base de datos: ' . $error->getMessage());
                // notify("Algun mensje explicando el error", 'error');
                // redirect('/Algunarutraquequieramandarlo');

            }
        }
    };

?>

<?php
    include __DIR__ . "/../../components/header.php";
?>

<main class="form-page">
    <article class="form-header">
        <h1>Editar Aula</h1>
        <p>Modifica el nombre y la capacidad del aula</p>
    </article>

    <form class="create-form" method="post">
        <div class="form-group">
            <label for="id">ID Aula</label>
            <input style="cursor: not-allowed;" type="text" name="id" id="id" readonly value="<?php echo $aula['id'] ?>">
        </div>
        <div class="form-group">
            <label for="nombre">nombre Aula</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $aula['nombre'] ?>">
        </div>
        <div class="form-group">
            <label for="capacidad">Capacidad Aula</label>
            <input type="text" name="capacidad" id="capacidad" value="<?php echo $aula['capacidad'] ?>">
        </div>
        <div class="form-group">
            <label for="created_at">Ultima vez modificado:</label>
            <input style="cursor: not-allowed;" type="text" name="created_at" id="created_at" readonly value="<?php echo $aula['created_at'] ?>">
        </div>

        <div class="form-actions">
            <button class="btn-action" type="submit">Modificar</button>
            <a class="btn-back" href="ver-aulas.php">Volver</a>
        </div>
    </form>
</main>

<?php
    include __DIR__ . "/../../components/footer.php";
?>