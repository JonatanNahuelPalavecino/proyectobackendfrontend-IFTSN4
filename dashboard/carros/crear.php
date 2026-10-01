<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/db.php";


    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para crear reservas de carros.', 'error');
        redirect('/dashboard/index.php');
    }
    
    $titulo = "Crear carro || Reserva tu aula";

    //LOGICA BACK-END -> CREATE carro
    if ($_SERVER['REQUEST_METHOD']=== 'POST'){
        $nombre_carro = trim ($_POST['nombre']);
        $descripcion_carro = trim ($_POST['descripcion']);
        $capacidad = 30;

        if(empty($nombre_carro) || empty($descripcion_carro)){
            notify ("El nombre del carro es obligatorio","error");
        }else{
            try{
                $sql = "INSERT INTO carts (nombre, descripcion,capacidad) VALUES (?,?,?)";
                $crearCarro = $pdo->prepare($sql);
                $crearCarro-> execute([$nombre_carro,$descripcion_carro, $capacidad]);

                notify("Carro '$nombre_carro' creado exitosamente", "success");
                redirect ('/dashboard/carros/crear.php');
            } catch (Exception $error){
                notify ("Hubo un error al guardar en la base de datos.", "error");
            }
        }
    }
?>

<?php include __DIR__ . "/../../components/header.php"; ?>

    <main class ="form-page">
        <div class ="form-header">
            <h1>🛒 Crear Carro</h1> 
            <p>Añade un nuevo carro al sistema. Por defecto, cada carro soporta un máximo de 30 notebooks.</p>
        </div>

        <form method="POST" class ="create-form">
            <div class="form-group">
                <label for="nombre">Identificador del Carro:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ej: Carro Móvil 1" minlength="4" required>
            </div>
        

             <div class ="form-group">
                <label for="descripcion">Descripcion:</label>
                <input type="text" name="descripcion" id="descripcion" placeholder="Ej: Carro para presentacion de proyectos " minlength="4" required>
            </div>
            
            <div class="form-actions">
                <button class="btn-action" type="submit">Guardar</button>     
                <a class="btn-back" href="../index.php" class="btn back">Volver</a>
            </div>
        </form>
    </main>

<?php include __DIR__ . "/../../components/footer.php"; ?>
