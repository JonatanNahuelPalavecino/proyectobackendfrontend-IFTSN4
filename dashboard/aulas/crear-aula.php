<?php
require_once __DIR__ . "/../../config/functions.php";
require_once __DIR__ . "/../../config/validations.php";
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../config/sendMailNotify.php";
require_once __DIR__ . "/../../config/email/templates/message.php";

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

           sendMailNotify(
                ['nombre' => NAME_SISTEMA, 'email' => MAIL_USERNAME],
                "Gestión de aulas",
                notifyOperationAulaAdmin(
                    ['title' => '¡Se creó una nueva aula en el Sistema!', 'subtitle' => 'Los datos del aula son:'],
                    $nombre_aula,
                    $capacidad
                )
           );
            
            notify("Creacion de aula exitoso.", 'success');
            redirect('/dashboard/aulas/ver-aulas.php');
        } catch (Exception $error) {
            die('Error de conexión a la base de datos: ' . $error->getMessage());
        }
    }
}

?>

<?php
include __DIR__ . "/../../components/header.php";
?>

<main class ="form-page">
    <div class="form-header">
        <span class="form-crudChip form-chipAulas">Nueva Aula</span>
        <p class="dh-welcome-label">Ingrese nombre de aula y capacidad</p>
    </div>

    <form method="post" class="create-form">        
        <div class="form-group">
            <label for="nombre">Nombre del Aula</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ej minimo: Aula 1" minlength="6" required>
        </div>

        <div class ="form-group">
            <label for="capacidad">Capacidad</label>
            <input type="number" name="capacidad" id="capacidad" placeholder="Ingresa la capacidad del aula" min="0" required>
        </div>
        
        <div class="form-actions">
            <button class="btn-action" type="submit">Guardar</button>     
            <a class="btn-back" href="ver-aulas.php" >Volver</a>
        </div>
    </form>
</main>

<?php
include __DIR__ . "/../../components/footer.php";
?>