<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para crear aulas.', 'error');
        redirect('/dashboard/');
    }

    $titulo = "Crear Aula | Reservá tu aula";
    
    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE CREAR AULAS

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre_aula = trim($_POST['nombre']);
        $capacidad = intval($_POST['capacidad']);

        $error = validateInputsCreateorEditClassroom($nombre_aula, $capacidad);

        if ($error) {
            notify($error, "error");
        } else {
            try {
                $sql = 'INSERT INTO classrooms (nombre, capacidad) VALUES (?, ?)';
                $crearAula = $pdo->prepare($sql);
                $crearAula->execute([$nombre_aula, $capacidad]);
    
                notify("creacion de aula exitoso.", 'success');
                redirect('/dashboard/aulas/crear-aula.php');
            } catch (Exception $error) {
                die('Error de conexión a la base de datos: ' . $error->getMessage());
            }
        }
    }

?>

<?php
    include __DIR__ . "/../../components/header.php";
?>

<main>
    <h1>Crear Aula</h1>
    <form method="post">
        <p>Ingresa el nombre del aula y su capacidad.</p>
        <div>
            <label for="nombre">Nombre del Aula</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ej minimo: Aula 1" minlength="6" required>
        </div>
        <div>
            <label for="capacidad">Capacidad</label>
            <input type="number" name="capacidad" id="capacidad" placeholder="Ingresa la capacidad del aula" min="0" required>
        </div>  
        <button type="submit">Crear Aula</button>
    </form>
</main>

<?php
    include __DIR__ . "/../../components/footer.php";
?>