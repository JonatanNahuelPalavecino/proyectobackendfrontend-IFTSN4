<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para crear notebooks.', 'error');
        redirect('/dashboard/');
    }

    $titulo = "Crear Notebook | Reservá tu aula";
    
    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE CREAR NOTEBOOKS
    $allCarts = getAllCarts($pdo);
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $nombreNotebook = trim($_POST['nombre']);
        $numeroSerie = trim($_POST['numeroSerie']);
        $carro = trim($_POST['carros']) !== "" ? trim($_POST['carros']) : null; 

        $error = validateInputsCreateorEditNotebooks($nombreNotebook, $numeroSerie, $pdo);
        
        if($error){
            notify($error, "error");    
        }else{
            try {
                $sql = "INSERT INTO computers (nombre, numero_serie, cart_id) VALUES (?,?,?)";
                $crearNotebook = $pdo->prepare($sql);
                $crearNotebook->execute([$nombreNotebook, $numeroSerie, $carro]);

                notify("Creacion de notebook con exito");

            }catch(Exception $error){
                die("Error al insertar en la bd: " . $error->getMessage());
            }
        }
    }
?>

<!------------------------------------------------------------------------------------------------>

<?php 
    include __DIR__ . "/../../components/header.php";
    var_dump($carro);
?>

<main>
    <h1>Crear Notebook</h1>
    <p>Ingrese nombre, numero serie y carro para guardar la notebook</p>
    <form method="post">
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ej: Notebook Dell 01" minLength="8" required>
        </div>
        
        <div>
            <label for="numeroSerie">Numero Serie:</label>
            <input type="text" name="numeroSerie" id="numeroSerie" placeholder="Ej: DELL-A-0001"
            pattern="(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9-]+" title="El número de serie debe contener letras y números" minLength="5" required>
        </div>

        <div>
            <label for="carros">Carros:</label>

            <?php if($allCarts): ?>
                <select name="carros" id="carros">
                    <option value="">No asignar carro</option>
                    <?php foreach($allCarts as $cart): ?>
                        <option value=<?=htmlspecialchars($cart['id'])?>> <?=htmlspecialchars($cart['nombre'])?> </option>
                    <?php endforeach;?>
                    
                </select>
            <?php else: ?>
                <p>No hay carros creados. La notebook se creará sin asignacion.</p>
            <?php endif;?>
        </div>

        <div>
            <button type="submit">Guardar</button>     
            <a href="../index.php" class="btn back">Volver</a>
        </div>
    </form>
</main>

<?php 
    include __DIR__ . "/../../components/footer.php";
?>