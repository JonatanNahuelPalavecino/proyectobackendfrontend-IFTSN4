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

    $sql = "SELECT * FROM carts WHERE id =?";
    $consulta =$pdo->prepare($sql);
    $consulta->execute([$id]);
    $carro= $consulta->fetch(PDO::FETCH_ASSOC);

    // Validación: Si el carro no existe, lo pateamos
    if(!$carro){
        notify ("El carro no existe.","error");
        redirect('/dashboard/carros/ver-reservas.php');
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
                redirect('/dashboard/index.php'); 
            } catch (Exception $error) {
                notify("Error al actualizar la base de datos.", "error");
            }
        }
    }
?>

<?php include __DIR__ . "/../../components/header.php"; ?>

<main>
    <h1>🛒 Modificar Carro</h1>

    <form method="POST">
        
        <div>
            <label for="id">ID del Carro (No modificable):</label>
            <input type="text" name="id" id="id" readonly style="cursor: not-allowed;" value="<?= htmlspecialchars($carro['id']) ?>">
        </div>

        <div>
            <label for="nombre">Nombre del Carro:</label>
            <input type="text" name="nombre" id="nombre" minlength="4" required value="<?= htmlspecialchars($carro['nombre']) ?>">
        </div>
        
        <div>
            <label for="descripcion">Descripción (Opcional):</label>
            <textarea name="descripcion" id="descripcion" rows="3"><?= htmlspecialchars($carro['descripcion'] ?? '') ?></textarea>
        </div>
        
        <div>
            <label>Capacidad del Carro:</label>
            <input type="text" readonly style="cursor: not-allowed;" value="<?= htmlspecialchars($carro['capacidad']) ?> notebooks fijas">
        </div>

        <button type="submit">Guardar Cambios</button>
    </form>
</main>

<?php include __DIR__ . "/../../components/footer.php"; ?>