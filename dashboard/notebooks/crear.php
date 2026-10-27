<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/validations.php";
    require_once __DIR__ . "/../../config/db.php";
    require_once __DIR__ . "/../../config/sendMailNotify.php";
    require_once __DIR__ . "/../../config/email/templates/message.php";

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

        $error = validateInputsCreateorEditNotebooks($nombreNotebook, $numeroSerie, $pdo, null, $carro);
        
        if($error){
            notify($error, "error");    
        }else{
            try {
                $sql = "INSERT INTO computers (nombre, numero_serie, cart_id) VALUES (?,?,?)";
                $crearNotebook = $pdo->prepare($sql);
                $crearNotebook->execute([$nombreNotebook, $numeroSerie, $carro]);

                notify("Creación de notebook con éxito");
                redirect("/dashboard/notebooks/ver-notebooks.php");
                // sendMailNotify(
                //     ['nombre' => NAME_SISTEMA, 'email' => MAIL_USERNAME],
                //     'Gestion de notebooks',
                //     notifyOperationNotebookAdmin(
                //     ['title' => '¡Se creó una nueva notebook en el Sistema!', 'subtitle' => 'Los datos de la notebook son:'],
                //     $nombreNotebook,
                //     $numeroSerie,
                //     $carro, 
                //     $carro ? getCartById($pdo, $carro)['nombre'] : null
                //     )   
                // );

            }catch(Exception $error){
                die("Error al insertar en la bd: " . $error->getMessage());
            }
        }
    }
?>

<!------------------------------------------------------------------------------------------------>

<?php 
    include __DIR__ . "/../../components/header.php";
    // var_dump($carro);
?>

<main class="form-page">    
    <div class="form-header">
        <h2 class="form-crudChip form-chipPcs">Nueva Notebook</h2>
        <p class="dh-welcome-label">Ingrese nombre, numero de serie y carro para guardar la notebook</p>
    </div>
    <form method="post" class="create-form">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ej: Notebook Dell 01" minLength="8" required>
        </div>
        
        <div class="form-group"> 
            <label for="numeroSerie">Numero Serie:</label>
            <input type="text" name="numeroSerie" id="numeroSerie" placeholder="Ej: DELL-A-0001"
            pattern="(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9-]+" title="El número de serie debe contener letras y números" minLength="5" required>
        </div>

        <div class="form-group">
            <label for="carros">Carros:</label>

            <?php if($allCarts): ?>
                <select name="carros" id="carros">
                    <option value="">No asignar carro</option>
                    <?php foreach($allCarts as $cart): ?>
                        <option value=<?=htmlspecialchars($cart['id'])?>> <?=htmlspecialchars($cart['nombre'])?> </option>
                    <?php endforeach;?>
                    
                </select>
            <?php else: ?>
                <p class="form-info">No hay carros creados. La notebook se creará sin asignacion.</p>
            <?php endif;?>
        </div>

        <div class="form-actions">
            <button class="btn-action" type="submit">Guardar</button>     
        </div>
    </form>
</main>

<?php 
    include __DIR__ . "/../../components/footer.php";
?>