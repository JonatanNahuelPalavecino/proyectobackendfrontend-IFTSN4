<?php
    require_once __DIR__ . "/../../../config/functions.php";
    require_once __DIR__ . "/../../../config/db.php";

    $usuario = getUser();

    if (!$usuario || $usuario['rol'] !== 'admin') {
        notify('No tenés permisos para modificar carros.', 'error');
        redirect('/dashboard/');
    }
    
    $titulo = "Modificar Carro || Reserva tu aula";

    // 1. Atrapar el ID del carro que viajó por la URL
    $id =$_GET['id']?? '';


    // Validación: Si el ID no es un número, lo pateamos
    if(!is_numeric($id)){
        notify ("ID de carro invalido","error");
        redirect("/dashboard/carros/ver-reservas.php");
    }

    // 2. Operación READ: Buscamos el carro en la base de datos para rellenar el formulario
    $sql = "SELECT * FROM carts WHERE id =?";
    $consulta =$pdo->prepare($sql);
    $consulta->execute([$id]);
    $carro= $consulta->fetch(PDO::FETCH_ASSOC);

    // Validación: Si el carro no existe, lo pateamos
    if(!$carro){
        notify ("El carro no existe.","error");
        redirect('/dashboard/carros/ver-reservas.php');
    }

    // 3. Operación UPDATE: Lógica para procesar el formulario cuando aprietan "Modificar"
    if ($_SERVER['REQUEST_METHOD']==="POST"){

        $nombre_carro = trim($_POST['nombre']);
        $descripcion_carro = trim($_POST['descripcion']); // Campo opcional que vimos en tu SQL

        if (empty($nombre_carro)) {
            notify("El nombre del carro es obligatorio.", "error");
        } else {
            try {
                // Actualizamos nombre y descripción basándonos en el ID exacto
                $sqlUpdate = 'UPDATE carts SET nombre = ?, descripcion = ? WHERE id = ?';
                $editarCarro = $pdo->prepare($sqlUpdate);
                $editarCarro->execute([$nombre_carro, $descripcion_carro, $id]);

                notify("Carro modificado exitosamente.", "success");
                
                // Ojo de Tech Lead: Asumo que tienes una pantalla "ver-carros.php". 
                // Si no la tienes, redirigimos al dashboard por ahora.
                redirect('/dashboard/index.php'); 
            } catch (Exception $error) {
                notify("Error al actualizar la base de datos.", "error");
            }
        }
    }
?>

<?php include __DIR__ . "/../../components/header.php"; ?>

<main class="dashboard">
    <h2>💻 Modificar Carro</h2>
    <p>Estás editando la información del sistema.</p>

    <!-- Formulario rellenado con los datos del Carro -->
    <form method="POST" class="auth-form" style="max-width: 500px; margin-top: 20px;">
        
        <div class="auth-field">
            <label for="id">ID del Carro (No modificable):</label>
            <!-- Campo de solo lectura para evitar que cambien el ID -->
            <input type="text" name="id" id="id" class="auth-input" readonly style="cursor: not-allowed; background-color: #e9ecef;" value="<?= htmlspecialchars($carro['id']) ?>">
        </div>

        <div class="auth-field">
            <label for="nombre">Nombre del Carro:</label>
            <input type="text" name="nombre" id="nombre" class="auth-input" minlength="4" required value="<?= htmlspecialchars($carro['nombre']) ?>">
        </div>
        
        <div class="auth-field">
            <label for="descripcion">Descripción (Opcional):</label>
            <textarea name="descripcion" id="descripcion" class="auth-input" rows="3"><?= htmlspecialchars($carro['descripcion'] ?? '') ?></textarea>
        </div>
        
        <!-- Recordatorio estático de la capacidad -->
        <div class="auth-field">
            <label>Capacidad del Carro:</label>
            <input type="text" class="auth-input" readonly style="cursor: not-allowed; background-color: #e9ecef;" value="<?= htmlspecialchars($carro['capacidad']) ?> notebooks fijas">
        </div>

        <button type="submit" class="auth-button">Guardar Cambios</button>
    </form>
</main>

<?php include __DIR__ . "/../../components/footer.php"; ?>