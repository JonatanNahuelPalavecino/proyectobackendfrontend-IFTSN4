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
        $nombreNotebook = htmlentities(addslashes(trim($_POST['nombre'])));
        $numeroSerie = htmlentities(addslashes(trim($_POST['numeroSerie'])));
        $carro = htmlentities(addslashes(trim($_POST['carros'])));

        $error = validateInputsCreateorEditNotebooks($nombreNotebook, $numeroSerie, $carro, $pdo);

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
?>

<main>
    <h1>Crear Notebook</h1>
    <p>Ingrese nombre, numero serie y carro para guardar la notebook</p>
    <form method="post">
        <div>
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ej: Notebook Dell 01" minLength="8" required>
        </div>
        
        <div>
            <label for="numeroSerie">Numero Serie</label>
            <input type="text" name="numeroSerie" id="numeroSerie" placeholder="Ej: DELL-A-0001"
            pattern="(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9-]+" title="El número de serie debe contener letras y números" minLength="5" required>
        </div>

        <div>
            <label for="carros">Carros</label>

            <?php if($allCarts): ?>
                <select name="carros" id="carros" required>
                    <option value="" disabled selected >Seleccione un carro</option>
                    <?php foreach($allCarts as $cart): ?>
                        <option value=<?=htmlspecialchars($cart['id'])?>> <?=htmlspecialchars($cart['nombre'])?> </option>
                    <?php endforeach;?>
                </select>
            <?php else: ?>
                <select style ="cursor: not-allowed" name="carros" id="carros" disabled >
                    <option>No hay carros creados</option>
                </select>
            <?php endif;?>
        </div>

        <div>
        <?php if($allCarts):?>
            <button type="submit">Guardar</button>
        <?php else:?>
            <button style ="cursor: not-allowed" disabled>Guardar</button>
        <?php endif; ?>
            <a href="../index.php" class="btn back">Volver</a>
        </div>
    </form>
</main>

<?php 
    include __DIR__ . "/../../components/footer.php";
?>