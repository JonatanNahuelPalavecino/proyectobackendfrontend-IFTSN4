<?php
    require_once __DIR__ . "/config/functions.php";
    require_once __DIR__ . "/config/db.php";
    
    if (isLoggedIn()) {
        redirect('/dashboard/');
    }
?>

<?php
    include __DIR__ . "/components/header.php";
?>

<main class= "container-home">
    <h1>SIRAUCA</h1>
    <h4>Sistema Integral de Reserva de Aulas y Carros</h4>
    <div class= "container-img">
        <div class= "img-overlay"></div>
        <img src="<?= BASE_URL ?>/assets/images/ifts_fondo2.jpg">
        <a class="btn-home" href="<?= BASE_URL ?>/login.php">Ingresar</a>
    </div>
</main>

<?php
    include "./components/footer.php";
?>