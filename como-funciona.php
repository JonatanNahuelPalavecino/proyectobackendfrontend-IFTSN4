<?php

    require_once __DIR__ . "/config/functions.php";
    require_once __DIR__ . "/config/db.php";

    $titulo = "¿Cómo funciona? | Sistema de Reservas de Aulas";

    include __DIR__ . "/components/header.php";
?>

<main class= "cf">
    <h1 class="cf-title">¿Cómo Funciona?</h1>
    <section class="cf-section">
        <h3 class="cf-subtitle">Para reservar un aula:</h3>
        <article class="cf-article">
            <p class="cf-description">1. Ingresar al sistema con tu usuario y contraseña.</p>
            <p class="cf-description">2. Seleccionar la opción "Reservar Aula" en el menú principal.</p>
            <p class="cf-description">3. Elegir el aula que deseas reservar y la fecha y hora de la reserva.</p>
            <p class="cf-description">4. Confirmar la reserva y recibirás un correo de confirmación.</p>
        </article>  
    </section>
    <section class="cf-section">
        <h3 class="cf-subtitle">Para reservar un Carro:</h3>
        <article class="cf-article">
            <p class="cf-description">1. Ingresar al sistema con tu usuario y contraseña.</p>
            <p class="cf-description">2. Seleccionar la opción "Reservar Carro" en el menú principal.</p>
            <p class="cf-description">3. Elegir el carro que deseas reservar, la fecha y hora de la reserva y agregue un comentario necesario para la configuración de las notebooks.</p>
            <p class="cf-description">4. Confirmar la reserva y recibirás un correo de confirmación.</p>
        </article>  
    </section>
    <section class="cf-section">
        <h3 class="cf-subtitle">Para modificar / cancelar una reserva:</h3>
        <article class="cf-article">
            <p class="cf-description">1. Ingresar al sistema con tu usuario y contraseña.</p>
            <p class="cf-description">2. Seleccionar la opción "Mis Reservas" en el menú principal.</p>
            <p class="cf-description">3. Buscar la reserva que deseas modificar o cancelar y hacer clic en el botón correspondiente.</p>
            <p class="cf-description">4. Confirmar la modificación o cancelación y recibirás un correo de confirmación.</p>
            <p class="cf-description">4. Aplica tanto para aulas y para carros SIEMPRE y cuando no haya pasado la fecha de reserva.</p>
        </article>
    </section>
    <section class="cf-section">
        <h3 class="cf-subtitle">¿Te quedaron algunas dudas adicionales?</h3>
        <article class="cf-article">
            <p class="cf-description">Si tienes alguna pregunta o necesitas ayuda adicional, no dudes en contactarnos por mail a <a class="cf-link" href="mailto:bedelesifts4@gmail.com">bedelesifts4@gmail.com</a></p>
        </article>
    </section>
</main>

<?php
    include "./components/footer.php";
?>