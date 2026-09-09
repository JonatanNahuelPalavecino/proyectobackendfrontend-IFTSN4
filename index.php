<?php
    require_once __DIR__ . "/config/functions.php";
    require_once __DIR__ . "/config/db.php";
    // ACA DEBE DE MOSTRARSE LA RUTA PRINCIPAL CON ALGUN BOTON PARA CALL TO ACTION
?>

<?php
    include __DIR__ . "/components/header.php";
?>

<main class= "container-home">
    <h1>Sistema de Reservas de Aulas</h1>
    <div class= "container-img">
        <div class= "img-overlay"></div>
        <img src="<?= BASE_URL ?>/assets/images/ifts_fondo2.jpg">
        <a class="btn-home" href="<?= BASE_URL ?>/login.php">Ingresar</a>
    </div>
</main>

<?php
    include "./components/footer.php";
?>