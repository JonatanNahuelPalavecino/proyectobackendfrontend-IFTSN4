<?php

require_once __DIR__ . "/config/functions.php";
require_once __DIR__ . "/config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email == "" || $password == "") {
        echo "Por favor, ingrese su correo electrónico y contraseña.";
    } else {
        echo "Correo electrónico: " . $email . "<br>";
        echo "Contraseña: " . $password;
    }
}

$titulo = "Iniciar sesión | Reservá tu aula";

$slides = [
    [
        "imagen" => "assets/images/imagen_instituto.png",
        "texto" => "Reservá tu aula fácilmente"
    ],
    [
        "imagen" => "assets/images/ifts_fondo2.jpg",
        "texto" => "Desarrollado por sus estudiantes"
    ],
];
?>

<?php
include __DIR__ . "/components/header.php";
?>

<div class="login-page">
    <?php
        include "./components/slider.php";
    ?>
    <main>
        <form method="post">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Iniciar sesión</button>
        </form>
    </main>
    <script src="<?= BASE_URL ?>/assets/js/slider.js"></script>
</div>



<?php
include "./components/footer.php";
?>