<?php

require_once __DIR__ . "/config/functions.php";
require_once __DIR__ . "/config/db.php";

$titulo = "¿Cómo funciona? | SIRAUCA - Sistema de Reserva de Aulas y Carros del IFTS N° 4";
 
include __DIR__ . "/components/header.php";
?>

<main class="cf">
    <article class="cf-titulo">
        <h2>¿Cómo Funciona?</h2>
        <p>Conocé el sistema y aprende a reservar en sencillos pasos</p>
    </article>

    <section class="cf-que-es">
        <article class="cf-siglas">
            S.I.R.AU.CA<small> Sistema Integral de Reservas de Aulas y Carros</small>
        </article>
        
        <article>
            <h3>¿Qué es SIRAUCA?</h3>
            <p>Es un <b>proyecto académico</b> que viene a facilitar la adminstracion de reservas de aulas y carros  para los profesores y el propio instituto. </p>

            <article class="cf-develop">
                <b>Desarrollado por:</b>
                <article class="cf-developers">
                    <span class="cf-developer">Jonatan Palavecino</span>
                    <span class="cf-developer">Damián Luna</span>
                    <span class="cf-developer">Daniel Lopez</span>
                </article>
            </article>
        </article>
    </section>

    <section class="cf-guia guia-aula">
        <article class="cf-guia-head">
        
            <svg class="bi bi-building" xmlns="http://www.w3.org/2000/svg" fill="currentColor"  viewBox="0 0 16 16">
                <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>
            </svg>
            

            <div>
                <h3>Para reservar un Aula</h3>
                <span>4 pasos</span>
            </div>
        </article>

        <article class="cf-guia-cards">

            <div class="cf-card">
                <span>1</span>
                <p>Ingresá al sistema con tu <b>usuario y contraseña</b>.</p>
            </div>
            
            <div class="cf-card">
                <span>2</span>
                <p>Seleccioná la opción <b>"Reservar Aula"</b> en el menú principal.</p>
            </div>
            
            <div class="cf-card">
                <span>3</span>
                <p>Elegí el <b>aula</b> que deseas reservar y la <b>fecha y hora</b> de la reserva.</p>
            </div>
            
            <div class="cf-card">
                <span>4</span>
                <p>Confirmá la reserva y recibirás un <b>correo de confirmación</b>.</p>
            </div>

        </article>
    </section>

    <section class="cf-guia guia-cart">
        
        <article class="cf-guia-head">
            
            <svg style="margin-left: 2px ;" class="bi bi-cart3 " xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
            </svg>

            <div>
                <h3>Para reservar un Carro</h3>
                <span>4 pasos</span>
            </div>

        </article>

        <article class="cf-guia-cards">

            <div class="cf-card">
                <span>1</span>
                <p>Ingresá al sistema con tu <b>usuario y contraseña</b>.</p>
            </div>
            
            <div class="cf-card">
                <span>2</span>
                <p>Seleccioná la opción <b>"Reservar Carro"</b> en el menú principal.</p>
            </div>
            
            <div class="cf-card">
                <span>3</span>
                <p>Elegí el <b>carro</b> que deseas reservar, la <b>fecha y hora</b> de la reserva y agregá un comentario necesario para la configuracion de las notebooks</p>
            </div>
            
            <div class="cf-card">
                <span>4</span>
                <p>Confirmá la reserva y recibirás un <b>correo de confirmación</b>.</p>
            </div>

        </article>
    </section>
    
    <section  class="cf-guia guia-edit">
        
        <article class="cf-guia-head">
            
            <svg style="margin-left: 3px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
            </svg>

            <div>
                <h3>Para Modificar / Cancelar una reserva</h3>
                <span>4 pasos</span>
            </div>

        </article>

        <article class="cf-guia-cards">

            <div class="cf-card">
                <span>1</span>
                <p>Ingresá al sistema con tu <b>usuario y contraseña</b>.</p>
            </div>
            
            <div class="cf-card">
                <span>2</span>
                <p>Seleccioná la opción <b>"Mis Reservas"</b> en el menú principal.</p>
            </div>
            
            <div class="cf-card">
                <span>3</span>
                <p>Buscá la reserva que deseás modificar o cancelar y hacé clic en el <b>botón correspondiente.</b></p>
            </div>
            
            <div class="cf-card">
                <span>4</span>
                <p>Confirmá la modificación o cancelación y recibirás un <b>correo de confirmación</b>.</p>
            </div>

        </article>

        <div class="cf-guia-alert">
            <span>⚠️</span> <p>Aplica tanto para aulas como para carros, <b>siempre y cuando no haya pasado la fecha de reserva</b>.</p>
        </div>
    </section>

    <article class ="cf-dudas">
        <h3>¿Te quedaron algunas dudas adicionales?</h3>
        <p>Si tenés alguna pregunta o necesitás ayuda adicional, no dudes en contactarnos por mail.</p>
        <a href="mailto:sirauca@gmail.com">✉ sirauca@gmail.com</a>
    </article>

</main>

<?php
include "./components/footer.php";
?>