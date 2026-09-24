<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/db.php";
    require_once __DIR__ . "/../../config/validations.php"; 
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

                notify("Edicion de notebook exitoso");
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

<main>
    <h2>Editar Notebook</h2>

    <form method="post">
        <div>
            <label for="id">ID Notebook</label>
            <input style="cursor: not-allowed;" type="text" name="id" id="id" readonly value="<?= htmlspecialchars($notebook['id']) ?>">
        </div>

        <div>
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ej: Notebook Dell 01" minLength="8" required value ="<?php echo htmlspecialchars($notebook['nombre']);?>">
        </div>
        
        <div>
            <label for="numeroSerie">Numero Serie</label>
                <input  type="text" name="numeroSerie" id="numeroSerie" placeholder="Ej: DELL-A-0001"
            pattern="(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9-]+" title="El número de serie debe contener letras y números" minLength="5" required value ="<?php echo htmlspecialchars($notebook['numero_serie']); ?>">
        </div>
        
        <div>
            <label for="created_at">Ultima vez modificado</label>
            <input style="cursor: not-allowed;" type="text" name="created_at" id="created_at" readonly value="<?php echo htmlspecialchars($notebook['created_at']); ?>">
        </div>

        <div>
            <label for="carros">Carros</label>
            <?php if($getAllCarts): ?>
                <select name="carro" id="carros">
                    <option value="">No asignar carro</option>
                    <?php foreach($getAllCarts as $cart):?>
                        <option value="<?= htmlspecialchars($cart['id']) ?? "";?>" <?= $cart['id'] == $notebook['cart_id'] ? "selected" : "";?> ><?=htmlspecialchars($cart['nombre'])?></option>
                    <?php endforeach;?>
                </select>
            <?php else:?>
                <p>No hay carros creados. La notebook se creará sin asignacion.</p>
            <?php endif;?>

        </div>
            <button type="submit">Guardar</button>
            <a href="ver-notebooks.php" class="btn back">Volver</a>
        </div>

    </form>

</main>

<?php 
    include __DIR__ . "/../../components/footer.php";
?>