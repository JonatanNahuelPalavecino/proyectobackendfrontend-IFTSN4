<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/db.php";
    require_once __DIR__ . "/../../config/validations.php"; 
    require_once __DIR__ . "/../../config/sendMailNotify.php";
    require_once __DIR__ . "/../../config/email/templates/message.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para modificar notebooks.', 'error');
        redirect('/dashboard/');
    }
    
    $idNotebook = $_GET['id'] ?? '';

    if(!is_numeric($idNotebook)){
        notify("Id de notebook inválido", "error");
        redirect('/dashboard/notebook/ver-notebooks.php');
    }

    //variables de consulta a DB
    $notebook = getNotebookById($pdo, $idNotebook);
    $getAllCarts = getAllCarts($pdo);

    if(!$notebook){
        notify("La notebook no existe", "error");
        redirect('/dashboard/notebook/ver-notebooks.php');
    }    


    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $nombreNotebook = trim($_POST['nombre']);
        $numeroSerie = trim($_POST['numeroSerie']);
        $carroSeleccionado = trim($_POST['carro']) !== "" ? trim($_POST['carro']) : null;


        $error= validateInputsCreateorEditNotebooks($nombreNotebook, $numeroSerie, $pdo, $idNotebook);
        
        if($error){
            notify($error, "error");
        }else {
            try {
                $sql = "UPDATE computers SET nombre = ?, numero_serie = ? ,cart_id = ?, created_at = NOW() WHERE id = ? ";
                $consulta = $pdo->prepare($sql);
                $consulta->execute([$nombreNotebook, $numeroSerie, $carroSeleccionado, $idNotebook]);

                $cart = getCartById($pdo, $carroSeleccionado);
                // Notificacion Administrador
                sendMailNotify(
                    ['nombre' => 'Sistema de Reservas', 'email' => 'antruxxi@gmail.com'],
                    'Se ha editado una notebook',
                    notifyOperationNotebookAdmin(
                        ['title' => '¡Se edito una notebook en el Sistema!', 'subtitle' => 'Los datos nuevos de la notebook son:'],
                        $nombreNotebook, 
                        $numeroSerie, 
                        $carroSeleccionado, 
                        $cart ? $cart['nombre'] : null
                    )
                );

                notify("Edición de notebook con éxito");
                redirect('/dashboard/notebooks/ver-notebooks.php');

            }catch(Exception $error){
                die("Error de consulta de base de datos" . $error->getMessage());
            }
        }
    }

    //ACA PUEDE ENTRAR SOLO ADMIN, YA QUE EL USER NO PUEDE MODIFICAR NOTEBOOKS

?>

<?php 
    include __DIR__ . "/../../components/header.php";
?>

<main class="form-page">
    <article class="form-header">
        <h1>💻Editar Notebook</h1>
        <p>Modifica el nombre, numero de serie y carro de la notebook</p>
    </article>

    <form method="post" class="create-form">
        <div class="form-group">
            <label for="id">ID Notebook</label>
            <input style="cursor: not-allowed;" type="text" name="id" id="id" readonly value="<?= htmlspecialchars($notebook['id']) ?>">
        </div>

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ej: Notebook Dell 01" minLength="8" required value ="<?php echo htmlspecialchars($notebook['nombre']);?>">
        </div>
        
        <div class="form-group">
            <label for="numeroSerie">Numero Serie</label>
                <input  type="text" name="numeroSerie" id="numeroSerie" placeholder="Ej: DELL-A-0001"
            pattern="(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9-]+" title="El número de serie debe contener letras y números" minLength="5" required value ="<?php echo htmlspecialchars($notebook['numero_serie']); ?>">
        </div>
        
        <div class="form-group">
            <label for="created_at">Ultima vez modificado</label>
            <input style="cursor: not-allowed;" type="text" name="created_at" id="created_at" readonly value="<?php echo htmlspecialchars($notebook['created_at']); ?>">
        </div>

        <div class="form-group">
            <label for="carros">Carros</label>
            <?php if($getAllCarts): ?>
                <select name="carro" id="carros">
                    <option value="">No asignar carro</option>
                    <?php foreach($getAllCarts as $cart):?>
                        <option value="<?= htmlspecialchars($cart['id']) ?? "";?>" <?= $cart['id'] == $notebook['cart_id'] ? "selected" : "";?> ><?=htmlspecialchars($cart['nombre'])?></option>
                    <?php endforeach;?>
                </select>
            <?php else:?>
                <p class="form-info">No hay carros creados. La notebook se creará sin asignacion.</p>
            <?php endif;?>
        </div >

        <div class="form-actions">
            <button class="btn-action" type="submit">Guardar</button>
            <a class="btn-back" href="ver-notebooks.php">Volver</a>
        </div>

    </form>

</main>

<?php 
    include __DIR__ . "/../../components/footer.php";
?>