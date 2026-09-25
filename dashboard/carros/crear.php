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

    <main class="dashboard">
        <h2>💻 Crear Nuevo Carro</h2>
        <p>Añade un nuevo carro al sistema. Por defecto, cada carro soporta un máximo de 30 notebooks.</p>

        <!-- Formulario simplificado -->
        <form method="POST" class="auth-form" style="max-width: 500px; margin-top: 20px;">
            <div class="auth-field">
                <label for="nombre">Identificador del Carro:</label>
                <input type="text" name="nombre" id="nombre" class="auth-input" placeholder="Ej: Carro Móvil 1" minlength="4" required>
            </div>
        

             <div class="auth-field">
                <label for="descripcion">Descripcion:</label>
                <input type="text" name="descripcion" id="descripcion" class="auth-input" placeholder="Ej: Carro para presentacion de proyectos " minlength="4" required>
            </div>
            
            <button type="submit" class="auth-button">Guardar Carro</button>
        </form>
    </main>

    <?php include __DIR__ . "/../../components/footer.php"; ?>





?>