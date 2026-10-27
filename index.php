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

<main class= "home">
    <section class="home-content">
        <h1 class="home-title">SIRAUCA</h1>
        
        <span class="home-lineDivisor"></span>


        <h2 class="home-subtitle">Sistema Integral de Reservas de <span class="home-textAulas">Aulas</span> y <span class="home-textCarts">Carros</span></h2>


        <p class="home-description">Gestioná de manera simple y eficiente la administracion, las reservas de aulas y carros para toda la comunidad educativa</p>

        <article class="home-actions" style ="display: flex; gap: 5px;">
            <a class="btn-home" href="<?= BASE_URL ?>/login.php">Ingresar ⮕</a>
            <a class="btn-comoFunciona" href="<?= BASE_URL ?>/como-funciona.php">¿Como funciona?</a>
        </article>

        
    </section>
</main>

<?php
    include "./components/footer.php";
?>