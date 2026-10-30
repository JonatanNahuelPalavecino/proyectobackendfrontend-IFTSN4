<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para modificar carros.', 'error');
        redirect('/dashboard/');
    }
    
    $titulo = "Modificar Carro || Reserva tu aula";

    $id =$_GET['id']?? '';


    if(!is_numeric($id)){
        notify ("ID de carro invalido","error");
        redirect("/dashboard/carros/ver-carros.php");
    }

    $sql = "SELECT carts.id, nombre, descripcion, capacidad, cart_reservations.estado FROM carts LEFT JOIN cart_reservations ON cart_reservations.id = (SELECT MAX(cr.id) FROM cart_reservations AS cr WHERE cr.cart_id = carts.id) WHERE carts.id =?";
    $consulta =$pdo->prepare($sql);
    $consulta->execute([$id]);
    $carro= $consulta->fetch(PDO::FETCH_ASSOC);

    // Validación: Si el carro no existe, lo pateamos
    if(!$carro){
        notify ("El carro no existe.","error");
        redirect('/dashboard/carros/ver-carros.php');
    }

    if ($carro['estado'] == "reservado" ) {
        notify ("El carro esta reservado y no puede editarse.","error");
        redirect('/dashboard/carros/ver-carros.php');
    } 

    if ($carro['estado'] == "entregado" ) {
        notify ("El carro esta entregado y no puede editarse.","error");
        redirect('/dashboard/carros/ver-carros.php');
    } 

    if ($_SERVER['REQUEST_METHOD']==="POST"){

        $nombre_carro = trim($_POST['nombre']);
        $descripcion_carro = trim($_POST['descripcion']); 

        if (empty($nombre_carro)) {
            notify("El nombre del carro es obligatorio.", "error");
        } else {
            try {
                $sqlUpdate = 'UPDATE carts SET nombre = ?, descripcion = ? WHERE id = ?';
                $editarCarro = $pdo->prepare($sqlUpdate);
                $editarCarro->execute([$nombre_carro, $descripcion_carro, $id]);

                notify("Carro modificado exitosamente.", "success");
                redirect('/dashboard/carros/ver-carros.php'); 
            } catch (Exception $error) {
                notify("Error al actualizar la base de datos.", "error");
            }
        }
    }
?>

<?php include __DIR__ . "/../../components/header.php"; ?>

<main class="form-page">
    <article class="form-header">
        <span class="form-crudChip form-chipCarts">Editar Carro</span>
        <p class="dh-welcome-label">Modifica el nombre, comentario del carro</p>
    </article>

    <form class="create-form" method="POST">
        
        <div class="form-group">
            <label for="id">Id:</label>
            <input type="text" name="id" id="id" readonly style="cursor: not-allowed;" value="<?= htmlspecialchars($carro['id']) ?>">
        </div>

        <div class="form-group">
            <label for="nombre">Nombre del Carro:</label>
            <input type="text" name="nombre" id="nombre" minlength="4" required value="<?= htmlspecialchars($carro['nombre']) ?>">
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea style="resize: none;" name="descripcion" id="descripcion" rows="3"><?= htmlspecialchars($carro['descripcion'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Capacidad:</label>
            <input type="text" readonly style="cursor: not-allowed;" value="<?= htmlspecialchars($carro['capacidad']) ?> notebooks fijas">
        </div>

        <div class="form-actions"> 
            <button class="btn-action" type="submit">Guardar Cambios</button>
        </div>
    </form>
</main>

<?php include __DIR__ . "/../../components/footer.php"; ?>