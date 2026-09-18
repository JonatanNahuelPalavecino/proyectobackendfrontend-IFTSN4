<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para editar aulas.', 'error');
        redirect('/dashboard/');
    }

    $titulo = "Editar Aula | Reservá tu aula";
    
    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE EDITAR AULAS

    $id = $_GET['id'] ?? '';

    if (!is_numeric($id)) {
        die('ID de aula inválido.');
    }

    $sql = 'SELECT * FROM classrooms WHERE id = ?';
    $consulta = $pdo->prepare($sql);
    $consulta->execute([$id]);
    $aula = $consulta->fetch();

    if (!$aula) {
        die('El aula no existe.');
    };

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $nombre = $_POST['nombre'];
        $capacidad = $_POST['capacidad'];

        echo $nombre;
        echo $capacidad;

        //FALTA AGREGAR LA LOGICA
    };

?>

<?php
    include __DIR__ . "/../../components/header.php";
?>

<main>
    <h1>Editar Aula</h1>

    <form method="post">
        <div>
            <label for="id">ID Aula</label>
            <input type="text" name="id" id="id" readonly value="<?php echo $aula['id'] ?>">
        </div>
        <div>
            <label for="nombre">nombre Aula</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $aula['nombre'] ?>">
        </div>
        <div>
            <label for="capacidad">Capacidad Aula</label>
            <input type="text" name="capacidad" id="capacidad" value="<?php echo $aula['capacidad'] ?>">
        </div>
        <div>
            <label for="created_at">Capacidad Aula</label>
            <input type="text" name="created_at" id="created_at" value="<?php echo $aula['created_at'] ?>">
        </div>
        <button type="submit">Modificar</button>
    </form>
</main>

<?php
    include __DIR__ . "/../../components/footer.php";
?>