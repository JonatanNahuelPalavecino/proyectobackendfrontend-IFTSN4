<?php
    require_once __DIR__ . "/config/functions.php";
    require_once __DIR__ . "/config/db.php";

    $usuario = getUser();

    if (!$usuario) {
        notify('Debés iniciar sesión para acceder.', 'error');
        redirect('/login.php');
    }

    $titulo = 'Dashboard | Reservá tu aula';
?>

<?php 
    require __DIR__ . '/components/header.php';
 ?>

<main class="dashboard">
    <h1 class="dashboard-title">Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?>!</h1>
    <?php if ($usuario['rol'] === 'admin'): ?>
        <p class="dashboard-text">Eres un administrador. Aquí puedes gestionar usuarios, aulas y reservas.</p>
    <?php else: ?>
        <p class="dashboard-text">Eres un usuario regular. Aquí puedes ver tus reservas
    y realizar nuevas reservas de aulas.</p>
    <?php endif; ?>
</main>

<?php 
    require __DIR__ . '/components/footer.php';
 ?>