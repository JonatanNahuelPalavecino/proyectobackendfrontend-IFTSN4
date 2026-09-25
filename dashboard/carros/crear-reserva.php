<?php
    require_once __DIR__ . "/../../config/functions.php";
    require_once __DIR__ . "/../../config/db.php";

    $usuario = getUser();

    // Guardia de seguridad estricto: Solo pueden entrar Usuarios (Profesores)
    if (!$usuario || $usuario['rol'] !== 'user') {
        notify('No tenés permisos para crear reservas de carros.', 'error');
        redirect('/dashboard/index.php');
    }
    
    // 1. Operación READ delegada a la función
    $carros = getCarrosDisponibles($pdo);

    // 2. Operación CREATE procesada al enviar el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cart_id = trim($_POST['cart_id']);
        $fecha = trim($_POST['fecha']);
        $comentario = trim($_POST['comentario']); 
        $user_id = $usuario['id']; 

        if (empty($cart_id) || empty($fecha) || empty($comentario)) {
            notify("Todos los campos (incluyendo el comentario) son obligatorios", "error");
        } else {
            
            $reservaExitosa = crearReservaCarro($pdo, $user_id, $cart_id, $fecha, $comentario);

            if ($reservaExitosa) {
                notify("Reserva confirmada con éxito", "success");
                redirect('/dashboard/carros/ver-reservas.php'); 
            } else {
                notify("Hubo un problema al procesar la reserva del carro.", "error");
            }
        }
    }

    $titulo = 'Reservar Carro Móvil | IFTS N° 4';
?>

<?php require __DIR__ . '/../../components/header.php'; ?>

<main class="dashboard">
    <h2>💻 Reservar un Carro Móvil</h2>
    <p>Selecciona el carro que necesitas. Recuerda dejar las especificaciones para las notebooks en los comentarios.</p>

    <!-- Formulario limpio y conectado a las funciones -->
    <form method="POST" class="auth-form" style="max-width: 600px; margin-top: 20px;">
        <div class="auth-field">
            <label for="cart_id">Seleccionar Carro Móvil:</label>
            <select name="cart_id" id="cart_id" class="auth-input" required>
                <option value="">--Elige un carro--</option>
                <?php foreach ($carros as $carro): ?>
                    <option value="<?= htmlspecialchars($carro['id']) ?>">
                        <?= htmlspecialchars($carro['nombre']) ?> 
                        (Cap. <?= htmlspecialchars($carro['capacidad']) ?> notebooks)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="auth-field">
            <label for="fecha">Fecha de la reserva:</label>
            <input type="date" name="fecha" id="fecha" class="auth-input" min="<?= date('Y-m-d') ?>" required>   
        </div>


        <div class="auth-field">
            <label for="comentario">Comentario (Configuración necesaria):</label>
            <textarea name="comentario" id="comentario" class="auth-input" rows="3" placeholder="Ej: Necesito que todas tengan AutoCAD instalado..." required></textarea>
        </div>

        <button type="submit" class="auth-button">Confirmar reserva</button>
    </form>      
</main>

<?php require __DIR__ . '/../../components/footer.php'; ?>